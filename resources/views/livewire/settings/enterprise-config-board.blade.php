<div>
    {{-- Header Domaines + bouton wizard (ta modale existe déjà) --}}
    <div class="card border-0 ">
        <div class="card-body pb-0 pt-0 px-2 my-2">
            <div class="border-bottom mb-3 pb-3 d-flex align-items-center justify-content-between flex-wrap gap-2">
                <h5 class="mb-0 fs-17">Domaines</h5>
                <a href="javascript:void(0)" class="btn btn-primary btn-sm" data-bs-toggle="modal"
                    data-bs-target="#wizardConfigModal">
                    <i class="ti ti-settings me-1"></i> Lancer l'assistant de configuration
                </a>
            </div>

            <ul class="nav nav-tabs nav-bordered nav-bordered-primary">
                @forelse($domains as $d)
                    <li class="nav-item me-3">
                        <a href="javascript:void(0)"
                            class="nav-link p-2 {{ $selectedDomainId === $d['id'] ? 'active' : '' }}"
                            wire:click="selectDomain('{{ $d['id'] }}')" wire:loading.attr="disabled"
                            wire:target="selectDomain">
                            <i class="ti ti-folder me-2"></i>{{ $d['label'] }}
                        </a>
                    </li>
                @empty
                    <li class="nav-item"><span class="text-muted">Aucun domaine configuré.</span></li>
                @endforelse
            </ul>
        </div>
    </div>

    <div class="row">
        {{-- CATEGORIES (gauche) --}}
        <div class="col-xl-3 col-lg-12">
            <div class="card mb-3">
                <div class="card-body">
                    <h5 class="mb-3 fs-17">Categories</h5>

                    <input type="text" class="form-control mb-2" placeholder="Rechercher…"
                        wire:model.debounce.300ms="searchCategory">

                    <div class="list-group list-group-flush">
                        @forelse ($categories as $c)
                            <a href="javascript:void(0)"
                                class="d-block p-2 fw-medium {{ $selectedCategoryId === $c['id'] ? 'active' : '' }}"
                                wire:click="selectCategory('{{ $c['id'] }}')" wire:loading.attr="disabled"
                                wire:target="selectCategory">
                                {{ $c['label'] }}
                            </a>
                        @empty
                            <span class="text-muted">Aucune catégorie.</span>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        {{-- ITEMS (droite) --}}
        <div class="col-xl-9 col-lg-12">
            <div class="card">
                <div class="card-body">
                    <div
                        class="border-bottom mb-3 pb-3 d-flex align-items-center justify-content-between flex-wrap gap-2">
                        <h5 class="mb-0 fs-17">Items</h5>
                        <input type="text" class="form-control w-auto" style="min-width:260px"
                            placeholder="Rechercher…" wire:model.debounce.300ms="searchItem">
                    </div>

                    @forelse ($items as $i)
                        @php
                            $hasPeriods = (int) $i->periodes_count_for_ent > 0;
                            $isActive = (bool) $i->periode_active;
                            $isExpired =
                                $hasPeriods &&
                                !$isActive &&
                                $i->latest_fin &&
                                \Carbon\Carbon::parse($i->latest_fin)->isPast();
                            $lastStatus = $i->latest_submission_status; // 'soumis' | 'approuvé' | 'rejeté' | null
                            $pendingId = $i->latest_pending_submission_id; // null si aucun "soumis"
                            $reviewNotes = $i->latest_submission_review_notes;
                            $lastReviewed = $i->latest_submission_reviewed_at
                                ? \Carbon\Carbon::parse($i->latest_submission_reviewed_at)->format('d/m/Y H:i')
                                : null;
                            $lastSubmitted = $i->latest_submission_submitted_at
                                ? \Carbon\Carbon::parse($i->latest_submission_submitted_at)->format('d/m/Y H:i')
                                : null;
                            $latestRange =
                                $i->latest_debut && $i->latest_fin
                                    ? \Carbon\Carbon::parse($i->latest_debut)->translatedFormat('d F Y') .
                                        ' → ' .
                                        \Carbon\Carbon::parse($i->latest_fin)->translatedFormat('d F Y')
                                    : null;
                        @endphp

                        <div class="border rounded shadow-sm p-3 mb-3">
                            <div class="row gy-3">
                                {{-- Col gauche: libellé + badges --}}
                                <div class="col-md-4">
                                    <div class="d-flex align-items-center">
                                        <span class="avatar avatar-lg border me-2 flex-shrink-0">
                                            <img src="{{ asset('assets/img/icons/mail-01.svg') }}"
                                                class="w-auto h-auto rounded-0" alt="">
                                        </span>
                                        <div>
                                            <h6 class="fs-14 fw-medium mb-1">{{ $i->nom_item }}</h6>

                                            {{-- Badge Période --}}
                                            @if (!$hasPeriods)
                                                <span class="badge bg-warning">Aucune période</span>
                                            @elseif ($isActive)
                                                <span class="badge bg-success">Période active</span>
                                            @elseif ($isExpired)
                                                <span class="badge bg-danger">Période expirée</span>
                                            @else
                                                <span class="badge bg-dark">Hors periode</span>
                                            @endif

                                            {{-- Badge Conformité (dernière décision) --}}
                                            @if ($lastStatus === 'approuvé')
                                                <span class="badge bg-success ms-1">Conformité approuvée</span>
                                            @elseif ($lastStatus === 'rejeté')
                                                <span class="badge bg-danger ms-1" data-bs-toggle="popover"
                                                    data-bs-placement="top" data-bs-trigger="hover focus"
                                                    data-bs-content="{{ $reviewNotes ? e($reviewNotes) : 'Aucune justification fournie.' }}">
                                                    Conformité rejetée
                                                </span>
                                            @elseif ($lastStatus === 'soumis')
                                                <span class="badge bg-info text-dark ms-1">En attente de
                                                    validation</span>
                                            @else
                                                <span class="badge bg-secondary ms-1">Jamais évalué</span>
                                            @endif
                                        </div>
                                    </div>

                                    {{-- Dates de période la plus récente --}}
                                    @if ($latestRange)
                                        <div class="mt-2 small text-muted"><span
                                                class="text-primary">{{ $latestRange }}</span>
                                        </div>
                                    @endif


                                </div>

                                {{-- Col droite: actions --}}
                                <div class="col-md-8">
                                    <div class="d-flex align-items-center justify-content-between">
                                        <div class="d-flex align-items-center gap-2 flex-wrap">

                                            {{-- Description toggle --}}
                                            @if (!empty($i->description))
                                                <a href="javascript:void(0);" class="border-end fs-18 pe-3 me-2"
                                                    data-bs-toggle="collapse"
                                                    data-bs-target="#desc-{{ $i->id }}"><i
                                                        class="ti ti-info-circle-filled me-1"></i></a>
                                            @endif
                                            @if ($lastStatus !== 'approuvé' && $hasPeriods)
                                                {{-- Déclarer / Modifier (soumission) --}}
                                                @if ($i->latest_pending_submission_id)
                                                    <a href="#" class="btn btn-light" data-bs-toggle="modal"
                                                        data-bs-target="#modalSubmitCompliance-{{ $i->id }}">
                                                        <i class="ti ti-edit me-1"></i>Modifier la soumission
                                                    </a>
                                                @else
                                                    <a href="#" class="btn btn-light" data-bs-toggle="modal"
                                                        data-bs-target="#modalSubmitCompliance-{{ $i->id }}">
                                                        <i class="ti ti-tool me-1"></i>Conformité
                                                    </a>
                                                @endif

                                                {{-- Évaluer si un pending existe --}}
                                                @if ($pendingId)
                                                    <a href="#" class="btn btn-outline-secondary"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#modalReviewCompliance-{{ $pendingId }}">
                                                        <i class="ti ti-clipboard-check me-1"></i>Évaluer
                                                    </a>
                                                @else
                                                    <button class="btn btn-outline-secondary" disabled>
                                                        <i class="ti ti-clipboard-x me-1"></i>Évaluer
                                                    </button>
                                                @endif
                                            @endif

                                            {{-- Périodes --}}
                                            <button class="btn btn-outline-secondary" data-bs-toggle="modal"
                                                data-bs-target="#periodesModal"
                                                wire:click="$dispatch('open-periode-manager', { id: '{{ $i->id }}' })">
                                                <i class="ti ti-calendar-stats me-1"></i>Périodes
                                            </button>

                                            {{-- Historique --}}
                                            <a href="#" class="btn btn-outline-light" data-bs-toggle="modal"
                                                data-bs-target="#modalHistory-{{ $i->id }}">
                                                <i class="ti ti-history me-1"></i>Historique
                                            </a>
                                        </div>

                                        {{-- Switch activation pour l’entreprise --}}
                                        <div class="form-check form-switch ps-0">
                                            @if ($lastStatus === 'approuvé')
                                                <input class="form-check-input ms-0 mt-0" type="checkbox"
                                                    role="switch" @checked(true)
                                                    wire:click="toggleItem('{{ $i->id }}')"
                                                    wire:loading.attr="disabled" wire:target="toggleItem">
                                            @else
                                                <input class="form-check-input ms-0 mt-0" type="checkbox"
                                                    role="switch" wire:click="toggleItem('{{ $i->id }}')">
                                            @endif
                                        </div>
                                    </div>
                                    {{-- Méta conformité --}}
                                    <ul class="list-unstyled small text-muted mt-3 mb-0 d-flex">
                                        @if ($lastSubmitted)
                                            <li class="me-3"><i class="ti ti-send me-1"></i>Dernière déclaration :
                                                {{ $lastSubmitted }}</li>
                                        @endif
                                        @if ($lastStatus && $lastStatus !== 'soumis')
                                            <li class="me-3">
                                                <i class="ti ti-checkup-list me-1"></i>Dernière décision :
                                                <span class="text-capitalize">{{ $lastStatus }}</span>
                                                @if ($lastReviewed)
                                                    ({{ $lastReviewed }})
                                                @endif
                                            </li>
                                        @endif
                                        @if ($lastStatus === 'rejeté' && $reviewNotes)
                                            <li><i class="ti ti-message-2 me-1"></i>Justification :
                                                <em>{{ $reviewNotes }}</em>
                                            </li>
                                        @endif
                                    </ul>
                                    @if (!empty($i->description))
                                        <div class="collapse mt-2" id="desc-{{ $i->id }}">
                                            <p class="mb-0 text-muted">{{ $i->description }}</p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="modal fade" id="modalSubmitCompliance-{{ $i->id }}" tabindex="-1"
                            wire:ignore.self>
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header bg-primary text-white">
                                        <h5 class="modal-title text-white">
                                            Conformité — {{ $i->nom_item }}
                                        </h5>
                                        <button type="button" class="btn-close bg-light"
                                            data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        @livewire(
                                            'settings.submit-form',
                                            [
                                                'itemId' => $i->id,
                                                'submissionId' => $i->latest_pending_submission_id, // null si pas de pending
                                            ],
                                            key('submit-' . $i->id . '-' . $i->latest_pending_submission_id)
                                        )
                                    </div>
                                </div>
                            </div>
                        </div>


                        {{-- Modale Évaluation : rendue seulement si on a un pending --}}
                        @if ($i->latest_pending_submission_id)
                            <div class="modal fade" id="modalReviewCompliance-{{ $i->latest_pending_submission_id }}"
                                tabindex="-1" wire:ignore.self>
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <div class="modal-header bg-secondary text-white">
                                            <h5 class="modal-title">Évaluation de conformité</h5>
                                            <button type="button" class="btn-close bg-light"
                                                data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            @livewire('Settings.review-form', ['submissionId' => $i->latest_pending_submission_id], key('review-' . $i->latest_pending_submission_id))
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                        {{-- ⚡ Appel du composant Livewire HistoryModal pour CHAQUE item --}}
                        @livewire('settings.history-modal', ['item' => $i], key('history-' . $i->id))
                    @empty
                        <div class="text-muted">Aucun item.</div>
                    @endforelse

                </div>
            </div>
        </div>

    </div>
</div>
<script>
    document.addEventListener('DOMContentLoaded', () => {
        document.querySelectorAll('[data-bs-toggle="popover"]').forEach(el => {
            new bootstrap.Popover(el);
        });
    });
</script>
