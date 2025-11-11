<?php

namespace App\Http\Controllers;

use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Entreprise;
use App\Models\User;
use App\Models\Pointage;
use App\Models\Absence;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\SoftDeletes;

class PdfPointageController extends Controller
{
    public function stream(Request $request, string $date_start, string $date_end)
    {
        [$meta, $employees] = $this->payload($request, $date_start, $date_end);

        return Pdf::loadView('pdf.rapport_pointages', compact('meta', 'employees'))
            ->setPaper('A4', 'portrait')
            ->stream($meta['filename'] ?? 'rapport_pointages.pdf');
    }

    public function download(Request $request, string $date_start, string $date_end)
    {
        [$meta, $employees] = $this->payload($request, $date_start, $date_end);

        $filename = $meta['filename'] ?? "rapport_pointages_{$date_start}_{$date_end}.pdf";

        return Pdf::loadView('pdf.rapport_pointages', compact('meta', 'employees'))
            ->setPaper('A4', 'portrait')
            ->download($filename);
    }

    public function save(Request $request, string $date_start, string $date_end)
    {
        [$meta, $employees] = $this->payload($request, $date_start, $date_end);

        $filename = $meta['filename'] ?? "rapport_pointages_{$date_start}_{$date_end}.pdf";
        $path     = "rapports/{$filename}";

        $pdf = Pdf::loadView('pdf.rapport_pointages', compact('meta', 'employees'))
            ->setPaper('A4', 'portrait');

        // s’assurer que le dossier existe
        Storage::makeDirectory('rapports');

        Storage::put($path, $pdf->output());

        // message simple (tu peux renvoyer une réponse JSON si besoin)
        return "Sauvegardé: storage/app/{$path}";
    }

    /**
     * Adapte ici: période, logo, et tes données réelles.
     */
    /**
     * Construit $meta et $employees à partir de la même logique que imprimeListePresence().
     */

