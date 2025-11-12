<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Entreprise;
use App\Services\AttributionIAService;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\On;

class AttributionEntreprise extends Component
{
    public $entreprise_id;
    public $entreprise;

    // Formulaire initial
    public $description_activite = '';

    // Formulaire supplémentaire
    public $nouveau_besoin = '';

    // Données générées par l'IA
    public $suggestions = null;
    public $resume = '';

    // États
    public $isGenerating = false;
    public $isInitial = true; // true si première attribution, false si supplémentaire
    public $errorMessage = '';
    public $successMessage = '';

    // Données éditables (pour retrait)
    public $domainesSelectionnes = [];

    protected $rules = [
        'description_activite' => 'required|string|min:10',
        'nouveau_besoin' => 'required|string|min:10',
    ];

    public function mount()
    {
        $this->entreprise_id = session('entreprise_id');
        $this->entreprise = Entreprise::findOrFail(session('entreprise_id'));

        // Vérifier si l'entreprise a déjà des domaines
        $this->isInitial = !$this->entreprise->hasDomainesConfigures();
    }

    /**
     * Génère les suggestions IA (initial ou supplémentaire)
     */
    public function generer()
    {
        if ($this->isInitial) {
            $this->validate(['description_activite' => 'required|string|min:10']);
        } else {
            $this->validate(['nouveau_besoin' => 'required|string|min:10']);
        }

        $this->isGenerating = true;
        $this->errorMessage = '';
        $this->suggestions = null;
        $this->domainesSelectionnes = [];

        try {
            $service = new AttributionIAService();

            if ($this->isInitial) {
                $resultat = $service->suggererAttributionInitiale(
                    $this->entreprise,
                    $this->description_activite
                );
            } else {
                $resultat = $service->suggererAttributionSupplementaire(
                    $this->entreprise,
                    $this->nouveau_besoin
                );
            }

            if (isset($resultat['success']) && $resultat['success'] === false) {
                $this->errorMessage = $resultat['message'];
                return;
            }

            $this->suggestions = $resultat['domaines'];
            $this->resume = $resultat['resume'];

            // Initialiser tous les domaines comme sélectionnés
            foreach ($this->suggestions as $domaine) {
                $this->domainesSelectionnes[$domaine['id']] = [
                    'selectionne' => true,
                    'categories' => []
                ];

                foreach ($domaine['categories'] as $categorie) {
                    $this->domainesSelectionnes[$domaine['id']]['categories'][$categorie['id']] = [
                        'selectionnee' => true,
                        'items' => []
                    ];

                    foreach ($categorie['items'] as $item) {
                        $this->domainesSelectionnes[$domaine['id']]['categories'][$categorie['id']]['items'][$item['id']] = true;
                    }
                }
            }
        } catch (\Exception $e) {
            $this->errorMessage = 'Erreur lors de la génération : ' . $e->getMessage();
        } finally {
            $this->isGenerating = false;
        }
    }

    /**
     * Retire un domaine (et toutes ses catégories/items en cascade)
     */
    public function retirerDomaine($domaineId)
    {
        if (isset($this->domainesSelectionnes[$domaineId])) {
            $this->domainesSelectionnes[$domaineId]['selectionne'] = false;

            // Désélectionner toutes les catégories et items
            foreach ($this->domainesSelectionnes[$domaineId]['categories'] as $catId => &$cat) {
                $cat['selectionnee'] = false;
                foreach ($cat['items'] as $itemId => &$item) {
                    $item = false;
                }
            }
        }
    }

    /**
     * Retire une catégorie (et tous ses items en cascade)
     */
    public function retirerCategorie($domaineId, $categorieId)
    {
        if (isset($this->domainesSelectionnes[$domaineId]['categories'][$categorieId])) {
            $this->domainesSelectionnes[$domaineId]['categories'][$categorieId]['selectionnee'] = false;

            // Désélectionner tous les items
            foreach ($this->domainesSelectionnes[$domaineId]['categories'][$categorieId]['items'] as $itemId => &$item) {
                $item = false;
            }

            // Vérifier si toutes les catégories du domaine sont désélectionnées
            $toutesDeselectionnes = true;
            foreach ($this->domainesSelectionnes[$domaineId]['categories'] as $cat) {
                if ($cat['selectionnee']) {
                    $toutesDeselectionnes = false;
                    break;
                }
            }

            if ($toutesDeselectionnes) {
                $this->domainesSelectionnes[$domaineId]['selectionne'] = false;
            }
        }
    }

