<div wire:poll.30s>
    {{-- Filtres --}}
    <div class="row g-2 mb-3">
        <div class="col-sm-3">
            <input type="date" class="form-control" wire:model.live="dateFrom" placeholder="Du">
        </div>
        <div class="col-sm-3">
            <input type="date" class="form-control" wire:model.live="dateTo" placeholder="Au">
        </div>
        <div class="col-sm-3 ms-auto">
            <select class="form-select" wire:model.live="statusFocus">
                <option value="">Tous les statuts</option>
                <option value="brouillon">Brouillon</option>
                <option value="soumis">Soumis</option>
                <option value="approuvé">Approuvé</option>
                <option value="rejeté">Rejeté</option>
            </select>
        </div>
    </div>

    {{-- KPIs --}}


    {{-- Répartition (donut) + Dernières activités --}}
    <div class="row g-3 mt-2">
        <div class="col-lg-8">
            <div class="row g-3">
                <div class="col-md-4">
                    <div class="card shadow-sm h-100">
                        <div class="card-body">
                            <div class="text-muted small">Total</div>
                            <div class="h3 mb-0">{{ $countAll }}</div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card shadow-sm h-100">
                        <div class="card-body">
                            <div class="text-muted small">Brouillon</div>
                            <div class="h3 mb-0">{{ $countDraft }}</div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card shadow-sm h-100">
                        <div class="card-body">
                            <div class="text-muted small">Soumis</div>
                            <div class="h3 mb-0">{{ $countPending }}</div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card shadow-sm h-100">
                        <div class="card-body">
                            <div class="text-muted small">Approuvé</div>
                            <div class="h3 mb-0 text-success">{{ $countApproved }}</div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card shadow-sm h-100">
                        <div class="card-body">
                            <div class="text-muted small">Rejeté</div>
                            <div class="h3 mb-0 text-danger">{{ $countRejected }}</div>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card shadow-sm h-100">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="text-muted small">Retours à confirmer</div>
                                @if ($returnsLate > 0)
                                    <span class="badge bg-danger">{{ $returnsLate }} en retard</span>
                                @endif
                            </div>
                            <div class="h3 mb-0">{{ $returnsToConfirm }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-white">
                    <strong>Dernières activités</strong>
                </div>
                <div class="card-body p-0">
                    <div class="list-group list-group-flush">
                        @forelse($latest as $a)
                            @php
                                $emp = $a->user?->nom . ' ' . ($a->user?->prenom ?? '');
                                $badge =
                                    [
                                        'brouillon' => 'secondary',
                                        'soumis' => 'warning',
                                        'approuvé' => 'success',
                                        'rejeté' => 'danger',
                                    ][$a->status] ?? 'secondary';
                            @endphp
                            <div class="list-group-item d-flex justify-content-between align-items-center">
                                <div>
                                    <div class="fw-semibold text-capitalize">
                                        {{ str_replace('_', ' ', $a->type) }}
                                        <span class="badge bg-{{ $badge }} ms-2">{{ ucfirst($a->status) }}</span>
                                    </div>
                                    <small class="text-muted">
                                        {{ $a->start_datetime?->format('d/m H:i') }} →
                                        {{ $a->end_datetime?->format('d/m H:i') }}
                                        — {{ trim($emp) ?: '—' }} @if ($a->user?->fonction)
                                            · {{ $a->user->fonction }}
                                        @endif
                                    </small>
                                </div>
                                <small class="text-muted">{{ $a->updated_at?->diffForHumans() }}</small>
                            </div>
                        @empty
                            <div class="list-group-item text-muted">Rien à afficher.</div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Chart.js (CDN) --}}
    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            (function() {
                const ctx = document.getElementById('absDonut');
                if (!ctx) return;

                // Détruit un éventuel graphique existant pour éviter les doublons au $refresh
                if (window.__absDonut) {
                    window.__absDonut.destroy();
                }
                Livewire.on('absencesUpdated', (fresh) => {
                    const f = Object.assign({
                        brouillon: 0,
                        soumis: 0,
                        approuvé: 0,
                        rejeté: 0
                    }, fresh || {});
                    chart.data.datasets[0].data = [f.brouillon, f.soumis, f.approuvé, f.rejeté];
                    chart.update();
                });

                window.__absDonut = new Chart(ctx.getContext('2d'), {
                    type: 'doughnut',
                    data: {
                        labels: @json($chartLabels),
                        datasets: [{
                            data: @json($chartData),
                        }]
                    },
                    options: {
                        responsive: true,
                        plugins: {
                            legend: {
                                position: 'bottom'
                            }
                        },
                        cutout: '60%'
                    }
                });
            })();
        </script>
    @endpush
</div>
