<div>
    <div>
        {{-- ==================== En-tête (statistiques) ==================== --}}
        <div class="row g-3">
            <div class="col-md-3">
                <div class="card border-0 shadow">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <div class="text-muted small mb-1">Total Items</div>
                                <h3 class="mb-0">{{ $stats['total_items'] }}</h3>
                            </div>
                            <div class="border border-primary rounded-circle p-3">
                                <i class="ti ti-list-check text-primary fs-22"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- <div class="col-md-3">
                <div class="card border-0 shadow">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <div class="text-muted small mb-1">En date de validité</div>
                                <h3 class="mb-0">{{ $stats['avec_periode_active'] }}</h3>
                            </div>
                            <div class="border border-success rounded-circle p-3">
                                <i class="ti ti-calendar-check text-success fs-22"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div> --}}

            <div class="col-md-3">
                <div class="card border-0 shadow">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <div class="text-muted small mb-1">Total en attente</div>
                                <h3 class="mb-0 text-warning">{{ $stats['en_attente'] }}</h3>
                            </div>
                            <div class="border border-warning rounded-circle p-3">
                                <i class="ti ti-hourglass-high text-warning fs-22"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card border-0 shadow">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <div class="text-muted small mb-1">Total Conformes</div>
                                <h3 class="mb-0 text-success">{{ $statsGlobales['valides'] }}</h3>
                            </div>
                            <div class="border border-success rounded-circle p-3">
                                <i class="ti ti-circle-check text-success fs-22"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card border-0 shadow">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <div class="text-muted small mb-1">Total Non Conformes</div>
                                <h3 class="mb-0 text-danger">{{ $statsGlobales['non_conformes'] }}</h3>
                            </div>
                            <div class="border border-danger rounded-circle p-3">
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

                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h4 class="mb-0 small text-muted" style="font-size: 20px">
                        <i class="ti ti-filter me-1"></i>Filtres
                        @if ($filterDomaine)
                            <span class="badge bg-primary ms-2">
                                {{ collect($domaineStats)->firstWhere('id', $filterDomaine)['nom'] ?? 'Domaine sélectionné' }}
                            </span>
                        @else
                            <span class="badge bg-primary ms-2">
                                Tous les domaines
                            </span>
                        @endif
                    </h4>
                    <button type="button" class="btn btn-outline-primary" wire:click="selectDomaine(null)"
                        style="cursor: pointer; transition: all 0.3s ease;">
                        <div class="d-flex align-items-center justify-content-between">
                            <i class="ti ti-layout-grid text-secondary fs-3 me-3"></i>

                            <h6 class="mb-1 fw-bold">Tous les domaines @if (!$filterDomaine)
                                    <span class="badge bg-primary">
                                        <i class="ti ti-check me-1"></i>
                                    </span>
                                @endif
                            </h6>
                        </div>
                    </button>

                </div>
                @if (!empty($domaineStats))
                    <div class="row g-2">
                        @foreach ($domaineStats as $domaine)
                            <div class="col-md-4 col-lg-4 col-xl-4">
                                <div class="domaine-card card border-0 shadow {{ $filterDomaine === $domaine['id'] ? 'border-primary border-3' : '' }}"
                                    wire:click="selectDomaine('{{ $domaine['id'] }}')"
                                    style="cursor: pointer; transition: all 0.3s ease;">

                                    <div class="card-body">
                                        <div class="d-flex align-items-center justify-content-between">
                                            <div class="d-flex align-items-center gap-2 flex-grow-1">
                                                <div class="border border-primary bg-primary rounded-circle p-2">
                                                    <i class="ti ti-folder text-white fs-22"></i>
                                                    {{-- <i class="{{ $domaine['icone'] }} text-primary fs-5"></i> --}}
                                                </div>
                                                <div class="flex-grow-1">
                                                    <h6 class="mb-0 small fw-bold text-truncate">
                                                        {{ \Illuminate\Support\Str::limit($domaine['nom'], 25, '...') }}
                                                    </h6>
                                                    <div class="text-muted" style="font-size: 0.75rem;">
                                                        {{ $domaine['total'] }}
                                                        critère{{ $domaine['total'] > 1 ? 's' : '' }}
                                                    </div>
                                                </div>
                                            </div>

                                            @if ($filterDomaine === $domaine['id'])
                                                <span class="badge bg-primary">
                                                    <i class="ti ti-check"></i>
                                                </span>
                                            @endif
                                            <div class="d-flex gap-2 ms-3">
                                                {{-- Items valides (approuvés) --}}
                                                <div class="flex-fill text-center" title="Items Conformes">
                                                    <div class="fw-bold text-success rounded px-2 border border-success"
                                                        style="display: flex; align-items: center; justify-content: center;">

                                                        <strong
                                                            class="text-success">{{ $domaine['valides'] }}/{{ $domaine['total'] ?? 0 }}</strong>

                                                    </div>
                                                    <div class="text-muted mt-1" style="font-size: 0.65rem;">Conforme
                                                    </div>
                                                </div>

                                                {{-- Items non conformes --}}
                                                <div class="flex-fill text-center"
                                                    title="Items non conformes (action requise)">
                                                    <div class="fw-bold text-danger px-2 rounded border border-danger"
                                                        style="display: flex; align-items: center; justify-content: center;">
                                                        <strong
                                                            class="text-danger">{{ $domaine['non_conformes'] ?? 0 }}/{{ $domaine['total'] ?? 0 }}</strong>
                                                    </div>
                                                    <div class="text-muted mt-1" style="font-size: 0.65rem;">Non conf.
                                                    </div>
                                                </div>
                                            </div>
                                        </div>


                                        {{-- Statistiques par période_state --}}
                                        <div class="pt-2">
                                            <div class="d-flex flex-wrap gap-2">

                                                @php
                                                    $periodeIcons = [
                                                        'active' => [
                                                            'icon' => 'ti-circle-check',
                                                            'color' => 'success',
                                                            'label' => 'Active',
                                                        ],
                                                        'upcoming' => [
                                                            'icon' => 'ti-calendar-event',
                                                            'color' => 'info',
                                                            'label' => 'À venir',
                                                        ],
                                                        'expired' => [
                                                            'icon' => 'ti-alert-circle',
                                                            'color' => 'warning',
                                                            'label' => 'Expirée',
                                                        ],
                                                        'disabled' => [
                                                            'icon' => 'ti-lock',
                                                            'color' => 'primary',
                                                            'label' => 'Clôturée',
                                                        ],
                                                        'none' => [
                                                            'icon' => 'ti-calendar-off',
                                                            'color' => 'muted',
                                                            'label' => 'Aucune',
                                                        ],
                                                    ];
                                                @endphp

                                                @foreach ($periodeIcons as $state => $config)
                                                    @if (($domaine['periode_stats'][$state] ?? 0) > 0)
                                                        <div class="badge bg-{{ $config['color'] }}-subtle text-{{ $config['color'] }} border border-{{ $config['color'] }}"
                                                            style="font-size: 0.7rem;" title="{{ $config['label'] }}">
                                                            <i class="ti {{ $config['icon'] }} me-1"></i>
                                                            {{ $domaine['periode_stats'][$state] }}/{{ $domaine['total'] }}
                                                        </div>
                                                    @endif
                                                @endforeach
                                            </div>
                                        </div>

                                        {{-- Barre de progression --}}
                                        <div class="mt-2">
                                            @php
                                                $pourcentage =
                                                    $domaine['total'] > 0
                                                        ? round(
                                                            (($domaine['valides'] > 0 ? $domaine['valides'] : 0) /
                                                                $domaine['total']) *
                                                                100,
                                                        )
                                                        : 0;
                                            @endphp
                                            <div class="progress" style="height: 4px;">
                                                <div class="progress-bar bg-success" role="progressbar"
                                                    style="width: {{ $pourcentage }}%"
                                                    aria-valuenow="{{ $pourcentage }}" aria-valuemin="0"
                                                    aria-valuemax="100">
                                                </div>
                                            </div>
                                            <div class="text-center text-muted mt-1" style="font-size: 0.7rem;">
                                                {{ $pourcentage }}% complété
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
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

                    <div class="col-md-2">
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
                    <div class="col-md-1">
                        <button type="button" class="btn btn-outline-primary me-3" wire:click="refresh"
                            wire:loading.attr="disabled" wire:target="refresh" style="margin-top: -30px">

                            <!-- Affichage normal -->
                            <span wire:loading.remove wire:target="refresh">
                                <i class="ti ti-search me-1"></i>
                                Rech...
                            </span>

                            <!-- Affichage pendant le chargement -->
                            <span wire:loading wire:target="refresh">
                                <span class="spinner-border spinner-border-sm me-1"></span>
                                Char…
                            </span>

                        </button>

                    </div>
                </div>
            </div>
        </div>


        {{-- ==================== Liste des items (affichage en ligne) ==================== --}}
        <div class="row g-2">
            @forelse ($items as $item)
                @php
                    $state = $item->periode_state;
                    $periode = $item->periodeActive;
                    $lastP = $item->lastPeriode;
                    $lastSub = $item->lastSubmission;
                    $hasPending = (int) $item->pending_count > 0;

                    switch ($item->type) {
                        case 'texte':
                            $typeColor = 'primary';
                            break;

                        case 'Checkbox':
                            $typeColor = 'dark';
                            break;
                        case 'liste':
                            $typeColor = 'warning';
                            break;
                        default:
                            $typeColor = 'secondary';
                            break;
                    }

                    $fmt = fn($d) => \Illuminate\Support\Carbon::parse($d)->format('d/m/Y');
                @endphp
                @php
                    // Calcul de la couleur de bordure selon la logique de conformité
                    $borderColor = '#6c757d'; // gris par défaut

                    if ($lastSub) {
                        // Règle métier : Si période active ET statut approuvé → NON CONFORME (rouge)
                        if ($item->hasActivePeriode && $lastSub->status === 'approuvé') {
                            $borderColor = '#dc3545'; // rouge - non conforme
                        } else {
                            // Sinon, couleur selon le statut réel
                            $borderColor = match ($lastSub->status) {
                                'approuvé' => '#28a745', // vert - approuvé
                                'rejeté' => '#dc3545', // rouge - rejeté
                                'soumis' => '#ffc107', // jaune - en attente
                                default => '#6c757d', // gris - autre
                            };
                        }
                    } else {
                        // Pas de soumission
                        if ($item->hasActivePeriode) {
                            // Période active sans soumission → NON CONFORME (rouge)
                            $borderColor = '#dc3545';
                        } else {
                            // Pas de période active, pas de soumission → gris neutre
                            $borderColor = '#6c757d';
                        }
                    }
                @endphp

                <div class="col-12">
                    <div class="card border-0 shadow-sm hover-shadow transition">
                        <div class="card-body rounded"
                            style="border-left: 10px solid {{ $borderColor }}; cursor:pointer">
                            <div class="d-flex flex-column flex-xl-row align-items-stretch gap-3">

                                {{-- ================= COL 1 : Infos item ================= --}}
                                <div class="flex-grow-1 min-w-0">
                                    {{-- En-tête --}}
                                    <div class="d-flex align-items-start justify-content-between mb-2">
                                        <div class="flex-grow-1">
                                            <h4 class="mb-1 fw-bold text-truncate">
                                                {{ \Illuminate\Support\Str::limit($item->nom_item, 120) }}
                                            </h4>
                                            <div class="text-muted small" style="font-size: 18px">
                                                <i class="ti ti-folder me-1"></i>
                                                {{ $item->CategorieDomaine?->nom_categorie ?? '—' }}
                                            </div>
                                        </div>
                                        <span
                                            class="btn btn-sm btn-outline-{{ $typeColor }} text-{{ $typeColor }}"
                                            style="font-size: 15px">
                                            {{ ucfirst($item->type) }}
                                        </span>
                                    </div>

                                    {{-- Description --}}
                                    @if ($item->description)
                                        <p class="small text-muted mb-0" style="font-size: 16px">
                                            {{ \Illuminate\Support\Str::limit($item->description, 150) }}
                                        </p>
                                    @endif
                                </div>

                                {{-- ================= COL 2 : Période + dernière soumission ================= --}}
                                <div class="flex-grow-1 flex-xl-grow-0" style="min-width: 260px;">
                                    {{-- État de période (reprend exactement tes blocs) --}}
                                    @if ($state === 'active' || $periode)
                                        <div
                                            class="d-flex align-items-center gap-2 mb-2 p-2 rounded-3 bg-success bg-opacity-10 border border-success-subtle">
                                            <i class="ti ti-calendar-check text-success"></i>
                                            <div class="small">
                                                <div class="fw-semibold text-success">Période active</div>
                                                <div class="text-muted">
                                                    {{ $fmt($periode->debut_periode) }} —
                                                    {{ $fmt($periode->fin_periode) }}
                                                </div>
                                            </div>
                                        </div>
                                    @elseif ($state === 'expired')
                                        <div
                                            class="d-flex align-items-center gap-2 mb-2 p-2 rounded-3 bg-warning bg-opacity-10 border border-warning-subtle">
                                            <i class="ti ti-alert-triangle text-warning"></i>
                                            <div class="small">
                                                <div class="fw-semibold text-warning">Période expirée</div>
                                                @if ($lastP)
                                                    <div class="text-muted">
                                                        {{ $fmt($lastP->debut_periode) }} —
                                                        {{ $fmt($lastP->fin_periode) }}
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    @elseif ($state === 'disabled')
                                        <div
                                            class="d-flex align-items-center gap-2 mb-2 p-2 rounded-3 bg-secondary bg-opacity-10 border border-secondary-subtle">
                                            <i class="ti ti-lock text-secondary"></i>
                                            <div class="small fw-semibold text-secondary">Période clôturée</div>
                                        </div>
                                    @elseif ($state === 'upcoming')
                                        <div
                                            class="d-flex align-items-center gap-2 mb-2 p-2 rounded-3 bg-info bg-opacity-10 border border-info-subtle">
                                            <i class="ti ti-calendar-stats text-info"></i>
                                            <div class="small">
                                                <div class="fw-semibold text-info">Période à venir</div>
                                                @if ($lastP)
                                                    <div class="text-muted">
                                                        {{ $fmt($lastP->debut_periode) }} —
                                                        {{ $fmt($lastP->fin_periode) }}
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    @else
                                        <div
                                            class="d-flex align-items-center gap-2 mb-2 p-2 rounded-3 bg-warning bg-opacity-10 border border-warning-subtle">
                                            <i class="ti ti-alert-triangle text-warning"></i>
                                            <div class="small fw-semibold text-warning">Aucune période définie</div>
                                        </div>
                                    @endif

                                    {{-- Statut dernière soumission --}}
                                    @php
                                        // Logique centralisée pour déterminer le statut de conformité
                                        $conformiteStatus = null;
                                        $conformiteLabel = '';
                                        $conformiteIcon = '';
                                        $conformiteBadgeClass = '';
                                        $submittedAtFormatted = null;

                                        if ($lastSub) {
                                            $submittedAtFormatted = \Illuminate\Support\Carbon::parse(
                                                $lastSub->submitted_at,
                                            )->format('d/m/Y H:i');

                                            // Règle métier principale :
                                            // Si période active ET statut approuvé → item est NON CONFORME (car période a changé)
                                            if ($item->hasActivePeriode && $lastSub->status === 'approuvé') {
                                                $conformiteStatus = 'non_conforme';
                                                $conformiteLabel = 'Non conforme';
                                                $conformiteIcon = 'ti-circle-x';
                                                $conformiteBadgeClass = 'bg-danger-subtle text-danger';
                                            }
                                            // Sinon, on suit le statut réel de la soumission
                                            else {
                                                switch ($lastSub->status) {
                                                    case 'approuvé':
                                                        $conformiteStatus = 'approuve';
                                                        $conformiteLabel = 'Approuvé';
                                                        $conformiteIcon = 'ti-circle-check';
                                                        $conformiteBadgeClass = 'bg-success-subtle text-success';
                                                        break;
                                                    case 'rejeté':
                                                        $conformiteStatus = 'rejete';
                                                        $conformiteLabel = 'Rejeté';
                                                        $conformiteIcon = 'ti-circle-x';
                                                        $conformiteBadgeClass = 'bg-danger-subtle text-danger';
                                                        break;
                                                    case 'soumis':
                                                        $conformiteStatus = 'en_attente';
                                                        $conformiteLabel = 'En attente';
                                                        $conformiteIcon = 'ti-hourglass-high';
                                                        $conformiteBadgeClass = 'bg-warning-subtle text-warning';
                                                        break;
                                                }
                                            }
                                        } else {
                                            // Pas de soumission
                                            if ($item->hasActivePeriode) {
                                                // Période active sans soumission = NON CONFORME
                                                $conformiteStatus = 'non_conforme';
                                                $conformiteLabel = 'Non conforme';
                                                $conformiteIcon = 'ti-circle-x';
                                                $conformiteBadgeClass = 'bg-danger-subtle text-danger';
                                            } else {
                                                // Pas de période active, pas de soumission = état neutre
                                                $conformiteStatus = 'aucune';
                                                $conformiteLabel = 'Aucune soumission';
                                                $conformiteIcon = 'ti-file-off';
                                                $conformiteBadgeClass = 'bg-light text-muted';
                                            }
                                        }
                                    @endphp

                                    {{-- Affichage unifié du statut --}}
                                    <div class="mb-0">
                                        @if ($lastSub)
                                            {{-- Avec soumission : afficher les détails --}}
                                            <div class="d-flex align-items-center justify-content-between mb-1">
                                                <span class="small text-muted">Dernière soumission</span>
                                                <span class="badge {{ $conformiteBadgeClass }} border">
                                                    <i
                                                        class="ti {{ $conformiteIcon }} me-1"></i>{{ $conformiteLabel }}
                                                </span>
                                            </div>
                                            <div class="small text-muted">{{ $submittedAtFormatted }}</div>
                                        @else
                                            {{-- Sans soumission --}}
                                            @if ($conformiteStatus === 'non_conforme')
                                                <div
                                                    class="p-2 rounded-3 bg-danger bg-opacity-10 border border-danger">
                                                    <div class="d-flex align-items-center gap-2">
                                                        <i class="ti {{ $conformiteIcon }} text-danger"></i>
                                                        <span
                                                            class="small fw-semibold text-danger">{{ $conformiteLabel }}</span>
                                                    </div>
                                                    <div class="small text-muted mt-1">Période active sans soumission
                                                    </div>
                                                </div>
                                            @else
                                                <div class="p-2 rounded-3 bg-light border border-dashed">
                                                    <div class="small text-muted text-center">
                                                        <i
                                                            class="ti {{ $conformiteIcon }} me-1"></i>{{ $conformiteLabel }}
                                                    </div>
                                                </div>
                                            @endif
                                        @endif
                                    </div>
                                </div>

                                {{-- ================= COL 3 : Actions ================= --}}
                                <div class="d-flex flex-column justify-content-between align-items-end gap-2"
                                    style="min-width: 230px;">

                                    {{-- Actions selon periode_state et rôle --}}
                                    <div class="d-flex flex-wrap justify-content-end gap-2 w-100">

                                        {{-- ========== UTILISATEUR STANDARD (non Admin) ========== --}}
                                        @if (auth()->user()->role?->nom !== 'ValideAudit' && auth()->user()->role?->nom !== 'SuperAdmin')
                                            @if ($state === 'active' || $periode)
                                                {{-- Période active OU à venir : utilisateur PEUT agir --}}

                                                @if ($lastSub && $lastSub->status === 'soumis')
                                                    {{-- Soumission en attente : bouton MODIFIER --}}
                                                    <button class="btn btn-sm btn-warning flex-fill"
                                                        data-bs-toggle="modal" data-bs-target="#submitModal"
                                                        wire:click="openSubmitModal('{{ $item->id }}', '{{ $lastSub->id }}')">
                                                        <i class="ti ti-edit me-1"></i>Modifier
                                                    </button>
                                                    <button class="btn btn-sm btn-outline-secondary"
                                                        wire:click="$dispatch('open-submit-modal2', { itemId: '{{ $item->id }}', submissionId: '{{ $lastSub->id }}' })">
                                                        <i class="ti ti-sparkles me-1"></i>Modifier IA
                                                    </button>
                                                @elseif ($lastSub && $lastSub->status === 'rejeté')
                                                    {{-- Soumission rejetée : bouton RESOUMETTRE --}}
                                                    <button class="btn btn-sm btn-danger flex-fill"
                                                        data-bs-toggle="modal" data-bs-target="#submitModal"
                                                        wire:click="openSubmitModal('{{ $item->id }}')">
                                                        <i class="ti ti-refresh me-1"></i>Resoumettre
                                                    </button>
                                                    <button class="btn btn-sm btn-outline-secondary"
                                                        wire:click="$dispatch('open-submit-modal2', { itemId: '{{ $item->id }}' })">
                                                        <i class="ti ti-sparkles me-1"></i>Resoumettre IA
                                                    </button>
                                                @else
                                                    {{--
                                                        Cas :
                                                        - Aucune soumission OU
                                                        - Soumission approuvée (nouvelle période active)
                                                        → bouton SOUMETTRE
                                                    --}}
                                                    <button class="btn btn-sm btn-primary flex-fill"
                                                        data-bs-toggle="modal" data-bs-target="#submitModal"
                                                        wire:click="openSubmitModal('{{ $item->id }}')">
                                                        <i class="ti ti-send me-1"></i>Soumettre
                                                    </button>
                                                    <button class="btn btn-sm btn-outline-secondary"
                                                        wire:click="$dispatch('open-submit-modal2', { itemId: '{{ $item->id }}' })">
                                                        <i class="ti ti-sparkles me-1"></i>Soumettre IA
                                                    </button>
                                                @endif
                                            @else
                                                {{-- Période PAS active ET pas à venir : bouton désactivé --}}
                                                <button class="btn btn-sm btn-secondary flex-fill" disabled>
                                                    <i class="ti ti-lock me-1"></i>
                                                    @if ($state === 'expired')
                                                        Période expirée
                                                    @elseif($state === 'disabled')
                                                        Période clôturée
                                                    @elseif($state === 'none')
                                                        Pas de période
                                                    @else
                                                        En attente de la période de soumission
                                                    @endif
                                                </button>
                                            @endif
                                        @endif

                                        {{-- ========== ADMIN (ValideAudit / SuperAdmin) ========== --}}
                                        @if (auth()->user()->role?->nom === 'ValideAudit' || auth()->user()->role?->SuperAdmin)
                                            {{-- Admin peut TOUJOURS gérer les périodes --}}
                                            <button class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal"
                                                data-bs-target="#periodesModal"
                                                wire:click="$dispatch('open-periode-manager', { id: '{{ $item->id }}' })">
                                                <i class="ti ti-calendar-event me-1"></i>
                                                @if ($state === 'none')
                                                    Définir période
                                                @else
                                                    Gérer période
                                                @endif
                                            </button>
                                            <button class="btn btn-sm btn-outline-info"
                                                wire:click="$dispatch('modal-periode-manager', { id: '{{ $item->id }}' })">
                                                <i class="ti ti-sparkles me-1"></i>Période IA
                                            </button>
                                        @endif

                                        {{-- ========== BOUTON HISTORIQUE (tous) ========== --}}
                                        <button class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal"
                                            data-bs-target="#historyModal"
                                            wire:click="openHistoryModal('{{ $item->id }}')">
                                            <i class="ti ti-history"></i>
                                        </button>
                                    </div>

                                    {{-- Badge en attente --}}
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
                </div>
            @empty
                <div class="col-12">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body text-center py-5">
                            <i class="ti ti-inbox fs-1 text-muted mb-3 d-block"></i>
                            <h5 class="text-muted">Aucun item trouvé</h5>
                            <p class="text-muted small">
                                @if ($filterDomaine)
                                    Aucun item dans ce domaine avec les filtres appliqués.
                                @else
                                    Ajustez vos filtres ou configurez vos items.
                                @endif
                            </p>
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
        {{-- ==================== Modales ==================== --}}
        @include('livewire.settings.modals.submit-modal')
        @include('livewire.settings.modals.history-modal')
        @include('livewire.settings.modals.item-periode-ia')

        @include('livewire.settings.modals.submit2-modal')
        @include('livewire.settings.modals.review-modal')

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

        /* Styles pour les cards de domaines */
        .domaine-card {
            transition: all 0.3s ease;
        }

        .domaine-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, .15) !important;
        }

        .domaine-card.border-primary {
            background: linear-gradient(135deg, rgba(13, 110, 253, 0.05) 0%, rgba(13, 110, 253, 0.1) 100%);
        }
    </style>
</div>
