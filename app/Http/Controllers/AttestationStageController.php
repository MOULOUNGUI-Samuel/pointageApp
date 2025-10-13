<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Entreprise;
// use App\Pdf\PdfAttestationStage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Services\AttendanceService;


class AttestationStageController extends Controller
{
    /** Utilitaire format monétaire CFA */
    private function money($n): string
    {
        return number_format((float)$n, 0, ',', ' ');
    }

    public function attestationStage(Request $request, string $userId, string $ticketPeriode)
    {
        Carbon::setLocale('fr');

        // ---- Base ----
        $user    = User::findOrFail($userId);
        $company = Entreprise::findOrFail($user->entreprise_id);

        $period = DB::table('periodes_paie')->where('ticket', $ticketPeriode)->first();
        abort_if(!$period, 404, 'Période introuvable');

        $pStart = Carbon::parse($period->date_debut)->startOfDay();
        $pEnd   = Carbon::parse($period->date_fin)->endOfDay();

        // ---- Détails paie (ta fonction existante) ----
        $pay = app(self::class)->generateDetailedPayrollData($userId, $ticketPeriode); // si la fonction est sur ce contrôleur
        // Si la fonction est ailleurs, appelle-la là où elle vit (ex: service)

        // Allocation “net”
        $allocationNet = null;
        foreach (($pay['rows'] ?? []) as $r) {
            if (($r['type'] ?? '') === 'gain' && !empty($r['gain_salarial'])) {
                $label = mb_strtolower((string)($r['designation'] ?? ''), 'UTF-8');
                if (str_contains($label, 'allocation') || str_contains($label, 'gratification')) {
                    $allocationNet = (float)$r['gain_salarial'];
                    break;
                }
            }
        }
        if ($allocationNet === null) {
            $allocationNet = (float)($user->salaire ?? $user->salairebase ?? 0);
            if (!$allocationNet && isset($pay['totals']['Net imposable'])) {
                $allocationNet = (float)$pay['totals']['Net imposable'];
            }
        }

        // Transport
        $transport = 0.0;
        foreach (($pay['rows'] ?? []) as $r) {
            if (($r['type'] ?? '') === 'gain' && !empty($r['gain_salarial'])) {
                $label = mb_strtolower((string)($r['designation'] ?? ''), 'UTF-8');
                if (str_contains($label, 'transport')) $transport += (float)$r['gain_salarial'];
            }
        }

        // Autres avantages non imposables hors transport
        $autres = 0.0;
        foreach (($pay['rows'] ?? []) as $r) {
            if (($r['type'] ?? '') === 'gain' && !empty($r['gain_salarial'])) {
                $label = mb_strtolower((string)($r['designation'] ?? ''), 'UTF-8');
                if (!str_contains($label, 'transport') && (int)($r['variableImposable'] ?? 1) === 0) {
                    $autres += (float)$r['gain_salarial'];
                }
            }
        }

        // Jours de présence distincts
        $presenceDays = DB::table('pointages')
            ->where('user_id', $userId)
            ->whereBetween('date_arriver', [$pStart->toDateString(), $pEnd->toDateString()])
            ->select(DB::raw('DATE(date_arriver) as d'))
            ->distinct()->pluck('d')->count();

        // Mois concerné
        $moisLib = ucfirst($pStart->isoFormat('MMMM YYYY'));

        // ---- Instanciation PDF ----
        $pdf = new PdfAttestationStage();
        // $pdf->logoPath = public_path('images/logo-entreprise.png'); // si dispo

        // Entreprise
        $pdf->entreprise = [
            'nom'          => $company->nom_entreprise ?? '[NOM ENTREPRISE]',
            'adresse'      => (string)$request->input('entreprise_adresse', '[ADRESSE COMPLÈTE]'),
            'ville'        => (string)$request->input('entreprise_ville', 'Libreville'),
            'tel'          => (string)$request->input('entreprise_tel', '[TÉLÉPHONE]'),
            'email'        => (string)$request->input('entreprise_email', '[EMAIL]'),
            'representant' => (string)$request->input('representant', '[Nom et Prénom du représentant]'),
            'fonction'     => (string)$request->input('fonction_representant', '[Fonction]'),
        ];

        // Stagiaire
        $nomComplet = trim(($user->nom ?? '') . ' ' . ($user->prenom ?? ''));
        $pdf->stagiaire = [
            'nom'       => (string)$request->input('stagiaire_nom', $nomComplet ?: '[NOM PRÉNOM]'),
            'formation' => (string)$request->input('stagiaire_formation', $user->competence ?? '[FILIÈRE/SPÉCIALITÉ]'),
            'etab'      => (string)$request->input('stagiaire_etablissement', '[NOM ÉCOLE/UNIVERSITÉ]'),
            'niveau'    => (string)$request->input('stagiaire_niveau', $user->niveau_etude ?? '[NIVEAU D’ÉTUDES]'),
            'service'   => (string)$request->input('stagiaire_service', '[SERVICE/DÉPARTEMENT]'),
            'periode'   => 'Du ' . $pStart->format('d/m/Y') . ' au ' . $pEnd->format('d/m/Y'),
        ];

        // Versement
        $total = $allocationNet + $transport + $autres;
        $pdf->versement = [
            'mois'          => $moisLib,
            'jours'         => (string)$presenceDays,
            'allocation'    => $this->money($allocationNet),
            'transport'     => $transport > 0 ? $this->money($transport) : '-',
            'autres'        => $autres > 0 ? $this->money($autres) : '-',
            'total'         => $this->money($total),
            'mode'          => (string)$request->input('mode_paiement', $user->mode_paiement ?: 'Virement / Chèque / Espèces'),
            'dateVersement' => (string)$request->input('date_versement', Carbon::now()->format('d/m/Y')),
            'reference'     => (string)$request->input('reference_paiement', '[RÉFÉRENCE SI VIREMENT]'),
        ];

        // Meta
        $pdf->meta = [
            'reference'               => 'ATT-STAGE-' . strtoupper($ticketPeriode),
            'dateEmission'            => Carbon::now()->format('d/m/Y'),
            'dateConvention'          => (string)$request->input('date_convention', '[DATE DE LA CONVENTION]'),
            'nbExemplaires'           => (string)$request->input('nb_exemplaires', '2'),
            'dateSignatureEntreprise' => (string)$request->input('date_signature_entreprise', Carbon::now()->format('d/m/Y')),
            'dateSignatureStagiaire'  => (string)$request->input('date_signature_stagiaire', Carbon::now()->format('d/m/Y')),
            'villeFooter'             => (string)$request->input('ville_footer', 'Libreville'),
            'dateFooter'              => Carbon::now()->format('d/m/Y'),
        ];

        // Build & Stream
        $pdf->build();

        return response($pdf->Output('S'), 200, [
            'Content-Type'        => 'application/pdf',
            'Content-Disposition' => 'inline; filename="attestation-allocation-stage.pdf"',
        ]);
    }

