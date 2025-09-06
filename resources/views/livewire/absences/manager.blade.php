<div class="modal fade" id="absenceModal" tabindex="-1" role="dialog" aria-labelledby="absenceLabel" aria-hidden="true"
    wire:ignore.self>
    <div class="modal-dialog modal-fullscreen p-3" role="document">
        <div class="modal-content" style="background-color: rgb(240, 243, 243)">
            <div class="modal-header" style="background-color:white">
                <h5 class="modal-title" id="absenceLabel">
                    Gestion des demandes
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
            </div>

            <div class="row g-4 mt-3 mx-3">
                <style>
                    .accordion-item {
                        border: 1px solid #eef0f3;
                    }

                    .accordion-button {
                        border-radius: .75rem !important;
                    }

                    .card {
                        border-radius: .75rem;
                    }

                    .badge.rounded-pill {
                        padding: .45rem .65rem;
                        font-weight: 600;
                    }

                    #absSearch::placeholder {
                        color: #9aa0a6;
                    }

                    .form-select,
                    .form-control {
                        border-radius: .6rem;
                    }
                </style>
                <style>
                    #absCountHint-{{ $forCompanyId }} {
                        font-size: .85rem;
                    }
                </style>

                {{-- GAUCHE : liste --}}
                <div class="col-lg-6 border-end">
                    <h4 class="mb-3">Demandes existantes</h4>
                    {{-- Filtres --}}
                    {{-- Filtres haut (si pas déjà faits) --}}
                    <div class="row g-2 mb-3">
                        <div class="col-md-5">
                            <input id="absSearch" type="text" class="form-control shadow"
                                placeholder="Recherche (type, motif, justification, retour)…">
                        </div>

                        <div class="col-md-3">
                            <select id="absStatus" class="form-select shadow">
                                <option value="" selected>Tous les statuts</option>
                                <option value="brouillon">Brouillon</option>
                                <option value="soumis">Soumis</option>
                                <option value="approuvé">Approuvé</option>
                                <option value="rejeté">Rejeté</option>
                            </select>
                        </div>

                        <div class="col-md-2">
                            <input id="absFrom" type="date" class="form-control shadow" placeholder="Du">
                        </div>
                        <div class="col-md-2">
                            <input id="absTo" type="date" class="form-control shadow" placeholder="Au">
                        </div>
                    </div>


                    @if (session('success'))
                        <div class="alert alert-success rounded-pill alert-dismissible fade show">
                            <strong class="me-5"><i class="fas fa-check me-2"></i> {{ session('success') }}</strong>
                            <button type="button" class="btn-close custom-close" data-bs-dismiss="alert"
                                aria-label="Close"><i class="fas fa-xmark"></i></button>
                        </div>
                    @endif
                    @if (session('error'))
                        <div class="alert alert-danger rounded-pill alert-dismissible fade show">
                            <strong class="me-5">
                                <i class="fas fa-exclamation-triangle me-2"></i>
                                {{ session('error') }}</strong>
                            <button type="button" class="btn-close custom-close" data-bs-dismiss="alert"
                                aria-label="Close"><i class="fas fa-xmark"></i></button>
                        </div>
                    @endif
                    <div class="d-flex align-items-center gap-2 mb-2">
                        <span id="absCount-{{ $forCompanyId }}" class="badge bg-primary" style="display:none;">0
                            résultat</span>
                        <small id="absCountHint-{{ $forCompanyId }}" class="text-primary"></small>
                    </div>

                    <div class="list-group">
                        <div class="accordion" id="absenceAcc-{{ $forCompanyId }}">
                            @forelse ($items as $a)
                                @php
                                    $isOpen = data_get($openAcc, $a->id, false);
                                    $isApproved = $a->status === 'approuvé';
                                    $isSubmitted = $a->status === 'soumis';
                                    $isrejete = $a->status === 'rejeté';
                                    $isDraft = $a->status === 'brouillon';
                                    $alreadyReturned = !is_null($a->return_confirmed_at);
                                    $isLate = \Carbon\Carbon::now()->gt($a->end_datetime);
                                    $durationHours =
                                        $a->start_datetime && $a->end_datetime
                                            ? $a->start_datetime->diffInHours($a->end_datetime)
                                            : null;
                                @endphp
                                @php
                                    $searchBlob = trim(
                                        implode(' ', [$a->type, $a->reason, $a->justification, $a->return_notes]),
                                    );
                                @endphp

                                <div class="accordion-item shadow-sm rounded-3 mb-2"
                                    wire:key="absence-{{ $a->id }}" data-status="{{ $a->status }}"
                                    data-start="{{ $a->start_datetime?->format('Y-m-d\TH:i') }}"
                                    data-end="{{ $a->end_datetime?->format('Y-m-d\TH:i') }}"
                                    data-text="{{ Str::of($searchBlob)->lower()->squish() }}">
                                    <h2 class="accordion-header" id="heading-{{ $a->id }}">
                                        <button type="button"
                                            class="accordion-button {{ $isOpen ? '' : 'collapsed' }}"
                                            wire:click="toggleAccordion('{{ $a->id }}')">
                                            <div class="d-flex justify-content-between w-100 align-items-center">
                                                <div class="d-flex flex-column">
                                                    <span class="fw-semibold text-capitalize">
                                                        {{ str_replace('_', ' ', $a->type) }} [ {{ $a->code_demande }} ]
                                                    </span>
                                                    <small class="text-muted">
                                                        {{ $a->start_datetime?->format('d/m/Y H:i') }} →
                                                        {{ $a->end_datetime?->format('d/m/Y H:i') }}
                                                        @if ($durationHours)
                                                            <span class="ms-2">· {{ $durationHours }}h</span>
                                                        @endif
                                                    </small>
                                                    @if ($a->reason)
                                                        <small class="text-muted mt-1 d-inline-block text-truncate"
                                                            style="max-width: 520px;">
                                                            <i class="fas fa-align-left me-1"></i>{{ $a->reason }}
                                                        </small>
                                                    @endif
                                                </div>
                                                <div class="d-flex align-items-center gap-2">
                                                    @if ($a->attachment_path)
                                                        <i class="fas fa-paperclip text-muted" title="Pièce jointe"></i>
                                                    @endif
                                                    @if ($alreadyReturned)
                                                        <span class="badge rounded-pill bg-dark">Retour confirmé</span>
                                                    @endif
                                                    <span
                                                        class="badge rounded-pill bg-{{ ['brouillon' => 'secondary', 'soumis' => 'warning', 'approuvé' => 'success', 'rejeté' => 'danger'][$a->status] ?? 'secondary' }}">
                                                        {{ ucfirst($a->status) }}
                                                    </span>
                                                </div>
                                            </div>
                                        </button>
                                    </h2>

                                    <div id="collapse-{{ $a->id }}"
                                        class="accordion-collapse collapse {{ $isOpen ? 'show' : '' }}"
                                        aria-labelledby="heading-{{ $a->id }}">
                                        <div class="accordion-body">
                                            {{-- ====== META GRID ====== --}}
                                            <div class="row g-3 mb-3">
                                                <div class="col-md-6">
                                                    <div class="card h-100 border-0 shadow-sm">
                                                        <div class="card-body">
                                                            <div class="fw-semibold text-muted mb-2">Détails</div>
                                                            <div class="mb-1">
                                                                <i class="fas fa-user me-2"></i>
                                                                <strong>Employé :</strong>
                                                                {{ $a->user?->nom }} {{ $a->user?->prenom }}
                                                            </div>
                                                            <div class="mb-1">
                                                                <i class="fas fa-briefcase me-2"></i>
                                                                <strong>Fonction :</strong>
                                                                {{ $a->user?->fonction ?? '—' }}
                                                            </div>


                                                            <div class="mb-1"><i
                                                                    class="fas fa-calendar-day me-2"></i><strong>Début
                                                                    :</strong>
                                                                {{ $a->start_datetime?->format('d/m/Y H:i') }}</div>
                                                            <div class="mb-1"><i
                                                                    class="fas fa-calendar-check me-2"></i><strong>Fin
                                                                    :</strong>
                                                                {{ $a->end_datetime?->format('d/m/Y H:i') }}</div>
                                                            @if ($durationHours)
                                                                <div class="mb-1"><i
                                                                        class="fas fa-hourglass-half me-2"></i><strong>Durée
                                                                        :</strong> {{ $durationHours }} h</div>
                                                            @endif
                                                            @if ($isApproved)
                                                                <div class="mt-2 small text-success"><i
                                                                        class="fas fa-check-circle me-1"></i>Approuvée
                                                                    le
                                                                    {{ $a->approved_at?->format('d/m/Y H:i') }}</div>
                                                            @endif
                                                            @if ($a->justification && $a->status === 'rejeté')
                                                                <div class="mt-2 small text-danger"><i
                                                                        class="fas fa-ban me-1"></i>Rejeté —
                                                                    {{ \Illuminate\Support\Str::limit($a->justification, 140) }}
                                                                </div>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="card h-100 border-0 shadow-sm">
                                                        <div class="card-body">
                                                            <div class="fw-semibold text-muted mb-2">Pièces &
                                                                décisions</div>

                                                            {{-- Motif (complet) --}}
                                                            <div class="mb-2">
                                                                <div class="text-muted small mb-1">Motif</div>
                                                                <div class="bg-light rounded p-2">
                                                                    {{ $a->reason ?: '—' }}</div>
                                                            </div>

                                                            {{-- Pièce jointe de la demande --}}
                                                            <div class="mb-2">
                                                                <div class="text-muted small mb-1">Pièce jointe</div>
                                                                @if ($a->attachment_path)
                                                                    <a class="btn btn-sm btn-outline-secondary"
                                                                        target="_blank"
                                                                        href="{{ asset('storage/' . $a->attachment_path) }}"
                                                                        onclick="ouvrirDocument(event, '{{ asset('storage/' . $a->attachment_path) }}')">
                                                                        <i class="fas fa-paperclip me-2"></i>Voir la
                                                                        pièce jointe
                                                                    </a>
                                                                @else
                                                                    <span class="text-muted">Aucune</span>
                                                                @endif
                                                            </div>

                                                            {{-- Statut de retour --}}
                                                            <div class="mb-2">
                                                                <div class="text-muted small mb-1">Retour</div>
                                                                @if ($alreadyReturned)
                                                                    <div class="d-flex flex-column gap-1">
                                                                        <span
                                                                            class="badge bg-{{ $a->returned_on_time ? 'success' : 'danger' }} w-auto">
                                                                            {{ $a->returned_on_time ? 'À l’heure' : 'En retard' }}
                                                                        </span>
                                                                        <small class="text-muted">Confirmé le
                                                                            {{ $a->return_confirmed_at?->format('d/m/Y H:i') }}</small>
                                                                        @if ($a->return_notes)
                                                                            <div class="bg-light rounded p-2 small">
                                                                                {{ $a->return_notes }}</div>
                                                                        @endif
                                                                        @if ($a->return_attachment_path)
                                                                            <a class="btn btn-sm btn-outline-secondary"
                                                                                target="_blank"
                                                                                href="{{ Storage::disk('public')->url($a->return_attachment_path) }}">
                                                                                <i
                                                                                    class="fas fa-paperclip me-2"></i>Pièce
                                                                                jointe
                                                                                retour
                                                                            </a>
                                                                        @endif
                                                                    </div>
                                                                @else
                                                                    <span class="text-muted">Non confirmé</span>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            {{-- ====== ACTIONS (conditionnelles) ====== --}}
                                            <div class="d-flex flex-wrap gap-2 mb-3"
                                                wire:key="actions-{{ $a->id }}">
                                                @if (($isDraft || $isrejete) && !$isApproved && !$alreadyReturned)
                                                    <button type="button" class="btn btn-sm btn-outline-primary"
                                                        wire:click="openForm2('{{ $a->id }}')">
                                                        <i class="fas fa-edit me-1"></i>Éditer
                                                    </button>
                                                    <button type="button" class="btn btn-sm btn-outline-info"
                                                        wire:click="submit('{{ $a->id }}')">
                                                        <i class="fas fa-paper-plane me-1"></i>Soumettre
                                                    </button>
                                                    @if ($isDraft)
                                                        <button type="button" class="btn btn-sm btn-outline-danger"
                                                            wire:click="showDelete('{{ $a->id }}')">
                                                            <i class="fas fa-trash me-1"></i>Supprimer
                                                        </button>
                                                    @endif
                                                @elseif($isSubmitted)
                                                    <button type="button" class="btn btn-sm btn-outline-success"
                                                        wire:click="approve('{{ $a->id }}')">
                                                        <i class="fas fa-check me-1"></i>Approuver
                                                    </button>
                                                    <button type="button" class="btn btn-sm btn-outline-danger"
                                                        wire:click="showRejectBox('{{ $a->id }}')">
                                                        <i class="fas fa-times me-1"></i>Rejeter
                                                    </button>
                                                @elseif($isApproved)
                                                    @if (!$alreadyReturned)
                                                        <button type="button" class="btn btn-sm btn-outline-dark"
                                                            wire:click="showReturnBox('{{ $a->id }}')">
                                                            <i class="fas fa-undo me-1"></i>Confirmer le retour
                                                        </button>
                                                    @endif
                                                @endif
                                            </div>

                                            {{-- CONFIRMATION SUPPRESSION (exclusive) --}}
                                            @if (data_get($confirmDelete, $a->id, false))
                                                <div
                                                    class="alert alert-warning d-flex justify-content-between align-items-center py-2 px-3">
                                                    <div><strong>Confirmer la suppression</strong> — Cette action est
                                                        irréversible.
                                                    </div>
                                                    <div class="d-flex gap-2">
                                                        <button type="button" class="btn btn-sm btn-secondary"
                                                            wire:click="cancelDelete('{{ $a->id }}')">Annuler</button>
                                                        <button type="button" class="btn btn-sm btn-danger"
                                                            wire:click="delete('{{ $a->id }}')">Supprimer</button>
                                                    </div>
                                                </div>
                                            @endif

                                            {{-- REJET (exclusive) --}}
                                            @if (data_get($showReject, $a->id, false))
                                                <div class="card border-danger">
                                                    <div class="card-body">
                                                        <label class="form-label">Justification du rejet</label>
                                                        <textarea rows="4" class="form-control @error('justif.' . $a->id) is-invalid @enderror shadow"
                                                            wire:model.defer="justif.{{ $a->id }}" placeholder="Expliquez la raison du rejet…"></textarea>
                                                        @error('justif.' . $a->id)
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror

                                                        <div class="d-flex gap-2 mt-3">
                                                            <button type="button" class="btn btn-warning"
                                                                wire:click="cancelReject('{{ $a->id }}')">Annuler</button>
                                                            <button type="button" class="btn btn-danger"
                                                                wire:click="confirmReject('{{ $a->id }}')">Confirmer
                                                                le
                                                                rejet</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif

                                            {{-- RETOUR (exclusive) --}}
                                            @if ($returnTargetId === $a->id)
                                                <div class="card mt-3 border-dark">
                                                    <div class="card-body">
                                                        <div
                                                            class="d-flex align-items-center justify-content-between mb-2">
                                                            <div class="fw-semibold">
                                                                Confirmer le retour
                                                                @if ($isLate)
                                                                    <span class="badge bg-danger ms-2">En
                                                                        retard</span>
                                                                @else
                                                                    <span class="badge bg-success ms-2">Période
                                                                        respectée</span>
                                                                @endif
                                                            </div>
                                                            <small class="text-muted">Date prévue :
                                                                {{ $a->end_datetime?->format('d/m/Y H:i') }}</small>
                                                        </div>

                                                        @if ($isLate)
                                                            <div class="mb-3">
                                                                <label class="form-label">Description
                                                                    (obligatoire)
                                                                </label>
                                                                <textarea rows="3" class="form-control @error('returnNotes') is-invalid @enderror shadow"
                                                                    wire:model.defer="returnNotes" placeholder="Expliquez le contexte du retard…"></textarea>
                                                                @error('returnNotes')
                                                                    <div class="invalid-feedback">{{ $message }}
                                                                    </div>
                                                                @enderror
                                                            </div>
                                                            <div class="mb-3">
                                                                <label class="form-label">Pièce jointe
                                                                    (facultative)</label>
                                                                <input type="file"
                                                                    class="form-control @error('returnAttachment') is-invalid @enderror shadow"
                                                                    wire:model="returnAttachment"
                                                                    accept=".pdf,.jpg,.jpeg,.png,.doc,.docx">
                                                                @error('returnAttachment')
                                                                    <div class="invalid-feedback">{{ $message }}
                                                                    </div>
                                                                @enderror
                                                                <div wire:loading wire:target="returnAttachment"
                                                                    class="small text-danger mt-1">Téléversement du
                                                                    document en
                                                                    cours…</div>
                                                            </div>
                                                        @else
                                                            <div class="text-muted mb-3">Retour à la date prévue —
                                                                aucun
                                                                justificatif requis.</div>
                                                            <div class="col-md-6 mb-3">
                                                                <label class="form-label">Date et heure de retoure
                                                                </label>
                                                                <input type="datetime-local"
                                                                    class="form-control @error('return_confirmed_at') is-invalid @enderror shadow"
                                                                    wire:model="return_confirmed_at">
                                                                @error('return_confirmed_at')
                                                                    <div class="invalid-feedback">{{ $message }}
                                                                    </div>
                                                                @enderror
                                                            </div>
                                                        @endif

                                                        <div class="d-flex gap-2">
                                                            <button type="button" class="btn btn-secondary"
                                                                wire:click="cancelReturn">Annuler</button>
                                                            <button type="button" class="btn btn-dark"
                                                                wire:click="confirmReturn">Valider le retour</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="text-muted">Aucune demande.</div>
                            @endforelse

                            <div class="mt-2">
                                {{ $items->links() }}
                            </div>
                        </div>
                        <div id="absNoResult-{{ $forCompanyId }}" class="alert alert-info mt-3"
                            style="display:none;">
                            <i class="fas fa-info-circle me-2"></i>
                            Aucun résultat trouvé avec vos critères de recherche.
                        </div>


                    </div>
                </div>

                {{-- DROITE : formulaire --}}
                <div class="col-lg-6">
                    <h4 class="mb-3">{{ $isEditing ? 'Modifier la demande' : 'Nouvelle demande' }}</h4>

                    @if ($hasConflict)
                        <div class="alert alert-danger py-2">
                            Conflit détecté avec :
                            <ul class="mb-0 small">
                                @foreach ($conflicts as $c)
                                    <li>{{ $c['type'] }} ({{ $c['start'] }} → {{ $c['end'] }})
                                        [{{ $c['status'] }}]
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form wire:submit.prevent="save">


                        <div class="row g-3">
                            @php
                                $code_societe = session('entreprise_nom');
                                $newcodeSociete = collect(explode(' ', $code_societe))
                                    ->map(fn($word) => strtoupper(substr($word, 0, 1)))
                                    ->implode('');
                                $randomString = strtoupper(Str::random(3));
                                $currentTime = now()->format('His');
                                $code_demande = $newcodeSociete . '-' . $randomString . '-' . $currentTime;
                            @endphp

                            <div class="col-md-6">
                                <label class="form-label">Code de la demande</label>
                                <input type="text"
                                    class="form-control @error('code_demande') is-invalid @enderror shadow"
                                    wire:model="code_demande" value="{{ $code_demande }}" readonly>
                                @error('code_demande')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Employé</label>
                                <select class="form-select @error('form_user_id') is-invalid @enderror shadow"
                                    wire:model="form_user_id">
                                    <option value="">— Sélectionner un employé —</option>
                                    @foreach ($companyUsers as $u)
                                        <option value="{{ $u['id'] }}">{{ $u['label'] }}</option>
                                    @endforeach
                                </select>
                                @error('form_user_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Type</label>
                                <select class="form-select @error('type') is-invalid @enderror shadow"
                                    wire:model="type">
                                    <option value="">Choix du type</option>
                                    <option value="congé_payé">Congé payé</option>
                                    <option value="maladie">Maladie</option>
                                    <option value="RTT">RTT (Réduction du Temps de Travail)</option>
                                    <option value="maternité">Congé maternité</option>
                                    <option value="paternité">Congé paternité</option>
                                    <option value="parental">Congé parental</option>
                                    <option value="formation">Congé formation</option>
                                    <option value="sans_solde">Congé sans solde</option>
                                    <option value="exceptionnel">Congé exceptionnel (mariage, décès, etc.)</option>
                                    <option value="accident_travail">Accident du travail</option>
                                    <option value="mission_pro">Déplacement / Mission professionnelle</option>
                                    <option value="grève">Grève</option>
                                    <option value="autre">Autre</option>
                                </select>
                                @error('type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6">
                                <label class="form-label">Début</label>
                                <input type="datetime-local"
                                    class="form-control @error('start_datetime') is-invalid @enderror shadow"
                                    wire:model="start_datetime">
                                @error('start_datetime')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Fin</label>
                                <input type="datetime-local"
                                    class="form-control @error('end_datetime') is-invalid @enderror shadow"
                                    wire:model="end_datetime">
                                @error('end_datetime')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Pièce jointe (PDF/JPG/PNG/DOC, 5 Mo)</label>
                                <input type="file"
                                    class="form-control @error('attachment') is-invalid @enderror shadow"
                                    wire:model="attachment" accept=".pdf,.jpg,.jpeg,.png,.doc,.docx">
                                @error('attachment')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div wire:loading wire:target="attachment" class="small text-muted mt-1">
                                    Téléversement…
                                </div>
                            </div>
                        </div>
                        <div class="mt-3">
                            <label class="form-label">Motif</label>
                            <textarea class="form-control @error('reason') is-invalid @enderror shadow" wire:model="reason"></textarea>
                            @error('reason')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex gap-2 mt-4">
                            <button type="submit"
                                class="btn btn-primary">{{ $isEditing ? 'Mettre à jour' : 'Créer la demande' }}</button>
                            <button type="button" class="btn btn-secondary"
                                wire:click="openForm()">Réinitialiser</button>
                        </div>
                    </form>
                </div>
                <script>
                    function ouvrirDocument(event, url) {
                        event.preventDefault(); // Empêche l'ouverture normale du lien

                        const width = 800; // largeur de la popup
                        const height = 900; // hauteur de la popup

                        // Calcul pour centrer la popup
                        const left = (window.screen.width / 2) - (width / 2);
                        const top = (window.screen.height / 2) - (height / 2);

                        // Ouvrir la popup centrée avec le document
                        window.open(
                            url,
                            'documentPopup',
                            `width=${width},height=${height},top=${top},left=${left},scrollbars=yes,resizable=yes`
                        );
                    }
                </script>
                <script>
                    (() => {
                        const $search = document.getElementById('absSearch');
                        const $status = document.getElementById('absStatus');
                        const $from = document.getElementById('absFrom');
                        const $to = document.getElementById('absTo');

                        const scopeId = 'absenceAcc-{{ $forCompanyId }}';
                        const $scope = document.getElementById(scopeId);
                        const $noResult = document.getElementById('absNoResult-{{ $forCompanyId }}');
                        const $count = document.getElementById('absCount-{{ $forCompanyId }}');
                        const $countHint = document.getElementById('absCountHint-{{ $forCompanyId }}');

                        if (!$scope) return;

                        const $items = Array.from($scope.querySelectorAll('.accordion-item'));

                        const norm = s => (s || '').toString().toLowerCase().normalize('NFD').replace(/\p{Diacritic}/gu, '').trim();

                        const toDate = (val) => {
                            if (!val) return null;
                            if (/^\d{4}-\d{2}-\d{2}$/.test(val)) return new Date(val + 'T23:59:59'); // fin de journée
                            return new Date(val);
                        };

                        const passText = (needle, haystack) => !needle || norm(haystack).includes(norm(needle));
                        const passStatus = (wanted, status) => !wanted || status === wanted;
                        const passFrom = (fromStr, startStr) => {
                            if (!fromStr) return true;
                            const from = toDate(fromStr);
                            const start = toDate(startStr);
                            if (!from || !start) return true;
                            return start >= new Date(from.getFullYear(), from.getMonth(), from.getDate(), 0, 0, 0);
                        };
                        const passTo = (toStr, endStr) => {
                            if (!toStr) return true;
                            const to = toDate(toStr);
                            const end = toDate(endStr);
                            if (!to || !end) return true;
                            const toEnd = new Date(to.getFullYear(), to.getMonth(), to.getDate(), 23, 59, 59);
                            return end <= toEnd;
                        };

                        const apply = () => {
                            const q = $search.value.trim();
                            const st = $status.value;
                            const df = $from.value;
                            const dt = $to.value;

                            let visibleCount = 0;

                            $items.forEach($it => {
                                const text = $it.dataset.text || '';
                                const stat = $it.dataset.status || '';
                                const start = $it.dataset.start || '';
                                const end = $it.dataset.end || '';

                                const ok = passText(q, text) && passStatus(st, stat) && passFrom(df, start) && passTo(
                                    dt, end);
                                $it.style.display = ok ? '' : 'none';
                                if (ok) visibleCount++;
                            });

                            // Message "aucun résultat"
                            if ($noResult) {
                                $noResult.style.display = visibleCount === 0 ? '' : 'none';
                            }

                            // Compteur et hint
                            if ($count) {
                                const label = visibleCount === 1 ? '1 résultat' : `${visibleCount} résultats`;
                                $count.textContent = label;
                                $count.style.display = ''; // show
                            }
                            if ($countHint) {
                                const parts = [];
                                if ($status && $status.value) {
                                    parts.push(`Choix du filtre :  ${$status.options[$status.selectedIndex].text}`);
                                }
                                if ($from && $from.value) {
                                    parts.push(`du ${$from.value}`);
                                }
                                if ($to && $to.value) {
                                    parts.push(`au ${$to.value}`);
                                }
                                if ($search && $search.value.trim()) {
                                    parts.push(`recherche « ${$search.value.trim()} »`);
                                }
                                $countHint.textContent = parts.length ? parts.join(' — ') :
                                    'Filtre : aucun (hors statut par défaut)';
                            }
                        };

                        // Events
                        $search.addEventListener('input', apply);
                        $status.addEventListener('change', apply);
                        $from.addEventListener('change', apply);
                        $to.addEventListener('change', apply);

                        // Par défaut : afficher "soumis"
                        $status.value = "";
                        apply();
                    })();
                </script>


            </div>
        </div>
    </div>
