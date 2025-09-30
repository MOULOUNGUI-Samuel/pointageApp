<div>
    {{-- BOUTON D’AJOUT EXEMPLE --}}


    {{-- GRILLE / LISTE (ex. groupée par catégorie) --}}
    {{-- @php
        $byCat = collect($variablesList)->groupBy('categoryName');
    @endphp

    @foreach ($byCat as $catName => $items)
        <div class="bg-gray-50 rounded-lg p-4 mb-3">
            <h4 class="font-semibold text-primary mb-3">
                <i class="fas fa-folder me-2"></i>{{ $catName }}
            </h4>

            @forelse($items as $v)
                <div class="d-flex align-items-center justify-content-between p-3 bg-white rounded border mb-2">
                    <div>
                        <div class="fw-medium">{{ $v['name'] }}</div>
                        <div class="small text-muted">
                            {{ strtoupper($v['type']) }}
                            @if ($v['statutVariable'])
                                · Taux sal. {{ $v['tauxVariable'] ?? 0 }}% · Taux pat.
                                {{ $v['tauxVariableEntreprise'] ?? 0 }}%
                            @endif
                            @if ($v['numeroVariable'])
                                · N° {{ $v['numeroVariable'] }}
                            @endif
                            @if ($v['variableImposable'])
                                · <span class="badge bg-warning text-dark">Imposable</span>
                            @endif
                        </div>
                    </div>
                    <div class="d-flex gap-2">
                        <button class="btn btn-sm btn-light" wire:click="openEdit('{{ $v['id'] }}')"
                            title="Modifier">
                            <i class="fas fa-pen"></i>
                        </button>

                        <button class="btn btn-sm btn-light text-danger"
                            wire:click="confirmDelete('{{ $v['id'] }}')" title="Supprimer">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>
            @empty
                <div class="p-3 bg-white rounded border border-dashed text-muted">
                    Aucune variable dans cette catégorie
                </div>
            @endforelse
        </div>
    @endforeach --}}

    {{-- MODALE (même contenu que la tienne, bindée Livewire) --}}
    <div class="modal fade" id="floatingLabelsModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-fullscreen modal-dialog-scrollable p-3">
            <div class="modal-content">
                <div class="modal-header bg-primary">
                    <h4 class="modal-title text-white">
                        <i class="fas fa-plus me-2"></i>
                        <span>{{ $mode === 'create' ? 'Ajouter une Variable' : 'Modifier la Variable' }}</span>
                    </h4>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Fermer"></button>
                </div>
                <form id="variableForm" wire:submit.prevent="save">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-4">
                                {{-- N° (readonly en edit, pré-rempli en create) --}}
                                <div class="mb-3">
                                    <label class="form-label">N°</label>
                                    <input type="text" class="form-control" wire:model.defer="numeroVariable"
                                        @if ($mode === 'edit') readonly @endif required>
                                    @error('numeroVariable')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>


                                <div class="mb-3">
                                    <label class="form-label">Nom de la variable</label>
                                    <input type="text" class="form-control" placeholder="libellé de la variable"
                                        wire:model.defer="nom_variable" required>
                                    @error('nom_variable')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- Type (inchangé) --}}
                                <div class="mb-3">
                                    <label class="form-label">Type</label>
                                    <select class="form-select" id="newVariableType" wire:model="type" required>
                                        <option value="">Choix du type</option>
                                        <option value="gain">Gain (Prime, Indemnité...)</option>
                                        <option value="deduction">Retenue (Acompte, Amende...)</option>
                                    </select>
                                    @error('type')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- Radios : TOUJOURS rendues, masquées par défaut --}}
                                <div class="mt-3">
                                    <div class="row">
                                        <div class="col-md-5 d-none" id="cotisationCheckWrapper">
                                            <div class="p-2 border rounded shadow-sm">
                                                <div class="form-check mb-1">
                                                    <input type="radio" id="customRadio1" class="form-check-input"
                                                        value="cotisation" wire:model="statutMode">
                                                    <label class="form-check-label" for="customRadio1">Variable de
                                                        cotisation</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-7">
                                            <div class="p-2 border rounded shadow-sm">
                                                <div class="form-check">
                                                    <input type="radio" id="customRadio2" class="form-check-input"
                                                        value="sans_cotisation" wire:model="statutMode">
                                                    <label class="form-check-label" for="customRadio2">Variable sans
                                                        cotisation avec taux</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @error('statutMode')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- Section COTISATION : TOUJOURS rendue + masquée au chargement --}}
                                <div class="mt-3 d-none" id="variableCotisation">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label">Taux de cotisation salariale</label>
                                                <div class="input-group">
                                                    <input type="text" class="form-control" inputmode="decimal"
                                                        placeholder="Ex. 7.5" id="newVariableTauxCotSal"
                                                        wire:model.defer="tauxVariable">
                                                    <span class="input-group-text">%</span>
                                                </div>
                                                @error('tauxVariable')
                                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label">Taux de cotisation patronale</label>
                                                <div class="input-group">
                                                    <input type="text" class="form-control" inputmode="decimal"
                                                        placeholder="Ex. 7.5" id="newVariableTauxCotPat"
                                                        wire:model.defer="tauxVariableEntreprise">
                                                    <span class="input-group-text">%</span>
                                                </div>
                                                @error('tauxVariableEntreprise')
                                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>


                                    <div id="assocHelp" class="form-text mb-2 d-none">
                                        Sélectionnez les variables à associer :
                                    </div>
                                </div>

                                {{-- Section SANS COTISATION : TOUJOURS rendue + masquée au chargement --}}
                                <div class="mt-3 d-none" id="variableSansCotisation">
                                    <div class="mb-3">
                                        <label class="form-label">Taux</label>
                                        <div class="input-group">
                                            <input type="text" class="form-control" inputmode="decimal"
                                                placeholder="Ex. 7.5" id="newVariableTauxSans"
                                                wire:model.defer="tauxVariable">
                                            <span class="input-group-text">%</span>
                                        </div>
                                        @error('tauxVariable')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-text mb-2">Sélectionnez les variables à associer :</div>
                                </div>


                                <div class="mb-3">
                                    <label class="form-label">Catégorie</label>
                                    <select class="form-select" wire:model="categorie_id" required>
                                        <option value="">Choix de la catégorie</option>
                                        @foreach ($categoriesList as $c)
                                            <option value="{{ $c['id'] }}">{{ $c['label'] }}</option>
                                        @endforeach
                                    </select>
                                    @error('categorie_id')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-check mb-3">
                                    <input type="checkbox" class="form-check-input" id="imposable"
                                        wire:model="variableImposable">
                                    <label class="form-check-label" for="imposable">Variable imposable</label>
                                </div>
                            </div>

                            {{-- Checklist d’associations (colonne de droite) --}}
                            <div class="col-md-8">
                                {{-- Checklist d’associations (colonne de droite) --}}
                                @php
                                    // Autoriser la sélection si Type = deduction ET une des deux options cochée
                                    $canAssociate =
                                        $type === 'deduction' &&
                                        in_array($statutMode, ['cotisation', 'sans_cotisation'], true);
                                @endphp
                                <p id="assocHelp" class="form-text {{ $canAssociate ? '' : 'd-none' }}">
                                    Sélectionnez les variables à associer :
                                </p>
                                <div class="row">
                                    @foreach ($variablesList as $v)
                                        @continue($currentId === $v['id']) {{-- pas d’auto-association --}}
                                        <div class="col-md-3">
                                            <div class="mb-3 border p-2 rounded shadow-sm">
                                                <div class="form-check">
                                                    <input type="checkbox" class="form-check-input selected-var"
                                                        id="assoc_{{ $v['id'] }}" value="{{ $v['id'] }}"
                                                        wire:model="selectedVariables" disabled>
                                                    <label class="form-check-label" for="assoc_{{ $v['id'] }}"
                                                        style="cursor:not-allowed;opacity:.7">
                                                        {{ $v['name'] }}
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                    @error('selectedVariables')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>

                            </div>


                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>

                        <button type="button" class="btn btn-primary" wire:click="save"
                            wire:loading.attr="disabled">
                            <span wire:loading.remove>Enregistrer</span>
                            <span class="spinner-border spinner-border-sm" wire:loading></span>
                        </button>

                    </div>
                </form>

            </div>
        </div>
    </div>

