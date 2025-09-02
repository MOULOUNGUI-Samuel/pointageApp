{{-- resources/views/livewire/absences/manager.blade.php --}}
<div>
    {{-- Bouton d’ouverture --}}
    <div class="d-flex align-items-center justify-content-between mb-3">
        <h4 class="mb-0">Mes Demandes d'Absence</h4>
        <button class="btn btn-primary" wire:click="openModal()" data-bs-toggle="modal" data-bs-target="#absenceModal">
            Nouvelle demande
        </button>
    </div>

    {{-- Filtres --}}
    <div class="row g-2 mb-3">
        <div class="col-md-6">
            <input type="text" wire:model.debounce.500ms="search" class="form-control"
                placeholder="Rechercher (type, motif, justification)…">
        </div>
        <div class="col-md-3">
            <select class="form-select" wire:model="statusFilter">
                <option value="">Tous les statuts</option>
                <option value="brouillon">Brouillon</option>
                <option value="soumis">Soumis</option>
                <option value="approuvé">Approuvé</option>
                <option value="rejeté">Rejeté</option>
            </select>
        </div>
    </div>

    {{-- Flashs --}}
    @if (session('success'))
        <div class="alert alert-success py-2">{{ session('success') }}</div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger py-2">{{ session('error') }}</div>
    @endif

    {{-- Liste compacte --}}
    <div class="table-responsive">
        <table class="table align-middle">
            <thead class="table-light">
                <tr>
                    <th>Type</th>
                    <th>Début</th>
                    <th>Fin</th>
                    <th>Statut</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($items as $a)
                    <tr>
                        <td class="text-capitalize">{{ str_replace('_', ' ', $a->type) }}</td>
                        <td>{{ $a->start_datetime?->format('d/m/Y H:i') }}</td>
                        <td>{{ $a->end_datetime?->format('d/m/Y H:i') }}</td>
                        <td>
                            @php
                                $badge =
                                    [
                                        'brouillon' => 'secondary',
                                        'soumis' => 'warning',
                                        'approuvé' => 'success',
                                        'rejeté' => 'danger',
                                    ][$a->status] ?? 'secondary';
                            @endphp
                            <span class="badge bg-{{ $badge }}">{{ ucfirst($a->status) }}</span>
                        </td>
                        <td class="text-end">
                            <div class="btn-group">
                                <button class="btn btn-sm btn-outline-primary"
                                    wire:click="openModal('{{ $a->id }}')">Éditer</button>

                                @if ($a->status === 'brouillon')
                                    <button class="btn btn-sm btn-outline-info"
                                        wire:click="submit('{{ $a->id }}')">Soumettre</button>
                                @endif

                                <button class="btn btn-sm btn-outline-danger"
                                    wire:click="delete('{{ $a->id }}')">Supprimer</button>

                                {{-- Actions manager (affiche quand tu veux, ici par exemple pour tous) --}}
                                @if ($a->status === 'soumis')
                                    <button class="btn btn-sm btn-outline-success"
                                        wire:click="approve('{{ $a->id }}')">Approuver</button>
                                    {{-- Rejet avec justification (saisie dans un textarea plus bas de la modale) --}}
                                    <a class="btn btn-sm btn-outline-danger" href="#" data-bs-toggle="modal"
                                        data-bs-target="#justifyModal-{{ $a->id }}">
                                        Rejeter
                                    </a>

                                    {{-- Modale justification (ton style, avec wire:ignore.self) --}}
                                    <div class="modal fade" id="justifyModal-{{ $a->id }}" tabindex="-1"
                                        role="dialog" aria-labelledby="justifyLabel-{{ $a->id }}"
                                        aria-hidden="true" wire:ignore.self>
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">

                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="justifyLabel-{{ $a->id }}">
                                                        Justification du rejet</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                        aria-label="Fermer"></button>
                                                </div>

                                                <div class="modal-body">
                                                    <textarea class="form-control" rows="5" wire:model.defer="justification"
                                                        placeholder="Expliquez la raison du rejet…"></textarea>
                                                    @error('justification')
                                                        <small class="text-danger">{{ $message }}</small>
                                                    @enderror
                                                </div>

                                                <div class="modal-footer">
                                                    <button class="btn btn-secondary"
                                                        data-bs-dismiss="modal">Annuler</button>
                                                    <button class="btn btn-danger" data-bs-dismiss="modal"
                                                        wire:click="reject('{{ $a->id }}')">
                                                        Confirmer le rejet
                                                    </button>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center text-muted">Aucune demande.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-2">
        {{ $items->links() }}
    </div>

    {{-- MODALE PLEIN ÉCRAN (liste gauche / formulaire droite) --}}
    {{-- IMPORTANT : wire:ignore.self sur la MODALE --}}
    <div class="modal fade" id="absenceModal" tabindex="-1" role="dialog" aria-labelledby="absenceLabel"
        aria-hidden="true" wire:ignore.self>
        <div class="modal-dialog modal-fullscreen" role="document">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title" id="absenceLabel">
                        {{ $isEditing ? 'Modifier la demande' : 'Nouvelle demande' }}
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
                </div>

                <div class="modal-body">
                    <div class="row g-4">
                        {{-- Colonne gauche : mini-liste --}}
                        <div class="col-lg-6 border-end">
                            <h6 class="mb-3">Mes dernières demandes</h6>
                            <div class="list-group">
                                @foreach ($items as $row)
                                    <a href="#"
                                        class="list-group-item list-group-item-action d-flex justify-content-between align-items-center"
                                        wire:click="openModal('{{ $row->id }}')" data-bs-toggle="modal"
                                        data-bs-target="#absenceModal">
                                        <div>
                                            <div class="fw-semibold text-capitalize">
                                                {{ str_replace('_', ' ', $row->type) }}</div>
                                            <small class="text-muted">
                                                {{ $row->start_datetime?->format('d/m H:i') }} →
                                                {{ $row->end_datetime?->format('d/m H:i') }}
                                            </small>
                                        </div>
                                        <span
                                            class="badge bg-{{ ['brouillon' => 'secondary', 'soumis' => 'warning', 'approuvé' => 'success', 'rejeté' => 'danger'][$row->status] ?? 'secondary' }}">
                                            {{ ucfirst($row->status) }}
                                        </span>
                                    </a>
                                @endforeach
                            </div>
                        </div>

                        {{-- Colonne droite : formulaire --}}
                        <div class="col-lg-6">
                            <form wire:submit.prevent="save" class="needs-validation" novalidate>
                                <div class="mb-3">
                                    <label class="form-label">Type d'absence</label>
                                    <select class="form-select @error('type') is-invalid @enderror" wire:model="type">
                                        <option value="congé_payé">Congé payé</option>
                                        <option value="maladie">Maladie</option>
                                        <option value="RTT">RTT</option>
                                        <option value="autre">Autre</option>
                                    </select>
                                    @error('type')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label">Début</label>
                                        <input type="datetime-local"
                                            class="form-control @error('start_datetime') is-invalid @enderror"
                                            wire:model="start_datetime">
                                        @error('start_datetime')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Fin</label>
                                        <input type="datetime-local"
                                            class="form-control @error('end_datetime') is-invalid @enderror"
                                            wire:model="end_datetime">
                                        @error('end_datetime')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="mt-3">
                                    <label class="form-label">Motif (raison)</label>
                                    <textarea rows="4" class="form-control @error('reason') is-invalid @enderror" wire:model="reason"
                                        placeholder="Expliquez brièvement…"></textarea>
                                    @error('reason')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="d-flex gap-2 mt-4">
                                    <button type="submit" class="btn btn-primary">
                                        {{ $isEditing ? 'Mettre à jour' : 'Enregistrer (brouillon)' }}
                                    </button>
                                    <button type="button" class="btn btn-secondary"
                                        data-bs-dismiss="modal">Fermer</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>


    {{-- Ouverture automatique de la modale côté JS quand $modalOpen passe à true --}}
    <script>
        document.addEventListener('livewire:initialized', () => {
            Livewire.hook('morph.updated', ({
                component
            }) => {
                const open = component.serverMemo.data.modalOpen;
                const el = document.getElementById('absenceModal');
                if (!el) return;
                const modal = bootstrap.Modal.getOrCreateInstance(el);
                if (open) modal.show();
                else modal.hide();
            });
        });
    </script>
</div>
