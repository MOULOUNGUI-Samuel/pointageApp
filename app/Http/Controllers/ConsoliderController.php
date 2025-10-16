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
use Illuminate\Support\Str;
use App\Services\AttendanceService;

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

        $entreprises = Entreprise::All();
        // Employés avec relations (colonnes utiles seulement)
        if(isset($entreprise_id)){

            $employes = User::with([
                'entreprise',
                'service',
                'categorieProfessionnelle'
            ])
                ->where('entreprise_id', $entreprise_id)
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
        }else{
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
        }

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


        // Fenêtre: 7 derniers jours incluant aujourd’hui
        $end   = Carbon::today()->endOfDay();
        $start = Carbon::today()->subDays(6)->startOfDay();

        /** @var AttendanceService $attSvc */
        $attSvc = app(AttendanceService::class);

        // Employés actifs (filtrés SI $entreprise_id fourni)
        if(isset($entreprise_id)){
            $employes = User::with([
                'service:id,nom_service',
                'entreprise:id,nom_entreprise,heure_ouverture,minute_pointage_limite'
            ])
                ->select(['id', 'nom', 'prenom', 'service_id', 'entreprise_id', 'fonction', 'type_contrat', 'date_naissance'])
                ->when($entreprise_id, fn($q) => $q->where('entreprise_id', $entreprise_id))
                ->where('statu_user', 1)
                ->where('statut', 1)
                ->orderBy('nom')->orderBy('prenom')
                ->get();

        }else{
            $employes = User::with([
                'service:id,nom_service',
                'entreprise:id,nom_entreprise,heure_ouverture,minute_pointage_limite'
            ])
                ->select(['id', 'nom', 'prenom', 'service_id', 'entreprise_id', 'fonction', 'type_contrat', 'date_naissance'])
                // ->when($entreprise_id, fn($q) => $q->where('entreprise_id', $entreprise_id))
                ->where('statu_user', 1)
                ->where('statut', 1)
                ->orderBy('nom')->orderBy('prenom')
                ->get();
        }

        // Si cas A (une entreprise), on la charge (utile pour l’intitulé)
        $entrepriseCible = $entreprise_id ? Entreprise::findOrFail($entreprise_id) : null;

        // Jours pour l’en-tête (ex. "Lun 07")
        $days = [];
        $cursor = $start->copy();
        while ($cursor <= $end) {
            $days[] = [
                'date'          => $cursor->toDateString(),
                'label'         => Str::ucfirst($cursor->locale('fr_FR')->isoFormat('ddd DD')),
                'is_weekend'    => $cursor->isWeekend(),
                'weekday_index' => (int)$cursor->dayOfWeekIso, // 1..7
                'carbon'        => $cursor->copy(),            // utile pour AttendanceService
            ];
            $cursor->addDay();
        }
        $dayLabels = array_column($days, 'label');

        // Prépare le seuil de retard par ENTREPRISE (open + minutes de grâce)
        $seuilsEntreprise = [];
        foreach ($employes as $u) {
            $e = $u->entreprise;
            if (!$e || isset($seuilsEntreprise[$e->id])) continue;
            $open  = $e->heure_ouverture ?: '08:00:00';
            $grace = (int)($e->minute_pointage_limite ?? 0);
            $seuilsEntreprise[$e->id] = Carbon::createFromFormat('H:i:s', $open)
                ->addMinutes($grace)->format('H:i:s');
        }

        // Agrégations
        $presenceRows = [];                // lignes du tableau (par employé)
        $companyStats = [];                // assiduité par entreprise: présent / ouvrés (ouvrés = jours réellement comptés)
        $serviceHeat  = [];                // heatmap retards/absences par service (Lun..Ven)

        foreach ($employes as $u) {
            $rowStatuses = [];
            $anomalies = 0;

            $svcName = optional($u->service)->nom_service ?? '—';
            $cmpObj  = $u->entreprise;
            $cmpId   = $cmpObj->id ?? '—';
            $cmpName = $cmpObj->nom_entreprise ?? '—';

            // structures
            $companyStats[$cmpName] = $companyStats[$cmpName] ?? ['present' => 0, 'ouvrés' => 0];

            // Pour éviter collision de services homonymes entre entreprises en mode “toutes”
            $svcKey = $entreprise_id ? $svcName : ($cmpName . ' · ' . $svcName);
            $serviceHeat[$svcKey] = $serviceHeat[$svcKey] ?? [1 => 0, 2 => 0, 3 => 0, 4 => 0, 5 => 0];

            $seuilRetard = $seuilsEntreprise[$cmpId] ?? '08:00:00';

            foreach ($days as $d) {
                /** Statut “métier” via AttendanceService */
                $status = $attSvc->getUserStatusForDate($u, $d['carbon']);

                // Mapping vers nos icônes/affichages :
                // - 'weekend'           -> weekend (ne compte nulle part)
                // - 'jour_ferie'        -> weekend-like (ne compte pas)
                // - 'absence_approuvee' -> “exclu du calcul” (pas d’anomalie, pas dans le dénominateur)
                // - 'absent_injustifie' -> absent (anomalie + heatmap + donut)
                // - 'présent'           -> présent (vérifier retard)

                if ($status === 'weekend') {
                    $rowStatuses[] = 'weekend';
                    continue;
                }

                if ($status === 'jour_ferie') {
                    $rowStatuses[] = 'jour_ferie';
                    continue;
                }

                if ($status === 'absence_approuvee') {
                    $rowStatuses[] = 'absence_approuvee';
                    // ni anomalie, ni “ouvrés”
                    continue;
                }

                // À partir d’ici, c’est un jour réellement “ouvré” comptabilisé
                $companyStats[$cmpName]['ouvrés']++;

                if ($status === 'absent_injustifie') {
                    $rowStatuses[] = 'absent';
                    $anomalies++;
                    if ($d['weekday_index'] <= 5) $serviceHeat[$svcKey][$d['weekday_index']] += 2; // absence pèse 2
                    continue;
                }

                // présent → vérifier “retard” selon seuil de l’entreprise
                // On lit l’heure d’arrivée du pointage du jour pour ce user
                $p = Pointage::where('user_id', $u->id)
                    ->whereDate('date_arriver', $d['date'])
                    ->orderBy('heure_arriver', 'asc')
                    ->first(['heure_arriver']);

                $isLate = $p && $p->heure_arriver && $p->heure_arriver > $seuilRetard;

                if ($isLate) {
                    $rowStatuses[] = 'late';
                    $anomalies++;
                    if ($d['weekday_index'] <= 5) $serviceHeat[$svcKey][$d['weekday_index']] += 1; // retard pèse 1
                    $companyStats[$cmpName]['present']++; // présent quand même
                } else {
                    $rowStatuses[] = 'present';
                    $companyStats[$cmpName]['present']++;
                }
            }

            $presenceRows[] = [
                'name'      => trim(($u->nom ?? '') . ' ' . ($u->prenom ?? '')),
                'statuses'  => $rowStatuses,
                'anomalies' => $anomalies,
            ];
        }

        // Assiduité (%)
        $assiduiteLabels = [];
        $assiduiteRates  = [];
        foreach ($companyStats as $cName => $st) {
            $rate = ($st['ouvrés'] > 0) ? round(100 * $st['present'] / $st['ouvrés'], 1) : 0.0;
            $assiduiteLabels[] = $cName;
            $assiduiteRates[]  = $rate;
        }

        // Heatmap (normalisée)
        $heatMax = 0;
        foreach ($serviceHeat as $svc => $arr) {
            $heatMax = max($heatMax, max($arr));
        }
        $heatmap = [];
        foreach ($serviceHeat as $svc => $arr) {
            $heatmap[] = [
                'service' => $svc,
                'cells'   => [$arr[1] ?? 0, $arr[2] ?? 0, $arr[3] ?? 0, $arr[4] ?? 0, $arr[5] ?? 0],
            ];
        }

        // Donut Présent / Retard / Absent (on EXCLUT weekend, jours fériés, absences approuvées)
        $donutPresent = 0;   // présents à l'heure
        $donutLate    = 0;   // retards
        $donutAbsent  = 0;   // absences injustifiées
        $donutWorkdays = 0;  // jours “ouvrés” effectifs (hors weekend/ferié/absence_approuvée)

        foreach ($presenceRows as $row) {
            foreach ($row['statuses'] as $st) {
                if (in_array($st, ['weekend', 'jour_ferie', 'absence_approuvee'], true)) {
                    continue; // exclu du dénominateur
                }
                $donutWorkdays++;

                if ($st === 'present') {
                    $donutPresent++;
                } elseif ($st === 'late') {
                    $donutLate++;
                } elseif ($st === 'absent') {
                    $donutAbsent++;
                }
            }
        }

        if ($donutWorkdays > 0) {
            $pctPresent = round(100 * $donutPresent / $donutWorkdays, 1);
            $pctLate    = round(100 * $donutLate    / $donutWorkdays, 1);
            $pctAbsent  = round(100 * $donutAbsent  / $donutWorkdays, 1);
        } else {
            $pctPresent = $pctLate = $pctAbsent = 0.0;
        }

        $assiduiteDonut = [
            'labels' => ['Présent', 'Retard', 'Absent'],
            'data'   => [$pctPresent, $pctLate, $pctAbsent],
        ];

        // Libellé de portée (UI)
        $scopeLabel = $entrepriseCible ? $entrepriseCible->nom_entreprise : 'Toutes les entreprises';
        return view('components.consolider.index', [
            'companiesData' => $parEntreprise,
            'employeesData' => $employeesData,
            'ageLabels'     => $ageLabels,
            'ageData'       => $ageData,
            'entreprises'   => $entreprises,
            'currentEntrepriseId' => $entreprise_id, // null si “toutes”

            'scopeLabel'       => $scopeLabel,     // "Ingenium" ou "Toutes les entreprises"
            'dayLabels'        => $dayLabels,
            'presenceRows'     => $presenceRows,
            'assiduiteLabels'  => $assiduiteLabels,
            'assiduiteRates'   => $assiduiteRates,
            'assiduiteDonut' => $assiduiteDonut,
            'heatmap'          => $heatmap,
            'heatMax'          => $heatMax,
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
