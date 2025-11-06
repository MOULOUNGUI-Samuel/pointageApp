<?php

namespace App\Livewire\Absences;

use App\Models\Absence;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Dashboard extends Component
{
    /** Portée entreprise */
    public string $companyKey = 'entreprise_id';              // adapte si besoin
    public string $forCompanyId;

    /** Filtres */
    public ?string $dateFrom = null; // Y-m-d
    public ?string $dateTo   = null; // Y-m-d

    /** Focus initial sur 'soumis' dans l’UI si tu veux filtrer la liste rapide */
    public string $statusFocus = 'soumis';

    /** Mise à jour auto depuis d’autres composants */
    protected $listeners = ['absencesUpdated' => '$refresh'];

    public function mount(): void
    {
        $this->forCompanyId = (string) session('entreprise_id');
    }

    private function scopedBase()
    {
        return Absence::query()
            ->whereHas('user', function ($q) {
                $q->where($this->companyKey, $this->forCompanyId);
            })
            ->when($this->dateFrom, fn($q) => $q->whereDate('start_datetime', '>=', $this->dateFrom))
            ->when($this->dateTo,   fn($q) => $q->whereDate('end_datetime',   '<=', $this->dateTo));
    }

    public function render()
    {
        $base = $this->scopedBase();

        // Répartition par statut
        $byStatus = (clone $base)
            ->select('status', DB::raw('COUNT(*) as total'))
            ->groupBy('status')
            ->pluck('total','status');

        $countDraft   = (int) ($byStatus['brouillon'] ?? 0);
        $countPending = (int) ($byStatus['soumis'] ?? 0);
        $countApproved= (int) ($byStatus['approuvé'] ?? 0);
        $countRejected= (int) ($byStatus['rejeté'] ?? 0);
        $countAll     = $countDraft + $countPending + $countApproved + $countRejected;

        // Retours à confirmer = approuvés sans retour confirmé (quelle que soit la date)
        $returnsToConfirm = (clone $base)
            ->where('status','approuvé')
            ->whereNull('return_confirmed_at')
            ->count();

        // Retours en retard = approuvés, fin dépassée, non confirmés
        $returnsLate = (clone $base)
            ->where('status','approuvé')
            ->whereNull('return_confirmed_at')
            ->where('end_datetime','<', now())
            ->count();

        // Dernières activités (liste rapide)
        $latest = (clone $base)
            ->with('user:id,nom,prenom,fonction,'.$this->companyKey)
            ->orderByDesc('updated_at')
            ->limit(8)
            ->get([
                'id','user_id','type','status','start_datetime','end_datetime',
                'approved_at','return_confirmed_at','updated_at'
            ]);

        // Jeu pour le donut Chart.js
        $chartLabels = ['Brouillon','Soumis','Approuvé','Rejeté'];
        $chartData   = [$countDraft,$countPending,$countApproved,$countRejected];

        return view('livewire.absences.dashboard', [
            'countAll'         => $countAll,
            'countDraft'       => $countDraft,
            'countPending'     => $countPending,
            'countApproved'    => $countApproved,
            'countRejected'    => $countRejected,
            'returnsToConfirm' => $returnsToConfirm,
            'returnsLate'      => $returnsLate,
            'latest'           => $latest,
            'chartLabels'      => $chartLabels,
            'chartData'        => $chartData,
        ]);
    }
}
