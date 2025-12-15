<?php

namespace App\Livewire;

use App\Models\Contract;
use App\Services\ContractService;
use Livewire\Component;

class ContractHistory extends Component
{
    public $contractId;
    public $contract;
    public $histories = [];

    public function mount($contractId)
    {
        $this->contractId = $contractId;
        $this->contract = Contract::findOrFail($contractId);
        $this->loadHistory();
    }

    public function loadHistory()
    {
        $contractService = new ContractService();
        $this->histories = $contractService->getContractHistory($this->contract);
    }

    public function render()
    {
        return view('livewire.contract-history');
    }
}
