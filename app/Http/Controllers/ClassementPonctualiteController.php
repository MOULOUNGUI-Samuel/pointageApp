<?php

namespace App\Http\Controllers;

use App\Models\Entreprise;
use App\Models\User;
use App\Models\Pointage;
use App\Models\Absence;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ClassementPonctualiteController extends Controller
{
    public function index(Request $request, string $date_start, string $date_end)
    {
        // --- Entreprise + période
        $entreprise_id = session('entreprise_id');
        $entreprise    = Entreprise::findOrFail($entreprise_id);

        try {
            $start = Carbon::parse($date_start)->startOfDay();
            $end   = Carbon::parse($date_end)->endOfDay();
            if ($end->lt($start)) [$start, $end] = [$end, $start];
        } catch (\Throwable $e) {
            abort(422, 'Dates invalides.');
        }

        Carbon::setLocale('fr');

        // --- Utilisateurs actifs de l'entreprise uniquement (comme demandé)
        $users = User::where('entreprise_id', $entreprise_id)
            ->where('statu_user', 1)
            ->where('statut', 1)
            ->get(['id','nom','prenom','fonction']);

        if ($users->isEmpty()) {
            return view('classement.ponctualite', [
                'meta' => $this->meta($entreprise, $start, $end),
                'ranking' => [],
            ]);
        }

        $userIds = $users->pluck('id');

        // --- Params entreprise
        $hStart  = $entreprise->heure_ouverture ?: '08:30:00';
        $hEnd    = $entreprise->heure_fin       ?: '17:30:00';
        $pauseS  = $entreprise->heure_debut_pose; // ou null
        $pauseE  = $entreprise->heure_fin_pose;   // ou null
        $tolMin  = (int)($entreprise->minute_pointage_limite ?? 0);

        // --- Aides jours ouvrés (WE ignorés + fériés FR si dispo)
        $yasumiCache = [];
        $isHoliday = function (Carbon $d) use (&$yasumiCache): bool {
            $y = $d->year;
            if (!isset($yasumiCache[$y])) {
                try { $yasumiCache[$y] = \Yasumi\Yasumi::create('France', $y); }
                catch (\Throwable $e) { $yasumiCache[$y] = null; }
            }
            return $yasumiCache[$y] ? $yasumiCache[$y]->isHoliday($d) : false;
        };
        $isWorkingDay = fn (Carbon $d) => !$d->isWeekend() && !$isHoliday($d);

        // --- Charger pointages & absences sur la période
        $pointages = Pointage::whereIn('user_id', $userIds)
            ->whereBetween('date_arriver', [$start->toDateString(), $end->toDateString()])
            ->orderBy('date_arriver')->orderBy('heure_arriver')
            ->get(['user_id','date_arriver','heure_arriver','heure_fin']);

        $absences  = Absence::whereIn('user_id', $userIds)
            ->where('status','approuvé')
            ->whereDate('start_datetime','<=',$end)
            ->whereDate('end_datetime','>=',$start)
            ->get(['user_id','start_datetime','end_datetime']);

        // --- Indexer absences OK par user/date
        $absOK = []; // [user_id][Y-m-d] => true
        foreach ($absences as $a) {
            $from = Carbon::parse($a->start_datetime)->startOfDay();
            $to   = Carbon::parse($a->end_datetime)->endOfDay();
            if ($from->lt($start)) $from = $start->copy();
            if ($to->gt($end))     $to   = $end->copy();
            for ($d = $from->copy(); $d->lte($to); $d->addDay()) {
                if ($isWorkingDay($d)) {
                    $absOK[$a->user_id][$d->toDateString()] = true;
                }
            }
        }

        // --- Regrouper pointages par user/date (earliest IN, latest OUT)
        $byUserDate = []; // [user_id][date] => [in, out]
        foreach ($pointages as $p) {
            $d = Carbon::parse($p->date_arriver)->toDateString();
            $slot = &$byUserDate[$p->user_id][$d];
            $slot['in']  = empty($slot['in'])  || ($p->heure_arriver && $p->heure_arriver < $slot['in'])  ? $p->heure_arriver : ($slot['in']  ?? null);
            $slot['out'] = empty($slot['out']) || ($p->heure_fin     && $p->heure_fin     > $slot['out']) ? $p->heure_fin     : ($slot['out'] ?? null);
        }

        // --- Agrégats par user
        $agg = []; // user_id => metrics
        foreach ($users as $u) {
            $agg[$u->id] = [
                'heures_net_sec'    => 0,
                'retard_cumule_min' => 0,
                'en_retard'         => 0,
                'absence_injust'    => 0,
            ];
        }

        // helper chevauchement
        $ov = function (?Carbon $a1, ?Carbon $a2, ?Carbon $b1, ?Carbon $b2): int {
            if (!$a1 || !$a2 || !$b1 || !$b2) return 0;
            if ($a2->lte($a1) || $b2->lte($b1)) return 0;
            $s = $a1->gt($b1) ? $a1->copy() : $b1->copy();
            $e = $a2->lt($b2) ? $a2->copy() : $b2->copy();
            return $e->gt($s) ? $e->diffInSeconds($s) : 0;
        };

        // boucle jours ouvrés
        for ($d = $start->copy(); $d->lte($end); $d->addDay()) {
            if (!$isWorkingDay($d)) continue;
            $dStr = $d->toDateString();
            $jourStart = Carbon::parse("$dStr $hStart");
            $jourEnd   = Carbon::parse("$dStr $hEnd");
            $pS        = ($pauseS && $pauseE) ? Carbon::parse("$dStr $pauseS") : null;
            $pE        = ($pauseS && $pauseE) ? Carbon::parse("$dStr $pauseE") : null;

            foreach ($users as $u) {
                $ud = $byUserDate[$u->id][$dStr] ?? null;
                $in  = (!empty($ud['in']))  ? Carbon::parse("$dStr {$ud['in']}")   : null;
                $out = (!empty($ud['out'])) ? Carbon::parse("$dStr {$ud['out']}")  : null;
                $outEff = $out ?: ($in ? $jourEnd : null);

                // Absence injustifiée si ni pointage ni absence OK
                if (!$in && !($absOK[$u->id][$dStr] ?? false)) {
                    $agg[$u->id]['absence_injust']++;
                }

                // Retard / Jours en retard
                if ($in) {
                    $lim = $jourStart->copy()->addMinutes($tolMin);
                    if ($in->gt($lim)) {
                        $agg[$u->id]['en_retard']++;
                        $agg[$u->id]['retard_cumule_min'] += (int) ceil($in->diffInSeconds($lim) / 60);
                    }
                }

                // Heures nettes (chevauchement - pause)
                $worked = $ov($in, $outEff, $jourStart, $jourEnd);
                $pause  = $ov($in, $outEff, $pS, $pE);
                $agg[$u->id]['heures_net_sec'] += max(0, $worked - $pause);
            }
        }

        // --- Scoring & classement
        // poids :  abs injustifiées >> jours en retard >> minutes de retard
        $W_ABS = 10000; $W_DJR = 100; $W_MIN = 1;

        $ranking = $users->map(function($u) use ($agg, $W_ABS, $W_DJR, $W_MIN) {
            $a = $agg[$u->id];
            $score = $a['absence_injust'] * $W_ABS
                   + $a['en_retard']     * $W_DJR
                   + $a['retard_cumule_min'] * $W_MIN;

            return [
                'user_id'           => $u->id,
                'nom'               => $u->nom,
                'prenom'            => $u->prenom,
                'fonction'          => $u->fonction,
                'retards_cumules'   => $a['retard_cumule_min'],            // minutes
                'jours_en_retard'   => $a['en_retard'],
                'abs_injustifiees'  => $a['absence_injust'],
                'heures_trav_min'   => (int) round($a['heures_net_sec']/60),
                'score'             => $score,
            ];
        })->sort(function($x, $y){
            // tri par score ASC, puis heures_trav_min DESC, puis nom
            return [$x['score'], -$x['heures_trav_min'], $x['nom'], $x['prenom']]
                 <=> [$y['score'], -$y['heures_trav_min'], $y['nom'], $y['prenom']];
        })->values()->all();

        // Formater HH:MM pour l'affichage
        foreach ($ranking as &$r) {
            $r['retards_hhmm']  = sprintf('%d:%02d', intdiv($r['retards_cumules'],60), $r['retards_cumules']%60);
            $r['heures_hhmm']   = sprintf('%d:%02d', intdiv($r['heures_trav_min'],60), $r['heures_trav_min']%60);
        }

        return view('classement.ponctualite', [
            'meta'     => $this->meta($entreprise, $start, $end),
            'ranking'  => $ranking,
        ]);
    }

    private function meta($entreprise, Carbon $start, Carbon $end): array
    {
        return [
            'title'   => 'Classement de Ponctualité des Employés',
            'periode' => "Période : {$start->format('d/m/Y')} au {$end->format('d/m/Y')}",
            'company' => $entreprise->nom_entreprise ?? 'Entreprise',
        ];
    }
}
