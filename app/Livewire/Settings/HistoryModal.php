<?php

namespace App\Livewire\Settings;

use Livewire\Component;
use App\Models\Item;
use App\Models\ConformitySubmission;

class HistoryModal extends Component
{
    public Item $item;
    public $submissions;

    public function mount(Item $item): void
    {
        $this->item = $item;
        $this->submissions = ConformitySubmission::with([
            'submitter:id,nom,prenom',
            'reviewer:id,nom,prenom',
            'periode:id,item_id,debut_periode,fin_periode',
        ])
        ->where('item_id', $item->id)
        ->where('entreprise_id', session('entreprise_id'))
        ->orderByDesc('submitted_at')
        ->get();
    }

    public function render()
    {
        return view('livewire.settings.history-modal');
    }
}
