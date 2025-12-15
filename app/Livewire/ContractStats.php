<?php

namespace App\Livewire;

use App\Models\Contract;
use App\Enums\ContractStatus;
use Livewire\Component;
use Illuminate\Support\Facades\DB;

class ContractStats extends Component
{
    public $entrepriseId;

    protected $listeners = ['contractCreated' => '$refresh', 'contractUpdated' => '$refresh'];

    public function mount($entrepriseId = null)
    {
        $this->entrepriseId = $entrepriseId ?? auth()->user()->entreprise_id;
    }

    public function render()
    {
        $query = Contract::where('entreprise_id', $this->entrepriseId);

        // Statistiques principales
        $totalContrats = (clone $query)->count();
        $contratsActifs = (clone $query)->where('statut', ContractStatus::ACTIF->value)->count();
        $contratsSuspendus = (clone $query)->where('statut', ContractStatus::SUSPENDU->value)->count();
        $contratsTermines = (clone $query)->where('statut', ContractStatus::TERMINE->value)->count();

        // Contrats expirant dans 30 jours
        $contratsExpirant = (clone $query)
            ->where('statut', ContractStatus::ACTIF->value)
            ->whereNotNull('date_fin')
            ->whereBetween('date_fin', [now(), now()->addDays(30)])
            ->count();

        // Répartition par type de contrat
        $repartitionTypes = (clone $query)
            ->select('type_contrat', DB::raw('count(*) as total'))
            ->groupBy('type_contrat')
            ->get()
            ->pluck('total', 'type_contrat')
            ->toArray();

        return view('livewire.contract-stats', [
            'totalContrats' => $totalContrats,
            'contratsActifs' => $contratsActifs,
            'contratsSuspendus' => $contratsSuspendus,
            'contratsTermines' => $contratsTermines,
            'contratsExpirant' => $contratsExpirant,
            'repartitionTypes' => $repartitionTypes,
        ]);
    }
}
