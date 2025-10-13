<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Absence;
use App\Models\Entreprise;
use App\Models\PeriodePaie;
use App\Models\Pointage;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Models\Variable;
use App\Services\AttendanceService;
use FPDF;

require_once base_path('vendor/setasign/fpdf/fpdf.php');

/**
 * Classe FPDF personnalisée
 */

class PdfPayslip extends \FPDF
{
    public string $logoPath   = '';
    public string $title      = 'BULLETIN DE PAIE';
    public string $printedBy  = 'Système';

    // Encodage FPDF (ISO-8859-1)
    protected function t($text)
    {
        return mb_convert_encoding((string)($text ?? ''), 'ISO-8859-1', 'UTF-8');
    }

    // Cellule clé/valeur simple (infos entreprise / salarié)
    public function kvRow($x, $y, $label, $value, $wLabel = 34, $wValue = 70, $boldValue = true)
    {
        $this->SetXY($x, $y);
        $this->SetFont('Arial', '', 10);
        $this->Cell($wLabel, 6, $this->t($label), 0, 0, 'L');
        $this->SetFont('Arial', $boldValue ? 'B' : '', 10);
        $this->Cell($wValue, 6, $this->t($value), 0, 0, 'L');
    }

    public function money($v)
    {
        return number_format((float)$v, 0, ',', ' ');
    }

    // ======== HEADER / FOOTER ========
    public function Header()
    {
        // IMPORTANT : on ne dessine RIEN ici pour éviter tout chevauchement
        // avec drawCompanyTitleCentered(), drawRightIdentityCard(), etc.
        // Si tu veux un logo global sur toutes les pages, décommenter :
        // if ($this->logoPath && @is_file($this->logoPath)) {
        //     $this->Image($this->logoPath, 12, 10, 20);
        // }
    }
    // --- Titre BULLETIN DE PAIE centré + 2 lignes période/paiement
    public function drawBulletinTitleAndPeriod(array $periode, float $y = 15): float
    {
        // Titre
        $this->SetFont('Arial', 'B', 24);
        $title = $this->t('BULLETIN DE PAIE');

        // Utiliser une largeur de cellule qui remplit toute la page pour faciliter le centrage
        $this->SetXY(0, $y);
        $this->Cell(300, -10, $title, 0, 1, 'C'); // Largeur = largeur de la page, Alignement = Centre

        // Deux lignes centrées (mêmes polices que ton exemple)
        $this->SetFont('Arial', '', 8);
        $l1 = $this->t('Période du   ' . $periode['du'] . '   au   ' . $periode['au']);
        $l2 = $this->t('Paiement le   ' . $periode['paiement'] . '   par   ' . $periode['mode']);

        // Espacement vertical entre les lignes
        $lineSpacing = 4;

        // Calcul des positions Y pour les lignes
        $y1 = $y;
        $y2 = $y  + $lineSpacing;

        // Affichage des lignes
        $this->SetXY(125, $y1);
        $this->Cell($this->GetStringWidth($l1), 6, $l1, 0, 1, 'L'); // Conserver l'alignement à gauche pour que le calcul du centrage fonctionne
        $this->SetXY(125, $y2);
        $this->Cell($this->GetStringWidth($l2), 6, $l2, 0, 1, 'L');

        // Renvoie le Y à partir duquel tu peux continuer à dessiner
        return $y + 16 + ($lineSpacing * 2);
    }

    public function Footer()
    {
        $this->SetY(-16);
        $this->SetFont('Arial', '', 8);
        $this->Cell(0, 6, $this->t("Pour vous aider à faire valoir vos droits, conservez ce bulletin de paie sans limitation de durée."), 0, 1, 'L');
        $this->SetFont('Arial', 'I', 8);
        $this->Cell(0, 6, $this->t('Page ') . $this->PageNo() . '/' . '{nb}', 0, 0, 'R');
    }

    /* ====== ENTÊTE (dessinée dans le corps de page) ====== */

    // Titre société centré (trois lignes)
    public function drawCompanyTitleCentered(array $e): void
    {
        $left  = 12;   // marge gauche du bloc
        $top   = 4;   // y de départ pour le logo
        $gap   = 3;    // espace entre logo et texte (mm)
        $logoH = 14;   // hauteur du logo (garde les proportions)

        // Logo au-dessus
        $yText = $top; // y du texte (sera ajusté si logo)
        if ($this->logoPath && @is_file($this->logoPath)) {
            // w=0, h=$logoH -> conserve les proportions
            $this->Image($this->logoPath, $left, $top, 0, $logoH);
            $yText = $top + $logoH + $gap;
        }

        // Nom de la société
        $this->SetXY($left, $yText);
        $this->SetFont('Arial', 'B', 16);
        $this->Cell(0, 7, $this->t($e['nom'] ?? ''), 0, 1, 'L');

        // Adresse / téléphone
        $this->SetFont('Arial', '', 12);
        $this->SetXY($left, $this->GetY());
        $this->Cell(0, 6, $this->t('Boite Postale : ' . ($e['bp'] ?? '') . '     ' . ($e['ville'] ?? '')), 0, 1, 'L');
        $this->SetXY($left, $this->GetY());
        $this->Cell(0, 6, $this->t('TEL : ' . ($e['tel'] ?? '')), 0, 1, 'L');
    }



    // Carte identité salarié à droite (nom en gras en haut)
    public function drawRightIdentityCard(array $id): void
    {
        // Cadre à droite
        $x = 110;           // position X du cadre
        $y = 25;            // position Y du cadre
        $w = 90;            // largeur du cadre

        // Géométrie interne
        $m      = 3;                    // marge interne
        $ix     = $x + $m;              // X interne
        $iy     = $y + $m;              // Y interne
        $innerW = $w - 2 * $m;            // largeur utile
        $lh     = 5;                    // hauteur de ligne
        $wLabel = 26;                   // largeur des libellés
        $wVal   = $innerW - $wLabel;    // largeur des valeurs

        // Calcule la hauteur nécessaire (nom + naissance + 4 lignes kv + marge basse)
        $neededH = 6 /*nom*/ + $lh /*naissance*/ + 4 * $lh /*4 lignes*/
            + $m; // marge basse

        $h = max(35, $neededH);         // hauteur du cadre (min 38mm)
        $this->Rect($x, $y, $w, $h);

        // Contenu du cadre
        // 1) Nom en gras
        $this->SetXY($ix, $iy);
        $this->SetFont('Arial', 'B', 12);
        $this->Cell($innerW, 6, $this->t(($id['civilite'] ?? '') . '  ' . ($id['nom'] ?? '')), 0, 1, 'L');

        // 2) Ligne naissance (tout sur une seule ligne, à l'intérieur du cadre)
        $this->SetFont('Arial', '', 10);
        $this->SetXY($ix, $this->GetY());
        $naiss = 'Né(e) le: ' . ($id['naissance'] ?? '') . '   à ' . ($id['lieu'] ?? '');
        $this->MultiCell($innerW, $lh, $this->t($naiss), 0, 'L');  // MultiCell pour rester dans la largeur

        // 3) Paires libellé/valeur
        $rows = [
            ['N° CNSS',   $id['cnss']      ?? ''],
            ['Matricule', $id['matricule'] ?? ''],
            ['Poste',     $id['poste']     ?? ''],
            ['Téléphone', $id['telephone'] ?? ''],
        ];

        foreach ($rows as [$label, $value]) {
            $this->SetXY($ix, $this->GetY());
            $this->SetFont('Arial', '', 10);
            $this->Cell($wLabel, $lh, $this->t($label), 0, 0, 'L');
            $this->SetFont('Arial', 'B', 10);
            $this->Cell($wVal,   $lh, $this->t($value), 0, 1, 'L');
        }
    }


