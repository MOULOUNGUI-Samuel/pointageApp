<div class="modal fade" id="floatingLabelsModal" tabindex="-1" aria-labelledby="floatingLabelsModalLabel"
    aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-header bg-primary">
                <h4 class="modal-title text-white" id="floatingLabelsModalLabel">
                    <i class="fas fa-plus me-2"></i><span id="modalTitleText">Ajouter une Variable</span>
                </h4>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                    aria-label="Fermer"></button>
            </div>

            <div class="modal-body">
                <!-- état -->
                <input type="hidden" id="formMode" value="create"> <!-- create | edit -->
                <input type="hidden" id="currentVariableId" value=""> <!-- id UUID -->

                <div class="mb-3">
                    <label class="form-label" for="newVariableName">Nom de la variable</label>
                    <input type="text" id="newVariableName" class="form-control" placeholder="Prime de performance"
                        required>
                    <div class="invalid-feedback d-none" id="errName"></div>
                </div>

                <div class="form-check mb-3">
                    <input type="checkbox" class="form-check-input" id="customCheckcolor1" name="statutVariable"
                        value="1" />
                    <label class="form-check-label" for="customCheckcolor1">Variable de cotisation</label>
                </div>

                <div class="d-none" id="variableCotisation">
                    <div class="mb-3">
                        <label class="form-label" for="newVariableTaux">Taux de cotisation salariale</label>
                        <div class="input-group">
                            <input type="text" id="newVariableTaux" class="form-control" inputmode="decimal"
                                placeholder="Ex. 7.5" max="100" name="tauxVariable" aria-describedby="suffixPct">
                            <span class="input-group-text" id="suffixPct">%</span>
                        </div>
                        <div class="form-text">Indiquez un pourcentage (ex. 5, 7.5 ou 12.25).</div>
                        <div class="invalid-feedback d-none" id="errTaux"></div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="newVariableTauxEntreprise">Taux de cotisation patronale</label>
                        <div class="input-group">
                            <input type="text" id="newVariableTauxEntreprise" class="form-control" inputmode="decimal"
                                placeholder="Ex. 7.5" max="100" name="tauxVariableEntreprise" aria-describedby="suffixPct">
                            <span class="input-group-text" id="suffixPct">%</span>
                        </div>
                        <div class="form-text">Indiquez un pourcentage (ex. 5, 7.5 ou 12.25).</div>
                        <div class="invalid-feedback d-none" id="errTaux"></div>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label" for="newVariableType">Type</label>
                    <select id="newVariableType" class="form-select" required>
                        <option value="">Choix du type</option>
                        <option value="gain">Gain (Prime, Indemnité...)</option>
                        <option value="deduction">Retenue (Acompte, Amende...)</option>
                    </select>
                    <div class="invalid-feedback d-none" id="errType"></div>
                </div>

                <div class="mb-3">
                    <label class="form-label" for="newVariableCategory">Catégorie</label>
                    <select id="newVariableCategory" class="form-select" required>
                        <option value="">Choix de la catégorie</option>
                        @foreach ($categories as $cat)
                            <option value="{{ $cat->id }}">{{ $cat->nom_categorie }}</option>
                        @endforeach
                    </select>
                    <div class="invalid-feedback d-none" id="errCategory"></div>
                </div>

                <div class="form-check mb-3">
                    <input type="checkbox" class="form-check-input" id="customCheckcolor2" name="variableImposable">
                    <label class="form-check-label" for="customCheckcolor2">Variable imposable</label>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                <button id="btnSubmitVariable" type="button" class="btn btn-primary" onclick="saveVariable()">
                    <span class="label">Enregistrer</span>
                    <span class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                </button>
            </div>

        </div>
    </div>
</div>


