<?php

namespace App\Livewire;

use App\Enums\ContractStatus;
use App\Enums\ContractType;
use App\Models\Contract;
use App\Models\User;
use App\Services\ContractService;
use Livewire\Component;
use Livewire\WithFileUploads;

class ContractModal extends Component
{
    use WithFileUploads;

    public $showModal = false;
    public $modalMode = 'create'; // create, edit, view, history, renew
    public $contractId = null;
    public $contract = null;

    // Form fields
    public $userId;
    public $entrepriseId;
    public $type_contrat = '';
    public $date_debut = '';
    public $date_fin = '';
    public $salaire_base = '';
    public $mode_paiement = '';
    public $avantages = '';
    public $statut = '';
    public $notes = '';
    public $comment = '';
    public $fichier_joint = null;

    public $users = [];
    public $histories = [];

    protected $listeners = [
        'openContractModal' => 'openModal',
        'closeContractModal' => 'closeModal'
    ];

    protected function rules()
    {
        return [
            'userId' => 'required|exists:users,id',
            'type_contrat' => 'required|string',
            'date_debut' => 'required|date',
            'date_fin' => 'nullable|date|after:date_debut',
            'salaire_base' => 'nullable|numeric|min:0',
            'mode_paiement' => 'nullable|string',
            'avantages' => 'nullable|string',
            'statut' => 'required|string',
            'fichier_joint' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:10240',
            'notes' => 'nullable|string',
        ];
    }

    protected $messages = [
        'userId.required' => 'Veuillez sélectionner un utilisateur.',
        'type_contrat.required' => 'Le type de contrat est obligatoire.',
        'date_debut.required' => 'La date de début est obligatoire.',
        'date_fin.after' => 'La date de fin doit être après la date de début.',
        'salaire_base.numeric' => 'Le salaire doit être un nombre.',
        'statut.required' => 'Le statut est obligatoire.',
    ];

    public function mount()
    {
        $this->entrepriseId = session('entreprise_id');
        $this->loadUsers();
    }

    public function loadUsers()
    {
        $this->users = User::where('entreprise_id', $this->entrepriseId)
            ->orderBy('nom')
            ->orderBy('prenom')
            ->get();
    }

    public function openModal($mode = 'create', $contractId = null, $userId = null)
    {
        $this->resetForm();
        $this->modalMode = $mode;
        $this->contractId = $contractId;
        $this->showModal = true;

        if ($mode === 'create') {
            $this->userId = $userId;
            $this->statut = ContractStatus::ACTIF->value;
            $this->date_debut = now()->format('Y-m-d');
        } elseif (in_array($mode, ['edit', 'view', 'history', 'renew']) && $contractId) {
            $this->loadContract($contractId);

            if ($mode === 'history') {
                $this->loadHistory();
            } elseif ($mode === 'renew') {
                $this->prepareRenewal();
            }
        }
    }

    public function loadContract($id)
    {
        $this->contract = Contract::with(['user', 'entreprise', 'createdBy', 'updatedBy', 'parentContract', 'renewedContracts'])
            ->findOrFail($id);

        if ($this->modalMode === 'edit') {
            $this->userId = $this->contract->user_id;
            $this->type_contrat = $this->contract->type_contrat;
            $this->date_debut = $this->contract->date_debut ? $this->contract->date_debut->format('Y-m-d') : '';
            $this->date_fin = $this->contract->date_fin ? $this->contract->date_fin->format('Y-m-d') : '';
            $this->salaire_base = $this->contract->salaire_base;
            $this->mode_paiement = $this->contract->mode_paiement;
            $this->avantages = $this->contract->avantages;
            $this->statut = $this->contract->statut;
            $this->notes = $this->contract->notes;
        }
    }

    public function loadHistory()
    {
        $contractService = new ContractService();
        $this->histories = $contractService->getContractHistory($this->contract);
    }

