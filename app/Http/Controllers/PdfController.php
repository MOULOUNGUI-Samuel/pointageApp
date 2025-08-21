<?php

namespace App\Http\Controllers;

use App\Models\Entreprise;
use App\Models\Pointage;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

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
        $pdf->title     = 'Employés absents : ' . ((count($users_non_existants) > 0) ?  ': '.count($users_non_existants) : '');

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
        $pdf->printedBy = \Illuminate\Support\Facades\Auth::user()->name ?? 'Système';
        $pdf->title     = 'Employés présents ' . ((count($pointages_oui) > 0) ? ': '.count($pointages_oui) : '');

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
}
