<div wire:ignore.self class="modal fade" id="openPeriodeModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header bg-primary bg-gradient">
                <div class="d-flex align-items-center gap-3 flex-grow-1">
                    <h5 class="modal-title">
                        <i class="bi bi-calendar-event me-2"></i>Définir Période de Validité
                    </h5>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show rounded-3 d-flex align-items-center">
                        <i class="ti ti-check me-2"></i>
                        <span>{{ session('success') }}</span>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif
                <div>

                    <!-- Header -->
                    <!-- Header -->
                    <div class="mb-4">
                        @if ($item)
                            <div class="d-flex align-items-start">
                                <div class="flex-grow-1">
                                    <h4 class="fw-bold mb-1">{{ $item->nom_item }}</h4>

                                    @if ($item->description)
                                        <p class="text-muted mb-2">{{ $item->description }}</p>
                                    @endif

                                    <div>
                                        @if ($item->categorie?->nom_categorie)
                                            <span class="badge bg-primary me-1">
                                                {{ $item->categorie->nom_categorie }}
                                            </span>
                                        @endif

                                        @if ($item->categorie?->domaine?->nom_domaine)
                                            <span class="badge bg-info">
                                                {{ $item->categorie->domaine->nom_domaine }}
                                            </span>
                                        @endif

                                        @if ($entreprise?->nom)
                                            <span class="badge bg-secondary">
                                                {{ $entreprise->nom }}
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="alert alert-info d-flex align-items-center mb-0">
                                <i class="bi bi-info-circle me-2"></i>
                                <div>
                                    <strong>Aucun item sélectionné.</strong><br>
                                    Cliquez sur le bouton <em>“Période”</em> d’un item pour définir une période de
                                    validité.
                                </div>
                            </div>
                        @endif
                    </div>


                    <!-- Messages -->
                    @if ($successMessage)
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="bi bi-check-circle-fill me-2"></i>
                            {{ $successMessage }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if ($errorMessage)
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="bi bi-exclamation-triangle-fill me-2"></i>
                            {{ $errorMessage }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <!-- Périodes Existantes -->
                    @if ($periodes_existantes->isNotEmpty())
                        <div class="card border-0 shadow-sm mb-4" style="border-radius: 15px;">
                            <div class="card-header bg-light py-3">
                                <h6 class="mb-0 fw-bold">
                                    <i class="bi bi-clock-history me-2"></i>
                                    Périodes Existantes
                                </h6>
                            </div>
                            <div class="card-body p-0">
                                <div class="table-responsive">
                                    <table class="table table-sm mb-0">
                                        <thead>
                                            <tr>
                                                <th>Début</th>
                                                <th>Fin</th>
                                                <th>Statut</th>
                                                <th>Créée par</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($periodes_existantes as $periode)
                                                <tr>
                                                    <td>{{ $periode->debut_periode->format('d/m/Y') }}</td>
                                                    <td>{{ $periode->fin_periode->format('d/m/Y') }}</td>
                                                    <td>
                                                        @if ($periode->is_active)
                                                            <span class="badge bg-success">Active</span>
                                                        @elseif ($periode->statut === '0')
                                                            <span class="badge bg-secondary">Fermée</span>
                                                        @else
                                                            <span class="badge bg-warning">Expirée</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <small>{{ $periode->user_add->name ?? 'Système' }}</small>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Formulaire de Génération -->
                    @if (!$suggestions)
                        <div class="card border-0 shadow-sm" style="border-radius: 15px;">
                            <div class="card-body p-4">
                                <div class="text-center mb-4">
                                    <div
                                        class="d-inline-flex align-items-center justify-content-center rounded-circle bg-primary bg-opacity-10 p-4 mb-3">
                                        <i class="bi bi-calendar-check text-primary" style="font-size: 3rem;"></i>
                                    </div>
                                    <h5 class="fw-bold mb-2">Définir une Période de Validité</h5>
                                    <p class="text-muted">L'IA va suggérer des périodes adaptées à cet item</p>
                                </div>

                                <div class="mb-4">
                                    <label class="form-label fw-bold">
                                        <i class="bi bi-info-circle text-info me-2"></i>
                                        Contexte Supplémentaire (optionnel)
                                    </label>
                                    <textarea wire:model="contexte_supplementaire" rows="3" class="form-control"
                                        placeholder="Ajoutez des informations spécifiques (contraintes réglementaires, besoins particuliers, etc.)"
                                        style="border-radius: 10px;"></textarea>
                                    <div class="form-text">
                                        <i class="bi bi-lightbulb me-1"></i>
                                        Plus vous donnez de contexte, plus les suggestions seront précises
                                    </div>
                                </div>

                                <button wire:click="genererSuggestions" wire:loading.attr="disabled" wire:loading.remove
                                    class="btn btn-primary btn-lg w-100 fw-bold shadow-sm" style="border-radius: 10px;">
                                    <span wire:loading.remove wire:target="genererSuggestions">
                                        <i class="bi bi-stars me-2"></i>
                                        Suggérer des Périodes avec l'IA
                                    </span>
                                    <span wire:loading wire:target="genererSuggestions">
                                        <span class="spinner-border spinner-border-sm me-2"></span>
                                        Génération en cours...
                                    </span>
                                </button>

                                <div class="row text-center">
                                    <div class="col-12">
                                        <!-- Animation de génération -->
                                        <div wire:loading wire:target="genererSuggestions" class="text-center">
                                            <div class="card border-0 bg-light">
                                                <div class="card-body text-center">
                                                    {{-- <div class="ai-thinking-animation mb-3">
                                                        <div class="ai-brain">
                                                            <i class="fas fa-robot text-primary" style="font-size: 3rem;"></i>
                                                        </div>
                                                    </div> --}}
                                                    <h2 class="fw-bold text-primary mb-2">
                                                        <span wire:loading wire:target="genererSuggestions"
                                                            class="d-flex align-items-center justify-content-center">
                                                            <span class="spinner-grow spinner-grow-sm me-2"></span>
                                                            <span class="spinner-grow spinner-grow-sm me-2"
                                                                style="animation-delay: 0.15s;"></span>
                                                            <span class="spinner-grow spinner-grow-sm me-2"
                                                                style="animation-delay: 0.3s;"></span>
                                                            <span class="ms-2">🤖 IA analyse...</span>
                                                        </span>
                                                    </h2>
                                                    <div
                                                        class="progress-steps d-flex justify-content-between align-items-center">
                                                        <div class="progress-step step-1">
                                                            <div class="step-icon"><i class="bi bi-search me-2"></i>
                                                            </div>
                                                            <div class="step-text">Analyse</div>
                                                        </div>
                                                        <div class="progress-step step-2">
                                                            <div class="step-icon"><i class="bi bi-database me-2"></i>
                                                            </div>
                                                            <div class="step-text">Recherche</div>
                                                        </div>
                                                        <div class="progress-step step-3">
                                                            <div class="step-icon"><i
                                                                    class="bi bi-check-circle me-2"></i></div>
                                                            <div class="step-text">Proposition des périodes</div>
                                                        </div>
                                                    </div>
                                                    <p class="text-muted mb-0 mt-3">
                                                        <small class="text-primary" style="font-size: 15px">Cela peut
                                                            prendre quelques secondes...</small>
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Suggestions de l'IA -->
                    @if ($suggestions)
                        <div class="card border-0 shadow-sm mb-4" style="border-radius: 15px;">
                            <div class="card-header bg-white py-3 border-bottom">
                                <div class="d-flex justify-content-between align-items-center">
                                    <h5 class="mb-0 fw-bold">
                                        <i class="bi bi-lightbulb text-warning me-2"></i>
                                        Suggestions de l'IA
                                    </h5>
                                    <button wire:click="annuler" class="btn btn-outline-secondary btn-sm">
                                        <i class="bi bi-arrow-clockwise me-1"></i>
                                        Nouvelle suggestion
                                    </button>
                                </div>
                            </div>
                            <div class="card-body p-4">
                                @if ($notes)
                                    <div class="alert alert-info border-0 mb-4">
                                        <i class="bi bi-info-circle-fill me-2"></i>
                                        <strong>Notes de l'IA :</strong> {{ $notes }}
                                    </div>
                                @endif

                                <div class="row g-3">
                                    @foreach ($suggestions as $index => $suggestion)
                                        <div class="col-md-4">
                                            <div wire:click="selectionnerSuggestion({{ $index }})"
                                                class="card h-100 suggestion-card {{ $suggestion_selectionnee === $index ? 'selected' : '' }}"
                                                style="cursor: pointer; border-radius: 12px; transition: all 0.3s ease;">
                                                <div class="card-body">
                                                    @if ($suggestion['est_recommande'])
                                                        <div class="mb-2">
                                                            <span class="badge bg-success">
                                                                <i class="bi bi-star-fill me-1"></i>
                                                                Recommandé
                                                            </span>
                                                        </div>
                                                    @endif

                                                    <div class="text-center mb-3">
                                                        <div class="display-4 fw-bold text-primary">
                                                            {{ $suggestion['duree_libelle'] }}
                                                        </div>
                                                        <small class="text-muted">{{ $suggestion['duree_jours'] }}
                                                            jours</small>
                                                    </div>

                                                    <div class="mb-3">
                                                        <small class="text-muted d-block mb-1">
                                                            <i class="bi bi-calendar-event me-1"></i>
                                                            Période
                                                        </small>
                                                        <div class="d-flex justify-content-between small">
                                                            <span>{{ \Carbon\Carbon::parse($suggestion['date_debut'])->format('d/m/Y') }}</span>
                                                            <i class="bi bi-arrow-right"></i>
                                                            <span>{{ \Carbon\Carbon::parse($suggestion['date_fin'])->format('d/m/Y') }}</span>
                                                        </div>
                                                    </div>

                                                    <div class="border-top pt-3">
                                                        <small class="text-muted d-block mb-1">
                                                            <i class="bi bi-info-circle me-1"></i>
                                                            Raison
                                                        </small>
                                                        <small>{{ $suggestion['raison'] }}</small>
                                                    </div>

                                                    @if ($suggestion_selectionnee === $index)
                                                        <div class="text-center mt-3">
                                                            <i class="bi bi-check-circle-fill text-success fs-3"></i>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>

                                <div class="mt-4 d-flex gap-3">
                                    <button wire:click="validerPeriode" wire:loading.attr="disabled"
                                        class="btn btn-success btn-lg flex-grow-1 fw-bold"
                                        style="border-radius: 10px;"
                                        {{ $suggestion_selectionnee === null ? 'disabled' : '' }}>
                                        <i class="bi bi-check-circle-fill me-2"></i>
                                        Valider et Créer la Période
                                    </button>
                                    <button wire:click="annuler" class="btn btn-outline-danger btn-lg"
                                        style="border-radius: 10px;">
                                        <i class="bi bi-x-circle me-2"></i>
                                        Annuler
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>

                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="ti ti-x me-1"></i>Fermer
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('livewire:initialized', () => {
            // Écouter l'événement pour fermer la modale après succès
            Livewire.on('periode-creee', () => {
                const modal = bootstrap.Modal.getInstance(document.getElementById('openPeriodeModal'));
                if (modal) {
                    modal.hide();
                }
            });

            // Gérer l'ouverture de la modale via l'événement
            Livewire.on('modal-periode-manager', (event) => {
                // Fermer toutes les autres modales ouvertes
                document.querySelectorAll('.modal.show').forEach(openModal => {
                    const modalInstance = bootstrap.Modal.getInstance(openModal);
                    if (modalInstance && openModal.id !== 'openPeriodeModal') {
                        modalInstance.hide();
                    }
                });

                // Ouvrir cette modale après un court délai
                setTimeout(() => {
                    const modal = new bootstrap.Modal(document.getElementById('openPeriodeModal'));
                    modal.show();
                }, 300);
            });
        });
    </script>

    <style>
            .progress-steps {
                display: flex;
                justify-content: center;
                gap: 40px;
                margin-top: 25px;
            }

            .progress-step {
                text-align: center;
                opacity: 0.25;
                transition: all 0.6s ease;
            }

            @keyframes step-activate {
                0% {
                    opacity: 0.25;
                    transform: scale(0.9) translateY(5px);
                }

                50% {
                    transform: scale(1.15) translateY(-5px);
                }

                100% {
                    opacity: 1;
                    transform: scale(1) translateY(0);
                }
            }

            .progress-step.active {
                opacity: 1;
            }

            .step-icon {
                font-size: 2.5rem;
                margin-bottom: 10px;
            }

            .progress-step.active .step-icon {
                animation: bounce-icon 1s ease infinite;
            }

            @keyframes bounce-icon {

                0%,
                100% {
                    transform: translateY(0);
                }

                50% {
                    transform: translateY(-10px);
                }
            }

            .step-text {
                font-size: 0.9rem;
                font-weight: 600;
                color: #6c757d;
            }

            .progress-step.active .step-text {
                color: #667eea;
            }

            /* Animation progressive des étapes */
            .step-1 {
                animation: step-activate 0.6s ease 0.5s forwards;
            }

            .step-2 {
                animation: step-activate 0.6s ease 2s forwards;
            }

            .step-3 {
                animation: step-activate 0.6s ease 3.5s forwards;
            }

            /* Animation du spinner */
            .spinner-grow {
                animation: spinner-grow 0.75s linear infinite;
            }

            @keyframes spinner-grow {
                0% {
                    transform: scale(0);
                    opacity: 0;
                }

                50% {
                    opacity: 1;
                }

                100% {
                    transform: scale(1);
                    opacity: 0;
                }
            }
        </style>
    </div>
