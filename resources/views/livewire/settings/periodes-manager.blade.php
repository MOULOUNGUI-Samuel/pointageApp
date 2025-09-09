<div>
    {{-- En-tête / contexte --}}
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <div class="fw-semibold">Item ciblé :</div>
            <div class="text-muted">{{ $itemLabel ?: '—' }}</div>
        </div>
        <div class="w-50">
            <input type="text" class="form-control" placeholder="Rechercher (dates)…"
                   wire:model.debounce.400ms="search">
        </div>
    </div>

    {{-- Formulaire période --}}
    <form wire:submit.prevent="save" class="card mb-3">
        <div class="card-body row g-3">
            <div class="col-md-4">
                <label class="form-label">Début</label>
                <input type="date" class="form-control @error('debut_periode') is-invalid @enderror"
                       wire:model="debut_periode">
                @error('debut_periode')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-4">
                <label class="form-label">Fin</label>
                <input type="date" class="form-control @error('fin_periode') is-invalid @enderror"
                       wire:model="fin_periode">
                @error('fin_periode')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-4">
                <label class="form-label">Statut</label>
                <select class="form-select @error('statut') is-invalid @enderror" wire:model="statut">
                    <option value="1">Actif</option>
                    <option value="0">Annulé</option>
                </select>
                @error('statut')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
        </div>
        <div class="card-footer d-flex gap-2">
            <button class="btn btn-primary" type="submit">
                {{ $isEditing ? 'Mettre à jour' : 'Créer' }}
            </button>
            <button class="btn btn-secondary" type="button" wire:click="openForm">Réinitialiser</button>
        </div>
    </form>

    {{-- Liste des périodes --}}
    <div class="table-responsive">
        <table class="table align-middle">
            <thead>
            <tr>
                <th>Début</th>
                <th>Fin</th>
                <th>Statut</th>
                <th class="text-end">Actions</th>
            </tr>
            </thead>
            <tbody>
            @forelse($list as $p)
                <tr>
                    <td>{{ optional($p->debut_periode)->format('d/m/Y') }}</td>
                    <td>{{ optional($p->fin_periode)->format('d/m/Y') }}</td>
                    <td>
                        @if($p->statut === '1')
                            <span class="badge bg-success">Actif</span>
                        @else
                            <span class="badge bg-secondary">Annulé</span>
                        @endif
                    </td>
                    <td class="text-end">
                        <div class="btn-group btn-group-sm">
                            <button class="btn btn-outline-primary" wire:click="openForm('{{ $p->id }}')">
                                <i class="fas fa-edit"></i>
                            </button>
                            @if($p->statut === '1')
                                <button class="btn btn-outline-warning" wire:click="cancel('{{ $p->id }}')"
                                        title="Annuler cette période">
                                    <i class="fas fa-ban"></i>
                                </button>
                                <button class="btn btn-outline-success" wire:click="renewFrom('{{ $p->id }}')"
                                        title="Renouveler à partir de la fin">
                                    <i class="fas fa-redo"></i>
                                </button>
                            @endif
                            <button class="btn btn-outline-danger" wire:click="confirmDelete('{{ $p->id }}')">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </td>
                </tr>
            @empty
                <tr><td colspan="4" class="text-muted">Aucune période définie.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-2">
        {{ $list->links() }}
    </div>

    {{-- Modale de confirmation suppression --}}
    <div class="modal @if($confirmingDeleteId) show d-block @endif" tabindex="-1"
         @if(!$confirmingDeleteId) style="display:none;" @endif>
        <div class="modal-dialog">
            <div class="modal-content shadow">
                <div class="modal-header">
                    <h5 class="modal-title">Confirmer la suppression</h5>
                    <button type="button" class="btn-close" wire:click="cancelDelete"></button>
                </div>
                <div class="modal-body">
                    <p>Cette action est irréversible. Supprimer cette période ?</p>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" wire:click="cancelDelete">Annuler</button>
                    <button class="btn btn-danger" wire:click="deleteConfirmed">Supprimer</button>
                </div>
            </div>
        </div>
    </div>
</div>
