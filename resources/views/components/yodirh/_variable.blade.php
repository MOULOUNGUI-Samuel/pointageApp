<div class="modal fade" id="floatingLabelsModal" tabindex="-1" aria-labelledby="floatingLabelsModalLabel"
    aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-header bg-primary">
                <h4 class="modal-title text-white" id="floatingLabelsModalLabel">
                    <i class="fas fa-plus mr-2"></i>Ajouter une Variable
                </h4>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                    aria-label="Fermer"></button>
            </div>

            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label">Nom de la variable</label>
                    <input type="text" id="newVariableName" class="form-control" placeholder="Prime de performance"
                        required>
                    <div class="invalid-feedback d-none" id="errName"></div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Type</label>
                    <select id="newVariableType" class="form-select" required>
                        <option value="">Choix du type</option>
                        <option value="gain">Gain (Prime, Indemnité...)</option>
                        <option value="deduction">Retenue (Acompte, Amende...)</option>
                    </select>
                    <div class="invalid-feedback d-none" id="errType"></div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Catégorie</label>
                    <select id="newVariableCategory" class="form-select" required>
                        <option value="">Choix de la catégorie</option>
                        @foreach ($categories as $cat)
                            <option value="{{ $cat->id }}">{{ $cat->nom_categorie }}</option>
                        @endforeach
                    </select>
                    <div class="invalid-feedback d-none" id="errCategory"></div>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>

                <!-- ⚠️ PAS de data-bs-dismiss ici : on l’ajoute dynamiquement si succès -->
                <button id="btnSubmitVariable" type="button" class="btn btn-primary" onclick="addVariable()">
                    <span class="label">Enregistrer</span>
                    <span class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                </button>
            </div>

        </div>
    </div>
</div>
<script>
    (function () {
      // Références
      const modalEl = document.getElementById('floatingLabelsModal');
      const nameEl  = document.getElementById('newVariableName');
      const typeEl  = document.getElementById('newVariableType');
      const catEl   = document.getElementById('newVariableCategory');
      const btnSave = document.getElementById('btnSubmitVariable') 
                   || document.querySelector('#floatingLabelsModal .modal-footer .btn.btn-primary');
    
      const errName = document.getElementById('errName');
      const errType = document.getElementById('errType');
      const errCat  = document.getElementById('errCategory');
    
      // Helpers erreurs
      function clearErrors() {
        [nameEl, typeEl, catEl].forEach(el => el && el.classList.remove('is-invalid'));
        [errName, errType, errCat].forEach(e => { if (e) { e.textContent=''; e.classList.add('d-none'); } });
      }
      function setInvalid(el, box, msg) {
        if (!el || !box) return;
        el.classList.add('is-invalid');
        box.textContent = msg;
        box.classList.remove('d-none');
      }
    
      // Validation simple + gestion de data-bs-dismiss
      function validateAndToggleDismiss() {
        clearErrors();
        let ok = true;
    
        if (!nameEl || !nameEl.value.trim()) { setInvalid(nameEl, errName, 'Veuillez entrer un nom.'); ok = false; }
        if (!typeEl || !typeEl.value)        { setInvalid(typeEl, errType, 'Veuillez choisir un type.'); ok = false; }
        if (!catEl  || !catEl.value)         { setInvalid(catEl,  errCat,  'Veuillez choisir une catégorie.'); ok = false; }
    
        if (btnSave) {
          if (ok) btnSave.setAttribute('data-bs-dismiss','modal');
          else    btnSave.removeAttribute('data-bs-dismiss');
        }
        return ok;
      }
    
      // Ecoutes pour mettre à jour l’attribut au fil de la saisie
      ['input','change'].forEach(evt => {
        [nameEl, typeEl, catEl].forEach(el => el && el.addEventListener(evt, validateAndToggleDismiss));
      });
    
      // ✅ Appelée par ton bouton "Ajouter" dans la tuile catégorie vide
      //    onclick="openAddVariableWithCategory('ID_DE_LA_CATEGORIE')"
      window.openAddVariableWithCategory = function (catId) {
        // Pré-sélection de la catégorie
        if (catEl) {
          // si l’option n’existe pas (cas rare), on l’ajoute à la volée
          let has = false;
          for (let i = 0; i < catEl.options.length; i++) {
            if (catEl.options[i].value === catId) { has = true; break; }
          }
          if (!has && catId) {
            const opt = new Option('(Catégorie)', catId, true, true);
            catEl.add(opt);
          }
          catEl.value = catId || '';
        }
    
        // Réinitialise les champs de saisie
        if (nameEl) nameEl.value = '';
        if (typeEl) typeEl.value = '';
    
        // Nettoie erreurs + ajuste dismiss selon l’état
        validateAndToggleDismiss();
    
        // Ouvre la modale
        const ModalCtor = window.bootstrap && window.bootstrap.Modal ? window.bootstrap.Modal : null;
        if (ModalCtor && modalEl) {
          ModalCtor.getOrCreateInstance(modalEl).show();
        } else if (modalEl) {
          // fallback très basique si Bootstrap non chargé (optionnel)
          modalEl.classList.add('show');
          modalEl.style.display = 'block';
          modalEl.removeAttribute('aria-hidden');
        }
      };
    
      // Au clic sur "Enregistrer", empêcher la fermeture si invalide
      if (btnSave) {
        btnSave.addEventListener('click', (e) => {
          if (!validateAndToggleDismiss()) {
            e.preventDefault();
            e.stopPropagation();
          }
        });
      }
    
      // Init état du bouton à l’ouverture
      validateAndToggleDismiss();
    })();
    </script>
    