    private function payload(Request $request, string $date_start, string $date_end): array
    {
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
        $printedBy  = Auth::user()->nom ?? 'Système';
        $periodeTxt = $start->translatedFormat('l d F Y') . ' / ' . $end->translatedFormat('l d F Y');

        // --- Paramètres entreprise (avec défauts sûrs)
        $hStart = $entreprise->heure_ouverture ?: '08:30:00';
        $hEnd   = $entreprise->heure_fin       ?: '17:30:00';
        $pS     = $entreprise->heure_debut_pose;   // ex. "12:30:00" | null
        $pE     = $entreprise->heure_fin_pose;     // ex. "13:30:00" | null
        $tolMin = (int)($entreprise->minute_pointage_limite ?? 0);

        // --- Jours ouvrés (WE exclus, fériés FR optionnels)
        $yasumi = [];
        $isHoliday = function (Carbon $d) use (&$yasumi): bool {
            $y = $d->year;
            if (!isset($yasumi[$y])) {
                try {
                    $yasumi[$y] = \Yasumi\Yasumi::create('France', $y);
                } catch (\Throwable $e) {
                    $yasumi[$y] = null;
                }
            }
            return $yasumi[$y] ? $yasumi[$y]->isHoliday($d) : false;
        };
        $isWorkingDay = fn(Carbon $d) => !$d->isWeekend() && !$isHoliday($d);

        $workingDays = [];
        for ($d = $start->copy(); $d->lte($end); $d->addDay()) {
            if ($isWorkingDay($d)) $workingDays[] = $d->toDateString();
        }
        $workingDaysCount = count($workingDays);

        // --- Ensemble des utilisateurs pris en compte
        $activeIds = User::where('entreprise_id', $entreprise_id)
            ->where('statu_user', 1)->where('statut', 1)->pluck('id');

        $idsFromPointages = Pointage::whereBetween('date_arriver', [$start->toDateString(), $end->toDateString()])
            ->whereHas('user', fn($q) => $q->where('entreprise_id', $entreprise_id))
            ->pluck('user_id');

        $idsFromAbsences = Absence::where('status', 'approuvé')
            ->whereHas('user', fn($q) => $q->where('entreprise_id', $entreprise_id))
            ->whereDate('start_datetime', '<=', $end)
            ->whereDate('end_datetime', '>=', $start)
            ->pluck('user_id');

        $allUserIds = $activeIds->merge($idsFromPointages)->merge($idsFromAbsences)->unique()->values();

        $users = User::query()
            ->whereIn('id', $allUserIds)
            ->get(['id', 'nom', 'prenom', 'matricule', 'fonction']);
        $userMap = $users->keyBy('id');

        // --- Charger données brutes de la période
        $pointages = Pointage::whereIn('user_id', $allUserIds)
            ->whereBetween('date_arriver', [$start->toDateString(), $end->toDateString()])
            ->orderBy('date_arriver')->orderBy('heure_arriver')
            ->get(['user_id', 'date_arriver', 'heure_arriver', 'heure_fin']);

        $absences  = Absence::whereIn('user_id', $allUserIds)
            ->where('status', 'approuvé')
            ->whereDate('start_datetime', '<=', $end)
            ->whereDate('end_datetime', '>=', $start)
            ->get(['user_id', 'start_datetime', 'end_datetime']);

        // --- Index absences projetées jour par jour
        $absOK = []; // [user_id][Y-m-d] => true
        foreach ($absences as $a) {
            $from = Carbon::parse($a->start_datetime)->startOfDay();
            $to   = Carbon::parse($a->end_datetime)->endOfDay();
            if ($from->lt($start)) $from = $start->copy();
            if ($to->gt($end))     $to   = $end->copy();
            for ($d = $from->copy(); $d->lte($to); $d->addDay()) {
                $dStr = $d->toDateString();
                if (in_array($dStr, $workingDays, true)) {
                    $absOK[$a->user_id][$dStr] = true;
                }
            }
        }

        // --- Regrouper pointages par user+date => earliest IN / latest OUT
        $inOut = []; // [user_id][Y-m-d] => ['in' => 'HH:mm:ss', 'out' => 'HH:mm:ss']
        foreach ($pointages as $p) {
            $dStr = Carbon::parse($p->date_arriver)->toDateString();
            if (!in_array($dStr, $workingDays, true)) continue;

            $slot = &$inOut[$p->user_id][$dStr];
            if (!isset($slot['in']) || ($p->heure_arriver && $p->heure_arriver < $slot['in'])) {
                $slot['in'] = $p->heure_arriver;
            }
            if (!isset($slot['out']) || ($p->heure_fin && $p->heure_fin > $slot['out'])) {
                $slot['out'] = $p->heure_fin;
            }
        }

        // --- Helper chevauchement
        $ov = function (?Carbon $a1, ?Carbon $a2, ?Carbon $b1, ?Carbon $b2): int {
            if (!$a1 || !$a2 || !$b1 || !$b2) return 0;
            if ($a2->lte($a1) || $b2->lte($b1)) return 0;
            $s = $a1->gt($b1) ? $a1->copy() : $b1->copy();
            $e = $a2->lt($b2) ? $a2->copy() : $b2->copy();
            return $e->gt($s) ? $e->diffInSeconds($s) : 0;
        };

        // --- Préparer agrégats
        $agg = []; // user_id => metrics
        foreach ($allUserIds as $uid) {
            $agg[$uid] = [
                'heures_net_sec'     => 0,
                'retard_cumule_min'  => 0,
                'a_l_heure'          => 0,
                'en_retard'          => 0,
                'absence_approuvee'  => 0,
                'absence_injustifiee' => 0,
            ];
        }

        // minutes attendues par jour pour l’entreprise (bornage + pause)
        $dailyExpectedMinutes = (function () use ($start, $hStart, $hEnd, $pS, $pE) {
            $d = $start->copy();
            $JS = Carbon::parse($d->toDateString() . ' ' . $hStart);
            $JE = Carbon::parse($d->toDateString() . ' ' . $hEnd);
            $work = max(0, $JE->diffInSeconds($JS));
            $pause = 0;
            if ($pS && $pE) {
                $PS = Carbon::parse($d->toDateString() . ' ' . $pS);
                $PE = Carbon::parse($d->toDateString() . ' ' . $pE);
                $pause = $ov = max(0, $PE->diffInSeconds($PS));
            }
            return (int) round(($work - $pause) / 60);
        })();

        // --- Boucle principale: pour chaque jour ouvré et chaque user
        foreach ($workingDays as $dStr) {
            $JS = Carbon::parse("$dStr $hStart");
            $JE = Carbon::parse("$dStr $hEnd");
            $PS = ($pS && $pE) ? Carbon::parse("$dStr $pS") : null;
            $PE = ($pS && $pE) ? Carbon::parse("$dStr $pE") : null;
            $lim = $JS->copy()->addMinutes($tolMin);

            foreach ($allUserIds as $uid) {
                // 1) Absence approuvée (prioritaire)
                if (!empty($absOK[$uid][$dStr])) {
                    $agg[$uid]['absence_approuvee']++;
                    continue;
                }

                // 2) Présence (on borne la sortie à l’heure de fin si non renseignée)
                $io = $inOut[$uid][$dStr] ?? null;
                $in  = $io && !empty($io['in'])  ? Carbon::parse("$dStr {$io['in']}")  : null;
                $out = $io && !empty($io['out']) ? Carbon::parse("$dStr {$io['out']}") : null;
                $outEff = $out ?: ($in ? $JE : null);

                if ($in) {
                    // statut retard/à l’heure
                    if ($in->gt($lim)) {
                        $agg[$uid]['en_retard']++;
                        $agg[$uid]['retard_cumule_min'] += (int) ceil($in->diffInSeconds($lim) / 60);
                    } else {
                        $agg[$uid]['a_l_heure']++;
                    }

                    // heures nettes: (in,outEff) ∩ (JS,JE) – pause
                    $worked = $ov($in, $outEff, $JS, $JE);
                    $pause  = $ov($in, $outEff, $PS, $PE);
                    $agg[$uid]['heures_net_sec'] += max(0, $worked - $pause);
                } else {
                    // 3) Pas de pointage ni absence OK -> injustifiée
                    $agg[$uid]['absence_injustifiee']++;
                }
            }
        }

        // --- Projection pour la vue (garanti : somme des statuts == nb_jours_ouvres)
        $employees = [];
        foreach ($allUserIds as $uid) {
            $u = $userMap->get($uid);
            $a = $agg[$uid];

            // Sécurité : réaligner si un décalage s’est produit
            $sumDays = $a['a_l_heure'] + $a['en_retard'] + $a['absence_approuvee'] + $a['absence_injustifiee'];
            if ($sumDays !== $workingDaysCount) {
                // ajuste l’injustifiée (jamais négatif)
                $a['absence_injustifiee'] = max(0, $a['absence_injustifiee'] + ($workingDaysCount - $sumDays));
            }

            $employees[] = [
                'nom'                  => $u->nom ?? '—',
                'prenom'               => $u->prenom ?? '',
                'matricule'            => $u->matricule ?? '',
                'fonction'             => $u->fonction ?? '',
                'heures_net_min'       => (int) round($a['heures_net_sec'] / 60),
                'retard_cumule_min'    => (int) $a['retard_cumule_min'],
                'a_l_heure'            => (int) $a['a_l_heure'],
                'en_retard'            => (int) $a['en_retard'],
                'absence_approuvee'    => (int) $a['absence_approuvee'],
                'absence_injustifiee'  => (int) $a['absence_injustifiee'],
            ];
        }
        usort($employees, fn($x, $y) => [$x['nom'], $x['prenom']] <=> [$y['nom'], $y['prenom']]);

        // --- Meta + contrôles entreprise
        $now      = now();
        $ref      = sprintf('%s-SYN-A%s-%s-%s', session('entreprise_code'), $now->format('y'), $now->format('dmy'), $now->format('His'));
        $logoPath = public_path(session('entreprise_logo') ? 'storage/' . ltrim(session('entreprise_logo'), '/') : 'src/image/logo.png');

        $meta = [
            'title'        => 'RAPPORT DE SYNTHÈSE DES POINTAGES',
            'subtitle'     => "Période : {$start->translatedFormat('F Y')} (du {$start->format('d/m/Y')} au {$end->format('d/m/Y')})",
            'reference'    => $ref,
            'company_name' => $entreprise->nom_entreprise ?? "Entreprise",
            'company_addr' => $entreprise->adresse ?? "",
            'company_ctc'  => trim("Tél. : " . ($entreprise->telephone ?? "") . " | Email : " . ($entreprise->email ?? "")),
            'logo_path'    => is_file($logoPath) ? $logoPath : null,
            'filename'     => "rapport_pointages_" . (session('entreprise_code') ?? 'ENT') . "_{$start->format('Ymd')}-{$end->format('Ymd')}.pdf",
            'periode_txt'  => $periodeTxt,
            'printed_by'   => $printedBy,

            // Contrôles / totaux entreprise
            'working_days_count'            => $workingDaysCount,
            'heure_debutTravail'            => $hStart,
            'heure_finTravail'              => $hEnd,
            'daily_expected_minutes'        => $dailyExpectedMinutes,
            'company_expected_minutes_total' => $dailyExpectedMinutes * $workingDaysCount,
        ];

        return [$meta, $employees];
    }


