<div>
    {{-- En-tête avec filtres --}}
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <h3 class="mb-0">
                        <i class="ti ti-chart-pie me-2"></i>
                        Statistiques de Conformité
                    </h3>
                    <p class="text-muted small mb-0">Analyses et indicateurs de performance</p>
                </div>
                <div class="col-md-6">
                    <div class="d-flex gap-2 justify-content-end">
                        <select class="form-select form-select-sm" wire:model="periode" style="width: 200px;">
                            <option value="all">6 derniers mois</option>
                            <option value="month">Par mois</option>
                            <option value="quarter">Par trimestre</option>
                            <option value="year">Par année</option>
                        </select>

                        <button class="btn btn-sm btn-primary" wire:click="loadAllStatistics"
                            wire:loading.attr="disabled" wire:target="loadAllStatistics">

                            <!-- Icône normale -->
                            <span wire:loading.remove wire:target="loadAllStatistics">
                                <i class="ti ti-refresh me-1"></i>Actualiser
                            </span>

                            <!-- Icône de chargement -->
                            <span wire:loading wire:target="loadAllStatistics">
                                <span class="spinner-border spinner-border-sm me-1"></span>Chargement…
                            </span>
                        </button>

                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Score Global --}}
    <div class="row g-3 mb-3">
        <div class="col-md-3">
            <div class="card border-0 shadow">
                <div class="card-body text-center d-flex align-items-center">
                    <div class="me-3">
                        <div class="position-relative d-inline-block">
                            @php
                                $scoreGlobal = $globalStats['score_global'] ?? 0;
                            @endphp
                            <svg width="120" height="120" class="mx-auto">
                                <circle cx="60" cy="60" r="50" fill="none" stroke="#e9ecef"
                                    stroke-width="10" />
                                <circle cx="60" cy="60" r="50" fill="none"
                                    stroke="{{ $scoreGlobal >= 80 ? '#28a745' : ($scoreGlobal >= 60 ? '#ffc107' : '#dc3545') }}"
                                    stroke-width="10"
                                    stroke-dasharray="{{ (314 * $scoreGlobal) / 100 }} 314"
                                    transform="rotate(-90 60 60)" />
                            </svg>
                            <div class="position-absolute top-50 start-50 translate-middle">
                                <h1 class="mb-0" style="font-size: 2.5rem;">{{ $scoreGlobal }}%</h1>
                            </div>
                        </div>
                    </div>
                    <div class="">
                        <h5 class="mb-1">Score Global</h5>
                        <span
                            class="badge bg-{{ $scoreGlobal >= 80 ? 'success' : ($scoreGlobal >= 60 ? 'warning' : 'danger') }}">
                            {{ $globalStats['statut'] ?? 'Insuffisant' }}
                        </span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Critères totaux --}}
        <div class="col-md-3">
            <div class="card border-0 shadow">
                <div class="card-body">
                    @php
                        $totalCriteres = $globalStats['total_criteres'] ?? 0;
                        $evalues = $globalStats['evalues'] ?? 0;
                        $conformes = $globalStats['conformes'] ?? 0;
                        $nonConformes = $globalStats['non_conformes'] ?? 0;
                        $enAttente = $globalStats['en_attente'] ?? 0;
                        $nonEvalues = $globalStats['non_evalues'] ?? max($totalCriteres - $evalues, 0);
                    @endphp

                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <div class="bg-primary bg-opacity-10 rounded-circle p-3">
                            <i class="ti ti-list-check text-primary" style="font-size: 18px"></i>
                        </div>
                        <h2 class="mb-0">{{ $totalCriteres }}</h2>
                    </div>
                    <h6 class="text-muted mb-2">Critères Totaux</h6>
                    <div class="progress" style="height: 6px;">
                        <div class="progress-bar bg-primary" role="progressbar" style="width: 100%"></div>
                    </div>
                    <div class="mt-2 small text-muted">
                        <i class="ti ti-eye me-1"></i>{{ $evalues }} évalués
                    </div>
                </div>
            </div>
        </div>

        {{-- Conformes --}}
        <div class="col-md-3">
            <div class="card border-0 shadow">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <div class="bg-success bg-opacity-10 rounded-circle p-3">
                            <i class="ti ti-circle-check text-success" style="font-size: 18px"></i>
                        </div>
                        <h2 class="mb-0 text-success">{{ $conformes }}</h2>
                    </div>
                    <h6 class="text-muted mb-2">Conformes</h6>
                    <div class="progress" style="height: 6px;">
                        <div class="progress-bar bg-success" role="progressbar"
                            style="width: {{ $totalCriteres > 0 ? ($conformes / $totalCriteres) * 100 : 0 }}%">
                        </div>
                    </div>
                    <div class="mt-2 small text-muted">
                        {{ $totalCriteres > 0 ? round(($conformes / $totalCriteres) * 100, 1) : 0 }}%
                        du total
                    </div>
                </div>
            </div>
        </div>

        {{-- Non conformes --}}
        <div class="col-md-3">
            <div class="card border-0 shadow">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <div class="bg-danger bg-opacity-10 rounded-circle p-3">
                            <i class="ti ti-alert-triangle text-danger " style="font-size: 18px"></i>
                        </div>
                        <h2 class="mb-0 text-danger">{{ $nonConformes }}</h2>
                    </div>
                    <h6 class="text-muted mb-2">Non Conformes</h6>
                    <div class="progress" style="height: 6px;">
                        <div class="progress-bar bg-danger" role="progressbar"
                            style="width: {{ $totalCriteres > 0 ? ($nonConformes / $totalCriteres) * 100 : 0 }}%">
                        </div>
                    </div>
                    <div class="mt-2 small text-muted">
                        {{ $totalCriteres > 0 ? round(($nonConformes / $totalCriteres) * 100, 1) : 0 }}%
                        du total
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Graphiques --}}
    <div class="row g-3 mb-4">
        {{-- Évolution du score --}}
        <div class="col-lg-7">
            <div class="card border-0 shadow">
                <div class="card-header bg-white border-bottom">
                    <h5 class="mb-0">
                        <i class="ti ti-trending-up me-2"></i>
                        Évolution du Score de Conformité
                    </h5>
                </div>
                <div class="card-body">
                    <canvas id="evolutionChart" height="80"></canvas>
                </div>
            </div>
        </div>

        {{-- Répartition par statut --}}
        <div class="col-lg-5">
            @php
                $enAttente = $globalStats['en_attente'] ?? 0;
            @endphp
            <div class="card border-0 shadow">
                <div class="card-header bg-white border-bottom">
                    <h5 class="mb-0">
                        <i class="ti ti-chart-donut me-2"></i>
                        Répartition par Statut
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-1"></div>
                        <div class="col-md-7 text-center" style="height: 230px">
                            <canvas id="repartitionChart"></canvas>
                        </div>
                        <div class="col-md-4">
                            <div class="mr-3">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span class="small"><span class="badge bg-success"></span> Conformes</span>
                                    <strong>{{ $conformes }}</strong>
                                </div>
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span class="small"><span class="badge bg-danger"></span> Non Conformes</span>
                                    <strong>{{ $nonConformes }}</strong>
                                </div>
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span class="small"><span class="badge bg-warning"></span> En Attente</span>
                                    <strong>{{ $enAttente }}</strong>
                                </div>
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="small"><span class="badge bg-secondary"></span> Non Évalués</span>
                                    <strong>{{ $nonEvalues }}</strong>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Statistiques par Domaine --}}
    <div class="card border-0 shadow mb-4">
        <div class="card-header bg-white border-bottom">
            <h5 class="mb-0">
                <i class="ti ti-folders me-2"></i>
                Répartition Détaillée par Domaine
            </h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Domaine</th>
                            <th class="text-center">Évalués</th>
                            <th class="text-center">Conformes</th>
                            <th class="text-center">Non Conformes</th>
                            <th class="text-center">Total</th>
                            <th class="text-center">Score</th>
                            <th class="text-center">Statut</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($domaineStats as $domaine)
                            <tr>
                                <td>
                                    <i class="ti ti-folder me-2 text-primary"></i>
                                    <strong>{{ $domaine['nom'] }}</strong>
                                </td>
                                <td class="text-center">{{ $domaine['evalues'] }}</td>
                                <td class="text-center">
                                    <span class="badge bg-success">{{ $domaine['conformes'] }}</span>
                                </td>
                                <td class="text-center">
                                    <span class="badge bg-danger">{{ $domaine['non_conformes'] }}</span>
                                </td>
                                <td class="text-center"><strong>{{ $domaine['total'] }}</strong></td>
                                <td class="text-center">
                                    <div class="d-inline-block" style="width: 60px;">
                                        <div class="progress" style="height: 20px;">
                                            <div class="progress-bar bg-{{ $domaine['score'] >= 80 ? 'success' : ($domaine['score'] >= 60 ? 'warning' : 'danger') }}"
                                                role="progressbar" style="width: {{ $domaine['score'] }}%">
                                                <small>{{ $domaine['score'] }}%</small>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="text-center">
                                    <span
                                        class="badge bg-{{ $domaine['score'] >= 80 ? 'success' : ($domaine['score'] >= 60 ? 'warning' : 'danger') }}">
                                        {{ $domaine['statut'] }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted">
                                    <i class="ti ti-inbox fs-1"></i>
                                    <p class="mb-0">Aucun domaine trouvé</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Graphique des scores par domaine --}}
            @if (!empty($domaineStats))
                <div class="mt-4">
                    <h6 class="mb-3">Comparaison des Scores par Domaine</h6>
                    <canvas id="domainesChart" height="60"></canvas>
                </div>
            @endif
        </div>
    </div>

    {{-- Indicateurs de Performance --}}
    <div class="row g-3 mb-4">
        @php
            $avgValidationTime = $performanceStats['avg_validation_time'] ?? 0;
            $tauxApprobation = $performanceStats['taux_approbation'] ?? 0;
            $avgResoumissions = $performanceStats['avg_resoumissions'] ?? 0;
        @endphp

        <div class="col-md-4">
            <div class="card border-0 shadow h-100">
                <div class="card-body text-center">
                    <i class="ti ti-clock text-primary fs-1 mb-3"></i>
                    <h2 class="mb-1">{{ $avgValidationTime }}h</h2>
                    <p class="text-muted mb-0">Temps Moyen de Validation</p>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card border-0 shadow h-100">
                <div class="card-body text-center">
                    <i class="ti ti-checkbox text-success fs-1 mb-3"></i>
                    <h2 class="mb-1">{{ $tauxApprobation }}%</h2>
                    <p class="text-muted mb-0">Taux d'Approbation</p>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card border-0 shadow h-100">
                <div class="card-body text-center">
                    <i class="ti ti-refresh text-warning fs-1 mb-3"></i>
                    <h2 class="mb-1">{{ $avgResoumissions }}</h2>
                    <p class="text-muted mb-0">Moyenne de Resoumissions</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Items avec périodes expirées --}}
    @if (!empty($itemsExpires))
        <div class="card border-0 shadow mb-4">
            <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="ti ti-alert-circle me-2 text-danger"></i>
                    Items avec Périodes Expirées
                </h5>
                <span class="badge bg-danger">{{ count($itemsExpires) }} critères</span>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Item</th>
                                <th>Catégorie</th>
                                <th>Domaine</th>
                                <th class="text-center">Date d'Expiration</th>
                                <th class="text-center">Jours Expirés</th>
                                <th class="text-center">Urgence</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($itemsExpires as $item)
                                <tr>
                                    <td><strong>{{ $item['nom_item'] }}</strong></td>
                                    <td>{{ $item['categorie'] }}</td>
                                    <td>
                                        <i class="ti ti-folder me-1 text-primary"></i>
                                        {{ $item['domaine'] }}
                                    </td>
                                    <td class="text-center">{{ $item['date_expiration'] }}</td>
                                    <td class="text-center">
                                        <span class="badge bg-danger">{{ $item['jours_expires'] }} jours</span>
                                    </td>
                                    <td class="text-center">
                                        @if ($item['jours_expires'] > 60)
                                            <span class="badge bg-danger">
                                                <i class="ti ti-alert-triangle me-1"></i>Critique
                                            </span>
                                        @elseif($item['jours_expires'] > 30)
                                            <span class="badge bg-warning">
                                                <i class="ti ti-alert-circle me-1"></i>Urgent
                                            </span>
                                        @else
                                            <span class="badge bg-secondary">
                                                <i class="ti ti-clock me-1"></i>À renouveler
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endif

    {{-- Statistiques par Catégorie --}}
    @if (!empty($categorieStats))
        <div class="card border-0 shadow">
            <div class="card-header bg-white border-bottom">
                <h5 class="mb-0">
                    <i class="ti ti-category me-2"></i>
                    Statistiques par Catégorie
                </h5>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    @foreach ($categorieStats as $categorie)
                        <div class="col-md-6 col-lg-4">
                            <div class="card border">
                                <div class="card-body">
                                    <h6 class="mb-1">{{ $categorie['nom_categorie'] }}</h6>
                                    <p class="text-muted small mb-3">{{ $categorie['nom_domaine'] }}</p>

                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <span class="small">Conformes</span>
                                        <strong>{{ $categorie['conformes'] }} / {{ $categorie['total'] }}</strong>
                                    </div>

                                    <div class="progress mb-2" style="height: 8px;">
                                        <div class="progress-bar bg-{{ $categorie['score'] >= 80 ? 'success' : ($categorie['score'] >= 60 ? 'warning' : 'danger') }}"
                                            role="progressbar" style="width: {{ $categorie['score'] }}%">
                                        </div>
                                    </div>

                                    <div class="text-center">
                                        <span
                                            class="badge bg-{{ $categorie['score'] >= 80 ? 'success' : ($categorie['score'] >= 60 ? 'warning' : 'danger') }}">
                                            {{ $categorie['score'] }}%
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endif

    {{-- Scripts Chart.js --}}
    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const chartData = @json($chartData);

                // Graphique d'évolution
                if (document.getElementById('evolutionChart')) {
                    new Chart(document.getElementById('evolutionChart'), {
                        type: 'line',
                        data: {
                            labels: chartData.evolution.labels,
                            datasets: [{
                                label: 'Score de Conformité (%)',
                                data: chartData.evolution.scores,
                                borderColor: '#0d6efd',
                                backgroundColor: 'rgba(13, 110, 253, 0.1)',
                                tension: 0.4,
                                fill: true,
                                pointRadius: 5,
                                pointHoverRadius: 7,
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: true,
                            plugins: {
                                legend: {
                                    display: false
                                },
                                tooltip: {
                                    callbacks: {
                                        label: function(context) {
                                            return 'Score: ' + context.parsed.y + '%';
                                        }
                                    }
                                }
                            },
                            scales: {
                                y: {
                                    beginAtZero: true,
                                    max: 100,
                                    ticks: {
                                        callback: function(value) {
                                            return value + '%';
                                        }
                                    }
                                }
                            }
                        }
                    });
                }

                // Graphique de répartition
                if (document.getElementById('repartitionChart')) {
                    new Chart(document.getElementById('repartitionChart'), {
                        type: 'doughnut',
                        data: {
                            labels: chartData.repartition.labels,
                            datasets: [{
                                data: chartData.repartition.values,
                                backgroundColor: [
                                    '#28a745',
                                    '#dc3545',
                                    '#ffc107',
                                    '#6c757d'
                                ],
                                borderWidth: 2,
                                borderColor: '#fff'
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: true,
                            plugins: {
                                legend: {
                                    display: false
                                }
                            }
                        }
                    });
                }

                // Graphique des domaines
                if (document.getElementById('domainesChart')) {
                    new Chart(document.getElementById('domainesChart'), {
                        type: 'bar',
                        data: {
                            labels: chartData.domaines.labels,
                            datasets: [{
                                label: 'Score (%)',
                                data: chartData.domaines.scores,
                                backgroundColor: chartData.domaines.scores.map(score =>
                                    score >= 80 ? '#28a745' : (score >= 60 ? '#ffc107' : '#dc3545')
                                ),
                                borderRadius: 6,
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: true,
                            plugins: {
                                legend: {
                                    display: false
                                }
                            },
                            scales: {
                                y: {
                                    beginAtZero: true,
                                    max: 100,
                                    ticks: {
                                        callback: function(value) {
                                            return value + '%';
                                        }
                                    }
                                }
                            }
                        }
                    });
                }
            });

            // Écouter les événements Livewire pour recharger les graphiques
            Livewire.on('chartDataUpdated', () => {
                location.reload();
            });
        </script>
    @endpush
</div>
