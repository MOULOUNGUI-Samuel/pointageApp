<div>
    @if($showModal)
    <div class="modal fade show" style="display: block; background: rgba(0,0,0,0.5);" tabindex="-1">
        <div class="modal-dialog {{ $modalMode === 'history' ? 'modal-xl' : 'modal-lg' }} modal-dialog-scrollable">
            <div class="modal-content">
                <!-- Modal Header -->
                <div class="modal-header">
                    <h5 class="modal-title">
                        @if($modalMode === 'create')
                            <i class="ti ti-plus me-2"></i>Nouveau contrat
                        @elseif($modalMode === 'edit')
                            <i class="ti ti-edit me-2"></i>Modifier le contrat
                        @elseif($modalMode === 'renew')
                            <i class="ti ti-refresh me-2"></i>Renouveler le contrat
                        @elseif($modalMode === 'view')
                            <i class="ti ti-eye me-2"></i>Détails du contrat
                        @elseif($modalMode === 'history')
                            <i class="ti ti-history me-2"></i>Historique du contrat
                        @endif
                    </h5>
                    <button type="button" class="btn-close" wire:click="closeModal"></button>
                </div>

                <!-- Modal Body -->
                <div class="modal-body">
                    @if (session('modal_error'))
                        <div class="alert alert-danger alert-dismissible fade show">
                            <i class="fas fa-exclamation-circle me-2"></i> {{ session('modal_error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if($modalMode === 'create' || $modalMode === 'edit' || $modalMode === 'renew')
                        <!-- FORMULAIRE CREATION / MODIFICATION / RENOUVELLEMENT -->
                        @if($modalMode === 'renew' && $contract)
                            <div class="alert alert-info mb-3">
                                <i class="ti ti-info-circle me-2"></i>
                                <strong>Renouvellement du contrat de {{ $contract->user->prenom }} {{ $contract->user->nom }}</strong>
                                <br>
                                <small>Contrat actuel : {{ $contract->type_contrat }}
                                    @if($contract->date_debut && $contract->date_fin)
                                        (du {{ $contract->date_debut->format('d/m/Y') }} au {{ $contract->date_fin->format('d/m/Y') }})
                                    @endif
                                </small>
                            </div>
                        @endif

                        <form wire:submit.prevent="save">
                            <div class="row">
                                <!-- Utilisateur -->
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Employé <span class="text-danger">*</span></label>
                                    <select wire:model="userId" class="form-select @error('userId') is-invalid @enderror" {{ ($modalMode === 'edit' || $modalMode === 'renew') ? 'disabled' : '' }}>
                                        <option value="">Sélectionner un employé</option>
                                        @foreach($users as $user)
                                            <option value="{{ $user->id }}">{{ $user->prenom }} {{ $user->nom }} ({{ $user->matricule }})</option>
                                        @endforeach
                                    </select>
                                    @error('userId') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>

                                <!-- Type de contrat -->
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Type de contrat <span class="text-danger">*</span></label>
                                    <select wire:model="type_contrat" class="form-select @error('type_contrat') is-invalid @enderror">
                                        <option value="">Sélectionner un type</option>
                                        @foreach($contractTypes as $key => $value)
                                            <option value="{{ $key }}">{{ $value }}</option>
                                        @endforeach
                                    </select>
                                    @error('type_contrat') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>

                                <!-- Date de début -->
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Date de début <span class="text-danger">*</span></label>
                                    <input type="date" wire:model="date_debut" class="form-control @error('date_debut') is-invalid @enderror">
                                    @error('date_debut') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>

                                <!-- Date de fin -->
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Date de fin</label>
                                    <input type="date" wire:model="date_fin" class="form-control @error('date_fin') is-invalid @enderror">
                                    @error('date_fin') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    <small class="text-muted">Laissez vide pour un contrat à durée indéterminée</small>
                                </div>

                                <!-- Salaire de base -->
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Salaire de base (FCFA)</label>
                                    <input type="number" wire:model="salaire_base" class="form-control @error('salaire_base') is-invalid @enderror" step="0.01">
                                    @error('salaire_base') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>

                                <!-- Mode de paiement -->
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Mode de paiement</label>
                                    <select wire:model="mode_paiement" class="form-select">
                                        <option value="">Sélectionner</option>
                                        <option value="Virement">Virement</option>
                                        <option value="Espèces">Espèces</option>
                                        <option value="Chèque">Chèque</option>
                                    </select>
                                </div>

                                <!-- Statut -->
                                <div class="col-md-12 mb-3">
                                    <label class="form-label">Statut <span class="text-danger">*</span></label>
                                    <select wire:model="statut" class="form-select @error('statut') is-invalid @enderror">
                                        @foreach($contractStatuses as $key => $value)
                                            <option value="{{ $key }}">{{ $value }}</option>
                                        @endforeach
                                    </select>
                                    @error('statut') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>

                                <!-- Avantages -->
                                <div class="col-md-12 mb-3">
                                    <label class="form-label">Avantages</label>
                                    <textarea wire:model="avantages" class="form-control" rows="2" placeholder="Décrivez les avantages du contrat..."></textarea>
                                </div>

                                <!-- Notes -->
                                <div class="col-md-12 mb-3">
                                    <label class="form-label">Notes</label>
                                    <textarea wire:model="notes" class="form-control" rows="2" placeholder="Notes internes..."></textarea>
                                </div>

                                <!-- Fichier joint -->
                                <div class="col-md-12 mb-3">
                                    <label class="form-label">Fichier joint (PDF, Word, Image)</label>
                                    <input type="file" wire:model="fichier_joint" class="form-control @error('fichier_joint') is-invalid @enderror"
                                           accept=".pdf,.doc,.docx,.jpg,.jpeg,.png">
                                    @error('fichier_joint') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    <small class="text-muted">Formats acceptés: PDF, DOC, DOCX, JPG, PNG (Max: 10MB)</small>

                                    @if($fichier_joint)
                                        <div class="mt-2">
                                            <span class="badge bg-success">
                                                <i class="ti ti-file me-1"></i>Fichier sélectionné: {{ $fichier_joint->getClientOriginalName() }}
                                            </span>
                                        </div>
                                    @endif
                                </div>

                                <!-- Commentaire -->
                                <div class="col-md-12 mb-3">
                                    <label class="form-label">Commentaire</label>
                                    <input type="text" wire:model="comment" class="form-control" placeholder="Ex: Mise à jour du salaire...">
                                    <small class="text-muted">Ce commentaire sera enregistré dans l'historique</small>
                                </div>
                            </div>
                        </form>

                    @elseif($modalMode === 'view' && $contract)
                        <!-- AFFICHAGE DETAILS -->
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <h6 class="text-muted mb-2">Employé</h6>
                                <div class="d-flex align-items-center">
                                    @if ($contract->user->photo)
                                                <img src="{{ asset('storage/' . $contract->user->photo) }}"
                                                    class="img-fluid rounded-circle border" alt="Photo de profil"
                                                    style="width: 40px; height: 40px;">
                                            @else
                                                <img src="{{ asset('src/images/user.jpg') }}"
                                                    class="img-fluid rounded-circle border" alt="Photo de profil"
                                                    style="width: 40px; height: 40px;">
                                            @endif
                                    <div class="ms-3">
                                        <strong>{{ $contract->user->prenom }} {{ $contract->user->nom }}</strong><br>
                                        <small class="text-muted">{{ $contract->user->matricule }}</small>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-3 mb-3">
                                <h6 class="text-muted mb-2">Type</h6>
                                <span class="badge bg-info">{{ $contract->type_contrat }}</span>
                            </div>

                            <div class="col-md-3 mb-3">
                                <h6 class="text-muted mb-2">Statut</h6>
                                <span class="badge {{ $contract->statut_badge }}">{{ $contract->statut_label }}</span>
                                @if($contract->parent_contract_id)
                                    <span class="badge bg-secondary ms-1">v{{ $contract->version }}</span>
                                @endif
                            </div>

                            <div class="col-md-3 mb-3">
                                <h6 class="text-muted mb-2">Date début</h6>
                                <p>{{ $contract->date_debut ? $contract->date_debut->format('d/m/Y') : '-' }}</p>
                            </div>

                            <div class="col-md-3 mb-3">
                                <h6 class="text-muted mb-2">Date fin</h6>
                                <p>
                                    @if($contract->date_fin)
                                        {{ $contract->date_fin->format('d/m/Y') }}
                                        @if($contract->is_active && $contract->jours_restants !== null)
                                            <br><small class="text-muted">
                                                @if($contract->jours_restants > 0)
                                                    <span class="text-warning">({{ $contract->jours_restants }} jours)</span>
                                                @else
                                                    <span class="text-danger">(Expiré)</span>
                                                @endif
                                            </small>
                                        @endif
                                    @else
                                        <span class="text-muted">Indéterminé</span>
                                    @endif
                                </p>
                            </div>

                            <div class="col-md-3 mb-3">
                                <h6 class="text-muted mb-2">Salaire</h6>
                                <p>{{ $contract->salaire_base ? number_format($contract->salaire_base, 0, ',', ' ') . ' FCFA' : '-' }}</p>
                            </div>

                            <div class="col-md-3 mb-3">
                                <h6 class="text-muted mb-2">Mode paiement</h6>
                                <p>{{ $contract->mode_paiement ?? '-' }}</p>
                            </div>

                            @if($contract->avantages)  
                            <div class="col-md-12 mb-3">
                                <h6 class="text-muted mb-2">Avantages</h6>
                                <p>{{ $contract->avantages }}</p>
                            </div>
                            @endif

                            @if($contract->notes)
                            <div class="col-md-12 mb-3">
                                <h6 class="text-muted mb-2">Notes</h6>
                                <p>{{ $contract->notes }}</p>
                            </div>
                            @endif

                            @if($contract->fichier_joint)
                            <div class="col-md-12 mb-3">
                                <h6 class="text-muted mb-2">Fichier joint</h6>
                                <a href="{{ asset('storage/' . $contract->fichier_joint) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                    <i class="ti ti-file-download me-1"></i>Télécharger le fichier
                                </a>
                            </div>
                            @endif

                            <div class="col-md-6 mb-3">
                                <h6 class="text-muted mb-2">Créé par</h6>
                                <p>{{ $contract->createdBy ? $contract->createdBy->prenom . ' ' . $contract->createdBy->nom : '-' }}</p>
                                <small class="text-muted">{{ $contract->created_at->format('d/m/Y H:i') }}</small>
                            </div>

                            <div class="col-md-6 mb-3">
                                <h6 class="text-muted mb-2">Modifié par</h6>
                                <p>{{ $contract->updatedBy ? $contract->updatedBy->prenom . ' ' . $contract->updatedBy->nom : '-' }}</p>
                                <small class="text-muted">{{ $contract->updated_at->format('d/m/Y H:i') }}</small>
                            </div>

                            @if($contract->parent_contract_id && $contract->parentContract)
                            <div class="col-md-12">
                                <div class="alert alert-info">
                                    <i class="ti ti-info-circle me-2"></i>
                                    Ce contrat est un renouvellement du contrat du {{ $contract->parentContract->date_debut->format('d/m/Y') }} au {{ $contract->parentContract->date_fin ? $contract->parentContract->date_fin->format('d/m/Y') : 'indéterminé' }}
                                </div>
                            </div>
                            @endif
                        </div>

                        <!-- Actions -->
                        @if($contract->is_active)
                        <div class="mt-3">
                            <h6>Actions rapides</h6>
                            <div class="d-flex gap-2">
                                <button type="button" class="btn btn-warning btn-sm" wire:click="suspendContract">
                                    <i class="ti ti-alert-triangle me-1"></i>Suspendre
                                </button>
                            </div>
                        </div>
                        @elseif($contract->statut === 'suspendu')
                        <div class="mt-3">
                            <h6>Actions rapides</h6>
                            <button type="button" class="btn btn-success btn-sm" wire:click="reactivateContract">
                                <i class="ti ti-check me-1"></i>Réactiver
                            </button>
                        </div>
                        @endif

                    @elseif($modalMode === 'history' && $contract)
                        <!-- HISTORIQUE -->
                        @if($histories->isEmpty())
                            <div class="text-center py-4">
                                <i class="ti ti-history text-muted" style="font-size: 3rem;"></i>
                                <p class="text-muted mt-2">Aucun historique disponible</p>
                            </div>
                        @else
                            <div class="timeline">
                                @foreach($histories as $history)
                                    <div class="timeline-item">
                                        <div class="timeline-marker">
                                            <span class="badge {{ $history->action_badge }}">
                                                @if($history->action === 'created')
                                                    <i class="ti ti-plus"></i>
                                                @elseif($history->action === 'updated')
                                                    <i class="ti ti-edit"></i>
                                                @elseif($history->action === 'renewed')
                                                    <i class="ti ti-refresh"></i>
                                                @elseif($history->action === 'suspended')
                                                    <i class="ti ti-alert-triangle"></i>
                                                @elseif($history->action === 'terminated')
                                                    <i class="ti ti-x"></i>
                                                @elseif($history->action === 'reactivated')
                                                    <i class="ti ti-check"></i>
                                                @else
                                                    <i class="ti ti-file"></i>
                                                @endif
                                            </span>
                                        </div>
                                        <div class="timeline-content">
                                            <div class="d-flex justify-content-between align-items-start mb-2">
                                                <div>
                                                    <h6 class="mb-1">{{ $history->action_label }}</h6>
                                                    <small class="text-muted">
                                                        <i class="ti ti-user me-1"></i>
                                                        {{ $history->modifiedBy->prenom ?? '' }} {{ $history->modifiedBy->nom ?? '' }}
                                                        <i class="ti ti-calendar ms-2 me-1"></i>
                                                        {{ $history->created_at->format('d/m/Y H:i') }}
                                                    </small>
                                                </div>
                                            </div>

                                            @if($history->comment)
                                                <p class="mb-2"><strong>Commentaire:</strong> {{ $history->comment }}</p>
                                            @endif

                                            @if($history->changes && count($history->changes) > 0)
                                                <div class="table-responsive mt-2">
                                                    <table class="table table-sm table-bordered">
                                                        <thead>
                                                            <tr>
                                                                <th>Champ</th>
                                                                <th>Avant</th>
                                                                <th>Après</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach($history->changes as $field => $change)
                                                                @if(is_array($change) && isset($change['before'], $change['after']))
                                                                    <tr>
                                                                        <td><strong>{{ ucfirst(str_replace('_', ' ', $field)) }}</strong></td>
                                                                        <td>
                                                                            @if(is_null($change['before']))
                                                                                <span class="text-muted">-</span>
                                                                            @else
                                                                                {{ is_array($change['before']) ? json_encode($change['before']) : $change['before'] }}
                                                                            @endif
                                                                        </td>
                                                                        <td>
                                                                            @if(is_null($change['after']))
                                                                                <span class="text-muted">-</span>
                                                                            @else
                                                                                <strong class="text-primary">
                                                                                    {{ is_array($change['after']) ? json_encode($change['after']) : $change['after'] }}
                                                                                </strong>
                                                                            @endif
                                                                        </td>
                                                                    </tr>
                                                                @endif
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    @endif
                </div>

                <!-- Modal Footer -->
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" wire:click="closeModal">
                        <i class="ti ti-x me-1"></i>Fermer
                    </button>
                    @if($modalMode === 'create' || $modalMode === 'edit' || $modalMode === 'renew')
                        <button type="button" wire:click="save" class="btn btn-primary">
                            <i class="ti ti-check me-1"></i>
                            @if($modalMode === 'edit')
                                Mettre à jour
                            @elseif($modalMode === 'renew')
                                Renouveler
                            @else
                                Créer
                            @endif
                        </button>
                    @elseif($modalMode === 'view' && $contract && $contract->estModifiable())
                        <button type="button" wire:click="openModal('edit', '{{ $contract->id }}')" class="btn btn-primary">
                            <i class="ti ti-edit me-1"></i>Modifier
                        </button>
                    @endif
                </div>
            </div>
        </div>
    </div>
    @endif

    <style>
        .timeline {
            position: relative;
            padding-left: 50px;
        }

        .timeline-item {
            position: relative;
            margin-bottom: 30px;
            padding-bottom: 30px;
            border-left: 2px solid #e9ecef;
        }

        .timeline-item:last-child {
            border-left: none;
        }

        .timeline-marker {
            position: absolute;
            left: -28px;
            top: 0;
        }

        .timeline-marker .badge {
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            font-size: 18px;
        }

        .timeline-content {
            padding-left: 20px;
            padding-top: 5px;
        }
    </style>
</div>
