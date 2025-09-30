<div class="modal fade" id="floatingLabelsModal" tabindex="-1" aria-labelledby="floatingLabelsModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-fullscreen modal-dialog-scrollable  p-3">
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
                <div class="row">
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label class="form-label" for="numeroVariable">N°</label>
                            <input type="text" id="numeroVariable" class="form-control" placeholder="">
                            <div class="invalid-feedback d-none" id="errNumero"></div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="newVariableName">Nom de la variable</label>
                            <input type="text" id="newVariableName" class="form-control"
                                placeholder="libeller de la variable" required>
                            <div class="invalid-feedback d-none" id="errName"></div>
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

                        <!-- Radios : valeurs distinctes -->
                        <div class="mt-3">
                            <div class="row">
                                <div class="col-md-5">
                                    <div class="p-2 border rounded shadow-sm">
                                        <div class="form-check mb-1 d-none" id="cotisationCheckWrapper">
                                            <input type="radio" id="customRadio1" name="statutVariable"
                                                class="form-check-input" value="cotisation" />
                                            <label class="form-check-label" for="customRadio1"
                                                style="cursor: pointer">Variable de
                                                cotisation</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-7">
                                    <div class="p-2 border rounded shadow-sm">
                                        <div class="form-check">
                                            <input type="radio" id="customRadio2" name="statutVariable"
                                                class="form-check-input" value="sans_cotisation" />
                                            <label class="form-check-label" for="customRadio2"
                                                style="cursor: pointer">Variable sans cotisation
                                                avec
                                                taux</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Sections: ids et inputs distincts -->
                        <div class="d-none" id="variableCotisation">
                            <div class="mb-3">
                                <label class="form-label" for="newVariableTauxCotSal">Taux de cotisation
                                    salariale</label>
                                <div class="input-group">
                                    <input type="text" id="newVariableTauxCotSal" class="form-control"
                                        inputmode="decimal" placeholder="Ex. 7.5" max="100" name="tauxVariable">
                                    <span class="input-group-text">%</span>
                                </div>
                                <div class="form-text">Ex. 5, 7.5 ou 12,25</div>
                                <div class="invalid-feedback d-none" id="errTauxSal"></div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label" for="newVariableTauxCotPat">Taux de cotisation
                                    patronale</label>
                                <div class="input-group">
                                    <input type="text" id="newVariableTauxCotPat" class="form-control"
                                        inputmode="decimal" placeholder="Ex. 7.5" max="100"
                                        name="tauxVariableEntreprise">
                                    <span class="input-group-text">%</span>
                                </div>
                                <div class="form-text">Ex. 5, 7.5 ou 12,25</div>
                                <div class="invalid-feedback d-none" id="errTauxPat"></div>
                            </div>
                        </div>

                        <div class="d-none" id="variableSansCotisation">
                            <div class="mb-3">
                                <label class="form-label" for="newVariableTauxSans">Taux</label>
                                <div class="input-group">
                                    <input type="text" id="newVariableTauxSans" class="form-control"
                                        inputmode="decimal" placeholder="Ex. 7.5" max="100"
                                        name="tauxSansCotisation">
                                    <span class="input-group-text">%</span>
                                </div>
                                <div class="form-text">Ex. 5, 7.5 ou 12,25</div>
                                <div class="invalid-feedback d-none" id="errTauxSans"></div>
                            </div>
                            <div class="form-text mb-2">Sélectionnez les variables à associer :</div>
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
                            <input type="checkbox" class="form-check-input" id="customCheckcolor2"
                                name="variableImposable">
                            <label class="form-check-label" for="customCheckcolor2">Variable imposable</label>
                        </div>
                    </div>
                    <div class="col-md-8">
                        <div class="row">
                            @foreach ($variables as $variable)
                                <div class="col-md-3">
                                    <div class="mb-3 border p-2 rounded shadow-sm">
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input selected-var"
                                                id="customCheckcolor{{ $variable->id }}" name="selectedVariables"
                                                value="{{ $variable->id }}" disabled />
                                            <label class="form-check-label" for="customCheckcolor{{ $variable->id }}"
                                                style="cursor: not-allowed; opacity:.7">{{ $variable->nom_variable }}</label>
                                        </div>
                                    </div>
                                </div>
                            @endforeach

                        </div>
                    </div>
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