    /* 
     * >>>> Ta fonction existe déjà plus haut dans ton contrôleur.
     * Si elle est dans un autre service, appelle-le à la place.
     * public function generateDetailedPayrollData(string $userId, string $ticketPeriode): array { ... }
     */
    function generateDetailedPayrollData(string $userId, string $tiketPeriode): array
    {
        // 1) Période
        $period = DB::table('periodes_paie')->where('ticket', $tiketPeriode)->first();
        if (!$period) return [];

        $periodStart = Carbon::parse($period->date_debut)->startOfDay();
        $periodEnd   = Carbon::parse($period->date_fin)->endOfDay();

        // 2) Variables
        $variables = DB::table('variables')
            ->select([
                'id',
                'numeroVariable',
                'nom_variable',
                'tauxVariable',
                'tauxVariableEntreprise',
                'type',
                'statutVariable',
                'variableImposable'
            ])
            ->orderBy('numeroVariable')->get()->keyBy('id');

        // 3) Montants saisis pour l’utilisateur sur la période
        $montants = DB::table('variable_periode_users')
            ->where('user_id', $userId)
            ->where('periode_paie_id', $period->id)
            ->pluck('montant', 'variable_id')->toArray();

        // 4) Associations
        $assocs = [];
        foreach (DB::table('variable_associers')->select(['variableBase_id', 'variableAssocier_id'])->get() as $r) {
            $assocs[$r->variableBase_id][] = $r->variableAssocier_id;
        }

        // 5) Salaire de base (ligne statique n°99)
        /** @var User $user */
        $user = User::find($userId);
        $baseSalary = (float)($user->salairebase ?? 0);

        // 6) Accumulateurs
        $preBrut = [];     // à afficher AVANT “Total Brut” (imposables + salaire base)
        $cotis   = [];     // cotisations (déductions statutVariable = 1)
        $autres  = [];     // autres (avantages non imposables, TCS, …)

        $totalBrut         = 0.0;
        $cotisBaseTotal    = 0.0; // << demandé : somme des bases de cotisations
        $retenuesSalTotal  = 0.0;
        $retenuesPatTotal  = 0.0;
        $avantagesNature   = 0.0;

        // 6.a) Salaire de base
        if ($baseSalary > 0) {
            $preBrut[] = [
                'numero' => 99,
                'designation' => 'Salaire de base',
                'base' => round($baseSalary, 2),
                'taux_salarial' => null,
                'gain_salarial' => round($baseSalary, 2),
                'retenue_sal' => null,
                'taux_patronal' => null,
                'retenue_pat' => null,
                'type' => 'gain',
                'statutVariable' => 0,
                'variableImposable' => 1,
            ];
            $totalBrut += $baseSalary;
        }

        // 6.b) Variables
        foreach ($variables as $id => $v) {
            // Base = somme des associés si présents sinon son propre montant
            $base = 0.0;
            if (!empty($assocs[$id])) {
                foreach ($assocs[$id] as $assocId) {
                    $base += (float)($montants[$assocId] ?? 0);
                }
            } else {
                $base = (float)($montants[$id] ?? 0);
            }

            // ignorer si rien de pertinent
            if ($base == 0 && (is_null($v->tauxVariable) || (float)$v->tauxVariable == 0)) {
                continue;
            }

            $tSal = is_null($v->tauxVariable)           ? null : (float)$v->tauxVariable;
            $tPat = is_null($v->tauxVariableEntreprise) ? null : (float)$v->tauxVariableEntreprise;

            $gainSal = null;
            $retSal = null;
            $retPat = null;

            if ($v->type === 'gain') {
                $gainSal = $tSal !== null ? round($base * $tSal / 100, 2) : round($base, 2);
                $totalBrut += $gainSal;

                $line = [
                    'numero'         => $v->numeroVariable,
                    'designation'    => $v->nom_variable,
                    'base'           => round($base, 2),
                    'taux_salarial'  => $tSal,
                    'gain_salarial'  => $gainSal,
                    'retenue_sal'    => null,
                    'taux_patronal'  => $tPat,
                    'retenue_pat'    => null,
                    'type'           => 'gain',
                    'statutVariable' => (int)$v->statutVariable,
                    'variableImposable' => (int)$v->variableImposable,
                ];

                if ((int)$v->variableImposable === 1) {
                    $preBrut[] = $line; // imposables AVANT Total Brut
                } else {
                    $avantagesNature += $gainSal;
                    $autres[] = $line;  // non imposables ailleurs
                }
            } elseif ($v->type === 'deduction') {
                $retSal = $tSal !== null ? round($base * $tSal / 100, 2) : round($base, 2);
                if ((int)$v->statutVariable === 1) {
                    // COTISATIONS (après Total Brut)
                    $retPat = $tPat !== null ? round($base * $tPat / 100, 2) : 0.0;

                    $cotis[] = [
                        'numero'         => $v->numeroVariable,
                        'designation'    => $v->nom_variable,
                        'base'           => round($base, 2),
                        'taux_salarial'  => $tSal,
                        'gain_salarial'  => null,
                        'retenue_sal'    => $retSal,
                        'taux_patronal'  => $tPat,
                        'retenue_pat'    => $retPat,
                        'type'           => 'deduction',
                        'statutVariable' => 1,
                        'variableImposable' => (int)$v->variableImposable,
                    ];
                    $cotisBaseTotal   += $base;
                    $retenuesSalTotal += $retSal;
                    $retenuesPatTotal += $retPat;
                } else {
                    // Déductions sans part patronale (ex: TCS)
                    $autres[] = [
                        'numero'         => $v->numeroVariable,
                        'designation'    => $v->nom_variable,
                        'base'           => round($base, 2),
                        'taux_salarial'  => $tSal,
                        'gain_salarial'  => null,
                        'retenue_sal'    => $retSal,
                        'taux_patronal'  => $tPat,
                        'retenue_pat'    => 0.0,
                        'type'           => 'deduction',
                        'statutVariable' => 0,
                        'variableImposable' => (int)$v->variableImposable,
                    ];
                    $retenuesSalTotal += $retSal;
                }
            }
        }

        // Ordonner par numéro
        usort($preBrut, fn($a, $b) => ($a['numero'] <=> $b['numero']));
        usort($cotis,   fn($a, $b) => ($a['numero'] <=> $b['numero']));
        usort($autres,  fn($a, $b) => ($a['numero'] <=> $b['numero']));

        // Totaux
        $salaireBrut  = round($totalBrut, 2);
        $netImposable = round($salaireBrut - $retenuesSalTotal, 2);

        // Construction des lignes dans l’ordre
        $rows = [];
        array_push($rows, ...$preBrut);
        $rows[] = [
            'numero' => null,
            'designation' => 'Total Brut',
            'base' => null,
            'taux_salarial' => null,
            'gain_salarial' => round($totalBrut, 2),
            'retenue_sal' => null,
            'taux_patronal' => null,
            'retenue_pat' => null,
            'is_total' => true,
            'total_key' => 'total_brut'
        ];
        array_push($rows, ...$cotis);
        $rows[] = [
            'numero' => null,
            'designation' => 'Total Cotisations',
            'base' => round($cotisBaseTotal, 2),
            'taux_salarial' => null,
            'gain_salarial' => null,
            'retenue_sal' => round($retenuesSalTotal, 2),
            'taux_patronal' => null,
            'retenue_pat' => round($retenuesPatTotal, 2),
            'is_total' => true,
            'total_key' => 'total_cotisations'
        ];
        array_push($rows, ...$autres);

        // ---------- CALCULS HEURES (période & année) ----------
        // Norme journalière d’après l’entreprise
        $company = DB::table('entreprises')->where('id', $user->entreprise_id)->first();
        $dailyNormMinutes = (function ($c) {
            if (!$c || !$c->heure_ouverture || !$c->heure_fin) return 8 * 60; // fallback 8h
            $start = Carbon::parse($c->heure_ouverture);
            $end   = Carbon::parse($c->heure_fin);
            $mins  = $end->diffInMinutes($start);
            if ($c->heure_debut_pose && $c->heure_fin_pose) {
                $p1 = Carbon::parse($c->heure_debut_pose);
                $p2 = Carbon::parse($c->heure_fin_pose);
                $mins -= max(0, $p2->diffInMinutes($p1));
            }
            return max(0, $mins);
        })($company);

        // Helper: heures d’un intervalle
        $computeHours = function (Carbon $from, Carbon $to) use ($userId, $dailyNormMinutes) {
            // Pointages (travail réel)
            $pts = DB::table('pointages')
                ->where('user_id', $userId)
                ->whereDate('date_arriver', '<=', $to->toDateString())
                ->where(function ($q) use ($from) {
                    $q->whereNull('date_fin')
                        ->orWhereDate('date_fin', '>=', $from->toDateString());
                })->get();

            $workedPerDay = []; // minutes par jour
            foreach ($pts as $p) {
                // bornes
                $startDT = Carbon::parse(($p->date_arriver ?: $from->toDateString()) . ' ' . ($p->heure_arriver ?: '00:00:00'));
                $endDT   = Carbon::parse((($p->date_fin ?: $p->date_arriver) ?: $to->toDateString()) . ' ' . ($p->heure_fin ?: '00:00:00'));
                if ($endDT->lt($from) || $startDT->gt($to)) continue;

                $startDT = $startDT->max($from);
                $endDT   = $endDT->min($to);
                $mins    = max(0, $endDT->diffInMinutes($startDT));

                // pauses
                $breaks = DB::table('pointages_intermediaires')->where('pointage_id', $p->id)->get();
                $breakM = 0;
                foreach ($breaks as $b) {
                    if (!$b->heure_sortie || !$b->heure_entrer) continue;
                    $s = Carbon::parse($b->heure_sortie);
                    $e = Carbon::parse($b->heure_entrer);
                    $breakM += max(0, $e->diffInMinutes($s));
                }
                $mins = max(0, $mins - $breakM);

                $day = Carbon::parse($p->date_arriver ?: $startDT)->toDateString();
                $workedPerDay[$day] = ($workedPerDay[$day] ?? 0) + $mins;
            }

            $workedMinutes = array_sum($workedPerDay);

            // Heures sup = excédent par jour au-dessus de la norme journalière
            $overtimeMinutes = 0;
            foreach ($workedPerDay as $d => $m) {
                $overtimeMinutes += max(0, $m - $dailyNormMinutes);
            }

            // Absences approuvées (chevauchement)
            $absences = DB::table('absences')
                ->where('user_id', $userId)
                ->where('status', 'approuvé') // adapte si 'approuvee' chez toi
                ->where(function ($q) use ($from, $to) {
                    $q->whereBetween('start_datetime', [$from, $to])
                        ->orWhereBetween('end_datetime',   [$from, $to])
                        ->orWhere(function ($qq) use ($from, $to) {
                            $qq->where('start_datetime', '<=', $from)
                                ->where('end_datetime', '>=', $to);
                        });
                })->get();

            $absenceMinutes = 0;
            foreach ($absences as $a) {
                $s = Carbon::parse($a->start_datetime)->max($from);
                $e = Carbon::parse($a->end_datetime)->min($to);
                if ($e->gt($s)) $absenceMinutes += $e->diffInMinutes($s);
            }

            // Retour en heures (2 décimales)
            $toHours = fn($m) => round($m / 60, 2);

            return [
                'worked'    => $toHours($workedMinutes),
                'overtime'  => $toHours($overtimeMinutes),
                'absence'   => $toHours($absenceMinutes),
            ];
        };

        // Période
        $hoursPeriod = $computeHours($periodStart, $periodEnd);

        // Année civile de la période
        $yearStart = $periodStart->copy()->startOfYear();
        $yearEnd   = $periodStart->copy()->endOfYear();
        $hoursYear = $computeHours($yearStart, $yearEnd);

        // Attendance service (facultatif)
        $attendance = new AttendanceService();
        $status = $attendance->getUserStatusForDate($user, new Carbon($period->date_debut));

        return [
            'rows' => $rows,
            'totals' => [
                'Total Brut'          => round($totalBrut, 2),
                'Total Cotisations'   => [
                    'base' => round($cotisBaseTotal, 2),
                    'sal'  => round($retenuesSalTotal, 2),
                    'pat'  => round($retenuesPatTotal, 2),
                ],
                'Salaire brut'        => $salaireBrut,
                'Charges salariales'  => round($retenuesSalTotal, 2),
                'Charges patronales'  => round($retenuesPatTotal, 2),
                'Avantages en nature' => round($avantagesNature, 2),
                'Net imposable'       => $netImposable,
            ],
            'hours' => [
                'period' => $hoursPeriod, // ['worked','overtime','absence'] en heures
                'year'   => $hoursYear,
            ],
            // Raccourcis pour compatibilité avec ton rendu PDF actuel
            'heuresTravaillees'       => $hoursPeriod['worked'],
            'heuresSupplementaires'   => $hoursPeriod['overtime'],
            'heuresAbsence'           => $hoursPeriod['absence'],

            'heuresTravailleesAnnee'     => $hoursYear['worked'],
            'heuresSupplementairesAnnee' => $hoursYear['overtime'],
            'heuresAbsenceAnnee'         => $hoursYear['absence'],

            'periodTicketId' => $period->id,
            'userStatus'     => $status,
        ];
    }
}
class PdfAttestationStage extends \FPDF
{
    /** Données injectées par le contrôleur */
    public array $entreprise = [];
    public array $stagiaire  = [];
    public array $versement  = [];
    public array $meta       = [];
    public ?string $logoPath = null;

