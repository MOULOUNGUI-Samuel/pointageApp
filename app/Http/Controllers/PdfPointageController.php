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
        // ----- Entreprise + période
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
        $periodeTxt = $start->translatedFormat('l d F Y') . ' / ' . $end->translatedFormat('l d F Y');
        $printedBy  = Auth::user()->nom ?? 'Système';

        // ===================== 1) Constituer l'ensemble complet des user_ids =====================
        // A. tous les utilisateurs de l’entreprise (sans filtrer par statut)
        $activeIds = User::where('entreprise_id', $entreprise_id)
        ->where('statu_user', 1)->where('statut', 1)->pluck('id');

        // B. ceux qui ont pointé dans la période
        $idsFromPointages = Pointage::whereBetween('date_arriver', [$start->toDateString(), $end->toDateString()])
            ->whereHas('user', fn($q) => $q->where('entreprise_id', $entreprise_id))
            ->pluck('user_id');

        // C. ceux qui ont une absence approuvée dans la période
        $idsFromAbsences = Absence::where('status', 'approuvé')
            ->whereHas('user', fn($q) => $q->where('entreprise_id', $entreprise_id))
            ->whereDate('start_datetime', '<=', $end)
            ->whereDate('end_datetime', '>=', $start)
            ->pluck('user_id');

        $allUserIds = $activeIds->merge($idsFromPointages)->merge($idsFromAbsences)->unique()->values();

        // ===== Charge les utilisateurs par ID (sans perdre ceux “inactifs”) =====
        $usersQuery = User::query()->where('statu_user', 1)->where('statut', 1)
            ->whereIn('id', $allUserIds);

        // Si ton modèle User a des Global Scopes (ex. "active"), on peut les retirer :
        // $usersQuery->withoutGlobalScopes(); // décommente si besoin

        // N'utilise withTrashed que si le trait SoftDeletes est présent :
        if (in_array(SoftDeletes::class, class_uses_recursive(User::class), true)) {
            $usersQuery->withTrashed();
        }

        $users = $usersQuery->get(['id', 'nom', 'prenom', 'matricule', 'fonction', 'entreprise_id']);
        $userMap = $users->keyBy('id');

        // ===================== 2) Utilitaires jours ouvrés & paramètres entreprise =====================
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
        $isWorkingDay = function (Carbon $d) use ($isHoliday): bool {
            return !$d->isWeekend() && !$isHoliday($d);
        };

        $heureDebutJour = $entreprise->heure_ouverture;   // "HH:mm:ss"
        $heureFinJour   = $entreprise->heure_fin;         // "HH:mm:ss"
        $pauseDebut     = $entreprise->heure_debut_pose;  // "HH:mm:ss" | null
        $pauseFin       = $entreprise->heure_fin_pose;    // "HH:mm:ss" | null
        $toleranceMin   = (int)($entreprise->minute_pointage_limite ?? 0);

        // ===================== 3) Récupérer pointages/absences (restreints à l'ensemble complet) =====================
        $pointagesRaw = Pointage::with('user:id,nom,prenom,matricule,fonction')
            ->whereIn('user_id', $allUserIds)
            ->whereBetween('date_arriver', [$start->toDateString(), $end->toDateString()])
            ->orderBy('date_arriver')->orderBy('heure_arriver')
            ->get();

        $absences = Absence::with('user:id,nom,prenom,matricule,fonction')
            ->whereIn('user_id', $allUserIds)
            ->where('status', 'approuvé')
            ->whereDate('start_datetime', '<=', $end)
            ->whereDate('end_datetime', '>=', $start)
            ->get();

        // Overlap helper
        $overlapSeconds = function (?Carbon $a1, ?Carbon $a2, ?Carbon $b1, ?Carbon $b2): int {
            if (!$a1 || !$a2 || !$b1 || !$b2) return 0;
            if ($a2->lte($a1) || $b2->lte($b1)) return 0;
            $s = $a1->gt($b1) ? $a1->copy() : $b1->copy();
            $e = $a2->lt($b2) ? $a2->copy() : $b2->copy();
            return $e->gt($s) ? $e->diffInSeconds($s) : 0;
        };

        $datesAvecPointage = []; // [user_id][Y-m-d] => true
        $datesAbsencesOK   = []; // [user_id][Y-m-d] => true
        $agg = [];              // user_id => agrégats

        // ===================== 4) Agrégats à partir des pointages =====================
        foreach ($pointagesRaw as $p) {
            $date = Carbon::parse($p->date_arriver);
            if (!$isWorkingDay($date)) continue;

            $jourStart = $heureDebutJour ? Carbon::parse($date->toDateString() . ' ' . $heureDebutJour) : null;
            $jourEnd   = $heureFinJour   ? Carbon::parse($date->toDateString() . ' ' . $heureFinJour)   : null;
            $pauseS    = ($pauseDebut && $pauseFin) ? Carbon::parse($date->toDateString() . ' ' . $pauseDebut) : null;
            $pauseE    = ($pauseDebut && $pauseFin) ? Carbon::parse($date->toDateString() . ' ' . $pauseFin)   : null;

            $inDT  = $p->heure_arriver ? Carbon::parse($date->toDateString() . ' ' . $p->heure_arriver) : null;
            $outDT = $p->heure_fin     ? Carbon::parse($date->toDateString() . ' ' . $p->heure_fin)     : null;
            $outEff = $outDT ?: ($inDT && $jourEnd ? $jourEnd : null);

            $uid = $p->user_id;
            if (!isset($agg[$uid])) {
                $agg[$uid] = [
                    'heures_net_sec'     => 0,
                    'retard_cumule_min'  => 0,
                    'a_l_heure'          => 0,
                    'en_retard'          => 0,
                    'absence_approuvee'  => 0,
                    'absence_injustifiee' => 0,
                ];
            }

            $worked = $overlapSeconds($inDT, $outEff, $jourStart, $jourEnd);
            $pause  = $overlapSeconds($inDT, $outEff, $pauseS, $pauseE);
            $netSec = max(0, $worked - $pause);
            $agg[$uid]['heures_net_sec'] += $netSec;

            if ($inDT && $jourStart) {
                $limite = $jourStart->copy()->addMinutes($toleranceMin);
                if ($inDT->gt($limite)) {
                    $agg[$uid]['en_retard'] += 1;
                    $agg[$uid]['retard_cumule_min'] += (int) ceil($inDT->diffInSeconds($limite) / 60);
                } else {
                    $agg[$uid]['a_l_heure'] += 1;
                }
            }

            $datesAvecPointage[$uid][$date->toDateString()] = true;
        }

        // ===================== 5) Absences approuvées déroulées =====================
        foreach ($absences as $a) {
            $from = Carbon::parse($a->start_datetime)->startOfDay();
            $to   = Carbon::parse($a->end_datetime)->endOfDay();
            if ($from->lt($start)) $from = $start->copy();
            if ($to->gt($end))     $to   = $end->copy();

            for ($d = $from->copy(); $d->lte($to); $d->addDay()) {
                if (!$isWorkingDay($d)) continue;
                $datesAbsencesOK[$a->user_id][$d->toDateString()] = true;

                if (!isset($agg[$a->user_id])) {
                    $agg[$a->user_id] = [
                        'heures_net_sec' => 0,
                        'retard_cumule_min' => 0,
                        'a_l_heure' => 0,
                        'en_retard' => 0,
                        'absence_approuvee' => 0,
                        'absence_injustifiee' => 0,
                    ];
                }
                $agg[$a->user_id]['absence_approuvee'] += 1;
            }
        }

        // ===================== 6) Absences injustifiées (pour TOUT l'ensemble) =====================
        for ($d = $start->copy(); $d->lte($end); $d->addDay()) {
            if (!$isWorkingDay($d)) continue;
            $dStr = $d->toDateString();

            foreach ($allUserIds as $uid) {
                $hasPointage = !empty($datesAvecPointage[$uid][$dStr]);
                $hasAbsOK    = !empty($datesAbsencesOK[$uid][$dStr]);
                if (!$hasPointage && !$hasAbsOK) {
                    if (!isset($agg[$uid])) {
                        $agg[$uid] = [
                            'heures_net_sec' => 0,
                            'retard_cumule_min' => 0,
                            'a_l_heure' => 0,
                            'en_retard' => 0,
                            'absence_approuvee' => 0,
                            'absence_injustifiee' => 0,
                        ];
                    }
                    $agg[$uid]['absence_injustifiee'] += 1;
                }
            }
        }

        // ===================== 7) Projection en $employees (même sans activité) =====================
        $employees = [];
        foreach ($allUserIds as $uid) {
            $u = $userMap->get($uid);
            // fallback minimal si jamais le user est introuvable (ne devrait pas arriver)
            $nom     = $u->nom     ?? '—';
            $prenom  = $u->prenom  ?? '';
            $mat     = $u->matricule ?? '';
            $fonc    = $u->fonction ?? '';

            $a = $agg[$uid] ?? [
                'heures_net_sec' => 0,
                'retard_cumule_min' => 0,
                'a_l_heure' => 0,
                'en_retard' => 0,
                'absence_approuvee' => 0,
                'absence_injustifiee' => 0,
            ];

            $employees[] = [
                'nom'                  => $nom,
                'prenom'               => $prenom,
                'matricule'            => $mat,
                'fonction'             => $fonc,
                'heures_net_min'       => (int) round($a['heures_net_sec'] / 60),
                'retard_cumule_min'    => (int) $a['retard_cumule_min'],
                'a_l_heure'            => (int) $a['a_l_heure'],
                'en_retard'            => (int) $a['en_retard'],
                'absence_approuvee'    => (int) $a['absence_approuvee'],
                'absence_injustifiee'  => (int) $a['absence_injustifiee'],
            ];
        }

        // Tri alphabétique (Nom, Prénom)
        usort($employees, fn($x, $y) => [$x['nom'], $x['prenom']] <=> [$y['nom'], $y['prenom']]);

        // ===================== 8) Meta (référence, logo, etc.) =====================
        $now = now();
        $ref = sprintf(session('entreprise_code').'-SYN-A%s-%s-%s', $now->format('y'), $now->format('dmy'), $now->format('His'));

        $logoPath = public_path(session('entreprise_logo')
            ? 'storage/' . ltrim(session('entreprise_logo'), '/')
            : 'src/image/logo.png');
