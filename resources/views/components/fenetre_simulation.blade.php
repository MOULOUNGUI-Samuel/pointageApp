<style>
    /* Taille personnalisée pour desktop et tablette */
    @media (min-width: 568px) {
        .offcanvas-custom {
            width: 700px !important;
            /* Tu peux mettre la largeur que tu veux */
        }
    }
</style>

<div class="offcanvas offcanvas-start offcanvas-custom" data-bs-scroll="true" data-bs-backdrop="false" tabindex="-1"
    id="offcanvasScrolling" aria-labelledby="offcanvasScrollingLabel">
    <div class="offcanvas-header">
        <h5 class="offcanvas-title" id="offcanvasScrollingLabel">Gestion des Simulations</h5>
        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div> <!-- end offcanvas-header-->
    <div class="offcanvas-body">

        {{-- Barre de recherche --}}
        <div class="mb-4">
            <input type="text" id="search-simulation" class="form-control shadow"
                placeholder="🔍 Rechercher une simulation...">
        </div>

        <div class="row row-cols-1 row-cols-md-2 g-3" id="simulations-container">

            {{-- Exemple de carte --}}
            <div class="col simulation-card" data-title="Simulateur de Prêt Dynamique Avancé">
                <a href="#" onclick="Simulateur_des_prets(event)" class="text-decoration-none text-dark">
                    <div class="card h-100 p-3 hover-shadow shadow-sm">
                        <div class="d-flex align-items-center mb-2">
                            <i class="bi bi-calculator fs-3 text-primary me-2"></i>
                            <h6 class="mb-0">Simulateur de Prêt Dynamique Avancé</h6>
                        </div>
                        <p class="text-muted mt-2">Prêt entre Particulier et INGENIUM Assurance - Gestion avancée des
                            dates et durées</p>
                    </div>
                </a>
            </div>

            <div class="col simulation-card" data-title="Simulateur Facturation - TPS & CSS">
                <a href="#" onclick="SIMULATEUR_FACTURATION_hots_TVA(event)"
                    class="text-decoration-none text-dark">
                    <div class="card h-100 p-3 hover-shadow shadow-sm">
                        <div class="d-flex align-items-center mb-2">
                            <i class="bi bi-file-earmark-text fs-3 text-primary me-2"></i>
                            <h6 class="mb-0">Simulateur Facturation - TPS & CSS</h6>
                        </div>
                        <p class="text-muted mt-2">
                            La TPS (9,5%) peut être retenue par le client ou gérée par le prestataire selon l'accord.
                            La CSS (1%) est facturée au client mais reste à la charge du prestataire.
                        </p>
                    </div>
                </a>
            </div>


        </div>
    </div>

    <style>
        .hover-shadow {
            transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .hover-shadow:hover {
            transform: scale(1.04);
            z-index: 2;
        }
    </style>
    <script>
        function Simulateur_des_prets(event) {
            event.preventDefault(); // Empêche le lien de naviguer

            const url = "{{ route('Simulateur_des_prets') }}";
            const width = 1450;
            const height = 700;

            // Calcul de la position centrée
            const left = (window.screen.width / 2) - (width / 2);
            const top = (window.screen.height / 2) - (height / 2);

            // Ouverture de la popup centrée
            window.open(
                url,
                'Simulateur_des_prets',
                `width=${width},height=${height},top=${top},left=${left},scrollbars=yes,resizable=no`
            );
        }
    </script>
    <script>
        function SIMULATEUR_FACTURATION_hots_TVA(event) {
            event.preventDefault(); // Empêche le lien de naviguer

            const url = "{{ route('SIMULATEUR_FACTURATION_hots_TVA') }}";
            const width = 1450;
            const height = 700;

            // Calcul de la position centrée
            const left = (window.screen.width / 2) - (width / 2);
            const top = (window.screen.height / 2) - (height / 2);

            // Ouverture de la popup centrée
            window.open(
                url,
                'SIMULATEUR_FACTURATION_hots_TVA',
                `width=${width},height=${height},top=${top},left=${left},scrollbars=yes,resizable=no`
            );
        }
    </script>
    <script>
        document.getElementById("search-simulation").addEventListener("keyup", function() {
            let query = this.value.toLowerCase();
            document.querySelectorAll(".simulation-card").forEach(function(card) {
                let title = card.getAttribute("data-title").toLowerCase();
                card.style.display = title.includes(query) ? "" : "none";
            });
        });
    </script>


</div> <!-- end offcanvas-->
