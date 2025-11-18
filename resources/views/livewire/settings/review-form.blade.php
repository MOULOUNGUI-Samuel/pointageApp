{{-- Corps --}}
<div class="">
    @php use Illuminate\Support\Facades\Storage; @endphp

    {{-- Flash succès --}}
    

    {{-- ÉTAT : en attente d’un submissionId (au premier affichage avant l’event) --}}
    @if (empty($submission))
        <div class="py-5 text-center text-muted">
            <div class="spinner-border mb-3" role="status" aria-hidden="true"></div>
            <div>Chargement des détails…</div>
        </div>
    @else
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
                                <i class="ti ti-upload me-1"></i>
                                Soumis le {{ $submission->submitted_at?->format('d/m/Y à H:i') }}
                            </div>
                            @if ($submission->submitter)
                                <div class="mb-1">
                                    <i class="ti ti-user me-1"></i>
                                    Par {{ $submission->submitter->nom }} {{ $submission->submitter->prenom }}
                                    @if ($submission->submitter->email)
                                        <span class="text-muted">({{ $submission->submitter->email }})</span>
                                    @endif
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
                        @if ($st === 'approuvé')
                            <div
                                class="d-flex align-items-center gap-2 p-3 rounded-3 bg-success bg-opacity-10 border border-success">
                                <i class="ti ti-circle-check text-success fs-22"></i>
                                <div>
                                    <div class="fw-bold text-success">Approuvé</div>
                                    <div class="small text-muted">{{ $submission->reviewed_at?->format('d/m/Y H:i') }}
                                    </div>
                                </div>
                            </div>
                        @elseif($st === 'rejeté')
                            <div
                                class="d-flex align-items-center gap-2 p-3 rounded-3 bg-danger bg-opacity-10 border border-danger">
                                <i class="ti ti-circle-x text-danger fs-22"></i>
                                <div>
                                    <div class="fw-bold text-danger">Rejeté</div>
                                    <div class="small text-muted">{{ $submission->reviewed_at?->format('d/m/Y H:i') }}
                                    </div>
                                </div>
                            </div>
                        @else
                            <div
                                class="d-flex align-items-center gap-2 p-3 rounded-3 bg-warning bg-opacity-10 border border-warning">
                                <i class="ti ti-hourglass-high text-warning fs-22"></i>
                                <div>
                                    <div class="fw-bold text-warning">En attente</div>
                                    <div class="small text-muted">Validation requise</div>
                                </div>
                            </div>
                        @endif
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

        {{-- ========= HISTORIQUE VALIDATION ========= --}}
        @if (in_array($submission->status, ['approuvé', 'rejeté']))
            <div class="card border-0 bg-light mb-4">
                <div class="card-body">
                    <h6 class="mb-3">
                        <i class="ti ti-clipboard-check me-2"></i>Historique de validation
                    </h6>
                    <div class="d-flex align-items-start gap-3">
                        <div class="flex-shrink-0">
                            <div
                                class="bg-{{ $submission->status === 'approuvé' ? 'success' : 'danger' }} bg-opacity-10 rounded-circle p-2">
                                <i
                                    class="ti ti-user-check text-{{ $submission->status === 'approuvé' ? 'success' : 'danger' }} fs-22"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1">
                            <div class="fw-semibold mb-1">
                                {{ $submission->reviewer->nom ?? '—' }} {{ $submission->reviewer->prenom ?? '' }}
                            </div>
                            <div class="small text-muted mb-2">
                                {{ $submission->reviewed_at?->format('d/m/Y à H:i') }}
                            </div>
                            @if ($submission->reviewer_notes)
                                <div class="p-3 rounded-3 bg-white border">
                                    <div class="small fw-semibold mb-1">Notes :</div>
                                    <div class="small">{{ $submission->reviewer_notes }}</div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @endif

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
                                <div class="bg-light p-3 rounded-3">{{ $a->value_text }}</div>
                            </div>
                        @break

                        @case('documents')
                            <div class="p-3 rounded-3 border mb-3">
                                <div class="d-flex align-items-center gap-2 mb-2">
                                    <i class="ti ti-paperclip text-info"></i>
                                    <span class="small fw-semibold text-muted">Document</span>
                                </div>
                                <a href="#" onclick="ouvrirDocument(event, '{{ asset('storage/' . $a->file_path) }}')"
                                    target="_blank" class="btn btn-outline-info" title="Voir le document">
                                    <i class="ti ti-file-description me-2"></i> Ouvrir le document
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

            {{-- ========= ACTIONS DE VALIDATION ========= --}}
            @if (!$submission->isFinal())
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
                                <i class="ti ti-info-circle me-1"></i>Les commentaires sont obligatoires en cas de rejet
                            </div>
                        </div>

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
                                            wire:loading.attr="disabled">
                                            <span wire:loading.remove wire:target="confirmApprove"><i
                                                    class="ti ti-check me-1"></i>Oui, approuver</span>
                                            <span wire:loading wire:target="confirmApprove"><span
                                                    class="spinner-border spinner-border-sm me-1"></span>Traitement…</span>
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
                                            wire:loading.attr="disabled">
                                            <span wire:loading.remove wire:target="confirmReject"><i
                                                    class="ti ti-x me-1"></i>Oui, rejeter</span>
                                            <span wire:loading wire:target="confirmReject"><span
                                                    class="spinner-border spinner-border-sm me-1"></span>Traitement…</span>
                                        </button>
                                        <button class="btn btn-outline-secondary" wire:click="cancelConfirm">
                                            <i class="ti ti-x me-1"></i>Annuler
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @endif

                        {{-- Boutons d’action --}}
                        @if (!$showConfirmApprove && !$showConfirmReject)
                            <div class="d-flex justify-content-end gap-2">
                                <button class="btn btn-success" wire:click="prepareApprove" wire:loading.attr="disabled">
                                    <span wire:loading.remove wire:target="prepareApprove"><i
                                            class="ti ti-circle-check me-1"></i>Approuver</span>
                                    <span wire:loading wire:target="prepareApprove"><span
                                            class="spinner-border spinner-border-sm me-1"></span>Chargement…</span>
                                </button>
                                <button class="btn btn-danger" wire:click="prepareReject" wire:loading.attr="disabled">
                                    <span wire:loading.remove wire:target="prepareReject"><i
                                            class="ti ti-circle-x me-1"></i>Rejeter</span>
                                    <span wire:loading wire:target="prepareReject"><span
                                            class="spinner-border spinner-border-sm me-1"></span>Chargement…</span>
                                </button>
                            </div>
                        @endif
                    </div>
                </div>
            @endif
        @endif

    </div>
