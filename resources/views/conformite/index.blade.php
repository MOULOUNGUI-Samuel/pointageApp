@extends('layouts.master2')

@section('title', 'Gestion de la Conformité')
@section('content2')
    {{-- Inclure les assets nécessaires --}}
    <x-conformite-assets />

    <div class="container-fluid py-4">
        {{-- En-tête de page --}}
        <div class="row mb-4">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h2 class="mb-1">
                            <i class="bi bi-clipboard-check fs-22  me-2"></i>
                            Gestion de la Conformité : 
                            <i>
                                @if (auth()->user()->role?->nom=='SuperAdmin')
                                    Super Admin
                                @elseif (auth()->user()->role?->nom=='ValideAudit')
                                    Auditeur 
                                @else
                                    Utilisateur opérationnel [ {{auth()->user()->role->nom}} ]
                                @endif
                            </i>
                        </h2>
                        <p class="text-muted mb-0">
                            Gérez vos déclarations de conformité et leur validation
                        </p>
                    </div>

                    {{-- Actions rapides (Super Admin uniquement) --}}
                    @if (auth()->user()->role?->SuperAdmin || auth()->user()->role?->nom=='ValideAudit')
                        <div class="btn-group">
                            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addItemModal">
                                <i class="ti ti-settings fs-22  me-2"></i>Gérer les Items
                            </button>
                            <button class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#wizardModal"
                                style="font-size: 20px">
                                🤖 Configurer entreprise avec IA
                            </button>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Messages flash de session --}}
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show rounded-3 mb-4" role="alert">
                <div class="d-flex align-items-center">
                    <i class="ti ti-check fs-4 me-3"></i>
                    <div>
                        <strong>Succès !</strong>
                        <p class="mb-0">{{ session('success') }}</p>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show rounded-3 mb-4" role="alert">
                <div class="d-flex align-items-center">
                    <i class="ti ti-alert-triangle fs-4 me-3"></i>
                    <div>
                        <strong>Erreur !</strong>
                        <p class="mb-0">{{ session('error') }}</p>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        {{-- Onglets de navigation --}}
        <ul class="nav nav-tabs mb-4" id="complianceTab" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="board-tab" data-bs-toggle="tab" data-bs-target="#board" type="button">
                    <i class="ti ti-dashboard fs-22  me-2"></i>Tableau de Bord
                </button>
            </li>
            @if (auth()->user()->role?->nom === 'ValideAudit' || auth()->user()->role?->SuperAdmin)
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="pending-tab" data-bs-toggle="tab" data-bs-target="#pending" type="button">
                        <i class="ti ti-hourglass fs-22  me-2"></i>
                        En Attente
                        <span class="badge bg-warning ms-2">{{ $pendingCount ?? 0 }}</span>
                    </button>
                </li>
            @endif
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="stats-tab" data-bs-toggle="tab" data-bs-target="#stats-content" type="button"
                    role="tab">
                    <i class="ti ti-chart-pie me-2"></i>Statistiques & Analyses
                </button>
            </li>

            @if (auth()->user()->role?->nom === 'ValideAudit' || auth()->user()->role?->SuperAdmin)
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="config-tab" data-bs-toggle="tab" data-bs-target="#config" type="button">
                        <i class="ti ti-adjustments fs-22  me-2"></i>Configuration
                    </button>
                </li>
            @endif

            
        </ul>

        {{-- Contenu des onglets --}}
        <div class="tab-content" id="complianceTabContent">
            {{-- Onglet Tableau de Bord --}}
            <div class="tab-pane fade show active" id="board" role="tabpanel">
                <livewire:settings.compliance-board :key="'settings-compliance-board-' . (auth()->id() ?? 'guest')" />
            </div>

            {{-- Onglet Configuration (Super Admin) --}}
            @if (auth()->user()->role?->nom === 'ValideAudit' || auth()->user()->role?->SuperAdmin)
                <div class="tab-pane fade" id="config" role="tabpanel">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body">
                            <h5 class="card-title mb-4">
                                <i class="ti ti-wand fs-22  me-2"></i>
                                Assistant de Configuration
                            </h5>
                            <p class="text-muted mb-4">
                                Configurez les domaines, catégories et items assignés à chaque entreprise.
                            </p>
                            <livewire:settings.enterprise-config-wizard />
                        </div>
                    </div>
                </div>
            @endif
            {{-- ==================== Onglet Statistiques ==================== --}}
            <div class="tab-pane fade" id="stats-content" role="tabpanel">
                @livewire('settings.compliance-statistics')
            </div>
            {{-- Onglet Soumissions en Attente (Validateurs) --}}
            @if (auth()->user()->role?->nom === 'ValideAudit' || auth()->user()->role?->SuperAdmin)
                <div class="tab-pane fade" id="pending" role="tabpanel">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body">
                            <h5 class="card-title mb-4">
                                <i class="ti ti-hourglass fs-22  me-2"></i>
                                Soumissions En Attente de Validation
                            </h5>

                            {{-- Liste des soumissions en attente --}}
                            @php
                                $pendingSubmissions = \App\Models\ConformitySubmission::with([
                                    'item:id,nom_item,type',
                                    'entreprise:id,nom_entreprise',
                                    'submittedBy:id,nom,prenom',
                                    'periodeItem:id,debut_periode,fin_periode',
                                ])
                                    ->where('status', 'soumis')
                                    ->latest('submitted_at')
                                    ->paginate(10);
                            @endphp

                            @forelse($pendingSubmissions as $sub)
                                <div class="card mb-3 border hover-item">
                                    <div class="card-body">
                                        <div class="row align-items-center">
                                            <div class="col-md-6">
                                                <h6 class="mb-2">{{ $sub->item->nom_item }}</h6>
                                                <div class="small text-muted">
                                                    <div><i class="ti ti-building me-1"></i>{{ $sub->entreprise->nom_entreprise }}
                                                    </div>
                                                    <div><i class="ti ti-user me-1"></i>{{ $sub->submittedBy->nom }}
                                                        {{ $sub->submittedBy->prenom }}</div>
                                                    <div><i
                                                            class="ti ti-clock me-1"></i>{{ $sub->submitted_at->format('d/m/Y H:i') }}
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                @if ($sub->periode)
                                                    <div class="small">
                                                        <i class="ti ti-calendar me-1"></i>
                                                        Période : {{ $sub->periode->debut_periode->format('d/m/Y') }}
                                                        — {{ $sub->periode->fin_periode->format('d/m/Y') }}
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="col-md-2 text-end">
                                                <a href="{{ route('conformite.review', $sub) }}"
                                                    class="btn btn-sm btn-primary">
                                                    <i class="ti ti-eye me-1"></i>Examiner
                                                </a>

                                                {{-- Vue IA --}}
                                                <a href="{{ route('conformite.reviewIA', ['submission' => $sub]) }}"
                                                    class="btn btn-sm btn-info">
                                                    <i class="ti ti-brain me-1"></i>Réviser avec IA
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="text-center py-5">
                                    <i class="ti ti-inbox fs-1 text-muted mb-3 d-block"></i>
                                    <h6 class="text-muted">Aucune soumission en attente</h6>
                                    <p class="text-muted small">Toutes les soumissions ont été traitées</p>
                                </div>
                            @endforelse

                            <div class="mt-3">
                                {{ $pendingSubmissions->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>

    {{-- Modales --}}
    @if (auth()->user()->role?->SuperAdmin || auth()->user()->role?->nom === 'ValideAudit')
        {{-- Modale de gestion des items --}}
        <livewire:settings.items-manager />
        {{-- Modale wizard de configuration --}}
        <div wire:ignore.self class="modal fade" id="wizardModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-xl modal-dialog-scrollable">
                <div class="modal-content">
                    <div class="modal-header bg-primary">
                        <h5 class="modal-title text-white">Assistant de Configuration</h5>
                        <button type="button" class="btn-close bg-light" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        @livewire('attribution-entreprise', ['entreprise_id' => $entreprise_id])
                    </div>
                </div>
            </div>
        </div>

        {{-- Modale de gestion des périodes --}}
        <div wire:ignore.self class="modal fade" id="periodesModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-scrollable">
                <div class="modal-content">
                    <div class="modal-header bg-primary">
                        <h5 class="modal-title text-white">Gestion des Périodes</h5>
                        <button type="button" class="btn-close bg-light" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <livewire:settings.periodes-manager />
                    </div>
                </div>
            </div>
        </div>
    @endif

    {{-- Modale de révision (pour validateurs) --}}
    @if (auth()->user()->role?->nom === 'ValideAudit' || auth()->user()->role?->SuperAdmin)
        <div wire:ignore.self class="modal fade" id="reviewModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-scrollable">
                <div class="modal-content">
                    {{-- Le contenu sera chargé dynamiquement par Livewire --}}
                </div>
            </div>
        </div>
    @endif
    @include('livewire.settings.modals.review-modal')
    <style>
        .hover-item {
            transition: all 0.2s ease;
        }

        .hover-item:hover {
            background-color: #f8f9fa;
            transform: translateX(4px);
        }
    </style>
@endsection

@push('scripts')
    <script>
        // Initialisation des onglets
        document.addEventListener('DOMContentLoaded', function() {
            // Restaurer l'onglet actif depuis le localStorage
            const activeTab = localStorage.getItem('complianceActiveTab');
            if (activeTab) {
                const tab = document.querySelector(`button[data-bs-target="${activeTab}"]`);
                if (tab) {
                    const bsTab = new bootstrap.Tab(tab);
                    bsTab.show();
                }
            }

            // Sauvegarder l'onglet actif
            document.querySelectorAll('button[data-bs-toggle="tab"]').forEach(button => {
                button.addEventListener('shown.bs.tab', function(e) {
                    localStorage.setItem('complianceActiveTab', e.target.dataset.bsTarget);
                });
            });
        });

        // Écouter les événements Livewire pour la modale de révision
        document.addEventListener('livewire:init', () => {
            // Ouvrir la modale
            Livewire.on('open-review-modal2', (data) => {
                const modalElement = document.getElementById('reviewModal2');
                if (modalElement) {
                    const modal = new bootstrap.Modal(modalElement);
                    modal.show();
                }
            });

            // Fermer la modale
            Livewire.on('close-review-modal', () => {
                const modalElement = document.getElementById('reviewModal2');
                if (modalElement) {
                    const modal = bootstrap.Modal.getInstance(modalElement);
                    if (modal) {
                        modal.hide();
                    }
                }
            });
        });
    </script>
@endpush
