<div class="">
    <div>
        @php use Illuminate\Support\Facades\Storage; @endphp

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

        @if ($submission)
            {{-- ========= EN-TÊTE SOUMISSION ========= --}}
            <div class="card border-0 bg-light mb-4">
                <div class="card-body">
                    <div class="d-flex align-items-start justify-content-between gap-3 flex-wrap">
                        <div>
                            <div class="d-flex align-items-center gap-2 mb-2">
                                <h5 class="mb-0">{{ $submission->item->nom_item }}</h5>
                                <span class="badge bg-secondary text-uppercase">{{ $submission->item->type }}</span>
                            </div>
                            <div class="small text-muted">
                                <div class="mb-1">
                                    <i class="ti ti-building me-1"></i>
                                    {{ $submission->entreprise->nom }}
                                </div>
                                <div class="mb-1">
                                    <i class="ti ti-upload me-1"></i>
                                    Soumis le {{ $submission->submitted_at?->format('d/m/Y à H:i') }}
                                </div>
                                @if ($submission->submitter)
                                    <div class="mb-1">
                                        <i class="ti ti-user me-1"></i>
                                        Par {{ $submission->submitter->nom }} {{ $submission->submitter->prenom }}
                                    </div>
                                @endif
                                @if ($submission->periode)
                                    <div>
                                        <i class="ti ti-calendar me-1"></i>
                                        Période : {{ $submission->periode->debut_periode?->format('d/m/Y') }}
                                        — {{ $submission->periode->fin_periode?->format('d/m/Y') }}
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div>
                            @php $st = $submission->status; @endphp
                            <div
                                class="d-flex align-items-center gap-2 p-3 rounded-3 bg-warning bg-opacity-10 border border-warning">
                                <i class="ti ti-hourglass-high text-warning fs-22"></i>
                                <div>
                                    <div class="fw-bold text-warning">En attente</div>
                                    <div class="small text-muted">Validation requise</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    @if ($submission->item->description)
                        <div class="mt-3 pt-3 border-top">
                            <div class="small text-muted">
                                <i class="ti ti-info-circle me-1"></i>
                                {{ $submission->item->description }}
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            {{-- ========= DONNÉES SOUMISES ========= --}}
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white border-bottom">
                    <h6 class="mb-0"><i class="ti ti-file-text me-2"></i>Données soumises</h6>
                </div>
                <div class="card-body">
                    @forelse($submission->answers as $a)
                        @switch($a->kind)
                            @case('texte')
                                <div class="p-3 rounded-3 border mb-3">
                                    <div class="d-flex align-items-center gap-2 mb-2">
                                        <i class="ti ti-text-size text-primary"></i>
                                        <span class="small fw-semibold text-muted">Texte</span>
                                    </div>
                                    <div class="bg-light p-3 rounded-3" style="white-space: pre-wrap;">{{ $a->value_text }}
                                    </div>
                                </div>
                            @break

                            @case('documents')
                                <div class="p-3 rounded-3 border mb-3">
                                    <div class="d-flex align-items-center gap-2 mb-2">
                                        <i class="ti ti-paperclip text-info"></i>
                                        <span class="small fw-semibold text-muted">Document</span>
                                    </div>
                                    <a class="btn btn-sm btn-outline-info" target="_blank"
                                        href="{{ Storage::disk('public')->url($a->file_path) }}">
                                        <i class="ti ti-download me-1"></i>
                                        Télécharger {{ basename($a->file_path) }}
                                    </a>
                                </div>
                            @break

                            @case('liste')
                                <div class="p-3 rounded-3 border mb-3">
                                    <div class="d-flex align-items-center gap-2 mb-2">
                                        <i class="ti ti-list-check text-success"></i>
                                        <span class="small fw-semibold text-muted">Choix (liste)</span>
                                    </div>
                                    @php
                                        $labels = (array) data_get($a->value_json, 'labels', []);
                                        $values = (array) data_get($a->value_json, 'selected', []);
                                    @endphp
                                    @if (!empty($labels))
                                        <div class="d-flex flex-wrap gap-2">
                                            @foreach ($labels as $lbl)
                                                <span class="badge bg-primary text-light border">
                                                    <i class="ti ti-check me-1"></i>{{ $lbl }}
                                                </span>
                                            @endforeach
                                        </div>
                                    @elseif (!empty($values))
                                        <div class="d-flex flex-wrap gap-2">
                                            @foreach ($values as $val)
                                                <span class="badge bg-primary text-light border">
                                                    <i class="ti ti-check me-1"></i>{{ $val }}
                                                </span>
                                            @endforeach
                                        </div>
                                    @else
                                        <span class="text-muted fst-italic">Aucun choix</span>
                                    @endif
                                </div>
                            @break

                            @case('checkbox')
                                <div class="p-3 rounded-3 border mb-3">
                                    <div class="d-flex align-items-center gap-2 mb-2">
                                        <i class="ti ti-checkbox text-secondary"></i>
                                        <span class="small fw-semibold text-muted">Choix</span>
                                    </div>
                                    @php
                                        $labels = $a->selectedLabels();
                                        $vals = $a->selectedMany();
                                    @endphp
                                    <div class="d-flex flex-wrap gap-2">
                                        @forelse($labels ?: $vals as $lbl)
                                            <span class="badge bg-secondary bg-opacity-10 text-secondary border">
                                                <i class="ti ti-check me-1"></i>{{ $lbl }}
                                            </span>
                                        @empty
                                            <span class="text-muted fst-italic">Aucun choix</span>
                                        @endforelse
                                    </div>
                                </div>
                            @break
                        @endswitch
                        @empty
                            <div class="text-center text-muted py-4">
                                <i class="ti ti-file-off fs-22 mb-2 d-block"></i>
                                Aucune donnée soumise
                            </div>
                        @endforelse
                    </div>
                </div>

                {{-- ========= ANALYSE IA ========= --}}
                <div class="mb-4">
                    <button type="button" class="btn btn-outline-primary w-100" wire:click="requestAiAnalysis"
                        wire:loading.attr="disabled" wire:target="requestAiAnalysis">
                        <span wire:loading.remove wire:target="requestAiAnalysis">
                            <i class="ti ti-brain me-2"></i>
                            Demander une analyse IA
                        </span>
                        <span wire:loading wire:target="requestAiAnalysis">
                            <span class="spinner-border spinner-border-sm me-2"></span>
                            L'IA analyse la soumission...
                        </span>
                    </button>
                </div>

                @if ($showAiAnalysis && !empty($aiAnalysis))
                    <div class="card border-0 shadow-sm mb-4 bg-gradient"
                        style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                        <div class="card-body text-white">
                            <h6 class="mb-3">
                                <i class="ti ti-chart-dots me-2"></i>
                                Analyse IA de la Soumission
                            </h6>

                            {{-- Recommandation --}}
                            @if (isset($aiAnalysis['recommandation']))
                                <div class="mb-3 p-3 bg-white bg-opacity-20 rounded-3">
                                    <div class="d-flex align-items-center justify-content-between">
                                        <div>
                                            <div class="small fw-semibold mb-1">Recommandation :</div>
                                            <div class="h5 mb-0">
                                                @if ($aiAnalysis['recommandation'] === 'approuver')
                                                    <i class="ti ti-circle-check me-2"></i>Approuver
                                                @elseif ($aiAnalysis['recommandation'] === 'rejeter')
                                                    <i class="ti ti-circle-x me-2"></i>Rejeter
                                                @else
                                                    <i class="ti ti-alert-circle me-2"></i>Approuver avec réserve
                                                @endif
                                            </div>
                                        </div>
                                        @if (isset($aiAnalysis['score_global']))
                                            <div class="text-center">
                                                <div class="display-6 fw-bold">{{ $aiAnalysis['score_global'] }}</div>
                                                <div class="small">/100</div>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @endif

                            {{-- Scores détaillés --}}
                            @if (isset($aiAnalysis['scores_details']))
                                <div class="mb-3">
                                    <div class="small fw-semibold mb-2">Scores détaillés :</div>
                                    @foreach ($aiAnalysis['scores_details'] as $critere => $score)
                                        <div class="d-flex align-items-center gap-2 mb-2">
                                            <span class="small text-capitalize"
                                                style="width: 100px;">{{ str_replace('_', ' ', $critere) }}</span>
                                            <div class="progress flex-grow-1" style="height: 8px;">
                                                <div class="progress-bar bg-{{ $score >= 80 ? 'success' : ($score >= 50 ? 'warning' : 'danger') }}"
                                                    style="width: {{ $score }}%"></div>
                                            </div>
                                            <span class="small fw-semibold"
                                                style="width: 40px;">{{ $score }}%</span>
                                        </div>
                                    @endforeach
                                </div>
                            @endif

                            {{-- Points forts et faibles --}}
                            <div class="row g-3 mb-3">
                                @if (!empty($aiAnalysis['points_forts']))
                                    <div class="col-md-6">
                                        <div class="bg-white bg-opacity-20 rounded-3 p-3">
                                            <div class="small fw-semibold mb-2">
                                                <i class="ti ti-thumb-up me-1"></i>Points forts :
                                            </div>
                                            <ul class="small mb-0 ps-3">
                                                @foreach ($aiAnalysis['points_forts'] as $point)
                                                    <li>{{ $point }}</li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    </div>
                                @endif

                                @if (!empty($aiAnalysis['points_faibles']))
                                    <div class="col-md-6">
                                        <div class="bg-white bg-opacity-20 rounded-3 p-3">
                                            <div class="small fw-semibold mb-2">
                                                <i class="ti ti-thumb-down me-1"></i>Points faibles :
                                            </div>
                                            <ul class="small mb-0 ps-3">
                                                @foreach ($aiAnalysis['points_faibles'] as $point)
                                                    <li>{{ $point }}</li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    </div>
                                @endif
                            </div>

                            {{-- Problèmes majeurs --}}
                            @if (!empty($aiAnalysis['problemes_majeurs']))
                                <div class="alert alert-danger mb-3">
                                    <div class="small fw-semibold mb-2">
                                        <i class="ti ti-alert-triangle me-1"></i>Problèmes majeurs :
                                    </div>
                                    <ul class="small mb-0 ps-3">
                                        @foreach ($aiAnalysis['problemes_majeurs'] as $probleme)
                                            <li>{{ $probleme }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            {{-- Résumé --}}
                            @if (isset($aiAnalysis['resume_analyse']))
                                <div class="p-3 bg-white bg-opacity-20 rounded-3">
                                    <div class="small">
                                        <i class="ti ti-notes me-1"></i>
                                        {{ $aiAnalysis['resume_analyse'] }}
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                @endif

                {{-- ========= ACTIONS DE VALIDATION ========= --}}
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white border-bottom">
                        <h6 class="mb-0"><i class="ti ti-edit me-2"></i>Décision de validation</h6>
                    </div>
                    <div class="card-body">
                        {{-- Notes --}}
                        <div class="mb-3">
                            <label class="form-label fw-semibold">
                                <i class="ti ti-message me-1"></i>
                                Notes / Commentaires
                                @if ($showConfirmReject)
                                    <span class="text-danger">*</span>
                                @endif
                            </label>
                            <textarea rows="4" class="form-control @error('notes') is-invalid @enderror"
                                placeholder="Ajoutez vos commentaires, remarques ou raisons de refus..." wire:model.defer="notes"></textarea>
                            @error('notes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">
                                <i class="ti ti-info-circle me-1"></i>
                                Les commentaires sont obligatoires en cas de rejet
                            </div>
                        </div>

                        {{-- Boutons d'assistance IA pour commentaires --}}
                        @if ($showAiAnalysis && !empty($aiAnalysis))
                            <div class="d-flex gap-2 mb-3">
                                <button type="button" class="btn btn-sm btn-outline-success"
                                    wire:click="generateApprovalComment">
                                    <i class="ti ti-sparkles me-1"></i>
                                    Générer commentaire d'approbation
                                </button>
                                <button type="button" class="btn btn-sm btn-outline-danger"
                                    wire:click="generateRejectionComment">
                                    <i class="ti ti-sparkles me-1"></i>
                                    Générer commentaire de rejet
                                </button>
                            </div>
                        @endif

                        {{-- Banners de confirmation --}}
                        @if ($showConfirmApprove)
                            <div class="alert alert-success d-flex align-items-start gap-3 mb-3">
                                <i class="ti ti-alert-circle fs-22"></i>
                                <div class="flex-grow-1">
                                    <div class="fw-bold mb-2">Confirmer l'approbation</div>
                                    <p class="mb-3 small">Vous êtes sur le point d'approuver cette soumission. Cette action
                                        est définitive.</p>
                                    <div class="d-flex gap-2">
                                        <button class="btn btn-success" wire:click="confirmApprove"
                                            wire:loading.attr="disabled" wire:target="confirmApprove">
                                            <span wire:loading.remove wire:target="confirmApprove">
                                                <i class="ti ti-check me-1"></i>Oui, approuver
                                            </span>
                                            <span wire:loading wire:target="confirmApprove">
                                                <span class="spinner-border spinner-border-sm me-1"></span>Traitement…
                                            </span>
                                        </button>
                                        <button class="btn btn-outline-secondary" wire:click="cancelConfirm">
                                            <i class="ti ti-x me-1"></i>Annuler
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @endif

                        @if ($showConfirmReject)
                            <div class="alert alert-danger d-flex align-items-start gap-3 mb-3">
                                <i class="ti ti-alert-triangle fs-22"></i>
                                <div class="flex-grow-1">
                                    <div class="fw-bold mb-2">Confirmer le rejet</div>
                                    <p class="mb-3 small">Vous êtes sur le point de rejeter cette soumission. L'entreprise
                                        devra soumettre une correction.</p>
                                    <div class="d-flex gap-2">
                                        <button class="btn btn-danger" wire:click="confirmReject"
                                            wire:loading.attr="disabled" wire:target="confirmReject">
                                            <span wire:loading.remove wire:target="confirmReject">
                                                <i class="ti ti-x me-1"></i>Oui, rejeter
                                            </span>
                                            <span wire:loading wire:target="confirmReject">
                                                <span class="spinner-border spinner-border-sm me-1"></span>Traitement…
                                            </span>
                                        </button>
                                        <button class="btn btn-outline-secondary" wire:click="cancelConfirm">
                                            <i class="ti ti-x me-1"></i>Annuler
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @endif

                        {{-- Boutons d'action --}}
                        @if (!$showConfirmApprove && !$showConfirmReject)
                            <div class="d-flex justify-content-end gap-2">
                                <button class="btn btn-success" wire:click="prepareApprove" wire:loading.attr="disabled">
                                    <span wire:loading.remove wire:target="prepareApprove">
                                        <i class="ti ti-circle-check me-1"></i>Approuver
                                    </span>
                                    <span wire:loading wire:target="prepareApprove">
                                        <span class="spinner-border spinner-border-sm me-1"></span>Chargement…
                                    </span>
                                </button>
                                <button class="btn btn-danger" wire:click="prepareReject" wire:loading.attr="disabled">
                                    <span wire:loading.remove wire:target="prepareReject">
                                        <i class="ti ti-circle-x me-1"></i>Rejeter
                                    </span>
                                    <span wire:loading wire:target="prepareReject">
                                        <span class="spinner-border spinner-border-sm me-1"></span>Chargement…
                                    </span>
                                </button>
                            </div>
                        @endif
                    </div>
                </div>
            @else
                <div class="text-center py-5">
                    <div class="spinner-border mb-3" role="status"></div>
                    <div class="text-muted">Chargement des détails...</div>
                </div>
            @endif
        </div>

        <script>
            // Écouter l'événement pour fermer la modale
            document.addEventListener('livewire:initialized', () => {
                Livewire.on('close-review-modal', () => {
                    const modal = bootstrap.Modal.getInstance(document.getElementById('reviewModal'));
                    if (modal) {
                        modal.hide();
                    }
                });
            });
        </script>
    </div>