    /* ====================== Helpers ====================== */

    protected function t(?string $txt): string
    {
        $txt = $txt ?? '';
        return iconv('UTF-8', 'windows-1252//TRANSLIT', $txt);
    }

    protected function h(int $size, bool $bold = false): void
    {
        $this->SetFont('Arial', $bold ? 'B' : '', $size);
    }

    protected function borderBox(float $x, float $y, float $w, float $h, ?array $fill = null): void
    {
        if ($fill) $this->SetFillColor($fill[0], $fill[1], $fill[2]);
        $this->SetDrawColor(210, 210, 210);
        $this->RoundedRect($x, $y, $w, $h, 3, 'D' . ($fill ? 'F' : ''));
    }

    // --- RoundedRect utils
    protected function RoundedRect($x, $y, $w, $h, $r, $style = ''): void
    {
        $k  = $this->k;
        $hp = $this->h;
        $op = ($style == 'F') ? 'f' : (($style == 'FD' || $style == 'DF') ? 'B' : 'S');
        $MyArc = 4 / 3 * (sqrt(2) - 1);

        $this->_out(sprintf('%.2F %.2F m', ($x + $r) * $k, ($hp - $y) * $k));
        $xc = $x + $w - $r;
        $yc = $y + $r;
        $this->_out(sprintf('%.2F %.2F l', $xc * $k, ($hp - $y) * $k));
        $this->_Arc($xc + $r * $MyArc, $yc - $r, $xc + $r, $yc - $r * $MyArc, $xc + $r, $yc);

        $xc = $x + $w - $r;
        $yc = $y + $h - $r;
        $this->_out(sprintf('%.2F %.2F l', ($x + $w) * $k, ($hp - $yc) * $k));
        $this->_Arc($xc + $r, $yc + $r * $MyArc, $xc + $r * $MyArc, $yc + $r, $xc, $yc + $r);

        $xc = $x + $r;
        $yc = $y + $h - $r;
        $this->_out(sprintf('%.2F %.2F l', $xc * $k, ($hp - ($y + $h)) * $k));
        $this->_Arc($xc - $r * $MyArc, $yc + $r, $xc - $r, $yc + $r * $MyArc, $xc - $r, $yc);

        $xc = $x + $r;
        $yc = $y + $r;
        $this->_out(sprintf('%.2F %.2F l', $x * $k, ($hp - $yc) * $k));
        $this->_Arc($xc - $r, $yc - $r * $MyArc, $xc - $r * $MyArc, $yc - $r, $xc, $yc - $r);

        $this->_out($op);
    }
    protected function _Arc($x1, $y1, $x2, $y2, $x3, $y3): void
    {
        $h = $this->h;
        $this->_out(sprintf(
            '%.2F %.2F %.2F %.2F %.2F %.2F c ',
            $x1 * $this->k,
            ($h - $y1) * $this->k,
            $x2 * $this->k,
            ($h - $y2) * $this->k,
            $x3 * $this->k,
            ($h - $y3) * $this->k
        ));
    }

