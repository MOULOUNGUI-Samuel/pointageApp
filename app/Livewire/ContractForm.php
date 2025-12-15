<?php

namespace App\Livewire;

use App\Enums\ContractStatus;
use App\Enums\ContractType;
use App\Models\Contract;
use App\Models\User;
use App\Services\ContractService;
use Livewire\Component;
use Carbon\Carbon;

class ContractForm extends Component
{
    public $contractId;
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

    public $isEdit = false;
    public $contract;
    public $users = [];

    protected $rules = [
        'userId' => 'required|exists:users,id',
        'type_contrat' => 'required|string',
        'date_debut' => 'required|date',
        'date_fin' => 'nullable|date|after:date_debut',
        'salaire_base' => 'nullable|numeric|min:0',
        'mode_paiement' => 'nullable|string',
        'avantages' => 'nullable|string',
        'statut' => 'required|string',
        'notes' => 'nullable|string',
    ];

    protected $messages = [
        'userId.required' => 'Veuillez sélectionner un utilisateur.',
        'type_contrat.required' => 'Le type de contrat est obligatoire.',
        'date_debut.required' => 'La date de début est obligatoire.',
        'date_fin.after' => 'La date de fin doit être après la date de début.',
        'salaire_base.numeric' => 'Le salaire doit être un nombre.',
        'statut.required' => 'Le statut est obligatoire.',
    ];

    public function mount($contractId = null, $userId = null)
    {
        $this->entrepriseId = session('entreprise_id');

        // Charger les utilisateurs de l'entreprise
        $this->users = User::where('entreprise_id', $this->entrepriseId)
            ->orderBy('nom')
            ->orderBy('prenom')
            ->get();

        if ($contractId) {
            $this->isEdit = true;
            $this->contractId = $contractId;
            $this->contract = Contract::findOrFail($contractId);

            // Vérifier si le contrat peut être modifié
            if (!$this->contract->estModifiable()) {
                session()->flash('error', 'Ce contrat ne peut plus être modifié.');
                return redirect()->route('contracts.index');
            }

            $this->loadContractData();
        } else {
            $this->userId = $userId;
            $this->statut = ContractStatus::ACTIF->value;
            $this->date_debut = now()->format('Y-m-d');
        }
    }

    protected function loadContractData()
    {
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

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }

    public function save()
    {
        $this->validate();

        try {
            $contractService = new ContractService();

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
                'comment' => $this->comment ?: null,
            ];

            if ($this->isEdit) {
                $contract = $contractService->updateContract($this->contract, $data, auth()->user());
                session()->flash('success', 'Contrat mis à jour avec succès.');
            } else {
                $contract = $contractService->createContract($data, auth()->user());
                session()->flash('success', 'Contrat créé avec succès.');
            }

            $this->dispatch('contractCreated');
            return redirect()->route('contracts.show', $contract->id);

        } catch (\Exception $e) {
            session()->flash('error', $e->getMessage());
        }
    }

    public function cancel()
    {
        if ($this->isEdit) {
            return redirect()->route('contracts.show', $this->contractId);
        } else {
            return redirect()->route('contracts.index');
        }
    }

    public function render()
    {
        return view('livewire.contract-form', [
            'contractTypes' => ContractType::forSelect(),
            'contractStatuses' => ContractStatus::forSelect(),
        ]);
    }
}