    // Bloc infos “Département / Si.Fam / Enfants / Parts / Catégorie / Horaire” (à gauche)
    public function drawLeftEmployeeMeta(array $m): void
    {
        $x = 12;
        $y = 45;
        $this->SetXY($x, $y);
        $this->SetFont('Arial', '', 11);

        $this->Cell(30, 6, $this->t('Département'), 0, 0, 'L');
        $this->SetFont('Arial', 'B', 11);
        $this->Cell(70, 6, $this->t($m['departement'] ?? ''), 0, 1, 'L');

        $this->SetFont('Arial', '', 11);
        $this->Cell(20, 6, $this->t('Si.Fam'), 0, 0, 'L');
        $this->SetFont('Arial', 'B', 11);
        $this->Cell(38, 6, $this->t($m['situation_fam'] ?? ''), 0, 0, 'L');

        $this->SetFont('Arial', '', 11);
        $this->Cell(32, 6, $this->t("Nbres d'enfants"), 0, 0, 'L');
        $this->SetFont('Arial', 'B', 11);
        $this->Cell(16, 6, $this->t($m['nb_enfants'] ?? ''), 0, 0, 'L');
        $this->Ln(5);
        $this->SetFont('Arial', '', 11);
        $this->Cell(24, 6, $this->t('Catégorie'), 0, 0, 'L');
        $this->SetFont('Arial', 'B', 11);
        $this->Cell(30, 6, $this->t($m['categorie'] ?? ''), 0, 0, 'L');

        $this->SetFont('Arial', '', 11);
        $this->Cell(18, 6, $this->t('Horaire'), 0, 0, 'L');
        $this->SetFont('Arial', 'B', 11);
        $this->Cell(0, 6,  $this->t($m['horaire'] ?? ''), 0, 1, 'L');
    }

    /* ====== TABLEAU PRINCIPAL ====== */
    public function drawTableHeader(float $yStart = 68): array
    {
        $x = 12;
        $y = $yStart;
        $h1 = 7;              // hauteur ligne 1 (titres de groupes)
        $h2 = 7;              // hauteur ligne 2 (sous-titres)
        $mergeH = $h1 + $h2;  // hauteur fusionnée
        $w = [12, 54, 16, 24, 12, 24, 16, 12, 16];

        $this->SetFont('Arial', 'B', 10);

        // ---------- Ligne 1 : colonnes fusionnées + entêtes de groupes ----------
        $this->SetXY($x, $y);
        // Colonnes fusionnées sur 2 lignes
        $this->Cell($w[0], $mergeH, $this->t('N°'),           1, 0, 'C');
        $this->Cell($w[1], $mergeH, $this->t('Désignation'),  1, 0, 'C');
        $this->Cell($w[2], $mergeH, $this->t('Nombre'),       1, 0, 'C');
        $this->Cell($w[3], $mergeH, $this->t('Base'),         1, 0, 'C');

        // Entêtes de groupes (sur h1 seulement)
        $this->Cell($w[4] + $w[5] + $w[6], $h1, $this->t('Part salariale'), 1, 0, 'C');
        $this->Cell($w[7] + $w[8],         $h1, $this->t('Part patronale'), 1, 1, 'C');

        // ---------- Ligne 2 : sous-titres des groupes ----------
        $xSub = $x + $w[0] + $w[1] + $w[2] + $w[3]; // on démarre après les 4 colonnes fusionnées
        $this->SetXY($xSub, $y + $h1);
        $this->Cell($w[4], $h2, $this->t('Taux'),    1, 0, 'C');
        $this->Cell($w[5], $h2, $this->t('Gain'),    1, 0, 'C');
        $this->Cell($w[6], $h2, $this->t('Retenue'), 1, 0, 'C');
        $this->Cell($w[7], $h2, $this->t('Taux'),    1, 0, 'C');
        $this->Cell($w[8], $h2, $this->t('Retenue'), 1, 1, 'C');

        // La prochaine ligne de données commencera après mergeH
        return ['y' => $y + $mergeH, 'w' => $w];
    }



    public function drawRow(array $w, float $y, array $row, int $rh = 6, bool $isLast = false): float
    {
        $this->SetXY(12, $y);
        $this->SetFont('Arial', '', 9);

        // Ajuste selon tes colonnes ; défaut 'L' si non défini
        $align = ['C', 'L', 'C', 'R', 'C', 'R', 'R', 'C', 'R'];

        // Bordure: lignes intermédiaires = 'LR', dernière = 'LRB'
        $border = $isLast ? 'LRB' : 'LR';

        foreach ($row as $i => $val) {
            $this->Cell($w[$i] ?? 0, $rh, $this->t($val ?? ''), $border, 0, $align[$i] ?? 'L');
        }
        $this->Ln($rh);
        return $y + $rh;
    }


    /* ====== BAS DE PAGE ====== */

    // === Cumuls + NET A PAYER (avec espace entre les deux) ===
    public function drawCumulsAndNet(array $cumuls, $net, float $yStart = null): float
    {
        $x = 12;
        $y = $yStart ?? ($this->GetPageHeight() - 88); // ancré vers le bas mais avec marge
        $headerH = 5;   // 2 lignes d’en-tête
        $rowH    = 6;   // 2 lignes de données
        $netW    = 28;  // colonne NET
        $gapNet  = 2;   // espace entre Cumuls et NET

        // Largeurs colonnes CUMULS (somme = 146)
        $widths = [15, 18, 18, 18, 22, 18, 22, 25]; // = 146
        $cumulsW = array_sum($widths);

        // --- En-têtes (2 lignes via MultiCell) ---
        $headers = [
            "Cumuls\n.",
            "Salaire\nbrut",
            'Charges salariales',
            'Charges patronales',
            'Avantages en nature',
            "Net\nimposable",
            "Heures\ntravaillées",
            "Heures\nsupplémentaires",
        ];
        $this->SetFont('Arial', 'B', 8);
        $this->SetFillColor(225, 225, 225);
        $this->SetXY($x, $y);
        foreach ($headers as $i => $h) {
            $cx = $this->GetX();
            $cy = $this->GetY();
            $this->MultiCell($widths[$i], $headerH, $this->t($h), 1, 'C', true);
            $this->SetXY($cx + $widths[$i], $cy);
        }
        // position sous l’en-tête (2 lignes)
        $this->SetXY($x, $y + $headerH * 2);

        // --- Ligne Période ---
        $this->SetFont('Arial', '', 8);
        $p = [
            'Période',
            $this->money($cumuls['periode']['brut'] ?? 0),
            $this->money($cumuls['periode']['charges_sal'] ?? 0),
            $this->money($cumuls['periode']['charges_pat'] ?? 0),
            $this->money($cumuls['periode']['avantages'] ?? 0),
            $this->money($cumuls['periode']['net_imposable'] ?? 0),
            (string)($cumuls['periode']['heures_trav'] ?? 0),
            (string)($cumuls['periode']['heures_sup'] ?? 0),
        ];
        foreach ($p as $i => $v) {
            $this->Cell($widths[$i], $rowH, $this->t($v), 1, 0, ($i >= 1 && $i <= 5) ? 'R' : 'C');
        }
        $this->Ln($rowH);

        // --- Ligne Année ---
        $this->SetX($x);
        $a = [
            'Année',
            $this->money($cumuls['annee']['brut'] ?? 0),
            $this->money($cumuls['annee']['charges_sal'] ?? 0),
            $this->money($cumuls['annee']['charges_pat'] ?? 0),
            $this->money($cumuls['annee']['avantages'] ?? 0),
            $this->money($cumuls['annee']['net_imposable'] ?? 0),
            (string)($cumuls['annee']['heures_trav'] ?? 0),
            (string)($cumuls['annee']['heures_sup'] ?? 0),
        ];
        foreach ($a as $i => $v) {
            $this->Cell($widths[$i], $rowH, $this->t($v), 1, 0, ($i >= 1 && $i <= 5) ? 'R' : 'C');
        }

        // --- Bloc NET A PAYER à droite avec un espace ---
        $netX = $x + $cumulsW + $gapNet;
        $netY = $y;
        $this->SetXY($netX, $netY);
        $this->SetFont('Arial', 'B', 9);
        $this->SetFillColor(210, 210, 210);
        $this->Cell($netW, $headerH * 2, $this->t('NET A PAYER'), 1, 2, 'C', true);

        $this->SetX($netX);
        $this->SetFont('Arial', 'B', 12);
        $this->Cell($netW, $rowH * 2, $this->t($this->money($net)), 1, 1, 'C');

        // Y juste sous le bloc cumuls+net
        $yNext = $y + $headerH * 2 + $rowH * 2;
        $this->SetY($yNext);
        return $yNext;
    }

