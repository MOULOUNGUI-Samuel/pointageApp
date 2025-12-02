<?php

namespace App\Livewire\Settings;

use App\Models\Item;
use App\Models\PeriodeItem;
use App\Models\ConformitySubmission;
use App\Models\Domaine;
use App\Models\CategorieDommaine;
use Illuminate\Support\Facades\DB;
use App\Services\PeriodeItemChecker; // adapte le namespace si besoin
use Livewire\Component;
use Carbon\Carbon;

class ComplianceStatistics extends Component
{
    public string $entrepriseId;
    public string $periode = 'all'; // all, month, quarter, year
    public ?string $selectedDomaine = null;

    // Données pour les graphiques
    public array $globalStats = [];
    public array $domaineStats = [];
    public array $categorieStats = [];
    public array $evolutionData = [];
    public array $itemsExpires = [];
    public array $performanceStats = [];

    public function mount(): void
    {
        $this->entrepriseId = (string) session('entreprise_id');
        $this->loadAllStatistics();
    }

    public function updatedPeriode(): void
    {
        $this->loadAllStatistics();
    }

    public function updatedSelectedDomaine(): void
    {
        $this->loadAllStatistics();
    }

    public function loadAllStatistics(): void
    {
        $this->loadGlobalStats();
        $this->loadDomaineStats();
        $this->loadCategorieStats();
        $this->loadEvolutionData();
        $this->loadItemsExpires();
        $this->loadPerformanceStats();
    }

    /**
     * Statistiques globales
     */
    private function loadGlobalStats(): void
    {
        $itemIds = $this->getItemIds();

        if ($itemIds->isEmpty()) {
            $this->globalStats = [
                'total_criteres' => 0,
                'evalues'        => 0,
                'conformes'      => 0,
                'non_conformes'  => 0,
                'en_attente'     => 0,
                'non_evalues'    => 0,
                'score_global'   => 0,
                'statut'         => 'Insuffisant',
            ];
            return;
        }

        $items = Item::whereIn('id', $itemIds)->get();

        $totalCriteres  = $items->count();
        $evalues        = 0;
        $conformes      = 0;
        $nonConformes   = 0;
        $enAttente      = 0;

        foreach ($items as $item) {
            $status = $this->computeItemStatus($item);

            if ($status['has_submission']) {
                $evalues++;
            }
            if ($status['conforme']) {
                $conformes++;
            }
            if ($status['non_conforme']) {
                $nonConformes++;
            }
            if ($status['en_attente']) {
                $enAttente++;
            }
        }

        $nonEvalues  = $totalCriteres - $evalues;
        $scoreGlobal = $totalCriteres > 0
            ? round(($conformes / $totalCriteres) * 100)
            : 0;

        $statut = match (true) {
            $scoreGlobal >= 80 => 'Excellent',
            $scoreGlobal >= 60 => 'Bon',
            $scoreGlobal >= 40 => 'Moyen',
            $scoreGlobal >= 20 => 'Faible',
            default             => 'Insuffisant',
        };

        $this->globalStats = [
            'total_criteres' => $totalCriteres,
            'evalues'        => $evalues,
            'conformes'      => $conformes,      // = "valides" selon ta logique
            'non_conformes'  => $nonConformes,
            'en_attente'     => $enAttente,
            'non_evalues'    => $nonEvalues,
            'score_global'   => $scoreGlobal,
            'statut'         => $statut,
        ];
    }

