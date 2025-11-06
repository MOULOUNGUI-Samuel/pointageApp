<?php

namespace App\Http\Controllers;

use App\Models\Entreprise;
use App\Models\User;
use App\Models\Pointage;
use App\Models\Absence;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ClassementPonctualiteController extends Controller
{
    public function index(Request $request, string $date_start, string $date_end)
    {
        // 1) Période & entreprise
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

        // 2) Utilisateurs actifs
        $users = User::where('entreprise_id', $entreprise_id)
            ->where('statu_user', 1)
            ->where('statut', 1)
            ->get(['id','nom','prenom','fonction']);

        if ($users->isEmpty()) {
            return view('classement.ponctualite', [
                'meta'    => $this->meta($entreprise, $start, $end),
                'ranking' => [],
            ]);
        }

        $userIds = $users->pluck('id');

        // 3) Paramètres entreprise
        $hStart = $entreprise->heure_ouverture ?: '08:30:00';
        $hEnd   = $entreprise->heure_fin       ?: '17:30:00';
        $pS     = $entreprise->heure_debut_pose;   // 'HH:mm:ss' ou null
        $pE     = $entreprise->heure_fin_pose;     // 'HH:mm:ss' ou null
        $tolMin = (int)($entreprise->minute_pointage_limite ?? 0);

        // 4) Outils jours ouvrés
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

        // 5) Absences approuvées (déroulées par jour)
        $absences = Absence::whereIn('user_id', $userIds)
            ->where('status', 'approuvé')
            ->whereDate('start_datetime','<=', $end)
            ->whereDate('end_datetime','>=', $start)
            ->get(['user_id','start_datetime','end_datetime']);

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

        // 6) Pointages agrégés par jour: earliest IN / latest OUT
        //    -> plus robuste qu’un tri en mémoire quand il y a plusieurs passages
        $pointages = Pointage::select([
                'user_id',
                'date_arriver',
                DB::raw('MIN(heure_arriver) as in_time'),
                DB::raw('MAX(heure_fin)     as out_time'),
            ])
            ->whereIn('user_id', $userIds)
            ->whereBetween('date_arriver', [$start->toDateString(), $end->toDateString()])
            ->groupBy('user_id','date_arriver')
            ->orderBy('date_arriver')
            ->get();

        // Index: [user_id][Y-m-d] => ['in'=>'HH:mm:ss','out'=>'HH:mm:ss']
        $byUserDate = [];
        foreach ($pointages as $p) {
            $d = Carbon::parse($p->date_arriver)->toDateString();
            $byUserDate[$p->user_id][$d] = [
                'in'  => $p->in_time,
                'out' => $p->out_time,
            ];
        }

        // 7) Agrégats par user
        $agg = [];
        foreach ($users as $u) {
            $agg[$u->id] = [
                'heures_net_sec'    => 0,
                'retard_cumule_min' => 0,
                'en_retard'         => 0,
                'absence_injust'    => 0,
            ];
        }

        // helper chevauchement (en secondes)
        $overlap = function (?Carbon $a1, ?Carbon $a2, ?Carbon $b1, ?Carbon $b2): int {
            if (!$a1 || !$a2 || !$b1 || !$b2) return 0;
            if ($a2->lte($a1) || $b2->lte($b1)) return 0;
            $s = $a1->gt($b1) ? $a1 : $b1;
            $e = $a2->lt($b2) ? $a2 : $b2;
            return $e->gt($s) ? $e->diffInSeconds($s) : 0;
        };

        // 8) Boucle jours ouvrés
        for ($d = $start->copy(); $d->lte($end); $d->addDay()) {
            if (!$isWorkingDay($d)) continue;
            $dStr = $d->toDateString();

            $jourStart = Carbon::parse("$dStr $hStart");
            $jourEnd   = Carbon::parse("$dStr $hEnd");
            $pauseS    = ($pS && $pE) ? Carbon::parse("$dStr $pS") : null;
            $pauseE    = ($pS && $pE) ? Carbon::parse("$dStr $pE") : null;

            foreach ($users as $u) {
                $slot = $byUserDate[$u->id][$dStr] ?? null;
                $in   = ($slot && $slot['in'])  ? Carbon::parse("$dStr {$slot['in']}")  : null;
                $out  = ($slot && $slot['out']) ? Carbon::parse("$dStr {$slot['out']}") : null;
                $outEff = $out ?: ($in ? $jourEnd : null); // si pas de sortie, borne à heure de fin

                // Absence injustifiée : aucun pointage et pas d'absence approuvée
                if (!$in && !($absOK[$u->id][$dStr] ?? false)) {
                    $agg[$u->id]['absence_injust']++;
                }

                // Retard: arrivée > (heure_ouverture + tolérance)
                if ($in) {
                    $limite = $jourStart->copy()->addMinutes($tolMin);
                    if ($in->gt($limite)) {
                        $agg[$u->id]['en_retard']++;
                        $agg[$u->id]['retard_cumule_min'] += (int) ceil($in->diffInSeconds($limite)/60);
                    }
                }

                // Heures nettes = chevauchement [in,outEff]∩[jourStart,jourEnd] - chevauchement pause
                $worked = $overlap($in, $outEff, $jourStart, $jourEnd);
                $pause  = $overlap($in, $outEff, $pauseS, $pauseE);
                $agg[$u->id]['heures_net_sec'] += max(0, $worked - $pause);
            }
        }

        // 9) Score + classement
        $W_ABS = 10000; $W_DJR = 100; $W_MIN = 1;   // poids (ajuste si besoin)

        $ranking = $users->map(function($u) use ($agg, $W_ABS, $W_DJR, $W_MIN) {
            $a     = $agg[$u->id];
            $score = $a['absence_injust'] * $W_ABS
                   + $a['en_retard']     * $W_DJR
                   + $a['retard_cumule_min'] * $W_MIN;

            return [
                'user_id'           => $u->id,
                'nom'               => $u->nom,
                'prenom'            => $u->prenom,
                'fonction'          => $u->fonction,
                'retards_cumules'   => $a['retard_cumule_min'],          // minutes
                'jours_en_retard'   => $a['en_retard'],
                'abs_injustifiees'  => $a['absence_injust'],
                'heures_trav_min'   => (int) round($a['heures_net_sec']/60),
                'score'             => $score,
            ];
        })->sort(function($x,$y){
            // score ASC (meilleur = plus petit) puis heures travaillées DESC, puis nom
            return [$x['score'], -$x['heures_trav_min'], $x['nom'], $x['prenom']]
                 <=> [$y['score'], -$y['heures_trav_min'], $y['nom'], $y['prenom']];
        })->values()->all();

        // Formats HH:MM
        foreach ($ranking as &$r) {
            $r['retards_hhmm'] = sprintf('%d:%02d', intdiv($r['retards_cumules'],60), $r['retards_cumules']%60);
            $r['heures_hhmm']  = sprintf('%d:%02d', intdiv($r['heures_trav_min'],60), $r['heures_trav_min']%60);
        }

        return view('classement.ponctualite', [
            'meta'    => $this->meta($entreprise, $start, $end),
            'ranking' => $ranking,
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