    /**
     * Retire un item
     */
    public function retirerItem($domaineId, $categorieId, $itemId)
    {
        if (isset($this->domainesSelectionnes[$domaineId]['categories'][$categorieId]['items'][$itemId])) {
            $this->domainesSelectionnes[$domaineId]['categories'][$categorieId]['items'][$itemId] = false;

            // Vérifier si tous les items de la catégorie sont désélectionnés
            $tousItemsDeselectionnes = true;
            foreach ($this->domainesSelectionnes[$domaineId]['categories'][$categorieId]['items'] as $item) {
                if ($item) {
                    $tousItemsDeselectionnes = false;
                    break;
                }
            }

            if ($tousItemsDeselectionnes) {
                $this->domainesSelectionnes[$domaineId]['categories'][$categorieId]['selectionnee'] = false;
            }
        }
    }

    /**
     * Valide et enregistre les attributions
     */
    public function validerAttribution()
    {
        if (!$this->suggestions) {
            $this->errorMessage = 'Aucune suggestion à enregistrer';
            $this->dispatch('notify', type: 'info', message: 'Aucune suggestion à enregistrer');
            return;
        }

        DB::beginTransaction();

        try {
            $statsCreation = [
                'domaines' => 0,
                'categories' => 0,
                'items' => 0
            ];

            foreach ($this->suggestions as $domaine) {
                // Vérifier si le domaine est sélectionné
                if (!($this->domainesSelectionnes[$domaine['id']]['selectionne'] ?? false)) {
                    continue;
                }

                // Attacher le domaine si pas déjà attaché
                if (!$this->entreprise->domaines()->where('domaine_id', $domaine['id'])->exists()) {
                    $this->entreprise->domaines()->attach($domaine['id'], [
                        'statut' => '1',
                        'created_at' => now(),
                        'updated_at' => now()
                    ]);
                    $statsCreation['domaines']++;
                }

                foreach ($domaine['categories'] as $categorie) {
                    // Vérifier si la catégorie est sélectionnée
                    if (!($this->domainesSelectionnes[$domaine['id']]['categories'][$categorie['id']]['selectionnee'] ?? false)) {
                        continue;
                    }

                    // Attacher la catégorie si pas déjà attachée
                    if (!$this->entreprise->categories()->where('categorie_domaine_id', $categorie['id'])->exists()) {
                        $this->entreprise->categories()->attach($categorie['id'], [
                            'statut' => '1',
                            'created_at' => now(),
                            'updated_at' => now()
                        ]);
                        $statsCreation['categories']++;
                    }

                    foreach ($categorie['items'] as $item) {
                        // Vérifier si l'item est sélectionné
                        if (!($this->domainesSelectionnes[$domaine['id']]['categories'][$categorie['id']]['items'][$item['id']] ?? false)) {
                            continue;
                        }

                        // Attacher l'item si pas déjà attaché
                        if (!$this->entreprise->items()->where('item_id', $item['id'])->exists()) {
                            $this->entreprise->items()->attach($item['id'], [
                                'statut' => '1',
                                'created_at' => now(),
                                'updated_at' => now()
                            ]);
                            $statsCreation['items']++;
                        }
                    }
                }
            }

            DB::commit();

            $message = sprintf(
                'Attribution réussie ! %d domaine(s), %d catégorie(s) et %d item(s) attribués.',
                $statsCreation['domaines'],
                $statsCreation['categories'],
                $statsCreation['items']
            );

            $this->dispatch('notify', type: 'success', message: $message);

            $this->successMessage = $message;

            // Émettre un événement
            $this->dispatch('attribution-validee');

            // Réinitialiser
            $this->reset(['suggestions', 'domainesSelectionnes', 'description_activite', 'nouveau_besoin', 'resume']);

            // Rafraîchir l'état
            $this->isInitial = !$this->entreprise->fresh()->hasDomainesConfigures();

            // 🔁 rechargement complet (petit délai pour laisser voir le toast)
            $this->js('setTimeout(() => window.location.reload(), 1800)');
        } catch (\Exception $e) {
            DB::rollBack();
            $this->errorMessage = 'Erreur lors de l\'enregistrement : ' . $e->getMessage();
        }
    }

    /**
     * Annule et réinitialise
     */
    public function annuler()
    {
        $this->reset(['suggestions', 'domainesSelectionnes', 'description_activite', 'nouveau_besoin', 'resume', 'errorMessage']);
    }
    #[On('settings-submitted')]
    #[On('settings-reviewed')]
    #[On('wizard-config-reload')]
    public function render()
    {
        return view('livewire.attribution-entreprise');
    }
}
