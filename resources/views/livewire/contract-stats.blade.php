<div>
    <div class="row mb-4">
        <!-- Total des contrats -->
        <div class="col-xl-2 col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-1 small">Total des contrats</p>
                            <h3 class="mb-0 fw-bold">{{ $totalContrats }}</h3>
                        </div>
                        <div class="border border-primary p-3 rounded-circle">
                            <i class="ti ti-file-text text-primary fs-26"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Contrats actifs -->
        <div class="col-xl-2 col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-1 small">Contrats actifs</p>
                            <h3 class="mb-0 fw-bold text-success">{{ $contratsActifs }}</h3>
                            @if ($totalContrats > 0)
                                <small
                                    class="text-muted">{{ round(($contratsActifs / $totalContrats) * 100, 1) }}%</small>
                            @endif
                        </div>
                        <div class="border border-success p-3 rounded-circle">
                            <i class="ti ti-check text-success fs-26"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Contrats expirant -->
        <div class="col-xl-2 col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-1 small">Expirant sous 30j</p>
                            <h3 class="mb-0 fw-bold text-warning">{{ $contratsExpirant }}</h3>
                            @if ($contratsActifs > 0)
                                <small
                                    class="text-muted">{{ round(($contratsExpirant / $contratsActifs) * 100, 1) }}%</small>
                            @endif
                        </div>
                        <div class="border border-warning p-3 rounded-circle">
                            <i class="ti ti-alert-triangle text-warning fs-26"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Contrats suspendus/terminés -->
        <div class="col-xl-2 col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-1 small">Suspendus</p>
                            <h3 class="mb-0 fw-bold">
                                <span class="text-warning">{{ $contratsSuspendus }}</span>
                            </h3>
                        </div>
                        <div class="border border-secondary p-3 rounded-circle">
                            <i class="ti ti-circle-x text-secondary fs-26"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Contrats suspendus/terminés -->
        <div class="col-xl-2 col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-1 small"> Terminés</p>
                            <h3 class="mb-0 fw-bold">
                                <span class="text-danger">{{ $contratsTermines }}</span>
                            </h3>
                        </div>
                        <div class="border border-danger p-3 rounded-circle">
                            <i class="ti ti-circle-x text-danger fs-26"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-2 col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <!-- Répartition par type (optionnel) -->
                    @if (!empty($repartitionTypes))
                        @foreach ($repartitionTypes as $type => $count)
                            <div class="mb-2">
                                <div class="d-flex align-items-center">
                                    <div class="flex-grow-1">
                                        <span class="badge bg-info me-2">{{ $type }}</span>
                                    </div>
                                    <span class="fw-bold">{{ $count }}</span>
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
