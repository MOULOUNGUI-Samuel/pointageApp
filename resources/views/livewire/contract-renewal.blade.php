<div>
    <div class="card">
        <div class="card-header">
            <h4 class="card-title">Renouveler le contrat</h4>
        </div>

        <div class="card-body">
            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show">
                    <strong><i class="fas fa-exclamation-circle me-2"></i> {{ session('error') }}</strong>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <!-- Informations ancien contrat -->
            <div class="alert alert-info mb-4">
                <h5 class="alert-heading"><i class="ti ti-info-circle me-2"></i>Contrat actuel</h5>
                <div class="row">
                    <div class="col-md-6">
                        <strong>Employé:</strong> {{ $oldContract->user->prenom }} {{ $oldContract->user->nom }}<br>
                        <strong>Type:</strong> {{ $oldContract->type_contrat }}<br>
                        <strong>Date début:</strong> {{ $oldContract->date_debut->format('d/m/Y') }}
                    </div>
                    <div class="col-md-6">
                        <strong>Date fin:</strong> {{ $oldContract->date_fin ? $oldContract->date_fin->format('d/m/Y') : 'Indéterminé' }}<br>
                        <strong>Salaire:</strong> {{ $oldContract->salaire_base ? number_format($oldContract->salaire_base, 0, ',', ' ') . ' FCFA' : '-' }}<br>
                        <strong>Version:</strong> v{{ $oldContract->version }}
                    </div>
                </div>
            </div>

            <form wire:submit.prevent="renew">
                <div class="row">
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
                        <label class="form-label">Nouvelle date de début <span class="text-danger">*</span></label>
                        <input type="date" wire:model="date_debut" class="form-control @error('date_debut') is-invalid @enderror">
                        @error('date_debut') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <!-- Date de fin -->
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Nouvelle date de fin</label>
                        <input type="date" wire:model="date_fin" class="form-control @error('date_fin') is-invalid @enderror">
                        @error('date_fin') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        <small class="text-muted">Laissez vide pour un contrat à durée indéterminée</small>
                    </div>

                    <!-- Salaire de base -->
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Nouveau salaire de base (FCFA)</label>
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

                    <!-- Avantages -->
                    <div class="col-md-12 mb-3">
                        <label class="form-label">Avantages</label>
                        <textarea wire:model="avantages" class="form-control" rows="3" placeholder="Décrivez les avantages du nouveau contrat..."></textarea>
                    </div>

                    <!-- Notes -->
                    <div class="col-md-12 mb-3">
                        <label class="form-label">Notes</label>
                        <textarea wire:model="notes" class="form-control" rows="3" placeholder="Notes sur le renouvellement..."></textarea>
                    </div>

                    <!-- Commentaire -->
                    <div class="col-md-12 mb-3">
                        <label class="form-label">Commentaire sur le renouvellement</label>
                        <input type="text" wire:model="comment" class="form-control" placeholder="Ex: Renouvellement avec augmentation de salaire...">
                        <small class="text-muted">Ce commentaire sera enregistré dans l'historique</small>
                    </div>
                </div>

                <div class="alert alert-warning">
                    <i class="ti ti-alert-triangle me-2"></i>
                    <strong>Important:</strong> Le contrat actuel sera automatiquement terminé et un nouveau contrat (v{{ $oldContract->version + 1 }}) sera créé.
                </div>

                <div class="d-flex justify-content-end gap-2">
                    <button type="button" wire:click="cancel" class="btn btn-secondary">
                        <i class="ti ti-x me-2"></i>Annuler
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="ti ti-refresh me-2"></i>Renouveler le contrat
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
