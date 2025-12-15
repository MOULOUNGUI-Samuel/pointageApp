<div>
    <div class="card">
        <div class="card-header">
            <h4 class="card-title">{{ $isEdit ? 'Modifier le contrat' : 'Nouveau contrat' }}</h4>
        </div>

        <div class="card-body">
            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show">
                    <strong><i class="fas fa-exclamation-circle me-2"></i> {{ session('error') }}</strong>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <form wire:submit.prevent="save">
                <div class="row">
                    <!-- Utilisateur -->
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Employé <span class="text-danger">*</span></label>
                        <select wire:model="userId" class="form-select @error('userId') is-invalid @enderror" {{ $isEdit ? 'disabled' : '' }}>
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
                    <div class="col-md-6 mb-3">
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
                        <textarea wire:model="avantages" class="form-control" rows="3" placeholder="Décrivez les avantages du contrat..."></textarea>
                    </div>

                    <!-- Notes -->
                    <div class="col-md-12 mb-3">
                        <label class="form-label">Notes</label>
                        <textarea wire:model="notes" class="form-control" rows="3" placeholder="Notes internes..."></textarea>
                    </div>

                    <!-- Commentaire -->
                    <div class="col-md-12 mb-3">
                        <label class="form-label">Commentaire sur la modification</label>
                        <input type="text" wire:model="comment" class="form-control" placeholder="Ex: Mise à jour du salaire suite à augmentation...">
                        <small class="text-muted">Ce commentaire sera enregistré dans l'historique</small>
                    </div>
                </div>

                <div class="d-flex justify-content-end gap-2">
                    <button type="button" wire:click="cancel" class="btn btn-secondary">
                        <i class="ti ti-x me-2"></i>Annuler
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="ti ti-check me-2"></i>{{ $isEdit ? 'Mettre à jour' : 'Créer' }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