    // Impression pour un utilisateur spécifique

    public function streamUser(Request $request, string $userId, string $date_start, string $date_end)
    {
        [$meta, $user, $summary, $rows] = $this->payloadUser($request, $userId, $date_start, $date_end);

        return Pdf::loadView('pdf.fiche_pointage', compact('meta', 'user', 'summary', 'rows'))
            ->setPaper('A4', 'portrait')
            ->stream($meta['filename']);
    }

    public function downloadUser(Request $request, string $userId, string $date_start, string $date_end)
    {
        [$meta, $user, $summary, $rows] = $this->payloadUser($request, $userId, $date_start, $date_end);

        return Pdf::loadView('pdf.fiche_pointage', compact('meta', 'user', 'summary', 'rows'))
            ->setPaper('A4', 'portrait')
            ->download($meta['filename']);
    }

    public function saveUser(Request $request, string $userId, string $date_start, string $date_end)
    {
        [$meta, $user, $summary, $rows] = $this->payloadUser($request, $userId, $date_start, $date_end);

        $pdf = Pdf::loadView('pdf.fiche_pointage', compact('meta', 'user', 'summary', 'rows'))
            ->setPaper('A4', 'portrait');

        Storage::makeDirectory('rapports');
        Storage::put("rapports/{$meta['filename']}", $pdf->output());

        return "Sauvegardé: storage/app/rapports/{$meta['filename']}";
    }

