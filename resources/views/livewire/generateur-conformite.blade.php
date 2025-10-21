<div>
    <!-- Messages -->
    @if (session()->has('success'))
        <div class="alert alert-success rounded-pill alert-dismissible fade show">
            <strong class="me-5"><i class="fas fa-check me-2"></i> {{ session('success') }}</strong>
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

    <!-- Formulaire de génération -->
    @if (!$generatedData)
        <div class="row justify-content-center">
            <div class="col-lg-6">
                <div class="alert alert-info border-0 mb-4" style="border-radius: 5px;">
                    <div class="d-flex align-items-start">
                        <i class="fas fa-info-circle-fill fs-4 me-3 mt-1"></i>
                        <div>
                            <h6 class="fw-bold mb-2" style="font-size: 20px">🎯 Comment ça marche ?</h6>
                            <p class="mb-2 small text-dark" style="font-size: 16px">
                                <strong>1.</strong> Indiquez le nom de votre domaine (ex: Ressources Humaines, Qualité,
                                Finance)<br>
                                <strong>2.</strong> Ajoutez des détails dans la description pour des résultats plus
                                précis<br>
                                <strong>3.</strong> Cliquez sur "Générer" et laissez l'IA créer votre référentiel
                                complet<br>
                                <strong>4.</strong> Modifiez si nécessaire, puis validez pour enregistrer
                            </p>
                            <p class="mb-0 small text-warning" style="font-size: 15px">
                                <i class="fas fa-lightbulb  me-1"></i>
                                <strong>Astuce :</strong> Plus vous donnez de contexte (secteur, taille entreprise,
                                réglementations),
                                plus les résultats seront adaptés à vos besoins !
                            </p>
                        </div>
                    </div>
                </div>
                <div class="card border-0 bg-light mt-4">
                    <div class="card-body p-4">
                        <h6 class="fw-bold mb-3">
                            <i class="fas fa-bookmark-star text-primary me-2"></i>
                            Exemples de domaines populaires
                        </h6>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="p-3 bg-white rounded border">
                                    <strong class="d-block mb-1" style="font-size: 16px">🏢 Ressources Humaines</strong>
                                    <small class="text-muted"  style="font-size: 14px">
                                        "Gestion RH incluant recrutement, formation, paie et évaluation des performances pour entreprise de 150 employés"
                                    </small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="p-3 bg-white rounded border">
                                    <strong class="d-block mb-1" style="font-size: 16px">🔒 Sécurité des Données (RGPD)</strong>
                                    <small class="text-muted"   style="font-size: 14px">
                                        "Conformité RGPD pour entreprise e-commerce collectant et traitant des données personnelles clients"
                                    </small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="p-3 bg-white rounded border">
                                    <strong class="d-block mb-1"  style="font-size: 16px">⚕️ Qualité ISO 9001</strong>
                                    <small class="text-muted"  style="font-size: 14px">
                                        "Système management qualité ISO 9001 pour PME manufacturière de pièces automobiles"
                                    </small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="p-3 bg-white rounded border">
                                    <strong class="d-block mb-1" style="font-size: 16px">🌱 Environnement HSE</strong>
                                    <small class="text-muted"  style="font-size: 14px">
                                        "Hygiène, Sécurité et Environnement pour site industriel chimique avec 200 employés"
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row mt-4">
                    <div class="col-md-4 mt-4">
                        <div class="card border-0 bg-light h-100">
                            <div class="card-body text-center">
                                <i class="fas fa-bolt text-warning fs-1"></i>
                                <h6 class="mt-2 mb-0" style="font-size: 16px">Rapide</h6>
                                <small class="text-muted" style="font-size: 14px">En quelques secondes</small>
                            </div>
                        </div>
                    </div>
                
                    <div class="col-md-4 mt-4">
                        <div class="card border-0 bg-light h-100">
                            <div class="card-body text-center">
                                <i class="fas fa-pen-square text-info fs-1"></i>
                                <h6 class="mt-2 mb-0"  style="font-size: 16px">Personnalisable</h6>
                                <small class="text-muted" style="font-size: 14px">Modifiez à volonté</small>
                            </div>
                        </div>
                    </div>
                
                    <div class="col-md-4 mt-4">
                        <div class="card border-0 bg-light h-100">
                            <div class="card-body text-center">
                                <i class="fas fa-shield-alt text-success fs-1"></i>
                                <h6 class="mt-2 mb-0"  style="font-size: 16px">Conforme</h6>
                                <small class="text-muted" style="font-size: 14px">Structure validée</small>
                            </div>
                        </div>
                    </div>
                </div>
                
            </div>
            <div class="col-lg-6">
                <div class="card border-0 shadow" style="border-radius: 15px;">
                    <div class="card-body p-4">
                        <div class="text-center mb-4">
                            <div
                                class="d-inline-flex align-items-center justify-content-center rounded-circle bg-primary bg-opacity-10 p-4 mb-3">
                                <i class="fas fa-robot text-primary" style="font-size: 3rem;"></i>
                            </div>
                            <h3 class="fw-bold mb-2">Commençons !</h3>
                            <p class="text-muted">Décrivez votre domaine et laissez l'IA faire le reste</p>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold d-flex align-items-center">
                                <i class="fas fa-bookmark-fill text-primary me-2"></i>
                                Nom du Domaine
                                <span class="text-danger ms-1">*</span>
                            </label>
                            <input type="text" wire:model="nom_domaine"
                                class="form-control form-control-lg @error('nom_domaine') is-invalid @enderror shadow"
                                placeholder="Ex: Ressources Humaines, Qualité, Finance..." style="border-radius: 5px;">
                            @error('nom_domaine')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold d-flex align-items-center">
                                <i class="fas fa-card-text text-primary me-2"></i>
                                Description du Domaine
                                <span class="badge bg-secondary ms-2">Optionnel</span>
                            </label>
                            <textarea wire:model="description_domaine" rows="4" class="form-control shadow"
                                placeholder="Décrivez le contexte, les objectifs ou les spécificités de ce domaine..." style="border-radius: 5px;"></textarea>
                            <div class="form-text">
                                <i class="fas fa-info-circle me-1"></i>
                                Plus vous donnez de détails, plus les résultats seront précis
                            </div>
                        </div>

                        <button wire:click="generate" wire:loading.attr="disabled"
                            class="btn btn-primary btn-lg w-100 fw-bold shadow-sm"
                            style="border-radius: 5px; padding: 15px;">
                            <span wire:loading.remove wire:target="generate">
                                <i class="fas fa-wand-magic-sparkles me-2"></i>
                                Générer avec l'IA
                            </span>
                            <span wire:loading wire:target="generate">
                                <span class="spinner-border spinner-border-sm me-2"></span>
                                Génération en cours...
                            </span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Prévisualisation et édition -->
    @if ($editableData)
        <div>
            <!-- Header section -->
            <div class="d-flex justify-content-between align-items-center mb-4 pb-3 border-bottom">
                <div>
                    <h2 class="fw-bold mb-1">
                        <i class="fas fa-eye text-primary me-2"></i>
                        Prévisualisation
                    </h2>
                    <p class="text-muted mb-0">Vérifiez et modifiez avant d'enregistrer</p>
                </div>
                    <div class="card-body p-4" style="width: 900px">
                        <div class="row">
                            <div class="col-6">
                                <div class="">
                                    <label class="form-label fw-semibold">Nom du domaine</label>
                                    <input type="text" wire:model="editableData.nom_domaine"
                                        class="form-control form-control-lg shadow" style="border-radius: 5px;">
                                </div>
                            </div>
                            <div class="col-6">
                                <div>
                                    <label class="form-label fw-semibold">Description</label>
                                    <textarea wire:model="editableData.description" rows="2" class="form-control shadow" style="border-radius: 5px;"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                <button wire:click="regenerate" class="btn btn-outline-secondary" style="border-radius: 5px;">
                    <i class="fas fa-plus me-2"></i>
                    Nouvelle génération
                </button>
            </div>

            <!-- Catégories -->
            <div class="mb-4">
                <div class="d-flex align-items-center mb-3">
                    <h4 class="fw-bold mb-0">
                        <i class="fas fa-grid-3x3-gap-fill text-info me-2"></i>
                        Catégories
                    </h4>
                    <span class="badge bg-info ms-2">{{ count($editableData['categories']) }}</span>
                </div>

                <div class="accordion" id="categoriesAccordion">
                    @foreach ($editableData['categories'] as $catIndex => $categorie)
                        <div class="accordion-item border-0 shadow-sm mb-3"
                            style="border-radius: 5px; overflow: hidden;">
                            <h2 class="accordion-header">
                                <button class="accordion-button fw-bold {{ $catIndex === 0 ? '' : 'collapsed' }} bg-primary"
                                    type="button" data-bs-toggle="collapse"
                                    data-bs-target="#collapse{{ $catIndex }}">
                                    <i class="fas fa-folder2-open me-2 text-primary"></i>
                                    <span
                                        class="me-2">{{ $categorie['nom_categorie'] ?? 'Catégorie ' . ($catIndex + 1) }}</span>
                                    <span
                                        class="badge bg-white rounded-pill text-primary">{{ $categorie['code_categorie'] ?? '' }}</span>
                                    <span class="badge bg-secondary rounded-pill ms-2">{{ count($categorie['items']) }}
                                        items</span>
                                </button>
                            </h2>
                            <div id="collapse{{ $catIndex }}"
                                class="accordion-collapse collapse {{ $catIndex === 0 ? 'show' : '' }}"
                                data-bs-parent="#categoriesAccordion">
                                <div class="accordion-body p-4">
                                    <!-- Bouton supprimer catégorie -->
                                    <div class="text-end mb-3">
                                        <button wire:click="removeCategory({{ $catIndex }})"
                                            class="btn btn-outline-danger btn-sm"
                                            onclick="return confirm('Supprimer cette catégorie ?')">
                                            <i class="fas fa-trash me-1"></i>
                                            Supprimer la catégorie
                                        </button>
                                    </div>

                                    <!-- Champs catégorie -->
                                    <div class="row g-3 mb-4">
                                        <div class="col-md-5">
                                            <label class="form-label fw-semibold">Nom de la catégorie</label>
                                            <input type="text"
                                                wire:model="editableData.categories.{{ $catIndex }}.nom_categorie"
                                                class="form-control shadow" placeholder="Nom de la catégorie"
                                                style="border-radius: 5px;">
                                        </div>
                                        <div class="col-md-2">
                                            <label class="form-label fw-semibold">Code</label>
                                            <input type="text"
                                                wire:model="editableData.categories.{{ $catIndex }}.code_categorie"
                                                class="form-control shadow" placeholder="CODE"
                                                style="border-radius: 5px; text-transform: uppercase;">
                                        </div>
                                        <div class="col-5">
                                            <label class="form-label fw-semibold">Description</label>
                                            <textarea wire:model="editableData.categories.{{ $catIndex }}.description" rows="2" class="form-control shadow"
                                                placeholder="Description de la catégorie" style="border-radius: 5px;"></textarea>
                                        </div>
                                    </div>

                                    <!-- Items -->
                                    <div class="border-top pt-4">
                                        <h6 class="fw-bold mb-3">
                                            <i class="fas fa-tasks text-success me-2"></i>
                                            Items de conformité
                                            <span
                                                class="badge bg-success ms-1">{{ count($categorie['items']) }}</span>
                                        </h6>

                                        <div class="row g-3">
                                            @foreach ($categorie['items'] as $itemIndex => $item)
                                                <div class="col-4">
                                                    <div class="card border-start border-success border-3"
                                                        style="border-radius: 5px;">
                                                        <div class="card-body">
                                                            <div
                                                                class="d-flex justify-content-between align-items-start mb-3">
                                                                <span class="badge bg-success">Item
                                                                    {{ $itemIndex + 1 }}</span>
                                                                <button
                                                                    wire:click="removeItem({{ $catIndex }}, {{ $itemIndex }})"
                                                                    class="btn btn-sm btn-outline-danger"
                                                                    onclick="return confirm('Supprimer cet item ?')">
                                                                    <i class="fas fa-trash text-danger"></i>
                                                                </button>
                                                            </div>

                                                            <div class="row g-2">
                                                                <div class="col-12">
                                                                    <input type="text"
                                                                        wire:model="editableData.categories.{{ $catIndex }}.items.{{ $itemIndex }}.nom_item"
                                                                        class="form-control shadow"
                                                                        placeholder="Nom de l'item"
                                                                        style="border-radius: 3px;">
                                                                </div>
                                                                <div class="col-12">
                                                                    <textarea wire:model="editableData.categories.{{ $catIndex }}.items.{{ $itemIndex }}.description"
                                                                        rows="2" class="form-control shadow" placeholder="Description de l'item"
                                                                        style="border-radius: 3px;"></textarea>
                                                                </div>
                                                                <div class="col-12">
                                                                    <select
                                                                        wire:model="editableData.categories.{{ $catIndex }}.items.{{ $itemIndex }}.type"
                                                                        class="form-select shadow"
                                                                        style="border-radius: 3px;">
                                                                        <option value="texte">📝 Texte</option>
                                                                        <option value="liste">� Liste déroulante
                                                                        </option>
                                                                        <option value="checkbox">☑️ Cases à cocher
                                                                        </option>
                                                                    </select>
                                                                </div>
                                                            </div>

                                                            @if (in_array($item['type'], ['liste', 'checkbox']) && isset($item['options']))
                                                                <div class="mt-3 p-2 bg-light rounded">
                                                                    <small class="fw-semibold d-block mb-2">
                                                                        <i class="fas fa-tags me-1"></i>Options
                                                                        disponibles:
                                                                    </small>
                                                                    <div class="d-flex flex-wrap gap-1">
                                                                        @foreach ($item['options'] as $opt)
                                                                            <span
                                                                                class="badge bg-primary">{{ $opt['label'] }}</span>
                                                                        @endforeach
                                                                    </div>
                                                                </div>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Boutons de validation -->
            <div class="card border-0 shadow-sm"
                style="border-radius: 15px; background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);">
                <div class="card-body p-4">
                    <div class="row g-3">
                        <div class="col"></div>
                        <div class="col-md-4">
                            <button wire:click="validate_and_save" wire:loading.attr="disabled"
                                class="btn btn-success btn-lg w-100 fw-bold shadow-sm" style="border-radius: 5px;">
                                <span wire:loading.remove wire:target="validate_and_save">
                                    <i class="fas fa-save me-1"></i>
                                    Valider et Enregistrer
                                </span>
                                <span wire:loading wire:target="validate_and_save">
                                    <span class="spinner-border spinner-border-sm me-2"></span>
                                    Enregistrement en cours...
                                </span>
                            </button>
                        </div>
                        <div class="col-md-2">
                            <button wire:click="regenerate" class="btn btn-outline-danger btn-lg w-100 fw-bold"
                                style="border-radius: 5px;">
                                <i class="fas fa-x-circle me-2"></i>
                                Annuler
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>