    /* ======================= Header ====================== */

    // Remplace entièrement la méthode Header()
    function Header()
    {
        // Marges & pagination
        $this->SetMargins(16, 16, 16);
        $this->SetAutoPageBreak(true, 16);

        // Logo (à droite)
        if ($this->logoPath && @is_file($this->logoPath)) {
            // largeur 22mm, aligné bord droit
            $this->Image($this->logoPath, 0, 12, 22, 0, '', '', true, 300, '', false);
            $this->SetXY(190 - 22, 12);
            $this->Image($this->logoPath, 190 - 22, 12, 22);
        }

        // Titre centré
        $this->SetXY(16, 14);
        $this->h(17, true);
        $this->SetTextColor(33, 33, 33);
        $this->Cell(0, 10, $this->t('ATTESTATION DE PAIEMENT'), 0, 2, 'C');

        // Sous-titre
        $this->h(10);
        $this->SetTextColor(120, 120, 120);
        $this->Cell(0, 6, $this->t('Allocation de stage'), 0, 2, 'C');

        // Trait
        $this->Ln(2);
        $this->SetDrawColor(52, 152, 219);
        $this->SetLineWidth(0.5);
        $this->Line(16, $this->GetY(), 194, $this->GetY());
        $this->SetLineWidth(0.2);

        // Cartouche Réf/Date (en haut à droite)
        $this->SetXY(120, 18);
        $this->SetFillColor(245, 247, 251);
        $this->SetDrawColor(220, 225, 230);
        $this->RoundedRect(120, 18, 74, 16, 2, 'DF');

        $this->SetXY(124, 20);
        $this->h(9, true);
        $this->SetTextColor(68, 68, 68);
        $ref  = $this->meta['reference'] ?? 'ATT-STAGE-[NUMÉRO]';
        $date = $this->meta['dateEmission'] ?? '[JJ/MM/AAAA]';
        $this->Cell(70, 5, $this->t('Référence : ' . $ref), 0, 2, 'L');

        $this->h(9);
        $this->Cell(70, 5, $this->t('Date d’émission : ' . $date), 0, 1, 'L');

        $this->Ln(2);
    }


