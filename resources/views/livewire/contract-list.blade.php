<div>
    <div class="card">
        <div class="card-header">
            <div class="row align-items-center">
                <div class="col-sm-4">
                    <div class="icon-form mb-3 mb-sm-0">
                        <span class="form-icon"><i class="ti ti-search"></i></span>
                        <input type="text" wire:model.live.debounce.300ms="search" class="form-control"
                            placeholder="Rechercher un utilisateur...">
                    </div>
                </div>
                <div class="col-sm-8">
                    <div class="d-flex align-items-center flex-wrap row-gap-2 justify-content-sm-end">
                        <!-- Filtre par statut -->
                        <div class="dropdown me-2">
                            <a href="javascript:void(0);" class="dropdown-toggle" data-bs-toggle="dropdown">
                                <i class="ti ti-filter me-2"></i>
                                Filtre par statut
                            </a>
                            <div class="dropdown-menu dropdown-menu-end">
                                <ul>
                                    <li>
                                        <a href="javascript:void(0);" class="dropdown-item"
                                            wire:click="$set('statutFilter', '')">
                                            <i class="ti ti-reload me-1"></i> Tous
                                        </a>
                                    </li>
                                    <li>
                                        <a href="javascript:void(0);" class="dropdown-item text-success"
                                            wire:click="$set('statutFilter', 'actif')">
                                            <i class="ti ti-check me-1"></i> Actifs
                                        </a>
                                    </li>
                                    <li>
                                        <a href="javascript:void(0);" class="dropdown-item text-info"
                                            wire:click="$set('statutFilter', 'termine')">
                                            <i class="ti ti-clock me-1"></i> Terminés
                                        </a>
                                    </li>
                                    <li>
                                        <a href="javascript:void(0);" class="dropdown-item text-warning"
                                            wire:click="$set('statutFilter', 'suspendu')">
                                            <i class="ti ti-alert-triangle me-1"></i> Suspendus
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>

                        <!-- Filtre par type de contrat -->
                        <div class="dropdown me-2">
                            <a href="javascript:void(0);" class="dropdown-toggle text-primary"
                                data-bs-toggle="dropdown">
                                <i class="ti ti-file me-2"></i>
                                Type de contrat
                            </a>
                            <div class="dropdown-menu dropdown-menu-end">
                                <ul>
                                    <li>
                                        <a href="javascript:void(0);" class="dropdown-item"
                                            wire:click="$set('typeContratFilter', '')">
                                            <i class="ti ti-reload me-1"></i> Tous
                                        </a>
                                    </li>
                                    <li>
                                        <a href="javascript:void(0);" class="dropdown-item"
                                            wire:click="$set('typeContratFilter', 'CDI')">
                                            CDI
                                        </a>
                                    </li>
                                    <li>
                                        <a href="javascript:void(0);" class="dropdown-item"
                                            wire:click="$set('typeContratFilter', 'CDD')">
                                            CDD
                                        </a>
                                    </li>
                                    <li>
                                        <a href="javascript:void(0);" class="dropdown-item"
                                            wire:click="$set('typeContratFilter', 'Stage')">
                                            Stage
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>

                        @if (!$userId)
                            <button type="button" class="btn btn-primary"
                                wire:click="$dispatch('openContractModal', { mode: 'create' })">
                                <i class="ti ti-plus me-2"></i>Nouveau contrat
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Employé</th>
                            <th>Type</th>
                            <th>Date début</th>
                            <th>Date fin</th>
                            <th>Salaire</th>
                            <th>Statut</th>
                            <th>Version</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($contracts as $contract)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="me-3">
                                            @if ($contract->user->photo)
                                                <img src="{{ asset('storage/' . $contract->user->photo) }}"
                                                    class="img-fluid rounded-circle border" alt="Photo de profil"
                                                    style="width: 40px; height: 40px;">
                                            @else
                                                <img src="{{ asset('src/images/user.jpg') }}"
                                                    class="img-fluid rounded-circle border" alt="Photo de profil"
                                                    style="width: 40px; height: 40px;">
                                            @endif
                                        </div>
                                        <div>
                                            <strong>{{ $contract->user->prenom }} {{ $contract->user->nom }}</strong>
                                            <br>
                                            <small class="text-muted">{{ $contract->user->matricule }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge bg-info">{{ $contract->type_contrat }}</span>
                                </td>
                                <td>{{ $contract->date_debut ? $contract->date_debut->format('d/m/Y') : '-' }}</td>
                                <td>
                                    @if ($contract->date_fin)
                                        {{ $contract->date_fin->format('d/m/Y') }}
                                        @if ($contract->is_active && $contract->jours_restants !== null)
                                            <br>
                                            <small class="text-muted">
                                                @if ($contract->jours_restants > 0)
                                                    <span class="text-warning">({{ $contract->jours_restants }}
                                                        jours)</span>
                                                @else
                                                    <span class="text-danger">(Expiré)</span>
                                                @endif
                                            </small>
                                        @endif
                                    @else
                                        <span class="text-muted">Indéterminé</span>
                                    @endif
                                </td>
                                <td>
                                    @if ($contract->salaire_base)
                                        {{ number_format($contract->salaire_base, 0, ',', ' ') }} FCFA
                                    @else
                                        -
                                    @endif
                                </td>
                                <td>
                                    <span class="badge {{ $contract->statut_badge }}">
                                        {{ $contract->statut_label }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge bg-secondary">v{{ $contract->version }}</span>
                                    @if ($contract->parent_contract_id)
                                        <i class="ti ti-refresh text-primary" title="Renouvelé"></i>
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group dropstart my-1">
                                        <button type="button" class="btn btn-primary dropdown-toggle mb-0"
                                            data-bs-toggle="dropdown" aria-expanded="false">
                                            Actions
                                        </button>
                                        <div class="dropdown-menu dropdown-menu-end">
                                            <a href="javascript:void(0);" class="dropdown-item"
                                                wire:click="$dispatch('openContractModal', { mode: 'view', contractId: '{{ $contract->id }}' })">
                                                <i class="ti ti-eye me-2"></i> Voir
                                            </a>
                                            @if ($contract->estModifiable())
                                                <a href="javascript:void(0);" class="dropdown-item"
                                                    wire:click="$dispatch('openContractModal', { mode: 'edit', contractId: '{{ $contract->id }}' })">
                                                    <i class="ti ti-edit me-2"></i> Modifier
                                                </a>
                                            @endif
                                            <a href="javascript:void(0);" class="dropdown-item"
                                                wire:click="$dispatch('openContractModal', { mode: 'history', contractId: '{{ $contract->id }}' })">
                                                <i class="ti ti-history me-2"></i> Historique
                                            </a>
                                            @if ($contract->estRenouvelable())
                                                <a href="javascript:void(0);" class="dropdown-item text-primary"
                                                    wire:click="$dispatch('openContractModal', { mode: 'renew', contractId: '{{ $contract->id }}' })">
                                                    <i class="ti ti-refresh me-2"></i> Renouveler
                                                </a>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-4">
                                    <i class="ti ti-file-off text-muted" style="font-size: 3rem;"></i>
                                    <p class="text-muted mt-2">Aucun contrat trouvé</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-3">
                {{ $contracts->links() }}
            </div>
        </div>
    </div>
</div>
