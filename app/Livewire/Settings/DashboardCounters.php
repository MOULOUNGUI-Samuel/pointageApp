<?php

namespace App\Livewire\Settings;

use App\Models\Domaine;
use App\Models\CategorieDommaine;
use App\Models\Item;
use Livewire\Component;
use Livewire\Attributes\On;

class DashboardCounters extends Component
{
    public int $domainesCount = 0;
    public int $categoriesCount = 0;
    public int $itemsCount = 0;

    public function mount(): void
    {
        $this->loadCounts();
    }

    private function loadCounts(): void
    {
        $this->domainesCount   = Domaine::count();
        $this->categoriesCount = CategorieDommaine::count();
        $this->itemsCount      = Item::count();
    }

    // 👉 écoute les événements des autres managers
    #[On('domaines-updated')]
    #[On('domaines-deleted')]
    #[On('categories-updated')]
    #[On('types-updated')]
    #[On('conformite-saved')]
    #[On('items-updated')]
    public function refreshCounters(): void
    {
        $this->loadCounts();
    }

    public function render()
    {
        return view('livewire.settings.dashboard-counters');
    }
}
