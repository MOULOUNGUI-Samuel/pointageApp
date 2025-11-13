<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\On;
use App\Models\Item;
use App\Models\Entreprise;
use App\Models\PeriodeItem;
use App\Services\PeriodeIAService;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class DefinirPeriode extends Component
{
    /** Identifiants & modèles */
    public ?string $itemId = null;
    public string $itemLabel = '';

    public ?Item $item = null;
    public ?Entreprise $entreprise = null;
    public ?string $entreprise_id = null;

    /** Contexte IA */
    public string $contexte_supplementaire = '';

    /** Suggestions IA */
    public ?array $suggestions = null;
    public string $notes = '';
    public bool $isGenerating = false;

    /** Index de la suggestion sélectionnée */
    public ?int $suggestion_selectionnee = null;

    /** Messages d’état */
    public string $errorMessage = '';
    public string $successMessage = '';

    public function mount(): void
    {
        $this->entreprise_id = (string) session('entreprise_id');

        if ($this->entreprise_id) {
            $this->entreprise = Entreprise::find($this->entreprise_id);
        }

        if (! $this->entreprise) {
            // Si tu préfères, tu peux mettre un message plutôt qu'un abort
            abort(403, "Entreprise introuvable dans la session.");
        }
    }

    /**
     * Ouverture de la modale pour un item donné.
     * 
     * Event déclenché depuis la vue :
     * wire:click="$dispatch('modal-periode-manager', { id: '{{ $item->id }}' })"
     */
   
    #[On('modal-periode-manager')]
    public function openForItem(string $id): void
    {
        $item = Item::findOrFail($id);


        $this->item      = $item;                // ✅ important
        $this->itemId    = $item->id;
        $this->itemLabel = $item->nom_item;

        // Reset de l'état logique de la modale (on garde item/entreprise)
        $this->reset([
            'suggestions',
            'notes',
            'contexte_supplementaire',
            'suggestion_selectionnee',
            'errorMessage',
            'successMessage',
        ]);
    }


    /**
     * Génère les suggestions de périodes via le service IA.
     */
    public function genererSuggestions(): void
    {
        if (! $this->item || ! $this->entreprise) {
            $this->errorMessage = "Item ou entreprise non initialisés.";
            return;
        }

        $this->isGenerating = true;
        $this->errorMessage = '';
        $this->suggestions = null;
        $this->notes = '';
        $this->suggestion_selectionnee = null;

        try {
            $service = new PeriodeIAService();

            $resultat = $service->suggererPeriodes(
                $this->item,
                $this->entreprise,
                $this->contexte_supplementaire
            );

            $this->suggestions = $resultat['suggestions'] ?? [];
            $this->notes       = $resultat['notes'] ?? '';

            // Auto-sélection de la suggestion recommandée
            foreach ($this->suggestions as $index => $suggestion) {
                if (!empty($suggestion['est_recommande'])) {
                    $this->suggestion_selectionnee = $index;
                    break;
                }
            }
        } catch (\Exception $e) {
            $this->errorMessage = 'Erreur lors de la génération : ' . $e->getMessage();
        } finally {
            $this->isGenerating = false;
        }
    }

    /**
     * Sélection d'une suggestion dans la liste.
     */
    public function selectionnerSuggestion(int|string $index): void
    {
        $index = (int) $index;

        if (! isset($this->suggestions[$index])) {
            $this->errorMessage = "Suggestion invalide.";
            return;
        }

        $this->suggestion_selectionnee = $index;
    }

    /**
     * Validation et création de la période à partir de la suggestion sélectionnée.
     */
    public function validerPeriode(): void
    {
        if (! $this->item || ! $this->entreprise) {
            $this->errorMessage = "Item ou entreprise non initialisés.";
            return;
        }

        if (
            $this->suggestion_selectionnee === null ||
            ! isset($this->suggestions[$this->suggestion_selectionnee])
        ) {
            $this->errorMessage = 'Veuillez sélectionner une période.';
            return;
        }

        // Vérifier l'existence d'une période active
        $periodeActive = PeriodeItem::where('item_id', $this->item->id)
            ->where('entreprise_id', $this->entreprise->id)
            ->where('statut', '1')
            ->whereDate('debut_periode', '<=', now())
            ->whereDate('fin_periode', '>=', now())
            ->first();

        if ($periodeActive) {
            $this->errorMessage = 'Une période active existe déjà pour cet item et cette entreprise.';
            return;
        }

        DB::beginTransaction();

        try {
            $suggestion = $this->suggestions[$this->suggestion_selectionnee];

            $service = new PeriodeIAService();

            $periode = $service->creerPeriodeDepuisSuggestion(
                $this->item,
                $this->entreprise,
                $suggestion,
                auth()->id()
            );

            DB::commit();

            $this->successMessage = sprintf(
                'Période créée avec succès ! Du %s au %s (%s)',
                $periode->debut_periode->format('d/m/Y'),
                $periode->fin_periode->format('d/m/Y'),
                $suggestion['duree_libelle'] ?? ''
            );

            // Event Livewire pour prévenir le parent / autres composants
            $this->dispatch('periode-creee', id: $periode->id);

            // Reset logique (on garde item / entreprise)
            $this->reset([
                'suggestions',
                'notes',
                'contexte_supplementaire',
                'suggestion_selectionnee',
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            $this->errorMessage = 'Erreur lors de la création : ' . $e->getMessage();
        }
    }

    /**
     * Annuler / réinitialiser le formulaire IA.
     */
    public function annuler(): void
    {
        $this->reset([
            'suggestions',
            'notes',
            'contexte_supplementaire',
            'suggestion_selectionnee',
            'errorMessage',
            'successMessage',
        ]);
    }

    public function render()
    {
        $periodes_existantes = collect();

        if ($this->item && $this->entreprise) {
            $periodes_existantes = PeriodeItem::where('item_id', $this->item->id)
                ->where('entreprise_id', $this->entreprise->id)
                ->orderBy('debut_periode', 'desc')
                ->get();
        }

        return view('livewire.definir-periode', [
            'periodes_existantes' => $periodes_existantes,
        ]);
    }
}