    /**
     * Retourne le statut de conformité d'un item pour l'entreprise courante,
     * en appliquant la même logique que dans le board (périodes + soumissions).
     */
    private function computeItemStatus(Item $item): array
    {
        $entrepriseId = $this->entrepriseId;

        // State période (utilise ton accessor et la méthode periodeStateFor)
        $periodeState = $item->periodeStateFor($entrepriseId);

        // Dernière soumission pour cette entreprise
        $lastSub = ConformitySubmission::where('item_id', $item->id)
            ->where('entreprise_id', $entrepriseId)
            ->latest('submitted_at')
            ->first();

        $hasSubmission = (bool) $lastSub;

        // Y a-t-il une période active ?
        $hasActivePeriode = PeriodeItemChecker::hasActivePeriod($item->id, $entrepriseId);

        $isConforme     = false;
        $isNonConforme  = false;
        $isEnAttente    = false;

        if ($lastSub) {
            if ($lastSub->status === 'soumis') {
                $isEnAttente = true;
            }

            if ($lastSub->status === 'approuvé') {
                // Dernière période (de référence) pour cet item / entreprise
                $activePeriode = PeriodeItem::where('item_id', $item->id)
                    ->where('entreprise_id', $entrepriseId)
                    ->orderByDesc('debut_periode')
                    ->first();

                if ($activePeriode) {
                    $submittedAt  = Carbon::parse($lastSub->submitted_at);
                    $debutPeriode = Carbon::parse($activePeriode->debut_periode);

                    // ✅ Soumission approuvée pendant OU après le début de la période de référence
                    if ($submittedAt->greaterThanOrEqualTo($debutPeriode)) {
                        $isConforme = true;
                    } elseif ($hasActivePeriode) {
                        // ✅ Soumission approuvée pour une ancienne période alors qu'il y a une nouvelle période active
                        $isNonConforme = true;
                    }
                } else {
                    // Aucun enregistrement de période → on considère conforme par défaut
                    $isConforme = true;
                }
            } elseif ($hasActivePeriode && $lastSub->status === 'rejeté') {
                // Période active + dernière soumission rejetée ⇒ non conforme
                $isNonConforme = true;
            }
        } else {
            // Aucune soumission
            if ($hasActivePeriode) {
                // Période active sans soumission ⇒ non conforme
                $isNonConforme = true;
            }
        }

        return [
            'periode_state'  => $periodeState,
            'has_submission' => $hasSubmission,
            'conforme'       => $isConforme,
            'non_conforme'   => $isNonConforme,
            'en_attente'     => $isEnAttente,
        ];
    }

    /**
     * Statistiques par domaine
     */
   private function loadDomaineStats(): void
{
    /** @var \Illuminate\Support\Collection<string, object> $domaines */
    $domaines = DB::table('entreprise_domaines')
        ->where('entreprise_id', $this->entrepriseId)
        ->where('entreprise_domaines.statut', '1')
        ->join('domaines', 'domaines.id', '=', 'entreprise_domaines.domaine_id')
        ->select('domaines.id', 'domaines.nom_domaine')
        ->orderBy('domaines.nom_domaine')
        ->get();

    $this->domaineStats = [];

    /** @var object{ id:string, nom_domaine:string } $domaine */
    foreach ($domaines as $domaine) {
        $itemIds = $this->getItemIdsByDomaine($domaine->id);

        if ($itemIds->isEmpty()) {
            continue;
        }

        $items = Item::whereIn('id', $itemIds)->get();

        $total        = $items->count();
        $evalues      = 0;
        $conformes    = 0;
        $nonConformes = 0;

        // Optionnel : stats par état de période pour ce domaine
        $periodeStats = [
            'active'   => 0,
            'upcoming' => 0,
            'expired'  => 0,
            'disabled' => 0,
            'none'     => 0,
        ];

        foreach ($items as $item) {
            $status = $this->computeItemStatus($item);

            // Période_state (pour badges / graphs)
            $state = $status['periode_state'];
            if (isset($periodeStats[$state])) {
                $periodeStats[$state]++;
            }

            if ($status['has_submission']) {
                $evalues++;
            }
            if ($status['conforme']) {
                $conformes++;
            }
            if ($status['non_conforme']) {
                $nonConformes++;
            }
        }

        $score = $total > 0 ? round(($conformes / $total) * 100) : 0;

        $statut = match (true) {
            $score >= 80 => 'Excellent',
            $score >= 60 => 'Bon',
            $score >= 40 => 'Moyen',
            $score >= 20 => 'Faible',
            default       => 'Insuffisant',
        };

        $this->domaineStats[] = [
            'id'             => $domaine->id,
            'nom'            => $domaine->nom_domaine,
            'total'          => $total,
            'evalues'        => $evalues,
            'conformes'      => $conformes,      // = valides
            'non_conformes'  => $nonConformes,
            'score'          => $score,
            'statut'         => $statut,
            'periode_stats'  => $periodeStats,   // si tu veux les utiliser dans des graphs
        ];
    }
}