    /* ======================= Footer ====================== */

    function Footer()
{
    $this->SetY(-18);
    $this->SetDrawColor(235,235,235);
    $this->Line(16, $this->GetY(), 194, $this->GetY());

    $this->SetY(-14);
    $this->h(8);
    $this->SetTextColor(135,135,135);
    $ville = $this->meta['villeFooter'] ?? '[VILLE]';
    $date  = $this->meta['dateFooter'] ?? '[DATE]';
    $this->Cell(0, 5, $this->t("Document émis le {$date} à {$ville}, République Gabonaise"), 0, 2, 'C');
    $this->h(7);
    $this->Cell(0, 5, $this->t("Attestation conforme à la convention de stage — à conserver."), 0, 0, 'C');
}


    /* ==================== Blocs contenu ==================== */

    // Remplace drawCompanyPanel()
    public function drawCompanyPanel(): float
    {
        $y = 40;             // descend un peu sous l’entête
        $x = 16;
        $w = 178;   // marges homogènes
        $h = 54;             // + haut pour éviter toute coupure

        $this->borderBox($x, $y, $w, $h, [245, 247, 251]);

        // Titre du bloc
        $this->SetXY($x + 8, $y + 7);
        $this->h(11, true);
        $this->SetTextColor(40, 53, 147);
        $this->Cell(0, 6, $this->t("ENTREPRISE D’ACCUEIL"), 0, 1, 'L');

        $this->SetTextColor(70, 70, 70);
        $this->h(9);
        $labelW = 38;
        $valW = $w - 8 - $labelW - 8; // padding 8

        $pairs = [
            ['Raison sociale :', $this->entreprise['nom'] ?? '[NOM DE L’ENTREPRISE]'],
            ['Adresse :',        $this->entreprise['adresse'] ?? '[ADRESSE COMPLÈTE]'],
            ['Ville :',          ($this->entreprise['ville'] ?? '[VILLE]') . ', Gabon'],
            ['Téléphone :',      $this->entreprise['tel'] ?? '[TÉLÉPHONE]'],
            ['Email :',          $this->entreprise['email'] ?? '[EMAIL]'],
        ];

        $yy = $y + 16;
        foreach ($pairs as [$k, $v]) {
            $this->SetXY($x + 8, $yy);
            $this->SetFont('Arial', 'B', 9);
            $this->Cell($labelW, 6, $this->t($k), 0, 0, 'L');
            $this->SetFont('Arial', '', 9);
            $this->Cell($valW,   6, $this->t($v), 0, 1, 'L');
            $yy += 6;
        }

        // Phrase + titre central
        $this->Ln(2);
        $this->SetTextColor(75, 75, 75);
        $this->SetX($x);
        $this->h(9);
        $rep  = $this->entreprise['representant'] ?? '[Nom et Prénom du représentant]';
        $fonc = $this->entreprise['fonction'] ?? '[Fonction]';
        $nomE = $this->entreprise['nom'] ?? '[NOM DE L’ENTREPRISE]';
        $this->MultiCell($w, 5, $this->t("Je soussigné(e), {$rep}, {$fonc}, représentant(e) légal(e) de {$nomE},"), 0, 'L');

        $this->Ln(2);
        $this->h(11, true);
        $this->SetTextColor(40, 53, 147);
        $this->Cell(0, 7, $this->t("ATTESTE PAR LA PRÉSENTE QUE :"), 0, 1, 'C');

        return $this->GetY();
    }


