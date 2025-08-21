<?php

namespace App\Http\Controllers;

use App\Models\Entreprise;
use App\Models\PeriodePaie;
use App\Models\Pointage;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

require_once base_path('vendor/setasign/fpdf/fpdf.php');

/**
 * Classe FPDF personnalisée
 */
class PdfList extends \FPDF
{
    public string $logoPath = '';
    public string $title = 'Présences / Arrivées';
    public string $printedBy = '';

    // --- Helpers ---
    public function t($str)
    {
        return mb_convert_encoding($str ?? '', 'ISO-8859-1', 'UTF-8');
    }

    // Rectangle à coins arrondis
    public function RoundedRect($x, $y, $w, $h, $r, $style = '')
    {
        $k  = $this->k;
        $hp = $this->h;
        $op = $style == 'F' ? 'f' : (($style == 'FD' || $style == 'DF') ? 'B' : 'S');
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

    protected function _Arc($x1, $y1, $x2, $y2, $x3, $y3)
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

    // --- EN-TÊTE & PIED DE PAGE ---
    function Header()
    {
        // Cadre
        $this->SetDrawColor(210, 210, 210);
        $this->Rect(10, 10, $this->GetPageWidth() - 20, $this->GetPageHeight() - 20);

        // Logo
        if ($this->logoPath && file_exists($this->logoPath)) {
            $this->Image($this->logoPath, 14, 13, 18);
        }

        // Titre
        $this->SetFont('Arial', 'B', 14);
        $this->SetXY(36, 14);
        $this->Cell(100, 8, $this->t($this->title), 0, 0, 'L');

        // Infos à droite
        $this->SetFont('Arial', '', 9);
        $this->SetXY(-95, 14);
        $this->Cell(85, 5, $this->t('Imprimé par : ' . ($this->printedBy ?: '—')), 0, 2, 'R');
        $this->Cell(85, 5, $this->t('Date : ' . now()->format('d/m/Y H:i')), 0, 2, 'R');

        // Ligne
        $this->SetDrawColor(220, 220, 220);
        $this->Line(12, 26, $this->GetPageWidth() - 12, 26);

        // Position de départ du contenu
        $this->SetY(30);
    }

    function Footer()
    {
        $this->SetDrawColor(220, 220, 220);
        $this->Line(12, $this->GetPageHeight() - 26, $this->GetPageWidth() - 12, $this->GetPageHeight() - 26);

        $this->SetY(-22);
        $this->SetFont('Arial', '', 8);
        $this->Cell(0, 5, $this->t('Généré le ' . now()->format('d/m/Y H:i')), 0, 1, 'L');

        $this->SetFont('Arial', 'I', 8);
        $this->Cell(0, 5, $this->t('Page ') . $this->PageNo() . '/{nb}', 0, 0, 'R');
    }

    // --- Éléments visuels ---
    public function drawAvatar($x, $y, $size, $initials)
    {
        $this->SetDrawColor(220, 230, 245);
        $this->SetFillColor(52, 120, 246);
        $this->RoundedRect($x, $y, $size, $size, 1.8, 'F');

        $this->SetTextColor(255, 255, 255);
        $this->SetFont('Arial', 'B', 10);
        $this->SetXY($x, $y + 0.6);
        $this->Cell($size, $size - 1.2, $this->t($initials), 0, 0, 'C');
        $this->SetTextColor(0, 0, 0);
    }

    public function drawTimeBadge($x, $y, $w, $h, $timeStr, $color = 'green')
    {
        if ($color === 'red') $this->SetFillColor(220, 53, 69);
        else $this->SetFillColor(25, 135, 84);

        $this->SetTextColor(255, 255, 255);
        $this->RoundedRect($x, $y, $w, $h, 3, 'F');

        $this->SetFont('Arial', 'B', 9);
        $this->SetXY($x, $y + 1.2);
        $this->Cell($w, $h - 2.4, $this->t($timeStr), 0, 0, 'C');
        $this->SetTextColor(0, 0, 0);
    }

    public function drawUserRow($x, $y, $name, $role, $time, $isGreen = true)
    {
        $rowHeight  = 18;
        $avatarSize = 10;
        $badgeW = 24;
        $badgeH = 8;

        // Carte (fond + bordure)
        $this->SetFillColor(248, 249, 250); // gris très léger
        $this->SetDrawColor(230, 230, 230);
        $this->RoundedRect($x, $y, $this->GetPageWidth() - $x - 12, $rowHeight, 2.5, 'DF');

        // Avatar
        $initials = $this->initials($name);
        $this->drawAvatar($x + 2.8, $y + 3.5, $avatarSize, $initials);

        // Nom + rôle
        $this->SetFont('Arial', 'B', 11);
        $this->SetXY($x + $avatarSize + 8, $y + 3.2);
        $this->Cell(120, 6, $this->t($name), 0, 2, 'L');

        $this->SetFont('Arial', '', 9);
        $this->SetTextColor(100, 100, 100);
        $this->Cell(120, 6, $this->t($role), 0, 0, 'L');
        $this->SetTextColor(0, 0, 0);

        // Badge heure à droite
        $pageW = $this->GetPageWidth();
        $rightMargin = 16;
        $badgeX = $pageW - $rightMargin - $badgeW;
        $badgeY = $y + 5.2;
        $this->drawTimeBadge($badgeX, $badgeY, $badgeW, $badgeH, $time, $isGreen ? 'green' : 'red');
    }

    private function initials($name)
    {
        $parts = preg_split('/\s+/', trim($name));
        $ini = '';
        foreach ($parts as $p) {
            if ($p !== '') $ini .= mb_strtoupper(mb_substr($p, 0, 1, 'UTF-8'), 'UTF-8');
            if (mb_strlen($ini, 'UTF-8') >= 2) break;
        }
        return $ini ?: 'U';
    }
}

/**
 * Controller
 */
class PdfController extends Controller
{
    public function absentsPdf(Request $request)
    {
        $entreprise_id = session('entreprise_id');

        // Utilisateurs actifs de l’entreprise SANS pointage aujourd’hui
        $users_non_existants = User::where('entreprise_id', $entreprise_id)
            ->whereDoesntHave('pointage', function ($query) {
                $query->whereDate('date_arriver', now()->format('Y-m-d'));
            })
            ->where('statu_user', 1)
            ->where('statut', 1)
            ->get();

        // Construire les lignes pour FPDF : [nom, rôle, heure, isGreen]
        $rows = $users_non_existants
            ->map(function ($user) {
                $name = trim(($user->nom ?? '') . ' ' . ($user->prenom ?? $user->first_name ?? $user->name ?? ''));
                $role = $user->fonction ?? $user->poste ?? $user->role_user ?? '—';
                $timeStr = '—';       // absent => pas d’heure
                $isGreen = false;     // badge rouge
                return [$name, $role, $timeStr, $isGreen];
            })
            ->sortBy(fn($row) => mb_strtolower($row[0], 'UTF-8')) // tri par nom
            ->values()
            ->toArray();

        // Génération PDF (ton code)
        $pdf = new PdfList('P', 'mm', 'A4');
        $pdf->AliasNbPages();
        $pdf->SetMargins(12, 20, 12);

        $pdf->logoPath  = public_path(session('entreprise_logo')
            ? 'storage/' . ltrim(session('entreprise_logo'), '/')
            : 'src/image/logo.png');
        $pdf->printedBy = Auth::user()->nom ?? 'Système';
        $pdf->title     = 'Employés absents ' . ((count($users_non_existants) > 0) ?  ': ' . count($users_non_existants) : '');

        $pdf->AddPage();

        // Si aucun absent, afficher un message
        if (empty($rows)) {
            $pdf->SetFont('Arial', 'I', 11);
            $msg = mb_convert_encoding("Aucun employé absent pour aujourd'hui.", 'ISO-8859-1', 'UTF-8');
            $pdf->Cell(0, 20, $msg, 0, 1, 'C'); // 'C' => centré
        } else {
            $y = $pdf->GetY() + 2;
            foreach ($rows as [$name, $role, $time, $isGreen]) {
                if ($y > ($pdf->GetPageHeight() - 40)) {
                    $pdf->AddPage();
                    $y = $pdf->GetY() + 2;
                }
                $pdf->drawUserRow(12, $y, $name, $role, $time, $isGreen);
                $y += 20;
            }
        }

        return response($pdf->Output('S'))
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'inline; filename="absents.pdf"');
    }