    /**
     * Statistiques par catégorie
     */
    private function loadCategorieStats(): void
    {
        $query = DB::table('entreprise_categorie_domaines')
            ->where('entreprise_categorie_domaines.entreprise_id', $this->entrepriseId)
            ->where('entreprise_categorie_domaines.statut', '1')
            ->join('categorie_domaines', 'categorie_domaines.id', '=', 'entreprise_categorie_domaines.categorie_domaine_id')
            ->join('domaines', 'domaines.id', '=', 'categorie_domaines.domaine_id');

        if ($this->selectedDomaine) {
            $query->where('domaines.id', $this->selectedDomaine);
        }

        /** @var \Illuminate\Support\Collection<string, object{
         *      id:string,
         *      nom_categorie:string,
         *      nom_domaine:string
         *  }> $categories
         */
        $categories = $query->select(
            'categorie_domaines.id',
            'categorie_domaines.nom_categorie',
            'domaines.nom_domaine'
        )
            ->orderBy('categorie_domaines.nom_categorie')
            ->get();

        $this->categorieStats = [];

        /** @var object{
         *      id:string,
         *      nom_categorie:string,
         *      nom_domaine:string
         *  } $categorie
         */
        foreach ($categories as $categorie) {
            $itemIds = DB::table('entreprise_items')
                ->join('items', 'items.id', '=', 'entreprise_items.item_id')
                ->where('entreprise_items.entreprise_id', $this->entrepriseId)
                ->where('entreprise_items.statut', '1')
                ->where('items.categorie_domaine_id', $categorie->id)
                ->pluck('items.id');

            if ($itemIds->isEmpty()) {
                continue;
            }

            $total     = $itemIds->count();
            $conformes = 0;

            foreach ($itemIds as $itemId) {
                $lastSub = ConformitySubmission::where('item_id', $itemId)
                    ->where('entreprise_id', $this->entrepriseId)
                    ->latest('submitted_at')
                    ->first();

                if ($lastSub && $lastSub->status === 'approuvé') {
                    $conformes++;
                }
            }

            $score = $total > 0 ? round(($conformes / $total) * 100) : 0;

            $this->categorieStats[] = [
                'nom_categorie' => $categorie->nom_categorie,
                'nom_domaine'   => $categorie->nom_domaine,
                'total'         => $total,
                'conformes'     => $conformes,
                'score'         => $score,
            ];
        }
    }


    /**
     * Évolution temporelle
     */
    private function loadEvolutionData(): void
    {
        $months = [];
        $dates = $this->getDateRange();

        foreach ($dates as $date) {
            $itemIds = $this->getItemIds();
            $conformes = 0;

            foreach ($itemIds as $itemId) {
                $lastSub = ConformitySubmission::where('item_id', $itemId)
                    ->where('entreprise_id', $this->entrepriseId)
                    ->where('submitted_at', '<=', $date)
                    ->latest('submitted_at')
                    ->first();

                if ($lastSub && $lastSub->status === 'approuvé') {
                    $conformes++;
                }
            }

            $total = $itemIds->count();
            $score = $total > 0 ? round(($conformes / $total) * 100) : 0;

            $months[] = [
                'date' => Carbon::parse($date)->format('M Y'),
                'score' => $score,
                'conformes' => $conformes,
                'total' => $total,
            ];
        }

        $this->evolutionData = $months;
    }

    /**
     * Items avec périodes expirées
     */
    private function loadItemsExpires(): void
    {
        $today = now();

        $itemIds = $this->getItemIds();

        $this->itemsExpires = [];

        foreach ($itemIds as $itemId) {
            $item = Item::with(['CategorieDomaine.Domaine'])->find($itemId);

            if (!$item) continue;

            $periodeActive = PeriodeItem::where('item_id', $itemId)
                ->where('entreprise_id', $this->entrepriseId)
                ->where('statut', '1')
                ->whereDate('debut_periode', '<=', $today)
                ->whereDate('fin_periode', '>=', $today)
                ->first();

            if ($periodeActive) {
                continue; // Pas expiré
            }

            $dernierePeriode = PeriodeItem::where('item_id', $itemId)
                ->where('entreprise_id', $this->entrepriseId)
                ->latest('fin_periode')
                ->first();

            if ($dernierePeriode && $dernierePeriode->fin_periode < $today) {
                $joursExpires = $today->diffInDays($dernierePeriode->fin_periode);

                $this->itemsExpires[] = [
                    'nom_item' => $item->nom_item,
                    'categorie' => $item->CategorieDomaine->nom_categorie ?? '—',
                    'domaine' => $item->CategorieDomaine->Domaine->nom_domaine ?? '—',
                    'date_expiration' => $dernierePeriode->fin_periode->format('d/m/Y'),
                    'jours_expires' => $joursExpires,
                ];
            }
        }

        // Trier par nombre de jours expirés (décroissant)
        usort($this->itemsExpires, fn($a, $b) => $b['jours_expires'] - $a['jours_expires']);
    }

