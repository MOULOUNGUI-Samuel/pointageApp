<div>
    <div>
        {{-- ==================== En-tête (statistiques) ==================== --}}
        <div class="row g-3 mb-4">
            <div class="col-md-3">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <div class="text-muted small mb-1">Total Items</div>
                                <h3 class="mb-0">{{ $stats['total_items'] }}</h3>
                            </div>
                            <div class="bg-primary bg-opacity-10 rounded-circle p-3">
                                <i class="ti ti-list-check text-primary fs-22"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <div class="text-muted small mb-1">Périodes Actives</div>
                                <h3 class="mb-0">{{ $stats['avec_periode_active'] }}</h3>
                            </div>
                            <div class="bg-success bg-opacity-10 rounded-circle p-3">
                                <i class="ti ti-calendar-check text-success fs-22"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-2">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <div class="text-muted small mb-1">En Attente</div>
                                <h3 class="mb-0 text-warning">{{ $stats['en_attente'] }}</h3>
                            </div>
                            <div class="bg-warning bg-opacity-10 rounded-circle p-3">
                                <i class="ti ti-hourglass-high text-warning fs-22"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-2">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <div class="text-muted small mb-1">Approuvés</div>
                                <h3 class="mb-0 text-success">{{ $stats['approuves'] }}</h3>
                            </div>
                            <div class="bg-success bg-opacity-10 rounded-circle p-3">
                                <i class="ti ti-circle-check text-success fs-22"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-2">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <div class="text-muted small mb-1">Rejetés</div>
                                <h3 class="mb-0 text-danger">{{ $stats['rejetes'] }}</h3>
                            </div>
                            <div class="bg-danger bg-opacity-10 rounded-circle p-3">
                                <i class="ti ti-circle-x text-danger fs-22"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- ==================== Filtres ==================== --}}
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body">
                <div class="row g-3 align-items-end">
                    <div class="col-md-3">
                        <label class="form-label small fw-semibold mb-1" for="filter-search">
                            <i class="ti ti-search me-1"></i>Recherche
                        </label>
                        <input id="filter-search" type="text" class="form-control" placeholder="Nom de l'item..."
                            wire:model.debounce.400ms="search" wire:key="filter-search"
                            aria-label="Recherche par nom ou description">
                    </div>

                    <div class="col-md-3">
                        <label class="form-label small fw-semibold mb-1" for="filter-categorie">
                            <i class="ti ti-folder me-1"></i>Catégorie
                        </label>
                        <select id="filter-categorie" class="form-select" wire:model="filterCategorie"
                            wire:key="filter-categorie" aria-label="Filtrer par catégorie">
                            <option value="">Toutes les catégories</option>
                            @foreach ($categories as $cat)
                                <option value="{{ $cat->id }}">{{ $cat->nom_categorie }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label small fw-semibold mb-1" for="filter-periode">
                            <i class="ti ti-calendar me-1"></i>Période
                        </label>
                        <select id="filter-periode" class="form-select" wire:model="filterPeriode"
                            wire:key="filter-periode" aria-label="Filtrer par période">
                            <option value="all">Toutes</option>
                            <option value="active">Actives</option>
                            <option value="expired">Expirées</option>
                            <option value="no_period">Sans période</option>
                        </select>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label small fw-semibold mb-1" for="filter-status">
                            <i class="ti ti-filter me-1"></i>Statut Soumission
                        </label>
                        <select id="filter-status" class="form-select" wire:model="filterStatus"
                            wire:key="filter-status" aria-label="Filtrer par statut de soumission">
                            <option value="all">Tous</option>
                            <option value="no_submission">Non soumis</option>
                            <option value="soumis">En attente</option>
                            <option value="approuvé">Approuvé</option>
                            <option value="rejeté">Rejeté</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        {{-- ==================== Grille des items ==================== --}}
        <div class="row g-3">
            @forelse ($items as $item)
                @php
                    // Accesseurs fournis dans le modèle Item :
                    // - $item->periode_state : active|expired|disabled|upcoming|none
                    // - $item->periodeActive : période active (ou null)
                    // - $item->lastPeriode   : dernière période toutes situations
                    $state = $item->periode_state;
                    $periode = $item->periodeActive;
                    $lastP = $item->lastPeriode;
                    $lastSub = $item->lastSubmission;
                    $hasPending = (int) $item->pending_count > 0;

                    $typeColor =
                        $item->type === 'texte' ? 'primary' : ($item->type === 'documents' ? 'info' : 'secondary');
                    $fmt = fn($d) => \Illuminate\Support\Carbon::parse($d)->format('d/m/Y');
                @endphp

                <div class="col-md-6 col-lg-4">
                    <div class="card border-0 shadow-sm h-100 hover-shadow transition">
                        <div class="card-body">

                            {{-- ---------- En-tête ---------- --}}
                            <div class="d-flex align-items-start justify-content-between mb-3">
                                <div class="flex-grow-1">
                                    <h6 class="mb-1 fw-bold">{{ \Illuminate\Support\Str::limit($item->nom_item, 35) }}
                                    </h6>
                                    <div class="text-muted small">
                                        <i class="ti ti-folder me-1"></i>
                                        {{ $item->CategorieDomaine?->nom_categorie ?? '—' }}
                                    </div>
                                </div>
                                <span class="badge bg-{{ $typeColor }} bg-opacity-10 text-{{ $typeColor }}">
                                    {{ ucfirst($item->type) }}
                                </span>
                            </div>

                            {{-- ---------- État de période ---------- --}}
                            @if ($state === 'active')
                                <div
                                    class="d-flex align-items-center gap-2 mb-3 p-2 rounded-3 bg-success bg-opacity-10 border border-success-subtle">
                                    <i class="ti ti-calendar-check text-success"></i>
                                    <div class="small">
                                        <div class="fw-semibold text-success">Période active</div>
                                        <div class="text-muted">{{ $fmt($periode->debut_periode) }} —
                                            {{ $fmt($periode->fin_periode) }}</div>
                                    </div>
                                </div>
                            @elseif ($state === 'expired')
                                <div
                                    class="d-flex align-items-center gap-2 mb-3 p-2 rounded-3 bg-warning bg-opacity-10 border border-warning-subtle">
                                    <i class="ti ti-alert-triangle text-warning"></i>
                                    <div class="small">
                                        <div class="fw-semibold text-warning">Période expirée</div>
                                        @if ($lastP)
                                            <div class="text-muted">{{ $fmt($lastP->debut_periode) }} —
                                                {{ $fmt($lastP->fin_periode) }}</div>
                                        @endif
                                    </div>
                                </div>
                            @elseif ($state === 'disabled')
                                <div
                                    class="d-flex align-items-center gap-2 mb-3 p-2 rounded-3 bg-secondary bg-opacity-10 border border-secondary-subtle">
                                    <i class="ti ti-lock text-secondary"></i>
                                    <div class="small fw-semibold text-secondary">Période clôturée</div>
                                </div>
                            @elseif ($state === 'upcoming')
                                <div
                                    class="d-flex align-items-center gap-2 mb-3 p-2 rounded-3 bg-info bg-opacity-10 border border-info-subtle">
                                    <i class="ti ti-calendar-stats text-info"></i>
                                    <div class="small">
                                        <div class="fw-semibold text-info">Période à venir</div>
                                        @if ($lastP)
                                            <div class="text-muted">{{ $fmt($lastP->debut_periode) }} —
                                                {{ $fmt($lastP->fin_periode) }}</div>
                                        @endif
                                    </div>
                                </div>
                            @else
                                <div
                                    class="d-flex align-items-center gap-2 mb-3 p-2 rounded-3 bg-warning bg-opacity-10 border border-warning-subtle">
                                    <i class="ti ti-alert-triangle text-warning"></i>
                                    <div class="small fw-semibold text-warning">Aucune période définie</div>
                                </div>
                            @endif

                            {{-- ---------- Statut dernière soumission ---------- --}}
                            @if ($lastSub)
                                @php $submittedAt = \Illuminate\Support\Carbon::parse($lastSub->submitted_at)->format('d/m/Y H:i'); @endphp
                                <div class="mb-3">
                                    <div class="d-flex align-items-center justify-content-between mb-1">
                                        <span class="small text-muted">Dernière soumission</span>
                                        @if ($lastSub->status === 'approuvé')
                                            <span class="badge bg-success-subtle text-success border">
                                                <i class="ti ti-circle-check me-1"></i>Approuvé
                                            </span>
                                        @elseif ($lastSub->status === 'rejeté')
                                            <span class="badge bg-danger-subtle text-danger border">
                                                <i class="ti ti-circle-x me-1"></i>Rejeté
                                            </span>
                                        @elseif ($lastSub->status === 'soumis')
                                            <span class="badge bg-warning-subtle text-warning border">
                                                <i class="ti ti-hourglass-high me-1"></i>En attente
                                            </span>
                                        @endif
                                    </div>
                                    <div class="small text-muted">{{ $submittedAt }}</div>
                                </div>
                            @else
                                <div class="mb-3 p-2 rounded-3 bg-light border border-dashed">
                                    <div class="small text-muted text-center">
                                        <i class="ti ti-file-off me-1"></i> Aucune soumission
                                    </div>
                                </div>
                            @endif

                            {{-- ---------- Description ---------- --}}
                            @if ($item->description)
                                <p class="small text-muted mb-3">
                                    {{ \Illuminate\Support\Str::limit($item->description, 80) }}
                                </p>
                            @endif

                            {{-- ---------- Actions (strictes selon l’état) ---------- --}}
                            <div class="d-flex gap-2 flex-wrap">
                                @if ($state === 'active')
                                    @if ($lastSub && $lastSub->status === 'soumis')
                                        <button class="btn btn-sm btn-warning flex-fill" data-bs-toggle="modal"
                                            data-bs-target="#submitModal"
                                            wire:click="openSubmitModal('{{ $item->id }}', '{{ $lastSub->id }}')">
                                            <i class="ti ti-edit me-1"></i>Modifier
                                        </button>
                                    @elseif ($lastSub && $lastSub->status === 'rejeté')
                                        <button class="btn btn-sm btn-primary flex-fill" data-bs-toggle="modal"
                                            data-bs-target="#submitModal"
                                            wire:click="openSubmitModal('{{ $item->id }}')">
                                            <i class="ti ti-refresh me-1"></i>Resoumettre
                                        </button>
                                    @else
                                        <button class="btn btn-sm btn-primary flex-fill" data-bs-toggle="modal"
                                            data-bs-target="#submitModal"
                                            wire:click="openSubmitModal('{{ $item->id }}')">
                                            <i class="ti ti-send me-1"></i>Soumettre
                                        </button>
                                    @endif
                                    @if (auth()->user()->role?->SuperAdmin)
                                        <button class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal"
                                            data-bs-target="#periodesModal"
                                            wire:click="$dispatch('open-periode-manager', { id: '{{ $item->id }}' })">
                                            <i class="ti ti-calendar-event me-1"></i>Période
                                        </button>
                                    @endif
                                @else
                                    <button class="btn btn-sm btn-secondary flex-fill" disabled>
                                        <i class="ti ti-lock me-1"></i>
                                        @if ($state === 'expired')
                                            Période expirée
                                        @elseif($state === 'disabled')
                                            Période clôturée
                                        @elseif($state === 'upcoming')
                                            Pas encore ouverte
                                        @else
                                            Pas de période
                                        @endif
                                    </button>
                                    <button class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal"
                                        data-bs-target="#periodesModal"
                                        wire:click="$dispatch('open-periode-manager', { id: '{{ $item->id }}' })">
                                        <i class="ti ti-calendar-event me-1"></i>Période
                                    </button>
                                @endif

                                <button class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal"
                                    data-bs-target="#historyModal"
                                    wire:click="openHistoryModal('{{ $item->id }}')">
                                    <i class="ti ti-history"></i>
                                </button>

                                @if ($hasPending)
                                    <span
                                        class="badge bg-warning rounded-circle d-inline-flex align-items-center justify-content-center"
                                        style="width: 24px; height: 24px;"
                                        title="{{ $item->pending_count }} en attente">
                                        {{ $item->pending_count }}
                                    </span>
                                @endif
                            </div>

                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body text-center py-5">
                            <i class="ti ti-inbox fs-1 text-muted mb-3 d-block"></i>
                            <h5 class="text-muted">Aucun item trouvé</h5>
                            <p class="text-muted small">Ajustez vos filtres ou configurez vos items.</p>
                        </div>
                    </div>
                </div>
            @endforelse
        </div>

        {{-- ==================== Pagination ==================== --}}
        <div class="mt-4">
            {{ $items->links() }}
        </div>

        {{-- ==================== Modales ==================== --}}
        @include('livewire.settings.modals.submit-modal')
        @include('livewire.settings.modals.history-modal')
        {{-- (La modale de période "periodesModal" est incluse dans la page parent si nécessaire) --}}
    </div>

    <style>
        .hover-shadow {
            transition: all 0.3s ease;
        }

        .hover-shadow:hover {
            transform: translateY(-4px);
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, .15) !important;
        }

        .transition {
            transition: all 0.3s ease;
        }
    </style>
</div>