<script>
    (function() {
        // Références
        const modalEl = document.getElementById('floatingLabelsModal');
        const nameEl = document.getElementById('newVariableName');
        const typeEl = document.getElementById('newVariableType');
        const catEl = document.getElementById('newVariableCategory');
        const btnSave = document.getElementById('btnSubmitVariable') ||
            document.querySelector('#floatingLabelsModal .modal-footer .btn.btn-primary');

        const cotisCheck = document.getElementById('customCheckcolor1'); // Variable de cotisation
        const tauxWrapEl = document.getElementById('variableCotisation'); // Conteneur du champ
        const tauxEl = document.getElementById('newVariableTaux');
        const tauxEl1 = document.getElementById('newVariableTauxEntreprise');

        const errName = document.getElementById('errName');
        const errType = document.getElementById('errType');
        const errCat = document.getElementById('errCategory');
        const errTaux = document.getElementById('errTaux');

        // Helpers erreurs
        function hideErr(box) {
            if (box) {
                box.textContent = '';
                box.classList.add('d-none');
            }
        }

        function showErr(el, box, msg) {
            if (!el || !box) return;
            el.classList.add('is-invalid');
            box.textContent = msg;
            box.classList.remove('d-none');
        }

        function clearErrors() {
            [nameEl, typeEl, catEl, tauxEl,tauxEl1].forEach(el => el && el.classList.remove('is-invalid'));
            [errName, errType, errCat, errTaux].forEach(hideErr);
        }

        // Affiche/masque le champ taux + bascule l'attribut required
        function toggleTauxVisibility() {
            const checked = !!(cotisCheck && cotisCheck.checked);
            if (checked) {
                tauxWrapEl && tauxWrapEl.classList.remove('d-none');
                if (tauxEl) {
                    tauxEl.required = true;
                    tauxEl.setAttribute('aria-required', 'true');
                }
                if (tauxEl1) {
                    tauxEl1.required = true;
                    tauxEl1.setAttribute('aria-required', 'true');
                }
            } else {
                // On cache, on nettoie et on rend non requis
                if (tauxWrapEl) tauxWrapEl.classList.add('d-none');
                if (tauxEl) {
                    tauxEl.required = false;
                    tauxEl.removeAttribute('aria-required');
                    tauxEl.value = '';
                    tauxEl.classList.remove('is-invalid');
                }
                if (tauxEl1) {
                    tauxEl1.required = false;
                    tauxEl1.removeAttribute('aria-required');
                    tauxEl1.value = '';
                    tauxEl1.classList.remove('is-invalid');
                }
                hideErr(errTaux);
            }
        }

        // Validation du taux (nombre décimal positif)
        function isValidTaux(value) {
            if (value === null || value === undefined) return false;
            // Accepte "5", "7.5", "12,25" -> on remplace la virgule par un point
            const normalized = String(value).trim().replace(',', '.');
            if (normalized === '') return false;
            const num = Number(normalized);
            return Number.isFinite(num) && num >= 0; // laissez <100 si certains taux peuvent dépasser (ex: 150%)
        }

        // Validation générale + gestion de data-bs-dismiss
        function validateAndToggleDismiss() {
            clearErrors();
            let ok = true;

            if (!nameEl || !nameEl.value.trim()) {
                showErr(nameEl, errName, 'Veuillez entrer un nom.');
                ok = false;
            }
            if (!typeEl || !typeEl.value) {
                showErr(typeEl, errType, 'Veuillez choisir un type.');
                ok = false;
            }
            if (!catEl || !catEl.value) {
                showErr(catEl, errCat, 'Veuillez choisir une catégorie.');
                ok = false;
            }

            // Si Variable de cotisation cochée, le taux devient requis
            if (cotisCheck && cotisCheck.checked) {
                const val = tauxEl ? tauxEl.value : '';
                if (!isValidTaux(val)) {
                    showErr(tauxEl, errTaux, 'Veuillez renseigner un taux valide (ex. 5, 7.5 ou 12,25).');
                    ok = false;
                } else {
                    // Normaliser la valeur (remplacer virgule par point) pour cohérence avant envoi
                    tauxEl.value = String(val).trim().replace(',', '.');
                }
            }
            if (cotisCheck && cotisCheck.checked) {
                const val = tauxEl1 ? tauxEl1.value : '';
                if (!isValidTaux(val)) {
                    showErr(tauxEl1, errTaux, 'Veuillez renseigner un taux valide (ex. 5, 7.5 ou 12,25).');
                    ok = false;
                } else {
                    // Normaliser la valeur (remplacer virgule par point) pour cohérence avant envoi
                    tauxEl1.value = String(val).trim().replace(',', '.');
                }
            }

            if (btnSave) {
                if (ok) btnSave.setAttribute('data-bs-dismiss', 'modal');
                else btnSave.removeAttribute('data-bs-dismiss');
            }
            return ok;
        }

        // Mises à jour en direct
        ['input', 'change'].forEach(evt => {
            [nameEl, typeEl, catEl, tauxEl,tauxEl1].forEach(el => el && el.addEventListener(evt,
                validateAndToggleDismiss));
        });
        if (cotisCheck) {
            cotisCheck.addEventListener('change', () => {
                toggleTauxVisibility();
                validateAndToggleDismiss();
            });
        }

        // API d’ouverture pré-sélectionnée (optionnel)
        // window.openAddVariableWithCategory('ID') pour ouvrir direct avec une catégorie
        window.openAddVariableWithCategory = function(catId) {
            if (catEl) {
                let has = false;
                for (let i = 0; i < catEl.options.length; i++) {
                    if (catEl.options[i].value === String(catId)) {
                        has = true;
                        break;
                    }
                }
                if (!has && catId) catEl.add(new Option('(Catégorie)', catId, true, true));
                catEl.value = catId || '';
            }

            // Reset champs
            if (nameEl) nameEl.value = '';
            if (typeEl) typeEl.value = '';
            if (cotisCheck) cotisCheck.checked = false;
            toggleTauxVisibility(); // cache le taux par défaut
            clearErrors();
            validateAndToggleDismiss();

            const ModalCtor = (window.bootstrap && window.bootstrap.Modal) ? window.bootstrap.Modal : null;
            if (ModalCtor && modalEl) ModalCtor.getOrCreateInstance(modalEl).show();
            else if (modalEl) {
                modalEl.classList.add('show');
                modalEl.style.display = 'block';
                modalEl.removeAttribute('aria-hidden');
            }
        };

        // Empêche la fermeture si invalide
        if (btnSave) {
            btnSave.addEventListener('click', (e) => {
                if (!validateAndToggleDismiss()) {
                    e.preventDefault();
                    e.stopPropagation();
                }
            });
        }

        // État initial
        toggleTauxVisibility();
        validateAndToggleDismiss();
    })();
</script>