    /**
     * Statistiques de performance
     */
    private function loadPerformanceStats(): void
    {
        $itemIds = $this->getItemIds();

        // Temps moyen de validation
        $avgValidationTime = ConformitySubmission::whereIn('item_id', $itemIds)
            ->where('entreprise_id', $this->entrepriseId)
            ->whereNotNull('reviewed_at')
            ->get()
            ->avg(function ($sub) {
                return $sub->submitted_at->diffInHours($sub->reviewed_at);
            });

        // Taux d'approbation
        $totalReviewed = ConformitySubmission::whereIn('item_id', $itemIds)
            ->where('entreprise_id', $this->entrepriseId)
            ->whereIn('status', ['approuvé', 'rejeté'])
            ->count();

        $approved = ConformitySubmission::whereIn('item_id', $itemIds)
            ->where('entreprise_id', $this->entrepriseId)
            ->where('status', 'approuvé')
            ->count();

        $tauxApprobation = $totalReviewed > 0
            ? round(($approved / $totalReviewed) * 100)
            : 0;

        // Nombre de resoumissions moyennes
        $resoumissions = [];
        foreach ($itemIds as $itemId) {
            $count = ConformitySubmission::where('item_id', $itemId)
                ->where('entreprise_id', $this->entrepriseId)
                ->count();
            if ($count > 0) {
                $resoumissions[] = $count;
            }
        }

        $avgResoumissions = !empty($resoumissions)
            ? round(array_sum($resoumissions) / count($resoumissions), 1)
            : 0;

        $this->performanceStats = [
            'avg_validation_time' => round($avgValidationTime ?? 0),
            'taux_approbation' => $tauxApprobation,
            'avg_resoumissions' => $avgResoumissions,
        ];
    }

    /**
     * Helper : Récupérer les IDs des items selon les filtres
     */
    private function getItemIds()
    {
        $query = DB::table('entreprise_items')
            ->where('entreprise_id', $this->entrepriseId)
            ->where('statut', '1');

        if ($this->selectedDomaine) {
            $query->join('items', 'items.id', '=', 'entreprise_items.item_id')
                ->join('categorie_domaines', 'categorie_domaines.id', '=', 'items.categorie_domaine_id')
                ->where('categorie_domaines.domaine_id', $this->selectedDomaine);
        }

        return $query->pluck('entreprise_items.item_id');
    }

    /**
     * Helper : Récupérer les IDs des items d'un domaine
     */
    private function getItemIdsByDomaine(string $domaineId)
    {
        return DB::table('entreprise_items')
            ->join('items', 'items.id', '=', 'entreprise_items.item_id')
            ->join('categorie_domaines', 'categorie_domaines.id', '=', 'items.categorie_domaine_id')
            ->where('entreprise_items.entreprise_id', $this->entrepriseId)
            ->where('entreprise_items.statut', '1')
            ->where('categorie_domaines.domaine_id', $domaineId)
            ->pluck('items.id');
    }

    /**
     * Helper : Obtenir la plage de dates selon la période
     */
    private function getDateRange(): array
    {
        $dates = [];
        $end = now();

        switch ($this->periode) {
            case 'month':
                $start = now()->subMonths(6);
                for ($i = 0; $i < 6; $i++) {
                    $dates[] = $start->copy()->addMonths($i)->endOfMonth();
                }
                break;

            case 'quarter':
                $start = now()->subMonths(12);
                for ($i = 0; $i < 4; $i++) {
                    $dates[] = $start->copy()->addMonths($i * 3)->endOfMonth();
                }
                break;

            case 'year':
                $start = now()->subYears(3);
                for ($i = 0; $i < 3; $i++) {
                    $dates[] = $start->copy()->addYears($i)->endOfYear();
                }
                break;

            default: // 6 derniers mois par défaut
                $start = now()->subMonths(6);
                for ($i = 0; $i < 6; $i++) {
                    $dates[] = $start->copy()->addMonths($i)->endOfMonth();
                }
        }

        return $dates;
    }

    public function render()
    {
        // Préparer les données pour les graphiques (format JSON)
        $chartData = [
            'evolution' => [
                'labels' => array_column($this->evolutionData, 'date'),
                'scores' => array_column($this->evolutionData, 'score'),
            ],
            'repartition' => [
                'labels' => ['Conformes', 'Non Conformes', 'En Attente', 'Non Évalués'],
                'values' => [
                    $this->globalStats['conformes'] ?? 0,
                    $this->globalStats['non_conformes'] ?? 0,
                    $this->globalStats['en_attente'] ?? 0,
                    $this->globalStats['non_evalues'] ?? 0,
                ],
            ],
            'domaines' => [
                'labels' => array_column($this->domaineStats, 'nom'),
                'scores' => array_column($this->domaineStats, 'score'),
            ],
        ];

        return view('livewire.settings.compliance-statistics', [
            'chartData' => $chartData,
        ]);
    }
}
