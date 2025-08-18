<div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasWithBackdrop3"
    aria-labelledby="offcanvasWithBackdropLabel1">
    <div class="offcanvas-header">
        <h5 class="offcanvas-title ps-3 mb-3" id="offcanvasWithBackdropLabel1"
            style="border-left: 5px solid #05436b; color: #333;">
            Enregistrer une catégorie
        </h5>
        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div> <!-- end offcanvas-header-->
    <div class="offcanvas-body">
        <!-- Dans votre vue Blade, par exemple resources/views/profile/edit.blade.php -->

        <!-- Formulaire pour enregistrer la clé (vous l'avez déjà) -->
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
                                value="{{ $val }}" placeholder="Entrez le nom" autocomplete="off">
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

            <div class="d-flex gap-2 mt-3">
                <button type="button" id="add-category" class="btn btn-light">
                    + Ajouter un champ
                </button>
                <button type="submit" class="btn btn-primary">
                    Enregistrer les catégories
                </button>
            </div>
        </form>

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
                               placeholder="Entrez le nom" autocomplete="off">
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


    </div> <!-- end offcanvas-body-->
</div>