    public function presentPdf(Request $request)
    {
        $entreprise_id = session('entreprise_id');
        $entreprise = Entreprise::find($entreprise_id);

        // Heure limite : fallback 08:30:00 si non renseignée
        $heureLimite = \Carbon\Carbon::createFromTimeString(
            $entreprise->heure_ouverture ?: '08:30:00'
        );

        // Pointages du jour + user (évite N+1)
        $pointages_oui = Pointage::with('user')
            ->whereHas('user', fn($q) => $q->where('entreprise_id', $entreprise_id)
                ->where('statu_user', 1)
                ->where('statut', 1))
            ->whereDate('date_arriver', now()->toDateString())   // si colonne datetime
            // ->where('date_arriver', now()->format('Y-m-d'))    // si colonne DATE
            ->get();

        // Construire les lignes pour FPDF : [nom, rôle, heure, isGreen]
        $rows = $pointages_oui
            ->map(function ($p) use ($heureLimite) {
                $user = $p->user;

                $name = trim(($user->nom ?? '') . ' ' . ($user->prenom ?? $user->first_name ?? $user->name ?? ''));
                $role = $user->role_user ?? $user->poste ?? $user->fonction ?? '—';

                $heureArrivee = $p->heure_arriver
                    ? \Carbon\Carbon::parse($p->heure_arriver)
                    : ($p->created_at ? \Carbon\Carbon::parse($p->created_at) : null);

                $timeStr = $heureArrivee ? $heureArrivee->format('H:i:s') : '—';
                $isGreen = $heureArrivee ? $heureArrivee->lte($heureLimite) : false;

                return [$name, $role, $timeStr, $isGreen];
            })
            // Tri par nom puis heure (concat simple, suffit ici)
            ->sortBy(fn($r) => mb_strtolower($r[0], 'UTF-8') . '|' . ($r[2] ?? ''))
            ->values()
            ->toArray();

        // --- PDF ---
        $pdf = new PdfList('P', 'mm', 'A4');
        $pdf->AliasNbPages();
        $pdf->SetMargins(12, 20, 12);

        $pdf->logoPath  = public_path(session('entreprise_logo')
            ? 'storage/' . ltrim(session('entreprise_logo'), '/')
            : 'src/image/logo.png');
        $pdf->printedBy = Auth::user()->nom ?? 'Système';
        $pdf->title     = 'Employés présents ' . ((count($pointages_oui) > 0) ? ': ' . count($pointages_oui) : '');

        $pdf->AddPage();

        if (empty($rows)) {
            $pdf->SetFont('Arial', 'I', 11);
            $msg = mb_convert_encoding("Aucun employé présent enregistré aujourd'hui.", 'ISO-8859-1', 'UTF-8');
            $pdf->Cell(0, 20, $msg, 0, 1, 'C'); // 'C' => centré
        } else {
            $y = $pdf->GetY() + 2;
            foreach ($rows as [$name, $role, $time, $isGreen]) {
                if ($y > ($pdf->GetPageHeight() - 40)) {
                    $pdf->AddPage();
                    $y = $pdf->GetY() + 2;
                }
                $pdf->drawUserRow(12, $y, $name, $role, $time, $isGreen);
                $y += 20;
            }
        }

        return response($pdf->Output('S'))
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'inline; filename="presents.pdf"');
    }





    public function payrollTablePdf(Request $request, string $ticket)
    {

        function fcfa1($n)
        {
            return number_format((float)$n, 0, ',', ' ') . ' F CFA';
        }
        $entrepriseId = session('entreprise_id');
        $entreprise   = Entreprise::findOrFail($entrepriseId);
        
        // --- 1) Période via ticket (comme dans loadTicketData) ---
        $periode = PeriodePaie::where('entreprise_id', $entrepriseId)
        ->where('ticket', $ticket)
        ->first();
        
        // --- 2) Employés actifs de l’entreprise (mêmes relations que dans ta vue) ---
        $users = User::with(['categorieProfessionnelle', 'service', 'role'])
        ->where('statu_user', 1)
        ->where('entreprise_id', $entrepriseId)
        ->orderBy('nom')
        ->get();
        // dd($periode);

        // --- 3) Lignes variables pour cette période ---
        $rows = DB::table('variable_periode_users as vpu')
            ->join('users as u', 'u.id', '=', 'vpu.user_id')
            ->join('variables as v', 'v.id', '=', 'vpu.variable_id')
            ->leftJoin('categories as c', 'c.id', '=', 'v.categorie_id')
            ->where('vpu.periode_paie_id', $periode->id)
            ->get([
                'u.id as user_id',
                'v.nom_variable',
                'v.type',                   // 'gain' | 'deduction'
                'c.nom_categorie',
                'vpu.montant',
            ]);

        // --- 4) Map employeeData + liste unique des variables (comme ton loadTicketData) ---
        $employeeData = [];          // [user_id => ['Prime d'assiduité' => 10000, ...]]
        $variablesMap = [];          // 'Prime d'assiduité' => ['type' => 'gain', 'category' => 'Primes']

        foreach ($rows as $r) {
            if ((float)$r->montant <= 0) continue; // on ne garde pas les zéros

            $uid = (string)$r->user_id;
            $var = (string)$r->nom_variable;

            $employeeData[$uid] ??= [];
            $employeeData[$uid][$var] = (float)$r->montant;

            $variablesMap[$var] ??= [
                'name'     => $var,
                'type'     => $r->type,
                'category' => $r->nom_categorie ?? '—',
            ];
        }

        // --- 5) Ordonner les variables (catégorie puis nom) ---
        $variables = array_values($variablesMap);
        usort($variables, function ($a, $b) {
            $ca = mb_strtolower($a['category'] ?? '', 'UTF-8');
            $cb = mb_strtolower($b['category'] ?? '', 'UTF-8');
            if ($ca !== $cb) return $ca <=> $cb;
            return mb_strtolower($a['name'], 'UTF-8') <=> mb_strtolower($b['name'], 'UTF-8');
        });

        // --- 6) Construire les lignes "employees" comme dans le JS ---
        $employees = $users->map(function ($u) {
            return [
                'id'         => (string)$u->id,
                'firstName'  => $u->prenom ?? '—',
                'lastName'   => $u->nom ?? '—',
                'position'   => $u->fonction
                    ?? ($u->categorieProfessionnelle->nom_categorie_professionnelle ?? '—'),
                'department' => $u->service->nom_service ?? '—',
                'role'       => $u->role->nom ?? '—',
                'baseSalary' => $u->salairebase ? (float)$u->salairebase : 0.0,
            ];
        })->values()->all();

        // --- 7) Génération PDF en paysage ---
        $pdf = new PdfList('L', 'mm', 'A4');  // L = Landscape
        $pdf->AliasNbPages();
        $pdf->SetMargins(12, 20, 12);

        // En-tête
        $pdf->logoPath  = public_path(session('entreprise_logo')
            ? 'storage/' . ltrim(session('entreprise_logo'), '/')
            : 'src/image/logo.png');
        $pdf->printedBy = Auth::user()->name ?? 'Système';
        $pdf->title     = "Saisie Globale des Variables ({$periode->date_debut} → {$periode->date_fin})";

        $pdf->AddPage();

        // --- 8) Mise en page du tableau ---
        $t = fn($s) => mb_convert_encoding($s ?? '', 'ISO-8859-1', 'UTF-8');

        $pageW = $pdf->GetPageWidth();
        $left  = 12;                 // marge gauche
        $right = 12;                 // marge droite
        $usableW = $pageW - $left - $right;

        // Largeurs colonnes fixes
        $wEmployee = 55;             // "Employé"
        $wBase     = 28;             // "Salaire de Base"
        $wNet      = 32;             // "Net à Payer"

        // largeur dispo pour variables :
        $varsAreaW = $usableW - $wEmployee - $wBase - $wNet;
        $minVarW   = 24;             // largeur minimale d'une variable
        $colsPerPage = max(1, (int) floor($varsAreaW / $minVarW));

        // Découper les variables en "pages horizontales"
        $chunks = array_chunk($variables, $colsPerPage);

        // Styles
        $pdf->SetFont('Arial', '', 9);


        // Dessine le tableau pour chaque chunk (si beaucoup de variables)
        foreach ($chunks as $chunkIndex => $varsChunk) {
            if ($chunkIndex > 0) {
                $pdf->AddPage();
            }

            // Recalcule la largeur de chaque var pour occuper l’espace restant
            $currentVarsW = $usableW - $wEmployee - $wBase - $wNet;
            $wVar = round($currentVarsW / max(1, count($varsChunk)), 2);

            // --- En-tête du tableau ---
            $y = $pdf->GetY();
            $pdf->SetFillColor(245, 245, 245);
            $pdf->SetDrawColor(220, 220, 220);
            $pdf->SetFont('Arial', 'B', 9);

            $pdf->SetXY($left, $y);
            $pdf->Cell($wEmployee, 8, $t('Employé'), 1, 0, 'L', true);
            $pdf->Cell($wBase,     8, $t('Salaire de Base'), 1, 0, 'R', true);

            foreach ($varsChunk as $v) {
                // On peut afficher l’icône via (G/D) si tu veux : ici juste le nom
                $label = $v['name'];
                $pdf->Cell($wVar, 8, $t($label), 1, 0, 'C', true);
            }
            $pdf->Cell($wNet, 8, $t('Net à Payer'), 1, 1, 'R', true);

            // --- Corps du tableau ---
            $pdf->SetFont('Arial', '', 9);
            $pdf->SetFillColor(255, 255, 255);
            $pdf->SetDrawColor(230, 230, 230);

            $y = $pdf->GetY();
            foreach ($employees as $emp) {
                // Saut de page si bas atteint
                if ($y > $pdf->GetPageHeight() - 28) {
                    $pdf->AddPage();
                    // répéter l’en-tête pour ce chunk
                    $pdf->SetFillColor(245, 245, 245);
                    $pdf->SetDrawColor(220, 220, 220);
                    $pdf->SetFont('Arial', 'B', 9);
                    $pdf->SetXY($left, $pdf->GetY());
                    $pdf->Cell($wEmployee, 8, $t('Employé'), 1, 0, 'L', true);
                    $pdf->Cell($wBase,     8, $t('Salaire de Base'), 1, 0, 'R', true);
                    foreach ($varsChunk as $v) {
                        $pdf->Cell($wVar, 8, $t($v['name']), 1, 0, 'C', true);
                    }
                    $pdf->Cell($wNet, 8, $t('Net à Payer'), 1, 1, 'R', true);

                    $y = $pdf->GetY();
                    $pdf->SetFont('Arial', '', 9);
                    $pdf->SetFillColor(255, 255, 255);
                    $pdf->SetDrawColor(230, 230, 230);
                }

                $uid = (string)$emp['id'];
                $cellsH = 8;

                // Montants variables pour cet employé (limités au chunk)
                $sumGain = 0.0;
                $sumDed  = 0.0;

                // Ligne : Employé + Salaire de base
                $pdf->SetXY($left, $y);
                $pdf->Cell($wEmployee, $cellsH, $t($emp['lastName'] . ' ' . $emp['firstName']), 1, 0, 'L');
                $pdf->Cell($wBase,     $cellsH, $t(fcfa1($emp['baseSalary'])),                1, 0, 'R');

                // Colonnes variables
                foreach ($varsChunk as $v) {
                    $val = $employeeData[$uid][$v['name']] ?? 0.0;
                    if ($v['type'] === 'gain') $sumGain += (float)$val;
                    else                        $sumDed  += (float)$val;

                    $pdf->Cell($wVar, $cellsH, $val ? $t(fcfa1($val)) : $t(''), 1, 0, 'R');
                }

                // Net = base + gains - déductions
                $net = (float)$emp['baseSalary'] + $sumGain - $sumDed;
                $pdf->Cell($wNet, $cellsH, $t(fcfa1($net)), 1, 1, 'R');

                $y = $pdf->GetY();
            }

            // Petite ligne vide entre les chunks (si plusieurs pages horizontales)
            if ($chunkIndex < count($chunks) - 1) {
                $pdf->Ln(2);
            }
        }

        // Si pas de variables/lignes, message centré
        if (empty($variables) || empty($employees)) {
            $pdf->Ln(10);
            $pdf->SetFont('Arial', 'I', 11);
            $pdf->Cell(0, 20, mb_convert_encoding("Aucune donnée à afficher pour cette période.", 'ISO-8859-1', 'UTF-8'), 0, 1, 'C');
        }

        return response($pdf->Output('S'))
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'inline; filename="saisie-variables-' . $ticket . '.pdf"');
    }

    
    public function detailParEmployerTablePdf(Request $request, string $ticket)
    {
    function fcfa($n) { return number_format((float)$n, 0, ',', ' ') . ' F CFA'; }
    $entrepriseId = session('entreprise_id');

    // 1) Période depuis le ticket
    $periode = PeriodePaie::where('entreprise_id', $entrepriseId)
        ->where('ticket', $ticket)
        ->firstOrFail();

    // 2) Employés actifs de l’entreprise (mêmes que dans l’écran)
    $users = User::where('entreprise_id', $entrepriseId)
        ->where('statu_user', 1)
        ->orderBy('nom')
        ->get(['id','nom','prenom','salairebase']);

    // 3) Agrégats variables par employé pour cette période
    $aggs = DB::table('variable_periode_users as vpu')
        ->join('variables as v', 'v.id', '=', 'vpu.variable_id')
        ->where('vpu.periode_paie_id', $periode->id)
        ->select([
            'vpu.user_id',
            DB::raw("SUM(CASE WHEN v.type='gain' THEN vpu.montant ELSE 0 END) AS total_gains"),
            DB::raw("SUM(CASE WHEN v.type='deduction' THEN vpu.montant ELSE 0 END) AS total_retenues"),
        ])
        ->groupBy('vpu.user_id')
        ->get()
        ->keyBy('user_id'); // accès rapide: $aggs[$userId]

    // 4) Construire les lignes dynamiques pour le tableau PDF
    $rows = [];
    foreach ($users as $u) {
        $g = (float)($aggs[$u->id]->total_gains ?? 0);
        $r = (float)($aggs[$u->id]->total_retenues ?? 0);
        $rows[] = [
            'name'     => trim(($u->nom ?? '').' '.($u->prenom ?? '')),
            'base'     => (float)($u->salairebase ?? 0),
            'gains'    => $g,
            'retenues' => $r,
        ];
    }

    // 5) Génération du PDF (paysage)
    $pdf = new PdfList('L', 'mm', 'A4');
    $pdf->AliasNbPages();
    $pdf->SetMargins(12, 20, 12);

    // En-tête
    $pdf->logoPath  = public_path(session('entreprise_logo')
        ? 'storage/' . ltrim(session('entreprise_logo'), '/')
        : 'src/image/logo.png');
    $pdf->printedBy = Auth::user()->nom ?? 'Système';
    $pdf->title     = "Détail par Employé du [{$periode->date_debut}] au [{$periode->date_fin}]";

    $pdf->AddPage();

    $t = fn($s) => mb_convert_encoding($s ?? '', 'ISO-8859-1', 'UTF-8');

    // Colonnes
    $left   = 12;
    $pageW  = $pdf->GetPageWidth();
    $right  = 12;
    $usable = $pageW - $left - $right;

    $wEmp  = 108;
    $wBase = 40;
    $wGain = 40;
    $wRet  = 40;
    $wNet  = 45;
    $hRow  = 10;
    
    // Entête du tableau
    $drawHeader = function() use ($pdf, $t, $left, $wEmp, $wBase, $wGain, $wRet, $wNet, $hRow) {
        $pdf->SetFillColor(245,245,245);
        $pdf->SetDrawColor(210,210,210);
        $pdf->SetFont('Arial','B',10);
        $pdf->SetX($left);
        $pdf->Cell($wEmp,  $hRow, $t('EMPLOYÉ'),         1, 0, 'L', true);
        $pdf->Cell($wBase, $hRow, $t('SALAIRE BASE'),    1, 0, 'C', true);
        $pdf->Cell($wGain, $hRow, $t('GAINS VARIABLES'), 1, 0, 'C', true);
        $pdf->Cell($wRet,  $hRow, $t('RETENUES'),        1, 0, 'C', true);
        $pdf->Cell($wNet,  $hRow, $t('SALAIRE NET'),     1, 1, 'C', true);
    };

// Dans la boucle pour chaque employé

    $drawHeader();

    // Corps
    $y = $pdf->GetY();
    $alt = false;

    $totalBase = $totalGain = $totalRet = $totalNet = 0;

    $pdf->SetFont('Arial','',10);
    foreach ($rows as $r) {
        // Saut de page
        if ($y > $pdf->GetPageHeight() - 30) {
            $pdf->AddPage();
            $drawHeader();
            $y = $pdf->GetY();
        }

        $base = (float)$r['base'];
        $gain = (float)$r['gains'];
        $ret  = (float)$r['retenues'];
        $net  = $base + $gain - $ret;

        $totalBase += $base;
        $totalGain += $gain;
        $totalRet  += $ret;
        $totalNet  += $net;

        if ($alt) $pdf->SetFillColor(245,245,245); else $pdf->SetFillColor(255,255,255);
        $pdf->SetDrawColor(230,230,230);

        $pdf->SetX($left);
        $pdf->SetFont('Arial','',10);
        $pdf->Cell($wEmp,  $hRow, $t(mb_strtoupper($r['name'], 'UTF-8')), 1, 0, 'L', true);
        $pdf->Cell($wBase, $hRow, $t(fcfa($base)), 1, 0, 'R', true);
        $pdf->Cell($wGain, $hRow, $t(fcfa($gain)), 1, 0, 'R', true);
        $pdf->Cell($wRet,  $hRow, $t(fcfa($ret)),  1, 0, 'R', true);
        
        $pdf->SetFont('Arial','B',10); // mettre en gras uniquement ici
        $pdf->Cell($wNet,  $hRow, $t(fcfa($net)),  1, 1, 'R', true);
        $pdf->SetFont('Arial','',10); // revenir à normal

        $y = $pdf->GetY();
        $alt = !$alt;
    }

    // Totaux
    $pdf->SetFont('Arial','B',10);
    $pdf->SetFillColor(235,235,235);
    $pdf->SetDrawColor(210,210,210);
    $pdf->SetX($left);
    $pdf->Cell($wEmp,  $hRow, $t('TOTAL'),          1, 0, 'R', true);
    $pdf->Cell($wBase, $hRow, $t(fcfa($totalBase)), 1, 0, 'R', true);
    $pdf->Cell($wGain, $hRow, $t(fcfa($totalGain)), 1, 0, 'R', true);
    $pdf->Cell($wRet,  $hRow, $t(fcfa($totalRet)),  1, 0, 'R', true);
    $pdf->Cell($wNet,  $hRow, $t(fcfa($totalNet)),  1, 1, 'R', true);

    return response($pdf->Output('S'))
        ->header('Content-Type', 'application/pdf')
        ->header('Content-Disposition', 'inline; filename="détail-employé-'.$ticket.'.pdf"');
}
}