    /**
     * FICHE INDIVIDUELLE : construit les données pour 1 utilisateur sur une période.
     */
    private function payloadUser(Request $request, string $userId, string $date_start, string $date_end): array
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
        $printedBy  = Auth::user()->nom ?? 'Système';
        $periodeTxt = $start->translatedFormat('l d F Y') . ' / ' . $end->translatedFormat('l d F Y');

        // --- Récupérer l'utilisateur cible (même s'il est inactif/soft-deleted)
        $userQuery = User::query()->where('id', $userId)->where('entreprise_id', $entreprise_id)->where('statu_user', 1)->where('statut', 1);
        if (in_array(SoftDeletes::class, class_uses_recursive(User::class), true)) {
            $userQuery->withTrashed();
        }
        $user = $userQuery->firstOrFail(['id', 'nom', 'prenom', 'matricule', 'fonction', 'entreprise_id']);

        // --- Jours ouvrés (+ Yasumi France par défaut ; adapte si Gabon dispo dans ton projet)
        $yasumiCache = [];
        $isHoliday = function (Carbon $d) use (&$yasumiCache): bool {
            $y = $d->year;
            if (!isset($yasumiCache[$y])) {
                try {
                    $yasumiCache[$y] = \Yasumi\Yasumi::create('France', $y);
                } catch (\Throwable $e) {
                    $yasumiCache[$y] = null;
                }
            }
            return $yasumiCache[$y] ? $yasumiCache[$y]->isHoliday($d) : false;
        };
        $isWorkingDay = fn(Carbon $d) => !$d->isWeekend() && !$isHoliday($d);

