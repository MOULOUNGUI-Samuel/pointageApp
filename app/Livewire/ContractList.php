<?php

namespace App\Livewire;

use App\Models\Contract;
use App\Models\User;
use App\Services\ContractService;
use Livewire\Component;
use Livewire\WithPagination;

class ContractList extends Component
{
    use WithPagination;

    public $userId;
    public $entrepriseId;
    public $showAll = false; // Pour afficher tous les contrats ou seulement les actifs
    public $search = '';
    public $statutFilter = '';
    public $typeContratFilter = '';
    public $contractToDelete = null;

    protected $listeners = [
        'contractCreated' => '$refresh',
        'contractUpdated' => '$refresh'
    ];

    public function mount($userId = null, $entrepriseId = null, $showAll = false)
    {
        $this->userId = $userId;
        $this->entrepriseId = $entrepriseId ?? session('entreprise_id');
        $this->showAll = $showAll;
    }

    public function render()
    {
        $query = Contract::query()
            ->with(['user', 'entreprise', 'createdBy', 'parentContract']);

        // Filtrer par utilisateur si spécifié
        if ($this->userId) {
            $query->where('user_id', $this->userId);
        }

        // Filtrer par entreprise
        if ($this->entrepriseId) {
            $query->where('entreprise_id', $this->entrepriseId);
        }

        // Filtrer par recherche
        if ($this->search) {
            $query->whereHas('user', function ($q) {
                $q->where('nom', 'like', '%' . $this->search . '%')
                    ->orWhere('prenom', 'like', '%' . $this->search . '%')
                    ->orWhere('matricule', 'like', '%' . $this->search . '%');
            });
        }

        // Filtrer par statut
        if ($this->statutFilter) {
            $query->where('statut', $this->statutFilter);
        }

        // Filtrer par type de contrat
        if ($this->typeContratFilter) {
            $query->where('type_contrat', $this->typeContratFilter);
        }

        // Afficher seulement les contrats actifs par défaut
        if (!$this->showAll && !$this->statutFilter) {
            $query->where('statut', 'actif');
        }

        $contracts = $query->orderBy('date_debut', 'desc')->paginate(10);

        return view('livewire.contract-list', [
            'contracts' => $contracts,
        ]);
    }

    public function toggleShowAll()
    {
        $this->showAll = !$this->showAll;
        $this->resetPage();
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingStatutFilter()
    {
        $this->resetPage();
    }

    public function updatingTypeContratFilter()
    {
        $this->resetPage();
    }

    public function confirmDelete()
    {
        if (!$this->contractToDelete) {
            return;
        }

        try {
            $contract = Contract::findOrFail($this->contractToDelete);

            // Vérifier les permissions
            if ($contract->entreprise_id !== $this->entrepriseId) {
                session()->flash('error', 'Vous n\'avez pas la permission de supprimer ce contrat.');
                return;
            }

            // Supprimer le contrat
            $contract->delete();

            session()->flash('success', 'Le contrat a été supprimé avec succès.');

            // Réinitialiser
            $this->contractToDelete = null;

            // Rafraîchir la liste
            $this->resetPage();

        } catch (\Exception $e) {
            session()->flash('error', 'Une erreur est survenue lors de la suppression du contrat.');
        }
    }
}
