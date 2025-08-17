@include('components/head2')

<style>
    .all-content-wrapper {
        display: none;
    }

    .preloader-single {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: white;
        display: flex;
        justify-content: center;
        align-items: center;
        z-index: 9999;
    }
</style>

<body>

    <!-- Preloader -->
    <div id="preloader" class="preloader-single">
        <div class="ts_preloading_box">
            <div id="ts-preloader-absolute16">
                <div class="tsperloader16" id="tsperloader16_one"></div>
                <div class="tsperloader16" id="tsperloader16_two"></div>
                <div class="tsperloader16" id="tsperloader16_three"></div>
                <div class="tsperloader16" id="tsperloader16_four"></div>
                <div class="tsperloader16" id="tsperloader16_big"></div>
            </div>
        </div>
    </div>
    @php
        $moduleNom = strtolower($module_nom);

    @endphp
    @switch($moduleNom)
        @case('smi')
            @include('components/smi/sidebar')
        @break

        @case('rh')
            @include('components/yodirh/sidebar')
        @break

        @case('documents owncloud')
            @include('components/cloudDoc/sidebar')
        @break

        @case('configurations')
            @include('components/configuration/sidebar')
        @break

        @default
            @include('components/yodirh/sidebar')
    @endswitch

    <!-- Ajout de l'ID requis pour le script -->
    <div id="main-content" class="page-wrapper all-content-wrapper">
        <div class="content">
            <div class="offcanvas offcanvas-start" tabindex="-1" id="offcanvasWithBackdrop"
                aria-labelledby="offcanvasWithBackdropLabel">
                <div class="offcanvas-header">
                    <h5 class="offcanvas-title ps-3 mb-3" id="offcanvasWithBackdropLabel"
                        style="border-left: 5px solid #05436b; color: #333;">
                        Actuellement sur : {{ Str::limit($entreprise_nom, 15, '...') }}
                    </h5>
                    <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas"
                        aria-label="Close"></button>
                </div> <!-- end offcanvas-header-->
                @php
                    $lesEntreprises = \App\Helpers\DateHelper::dossier_info();
                @endphp
                <div class="offcanvas-body">
                    <div class="p-3" style="overflow-y: auto;">
                        <div class="row row-cols-3 g-2">
                            @foreach ($lesEntreprises['entreprise'] as $entreprise)
                                <div class="col text-center  card-hover-zoom">
                                    <a href="{{ route('change_entreprise', $entreprise->id) }}"
                                        class="text-decoration-none text-dark d-block">
                                        <div class="d-flex align-items-center justify-content-center mx-auto mb-2 shadow"
                                            style="width: 80px;height: 70px; transition: transform 0.3s;border-radius: 5px;">
                                            <img src="{{ asset('storage/' . $entreprise->logo) }}"
                                                alt="{{ $entreprise->nom_entreprise }}" class="img-fluid rounded"
                                                style="width: 80px;height: 70px; object-fit: contain;border-radius: 5px;border-radius: 20px">
                                        </div>
                                        <small class="fw-medium d-block text-truncate"
                                            title="{{ $entreprise->nom_entreprise }}">{{ $entreprise->nom_entreprise }}</small>
                                    </a>
                                </div>
                            @endforeach
                            <style>
                                .card-hover-zoom {
                                    transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
                                }

                                .card-hover-zoom:hover {
                                    transform: scale(1.15);
                                    z-index: 2;
                                }
                            </style>
                        </div>
                        <div class="mt-4">
                            <a href="{{ route('components.liste_module') }}"
                                class="btn btn-outline-primary btn-sm w-100 btn-block"
                                style="margin-bottom: 10px;color:white;font-size: 12px">
                                <i class="ti ti-arrow-left"></i>
                                Retour sur la page d'actualité
                            </a>
                        </div>
                    </div>
                </div> <!-- end offcanvas-body-->
            </div>
            <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasWithBackdrop2"
                aria-labelledby="offcanvasWithBackdropLabel">
                <div class="offcanvas-header">
                    <h5 class="offcanvas-title ps-3 mb-3" id="offcanvasWithBackdropLabel"
                        style="border-left: 5px solid #05436b; color: #333;">
                        Gestion des caisses
                    </h5>
                    <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas"
                        aria-label="Close"></button>
                </div> <!-- end offcanvas-header-->
                {{-- @php
                    $lesEntreprises = \App\Helpers\DateHelper::dossier_info();
                @endphp   --}}
                <div class="offcanvas-body">
                    <div class="p-3" style="overflow-y: auto;">
                        <div class="input-group mb-3">
                            <input type="text" id="search-caisse" class="form-control"
                                placeholder="🔍 Rechercher une caisse..." aria-label="Recherche intelligente">
                            <span class="input-group-text"><i class="ti ti-search"></i></span>
                        </div>
                        <div class="row row-cols-3 g-2">
                            <div class="col text-center card-hover-zoom2">
                                <a href="#" data-bs-toggle="modal" data-bs-target="#login-modal"
                                    class="text-decoration-none text-dark d-block">
                                    <div class="d-flex align-items-center justify-content-center mx-auto mb-2 shadow"
                                        style="width: 80px;height: 70px; transition: transform 0.3s;border-radius: 5px;">
                                        <img src="{{ asset('assets/img/yodigest.png') }}" alt="YODIGEST"
                                            class="img-fluid rounded"
                                            style="width: 80px;height: 70px; object-fit: contain;border-radius: 5px;border-radius: 20px">
                                    </div>
                                    <small class="fw-medium d-block text-truncate" title="YODIGEST">YODIGEST</small>
                                </a>
                            </div>
                            <style>
                                .card-hover-zoom2 {
                                    transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
                                }

                                .card-hover-zoom2:hover {
                                    transform: scale(1.10);
                                    z-index: 2;
                                }
                            </style>
                            <script>
                                document.getElementById('search-caisse').addEventListener('input', function() {
                                    const input = this.value.toLowerCase();
                                    const items = document.querySelectorAll('.card-hover-zoom2');

                                    items.forEach(function(item) {
                                        const text = item.innerText.toLowerCase();
                                        item.style.display = text.includes(input) ? 'block' : 'none';
                                    });
                                });
                            </script>
                        </div>
                    </div>
                </div> <!-- end offcanvas-body-->
            </div>
            <div id="login-modal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-body">
                            <div class="text-center mt-2 mb-4">
                                <div class="auth-logo">
                                    <a href="#" class="logo logo-dark">
                                        <span class="logo-lg">
                                            <img src="{{ asset('assets/img/yodigest.png') }}" alt="Logo"
                                                height="42">
                                        </span>
                                    </a>
                                </div>
                            </div>

                            <form id="login-caisse-form" action="{{ route('caisse.login') }}" method="POST"
                                class="px-3">
                                @csrf

                                <input type="hidden" name="codesociete" value="YOD"> {{-- à adapter dynamiquement si nécessaire --}}
                                <div id="login-error" class="text-danger text-center mt-2" style="display:none;">
                                </div>
                                <div class="mb-3">
                                    <label for="login" class="form-label">Identifiant</label>
                                    <input class="form-control" type="text" id="login" required
                                        name="login" placeholder="Identifiant">
                                </div>

                                <div class="mb-3">
                                    <label for="mdp" class="form-label">Mot de passe</label>
                                    <input class="form-control" type="password" id="mdp" name="mdp"
                                        placeholder="Mot de passe">
                                </div>

                                <div class="mb-2 text-center">
                                    <button class="btn rounded-pill btn-primary" type="submit">Se connecter</button>
                                </div>


                            </form>
                        </div>
                    </div>
                </div>
            </div>
            @include('components/header2')
            @yield('content2')
        </div>
        {{-- @include('components/footer') --}}
    </div>

    @include('components/script2')
    <script>
        setTimeout(function() {
            document.querySelectorAll('.alertMasque').forEach(function(alert) {
                alert.style.display = 'none';
            });
        }, 1200);
    </script>
    <script>
        function Paie(event) {
            event.preventDefault(); // Empêche le lien de naviguer

            const url = "{{ route('paie') }}";
            const width = 1450;
            const height = 700;

            // Calcul de la position centrée
            const left = (window.screen.width / 2) - (width / 2);
            const top = (window.screen.height / 2) - (height / 2);

            // Ouverture de la popup centrée
            window.open(
                url,
                'annuaire',
                `width=${width},height=${height},top=${top},left=${left},scrollbars=yes,resizable=no`
            );
        }
    </script>
    <script>
        // On utilise jQuery car votre code l'utilise déjà
        $('#login-caisse-form').on('submit', function(e) {
            e.preventDefault(); // Empêche le rechargement de la page
            const form = $(this);
            const errorDiv = $('#login-error');
            const submitButton = form.find('button[type="submit"]');

            // Cacher les erreurs et désactiver le bouton pour éviter les double-clics
            errorDiv.hide().text('');
            submitButton.prop('disabled', true).text('Connexion...');

            $.ajax({
                url: form.attr('action'),
                type: 'POST',
                data: form.serialize(),
                success: function(response) {
                    // Si la réponse contient une 'redirect_url', on y va !
                    if (response.success && response.redirect_url) {
                        window.location.href = response.redirect_url;
                    } else {
                        // Cas d'erreur inattendu où success=true mais pas d'URL
                        errorDiv.text(response.message || 'Une erreur de redirection est survenue.')
                            .show();
                        submitButton.prop('disabled', false).text('Se connecter');
                    }
                },
                error: function(xhr) {
                    // Gérer les erreurs (401, 500, etc.)
                    let message = xhr.responseJSON?.message || 'Une erreur est survenue.';
                    errorDiv.text(message).show();
                    submitButton.prop('disabled', false).text('Se connecter');
                }
            });
        });
    </script>

    <script>
        // 🔌 GESTION CONNEXION PERDUE
        function showOfflinePopup() {
            const existingPopup = document.getElementById('offline-popup');
            if (existingPopup) return;

            const popup = document.createElement('div');
            popup.id = 'offline-popup';
            popup.innerHTML = `
                <div class="alert alert-danger text-center position-fixed bottom-0 start-0 end-0 m-3 shadow" role="alert" style="z-index: 9999;">
                    📡 Connexion perdue.
                </div>
            `;
            document.body.appendChild(popup);
        }

        function showOnlinePopup() {
            const popup = document.createElement('div');
            popup.id = 'online-popup';
            popup.innerHTML = `
                <div class="alert alert-success text-center position-fixed bottom-0 start-0 end-0 m-3 shadow" role="alert" style="z-index: 9999;">
                    ✅ Connexion rétablie.
                </div>
            `;
            document.body.appendChild(popup);
            setTimeout(() => popup.remove(), 4000);
        }

        function removeOfflinePopup() {
            const popup = document.getElementById('offline-popup');
            if (popup) popup.remove();
            showOnlinePopup();
        }

        window.addEventListener('offline', showOfflinePopup);
        window.addEventListener('online', removeOfflinePopup);

        if (!navigator.onLine) {
            showOfflinePopup();
        }

        // 📶 GESTION QUALITÉ RÉSEAU INTERNET
        function checkNetworkQuality() {
            const start = Date.now();
            fetch(window.location.href, {
                    method: 'HEAD',
                    cache: 'no-store'
                })
                .then(() => {
                    const duration = Date.now() - start;
                    let message = '';
                    if (duration < 100) {
                        message = '🚀 Réseau excellent';
                    } else if (duration < 500) {
                        message = '📶 Réseau moyen';
                    } else {
                        message = '🐢 Réseau lent';
                    }

                    const quality = document.createElement('div');
                    quality.className = 'alert alert-info text-center position-fixed bottom-0 start-0 end-0 m-3 shadow';
                    quality.style.zIndex = 9999;
                    quality.innerText = message;
                    document.body.appendChild(quality);
                    setTimeout(() => quality.remove(), 1000);
                });
        }

        setInterval(checkNetworkQuality, 60000); // Test de réseau toutes les 60s

        // ⏱️ GESTION SESSION EXPIRÉE
        function showSessionExpiredPopup() {
            const existingPopup = document.getElementById('session-popup');
            if (existingPopup) return;

            const popup = document.createElement('div');
            popup.id = 'session-popup';
            popup.innerHTML = `
                <div class="alert alert-warning text-center position-fixed bottom-0 start-0 end-0 m-3 shadow" role="alert" style="z-index: 9999;">
                    ⌛ Session expirée. Redirection en cours...
                </div>
            `;
            document.body.appendChild(popup);

            setTimeout(() => {
                window.location.href = "/liste_modules";
            }, 3000);
        }

        function checkSessionExpired() {
            fetch(window.location.href, {
                    method: 'HEAD',
                    cache: 'no-store'
                })
                .then(response => {
                    if (response.status === 419 || response.status === 401) {
                        showSessionExpiredPopup();
                    }
                })
                .catch(() => {
                    showOfflinePopup();
                });
        }

        setInterval(checkSessionExpired, 60000); // Vérifie expiration session toutes les 60s
    </script>

    <script>
        // Assurez-vous que ce code est placé après l'inclusion des bibliothèques ci-dessus

        $(document).ready(function() {

            // 1. Initialisation des Datepickers (calendriers)
            // On cible tous les conteneurs qui ont la classe 'js-datepicker'
            $('.js-datepicker .input-group.date').datepicker({
                format: 'dd/mm/yyyy', // Format de date français
                todayBtn: "linked",
                keyboardNavigation: false,
                forceParse: false,
                calendarWeeks: true,
                autoclose: true,
                language: 'fr' // Assurez-vous d'inclure le fichier de langue si nécessaire
            });


            // 2. Initialisation de Select2 (listes déroulantes améliorées)
            // On cible toutes les listes qui ont la classe 'js-select2'
            $('.js-select2').select2({
                placeholder: "Sélectionnez une option",
                allowClear: true // Ajoute une croix pour vider la sélection
            });


            // 3. Initialisation des masques de saisie (avec IMask.js)
            // On cible tous les champs qui ont un attribut 'data-mask'
            document.querySelectorAll('[data-mask]').forEach(input => {
                IMask(input, {
                    mask: input.dataset.mask // On récupère le masque depuis l'attribut data-mask
                });
            });

        });
    </script>

    <script>
        // Nombre de tâches de chargement en cours
        let pendingTasks = 0;

        function showPreloader() {
            document.getElementById('preloader').style.display = 'flex';
            document.getElementById('main-content').style.display = 'none';
        }

        function hidePreloader() {
            document.getElementById('preloader').style.display = 'none';
            document.getElementById('main-content').style.display = 'block';
        }

        function addTask() {
            pendingTasks++;
            showPreloader();
        }

        function completeTask() {
            pendingTasks = Math.max(0, pendingTasks - 1);
            if (pendingTasks === 0) {
                hidePreloader();
            }
        }

        // Charger la page = 1 tâche
        addTask();

        window.addEventListener('load', function() {
            completeTask(); // Fin du chargement initial
        });

        // Exemple d'utilisation :
        // addTask();
        // fetch('/api/data')
        //     .then(response => response.json())
        //     .then(data => {
        //         // traitement
        //     })
        //     .finally(() => completeTask());
    </script>
</body>

</html>
