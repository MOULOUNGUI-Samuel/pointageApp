<div class="">
    <div>
        {{-- Messages d'erreur/succès --}}
        @if ($errorMessage)
            <div class="alert alert-danger alert-dismissible fade show mb-3">
                <i class="ti ti-alert-circle me-2"></i>
                {{ $errorMessage }}
                <button type="button" class="btn-close" wire:click="$set('errorMessage', '')"></button>
            </div>
        @endif
    
        @if ($successMessage)
            <div class="alert alert-success alert-dismissible fade show mb-3">
                <i class="ti ti-check me-2"></i>
                {{ $successMessage }}
                <button type="button" class="btn-close" wire:click="$set('successMessage', '')"></button>
            </div>
        @endif
    
        @if ($item)
            {{-- En-tête de l'item --}}
            <div class="card border-0 bg-light mb-4">
                <div class="card-body">
                    <div class="d-flex align-items-start justify-content-between">
                        <div>
                            <h5 class="mb-2">{{ $item->nom_item }}</h5>
                            <div class="small text-muted">
                                <i class="ti ti-folder me-1"></i>
                                {{ $item->CategorieDomaine?->nom_categorie ?? '—' }}
                                <span class="mx-2">•</span>
                                <i class="ti ti-tag me-1"></i>
                                {{ $item->CategorieDomaine?->Domaine?->nom_domaine ?? '—' }}
                            </div>
                        </div>
                        <span class="badge bg-{{ $item->type === 'texte' ? 'primary' : ($item->type === 'documents' ? 'info' : 'secondary') }} text-uppercase">
                            {{ $item->type }}
                        </span>
                    </div>
                    
                    @if ($item->description)
                        <div class="mt-3 p-3 rounded-3 bg-white border">
                            <div class="small">
                                <i class="ti ti-info-circle text-primary me-1"></i>
                                {{ $item->description }}
                            </div>
                        </div>
                    @endif
    
                    @if ($periode)
                        <div class="mt-3 p-2 rounded-3 bg-success bg-opacity-10 border border-success-subtle">
                            <div class="small text-success">
                                <i class="ti ti-calendar-check me-1"></i>
                                Période valide du {{ $periode->debut_periode->format('d/m/Y') }} 
                                au {{ $periode->fin_periode->format('d/m/Y') }}
                            </div>
                        </div>
                    @endif
                </div>
            </div>
    
            {{-- Bouton d'aide IA --}}
            <div class="mb-4">
                <button 
                    type="button" 
                    class="btn btn-outline-primary w-100"
                    wire:click="requestAiSuggestions"
                    wire:loading.attr="disabled"
                    wire:target="requestAiSuggestions">
                    <span wire:loading.remove wire:target="requestAiSuggestions">
                        <i class="ti ti-bulb me-2"></i>
                        Demander l'aide de l'IA pour remplir ce champ
                    </span>
                    <span wire:loading wire:target="requestAiSuggestions">
                        <span class="spinner-border spinner-border-sm me-2"></span>
                        L'IA analyse vos besoins...
                    </span>
                </button>
            </div>
    
            {{-- Suggestions IA --}}
            @if ($showAiSuggestions && !empty($aiSuggestions))
                <div class="card border-0 shadow mb-4">
                    <div class="card-body text-white ">
                        <h6 class="mb-3">
                            <i class="ti ti-sparkles me-2 text-white"></i>
                            Suggestions de l'IA
                        </h6>
    
                        @if ($item->type === 'texte')
                            <div class="bg-primary rounded-3 p-3 mb-3">
                                <div class="small fw-semibold mb-2">Exemple de texte :</div>
                                <div class="small">{{ $aiSuggestions['exemple_texte'] ?? '' }}</div>
                            </div>
    
                            @if (isset($aiSuggestions['points_cles']))
                                <div class="mb-3">
                                    <div class="small fw-semibold mb-2 text-primary">Points clés à inclure :</div>
                                    <ul class="small mb-0">
                                        @foreach ($aiSuggestions['points_cles'] as $point)
                                            <li class=" text-primary">{{ $point }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
    
                            <button 
                                type="button" 
                                class="btn btn-light btn-sm"
                                wire:click="applySuggestion">
                                <i class="ti ti-copy me-1"></i>
                                Utiliser cet exemple
                            </button>
    
                        @elseif (in_array($item->type, ['liste', 'checkbox']))
                            @if (isset($aiSuggestions['options_recommandees']))
                                <div class="small fw-semibold mb-2">Options recommandées :</div>
                                @foreach ($aiSuggestions['options_recommandees'] as $opt)
                                    <div class="bg-primary rounded-3 p-2 mb-2">
                                        <div class="d-flex align-items-center justify-content-between">
                                            <div class="flex-grow-1">
                                                <div class="fw-semibold">{{ $opt['option'] ?? '' }}</div>
                                                <div class="small">{{ $opt['raison'] ?? '' }}</div>
                                            </div>
                                            <span class="badge bg-{{ $opt['priorite'] === 'haute' ? 'warning' : ($opt['priorite'] === 'moyenne' ? 'info' : 'secondary') }}">
                                                {{ ucfirst($opt['priorite'] ?? 'info') }}
                                            </span>
                                        </div>
                                    </div>
                                @endforeach
                            @endif
    
                        @elseif ($item->type === 'documents')
                            @if (isset($aiSuggestions['document_attendu']))
                                <div class="mb-3">
                                    <div class="small fw-semibold mb-1">Document attendu :</div>
                                    <div class="small">{{ $aiSuggestions['document_attendu'] }}</div>
                                </div>
                            @endif
    
                            @if (isset($aiSuggestions['contenu_essentiel']))
                                <div class="mb-3">
                                    <div class="small fw-semibold mb-2">Contenu essentiel :</div>
                                    <ul class="small mb-0">
                                        @foreach ($aiSuggestions['contenu_essentiel'] as $item)
                                            <li>{{ $item }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                        @endif
    
                        @if (isset($aiSuggestions['conseils']))
                            <div class="mt-3 p-2 bg-primary rounded-3">
                                <div class="small">
                                    <i class="ti ti-info-circle me-1"></i>
                                    {{ $aiSuggestions['conseils'] }}
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            @endif
    
            {{-- Formulaire selon le type --}}
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white border-bottom">
                    <h6 class="mb-0">
                        <i class="ti ti-edit me-2"></i>
                        {{ $submissionId ? 'Modifier la soumission' : 'Remplir les données' }}
                    </h6>
                </div>
                <div class="card-body">
                    @if ($item->type === 'texte')
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Votre réponse <span class="text-danger">*</span></label>
                            <textarea 
                                class="form-control @error('textValue') is-invalid @enderror" 
                                rows="8"
                                wire:model.defer="textValue"
                                placeholder="Saisissez votre réponse ici..."></textarea>
                            @error('textValue')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">
                                <i class="ti ti-info-circle me-1"></i>
                                Minimum 10 caractères requis
                            </div>
                        </div>
    
                    @elseif ($item->type === 'documents')
                        {{-- Si modification, afficher le fichier existant --}}
                        @if ($submission && $submission->answers->where('kind', 'documents')->first())
                            @php $existingFile = $submission->answers->where('kind', 'documents')->first(); @endphp
                            <div class="alert alert-info mb-3">
                                <i class="ti ti-file-check me-2"></i>
                                Fichier actuel : 
                                <a href="{{ Storage::disk('public')->url($existingFile->file_path) }}" target="_blank" class="alert-link">
                                    {{ basename($existingFile->file_path) }}
                                </a>
                            </div>
                        @endif
    
                        <div class="mb-3">
                            <label class="form-label fw-semibold">
                                {{ $submissionId ? 'Remplacer le fichier (optionnel)' : 'Télécharger le document' }}
                                @if (!$submissionId)<span class="text-danger">*</span>@endif
                            </label>
                            <input 
                                type="file" 
                                class="form-control @error('uploadedFile') is-invalid @enderror"
                                wire:model="uploadedFile">
                            @error('uploadedFile')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">
                                <i class="ti ti-info-circle me-1"></i>
                                Taille maximale : 10 MB
                            </div>
                        </div>
    
                        @if ($uploadedFile)
                            <div class="alert alert-success">
                                <i class="ti ti-check me-2"></i>
                                Fichier sélectionné : {{ $uploadedFile->getClientOriginalName() }}
                            </div>
                        @endif
    
                    @elseif (in_array($item->type, ['liste', 'checkbox']))
                        <div class="mb-3">
                            <label class="form-label fw-semibold">
                                {{ $item->type === 'liste' ? 'Sélectionnez les options' : 'Cochez les cases correspondantes' }}
                                <span class="text-danger">*</span>
                            </label>
                            
                            @foreach ($item->options as $option)
                                <div class="form-check mb-2">
                                    <input 
                                        class="form-check-input" 
                                        type="checkbox" 
                                        id="option-{{ $option->id }}"
                                        wire:model.defer="selectedOptions.{{ $option->value }}">
                                    <label class="form-check-label" for="option-{{ $option->id }}">
                                        {{ $option->label }}
                                    </label>
                                </div>
                            @endforeach
    
                            @error('selectedOptions')
                                <div class="text-danger small mt-2">{{ $message }}</div>
                            @enderror
                        </div>
                    @endif
                </div>
            </div>
    
            {{-- Analyse pré-soumission --}}
            @if (!$showConfirmation)
                <div class="d-flex gap-2 mb-3">
                    <button 
                        type="button" 
                        class="btn btn-outline-info flex-fill"
                        wire:click="analyzeBeforeSubmit"
                        wire:loading.attr="disabled"
                        wire:target="analyzeBeforeSubmit">
                        <span wire:loading.remove wire:target="analyzeBeforeSubmit">
                            <i class="ti ti-search me-2"></i>
                            Analyser avec l'IA
                        </span>
                        <span wire:loading wire:target="analyzeBeforeSubmit">
                            <span class="spinner-border spinner-border-sm me-2"></span>
                            Analyse en cours...
                        </span>
                    </button>
    
                    <button 
                        type="button" 
                        class="btn btn-primary flex-fill"
                        wire:click="prepareSubmit"
                        wire:loading.attr="disabled">
                        <span wire:loading.remove>
                            <i class="ti ti-send me-2"></i>
                            {{ $submissionId ? 'Modifier la soumission' : 'Soumettre' }}
                        </span>
                        <span wire:loading>
                            <span class="spinner-border spinner-border-sm me-2"></span>
                            Préparation...
                        </span>
                    </button>
                </div>
            @endif
    
            {{-- Résultats de l'analyse --}}
            @if ($showPreSubmitAnalysis && !empty($analysisResults))
                <div class="card border-0 shadow-sm mb-3">
                    <div class="card-header bg-info bg-opacity-10 border-bottom">
                        <h6 class="mb-0 text-info">
                            <i class="ti ti-chart-dots me-2"></i>
                            Analyse de votre soumission
                        </h6>
                    </div>
                    <div class="card-body">
                        @if (isset($analysisResults['score_qualite']))
                            <div class="mb-3">
                                <div class="d-flex align-items-center justify-content-between mb-2">
                                    <span class="small fw-semibold">Score de qualité</span>
                                    <span class="badge bg-{{ $analysisResults['score_qualite'] >= 80 ? 'success' : ($analysisResults['score_qualite'] >= 50 ? 'warning' : 'danger') }}">
                                        {{ $analysisResults['score_qualite'] }}/100
                                    </span>
                                </div>
                                <div class="progress" style="height: 8px;">
                                    <div class="progress-bar bg-{{ $analysisResults['score_qualite'] >= 80 ? 'success' : ($analysisResults['score_qualite'] >= 50 ? 'warning' : 'danger') }}" 
                                         style="width: {{ $analysisResults['score_qualite'] }}%"></div>
                                </div>
                            </div>
                        @endif
    
                        @if (!empty($analysisResults['problemes']))
                            <div class="mb-3">
                                <div class="small fw-semibold mb-2">Problèmes détectés :</div>
                                @foreach ($analysisResults['problemes'] as $probleme)
                                    <div class="alert alert-{{ $probleme['severite'] === 'critique' ? 'danger' : ($probleme['severite'] === 'warning' ? 'warning' : 'info') }} py-2 mb-2">
                                        <i class="ti ti-alert-{{ $probleme['severite'] === 'critique' ? 'triangle' : 'circle' }} me-1"></i>
                                        {{ $probleme['message'] }}
                                    </div>
                                @endforeach
                            </div>
                        @endif
    
                        @if (!empty($analysisResults['suggestions']))
                            <div class="mb-3">
                                <div class="small fw-semibold mb-2">Suggestions d'amélioration :</div>
                                <ul class="small mb-0">
                                    @foreach ($analysisResults['suggestions'] as $suggestion)
                                        <li>{{ $suggestion }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
    
                        @if (isset($analysisResults['resume']))
                            <div class="p-3 rounded-3 bg-light border">
                                <div class="small">{{ $analysisResults['resume'] }}</div>
                            </div>
                        @endif
                    </div>
                </div>
            @endif
    
            {{-- Confirmation finale --}}
            @if ($showConfirmation)
                <div class="card border-0 shadow-sm bg-warning bg-opacity-10 mb-3">
                    <div class="card-body">
                        <div class="d-flex align-items-start gap-3">
                            <i class="ti ti-alert-triangle text-warning fs-22"></i>
                            <div class="flex-grow-1">
                                <h6 class="mb-2">Confirmer la soumission</h6>
                                <p class="small mb-3">
                                    Êtes-vous sûr de vouloir {{ $submissionId ? 'modifier' : 'soumettre' }} ces données ? 
                                    {{ $submissionId ? 'La version précédente sera remplacée.' : 'Elles seront envoyées pour validation.' }}
                                </p>
                                <div class="d-flex gap-2">
                                    <button 
                                        type="button" 
                                        class="btn btn-success"
                                        wire:click="confirmSubmit"
                                        wire:loading.attr="disabled"
                                        wire:target="confirmSubmit">
                                        <span wire:loading.remove wire:target="confirmSubmit">
                                            <i class="ti ti-check me-1"></i>
                                            Oui, {{ $submissionId ? 'modifier' : 'soumettre' }}
                                        </span>
                                        <span wire:loading wire:target="confirmSubmit">
                                            <span class="spinner-border spinner-border-sm me-1"></span>
                                            Traitement...
                                        </span>
                                    </button>
                                    <button 
                                        type="button" 
                                        class="btn btn-outline-secondary"
                                        wire:click="cancelSubmit">
                                        <i class="ti ti-x me-1"></i>
                                        Annuler
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        @else
            <div class="text-center py-5">
                <div class="spinner-border mb-3" role="status"></div>
                <div class="text-muted">Chargement...</div>
            </div>
        @endif
    </div>
    
    <script>
        // Écouter l'événement pour fermer la modale
        document.addEventListener('livewire:initialized', () => {
            Livewire.on('close-submit-modal', () => {
                const modal = bootstrap.Modal.getInstance(document.getElementById('submitModal2'));
                if (modal) {
                    modal.hide();
                }
            });
        });
    </script>
</div>