@push('styles')
<style>
    .accordion-button:not(.collapsed) {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
        color: white !important;
    }
    
    .accordion-button:not(.collapsed) .badge {
        background-color: rgba(255, 255, 255, 0.3) !important;
    }
    
    .accordion-button:not(.collapsed) i {
        color: white !important;
    }
    
    .form-control:focus, .form-select:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 0.25rem rgba(102, 126, 234, 0.25);
    }
    
    .btn-primary {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border: none;
    }
    
    .btn-primary:hover {
        background: linear-gradient(135deg, #5568d3 0%, #63408a 100%);
        transform: translateY(-2px);
        transition: all 0.3s ease;
    }
    
    .card {
        transition: all 0.3s ease;
    }
    
    .card:hover {
        transform: translateY(-2px);
    }
</style>
@endpush

@push('scripts')
    <script>
        // Fermer le modal et réinitialiser après succès
        document.addEventListener('livewire:initialized', () => {
            Livewire.on('conformite-saved', () => {
                // Fermer le modal
                const modal = bootstrap.Modal.getInstance(document.getElementById('IAModal'));
                if (modal) {
                    modal.hide();
                }

                // Afficher une notification de succès (optionnel)
                setTimeout(() => {
                    location.reload(); // Recharger la page pour voir les nouvelles données
                }, 1500);
            });
        });
    </script>
@endpush
