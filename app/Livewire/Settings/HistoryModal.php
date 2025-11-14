<?php

namespace App\Livewire\Settings;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\On;
use App\Models\Item;
use App\Models\ConformitySubmission;
use Illuminate\Support\Facades\Storage;

class HistoryModal extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public ?string $itemId = null;
    public string $itemLabel = '';
    public string $entrepriseId;

    // Filtres
    public string $filterStatus = 'all'; // all|soumis|approuvé|rejeté
    public string $search = '';

    #[On('open-history-modal')]
    public function openForItem(string $itemId): void
    {
        $item = Item::findOrFail($itemId);
        $this->itemId = $item->id;
        $this->itemLabel = $item->nom_item;
        $this->resetPage();
    }

    public function mount(): void
    {
        $this->entrepriseId = (string) session('entreprise_id');
    }

    public function updatedFilterStatus(): void
    {
        $this->resetPage();
    }

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    /** Réinitialiser tous les filtres */
    public function resetFilters(): void
    {
        $this->search = '';
        $this->filterStatus = $this->filterStatus;
        $this->resetPage();
    }

    /** Ouvrir la modal de détails d'une soumission */
    public function viewDetails(string $submissionId): void
    {
        $this->dispatch('open-review-modal', submissionId: $submissionId);
    }

    /** Supprimer une soumission (seulement si non validée) */
    public function deleteSubmission(string $submissionId): void
    {
        $submission = ConformitySubmission::where('entreprise_id', $this->entrepriseId)
            ->where('item_id', $this->itemId)
            ->findOrFail($submissionId);

        // On ne peut supprimer que les soumissions en attente
        if ($submission->status !== 'soumis') {
            $this->dispatch('notify', [
                'type' => 'error',
                'message' => 'Impossible de supprimer une soumission validée ou rejetée.'
            ]);
            return;
        }

        // Supprimer les fichiers associés
        foreach ($submission->answers()->where('kind', 'documents')->get() as $answer) {
            if ($answer->file_path && Storage::disk('public')->exists($answer->file_path)) {
                Storage::disk('public')->delete($answer->file_path);
            }
        }

        $submission->delete();

        $this->dispatch('notify', [
            'type' => 'success',
            'message' => 'Soumission supprimée avec succès.'
        ]);

        $this->dispatch('wizard-config-reload');
        $this->resetPage();
    }

    public function render()
    {
        if (!$this->itemId) {
            // Pagination vide pour un état initial propre
            $submissions = ConformitySubmission::whereNull('id')->paginate(10);
            $stats = [
                'total'    => 0,
                'pending'  => 0,
                'approved' => 0,
                'rejected' => 0,
            ];
            return view('livewire.settings.history-modal', compact('submissions', 'stats'));
        }

        $query = ConformitySubmission::with([
            'submittedBy:id,nom,prenom',
            'reviewedBy:id,nom,prenom',               // <- renommé (ancien "reviewer")
            'periodeItem:id,item_id,debut_periode,fin_periode', // <- renommé (ancien "periode")
            'answers' => fn($q) => $q->orderBy('position'),
        ])
        ->where('item_id', $this->itemId)
        ->where('entreprise_id', $this->entrepriseId);

        // Filtre par statut
        if ($this->filterStatus !== 'all') {
            $query->where('status', $this->filterStatus);
        }

        // Recherche dans les notes / id
        if ($this->search !== '') {
            $s = trim($this->search);
            $query->where(function($q) use ($s) {
                $q->where('reviewer_notes', 'like', "%{$s}%")
                  ->orWhere('id', 'like', "%{$s}%");
            });
        }

        $submissions = $query->orderByDesc('submitted_at')->paginate(10);

        // Stats rapides (en s'appuyant sur les scopes du modèle)
        $base = ConformitySubmission::where('item_id', $this->itemId)
            ->where('entreprise_id', $this->entrepriseId);

        $stats = [
            'total'    => (clone $base)->count(),
            'pending'  => (clone $base)->enAttente()->count(),
            'approved' => (clone $base)->approuvees()->count(),
            'rejected' => (clone $base)->rejetees()->count(),
        ];

        return view('livewire.settings.history-modal', compact('submissions', 'stats'));
    }
}