    // === Compteurs + Signatures (placés avec un yStart donné) ===
    public function drawCountersAndSignatures(array $compteurs, ?float $yStart = null): void
    {
        $x = 12;
        $y = $yStart ?? ($this->GetY() + 6);

        $headers = ['Compteurs', 'Pris', 'Restant', 'Acquis'];
        $widths  = [35, 26, 26, 28]; // = 106
        $headerH = 7;
        $rowH = 7;

        $this->SetFont('Arial', 'B', 9);
        $this->SetFillColor(230, 230, 230);
        $this->SetXY($x, $y);
        foreach ($headers as $i => $h) {
            $this->Cell($widths[$i], $headerH, $this->t($h), 1, 0, 'C', true);
        }
        $this->Ln($headerH);

        $this->SetFont('Arial', '', 9);
        foreach (['Congés', 'Repos compensateur'] as $k) {
            $row = $compteurs[$k] ?? ['pris' => '0,000', 'restant' => '0,000', 'acquis' => '0,000'];
            $this->SetX($x);
            $this->Cell($widths[0], $rowH, $this->t($k), 1, 0, 'L');
            $this->Cell($widths[1], $rowH, $this->t($row['pris']), 1, 0, 'C');
            $this->Cell($widths[2], $rowH, $this->t($row['restant']), 1, 0, 'C');
            $this->Cell($widths[3], $rowH, $this->t($row['acquis']), 1, 1, 'C');
        }

        $tableH = $headerH + $rowH * 2;

        // Pavés à droite du tableau (avec un petit gap horizontal)
        $gap = 2;
        $xSign = $x + array_sum($widths) + $gap;
        $right = $this->GetPageWidth() - 12;
        $signW = max(0, $right - $xSign);
        $wBox  = $signW / 2;

        $this->Rect($xSign,         $y, $wBox, $tableH);
        $this->Rect($xSign + $wBox, $y, $wBox, $tableH);

        $this->SetFont('Arial', 'B', 11);
        $this->SetXY($xSign, $y + 2);
        $this->Cell($wBox, 6, $this->t('Employeur'), 0, 0, 'C');
        $this->SetXY($xSign + $wBox, $y + 2);
        $this->Cell($wBox, 6, $this->t('Employé'),   0, 0, 'C');

        $this->SetY($y + $tableH + 2);
    }
}



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
                $name = trim(($user->nom ?? '') . ' ' . ($user->prenom ?? $user->prenom ?? $user->nom ?? ''));
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

                $name = trim(($user->nom ?? '') . ' ' . ($user->prenom ?? $user->prenom ?? $user->nom ?? ''));
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
    public function imprimeListePresence(Request $request, string $date_start, string $date_end)
    {
        // ----- Entreprise + période
        $entreprise_id = session('entreprise_id');
        $entreprise    = Entreprise::findOrFail($entreprise_id);

        try {
            $start = Carbon::parse($date_start)->startOfDay();
            $end   = Carbon::parse($date_end)->endOfDay();
            if ($end->lt($start)) {
                [$start, $end] = [$end, $start];
            }
        } catch (\Throwable $e) {
            abort(422, 'Dates invalides.');
        }

        Carbon::setLocale('fr');
        $periodeTxt = $start->translatedFormat('l d F Y') . ' / ' . $end->translatedFormat('l d F Y');
        $printedBy  = Auth::user()->nom ?? 'Système';

        // ----- Utilisateurs actifs de l’entreprise
        $users = User::where('entreprise_id', $entreprise_id)
            ->where('statu_user', 1)
            ->where('statut', 1)
            ->get(['id', 'nom', 'prenom']);
        $userIds = $users->pluck('id');
        // Map pratique id -> [nom, prenom]
        $userMap = $users->keyBy('id');

        // ----- Jours ouvrés (sans WE / jours fériés France)
        $yasumiCache = [];
        $isHoliday = function (Carbon $d) use (&$yasumiCache): bool {
            $y = $d->year;
            if (!isset($yasumiCache[$y])) {
                $yasumiCache[$y] = \Yasumi\Yasumi::create('France', $y);
            }
            return $yasumiCache[$y]->isHoliday($d);
        };
        $isWorkingDay = function (Carbon $d) use ($isHoliday): bool {
            return !$d->isWeekend() && !$isHoliday($d);
        };

        // ----- Paramètres entreprise
        $heureDebutJour = $entreprise->heure_ouverture;      // "HH:mm:ss"
        $heureFinJour   = $entreprise->heure_fin;            // "HH:mm:ss"
        $pauseDebut     = $entreprise->heure_debut_pose;     // "HH:mm:ss" ou null
        $pauseFin       = $entreprise->heure_fin_pose;       // "HH:mm:ss" ou null
        $toleranceMin   = (int)($entreprise->minute_pointage_limite ?? 0);

        // ----- 1) POINTAGES sur la période (basé sur date_arriver)
        $pointagesRaw = Pointage::with('user:id,nom,prenom')
            ->whereIn('user_id', $userIds)
            ->whereBetween('date_arriver', [$start->toDateString(), $end->toDateString()])
            ->orderBy('date_arriver')
            ->orderBy('heure_arriver')
            ->get();

        // util: chevauchement
        $overlapSeconds = function (?Carbon $a1, ?Carbon $a2, ?Carbon $b1, ?Carbon $b2): int {
            if (!$a1 || !$a2 || !$b1 || !$b2) return 0;
            if ($a2->lte($a1) || $b2->lte($b1)) return 0;
            $s = $a1->gt($b1) ? $a1->copy() : $b1->copy();
            $e = $a2->lt($b2) ? $a2->copy() : $b2->copy();
            return $e->gt($s) ? $e->diffInSeconds($s) : 0;
        };

        // Pour créer les absences injustifiées : set des dates pointées / absences OK par user
        $datesAvecPointage = [];   // [user_id][YYYY-mm-dd] => true
        $datesAbsencesOK   = [];   // [user_id][YYYY-mm-dd] => true

        // ----- Construire les lignes de pointage + statut à l'heure/en retard
        $pointageRows = collect();

        foreach ($pointagesRaw as $p) {
            $date = Carbon::parse($p->date_arriver);
            if (!$isWorkingDay($date)) continue;

            $jourStart = $heureDebutJour ? Carbon::parse($date->toDateString() . ' ' . $heureDebutJour) : null;
            $jourEnd   = $heureFinJour   ? Carbon::parse($date->toDateString() . ' ' . $heureFinJour)   : null;
            $pauseS    = ($pauseDebut && $pauseFin) ? Carbon::parse($date->toDateString() . ' ' . $pauseDebut) : null;
            $pauseE    = ($pauseDebut && $pauseFin) ? Carbon::parse($date->toDateString() . ' ' . $pauseFin)   : null;

            $inDT  = $p->heure_arriver ? Carbon::parse($date->toDateString() . ' ' . $p->heure_arriver) : null;
            $outDT = $p->heure_fin     ? Carbon::parse($date->toDateString() . ' ' . $p->heure_fin)     : null;

            // fallback sortie = heure_fin entreprise du même jour si heure_arriver existe
            $outEff = $outDT ?: ($inDT && $jourEnd ? $jourEnd : null);

            // Statut "À l'heure" / "En retard" avec tolérance
            $statutCalc = '';
            if ($inDT && $jourStart) {
                $limite = $jourStart->copy()->addMinutes($toleranceMin);
                $statutCalc = $inDT->gt($limite) ? 'En retard' : 'À l\'heure';
            }

            $inTxt  = $p->heure_arriver ? $inDT->format('H:i:s') : '-- : -- : --';
            $outTxt = $p->heure_fin
                ? $outDT->format('H:i:s')
                : ($p->heure_arriver && $heureFinJour
                    ? ($jourEnd?->format('H:i:s') ?? '-- : -- : --')
                    : '-- : -- : --');

            $u = $p->user ?? $userMap->get($p->user_id);
            $pointageRows->push([
                'user_id' => $p->user_id,
                'nom'     => trim($u->nom ?? ''),
                'prenom'  => trim($u->prenom ?? ''),
                'date'    => $date->translatedFormat('l d F Y'),
                'in'      => $inTxt,
                'out'     => $outTxt,
                'statut'  => $statutCalc ?: ($p->statut ?? ''), // priorité au statut calculé
                'sortkey' => $date->format('Y-m-d') . ' 1 ' . str_pad((string)$p->user_id, 6, '0', STR_PAD_LEFT),
            ]);

            $datesAvecPointage[$p->user_id][$date->toDateString()] = true;
        }

        // ----- 2) ABSENCES approuvées chevauchant la période (déroulées par jour)
        $absences = Absence::with('user:id,nom,prenom')
            ->whereIn('user_id', $userIds)
            ->where('status', 'approuvé')
            ->where(function ($q) use ($start, $end) {
                $q->whereDate('start_datetime', '<=', $end)
                    ->whereDate('end_datetime', '>=', $start);
            })
            ->get();

        $absenceRows = collect();
        foreach ($absences as $a) {
            $u     = $a->user ?? $userMap->get($a->user_id);
            $from  = Carbon::parse($a->start_datetime)->startOfDay();
            $to    = Carbon::parse($a->end_datetime)->endOfDay();
            // recadrage sur la fenêtre demandée
            if ($from->lt($start)) $from = $start->copy();
            if ($to->gt($end))     $to   = $end->copy();

            for ($d = $from->copy(); $d->lte($to); $d->addDay()) {
                if (!$isWorkingDay($d)) continue;
                $datesAbsencesOK[$a->user_id][$d->toDateString()] = true;

                $absenceRows->push([
                    'user_id' => $a->user_id,
                    'nom'     => trim($u->nom ?? ''),
                    'prenom'  => trim($u->prenom ?? ''),
                    'date'    => $d->translatedFormat('l d F Y'),
                    'in'      => '-- : -- : --',
                    'out'     => '-- : -- : --',
                    'statut'  => 'Absence approuvée',
                    'sortkey' => $d->format('Y-m-d') . ' 0 ' . str_pad((string)$a->user_id, 6, '0', STR_PAD_LEFT),
                ]);
            }
        }

        // ----- 3) ABSENCES INJUSTIFIÉES (par user, par jour ouvré sans pointage ni absence approuvée)
        $absInjRows = collect();
        for ($d = $start->copy(); $d->lte($end); $d->addDay()) {
            if (!$isWorkingDay($d)) continue;
            $dStr = $d->toDateString();

            foreach ($userIds as $uid) {
                $hasPointage = !empty($datesAvecPointage[$uid][$dStr]);
                $hasAbsOK    = !empty($datesAbsencesOK[$uid][$dStr]);

                if (!$hasPointage && !$hasAbsOK) {
                    $u = $userMap->get($uid);
                    $absInjRows->push([
                        'user_id' => $uid,
                        'nom'     => trim($u->nom ?? ''),
                        'prenom'  => trim($u->prenom ?? ''),
                        'date'    => $d->translatedFormat('l d F Y'),
                        'in'      => '-- : -- : --',
                        'out'     => '-- : -- : --',
                        'statut'  => 'Absent injustifié',
                        'sortkey' => $d->format('Y-m-d') . ' 0 ' . str_pad((string)$uid, 6, '0', STR_PAD_LEFT),
                    ]);
                }
            }
        }

        // ----- Fusion + tri
        $rows = $absenceRows->merge($absInjRows)->merge($pointageRows)->sortBy('sortkey')->values();

        // ----- PDF (A4 paysage)
        $logoPath = public_path(session('entreprise_logo')
            ? 'storage/' . ltrim(session('entreprise_logo'), '/')
            : 'src/image/logo.png');

        $pdf = new class('L', 'mm', 'A4') extends FPDF {
            public string $logoPath = '';
            public string $entrepriseName = '';
            public string $periode = '';
            public string $printedBy = '';

            public function Header()
            {
                if (is_file($this->logoPath)) {
                    $this->Image($this->logoPath, 10, 8, 22);
                }
                // Alignement à droite propre
                $this->SetFont('Arial', 'B', 14);
                $this->Cell(0, 7, utf8_decode($this->entrepriseName), 0, 1, 'R');

                $this->SetFont('Arial', '', 10);
                $this->Cell(0, 6, utf8_decode("Période : " . $this->periode), 0, 1, 'R');
                $this->Cell(0, 5, utf8_decode("Imprimé par : " . $this->printedBy), 0, 1, 'R');

                $this->Ln(3);

                $this->SetFont('Arial', 'B', 10);
                $this->SetFillColor(230, 230, 230);
                $this->SetDrawColor(200, 200, 200);

                $w = [
                    'nom'    => 65,
                    'prenom' => 45,
                    'date'   => 60,
                    'in'     => 35,
                    'out'    => 35,
                    'statut' => 37,
                ];

                $this->Cell($w['nom'],    9, utf8_decode('Nom(s)'),        1, 0, 'L', true);
                $this->Cell($w['prenom'], 9, utf8_decode('Prénom(s)'),     1, 0, 'L', true);
                $this->Cell($w['date'],   9, utf8_decode('Date'),          1, 0, 'L', true);
                $this->Cell($w['in'],     9, utf8_decode('Heure arrivée'), 1, 0, 'C', true);
                $this->Cell($w['out'],    9, utf8_decode('Heure sortie'),  1, 0, 'C', true);
                $this->Cell($w['statut'], 9, utf8_decode('Statut'),        1, 1, 'C', true);
            }

            public function Footer()
            {
                $this->SetY(-15);
                $this->SetFont('Arial', 'I', 8);
                $this->SetTextColor(120, 120, 120);
                $this->Cell(0, 10, utf8_decode('Page ' . $this->PageNo() . '/{nb}'), 0, 0, 'C');
            }
        };

        $pdf->AliasNbPages();
        $pdf->SetMargins(10, 25, 10);
        $pdf->SetAutoPageBreak(true, 18);
        $pdf->logoPath       = $logoPath;
        $pdf->entrepriseName = $entreprise->nom_entreprise ?? 'Entreprise';
        $pdf->periode        = $periodeTxt;
        $pdf->printedBy      = $printedBy;

        $pdf->AddPage();
        $pdf->SetFont('Arial', '', 10);
        $pdf->SetDrawColor(220, 220, 220);

        $w = [
            'nom'    => 65,
            'prenom' => 45,
            'date'   => 60,
            'in'     => 35,
            'out'    => 35,
            'statut' => 37,
        ];

        // mapping numérique -> libellé (si besoin)
        $statusMap = [
            0 => "À l'heure",
            1 => "En retard",
            2 => "Absent injustifié",
            3 => "Absence approuvée",
        ];

        if ($rows->isEmpty()) {
            $pdf->Ln(10);
            $pdf->SetFont('Arial', 'I', 11);
            $pdf->Cell(0, 10, utf8_decode("Aucune donnée sur la période."), 0, 1, 'C');
        } else {
            foreach ($rows as $r) {
                $pdf->Cell($w['nom'],    8, utf8_decode((string)$r['nom']),    1, 0, 'L');
                $pdf->Cell($w['prenom'], 8, utf8_decode((string)$r['prenom']), 1, 0, 'L');
                $pdf->Cell($w['date'],   8, utf8_decode((string)$r['date']),   1, 0, 'L');
                $pdf->Cell($w['in'],     8, utf8_decode((string)$r['in']),     1, 0, 'C');
                $pdf->Cell($w['out'],    8, utf8_decode((string)$r['out']),    1, 0, 'C');

                // Normalisation du statut (int ou string) + couleurs
                $raw      = $r['statut'] ?? '';
                $statText = is_numeric($raw) ? ($statusMap[(int)$raw] ?? (string)$raw) : (string)$raw;
                $lower    = mb_strtolower($statText, 'UTF-8');

                if (strpos($lower, 'heure') !== false) {
                    $pdf->SetTextColor(0, 128, 0);       // vert
                } elseif (strpos($lower, 'retard') !== false) {
                    $pdf->SetTextColor(200, 0, 0);       // rouge
                } elseif (
                    strpos($lower, 'absence') !== false
                    || strpos($lower, 'absent') !== false
                ) {
                    $pdf->SetTextColor(180, 120, 0);     // orange
                } else {
                    $pdf->SetTextColor(80, 80, 80);      // gris
                }

                $pdf->Cell($w['statut'], 8, utf8_decode($statText ?: '—'), 1, 1, 'C');
                $pdf->SetTextColor(0, 0, 0);
            }
        }

        // Nom de fichier (simple et safe)
        $safeEnt = preg_replace('~[^A-Za-z0-9_\-]~', '_', (string)($entreprise->nom_entreprise ?? 'Entreprise'));
        $filename = "presence_{$safeEnt}_{$start->format('Ymd')}-{$end->format('Ymd')}.pdf";

        return response($pdf->Output('S'))
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'inline; filename="' . $filename . '"');
    }


    public function imprimeListePresenceUser(Request $request, string $date_start, string $date_end, string $userId)
    {
        // ----- Utilisateur + entreprise (pour les horaires)
        $user       = User::findOrFail($userId);
        $entreprise = Entreprise::findOrFail($user->entreprise_id);

        try {
            $start = Carbon::parse($date_start)->startOfDay();
            $end   = Carbon::parse($date_end)->endOfDay();
            if ($end->lt($start)) {
                [$start, $end] = [$end, $start];
            }
        } catch (\Throwable $e) {
            abort(422, 'Dates invalides.');
        }

        Carbon::setLocale('fr');
        $periodeTxt = $start->translatedFormat('l d F Y') . ' / ' . $end->translatedFormat('l d F Y');
        $printedBy  = Auth::user()->nom ?? 'Système';

        // ----- utilitaires jours ouvrés (sans WE ni jours fériés FR)
        $yasumiCache = [];
        $isHoliday = function (Carbon $d) use (&$yasumiCache): bool {
            $y = $d->year;
            if (!isset($yasumiCache[$y])) {
                $yasumiCache[$y] = \Yasumi\Yasumi::create('France', $y);
            }
            return $yasumiCache[$y]->isHoliday($d);
        };
        $isWorkingDay = function (Carbon $d) use ($isHoliday): bool {
            return !$d->isWeekend() && !$isHoliday($d);
        };

        // ----- 1) POINTAGES du seul utilisateur
        $pointagesRaw = Pointage::with('user:id,nom,prenom')
            ->where('user_id', $userId)
            ->whereBetween('date_arriver', [$start->toDateString(), $end->toDateString()])
            ->orderBy('date_arriver')
            ->orderBy('heure_arriver')
            ->get();

        // ----- Paramètres entreprise
        $heureDebutJour = $entreprise->heure_ouverture;
        $heureFinJour   = $entreprise->heure_fin;
        $pauseDebut     = $entreprise->heure_debut_pose;
        $pauseFin       = $entreprise->heure_fin_pose;
        $toleranceMin   = (int)($entreprise->minute_pointage_limite ?? 0);

        $totalWorkedSec  = 0;
        $totalOverSec    = 0;
        $totalLateSec    = 0;
        $expectedWorkSec = 0;

        $approvedAbsenceSec    = 0; // total absences approuvées (heures)
        $unjustifiedAbsenceSec = 0; // total absences injustifiées (heures)

        $expectedDailySecByDate = []; // 'Y-m-d' => seconds attendus ce jour
        $workedSecByDate        = []; // 'Y-m-d' => seconds travaillés (somme des pointages du jour)
        $datesAvecPointage      = []; // 'Y-m-d' => true si au moins un pointage
        $datesAbsencesOK        = []; // 'Y-m-d' => true si absence approuvée couvre ce jour

        // util: chevauchement (pour retirer la pause)
        $overlapSeconds = function (?Carbon $a1, ?Carbon $a2, ?Carbon $b1, ?Carbon $b2): int {
            if (!$a1 || !$a2 || !$b1 || !$b2) return 0;
            if ($a2->lte($a1) || $b2->lte($b1)) return 0;
            $s = $a1->gt($b1) ? $a1->copy() : $b1->copy();
            $e = $a2->lt($b2) ? $a2->copy() : $b2->copy();
            return $e->gt($s) ? $e->diffInSeconds($s) : 0;
        };

        // ----- Heures prévues (jours ouvrés) + mémoriser par date
        for ($d = $start->copy(); $d->lte($end); $d->addDay()) {
            if (!$isWorkingDay($d) || !$heureDebutJour || !$heureFinJour) continue;

            $jourStart = Carbon::parse($d->toDateString() . ' ' . $heureDebutJour);
            $jourEnd   = Carbon::parse($d->toDateString() . ' ' . $heureFinJour);
            if ($jourEnd->lte($jourStart)) continue;

            $daily = $jourEnd->diffInSeconds($jourStart);
            if ($pauseDebut && $pauseFin) {
                $pauseS = Carbon::parse($d->toDateString() . ' ' . $pauseDebut);
                $pauseE = Carbon::parse($d->toDateString() . ' ' . $pauseFin);
                $daily -= $overlapSeconds($jourStart, $jourEnd, $pauseS, $pauseE);
            }
            $daily = max(0, $daily);
            $expectedWorkSec += $daily;
            $expectedDailySecByDate[$d->toDateString()] = $daily;
        }

        // ----- Calculs par pointage (ignore WE/fériés) + statut
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

            // total travaillé (et par jour)
            if ($inDT && $outEff && $outEff->gt($inDT)) {
                $worked = $outEff->diffInSeconds($inDT);
                $worked -= $overlapSeconds($inDT, $outEff, $pauseS, $pauseE);
                if ($worked < 0) $worked = 0;
                $totalWorkedSec += $worked;

                $dKey = $date->toDateString();
                $workedSecByDate[$dKey] = ($workedSecByDate[$dKey] ?? 0) + $worked;
            }

            // supplémentaires
            if ($outEff && $jourEnd && $outEff->gt($jourEnd)) {
                $totalOverSec += $outEff->diffInSeconds($jourEnd);
            }

            // statut "à l'heure" / "en retard" (basé sur heure d'ouverture + tolérance)
            $p->computed_statut = '';
            $p->computed_late_s = 0;
            if ($inDT && $jourStart) {
                $limite = $jourStart->copy()->addMinutes($toleranceMin);
                if ($inDT->gt($limite)) {
                    $p->computed_statut = 'En retard';
                    $p->computed_late_s = $inDT->diffInSeconds($limite);
                    $totalLateSec      += $p->computed_late_s;
                } else {
                    $p->computed_statut = 'À l\'heure';
                }
            }

            $datesAvecPointage[$date->toDateString()] = true;
        }

        // ----- Lignes d'affichage (jours ouvrés) + fallback heure fin entreprise
        $pointages = $pointagesRaw
            ->filter(fn($p) => $isWorkingDay(Carbon::parse($p->date_arriver)))
            ->map(function ($p) use ($entreprise) {
                $u   = $p->user;
                $date = Carbon::parse($p->date_arriver);

                $in = $p->heure_arriver
                    ? Carbon::parse($p->heure_arriver)->format('H:i:s')
                    : '-- : -- : --';
                $out = $p->heure_fin
                    ? Carbon::parse($p->heure_fin)->format('H:i:s')
                    : ($p->heure_arriver && $entreprise->heure_fin
                        ? Carbon::parse("{$p->date_arriver} {$entreprise->heure_fin}")->format('H:i:s')
                        : '-- : -- : --');

                $statut = $p->computed_statut ?? '';
                if (!$statut) {
                    $raw = $p->statut ?? '';
                    $statut = is_numeric($raw) ? (string)$raw : (string)$raw;
                }

                return [
                    'user_id' => $p->user_id,
                    'nom'     => trim($u->nom ?? ''),
                    'prenom'  => trim($u->prenom ?? ''),
                    'date'    => $date->translatedFormat('l d F Y'),
                    'in'      => $in,
                    'out'     => $out,
                    'statut'  => $statut,
                    'sortkey' => $date->format('Y-m-d') . ' 1',
                ];
            });

        // ----- Absences approuvées
        $absences = Absence::with('user:id,nom,prenom')
            ->where('user_id', $userId)
            ->where('status', 'approuvé')
            ->where(function ($q) use ($start, $end) {
                $q->whereDate('start_datetime', '<=', $end)
                    ->whereDate('end_datetime', '>=', $start);
            })
            ->get();

        $absenceRows = collect();
        foreach ($absences as $a) {
            $u     = $a->user;
            $from  = Carbon::parse($a->start_datetime)->startOfDay();
            $to    = Carbon::parse($a->end_datetime)->endOfDay();
            if ($from->lt($start)) $from = $start->copy();
            if ($to->gt($end))     $to   = $end->copy();

            for ($d = $from->copy(); $d->lte($to); $d->addDay()) {
                if (!$isWorkingDay($d)) continue;
                $dKey = $d->toDateString();
                $datesAbsencesOK[$dKey] = true;

                $absenceRows->push([
                    'user_id' => $a->user_id,
                    'nom'     => trim($u->nom ?? ''),
                    'prenom'  => trim($u->prenom ?? ''),
                    'date'    => $d->translatedFormat('l d F Y'),
                    'in'      => '-- : -- : --',
                    'out'     => '-- : -- : --',
                    'statut'  => 'Absence approuvée',
                    'sortkey' => $d->format('Y-m-d') . ' 0',
                ]);
            }
        }

        // ----- Absences injustifiées (jours ouvrés sans pointage ni absence approuvée)
        $absInjRows = collect();
        for ($d = $start->copy(); $d->lte($end); $d->addDay()) {
            if (!$isWorkingDay($d)) continue;
            $dKey = $d->toDateString();
            $aUnPointage   = isset($datesAvecPointage[$dKey]);
            $aUneAbsenceOK = isset($datesAbsencesOK[$dKey]);

            if (!$aUnPointage && !$aUneAbsenceOK) {
                $absInjRows->push([
                    'user_id' => $userId,
                    'nom'     => trim($user->nom ?? ''),
                    'prenom'  => trim($user->prenom ?? ''),
                    'date'    => $d->translatedFormat('l d F Y'),
                    'in'      => '-- : -- : --',
                    'out'     => '-- : -- : --',
                    'statut'  => 'Absent injustifié',
                    'sortkey' => $d->format('Y-m-d') . ' 0',
                ]);
            }
        }

        // ----- Fusion des lignes
        $rows = $absenceRows->merge($absInjRows)->merge($pointages)->sortBy('sortkey')->values();

        // ----- Calcul du total des heures d'absences (approuvées + injustifiées)
        // Règle:
        //  - Jour avec absence approuvée: absence = max(0, attendu - travaillé ce jour)
        //  - Jour sans pointage et sans absence approuvée: absence = attendu
        for ($d = $start->copy(); $d->lte($end); $d->addDay()) {
            if (!$isWorkingDay($d)) continue;
            $dKey = $d->toDateString();
            $attendu = $expectedDailySecByDate[$dKey] ?? 0;
            if ($attendu <= 0) continue;

            $worked = $workedSecByDate[$dKey] ?? 0;

            if (isset($datesAbsencesOK[$dKey])) {
                $approvedAbsenceSec += max(0, $attendu - $worked);
            } elseif (!isset($datesAvecPointage[$dKey])) {
                $unjustifiedAbsenceSec += $attendu;
            }
        }
        $totalAbsenceSec = $approvedAbsenceSec + $unjustifiedAbsenceSec;

        // ----- PDF (A4 paysage) + chemins images
        $logoPath = public_path(session('entreprise_logo')
            ? 'storage/' . ltrim(session('entreprise_logo'), '/')
            : 'src/image/logo.png');

        // photo utilisateur: essaie plusieurs attributs, fallback icône
        $photoAttr = $user->photo ?? null;
        $userPhotoPath = $photoAttr
            ? public_path('storage/' . ltrim($photoAttr, '/'))
            : public_path('src/images/user.jpg');
        if (!is_file($userPhotoPath)) {
            $userPhotoPath = public_path('src/images/user.jpg'); // ultime fallback
        }

        $pdf = new class('L', 'mm', 'A4') extends FPDF {
            public string $logoPath = '';
            public string $entrepriseName = '';
            public string $periode = '';
            public string $printedBy = '';
            public string $userName = '';
            public string $userPhotoPath = '';

            public function Header()
            {
                // --- Gauche : logo
                $logoX = 10;
                $logoY = 8;
                $logoW = 22;
                if (is_file($this->logoPath)) {
                    $this->Image($this->logoPath, $logoX, $logoY, $logoW);
                }

                // --- Droite : photo user
                $photoX = 266;
                $photoY = 8;
                $photoW = 18;
                if (is_file($this->userPhotoPath)) {
                    $this->Image($this->userPhotoPath, $photoX, $photoY, $photoW, $photoW);
                }

                // --- Infos (gauche par simplicité)
                $this->SetFont('Arial', 'B', 14);
                $this->Text(10, 25, utf8_decode($this->entrepriseName));

                $this->SetFont('Arial', '', 10);
                $this->Text(10, 30, utf8_decode('Période : ' . $this->periode));
                $this->Text(10, 35, utf8_decode('Imprimé par : ' . $this->printedBy));

                $nomLimite = mb_strimwidth($this->userName, 0, 29, '...', 'UTF-8');
                $this->Text(235, 32, utf8_decode($nomLimite));

                // --- Saut sous visuels
                $bottomLeft = max($logoY + $logoW, $photoY + $photoW) + 8;
                $this->SetY(max($bottomLeft, $this->GetY() + 4));

                // --- En-tête du tableau
                $this->SetFont('Arial', 'B', 10);
                $this->SetFillColor(230, 230, 230);
                $this->SetDrawColor(200, 200, 200);

                $w = [
                    'nom'    => 65,
                    'prenom' => 45,
                    'date'   => 60,
                    'in'     => 35,
                    'out'    => 35,
                    'statut' => 37,
                ];

                $this->Cell($w['nom'],    9, utf8_decode('Nom(s)'),        1, 0, 'L', true);
                $this->Cell($w['prenom'], 9, utf8_decode('Prénom(s)'),     1, 0, 'L', true);
                $this->Cell($w['date'],   9, utf8_decode('Date'),          1, 0, 'L', true);
                $this->Cell($w['in'],     9, utf8_decode('Heure arrivée'), 1, 0, 'C', true);
                $this->Cell($w['out'],    9, utf8_decode('Heure sortie'),  1, 0, 'C', true);
                $this->Cell($w['statut'], 9, utf8_decode('Statut'),        1, 1, 'C', true);
            }

            public function Footer()
            {
                $this->SetY(-15);
                $this->SetFont('Arial', 'I', 8);
                $this->SetTextColor(120, 120, 120);
                $this->Cell(0, 10, utf8_decode('Page ' . $this->PageNo() . '/{nb}'), 0, 0, 'C');
            }
        };

        // --- Marges
        $leftMargin  = 10;
        $topMargin   = 25;
        $rightMargin = 10;

        $pdf->AliasNbPages();
        $pdf->SetMargins($leftMargin, $topMargin, $rightMargin);
        $pdf->SetAutoPageBreak(true, 18);
        $pdf->logoPath       = $logoPath;
        $pdf->userPhotoPath  = $userPhotoPath;
        $pdf->entrepriseName = $entreprise->nom_entreprise ?? 'Entreprise';
        $pdf->periode        = $periodeTxt;
        $pdf->printedBy      = $printedBy;
        $pdf->userName       = trim(($user->nom ?? '') . ' ' . ($user->prenom ?? ''));

        $pdf->AddPage();
        $pdf->SetFont('Arial', '', 10);
        $pdf->SetDrawColor(220, 220, 220);

        $w = [
            'nom'    => 65,
            'prenom' => 45,
            'date'   => 60,
            'in'     => 35,
            'out'    => 35,
            'statut' => 37,
        ];

        $statusMap = [
            0 => "À l'heure",
            1 => "En retard",
            2 => "Absent injustifié",
            3 => "Absence approuvée",
        ];

        if ($rows->isEmpty()) {
            $pdf->Ln(10);
            $pdf->SetFont('Arial', 'I', 11);
            $pdf->Cell(0, 10, utf8_decode("Aucune donnée sur la période."), 0, 1, 'C');
        } else {
            foreach ($rows as $r) {
                $pdf->Cell($w['nom'],    8, utf8_decode((string)$r['nom']),    1, 0, 'L');
                $pdf->Cell($w['prenom'], 8, utf8_decode((string)$r['prenom']), 1, 0, 'L');
                $pdf->Cell($w['date'],   8, utf8_decode((string)$r['date']),   1, 0, 'L');
                $pdf->Cell($w['in'],     8, utf8_decode((string)$r['in']),     1, 0, 'C');
                $pdf->Cell($w['out'],    8, utf8_decode((string)$r['out']),    1, 0, 'C');

                $raw      = $r['statut'] ?? '';
                $statText = is_numeric($raw) ? ($statusMap[(int)$raw] ?? (string)$raw) : (string)$raw;

                $lower = mb_strtolower($statText, 'UTF-8');
                if (strpos($lower, 'heure') !== false)       $pdf->SetTextColor(0, 128, 0);
                elseif (strpos($lower, 'retard') !== false)  $pdf->SetTextColor(200, 0, 0);
                elseif (strpos($lower, 'absence') !== false) $pdf->SetTextColor(180, 120, 0);
                else                                          $pdf->SetTextColor(80, 80, 80);

                $pdf->Cell($w['statut'], 8, utf8_decode($statText ?: '—'), 1, 1, 'C');
                $pdf->SetTextColor(0, 0, 0);
            }
        }

        // ----- Synthèse (5 colonnes : travaillé / sup / retard / prévues / absences)
        $formatHM = function (int $seconds): string {
            $h = intdiv($seconds, 3600);
            $m = intdiv($seconds % 3600, 60);
            return sprintf('%02d:%02d', $h, $m);
        };

        $pdf->Ln(4);
        $pdf->SetFont('Arial', 'B', 10);
        $pdf->SetFillColor(230, 230, 230);
        $pdf->SetDrawColor(200, 200, 200);

        $usable = $pdf->GetPageWidth() - $leftMargin - $rightMargin;
        $col    = $usable / 5;

        $pdf->SetX($leftMargin);
        $pdf->Cell($col, 9, utf8_decode('Total heures travaillées : ' . $formatHM($totalWorkedSec)),          1, 0, 'C', true);
        $pdf->Cell($col, 9, utf8_decode('Heures supplémentaires : ' . $formatHM($totalOverSec)),               1, 0, 'C', true);
        $pdf->Cell($col, 9, utf8_decode('Heures de retard : '       . $formatHM($totalLateSec)),               1, 0, 'C', true);
        $pdf->Cell($col, 9, utf8_decode('Heures prévues : '         . $formatHM($expectedWorkSec)),            1, 0, 'C', true);
        $pdf->Cell($col, 9, utf8_decode('Heures d\'absence : '      . $formatHM($approvedAbsenceSec + $unjustifiedAbsenceSec)), 1, 1, 'C', true);

        // (option) petit rappel du détail juste en dessous
        $pdf->SetFont('Arial', '', 9);
        $pdf->Ln(2);
        $pdf->Cell(0, 6, utf8_decode(
            'Détail absences : Approuvées: ' . $formatHM($approvedAbsenceSec) .
                ' | Injustifiées: ' . $formatHM($unjustifiedAbsenceSec)
        ), 0, 1, 'C');

        // ----- Sortie
        $filename = 'presence_user_' . $userId . '_' . $start->format('Ymd') . '-' . $end->format('Ymd') . '.pdf';

        return response($pdf->Output('S'))
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'inline; filename="' . $filename . '"');
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
            ->first()
            ?? PeriodePaie::where('entreprise_id', $entrepriseId)
            ->latest('created_at')
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
        function fcfa($n)
        {
            return number_format((float)$n, 0, ',', ' ') . ' F CFA';
        }
        $entrepriseId = session('entreprise_id');

        // 1) Période depuis le ticket
        $periode = PeriodePaie::where('entreprise_id', $entrepriseId)
            ->where('ticket', $ticket)
            ->first()
            ?? PeriodePaie::where('entreprise_id', $entrepriseId)
            ->latest('created_at')
            ->firstOrFail();

        // 2) Employés actifs de l’entreprise (mêmes que dans l’écran)
        $users = User::where('entreprise_id', $entrepriseId)
            ->where('statu_user', 1)
            ->orderBy('nom')
            ->get(['id', 'nom', 'prenom', 'salairebase']);

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
                'name'     => trim(($u->nom ?? '') . ' ' . ($u->prenom ?? '')),
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
        $drawHeader = function () use ($pdf, $t, $left, $wEmp, $wBase, $wGain, $wRet, $wNet, $hRow) {
            $pdf->SetFillColor(245, 245, 245);
            $pdf->SetDrawColor(210, 210, 210);
            $pdf->SetFont('Arial', 'B', 10);
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

        $pdf->SetFont('Arial', '', 10);
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

            if ($alt) $pdf->SetFillColor(245, 245, 245);
            else $pdf->SetFillColor(255, 255, 255);
            $pdf->SetDrawColor(230, 230, 230);

            $pdf->SetX($left);
            $pdf->SetFont('Arial', '', 10);
            $pdf->Cell($wEmp,  $hRow, $t(mb_strtoupper($r['name'], 'UTF-8')), 1, 0, 'L', true);
            $pdf->Cell($wBase, $hRow, $t(fcfa($base)), 1, 0, 'R', true);
            $pdf->Cell($wGain, $hRow, $t(fcfa($gain)), 1, 0, 'R', true);
            $pdf->Cell($wRet,  $hRow, $t(fcfa($ret)),  1, 0, 'R', true);

            $pdf->SetFont('Arial', 'B', 10); // mettre en gras uniquement ici
            $pdf->Cell($wNet,  $hRow, $t(fcfa($net)),  1, 1, 'R', true);
            $pdf->SetFont('Arial', '', 10); // revenir à normal

            $y = $pdf->GetY();
            $alt = !$alt;
        }

        // Totaux
        $pdf->SetFont('Arial', 'B', 10);
        $pdf->SetFillColor(235, 235, 235);
        $pdf->SetDrawColor(210, 210, 210);
        $pdf->SetX($left);
        $pdf->Cell($wEmp,  $hRow, $t('TOTAL'),          1, 0, 'R', true);
        $pdf->Cell($wBase, $hRow, $t(fcfa($totalBase)), 1, 0, 'R', true);
        $pdf->Cell($wGain, $hRow, $t(fcfa($totalGain)), 1, 0, 'R', true);
        $pdf->Cell($wRet,  $hRow, $t(fcfa($totalRet)),  1, 0, 'R', true);
        $pdf->Cell($wNet,  $hRow, $t(fcfa($totalNet)),  1, 1, 'R', true);

        return response($pdf->Output('S'))
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'inline; filename="détail-employé-' . $ticket . '.pdf"');
    }

    /**
     * Génère des données détaillées de paie avec des totaux calculés automatiquement.
     *
     * @param string $userId        L'ID de l'utilisateur (employé).
     * @param string $tiketPeriode  Le ticket/identifiant pour la période de paie.
     *
     * @return array Un tableau contenant les données de paie et les totaux calculés.
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


    public function ficheDePaieDemo(string $userId, string $tiketPeriode)
    {
        // ---- Données calculées (lignes déjà ordonnées + totaux)
        $data = $this->generateDetailedPayrollData($userId, $tiketPeriode);
        if (empty($data)) {
            abort(404, 'Période introuvable ou données indisponibles.');
        }

        // ---- Contexte période / utilisateur / entreprise
        $period = DB::table('periodes_paie')->where('ticket', $tiketPeriode)->first();
        if (!$period) {
            abort(404, 'Ticket de période invalide.');
        }

        /** @var User $user */
        $user = User::findOrFail($userId);
        $entreprise = DB::table('entreprises')->where('id', $user->entreprise_id)->first();

        // ---- En-têtes (entreprise / identité / méta)
        $entrepriseHead = [
            'nom'   => $entreprise->nom_entreprise ?? '—',
            'bp'    => $entreprise->code_entreprise ?? '—',
            'ville' => $user->ville->nom_ville, // ajoute si tu as une ville en base
            'tel'   => $user->telephone_professionnel ?? $user->telephone ?? '',
        ];

        $identite = [
            'civilite'  => '', // renseigne si tu as l’info
            'nom'       => trim(($user->nom ?? '') . ' ' . ($user->prenom ?? '')) ?: '—',
            'naissance' => $user->date_naissance ? Carbon::parse($user->date_naissance)->format('d/m/Y') : '',
            'lieu'      => $user->lieu_naissance ?? '',
            'cnss'      => $user->numero_securite_sociale ?? '',
            'matricule' => $user->matricule ?? '',
            'poste'     => $user->fonction ?? '',
            'telephone' => $user->telephone ?? '',
        ];

        $meta = [
            'departement'   => '', // si tu relies service_id → services
            'situation_fam' => $user->etat_civil ?? '',
            'nb_enfants'    => (string)($user->nombre_enfant ?? '0'),
            'nb_parts'      => '0,00',
            'categorie'     => '', // si tu relies categorie_professionel_id
            'horaire'       => '', // si tu calcules les heures normées
        ];

        $periode = [
            'du'       => Carbon::parse($period->date_debut)->format('d/m/Y'),
            'au'       => Carbon::parse($period->date_fin)->format('d/m/Y'),
            'paiement' => Carbon::parse($period->date_fin)->format('d/m/Y'),
            'mode'     => $user->mode_paiement ?? 'Espèces',
        ];

        // ---- Lignes du tableau (déjà triées par generateDetailedPayrollData)
        $rows = [];
        foreach ($data['rows'] as $r) {
            $rows[] = [
                $r['numero'] ?? '',           // N°
                $r['designation'] ?? '',      // Désignation
                '',                           // Nombre (si tu veux le gérer plus tard)
                $r['base'] ?? '',             // Base
                $r['taux_salarial'] ?? '',    // Part salariale - Taux
                $r['gain_salarial'] ?? '',    // Part salariale - Gain
                $r['retenue_sal'] ?? '',      // Part salariale - Retenue
                $r['taux_patronal'] ?? '',    // Part patronale - Taux
                $r['retenue_pat'] ?? '',      // Part patronale - Retenue
            ];
        }

        // ---- Totaux / cumuls
        $totalBrut       = (float)($data['totals']['Total Brut'] ?? 0);
        $totCotisBase    = (float)($data['totals']['Total Cotisations']['base'] ?? 0);
        $chargesSal      = (float)($data['totals']['Total Cotisations']['sal']  ?? 0);
        $chargesPat      = (float)($data['totals']['Total Cotisations']['pat']  ?? 0);
        $salaireBrut     = (float)($data['totals']['Salaire brut'] ?? $totalBrut);
        $netImposable    = (float)($data['totals']['Net imposable'] ?? max(0, $salaireBrut - $chargesSal));
        $avantagesNature = (float)($data['totals']['Avantages en nature'] ?? 0);

        // Ligne “Salaire net” (si tu veux l’afficher comme dans la maquette)
        $rows[] = ['3380', '****** Salaire net *********', '', $salaireBrut, '', $salaireBrut, '', '', ''];

        // Exemple d’ajouts conditionnels (évite doublons si déjà dans $data['rows'])
        $already = collect($data['rows'])->pluck('designation')->map('strtolower')->all();
        $periodId = $data['periodTicketId'];

        $maybeAdd = function (string $varName, string $code, ?string $tauxAffiche = null) use (&$rows, $userId, $periodId, $already) {
            if (in_array(strtolower($varName), $already, true)) return;
            $row = DB::table('variable_periode_users')
                ->join('variables', 'variable_periode_users.variable_id', '=', 'variables.id')
                ->where('user_id', $userId)
                ->where('periode_paie_id', $periodId)
                ->where('variables.nom_variable', $varName)
                ->select('variable_periode_users.montant')
                ->first();
            if ($row) {
                $rows[] = [
                    $code,
                    $varName,
                    '',
                    (float)$row->montant,
                    $tauxAffiche,
                    (float)$row->montant,
                    '',
                    '',
                    ''
                ];
            }
        };

        // Exemples (facultatifs)
        $maybeAdd('Indemnité de transport', '3405', '30,00');
        $maybeAdd('Arrondi précédent',      '4500', null);
        $maybeAdd('Arrondi du mois',        '4515', null);

        $heuresTravaillees = (float)($data['heuresTravaillees'] ?? 0);
        $heuresSup       = (float)($data['heuresSupplementaires'] ?? 0);
        $heuresAbs       = (float)($data['heuresAbsence'] ?? 0);
        // Cumuls affichés en pied de tableau (mets tes vraies valeurs annuelles si tu les as)
        $cumuls = [
            'periode' => [
                'brut'           => $salaireBrut,
                'charges_sal'    => $chargesSal,
                'charges_pat'    => $chargesPat,
                'avantages'      => $avantagesNature,
                'net_imposable'  => $netImposable,
                'heures_trav'    => $heuresTravaillees,
                'heures_sup'     => $heuresSup,
            ],
            'annee' => [
                'brut'           => 0,
                'charges_sal'    => 0,
                'charges_pat'    => 0,
                'avantages'      => 0,
                'net_imposable'  => 0,
                'heures_trav'    => $heuresTravailleesAnnee ?? 0,
                'heures_sup'     => $heuresSupplementairesAnnee ?? 0,
            ],
        ];

        // Net à payer (généralement salaire brut – charges salariales)
        $netAPayer = max(0, $salaireBrut - $chargesSal);

        // ---- PDF
        $pdf = new PdfPayslip('P', 'mm', 'A4');
        $pdf->AliasNbPages();
        $pdf->SetMargins(16, 10, 12);
        $pdf->logoPath = public_path('storage/' . session('entreprise_logo')); // optionnel

        $pdf->AddPage();

        $currentY = $pdf->drawBulletinTitleAndPeriod($periode);
        $pdf->drawCompanyTitleCentered($entrepriseHead);
        $pdf->drawRightIdentityCard($identite);
        $pdf->drawLeftEmployeeMeta($meta);

        $startY = max($currentY + 10, 65);
        ['y' => $y, 'w' => $w] = $pdf->drawTableHeader($startY);

        $curY = $y;
        $limitY = $pdf->GetPageHeight() - 98;
        $rh = 6;

        $rowsCount = count($rows);
        $idx = 0;
        $buffer = null;

        foreach ($rows as $r) {
            if ($buffer === null) { // prime la première
                $buffer = $r;
                $idx++;
                continue;
            }

            // Est-ce que la PROCHAINE ligne (r) tiendra sur la page courante ?
            $willBreak = ($curY + $rh > $limitY);

            // Si la prochaine ligne ne tient pas, on ferme la ligne buffer avec 'LRB' (fin de page)
            $isEndOfChunk = $willBreak;

            $curY = $pdf->drawRow($w, $curY, $buffer, $rh, $isEndOfChunk);

            if ($willBreak) {
                $pdf->AddPage();
                ['y' => $y, 'w' => $w] = $pdf->drawTableHeader(20);
                $curY = $y;
            }

            // décale le buffer
            $buffer = $r;
            $idx++;
        }

        // Dernière ligne du tableau : bordure basse 'LRB'
        if ($buffer !== null) {
            $curY = $pdf->drawRow($w, $curY, $buffer, $rh, true);
        }


        $yAfterCumuls = $pdf->drawCumulsAndNet($cumuls, $netAPayer);
        $pdf->drawCountersAndSignatures([
            'Congés'               => ['pris' => '0', 'restant' => '0', 'acquis' => '0'],
            'Repos compensateur'   => ['pris' => '0', 'restant' => '0', 'acquis' => '0'],
        ], $yAfterCumuls + 4);

        return response($pdf->Output('S'))
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'inline; filename="bulletin_paie.pdf"');
    }
}



