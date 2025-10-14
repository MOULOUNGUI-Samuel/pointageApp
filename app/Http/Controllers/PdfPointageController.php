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
        $activeIds = User::where('entreprise_id', $entreprise_id)->pluck('id');

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
        $usersQuery = User::query()
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

        $meta = [
            'title'        => 'RAPPORT DE SYNTHÈSE DES POINTAGES',
            'subtitle'     => "Période : {$start->translatedFormat('F Y')} (du {$start->format('d/m/Y')} au {$end->format('d/m/Y')})",
            'reference'    => $ref,
            'company_name' => $entreprise->nom_entreprise ?? "Entreprise",
            'company_addr' => $entreprise->adresse ?? "",
            'company_ctc'  => trim("Tél. : " . ($entreprise->telephone ?? "") . " | Email : " . ($entreprise->email ?? "")),
            'logo_path'    => is_file($logoPath) ? $logoPath : null,
            'filename'     => "rapport_pointages_{$start->format('Ymd')}-{$end->format('Ymd')}.pdf",
            'periode_txt'  => $periodeTxt,
            'printed_by'   => $printedBy,
        ];

        return [$meta, $employees];
    }
}