</div>


{{-- Bootstrap 5 modal hooks + petites notif/confirm --}}
{{-- @push('scripts') --}}
<script>
    window.addEventListener('show-variable-modal', () => {
        const el = document.getElementById('floatingLabelsModal');
        bootstrap.Modal.getOrCreateInstance(el).show();
    });

    window.addEventListener('hide-variable-modal', () => {
        const el = document.getElementById('floatingLabelsModal');
        bootstrap.Modal.getOrCreateInstance(el).hide();
    });

    window.addEventListener('notify', e => {
        const {
            type,
            msg
        } = e.detail || {};
        // branche ta toast ici
        console.log(type || 'info', msg || '');
    });

    window.addEventListener('confirm-delete-variable', e => {
        const id = e.detail?.id;
        if (confirm('Supprimer cette variable ?')) {
            // v3: déclenche un event Livewire que le composant écoute via #[On('delete-variable')]
            Livewire.dispatch('delete-variable', {
                id
            });
        }
    });
</script>
<script>
    (() => {
      const root = document.getElementById('floatingLabelsModal');
      if (!root) return;
    
      const q  = sel => root.querySelector(sel);
      const qa = sel => Array.from(root.querySelectorAll(sel));
    
      const typeEl   = q('#newVariableType');
      const rCotisWr = q('#cotisationCheckWrapper');   // colonne contenant #customRadio1
      const rCotis   = q('#customRadio1');
      const rSans    = q('#customRadio2');
    
      const secCotis = q('#variableCotisation');
      const secSans  = q('#variableSansCotisation');
    
      const tauxSal  = q('#newVariableTauxCotSal');
      const tauxPat  = q('#newVariableTauxCotPat');
      const tauxSans = q('#newVariableTauxSans');
    
      const assocHelp = q('#assocHelp');
      const assocCbs  = qa('.selected-var, [name="selectedVariables"]');
    
      const shown = (el,on)=> el && el.classList.toggle('d-none', !on);
      const req   = (el,on)=>{
        if (!el) return;
        el.toggleAttribute('required', !!on);
        if (on) el.setAttribute('aria-required','true'); else el.removeAttribute('aria-required');
      };
      const enableAssoc = on => {
        assocCbs.forEach(cb => {
          cb.disabled = !on;
          const lab = root.querySelector(`label[for="${cb.id}"]`);
          if (lab){ lab.style.cursor = on ? 'pointer' : 'not-allowed'; lab.style.opacity = on ? '1' : '.7'; }
          // on conserve les cases cochées quand on désactive
        });
        if (assocHelp) assocHelp.classList.toggle('d-none', !on);
      };
    
      if (rCotis && rSans) rCotis.name = rSans.name = rCotis.name || rSans.name || 'statutVariable';
    
      function syncUI(){
        const isDeduction = typeEl && typeEl.value === 'deduction';
    
        // 1) Le radio "cotisation" n'est visible/valable que si Type = Retenue
        shown(rCotisWr, !!isDeduction);
        if (!isDeduction && rCotis) rCotis.checked = false;
    
        // 2) Sections :
        //    - Cotisation => uniquement si Type = Retenue ET radio cotis coché
        shown(secCotis, isDeduction && !!rCotis?.checked);
        //    - Sans cotisation => visible si radio sans coché (peu importe le type)
        shown(secSans, !!rSans?.checked);
    
        // 3) Required :
        req(tauxSal,  isDeduction && !!rCotis?.checked);
        req(tauxPat,  isDeduction && !!rCotis?.checked);
        req(tauxSans, isDeduction && !!rSans?.checked); // requis seulement si Retenue + sans
    
        // 4) Associations activées si :
        //    - rSans coché (quel que soit le type) OU
        //    - (Type = Retenue et rCotis coché)
        const canAssociate = (!!rSans?.checked) || (isDeduction && !!rCotis?.checked);
        enableAssoc(canAssociate);
      }
    
      root.addEventListener('change', e => {
        if (e.target === typeEl || e.target === rCotis || e.target === rSans) syncUI();
      });
      root.addEventListener('input', e => {
        if (e.target === rCotis || e.target === rSans) syncUI();
      });
    
      root.addEventListener('shown.bs.modal', () => setTimeout(syncUI, 0));
      window.addEventListener('show-variable-modal', () => setTimeout(syncUI, 0));
    
      syncUI();
    })();
    </script>
    
    
