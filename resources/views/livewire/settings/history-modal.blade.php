<div wire:ignore.self class="modal fade" id="historyModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header bg-primary bg-gradient">
                <div class="d-flex align-items-center gap-3 flex-grow-1">
                    <h5 class="modal-title text-white mb-0">
                        <i class="ti ti-history me-2"></i>Historique des Soumissions
                    </h5>
                    @if (isset($itemLabel))
                        <span class="badge bg-white text-primary">{{ $itemLabel }}</span>
                    @endif
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show rounded-3 d-flex align-items-center">
                        <i class="ti ti-check me-2"></i>
                        <span>{{ session('success') }}</span>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                {{-- Stats rapides --}}
                @if (isset($stats))
                    <div class="row g-2 mb-4">
                        <div class="col-3">
                            <div class="p-3 rounded-3 bg-primary border text-center">
                                <div class="fs-22 fw-bold text-white">{{ $stats['total'] }}</div>
                                <div class="small text-light">Total</div>
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="p-3 rounded-3 bg-warning bg-opacity-10 border border-warning text-center">
                                <div class="fs-22 fw-bold text-warning">{{ $stats['pending'] }}</div>
                                <div class="small text-muted">En attente</div>
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="p-3 rounded-3 bg-success bg-opacity-10 border border-success text-center">
                                <div class="fs-22 fw-bold text-success">{{ $stats['approved'] }}</div>
                                <div class="small text-muted">Approuvés</div>
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="p-3 rounded-3 bg-danger bg-opacity-10 border border-danger text-center">
                                <div class="fs-22 fw-bold text-danger">{{ $stats['rejected'] }}</div>
                                <div class="small text-muted">Rejetés</div>
                            </div>
                        </div>
                    </div>
                @endif

                {{-- Filtres --}}
                <div class="card border-0 bg-light mb-3">
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label small fw-semibold mb-1">
                                    <i class="ti ti-search me-1"></i>Recherche
                                </label>
                                <input type="text" class="form-control" placeholder="ID, notes..."
                                    wire:model.debounce.400ms="search">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-semibold mb-1">
                                    <i class="ti ti-filter me-1"></i>Statut
                                </label>
                                <select class="form-select" wire:model="filterStatus">
                                    <option value="all">Tous les statuts</option>
                                    <option value="soumis">En attente</option>
                                    <option value="approuvé">Approuvé</option>
                                    <option value="rejeté">Rejeté</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Liste des soumissions --}}
                <div class="list-group list-group-flush">
                    @forelse($submissions as $sub)
                        <div class="list-group-item border rounded-3 mb-3 p-3 hover-item">
                            <div class="row align-items-center">
                                {{-- Info principale --}}
                                <div class="col-md-6">
                                    <div class="d-flex align-items-start gap-3">
                                        {{-- Icône de statut --}}
                                        <div class="flex-shrink-0">
                                            @if ($sub->status === 'approuvé')
                                                <div class="bg-success bg-opacity-10 rounded-circle p-2">
                                                    <i class="ti ti-circle-check text-success fs-22"></i>
                                                </div>
                                            @elseif($sub->status === 'rejeté')
                                                <div class="bg-danger bg-opacity-10 rounded-circle p-2">
                                                    <i class="ti ti-circle-x text-danger fs-22"></i>
                                                </div>
                                            @else
                                                <div class="bg-warning bg-opacity-10 rounded-circle p-2">
                                                    <i class="ti ti-hourglass-high text-warning fs-22"></i>
                                                </div>
                                            @endif
                                        </div>

                                        {{-- Détails --}}
                                        <div class="flex-grow-1">
                                            <div class="d-flex align-items-center gap-2 mb-1">
                                                <span class="badge bg-light text-dark border">
                                                    #{{ Str::limit($sub->id, 8, '') }}
                                                </span>
                                                @if ($sub->status === 'approuvé')
                                                    <span class="badge bg-success-subtle text-success border">
                                                        Approuvé
                                                    </span>
                                                @elseif($sub->status === 'rejeté')
                                                    <span class="badge bg-danger-subtle text-danger border">
                                                        Rejeté
                                                    </span>
                                                @else
                                                    <span class="badge bg-warning-subtle text-warning border">
                                                        En attente
                                                    </span>
                                                @endif
                                            </div>

                                            <div class="small text-muted mb-1">
                                                <i class="ti ti-user me-1"></i>
                                                Soumis par {{ $sub->submittedBy->nom ?? '—' }}
                                                {{ $sub->submittedBy->prenom ?? '' }}
                                            </div>

                                            <div class="small text-muted">
                                                <i class="ti ti-clock me-1"></i>
                                                {{ $sub->submitted_at?->format('d/m/Y H:i') }}
                                            </div>

                                            @if ($sub->periodeItem)
                                                <div class="small text-muted mt-1">
                                                    <i class="ti ti-calendar me-1"></i>
                                                    Période :
                                                    {{ optional($sub->periodeItem->debut_periode)->format('d/m/Y') ?? $sub->periodeItem->debut_periode }}
                                                    —
                                                    {{ optional($sub->periodeItem->fin_periode)->format('d/m/Y') ?? $sub->periodeItem->fin_periode }}
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                {{-- Info validation --}}
                                <div class="col-md-4">
                                    @if (in_array($sub->status, ['approuvé', 'rejeté'], true))
                                        <div class="small">
                                            <div class="text-muted mb-1">
                                                <i class="ti ti-user-check me-1"></i>
                                                Validé par {{ $sub->reviewedBy->nom ?? '—' }}
                                                {{ $sub->reviewedBy->prenom ?? '' }}
                                            </div>
                                            <div class="text-muted">
                                                <i class="ti ti-clock me-1"></i>
                                                {{ $sub->reviewed_at?->format('d/m/Y H:i') }}
                                            </div>
                                            @if ($sub->reviewer_notes)
                                                <div class="mt-2 p-2 rounded-3 bg-light border">
                                                    <div class="fw-semibold small mb-1">Notes :</div>
                                                    <div class="small text-muted">
                                                        {{ Str::limit($sub->reviewer_notes, 80) }}
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                    @else
                                        <div class="text-muted small fst-italic">
                                            En attente de validation...
                                        </div>
                                    @endif
                                </div>

                                {{-- Actions --}}
                                <div class="col-md-2 text-end">
                                    <div class="btn-group btn-group-sm">
                                        @if ($sub->status === 'soumis')
                                            <button class="btn btn-outline-danger"
                                                wire:click="deleteSubmission('{{ $sub->id }}')"
                                                wire:confirm="Êtes-vous sûr de vouloir supprimer cette soumission ?"
                                                title="Supprimer">
                                                <i class="ti ti-trash"></i>
                                            </button>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            {{-- Aperçu des réponses --}}
                            @if ($sub->answers->isNotEmpty())
                                <div class="mt-3 pt-3 border-top">
                                    <div class="small fw-semibold text-muted mb-2">
                                        <i class="ti ti-file-text me-1"></i>Aperçu des données :
                                    </div>
                                    <div class="d-flex flex-wrap gap-2">
                                        @foreach ($sub->answers->take(3) as $ans)
                                            @if ($ans->kind === 'texte')
                                                <span class="badge bg-light text-dark border">
                                                    <i class="ti ti-text-size me-1"></i>
                                                    {{ Str::limit($ans->value_text, 30) }}
                                                </span>
                                            @elseif($ans->kind === 'documents')
                                                <span class="badge bg-info-subtle text-info border">
                                                    <i class="ti ti-paperclip me-1"></i>Document
                                                </span>
                                            @elseif($ans->kind === 'liste')
                                                <span class="badge bg-primary-subtle text-primary border">
                                                    <i class="ti ti-list-check me-1"></i>
                                                    {{ $ans->selectedLabel() ?? $ans->selectedList() }}
                                                </span>
                                            @elseif($ans->kind === 'checkbox')
                                                @php
                                                    // Depuis le passage au radio : "selected" est une STRING (ou null)
                                                    $selected = data_get($ans->value_json, 'selected');
                                                    $label = data_get($ans->value_json, 'label'); // stocké à l’enregistrement
                                                    // Rétro-compat : si jamais ancien format en array
                                                    if (is_array($selected)) {
                                                        $count = count($selected);
                                                        $display = implode(
                                                            ', ',
                                                            (array) (data_get($ans->value_json, 'labels') ?: $selected),
                                                        );
                                                    } else {
                                                        $count = filled($selected) ? 1 : 0;
                                                        $display = $label ?? (string) $selected;
                                                    }
                                                @endphp

                                                <span class="badge bg-secondary-subtle text-secondary border">
                                                    <i class="ti ti-checkbox me-1"></i>
                                                    {{ $count }} sélection(s)
                                                    @if ($count > 0)
                                                        — {{ $display }}
                                                    @endif
                                                </span>
                                            @endif
                                        @endforeach
                                        @if ($sub->answers->count() > 3)
                                            <span class="badge bg-light text-muted border">
                                                +{{ $sub->answers->count() - 3 }} autre(s)
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            @endif
                        </div>
                    @empty
                        <div class="text-center py-5">
                            <i class="ti ti-inbox fs-22 text-muted mb-3 d-block"></i>
                            <h6 class="text-muted">Aucune soumission trouvée</h6>
                            <p class="text-muted small">
                                @if ($filterStatus !== 'all' || $search !== '')
                                    Essayez de modifier vos filtres
                                @else
                                    Aucune soumission n'a encore été effectuée pour cet item
                                @endif
                            </p>
                        </div>
                    @endforelse
                </div>

                {{-- Pagination --}}
                <div class="mt-3">
                    {{ $submissions->links() }}
                </div>
            </div>

            <div class="modal-footer bg-light">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="ti ti-x me-1"></i>Fermer
                </button>
            </div>
        </div>
    </div>
    <style>
        .hover-item {
            transition: all 0.2s ease;
        }

        .hover-item:hover {
            background-color: #f8f9fa;
            transform: translateX(4px);
        }
    </style>
</div>