$entreprise_code=session('entreprise_code');
        $meta = [
            'title'        => 'RAPPORT DE SYNTHÈSE DES POINTAGES',
            'subtitle'     => "Période : {$start->translatedFormat('F Y')} (du {$start->format('d/m/Y')} au {$end->format('d/m/Y')})",
            'reference'    => $ref,
            'company_name' => $entreprise->nom_entreprise ?? "Entreprise",
            'heure_debutTravail' => $heureDebutJour ?? "08:30:00",
            'company_addr' => $entreprise->adresse ?? "",
            'company_ctc'  => trim("Tél. : " . ($entreprise->telephone ?? "") . " | Email : " . ($entreprise->email ?? "")),
            'logo_path'    => is_file($logoPath) ? $logoPath : null,
            'filename'     => "rapport_pointages_{$entreprise_code}_{$start->format('Ymd')}-{$end->format('Ymd')}.pdf",
            'periode_txt'  => $periodeTxt,
            'printed_by'   => $printedBy,
        ];

        return [$meta, $employees];
    }


    // Impression pour un utilisateur spécifique

    public function streamUser(Request $request, string $userId, string $date_start, string $date_end)
    {
        [$meta, $user, $summary, $rows] = $this->payloadUser($request, $userId, $date_start, $date_end);

        return Pdf::loadView('pdf.fiche_pointage', compact('meta','user','summary','rows'))
            ->setPaper('A4','portrait')
            ->stream($meta['filename']);
    }

    public function downloadUser(Request $request, string $userId, string $date_start, string $date_end)
    {
        [$meta, $user, $summary, $rows] = $this->payloadUser($request, $userId, $date_start, $date_end);

        return Pdf::loadView('pdf.fiche_pointage', compact('meta','user','summary','rows'))
            ->setPaper('A4','portrait')
            ->download($meta['filename']);
    }

    public function saveUser(Request $request, string $userId, string $date_start, string $date_end)
    {
        [$meta, $user, $summary, $rows] = $this->payloadUser($request, $userId, $date_start, $date_end);

        $pdf = Pdf::loadView('pdf.fiche_pointage', compact('meta','user','summary','rows'))
            ->setPaper('A4','portrait');

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
        $user = $userQuery->firstOrFail(['id','nom','prenom','matricule','fonction','entreprise_id']);

        // --- Jours ouvrés (+ Yasumi France par défaut ; adapte si Gabon dispo dans ton projet)
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
            ->get(['date_arriver','heure_arriver','heure_fin']);

        $absences = Absence::where('user_id', $user->id)
            ->where('status','approuvé')
            ->whereDate('start_datetime', '<=', $end)
            ->whereDate('end_datetime', '>=', $start)
            ->get(['start_datetime','end_datetime']);

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
                'net_hm'   => sprintf('%d:%02d', intdiv($netMin,60), $netMin%60),
                'late_mss' => $lateMin > 0 ? sprintf('%02d:%02d', $lateMin, 0) : '--:--',
            ];
        }

        // --- Résumé pour la vue
        $summary = [
            'total_net_hm'  => sprintf('%d:%02d', intdiv($totalNetMin,60), $totalNetMin%60),
            'total_late_hm' => sprintf('%d:%02d', intdiv($totalLateMin,60), $totalLateMin%60),
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
            preg_replace('~\s+~','_', trim($user->nom.'_'.$user->prenom)),
            $start->format('Ymd'),
            $end->format('Ymd')
        );

        $meta = [
            'title'        => 'FICHE DE SYNTHÈSE DE POINTAGE',
            'subtitle'     => "Période du {$start->format('d/m/Y')} au {$end->format('d/m/Y')}",
            'reference'    => $ref,
            'company_name' => $entreprise->nom_entreprise ?? "Entreprise",
            'company_addr' => $entreprise->adresse ?? "",
            'company_ctc'  => trim("Tél. : ".($entreprise->telephone ?? "")." | Email : ".($entreprise->email ?? "")),
            'logo_path'    => is_file($logoPath) ? $logoPath : null,
            'filename'     => $filename,
            'periode_txt'  => $periodeTxt,
            'heure_debut'  => $heureDebutJour,
            'heure_fin'    => $heureFinJour,
            
        ];

        return [$meta, $user, $summary, $rows];
    }
}
