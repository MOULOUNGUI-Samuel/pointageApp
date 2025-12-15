<?php

namespace App\Livewire;

use App\Enums\ContractType;
use App\Models\Contract;
use App\Services\ContractService;
use Livewire\Component;

class ContractRenewal extends Component
{
    public $contractId;
    public $oldContract;
    public $type_contrat = '';
    public $date_debut = '';
    public $date_fin = '';
    public $salaire_base = '';
    public $mode_paiement = '';
    public $avantages = '';
    public $notes = '';
    public $comment = '';

    protected $rules = [
        'type_contrat' => 'required|string',
        'date_debut' => 'required|date',
        'date_fin' => 'nullable|date|after:date_debut',
        'salaire_base' => 'nullable|numeric|min:0',
        'mode_paiement' => 'nullable|string',
        'avantages' => 'nullable|string',
        'notes' => 'nullable|string',
    ];

    protected $messages = [
        'type_contrat.required' => 'Le type de contrat est obligatoire.',
        'date_debut.required' => 'La date de début est obligatoire.',
        'date_fin.after' => 'La date de fin doit être après la date de début.',
        'salaire_base.numeric' => 'Le salaire doit être un nombre.',
    ];

    public function mount($contractId)
    {
        $this->contractId = $contractId;
        $this->oldContract = Contract::findOrFail($contractId);

        // Vérifier si le contrat peut être renouvelé
        if (!$this->oldContract->estRenouvelable()) {
            session()->flash('error', 'Ce contrat ne peut pas être renouvelé pour le moment.');
            return redirect()->route('contracts.show', $contractId);
        }

        // Pré-remplir avec les données de l'ancien contrat
        $this->type_contrat = $this->oldContract->type_contrat;
        $this->salaire_base = $this->oldContract->salaire_base;
        $this->mode_paiement = $this->oldContract->mode_paiement;
        $this->avantages = $this->oldContract->avantages;

        // Dates par défaut pour le nouveau contrat
        if ($this->oldContract->date_fin) {
            // Commencer le lendemain de la fin de l'ancien contrat
            $this->date_debut = $this->oldContract->date_fin->addDay()->format('Y-m-d');
        } else {
            $this->date_debut = now()->format('Y-m-d');
        }
    }

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }

    public function renew()
    {
        $this->validate();

        try {
            $contractService = new ContractService();

            $data = [
                'type_contrat' => $this->type_contrat,
                'date_debut' => $this->date_debut,
                'date_fin' => $this->date_fin ?: null,
                'salaire_base' => $this->salaire_base ?: null,
                'mode_paiement' => $this->mode_paiement ?: null,
                'avantages' => $this->avantages ?: null,
                'notes' => $this->notes ?: null,
                'comment' => $this->comment ?: null,
            ];

            $newContract = $contractService->renewContract($this->oldContract, $data, auth()->user());

            session()->flash('success', 'Contrat renouvelé avec succès.');
            return redirect()->route('contracts.show', $newContract->id);

        } catch (\Exception $e) {
            session()->flash('error', $e->getMessage());
        }
    }

    public function cancel()
    {
        return redirect()->route('contracts.show', $this->contractId);
    }

    public function render()
    {
        return view('livewire.contract-renewal', [
            'contractTypes' => ContractType::forSelect(),
        ]);
    }
}