    // Remplace drawTraineePanel()
    public function drawTraineePanel(float $yStart): float
    {
        $x = 16;
        $w = 178;
        $y = $yStart + 2;
        $h = 56;
        $this->borderBox($x, $y, $w, $h, [88, 86, 214]); // violet + clair

        // Titre
        $this->SetTextColor(255, 255, 255);
        $this->SetXY($x + 8, $y + 7);
        $this->h(10, true);
        $this->Cell(0, 6, $this->t('STAGIAIRE'), 0, 1, 'L');

        // Deux colonnes
        $this->h(9);
        $labelW = 44;
        $gap = 4;

        $xL = $x + 8;
        $xLV = $xL + $labelW + $gap;
        $wLV = 64;
        $xR = $x + 96;
        $xRV = $xR + $labelW + $gap;
        $wRV = 62;

        $left = [
            ["Nom et Prénom :", $this->stagiaire['nom'] ?? '[NOM PRÉNOM DU STAGIAIRE]'],
            ["Formation :",     $this->stagiaire['formation'] ?? '[FILIÈRE/SPÉCIALITÉ]'],
            ["Période de stage :", $this->stagiaire['periode'] ?? 'Du [DATE] au [DATE]'],
        ];
        $right = [
            ["Établissement :", $this->stagiaire['etab'] ?? '[NOM ÉCOLE/UNIVERSITÉ]'],
            ["Niveau :",        $this->stagiaire['niveau'] ?? '[NIVEAU D’ÉTUDES]'],
            ["Service d’affectation :", $this->stagiaire['service'] ?? '[SERVICE/DÉPARTEMENT]'],
        ];

        $yy = $y + 20;
        for ($i = 0; $i < 3; $i++) {
            // gauche
            $this->SetXY($xL, $yy);
            $this->SetFont('Arial', 'B', 9);
            $this->Cell($labelW, 6, $this->t($left[$i][0]), 0, 0, 'L');
            $this->SetFont('Arial', '', 9);
            $this->Cell($wLV, 6, $this->t($left[$i][1]), 0, 0, 'L');
            // droite
            $this->SetXY($xR, $yy);
            $this->SetFont('Arial', 'B', 9);
            $this->Cell($labelW, 6, $this->t($right[$i][0]), 0, 0, 'L');
            $this->SetFont('Arial', '', 9);
            $this->Cell($wRV, 6, $this->t($right[$i][1]), 0, 1, 'L');
            $yy += 7;
        }

        // Paragraphe sous panneau
        $this->SetY($y + $h + 6);
        $this->SetTextColor(60, 60, 60);
        $this->h(9);
        $convDate = $this->meta['dateConvention'] ?? '[DATE DE LA CONVENTION]';
        $txt = "A effectué un stage au sein de notre structure durant la période susmentionnée, "
            . "dans le cadre de sa formation académique, conformément à la convention de stage tripartite signée le {$convDate} "
            . "entre notre entreprise, l’établissement de formation et le stagiaire.";
        $this->MultiCell($w, 5, $this->t($txt), 0, 'L');

        return $this->GetY();
    }