    public function prepareRenewal()
    {
        // Pré-remplir les données pour le renouvellement
        $this->userId = $this->contract->user_id;
        $this->type_contrat = $this->contract->type_contrat;
        $this->salaire_base = $this->contract->salaire_base;
        $this->mode_paiement = $this->contract->mode_paiement;
        $this->avantages = $this->contract->avantages;
        $this->statut = ContractStatus::ACTIF->value;

        // Nouvelle date de début = lendemain de la date de fin de l'ancien contrat
        if ($this->contract->date_fin) {
            $this->date_debut = $this->contract->date_fin->addDay()->format('Y-m-d');
        } else {
            $this->date_debut = now()->format('Y-m-d');
        }

        // Calculer la nouvelle date de fin si le contrat original en avait une
        if ($this->contract->date_fin && $this->contract->date_debut) {
            $duree = $this->contract->date_debut->diffInDays($this->contract->date_fin);
            $this->date_fin = now()->addDays($duree)->format('Y-m-d');
        } else {
            $this->date_fin = '';
        }

        $this->notes = '';
    }

    public function save()
    {
        $this->validate();

        try {
            $contractService = new ContractService();

            // Gérer l'upload du fichier
            $fichierPath = null;
            if ($this->fichier_joint) {
                $fichierPath = $this->fichier_joint->store('contrats', 'public');
            }

            $data = [
                'user_id' => $this->userId,
                'entreprise_id' => $this->entrepriseId,
                'type_contrat' => $this->type_contrat,
                'date_debut' => $this->date_debut,
                'date_fin' => $this->date_fin ?: null,
                'salaire_base' => $this->salaire_base ?: null,
                'mode_paiement' => $this->mode_paiement ?: null,
                'avantages' => $this->avantages ?: null,
                'statut' => $this->statut,
                'notes' => $this->notes ?: null,
                'fichier_joint' => $fichierPath,
                'comment' => $this->comment ?: null,
            ];

            if ($this->modalMode === 'edit') {
                $contractService->updateContract($this->contract, $data, auth()->user());
                session()->flash('success', 'Contrat mis à jour avec succès.');
            } elseif ($this->modalMode === 'renew') {
                $contractService->renewContract($this->contract, $data, auth()->user());
                session()->flash('success', 'Contrat renouvelé avec succès.');
            } else {
                $contractService->createContract($data, auth()->user());
                session()->flash('success', 'Contrat créé avec succès.');
            }

            $this->closeModal();
            $this->dispatch('contractUpdated');

        } catch (\Exception $e) {
            session()->flash('modal_error', $e->getMessage());
        }
    }

    public function suspendContract()
    {
        try {
            $contractService = new ContractService();
            $contractService->suspendContract($this->contract, auth()->user(), $this->comment);

            session()->flash('success', 'Contrat suspendu avec succès.');
            $this->closeModal();
            $this->dispatch('contractUpdated');
        } catch (\Exception $e) {
            session()->flash('modal_error', $e->getMessage());
        }
    }

    public function reactivateContract()
    {
        try {
            $contractService = new ContractService();
            $contractService->reactivateContract($this->contract, auth()->user(), $this->comment);

            session()->flash('success', 'Contrat réactivé avec succès.');
            $this->closeModal();
            $this->dispatch('contractUpdated');
        } catch (\Exception $e) {
            session()->flash('modal_error', $e->getMessage());
        }
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->resetForm();
    }

    public function resetForm()
    {
        $this->contractId = null;
        $this->contract = null;
        $this->userId = null;
        $this->type_contrat = '';
        $this->date_debut = '';
        $this->date_fin = '';
        $this->salaire_base = '';
        $this->mode_paiement = '';
        $this->avantages = '';
        $this->statut = '';
        $this->notes = '';
        $this->comment = '';
        $this->histories = [];
        $this->resetErrorBag();
    }

    public function render()
    {
        return view('livewire.contract-modal', [
            'contractTypes' => ContractType::forSelect(),
            'contractStatuses' => ContractStatus::forSelect(),
        ]);
    }
}
