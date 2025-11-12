<div wire:ignore.self class="modal fade" id="addItemModal" role="dialog" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-fullscreen modal-dialog-scrollable">
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
                                <strong class="me-5"><i
                                        class="fas fa-check me-2"></i>{{ session('success') }}</strong>
                                <button type="button" class="btn-close custom-close" data-bs-dismiss="alert"
                                    aria-label="Close">
                                    <i class="fas fa-xmark"></i>
                                </button>
                            </div>
                        @endif

                        @if (session('error'))
                            <div class="alert alert-danger rounded-pill alert-dismissible fade show">
                                <strong class="me-5">
                                    <i class="fas fa-exclamation-triangle me-2"></i>{{ session('error') }}
                                </strong>
                                <button type="button" class="btn-close custom-close" data-bs-dismiss="alert"
                                    aria-label="Close">
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
                                            </div>

                                            <div class="text-muted small">
                                                {{ \Illuminate\Support\Str::limit($i->description, 25, '..') }}
                                            </div>
                                        </td>
                                        <td>{{ \Illuminate\Support\Str::limit($i->CategorieDomaine?->nom_categorie, 17, '..') }}
                                        </td>
                                        <td>
                                            @if (in_array($i->type, ['checkbox', 'liste']))
                                                <button class="btn btn-sm btn-outline-primary" data-bs-toggle="collapse"
                                                    data-bs-target="#collapseOptions{{ $i->id }}"
                                                    aria-expanded="false"
                                                    aria-controls="collapseOptions{{ $i->id }}">
                                                    {{ ucfirst($i->type) }} <i class="fas fa-eye"></i>
                                                </button>

                                                <div class="collapse mt-2" id="collapseOptions{{ $i->id }}">
                                                    <div class="card card-body">
                                                        <p class="fw-semibold mb-2">Liste des options :</p>
                                                        @if ($i->options->isNotEmpty())
                                                            <ul class="mb-0">
                                                                @foreach ($i->options->sortBy('position') as $opt)
                                                                    <li>
                                                                        {{ $opt->label }}
                                                                        @if ($opt->value)
                                                                            <span
                                                                                class="text-muted">({{ $opt->value }})</span>
                                                                        @endif
                                                                    </li>
                                                                @endforeach
                                                            </ul>
                                                        @else
                                                            <p class="text-muted small mb-0">Aucune option définie.</p>
                                                        @endif
                                                    </div>
                                                </div>
                                            @else
                                                <p>{{ ucfirst($i->type) }}</p>
                                            @endif
                                        </td>

                                        <td class="text-end">
                                            {{-- Bouton Éditer --}}
                                            <button class="btn btn-sm btn-outline-primary"
                                                wire:click="openForm('{{ $i->id }}')"
                                                wire:loading.attr="disabled"
                                                wire:target="openForm('{{ $i->id }}')">
                                                <span wire:loading.remove
                                                    wire:target="openForm('{{ $i->id }}')">
                                                    <i class="fas fa-edit"></i>
                                                </span>
                                                <span wire:loading wire:target="openForm('{{ $i->id }}')">
                                                    <i class="fas fa-spinner fa-spin"></i>
                                                </span>
                                            </button>

                                            {{-- Bouton Supprimer --}}
                                            <button class="btn btn-sm btn-outline-danger"
                                                wire:click="confirmDelete('{{ $i->id }}')"
                                                wire:loading.attr="disabled"
                                                wire:target="confirmDelete('{{ $i->id }}')">
                                                <span wire:loading.remove
                                                    wire:target="confirmDelete('{{ $i->id }}')">
                                                    <i class="fas fa-trash"></i>
                                                </span>
                                                <span wire:loading wire:target="confirmDelete('{{ $i->id }}')">
                                                    <i class="fas fa-spinner fa-spin"></i>
                                                </span>
                                            </button>

                                            {{-- <button class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal"
                                                data-bs-target="#periodesModal"
                                                wire:click="$dispatch('open-periode-manager', { id: '{{ $i->id }}' })">
                                                <i class="fas fa-calendar-alt me-1"></i>
                                                Configurer période
                                            </button> --}}
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
                                @error('categorie_domaine_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-2">
                                <label class="form-label">Nom</label>
                                <input type="text"
                                    class="form-control @error('nom_item') is-invalid @enderror shadow"
                                    wire:model.defer="nom_item">
                                @error('nom_item')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-2">
                                <label class="form-label">Type</label>
                                <select class="form-select @error('type') is-invalid @enderror shadow" wire:model="type"
                                    wire:click="afficheButtonAjouter">
                                    <option value="texte">Texte</option>
                                    <option value="documents">Documents</option>
                                    <option value="liste">Liste</option>
                                    <option value="checkbox">Case à cocher</option>
                                </select>
                                @error('type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            @if ($showOptions && in_array($type, ['liste', 'checkbox']))
                                <div class="mb-2" wire:key="options-{{ $type }}-{{ count($options) }}">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <label class="form-label mb-0">
                                            {{ $type === 'liste' ? 'Éléments de la liste' : 'Éléments de case à cocher' }}
                                        </label>
                                        <button type="button" class="btn btn-sm btn-outline-primary"
                                            wire:click="addOption" wire:loading.attr="disabled"
                                            wire:target="addOption">
                                            <span wire:loading.remove wire:target="addOption">
                                                <i class="fas fa-plus"></i> Ajouter
                                            </span>
                                            <span wire:loading wire:target="addOption">
                                                <i class="fas fa-spinner fa-spin"></i> Charg...
                                            </span>
                                        </button>

                                    </div>

                                    @error('options')
                                        <div class="text-danger small">{{ $message }}</div>
                                    @enderror

                                    @forelse ($options as $idx => $opt)
                                        <div class="input-group mt-2">
                                            <input type="text"
                                                class="form-control @error('options.' . $idx . '.label') is-invalid @enderror"
                                                placeholder="Libellé"
                                                wire:model.defer="options.{{ $idx }}.label">
                                            <input type="text" class="form-control"
                                                placeholder="Valeur (facultatif)"
                                                wire:model.defer="options.{{ $idx }}.value">
                                            <button class="btn btn-outline-danger" type="button"
                                                wire:click="removeOption({{ $idx }})"
                                                wire:loading.attr="disabled"
                                                wire:target="removeOption({{ $idx }})">

                                                <span wire:loading.remove
                                                    wire:target="removeOption({{ $idx }})">
                                                    <i class="fas fa-trash"></i>
                                                </span>
                                                <span wire:loading wire:target="removeOption({{ $idx }})">
                                                    <i class="fas fa-spinner fa-spin"></i>
                                                </span>
                                            </button>

                                            @error('options.' . $idx . '.label')
                                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    @empty
                                        <div class="text-muted small mt-1">Aucun élément. Cliquez sur “Ajouter”.</div>
                                    @endforelse
                                </div>
                            @endif




                            <div class="mb-2">
                                <label class="form-label">Description</label>
                                <textarea class="form-control @error('description') is-invalid @enderror shadow" rows="3"
                                    wire:model.defer="description"></textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Statut</label>
                                <select class="form-select @error('statut') is-invalid @enderror shadow"
                                    wire:model.defer="statut">
                                    <option value="1">Actif</option>
                                    <option value="0">Inactif</option>
                                </select>
                                @error('statut')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="d-flex gap-2">
                                {{-- Bouton Enregistrer --}}
                                <button class="btn btn-primary" type="submit" wire:loading.attr="disabled"
                                    wire:target="save">
                                    <span wire:loading.remove wire:target="save">
                                        <i class="fas fa-save me-1"></i>
                                        {{ $isEditing ? 'Mettre à jour' : 'Créer' }}
                                    </span>
                                    <span wire:loading wire:target="save">
                                        <i class="fas fa-spinner fa-spin me-1"></i>
                                        En cours...
                                    </span>
                                </button>

                                {{-- Bouton Réinitialiser --}}
                                <button class="btn btn-secondary" type="button" wire:click="openForm()">
                                    <span>
                                        <i class="fas fa-rotate me-1"></i>
                                        Réinitialiser
                                    </span>
                                </button>
                            </div>

                        </form>
                    </div>
                </div>
            </div> {{-- body --}}
        </div>
    </div>
</div>