    // Remplace drawPaymentDetails()
    public function drawPaymentDetails(float $yStart): float
    {
        $x = 16;
        $w = 178;
        $y = $yStart + 6;
        $h = 86;
        $this->borderBox($x, $y, $w, $h, [255, 250, 235]);

        // Titre
        $this->SetXY($x + 8, $y + 7);
        $this->h(11, true);
        $this->SetTextColor(217, 128, 50);
        $this->Cell(0, 6, $this->t("DÉTAILS DU VERSEMENT"), 0, 1, 'L');

        // Tableau
        $this->SetTextColor(60, 60, 60);
        $this->h(9);
        $x1 = $x + 8;
        $w1 = 86;
        $x2 = $x1 + $w1;
        $w2 = $w - 16 - $w1;
        $lineH = 8;

        // Format “autres” -> ajouter unité si numérique
        $autres = $this->versement['autres'] ?? '-';
        if ($autres !== '-' && preg_match('/^\d[\d\s]*$/', (string)$autres)) {
            $autres .= ' F CFA';
        }

        $rows = [
            ["Mois concerné", $this->versement['mois'] ?? '[MOIS ANNÉE]'],
            ["Nombre de jours de présence", trim(($this->versement['jours'] ?? '[XX]') . ' jours')],
            ["Allocation mensuelle de stage", trim(($this->versement['allocation'] ?? '[MONTANT]') . ' F CFA   NET')],
            ["Indemnité de transport (le cas échéant)", trim(($this->versement['transport'] ?? '[MONTANT ou "-"]') . ($this->versement['transport'] !== '-' ? ' F CFA' : ''))],
            ["Autres avantages (le cas échéant)", $autres],
        ];

        // En-tête visuelle (gris)
        $this->SetFillColor(247, 247, 247);
        $this->SetDrawColor(230, 230, 230);

        foreach ($rows as $i => [$k, $v]) {
            $this->SetXY($x1, $this->GetY() + 2);
            $this->SetFont('Arial', 'B', 9);
            $this->Cell($w1, $lineH, $this->t($k), 1, 0, 'L', true);
            $this->SetFont('Arial', '', 9);
            $this->Cell($w2, $lineH, $this->t($v), 1, 1, 'L');
        }

        // TOTAL (bande verte)
        $this->SetXY($x1, $this->GetY() + 2);
        $this->SetFillColor(33, 150, 83);
        $this->SetTextColor(255, 255, 255);
        $this->h(10, true);
        $this->Cell($w1, 10, $this->t('MONTANT TOTAL VERSÉ'), 1, 0, 'L', true);
        $this->Cell($w2, 10, $this->t(($this->versement['total'] ?? '[MONTANT TOTAL]') . ' F CFA'), 1, 1, 'L', true);

        // Mode / Date / Référence
        $this->SetTextColor(60, 60, 60);
        $this->h(9);
        $rows2 = [
            ["Mode de paiement", $this->versement['mode'] ?? 'Virement / Chèque / Espèces'],
            ["Date de versement", $this->versement['dateVersement'] ?? '[JJ/MM/AAAA]'],
            ["Référence de paiement", $this->versement['reference'] ?? '[RÉFÉRENCE SI VIREMENT]'],
        ];
        foreach ($rows2 as [$k, $v]) {
            $this->SetXY($x1, $this->GetY() + 2);
            $this->SetFont('Arial', 'B', 9);
            $this->Cell($w1, $lineH, $this->t($k), 1, 0, 'L', true);
            $this->SetFont('Arial', '', 9);
            $this->Cell($w2, $lineH, $this->t($v), 1, 1, 'L');
        }

        return $this->GetY();
    }


