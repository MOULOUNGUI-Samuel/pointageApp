<div wire:ignore.self class="modal fade" id="addCategoryDomainModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h5 class="modal-title text-white">Gestion des catégories de domaines</h5>
                <button type="button" class="btn-close  bg-light p-1" data-bs-dismiss="modal"></button>
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
                                    <strong class="me-5"><i class="fas fa-check me-2"></i>
                                        {{ session('success') }}</strong>
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
                            {{-- ✅ bandeau de confirmation suppression --}}
                            @if ($confirmingDeleteId)
                                <div class="alert alert-warning d-flex align-items-center justify-content-between">
                                    <div>
                                        <i class="fas fa-exclamation-triangle me-2"></i>
                                        Confirmer la suppression de cette catégorie ?
                                    </div>
                                    <div class="d-flex gap-2">
                                        <button class="btn btn-sm btn-danger" wire:click="deleteConfirmed">
                                            <i class="fas fa-check me-1"></i>
                                            Oui,
                                            supprimer</button>
                                        <button class="btn btn-sm btn-secondary" wire:click="cancelDelete">
                                            <i class="fas fa-times me-1"></i>
                                            Annuler</button>
                                    </div>
                                </div>
                            @endif
                            <table class="table table-sm align-middle">
                                <thead>
                                    <tr>
                                        <th>Nom</th>
                                        <th>Code</th>
                                        <th>Domaine</th> {{-- ✅ --}}
                                        <th>Statut</th>
                                        <th class="text-end">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="dataTable">
                                    @forelse($items as $c)
                                        <tr>
                                            <td>{{ \Illuminate\Support\Str::limit($c->nom_categorie, 20, '..') }}</td>
                                            <td><code>{{ $c->code_categorie }}</code></td>
                                            <td class="text-muted">
                                                {{ \Illuminate\Support\Str::limit($c->domaine?->nom_domaine ?? '—', 18, '..') }}
                                            </td>
                                            <td><span
                                                    class="badge bg-{{ $c->statut == 1 ? 'success' : 'secondary' }}">{{ $c->statut == 1 ? 'Actif' : 'Inactif' }}</span>
                                            </td>
                                            <td class="text-end">
                                                <button class="btn btn-sm btn-outline-primary"
                                                    wire:click="openForm('{{ $c->id }}')">
                                                    <i class="fas fa-edit"></i></button>
                                                <button class="btn btn-sm btn-outline-danger"
                                                    wire:click="confirmDelete('{{ $c->id }}')">
                                                    <i class="fas fa-trash"></i></button>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-muted">Aucune catégorie.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                            <div>{{ $items->links() }}</div>
                    </div>

                    {{-- FORMULAIRE --}}
                    <div class="col-lg-5">
                        <h4 class="my-2">{{ $isEditing ? 'Modifier la catégorie' : 'Nouvelle catégorie' }}</h4>
                        <form wire:submit.prevent="save">
                            <div class="mb-2">
                                <label class="form-label">Nom</label>
                                {{-- Nom de la catégorie --}}
                                <input type="text" class="form-control mt-2 shadow" placeholder="Nom de la catégorie"
                                    wire:model.live="nom_categorie">
                                @error('nom_categorie')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>


                            {{-- ✅ Sélecteur Parent --}}
                            <div class="mb-2">
                                <label class="form-label">Domaine</label>
                                <select class="form-select shadow" wire:model.live="domaine_id">
                                    <option value="">-- Choisir un domaine --</option>
                                    @foreach ($domaines as $d)
                                        <option value="{{ $d['id'] }}">{{ $d['label'] }}</option>
                                    @endforeach
                                </select>
                                @error('domaine_id')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-2">
                                <label class="form-label">Code(généré automatiquement)</label>
                                {{-- Code (auto) --}}
                                <input type="text" class="form-control mt-2 shadow" wire:model="code_categorie" readonly>
                                @error('code_categorie')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-2">
                                <label class="form-label">Description</label>
                                <textarea class="form-control @error('description') is-invalid @enderror shadow" rows="3" wire:model.defer="description"></textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Statut</label>
                                <select class="form-select @error('statut') is-invalid @enderror sahadow"
                                    wire:model.defer="statut">
                                    <option value="1">Actif</option>
                                    <option value="0">Inactif</option>
                                </select>
                                @error('statut')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="d-flex gap-2">
                                <button class="btn btn-primary" type="submit">
                                    <i class="fas fa-save me-1"></i>
                                    {{ $isEditing ? 'Mettre à jour' : 'Créer' }}</button>
                                <button class="btn btn-secondary" type="button" wire:click="openForm()">
                                    <i class="fas fa-rotate me-1"></i>
                                    Réinitialiser</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div> {{-- body --}}
        </div>
    </div>
</div>