        // --- Paramètres entreprise
        $heureDebutJour = $entreprise->heure_ouverture ?: '08:30:00';
        $heureFinJour   = $entreprise->heure_fin       ?: '17:30:00';
        $pauseDebut     = $entreprise->heure_debut_pose;  // ex "12:30:00" ou null
        $pauseFin       = $entreprise->heure_fin_pose;    // ex "13:30:00" ou null
        $toleranceMin   = (int)($entreprise->minute_pointage_limite ?? 0);

        // --- Récupérer pointages & absences du user
        $pointages = Pointage::where('user_id', $user->id)
            ->whereBetween('date_arriver', [$start->toDateString(), $end->toDateString()])
            ->orderBy('date_arriver')->orderBy('heure_arriver')
            ->get(['date_arriver', 'heure_arriver', 'heure_fin']);

        $absences = Absence::where('user_id', $user->id)
            ->where('status', 'approuvé')
            ->whereDate('start_datetime', '<=', $end)
            ->whereDate('end_datetime', '>=', $start)
            ->get(['start_datetime', 'end_datetime']);

        // --- Helper chevauchement
        $overlapSec = function (?Carbon $a1, ?Carbon $a2, ?Carbon $b1, ?Carbon $b2): int {
            if (!$a1 || !$a2 || !$b1 || !$b2) return 0;
            if ($a2->lte($a1) || $b2->lte($b1)) return 0;
            $s = $a1->gt($b1) ? $a1->copy() : $b1->copy();
            $e = $a2->lt($b2) ? $a2->copy() : $b2->copy();
            return $e->gt($s) ? $e->diffInSeconds($s) : 0;
        };

        // --- Indexer absences approuvées par date
        $absenceOK = []; // Y-m-d => true
        foreach ($absences as $a) {
            $from = Carbon::parse($a->start_datetime)->startOfDay();
            $to   = Carbon::parse($a->end_datetime)->endOfDay();
            if ($from->lt($start)) $from = $start->copy();
            if ($to->gt($end))     $to   = $end->copy();
            for ($d = $from->copy(); $d->lte($to); $d->addDay()) {
                if ($isWorkingDay($d)) $absenceOK[$d->toDateString()] = true;
            }
        }

        // --- Regrouper pointages par jour (earliest IN, latest OUT)
        $byDate = [];
        foreach ($pointages as $p) {
            $d = Carbon::parse($p->date_arriver)->toDateString();
            $byDate[$d] ??= ['in' => null, 'out' => null];
            if (!empty($p->heure_arriver)) {
                $in = Carbon::parse("$d {$p->heure_arriver}");
                $byDate[$d]['in'] = $byDate[$d]['in'] ? min($byDate[$d]['in'], $in) : $in;
            }
            if (!empty($p->heure_fin)) {
                $out = Carbon::parse("$d {$p->heure_fin}");
                $byDate[$d]['out'] = $byDate[$d]['out'] ? max($byDate[$d]['out'], $out) : $out;
            }
        }

        // --- Boucle jours ouvrés et calculs
        $totalNetMin   = 0;
        $totalLateMin  = 0;
        $countOnTime   = 0;
        $countLate     = 0;
        $countAbsOK    = 0;
        $countAbsInj   = 0;

        $rows = []; // pour le détail quotidien

