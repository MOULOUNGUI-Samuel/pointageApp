<div>
    <div>
        <!-- En-tête -->
        <div class="mb-4">
            <div class="d-flex align-items-center">
                <div
                    class="avatar avatar-lg bg-primary bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center me-3">
                    <i class="bi bi-building text-primary fs-3"></i>
                </div>
                <div>
                    <h3 class="fw-bold mb-1">{{ $entreprise->nom_entreprise }}</h3>
                    <p class="text-muted mb-0">
                        <i class="bi bi-gear-fill me-1"></i>
                        Configuration des domaines de conformité
                    </p>
                </div>
            </div>
        </div>

        @if ($successMessage)
        <div class="alert alert-success rounded-pill alert-dismissible fade show">
            <strong class="me-5"><i class="fas fa-exclamation-triangle-fill me-2"></i> {{ $successMessage }}</strong>
            <button type="button" class="btn-close custom-close" data-bs-dismiss="alert" aria-label="Close"><i
                    class="fas fa-xmark"></i></button>
        </div>
    @endif
        @if ($errorMessage)
        <div class="alert alert-danger rounded-pill alert-dismissible fade show">
            <strong class="me-5"><i class="fas fa-exclamation-triangle-fill me-2"></i> {{ $errorMessage }}</strong>
            <button type="button" class="btn-close custom-close" data-bs-dismiss="alert" aria-label="Close"><i
                    class="fas fa-xmark"></i></button>
        </div>
    @endif
        <!-- Formulaires -->
        @if (!$suggestions)
            <!-- FORMULAIRE 1 : Attribution initiale (visible si pas de domaines) -->
            @if ($isInitial)
                <div class="card border-0 shadow-sm" style="border-radius: 15px;">
                    <div class="card-body p-4">
                        <div class="text-center mb-4">
                            <div
                                class="d-inline-flex align-items-center justify-content-center rounded-circle bg-primary bg-opacity-10 p-4 mb-3">
                                <span style="font-size: 75px">🤖</span>
                            </div>
                            <h4 class="fw-bold mb-2">Configuration Initiale Intelligente</h4>
                            <p class="text-muted">Décrivez l'activité de votre entreprise et l'IA sélectionnera
                                automatiquement les domaines de conformité pertinents</p>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold d-flex align-items-center">
                                <i class="bi bi-briefcase-fill text-primary me-2"></i>
                                Description de l'activité
                                <span class="text-danger ms-1">*</span>
                            </label>
                            <textarea wire:model="description_activite" rows="6"
                                class="form-control @error('description_activite') is-invalid @enderror"
                                placeholder="Exemple : Entreprise de développement logiciel spécialisée dans les solutions bancaires, comptant 50 employés. Nous gérons des données sensibles de clients et devons respecter les normes RGPD. Nous avons également besoin de gérer les aspects RH, qualité ISO 9001..."
                                style="border-radius: 10px;"></textarea>
                            @error('description_activite')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">
                                <i class="bi bi-info-circle me-1"></i>
                                Incluez : secteur d'activité, taille, contraintes réglementaires, spécificités...
                            </div>
                        </div>

                        <button wire:click="generer" wire:loading.attr="disabled"  wire:loading.remove
                            class="btn btn-primary btn-lg w-100 fw-bold shadow-sm"
                            style="border-radius: 10px; padding: 15px;">
                            <span wire:loading.remove wire:target="generer">
                                <i class="bi bi-stars me-2"></i>
                                Analyser et Suggérer les Domaines
                            </span>
                            <span wire:loading wire:target="generer">
                                <span class="spinner-border spinner-border-sm me-2"></span>
                                Analyse en cours...
                            </span>
                        </button>
                        
                    </div>
                </div>
            @else
                <!-- FORMULAIRE 2 : Besoins supplémentaires (visible si domaines existent déjà) -->
                <div class="card border-0 shadow-sm"
                    style="border-radius: 15px; border-left: 5px solid #28a745 !important;">
                    <div class="card-body p-4">
                        <div class="alert alert-info border-0 mb-4">
                            <i class="bi bi-info-circle-fill me-2"></i>
                            Cette entreprise a déjà des domaines configurés. Utilisez ce formulaire pour ajouter des
                            domaines supplémentaires.
                        </div>

                        <div class="text-center mb-4">
                            <div
                                class="d-inline-flex align-items-center justify-content-center rounded-circle bg-success bg-opacity-10 p-4 mb-3">
                                <i class="bi bi-plus-circle-fill text-success" style="font-size: 3rem;"></i>
                            </div>
                            <h4 class="fw-bold mb-2">Ajouter des Domaines Supplémentaires</h4>
                            <p class="text-muted">Décrivez vos nouveaux besoins en conformité</p>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold d-flex align-items-center">
                                <i class="bi bi-clipboard-plus text-success me-2"></i>
                                Nouveau besoin de conformité
                                <span class="text-danger ms-1">*</span>
                            </label>
                            <textarea wire:model="nouveau_besoin" rows="5" class="form-control @error('nouveau_besoin') is-invalid @enderror"
                                placeholder="Exemple : Nous venons d'obtenir un contrat avec le secteur de la santé et devons maintenant gérer la conformité HIPAA et les certifications HDS..."
                                style="border-radius: 10px;"></textarea>
                            @error('nouveau_besoin')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <button wire:click="generer" wire:loading.attr="disabled" wire:loading.remove
                            class="btn btn-success btn-lg w-100 fw-bold shadow-sm"
                            style="border-radius: 10px; padding: 15px;">
                            <span wire:loading.remove wire:target="generer">
                                <i class="bi bi-plus-circle-fill me-2"></i>
                                Rechercher des Domaines Complémentaires
                            </span>
                            <span wire:loading wire:target="generer">
                                <span class="spinner-border spinner-border-sm me-2"></span>
                                Recherche en cours...
                            </span>
                        </button>
                    </div>
                </div>
            @endif
            <div class="row text-center">
                <div class="col-12">
                    <!-- Animation de génération -->
                    <div wire:loading wire:target="generer" class="text-center">
                        <div class="card border-0 bg-light">
                            <div class="card-body text-center">
                                {{-- <div class="ai-thinking-animation mb-3">
                                    <div class="ai-brain">
                                        <i class="fas fa-robot text-primary" style="font-size: 3rem;"></i>
                                    </div>
                                </div> --}}
                                <h2 class="fw-bold text-primary mb-2">
                                    <span wire:loading wire:target="generer"
                                        class="d-flex align-items-center justify-content-center">
                                        <span class="spinner-grow spinner-grow-sm me-2"></span>
                                        <span class="spinner-grow spinner-grow-sm me-2"
                                            style="animation-delay: 0.15s;"></span>
                                        <span class="spinner-grow spinner-grow-sm me-2"
                                            style="animation-delay: 0.3s;"></span>
                                        <span class="ms-2">🤖 IA analyse l'activité...</span>
                                    </span>
                                </h2>
                                <div
                                    class="progress-steps d-flex justify-content-between align-items-center">
                                    <div class="progress-step step-1">
                                        <div class="step-icon"><i class="bi bi-search me-2"></i></div>
                                        <div class="step-text">Analyse de l'activité</div>
                                    </div>
                                    <div class="progress-step step-2">
                                        <div class="step-icon"><i class="bi bi-database me-2"></i> </div>
                                        <div class="step-text">Recherche en base de données</div>
                                    </div>
                                    <div class="progress-step step-3">
                                        <div class="step-icon"><i class="bi bi-check-circle me-2"></i></div>
                                        <div class="step-text">Sélection des correspondances</div>
                                    </div>
                                </div>
                                <p class="text-muted mb-0 mt-3">
                                    <small class="text-primary" style="font-size: 15px">Recherche des domaines de conformité pertinents.Cela peut prendre quelques secondes...</small>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <!-- Prévisualisation des Suggestions -->
        @if ($suggestions)
            <div class="card border-0 shadow-sm mb-4" style="border-radius: 15px;">
                <div class="card-header bg-white py-3 border-bottom" style="border-radius: 15px 15px 0 0;">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="mb-1 fw-bold">
                                <i class="bi bi-eye text-primary me-2"></i>
                                Prévisualisation des Suggestions
                            </h5>
                            <p class="text-muted mb-0 small">Vérifiez et ajustez avant validation</p>
                        </div>
                        <button wire:click="annuler" class="btn btn-outline-secondary btn-sm"
                            style="border-radius: 8px;">
                            <i class="bi bi-arrow-clockwise me-1"></i>
                            Nouvelle analyse
                        </button>
                    </div>
                </div>
                <div class="card-body p-4">
                    <!-- Résumé IA -->
                    @if ($resume)
                        <div class="alert alert-light border mb-4">
                            <h6 class="fw-bold mb-2">
                                <i class="bi bi-lightbulb text-warning me-2"></i>
                                Analyse de l'IA
                            </h6>
                            <p class="mb-0">{{ $resume }}</p>
                        </div>
                    @endif

                    <!-- Liste des Domaines -->
                    <div class="accordion" id="domainesAccordion">
                        @foreach ($suggestions as $index => $domaine)
                            @php
                                $domaineSelectionne = $domainesSelectionnes[$domaine['id']]['selectionne'] ?? false;
                            @endphp

                            <div class="accordion-item border-0 shadow-sm mb-3 {{ !$domaineSelectionne ? 'opacity-50' : '' }}"
                                style="border-radius: 15px; overflow: hidden;">
                                <h2 class="accordion-header">
                                    <button class="accordion-button fw-bold {{ $index === 0 ? '' : 'collapsed' }}"
                                        type="button" data-bs-toggle="collapse"
                                        data-bs-target="#domaine{{ $index }}"
                                        style="background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);">
                                        <div class="d-flex align-items-center w-100">
                                            <i class="bi bi-folder2-open me-2 text-primary"></i>
                                            <span class="me-auto">{{ $domaine['nom_domaine'] }}</span>
                                            <span class="badge bg-primary me-2">{{ count($domaine['categories']) }}
                                                catégories</span>
                                            @if ($domaineSelectionne)
                                                <span class="badge bg-success me-2">
                                                    <i class="bi bi-check-circle-fill"></i>
                                                </span>
                                            @else
                                                <span class="badge bg-danger me-2">
                                                    <i class="bi bi-x-circle-fill"></i> Retiré
                                                </span>
                                            @endif
                                        </div>
                                    </button>
                                </h2>
                                <div id="domaine{{ $index }}"
                                    class="accordion-collapse collapse {{ $index === 0 ? 'show' : '' }}"
                                    data-bs-parent="#domainesAccordion">
                                    <div class="accordion-body p-4">
                                        <!-- Actions domaine -->
                                        <div class="d-flex justify-content-between align-items-start mb-3">
                                            <div>
                                                <p class="text-muted mb-2">{{ $domaine['description'] }}</p>
                                                @if (isset($domaine['raison_selection']))
                                                    <div class="alert alert-info py-2 px-3 mb-0">
                                                        <small>
                                                            <i class="bi bi-info-circle me-1"></i>
                                                            <strong>Raison :</strong>
                                                            {{ $domaine['raison_selection'] }}
                                                        </small>
                                                    </div>
                                                @endif
                                            </div>
                                            @if ($domaineSelectionne)
                                                <button wire:click="retirerDomaine('{{ $domaine['id'] }}')"
                                                    class="btn btn-outline-danger btn-sm"
                                                    onclick="return confirm('Retirer ce domaine et toutes ses catégories/items ?')">
                                                    <i class="bi bi-trash me-1"></i>
                                                    Retirer
                                                </button>
                                            @endif
                                        </div>

                                        <!-- Catégories -->
                                        @foreach ($domaine['categories'] as $categorie)
                                            @php
                                                $categorieSelectionnee =
                                                    $domainesSelectionnes[$domaine['id']]['categories'][
                                                        $categorie['id']
                                                    ]['selectionnee'] ?? false;
                                            @endphp

                                            <div class="card border mb-3 {{ !$categorieSelectionnee ? 'opacity-50' : '' }}"
                                                style="border-radius: 10px;">
                                                <div class="card-body">
                                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                                        <div>
                                                            <h6 class="fw-bold mb-1">
                                                                <i class="bi bi-folder text-info me-2"></i>
                                                                {{ $categorie['nom_categorie'] }}
                                                                <span
                                                                    class="badge bg-info ms-2">{{ $categorie['code_categorie'] }}</span>
                                                                @if (!$categorieSelectionnee)
                                                                    <span class="badge bg-danger ms-2">Retirée</span>
                                                                @endif
                                                            </h6>
                                                            <p class="text-muted small mb-0">
                                                                {{ $categorie['description'] }}</p>
                                                        </div>
                                                        @if ($categorieSelectionnee && $domaineSelectionne)
                                                            <button
                                                                wire:click="retirerCategorie('{{ $domaine['id'] }}', '{{ $categorie['id'] }}')"
                                                                class="btn btn-outline-danger btn-sm"
                                                                onclick="return confirm('Retirer cette catégorie et tous ses items ?')">
                                                                <i class="bi bi-x-lg"></i>
                                                            </button>
                                                        @endif
                                                    </div>

                                                    <!-- Items -->
                                                    <div class="mt-3">
                                                        <small class="fw-bold text-muted d-block mb-2">
                                                            Items ({{ count($categorie['items']) }})
                                                        </small>
                                                        <div class="row g-2">
                                                            @foreach ($categorie['items'] as $item)
                                                                @php
                                                                    $itemSelectionne =
                                                                        $domainesSelectionnes[$domaine['id']][
                                                                            'categories'
                                                                        ][$categorie['id']]['items'][$item['id']] ??
                                                                        false;
                                                                @endphp

                                                                <div class="col-md-6">
                                                                    <div
                                                                        class="d-flex align-items-center p-2 border rounded {{ !$itemSelectionne ? 'bg-light opacity-50' : 'bg-white' }}">
                                                                        <div class="flex-grow-1">
                                                                            <div class="d-flex align-items-center">
                                                                                @if ($item['type'] === 'texte')
                                                                                <i class="bi bi-textarea-t text-secondary me-2"></i>
                                                                            
                                                                            @elseif ($item['type'] === 'liste')
                                                                                <i class="bi bi-list-ul text-primary me-2"></i>
                                                                            
                                                                            @elseif ($item['type'] === 'checkbox')
                                                                                <i class="bi bi-check-square text-success me-2"></i>
                                                                            
                                                                            @elseif ($item['type'] === 'file')
                                                                                <i class="bi bi-file-earmark text-warning me-2"></i>
                                                                            @endif
                                                                            
                                                                                <small
                                                                                    class="fw-semibold">{{ $item['nom_item'] }}</small>
                                                                            </div>
                                                                        </div>
                                                                        @if ($itemSelectionne && $categorieSelectionnee && $domaineSelectionne)
                                                                            <button
                                                                                wire:click="retirerItem('{{ $domaine['id'] }}', '{{ $categorie['id'] }}', '{{ $item['id'] }}')"
                                                                                class="btn btn-sm btn-outline-danger py-0 px-1"
                                                                                title="Retirer cet item">
                                                                                <i class="bi bi-x"></i>
                                                                            </button>
                                                                        @else
                                                                            <span class="badge bg-danger">Retiré</span>
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Boutons de validation -->
                    <div class="row g-3 mt-3">
                        <div class="col-md-8">
                            <button wire:click="validerAttribution" wire:loading.attr="disabled"
                                class="btn btn-success btn-lg w-100 fw-bold shadow-sm" style="border-radius: 10px;">
                                <span wire:loading.remove wire:target="validerAttribution">
                                    <i class="bi bi-check-circle-fill me-2"></i>
                                    Valider et Attribuer
                                </span>
                                <span wire:loading wire:target="validerAttribution">
                                    <span class="spinner-border spinner-border-sm me-2"></span>
                                    Enregistrement...
                                </span>
                            </button>
                        </div>
                        <div class="col-md-4">
                            <button wire:click="annuler" class="btn btn-outline-danger btn-lg w-100 fw-bold"
                                style="border-radius: 10px;">
                                <i class="bi bi-x-circle me-2"></i>
                                Annuler
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>

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

    @push('scripts')
        <script>
            document.addEventListener('livewire:initialized', () => {
                Livewire.on('attribution-validee', () => {
                    // Faire un scroll vers le haut après validation
                    window.scrollTo({
                        top: 0,
                        behavior: 'smooth'
                    });
                });
            });
        </script>
    @endpush
</div>
