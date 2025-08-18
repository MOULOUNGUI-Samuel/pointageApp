<div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasAddVariable" aria-labelledby="offcanvasAddVariableLabel">
    <div class="offcanvas-header">
        <h5 class="offcanvas-title" id="offcanvasAddVariableLabel">Enregistrer une variable</h5>
        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Fermer"></button>
    </div>

    <div class="offcanvas-body">
        <form id="formAddVariable" onsubmit="event.preventDefault(); addVariable();">
            <div class="mb-3">
                <label class="form-label">Nom de la variable</label>
                <input type="text" id="newVariableName" class="form-control" placeholder="Prime de performance" autocomplete="off">
                <div class="invalid-feedback d-none" id="errName"></div>
            </div>

            <div class="mb-3">
                <label class="form-label">Type</label>
                <select id="newVariableType" class="form-select">
                    <option value="gain">Gain (Prime, Indemnité...)</option>
                    <option value="deduction">Retenue (Acompte, Amende...)</option>
                </select>
                <div class="invalid-feedback d-none" id="errType"></div>
            </div>

            <div class="mb-3">
                <label class="form-label">Catégorie</label>
                <select id="newVariableCategory" class="form-select">
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}">{{ $cat->nom_categorie }}</option>
                    @endforeach
                </select>
                <div class="invalid-feedback d-none" id="errCategory"></div>
            </div>

            <div class="d-flex justify-content-end gap-2">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="offcanvas">Annuler</button>
                <button id="btnSubmitVariable" type="submit" class="btn btn-primary">
                    <span class="label">Ajouter</span>
                    <span class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                </button>
            </div>
        </form>

        <script>
        (function () {
            const offEl = document.getElementById('offcanvasAddVariable');
            // ✅ Utiliser window.bootstrap pour éviter ReferenceError si "bootstrap" n'existe pas
            const OffcanvasCtor = window.bootstrap && window.bootstrap.Offcanvas ? window.bootstrap.Offcanvas : null;
            const off = offEl && OffcanvasCtor ? new OffcanvasCtor(offEl) : null;

            const nameEl = document.getElementById('newVariableName');
            const typeEl = document.getElementById('newVariableType');
            const catEl  = document.getElementById('newVariableCategory');

            const btnSubmit = document.getElementById('btnSubmitVariable');
            const spn = btnSubmit ? btnSubmit.querySelector('.spinner-border') : null;
            const lbl = btnSubmit ? btnSubmit.querySelector('.label') : null;

            const errName = document.getElementById('errName');
            const errType = document.getElementById('errType');
            const errCat  = document.getElementById('errCategory');

            const csrf = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

            function safeNotify(msg, type='info') {
                if (typeof window.showNotification === 'function') {
                    window.showNotification(msg, type);
                } else {
                    console[type === 'error' ? 'error' : 'log']('[notify]', msg);
                }
            }

            function clearErrors() {
                [nameEl, typeEl, catEl].forEach(el => el && el.classList.remove('is-invalid'));
                [errName, errType, errCat].forEach(e => { if (e) { e.textContent = ''; e.classList.add('d-none'); } });
            }

            function setInvalid(el, errBox, msg) {
                if (!el || !errBox) return;
                el.classList.add('is-invalid');
                errBox.textContent = msg;
                errBox.classList.remove('d-none');
            }

            function disableSubmit(disabled) {
                if (!btnSubmit) return;
                btnSubmit.disabled = disabled;
                if (spn) spn.classList.toggle('d-none', !disabled);
                if (lbl) lbl.textContent = disabled ? 'Enregistrement...' : 'Ajouter';
            }

            function resetForm() {
                clearErrors();
                if (nameEl) nameEl.value = '';
                if (typeEl) typeEl.value = 'gain';
                if (catEl && catEl.options.length) catEl.selectedIndex = 0;
            }

            // Compatibilité avec tes anciens helpers
            window.showAddVariableModal = function () { if (off) off.show(); };
            window.closeAddVariableModal = function () { if (off) off.hide(); resetForm(); };

            if (offEl && OffcanvasCtor) {
                offEl.addEventListener('hidden.bs.offcanvas', resetForm);
            }

            // AJAX create
            window.addVariable = async function () {
                clearErrors();

                const name = (nameEl?.value || '').trim();
                const type = (typeEl?.value || 'gain').trim();
                const categorie_id = (catEl?.value || '').trim();

                if (!name) { setInvalid(nameEl, errName, 'Veuillez entrer un nom pour la variable.'); safeNotify('Veuillez entrer un nom pour la variable', 'error'); return; }
                if (!categorie_id) { setInvalid(catEl, errCat, 'Veuillez choisir une catégorie.'); safeNotify('Veuillez choisir une catégorie', 'error'); return; }

                disableSubmit(true);

                try {
                    const res = await fetch("{{ route('variables.ajax.store') }}", {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': csrf
                        },
                        body: JSON.stringify({ nom_variable: name, type, categorie_id })
                    });

                    // Tentative de parse même si non-JSON (ex: 419)
                    let data = {};
                    try { data = await res.json(); } catch (_) {}

                    if (!res.ok) {
                        if (res.status === 419) {
                            safeNotify('Session expirée (CSRF). Rafraîchis la page et réessaie.', 'error');
                            return;
                        }
                        if (res.status === 422 && data.errors) {
                            if (data.errors.nom_variable) setInvalid(nameEl, errName, data.errors.nom_variable[0]);
                            if (data.errors.type)        setInvalid(typeEl, errType, data.errors.type[0]);
                            if (data.errors.categorie_id)setInvalid(catEl,  errCat,  data.errors.categorie_id[0]);
                            safeNotify('Veuillez corriger les erreurs du formulaire', 'error');
                            return;
                        }
                        safeNotify(data.message || 'Erreur lors de la création', 'error');
                        return;
                    }

                    const created = data.data; // {id, nom_variable, type, categorie:{id, nom_categorie}}
                    if (Array.isArray(window.payrollVariables)) {
                        window.payrollVariables.push({
                            id: created.id,
                            name: created.nom_variable,
                            type: created.type,
                            category: created.categorie?.nom_categorie || ''
                        });
                    }

                    if (typeof window.renderVariablesGrid === 'function') {
                        window.renderVariablesGrid();
                    }

                    safeNotify('Variable ajoutée avec succès', 'success');
                    window.closeAddVariableModal();

                } catch (e) {
                    console.error(e);
                    safeNotify('Erreur réseau, vérifiez votre connexion', 'error');
                } finally {
                    disableSubmit(false);
                }
            };
        })();
        </script>
    </div>
</div>