        for ($d = $start->copy(); $d->lte($end); $d->addDay()) {
            if (!$isWorkingDay($d)) continue;
            $dStr = $d->toDateString();
            $jourStart = Carbon::parse("$dStr $heureDebutJour");
            $jourEnd   = Carbon::parse("$dStr $heureFinJour");
            $pauseS    = ($pauseDebut && $pauseFin) ? Carbon::parse("$dStr $pauseDebut") : null;
            $pauseE    = ($pauseDebut && $pauseFin) ? Carbon::parse("$dStr $pauseFin")   : null;

            $in  = $byDate[$dStr]['in']  ?? null;
            $out = $byDate[$dStr]['out'] ?? null;
            $outEff = $out ?: ($in ? $jourEnd : null);

            // Statut + retards
            $statut = '';
            $lateMin = 0;

            if ($absenceOK[$dStr] ?? false) {
                $statut = 'Absence approuvée';
                $countAbsOK++;
            } elseif ($in) {
                $limite = $jourStart->copy()->addMinutes($toleranceMin);
                if ($in->gt($limite)) {
                    $statut  = 'En retard';
                    $lateMin = (int) ceil($in->diffInSeconds($limite) / 60);
                    $countLate++;
                } else {
                    $statut = "À l'heure";
                    $countOnTime++;
                }
            } else {
                $statut = 'Absent injustifié';
                $countAbsInj++;
            }

            // Heures nettes (chevauchement - pause)
            $worked = $overlapSec($in, $outEff, $jourStart, $jourEnd);
            $pause  = $overlapSec($in, $outEff, $pauseS, $pauseE);
            $netMin = (int) max(0, ($worked - $pause) / 60);

            $totalNetMin  += $netMin;
            $totalLateMin += $lateMin;

            // Formatage
            $fmtHMS = fn(?Carbon $t) => $t ? $t->format('H:i:s') : '--:--:--';
            $rows[] = [
                'date_txt' => $d->translatedFormat('d/m/Y'),
                'jour_abr' => $d->translatedFormat('D'), // Lun, Mar, ...
                'in'       => $fmtHMS($in),
                'out'      => $fmtHMS($outEff),
                'statut'   => $statut,
                'net_hm'   => sprintf('%d:%02d', intdiv($netMin, 60), $netMin % 60),
                'late_mss' => $lateMin > 0 ? sprintf('%02d:%02d', $lateMin, 0) : '--:--',
            ];
        }

        // --- Résumé pour la vue
        $summary = [
            'total_net_hm'  => sprintf('%d:%02d', intdiv($totalNetMin, 60), $totalNetMin % 60),
            'total_late_hm' => sprintf('%d:%02d', intdiv($totalLateMin, 60), $totalLateMin % 60),
            'a_l_heure'     => $countOnTime,
            'en_retard'     => $countLate,
            'absence_ok'    => $countAbsOK,
            'absence_inj'   => $countAbsInj,
            'total_jours'   => count($rows),
        ];

        // --- Meta & logo
        $now = now();
        $entreprise_code = (string) (session('entreprise_code') ?: 'BFEV');
        $ref = sprintf('%s-PT-A%s-%s-%s', $entreprise_code, $now->format('y'), $now->format('dmy'), $now->format('His'));

        $logoPath = public_path(session('entreprise_logo')
            ? 'storage/' . ltrim(session('entreprise_logo'), '/')
            : 'src/image/logo.png');

        $filename = sprintf(
            'fiche_pointage_%s_%s_%s.pdf',
            preg_replace('~\s+~', '_', trim($user->nom . '_' . $user->prenom)),
            $start->format('Ymd'),
            $end->format('Ymd')
        );

        $meta = [
            'title'        => 'FICHE DE SYNTHÈSE DE POINTAGE',
            'subtitle'     => "Période du {$start->format('d/m/Y')} au {$end->format('d/m/Y')}",
            'reference'    => $ref,
            'company_name' => $entreprise->nom_entreprise ?? "Entreprise",
            'company_addr' => $entreprise->adresse ?? "",
            'company_ctc'  => trim("Tél. : " . ($entreprise->telephone ?? "") . " | Email : " . ($entreprise->email ?? "")),
            'logo_path'    => is_file($logoPath) ? $logoPath : null,
            'filename'     => $filename,
            'periode_txt'  => $periodeTxt,
            'heure_debut'  => $heureDebutJour,
            'heure_fin'    => $heureFinJour,

        ];

        return [$meta, $user, $summary, $rows];
    }
}