    // Remplace drawImportantNotes()
    public function drawImportantNotes(float $yStart): float
    {
        $x = 16;
        $w = 178;
        $y = $yStart + 8;
        $h = 64;
        $this->borderBox($x, $y, $w, $h, [232, 245, 233]);

        $this->SetXY($x + 8, $y + 7);
        $this->h(11, true);
        $this->SetTextColor(46, 125, 50);
        $this->Cell(0, 6, $this->t("INFORMATIONS IMPORTANTES"), 0, 1, 'L');

        $this->SetTextColor(66, 66, 66);
        $this->h(9);
        $bullets = [
            "Nature juridique : L’allocation de stage versée au stagiaire ne constitue pas un salaire et n’est pas soumise aux cotisations sociales (CNSS, CNAMGS) ni à l’IRPP.",
            "Statut : Le stagiaire n’a pas le statut de salarié. Il est lié à l’entreprise par une convention de stage et non par un contrat de travail.",
            "Cadre légal : Conforme au Code du Travail gabonais (Loi n°022/2021 du 19 novembre 2021, Article 1-1 définissant le statut de stagiaire).",
            "Objet : Cette attestation est délivrée pour servir et valoir ce que de droit (justification auprès de l’établissement de formation ou de toute autorité compétente).",
        ];

        $this->SetXY($x + 8, $y + 18);
        foreach ($bullets as $txt) {
            $this->MultiCell($w - 16, 5.2, $this->t('• ' . $txt), 0, 'L');
            $this->Ln(0.5);
        }

        $this->Ln(2);
        $n = $this->meta['nbExemplaires'] ?? '[NOMBRE]';
        $this->MultiCell($w, 5.2, $this->t("La présente attestation est établie en {$n} exemplaires originaux pour servir et valoir ce que de droit."), 0, 'L');

        return $this->GetY();
    }


    // Remplace drawSignatures()
public function drawSignatures(float $yStart): void
{
    $x=16; $w=178; $y=$yStart+8; $h=52;
    $this->borderBox($x, $y, $w, $h, [255,255,255]);

    $this->SetXY($x+6, $y+6);
    $this->h(9, true);
    $this->SetTextColor(80,80,80);
    $this->Cell($w/2-6, 6, $this->t("L’ENTREPRISE"), 0, 0, 'C');
    $this->Cell($w/2-6, 6, $this->t("LE/LA STAGIAIRE"), 0, 1, 'C');

    $left = [
        "Nom : " . ($this->entreprise['representant'] ?? '[NOM DU REPRÉSENTANT]'),
        "Fonction : " . ($this->entreprise['fonction'] ?? '[FONCTION]'),
        "Date : " . ($this->meta['dateSignatureEntreprise'] ?? '[JJ/MM/AAAA]'),
    ];
    $right = [
        "Nom : " . ($this->stagiaire['nom'] ?? '[NOM DU STAGIAIRE]'),
        "Date : " . ($this->meta['dateSignatureStagiaire'] ?? '[JJ/MM/AAAA]'),
        "Mention \"Lu et approuvé, bon pour réception de " . ($this->versement['total'] ?? '[MONTANT]') . " F CFA\"",
    ];

    $this->h(9);
    $this->SetTextColor(70,70,70);
    $yy = $y + 18;
    for ($i=0; $i<3; $i++) {
        $this->SetXY($x+8, $yy);              $this->Cell($w/2-16, 6, $this->t($left[$i]), 0, 0, 'L');
        $this->SetXY($x+$w/2+8, $yy);         $this->Cell($w/2-16, 6, $this->t($right[$i]), 0, 1, 'L');
        $yy += 7;
    }

    // Traits de signature
    $this->SetDrawColor(200,200,200);
    $yLine = $y + $h - 12;
    $this->Line($x+18, $yLine, $x+$w/2-18, $yLine);
    $this->Line($x+$w/2+18, $yLine, $x+$w-18, $yLine);

    $this->SetTextColor(120,120,120);
    $this->h(8);
    $this->SetXY($x+6, $yLine+2);
    $this->Cell($w/2-12, 5, $this->t("Signature et cachet de l’entreprise"), 0, 0, 'C');
    $this->Cell($w/2-12, 5, $this->t("Signature"), 0, 1, 'C');
}


    /** Orchestrateur */
    public function build(): void
    {
        $this->AddPage('P', 'A4');
        $y = $this->drawCompanyPanel();
        $y = $this->drawTraineePanel($y);
        $y = $this->drawPaymentDetails($y);
        $y = $this->drawImportantNotes($y);
        $this->drawSignatures($y);
    }
}
