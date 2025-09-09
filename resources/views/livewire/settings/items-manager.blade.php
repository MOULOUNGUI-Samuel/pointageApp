<div wire:ignore.self class="modal fade" id="addItemModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h5 class="modal-title text-white">Gestion des items</h5>
                <button type="button" class="btn-close bg-light p-1" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                <div class="row g-3">
                    {{-- LISTE --}}
                    <div class="col-lg-7 border-end">
                        <div class="d-flex mb-2">
                            <input type="text" class="form-control me-2 searchInput" placeholder="Rechercher…">
                        </div>

                        @if (session('success'))
                            <div class="alert alert-success rounded-pill alert-dismissible fade show">
                                <strong class="me-5"><i class="fas fa-check me-2"></i>{{ session('success') }}</strong>
                                <button type="button" class="btn-close custom-close" data-bs-dismiss="alert" aria-label="Close">
                                    <i class="fas fa-xmark"></i>
                                </button>
                            </div>
                        @endif

                        @if (session('error'))
                            <div class="alert alert-danger rounded-pill alert-dismissible fade show">
                                <strong class="me-5">
                                    <i class="fas fa-exclamation-triangle me-2"></i>{{ session('error') }}
                                </strong>
                                <button type="button" class="btn-close custom-close" data-bs-dismiss="alert" aria-label="Close">
                                    <i class="fas fa-xmark"></i>
                                </button>
                            </div>
                        @endif

                        {{-- Confirmation suppression --}}
                        @if ($confirmingDeleteId)
                            <div class="alert alert-warning d-flex align-items-center justify-content-between">
                                <div>
                                    <i class="fas fa-exclamation-triangle me-2"></i>
                                    Confirmer la suppression de cet item ?
                                </div>
                                <div class="d-flex gap-2">
                                    <button class="btn btn-sm btn-danger" wire:click="deleteConfirmed">
                                        <i class="fas fa-check me-1"></i> Oui, supprimer
                                    </button>
                                    <button class="btn btn-sm btn-secondary" wire:click="cancelDelete">
                                        <i class="fas fa-times me-1"></i> Annuler
                                    </button>
                                </div>
                            </div>
                        @endif

                        <table class="table table-sm align-middle">
                            <thead>
                                <tr>
                                    <th>Item</th>
                                    <th>Catégorie</th>
                                    <th>Type</th>
                                    <th class="text-end">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="dataTable">
                                @forelse($items as $i)
                                    <tr>
                                        <td>
                                            <div class="fw-semibold">
                                                {{ \Illuminate\Support\Str::limit($i->nom_item, 25, '..') }} <br>
                                              
                                                @if ($i->periodeActive) 
                                                    <span class="badge bg-success">Période en cours</span>
                                                @else
                                                    <span class="badge bg-warning text-dark">Aucune période</span>
                                                @endif
                                              
                                                <br>{{ $i->periodes_actives_count }} active(s)
                                              </div>
                                              
                                            <div class="text-muted small">
                                                {{ \Illuminate\Support\Str::limit($i->description, 25, '..') }}
                                            </div>
                                        </td>
                                        <td>{{ \Illuminate\Support\Str::limit($i->CategorieDommaine?->nom_categorie, 17, '..') }}</td>
                                        <td>{{ \Illuminate\Support\Str::limit($i->typeItem?->nom_type, 10, '..') }}</td>
                                        <td class="text-end">
                                            <button class="btn btn-sm btn-outline-primary" wire:click="openForm('{{ $i->id }}')">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button class="btn btn-sm btn-outline-danger" wire:click="confirmDelete('{{ $i->id }}')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                            <button class="btn btn-sm btn-outline-secondary"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#periodesModal"
                                                    wire:click="$dispatch('open-periode-manager', { id: '{{ $i->id }}' })">
                                                <i class="fas fa-calendar-alt me-1"></i>
                                                Configurer période
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-muted">Aucun item.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>

                        <div>{{ $items->links() }}</div>
                    </div>

                    {{-- FORMULAIRE --}}
                    <div class="col-lg-5">
                        <h4>{{ $isEditing ? "Éditer l'item" : 'Ajouter un nouvel item' }}</h4>
                        <hr>

                        <form wire:submit.prevent="save">
                            <div class="mb-2">
                                <label class="form-label">Catégorie</label>
                                <select class="form-select @error('categorie_domaine_id') is-invalid @enderror shadow"
                                        wire:model.defer="categorie_domaine_id">
                                    <option value="">— Choisir une catégorie —</option>
                                    @foreach ($categories as $c)
                                        <option value="{{ $c['id'] }}">{{ $c['label'] }}</option>
                                    @endforeach
                                </select>
                                @error('categorie_domaine_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="mb-2">
                                <label class="form-label">Type</label>
                                <select class="form-select @error('type_item_id') is-invalid @enderror shadow"
                                        wire:model.defer="type_item_id">
                                    <option value="">— Choisir un type —</option>
                                    @foreach ($types as $t)
                                        <option value="{{ $t['id'] }}">{{ $t['label'] }}</option>
                                    @endforeach
                                </select>
                                @error('type_item_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="mb-2">
                                <label class="form-label">Nom</label>
                                <input type="text" class="form-control @error('nom_item') is-invalid @enderror shadow"
                                       wire:model.defer="nom_item">
                                @error('nom_item') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="mb-2">
                                <label class="form-label">Description</label>
                                <textarea class="form-control @error('description') is-invalid @enderror shadow"
                                          rows="3" wire:model.defer="description"></textarea>
                                @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Statut</label>
                                <select class="form-select @error('statut') is-invalid @enderror shadow"
                                        wire:model.defer="statut">
                                    <option value="1">Actif</option>
                                    <option value="0">Inactif</option>
                                </select>
                                @error('statut') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="d-flex gap-2">
                                <button class="btn btn-primary" type="submit">
                                    <i class="fas fa-save me-1"></i>
                                    {{ $isEditing ? 'Mettre à jour' : 'Créer' }}
                                </button>
                                <button class="btn btn-secondary" type="button" wire:click="openForm()">
                                    <i class="fas fa-rotate me-1"></i> Réinitialiser
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div> {{-- body --}}
        </div>
    </div>
</div>
