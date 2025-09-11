<div class="modal fade custom-modal file-manager-modal upload-modal" id="partageLabelsModal" tabindex="-1" role="dialog"
    aria-labelledby="partageLabelsModal" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h4 class="modal-title" id="partageLabelsModal" style="color: white">
                    Demande d'assistance : {{ Auth::user()->nom }}
                </h4>
                <button class="btn-close btn-lg" data-bs-dismiss="modal" aria-label="Close">
                    <i class="ti ti-x"></i>
                </button>
            </div>


            <div class="card">
                <div class="card-body">
                    <ul class="nav nav-tabs nav-tabs-solid nav-tabs-rounded nav-justified mb-3">
                        <li class="nav-item"><a class="nav-link active shadow rounded"
                                href="#solid-rounded-justified-tab1" data-bs-toggle="tab">Liste des demandes</a></li>
                        <li class="nav-item"><a class="nav-link shadow  rounded" href="#solid-rounded-justified-tab2"
                                data-bs-toggle="tab">Ajouter une demande</a></li>
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane show active" id="solid-rounded-justified-tab1">
                            {{-- resources/views/demande_interventions/index.blade.php --}}
                            @php use Illuminate\Support\Str; @endphp

                            <div class="card border-0 shadow-sm">
                                <div class="card-body">
                                    <div class="row g-3 align-items-end mb-3">
                                        <div class="col-md-5">
                                            <label class="form-label">Rechercher une demande</label>
                                            <input type="text" id="searchInputDemandes"
                                                class="form-control shadow rounded"
                                                placeholder="🔍 Titre, description, entreprise, demandeur...">
                                        </div>

                                        <div class="col-md-3">
                                            <label class="form-label">Filtrer par statut</label>
                                            <select id="filterStatut" class="form-select">
                                                <option value="">Tous</option>
                                                <option value="en_attente">En attente</option>
                                                <option value="en_cours">En cours</option>
                                                <option value="traitee">Traitées</option>
                                                <option value="annulee">Annulées</option>
                                                <option value="en_retard">En retard</option>
                                            </select>
                                        </div>

                                        <div class="col-md-2 d-flex align-items-center">
                                            <div class="form-check form-switch mt-4">
                                                <input class="form-check-input" type="checkbox" id="onlyMineSwitch">
                                                <label class="form-check-label" for="onlyMineSwitch">Mes
                                                    demandes</label>
                                            </div>
                                        </div>

                                        <div class="col-md-2 mt-4 d-flex justify-content-end">
                                            <button id="exportCsvBtn" class="btn btn-outline-primary btn-sm">Exporter
                                                CSV
                                                (filtré)</button>
                                        </div>
                                    </div>

                                    <div class="table-responsive" style="max-height: 600px; overflow-y:auto;">
                                        <table class="table table-striped align-middle" id="tableDemandes">
                                            <thead class="thead-light">
                                                <tr>
                                                    <th>Titre</th>
                                                    <th>Entreprise</th>
                                                    <th>Demandeur</th>
                                                    <th>Échéance</th>
                                                    <th>Statut</th>
                                                    <th>Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($demandes as $d)
                                                    @php
                                                        $ent =
                                                            $d->entreprise->nom_entreprise ??
                                                            ($d->entreprise->nom ?? '-');
                                                        $who = ($d->user->nom ?? '-') . ' ' . ($d->user->prenom ?? '');
                                                        $effective = $d->statut_effectif; // en_attente | en_cours | traitee | annulee | en_retard
                                                        $isFinal =
                                                            in_array($d->statut, ['traitee', 'annulee']) ||
                                                            in_array($effective, ['traitee', 'annulee']);

                                                        // badge principal = statut choisi si non-final, sinon statut effectif (traitee/annulee)
                                                        $display = in_array($d->statut, ['en_attente', 'en_cours'])
                                                            ? $d->statut
                                                            : $effective;
                                                        $badgeMap = [
                                                            'en_attente' => 'secondary',
                                                            'en_cours' => 'info',
                                                            'traitee' => 'success',
                                                            'annulee' => 'dark',
                                                            'en_retard' => 'danger',
                                                        ];
                                                        $badge = $badgeMap[$display] ?? 'secondary';
                                                        $isOverdueNow =
                                                            $effective === 'en_retard' &&
                                                            in_array($display, ['en_attente', 'en_cours']); // pas pour final
                                                    @endphp
                                                    @php
                                                        // "statut affiché" = le statut choisi par l’utilisateur (attente / en cours),
                                                        // sinon on garde le statut effectif (traitee/annulee).
                                                        $display = in_array($d->statut, ['en_attente', 'en_cours'])
                                                            ? $d->statut
                                                            : $d->statut_effectif;

                                                        $badgeMap = [
                                                            'en_attente' => 'secondary',
                                                            'en_cours' => 'info',
                                                            'traitee' => 'success',
                                                            'annulee' => 'dark',
                                                            'en_retard' => 'danger', // utilisé pour le petit badge "Retard", pas pour $display
                                                        ];
                                                        $badge = $badgeMap[$display] ?? 'secondary';

                                                        $isOverdue = $d->statut_effectif === 'en_retard';
                                                    @endphp
                                                    <tr id="demande-row-{{ $d->id }}"
                                                        data-search="{{ strtolower(trim($d->titre . ' ' . $d->description . ' ' . $ent . ' ' . $who)) }}"
                                                        data-statut-effectif="{{ $effective }}"
                                                        data-user-id="{{ $d->user_id }}">
                                                        <td class="fw-semibold">{{ $d->titre }}</td>
                                                        <td>{{ $ent }}
                                                            ({{ (Auth::id()===$d->user_id) ? 'interne' : 'externe' }})

                                                        </td>
                                                        <td>{{ $who }}</td>
                                                        <td>
                                                            <div>{{ $d->date_souhaite?->format('d/m/Y') ?? '-' }}</div>
                                                            <small
                                                                class="text-muted">{{ $d->deadline_label ?? '' }}</small>
                                                        </td>
                                                        <td>
                                                            <span class="badge bg-{{ $badge }} fw-normal"
                                                                data-status-badge>{{ Str::headline($display) }}</span>
                                                            @if ($isOverdueNow)
                                                                <span class="badge bg-danger ms-2"
                                                                    data-overdue-flag>Retard</span>
                                                            @endif
                                                        </td>
                                                        <td>
                                                            @if (!$isFinal)
                                                                <div class="btn-group btn-group-sm" data-actions>
                                                                    @if ($d->user->id !== Auth::id() && $d->entreprise_id === $entreprise_id)
                                                                        <button
                                                                            class="btn btn-outline-secondary btn-set-status"
                                                                            data-id="{{ $d->id }}"
                                                                            data-status="en_attente">Attente</button>
                                                                        <button
                                                                            class="btn btn-outline-info btn-set-status"
                                                                            data-id="{{ $d->id }}"
                                                                            data-status="en_cours">En cours</button>
                                                                        <button
                                                                            class="btn btn-outline-success btn-set-status"
                                                                            data-id="{{ $d->id }}"
                                                                            data-status="traitee">Traiter</button>
                                                                    @endif
                                                                    @if ($d->user->id === Auth::id() && $effective !== 'traitee')
                                                                        <button
                                                                            class="btn btn-outline-dark btn-set-status"
                                                                            data-id="{{ $d->id }}"
                                                                            data-status="annulee">Annuler</button>
                                                                    @endif
                                                                </div>
                                                            @else
                                                                {{-- Rien : tâches finales = plus d’actions --}}
                                                            @endif

                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>

                                    <div class="mt-3">
                                        {{ $demandes->links() }}
                                    </div>
                                </div>
                            </div>

                            {{-- Toast --}}
                            <div id="notification-toast"
                                class="toast align-items-center text-white bg-success border-0 position-fixed top-0 end-0 m-3"
                                role="alert" aria-live="assertive" aria-atomic="true" style="z-index:1056;">
                                <div class="d-flex">
                                    <div class="toast-body"></div>
                                    <button type="button" class="btn-close btn-close-white me-2 m-auto"
                                        data-bs-dismiss="toast" aria-label="Close"></button>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane" id="solid-rounded-justified-tab2">
                            <form action="{{ route('envoi_demande') }}" method="POST" enctype="multipart/form-data"
                                class="needs-validation" novalidate>
                                @csrf
                                <div class="row p-4">
                                    <div class="col-md-6 mb-2">
                                        <div class="form-group" id="titre">
                                            <label class="form-label">Titre</label>
                                            <div class="input-group date">
                                                <input type="text" class="form-control" name="titre"
                                                    value="{{ old('titre') }}" required autocomplete="off">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-2">
                                        <div class="form-group" id="group_date_naissance">
                                            <label class="form-label">Societe assignée</label>
                                            <div class="input-group">
                                                <select class="select2 form-control" name="entreprise_id"
                                                    style="width: 100%;" required>
                                                    <option value="">Veuillez selectionner</option>
                                                    @foreach ($entreprises as $entreprise)
                                                        @if (
                                                            $entreprise->code_entreprise == 'BFEV' ||
                                                                $entreprise->code_entreprise == 'YODI' ||
                                                                $entreprise->code_entreprise == 'YOD')
                                                            <option value="{{ $entreprise->id }}"
                                                                {{ old('entreprise_id') == $entreprise->id || $entreprise->code_entreprise == 'BFEV' ? 'selected' : '' }}>
                                                                {{ $entreprise->nom_entreprise }}
                                                            </option>
                                                        @endif
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group col-md-12  mb-2">
                                        <label class="form-label">Description du besoin</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fa fa-tasks"></i></span>
                                            <textarea name="description" class="form-control" required>{{ old('description') }}</textarea>
                                        </div>
                                    </div>
                                    <div class="col-md-6  mb-2">
                                        <div class="form-group" id="date_souhaite">
                                            <label class="form-label">Délai souhaité</label>
                                            <div class="input-group date">
                                                <span class="input-group-text"><i class="fa fa-calendar"></i></span>
                                                <input type="date" class="form-control" name="date_souhaite"
                                                    value="{{ old('date_souhaite') }}" required autocomplete="off">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6  mb-2">
                                        <label class="form-label">Pièce jointes</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fa fa-id-card"></i></span>
                                            <input type="file" name="piece_jointe" class="form-control"
                                                accept="image/*,.pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.odt,.ods,.odp,.rtf" />

                                        </div>
                                    </div>

                                    <div class="modal-footer">
                                        <button type="reset" class="btn btn-dark">Annuler</button>
                                        <button type="submit" class="btn btn-primary btn-action"
                                            data-loader-target="loader-modif">Enregistrer
                                            l'utilisateur</button>
                                        <button type="button" id="loader-modif" class="btn btn-outline-primary"
                                            style="display: none;" disabled>
                                            <i class="fas fa-spinner fa-spin me-2"></i>Enregistrement
                                            en cours...
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    const input = document.querySelector('input[name="piece_jointe"]');
                    if (!input) return;

                    const allowed = [
                        'pdf', 'doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx', 'odt', 'ods', 'odp', 'rtf',
                        'jpg', 'jpeg', 'png', 'webp', 'gif'
                    ];

                    input.addEventListener('change', function() {
                        const f = this.files?.[0];
                        if (!f) return;
                        const ext = (f.name.split('.').pop() || '').toLowerCase();
                        if (!allowed.includes(ext)) {
                            alert(
                                "Format non autorisé. Autorisés: images (jpg, jpeg, png, webp, gif) et documents (pdf, docx, xlsx, pptx, odt, ...)."
                            );
                            this.value = ''; // reset
                        }
                    });
                });
            </script>


            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    const CURRENT_USER_ID = @json(Auth::id());

                    const toastEl = document.getElementById('notification-toast');
                    const toast = new bootstrap.Toast(toastEl);

                    function showToast(msg, isError = false) {
                        toastEl.querySelector('.toast-body').textContent = msg;
                        toastEl.classList.toggle('bg-success', !isError);
                        toastEl.classList.toggle('bg-danger', isError);
                        toast.show();
                    }

                    // --- Filtres & recherche (inclut "Mes demandes")
                    const input = document.getElementById('searchInputDemandes');
                    const filter = document.getElementById('filterStatut');
                    const mineTgl = document.getElementById('onlyMineSwitch');
                    const rows = Array.from(document.querySelectorAll('#tableDemandes tbody tr'));

                    function applyFilters() {
                        const q = (input.value || '').toLowerCase().trim();
                        const stat = (filter.value || '')
                            .trim(); // '' | en_attente | en_cours | traitee | annulee | en_retard
                        const onlyMine = !!mineTgl?.checked;

                        rows.forEach(row => {
                            const hay = row.getAttribute('data-search') || '';
                            const st = row.getAttribute('data-statut-effectif') || '';
                            const uid = row.getAttribute('data-user-id') || '';
                            const matchText = !q || hay.includes(q);
                            const matchStat = !stat || st === stat;
                            const matchMine = !onlyMine || (uid && String(uid) === String(CURRENT_USER_ID));

                            row.style.display = (matchText && matchStat && matchMine) ? '' : 'none';
                        });
                    }

                    input?.addEventListener('input', applyFilters);
                    filter?.addEventListener('change', applyFilters);
                    mineTgl?.addEventListener('change', applyFilters);

                    // --- Mise à jour du badge (helper)
                    function badgeClassFor(status) {
                        switch (status) {
                            case 'en_attente':
                                return 'bg-secondary';
                            case 'en_cours':
                                return 'bg-info';
                            case 'traitee':
                                return 'bg-success';
                            case 'annulee':
                                return 'bg-dark';
                            case 'en_retard':
                                return 'bg-danger';
                            default:
                                return 'bg-secondary';
                        }
                    }

                    // =========================
                    //  Effet de chargement + anti double-clic
                    // =========================
                    const inflightByDemande = new Map(); // idDemande -> true si requête en cours

                    function setLoading(btn, loading = true) {
                        if (loading) {
                            const w = btn.getBoundingClientRect().width; // fige la largeur
                            btn.style.width = w + 'px';
                            btn.dataset._label = btn.innerHTML;
                            btn.innerHTML =
                                '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>' +
                                (btn.getAttribute('data-loading-text') || '…');
                            btn.disabled = true;
                            btn.classList.add('disabled');
                            btn.style.pointerEvents = 'none';
                        } else {
                            if (btn.dataset._label) btn.innerHTML = btn.dataset._label;
                            btn.style.width = '';
                            btn.disabled = false;
                            btn.classList.remove('disabled');
                            btn.style.pointerEvents = '';
                            delete btn.dataset._label;
                        }
                    }

                    function toggleGroup(group, disabled) {
                        if (!group) return;
                        group.querySelectorAll('.btn-set-status').forEach(b => {
                            if (disabled) {
                                b.disabled = true;
                                b.style.pointerEvents = 'none';
                            } else {
                                if (b.getAttribute('data-loading') !== '1') {
                                    b.disabled = false;
                                    b.style.pointerEvents = '';
                                }
                            }
                        });
                    }

                    // --- Actions statut (AJAX) avec loader
                    document.querySelectorAll('.btn-set-status').forEach(btn => {
                        btn.addEventListener('click', function() {
                            const id = this.dataset.id;
                            const newStatus = this.dataset.status;
                            if (!id || !newStatus) return;

                            // Empêche plusieurs clics sur la même demande pendant la requête
                            if (inflightByDemande.get(id)) return;
                            inflightByDemande.set(id, true);

                            const group = this.closest('[data-actions]');
                            const clickedBtn = this;

                            // Marqueur pour ne pas réactiver ce bouton trop tôt
                            clickedBtn.setAttribute('data-loading', '1');

                            // Désactive tout le groupe + spinner sur le bouton cliqué
                            toggleGroup(group, true);
                            setLoading(clickedBtn, true);

                            fetch(`{{ url('/demande-interventions') }}/${id}/status`, {
                                    method: 'PATCH',
                                    headers: {
                                        'Content-Type': 'application/json',
                                        'Accept': 'application/json',
                                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                    },
                                    body: JSON.stringify({
                                        statut: newStatus
                                    })
                                })
                                .then(r => r.json().then(body => ({
                                    status: r.status,
                                    body
                                })))
                                .then(({
                                    status,
                                    body
                                }) => {
                                    if (status === 200 && body.success) {
                                        showToast(body.message || 'Statut mis à jour.');

                                        const row = document.getElementById(`demande-row-${id}`);
                                        if (row) {
                                            // garder l'effectif pour les filtres (en_retard, etc.)
                                            row.setAttribute('data-statut-effectif', body.demande
                                                .statut_effectif || newStatus);

                                            // 1) Badge principal = toujours le statut cliqué
                                            const badge = row.querySelector('[data-status-badge]');
                                            if (badge) {
                                                badge.className =
                                                    `badge ${badgeClassFor(newStatus)} fw-normal`;
                                                badge.textContent = newStatus.replace('_', ' ').replace(
                                                    /\b\w/g, c => c.toUpperCase());
                                            }

                                            // 2) Indicateur "Retard" : jamais pour Traitee/Annulee
                                            const overdueFlag = row.querySelector(
                                                '[data-overdue-flag]');
                                            const shouldShowOverdue =
                                                (body.demande.statut_effectif === 'en_retard') &&
                                                (newStatus === 'en_attente' || newStatus ===
                                                    'en_cours');
                                            if (overdueFlag) {
                                                overdueFlag.style.display = shouldShowOverdue ? '' :
                                                    'none';
                                            } else if (shouldShowOverdue) {
                                                const flag = document.createElement('span');
                                                flag.className = 'badge bg-danger ms-2';
                                                flag.setAttribute('data-overdue-flag', '');
                                                flag.textContent = 'Retard';
                                                const statusCell = badge?.parentElement || row
                                                    .querySelector('td:nth-child(5)');
                                                statusCell?.appendChild(flag);
                                            }

                                            // 3) Échéance : cacher si final
                                            const deadlineSmall = row.querySelector('[data-deadline]');
                                            if (deadlineSmall) {
                                                const hideDeadline = (newStatus === 'traitee' ||
                                                    newStatus === 'annulee');
                                                if (hideDeadline) {
                                                    deadlineSmall.textContent = '';
                                                    deadlineSmall.style.display = 'none';
                                                } else {
                                                    const text = (body.demande.deadline_label || '')
                                                        .trim();
                                                    deadlineSmall.textContent = text;
                                                    deadlineSmall.style.display = text ? '' : 'none';
                                                }
                                            }

                                            // 4) Actions : retirer les boutons si statut final
                                            if (newStatus === 'traitee' || newStatus === 'annulee') {
                                                const actions = row.querySelector('[data-actions]');
                                                if (actions) actions.remove();
                                            }
                                        }

                                        // Re-filtre si besoin
                                        applyFilters();
                                    } else if (status === 422) {
                                        showToast('Statut invalide.', true);
                                    } else {
                                        showToast(body?.message || 'Erreur lors de la mise à jour.',
                                            true);
                                    }
                                })
                                .catch(() => showToast('Erreur de connexion.', true))
                                .finally(() => {
                                    inflightByDemande.delete(id);
                                    // Retirer le loader
                                    setLoading(clickedBtn, false);
                                    clickedBtn.removeAttribute('data-loading');
                                    // Réactiver le groupe si toujours présent
                                    if (group && document.body.contains(group)) {
                                        toggleGroup(group, false);
                                    }
                                });
                        });
                    });

                    // --- Export CSV (lignes visibles de la page courante)
                    const exportBtn = document.getElementById('exportCsvBtn');
                    exportBtn?.addEventListener('click', function() {
                        // Applique les filtres avant export
                        applyFilters();

                        const visibleRows = Array.from(document.querySelectorAll('#tableDemandes tbody tr'))
                            .filter(r => r.style.display !== 'none');

                        if (!visibleRows.length) {
                            showToast('Aucune demande à exporter pour les filtres actuels.', true);
                            return;
                        }

                        // En-têtes (on prend les 5 premières colonnes, sans "Actions")
                        const headers = ['Titre', 'Entreprise', 'Demandeur', 'Échéance', 'Statut'];

                        function csvEscape(v) {
                            const s = (v ?? '').toString().replace(/\r?\n|\r/g, ' ').trim();
                            return /[",;]/.test(s) ? `"${s.replace(/"/g,'""')}"` : s;
                        }

                        const lines = [];
                        lines.push(headers.map(csvEscape).join(','));

                        visibleRows.forEach(row => {
                            const cells = row.querySelectorAll('td');
                            const titre = cells[0]?.innerText ?? '';
                            const entreprise = cells[1]?.innerText ?? '';
                            const demandeur = cells[2]?.innerText ?? '';
                            const echeance = cells[3]?.querySelector('div')?.innerText ?? cells[3]
                                ?.innerText ?? '';
                            const statut = cells[4]?.innerText ?? '';
                            lines.push([titre, entreprise, demandeur, echeance, statut].map(csvEscape).join(
                                ','));
                        });

                        const blob = new Blob([lines.join('\n')], {
                            type: 'text/csv;charset=utf-8;'
                        });
                        const url = URL.createObjectURL(blob);
                        const a = document.createElement('a');
                        const now = new Date();
                        const stamp = now.toISOString().slice(0, 19).replace(/[:T]/g, '-');
                        a.href = url;
                        a.download = `demandes_filtrees_${stamp}.csv`;
                        document.body.appendChild(a);
                        a.click();
                        document.body.removeChild(a);
                        URL.revokeObjectURL(url);
                    });

                    // Premier rendu
                    applyFilters();
                });
            </script>

        </div>
    </div>
</div>
