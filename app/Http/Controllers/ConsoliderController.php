<?php

namespace App\Http\Controllers;

use App\Models\CategorieProfessionnelle;
use App\Models\Entreprise;
use App\Models\Module;
use App\Models\Pointage;
use App\Models\PointagesIntermediaire;
use App\Models\Service;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ConsoliderController extends Controller
{
    //
    // Helpers privés du contrôleur
    private static function humanSeniority(?string $dateEmbauche): string
    {
        try {
            $debut = Carbon::parse($dateEmbauche);
            $now   = Carbon::now();
            $years = $debut->diffInYears($now);
            $months = $debut->copy()->addYears($years)->diffInMonths($now);
            $parts = [];
            if ($years)  $parts[] = $years . ' an' . ($years > 1 ? 's' : '');
            if ($months) $parts[] = $months . ' mois';
            return $parts ? implode(' ', $parts) : '0 mois';
        } catch (\Throwable $e) {
            return '—';
        }
    }
    // Dans ton contrôleur
    private static function colorFor(?string $rawCode): string
    {
        $code = strtoupper(trim((string)$rawCode));

        // Normalisation / alias (ex: "ING" => "INGENIUM", "YODI" => "YOD")
        $aliases = [
            'ING' => 'INGENIUM',
            'INGENIUM' => 'INGENIUM',
            'YODI' => 'YOD',
        ];
        $code = $aliases[$code] ?? $code;

        // Palette douce (désaturée, lisible sur fond blanc)
        return match ($code) {
            'INGENIUM'  => '#5969A8', // indigo doux
            'EZER'      => '#6C5A87', // violet fumé
            'COMKETING' => '#B17F9D', // rose brume
            'YOD'       => '#6B99A6', // teal-gris
            'EGCC'      => '#6FA19A', // vert d’eau désaturé
            'BFEV'      => '#8699A3', // bleu-gris doux
            'NEH'       => '#9AA271', // olive clair
            default     => self::pastelFromString($code), // fallback pastel cohérent
        };
    }

    /**
     * Fallback pastel cohérent à partir d'une chaîne (hash → HSL).
     * Donne une teinte stable, faible saturation et forte clarté (pastel).
     */
    private static function pastelFromString(string $s): string
    {
        // petit hash
        $h = 0;
        for ($i = 0; $i < strlen($s); $i++) $h = (31 * $h + ord($s[$i])) & 0xFFFFFFFF;

        // HSL pastel
        $hue = $h % 360;          // 0..359
        $sat = 28;                // faible saturation
        $light = 72;              // clair
        return self::hslToHex($hue, $sat / 100, $light / 100);
    }

    private static function hslToHex($h, $s, $l): string
    {
        $c = (1 - abs(2 * $l - 1)) * $s;
        $x = $c * (1 - abs(fmod($h / 60, 2) - 1));
        $m = $l - $c / 2;
        [$r, $g, $b] = match (true) {
            $h < 60   => [$c, $x, 0],
            $h < 120  => [$x, $c, 0],
            $h < 180  => [0, $c, $x],
            $h < 240  => [0, $x, $c],
            $h < 300  => [$x, 0, $c],
            default   => [$c, 0, $x],
        };
        $toHex = fn($v) => str_pad(dechex((int)round(($v + $m) * 255)), 2, '0', STR_PAD_LEFT);
        return '#' . $toHex($r) . $toHex($g) . $toHex($b);
    }

    public function index(?string $entreprise_id = null)
    {
        $entreprise_id = session('entreprise_id');

        // Employés avec relations (colonnes utiles seulement)
        $employes = User::with([
            'entreprise',
            'service',
            'categorieProfessionnelle'
        ])
            // ->where('entreprise_id', $entreprise_id)
            ->where('statu_user', 1)
            ->where('statut', 1)
            ->orderBy('nom')->orderBy('prenom')
            ->get();

        // Petite aide: ancienneté “X ans / Y mois”
        $employes->transform(function ($u) {
            $u->anciennete_label = $u->date_embauche
                ? self::humanSeniority($u->date_embauche)
                : null;
            return $u;
        });

        // Stats par entreprise (si ta page doit afficher plusieurs entreprises, adapte le where ci-dessus)
        $parEntreprise = $employes->groupBy('entreprise_id')->map(function ($group) {
            $e = $group->first()->entreprise;
            return [
                'name'     => $e->nom_entreprise ?? 'N/A',
                'effectif' => $group->count(),
                // Couleur: au choix — depuis la DB (champ), code, ou palette par défaut
                'color'    => self::colorFor($e->code_entreprise ?? null),
            ];
        })->values();

        $employeesData = $employes->map(function ($u) {
            return [
                'name'       => trim(($u->nom ?? '') . ' ' . ($u->prenom ?? '')),
                'company'    => optional($u->entreprise)->nom_entreprise,
                'poste'      => $u->fonction,
                'service'    => optional($u->service)->nom_service,
                'categorie'  => optional($u->categorieProfessionnelle)->nom_categorie_professionnelle,
                'anciennete' => $u->anciennete_label,
                'statut'     => $u->type_contrat,
            ];
        })->values()->all(); // <- array pur, plus de Collection ni Closure


        // Buckets fixes
        $ageBuckets = [
            '18-25' => 0,
            '26-35' => 0,
            '36-45' => 0,
            '46-55' => 0,
            '56+'   => 0,
        ];

        // Compte par tranche
        foreach ($employes as $u) {
            if (empty($u->date_naissance)) continue;
            try {
                $age = Carbon::parse($u->date_naissance)->age; // calcule l'âge
            } catch (\Throwable $e) {
                continue;
            }

            if ($age < 18) continue;                 // ignore <18 (optionnel)
            elseif ($age <= 25) $ageBuckets['18-25']++;
            elseif ($age <= 35) $ageBuckets['26-35']++;
            elseif ($age <= 45) $ageBuckets['36-45']++;
            elseif ($age <= 55) $ageBuckets['46-55']++;
            else $ageBuckets['56+']++;
        }

       // 3) Séries prêtes pour Blade (arrays “purs”)
$ageLabels = array_keys($ageBuckets);
$ageData   = array_values($ageBuckets);
        return view('components.consolider.index', [
            'companiesData' => $parEntreprise,
            'employeesData' => $employeesData,
            'ageLabels'     => $ageLabels,
            'ageData'       => $ageData,
        ]);
    }


    public function change_entreprise($entreprise_id)
    {
        session()->put('entreprise_id', $entreprise_id);
        $entreprise = Entreprise::find($entreprise_id);
        session()->put('entreprise_nom', $entreprise->nom_entreprise);
        session()->put('entreprise_logo', $entreprise->logo);
        session()->put('entreprise_code', $entreprise->code_entreprise);
        return redirect()->back();
    }
}
