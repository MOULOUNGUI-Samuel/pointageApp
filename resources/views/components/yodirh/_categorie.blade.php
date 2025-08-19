<div class="modal fade" id="floatingLabelsModalCategori" tabindex="-1" aria-labelledby="floatingLabelsModalLabel"
    aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-header bg-primary">
                <h4 class="modal-title text-white" id="floatingLabelsModalLabel">
                    <i class="fas fa-plus mr-2"></i>Enregistrer une catégorie
                </h4>
            </div>

            <div class="modal-body">
                <form method="POST" action="{{ route('categories.storeMultiple') }}" id="form-categories">
                    @csrf

                    <div id="categories-wrapper">
                        @php
                            // Recrée les champs saisis en cas d’erreur de validation
                            $oldValues = old('nom_categorie', ['']);
                        @endphp

                        @foreach ($oldValues as $i => $val)
                            <div class="row g-2 align-items-start mb-2 categorie-row">
                                <div class="col">
                                    <label class="form-label" for="nom_categorie_{{ $i }}">Nom de la
                                        catégorie</label>
                                    <input type="text"
                                        class="form-control @error('nom_categorie.' . $i) is-invalid @enderror"
                                        id="nom_categorie_{{ $i }}" name="nom_categorie[]"
                                        value="{{ $val }}" placeholder="Entrez le nom" autocomplete="off"
                                        required>
                                    @error('nom_categorie.' . $i)
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-auto">
                                    <button type="button" class="btn btn-outline-danger remove-row mt-4"
                                        @if ($i === 0) disabled @endif>&times;</button>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div class="text-right">
                        <button type="button" id="add-category" class="btn btn-light shadow mb-1">
                            + Nouvelle catégorie
                        </button>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                        <button type="submit" class="btn-action btn btn-primary" data-loader-target="ajout">
                            Enregistrer les catégories
                        </button>
                        <!-- Bouton de chargement (caché au départ) -->
                        <button type="button" id="ajout" class="btn btn-outline-primary" style="display: none;"
                            disabled>
                            <i class="fas fa-spinner fa-spin me-2"></i>Enregistrement...
                        </button>
                    </div>
                </form>
            </div>

        </div>
    </div>
</div>

{{-- JS minimal pour ajouter/retirer des champs --}}
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const wrapper = document.getElementById('categories-wrapper');
        const addBtn = document.getElementById('add-category');

        function addRow(value = '') {
            const index = wrapper.querySelectorAll('.categorie-row').length;
            const div = document.createElement('div');
            div.className = 'row g-2 align-items-start mb-2 categorie-row';
            div.innerHTML = `
            <div class="col">
                <label class="form-label" for="nom_categorie_${index}">Nom de la catégorie</label>
                <input type="text" class="form-control"
                       id="nom_categorie_${index}"
                       name="nom_categorie[]"
                       value="${value}"
                       placeholder="Entrez le nom" autocomplete="off" required>
            </div>
            <div class="col-auto d-flex align-items-end">
                <button type="button" class="btn btn-outline-danger remove-row mt-4">&times;</button>
            </div>
        `;
            wrapper.appendChild(div);
        }

        addBtn.addEventListener('click', () => addRow());

        wrapper.addEventListener('click', (e) => {
            if (e.target.closest('.remove-row')) {
                const row = e.target.closest('.categorie-row');
                // Évite de supprimer la toute première ligne (toujours garder au moins 1 champ)
                if (wrapper.querySelectorAll('.categorie-row').length > 1) {
                    row.remove();
                }
            }
        });
    });
</script>
