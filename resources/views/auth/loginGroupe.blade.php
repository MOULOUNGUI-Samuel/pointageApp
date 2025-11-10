<!DOCTYPE html>
<html lang="fr">

<head>

    <!-- Meta Tags -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description"
        content="NedCore est la solution numérique centralisée du Groupe NedCo, dédiée à la gestion intégrée de toutes les entités du groupe. Grâce à une interface moderne et intuitive, chaque collaborateur accède aux modules essentiels : gestion RH, finances, projets, documents et reporting stratégique">
    <meta name="keywords"
        content="NedCore, solution numérique, Groupe NedCo, gestion intégrée, gestion RH, finances, projets, documents, reporting stratégique, interface moderne, outils d'entreprise">
    <meta name="author" content="Dreams Technologies">
    <meta name="robots" content="index, follow">

    <!-- Title -->
    <title>NedCore</title>
    <!-- Apple Touch Icon -->
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('assets/img/authentication/logo_nedcore.JPG') }}">
    <!-- Favicon -->
    <link rel="icon" href="{{ asset('assets/img/authentication/logo_nedcore.JPG') }}" type="image/x-icon">
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('assets/img/authentication/logo_nedcore.JPG') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Tabler Icon CSS -->
    <link rel="stylesheet" href="{{ asset('assets/plugins/tabler-icons/tabler-icons.css') }}">

    <!-- Fontawesome CSS -->
    <link rel="stylesheet" href="{{ asset('assets/plugins/fontawesome/css/fontawesome.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/plugins/fontawesome/css/all.min.css') }}">

    <!-- Main CSS -->
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">

</head>

<body class="account-page">

    <!-- Main Wrapper -->
    <div class="main-wrapper">

        <div class="account-content">
            <div class="d-flex flex-wrap w-100 vh-100 overflow-hidden account-bg-01">
                <div
                    class="d-flex align-items-center justify-content-center flex-wrap vh-100 overflow-auto p-4 w-50 bg-backdrop">
                    <form action="{{ route('login') }}" method="POST" class="needs-validation" novalidate>
                        @csrf
                        {{-- <div class="d-block d-lg-none">
                            <input type="text" name="mobileforme" value="1">
                        </div> --}}
                        <div class="mx-auto w-100" style="max-width: 600px;">
                            <div class="text-center mb-4">
                                <img src="{{ asset('assets/img/authentication/logo_nedcore.png') }}" class="img-fluid"
                                    alt="Logo" style="max-width: 200px;">
                            </div>
                            <div class="mb-4">
                                <h4 class="mb-2 fs-20">Se connecter</h4>
                                <p>Accédez à NedCore en utilisant votre code entreprise,votre identifiant et votre mot
                                    de passe.</p>
                            </div>
                            @if (session('error'))
                                <div class="mb-3">
                                    <div class="alert alert-danger text-center mb-3" style="font-size: 16px">
                                        <i class="fas fa-exclamation-circle mr-2" style="font-size: 18px;"></i>
                                        {{ session('error') }}
                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                </div>
                            @endif
                            @if ($errors->any())
                                <div class="alert alert-danger alert-dismissible fade show" role="alert"
                                    style="background-color: #f8d7da; color: #d8000c; border: 1px solid #f5c6cb; border-radius: 6px; font-size: 16px;">
                                    <ul class="mb-0" style="list-style: none; padding-left: 0;">
                                        @foreach ($errors->all() as $error)
                                            <li
                                                style="display: flex; justify-content: space-between; align-items: center;">
                                                <span>
                                                    <i class="fas fa-exclamation-circle mr-2"
                                                        style="font-size: 18px;"></i>
                                                    {{ $error }}
                                                </span>
                                            </li>
                                        @endforeach
                                    </ul>
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Fermer"
                                        style="position: absolute; top: 8px; right: 12px; background-color: white; border: 1px solid #000; border-radius: 4px; width: 24px; height: 24px; line-height: 12px; padding: 0;">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                            @endif

                            <div class="mb-3">
                                <label class="col-form-label">Code entreprise</label>
                                <div class="position-relative">
                                    <div class="input-group">
                                        <input type="text" name="code_entreprise" id="validationCustom01"
                                            value="{{ old('code_entreprise') }}" class="form-control form-control-lg"
                                            required>
                                        <button class="btn btn-outline-primary dropdown-toggle" type="button"
                                            data-bs-toggle="dropdown" aria-expanded="false">Choisir</button>
                                        <ul class="dropdown-menu dropdown-menu-end">
                                            @foreach ($entreprises as $entreprise)
                                                <li><a class="dropdown-item" id="code_entreprise"
                                                        href="#">{{ $entreprise->code_entreprise }}</a>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                    <div class="invalid-feedback">
                                        Veuillez renseigner le code entreprise.
                                    </div>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="col-form-label">Identifiant</label>
                                <div class="position-relative">
                                    <span class="input-icon-addon">
                                        <i class="ti ti-mail"></i>
                                    </span>
                                    <input type="text" name="matricule" value="{{ old('matricule') }}"
                                        id="validationCustom02" class="form-control form-control-lg" required>
                                    <div class="invalid-feedback">
                                        Veuillez renseigner votre identifiant.
                                    </div>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="col-form-label">Mot de passe</label>
                                <div class="pass-group">
                                    <input type="password" id="validationCustom03" name="password"
                                        value="{{ old('password') }}" autocomplete="current-password"
                                        class="pass-input form-control form-control-lg" required>
                                    <span class="ti toggle-password ti-eye-off"></span>
                                </div>
                                <div class="invalid-feedback">
                                    Veuillez renseigner votre mot de passe.
                                </div>
                            </div>
                            <div class="d-flex align-items-center justify-content-between mb-3">
                                <div class="form-check form-check-md d-flex align-items-center">
                                    <input class="form-check-input form-control-md" type="checkbox" value=""
                                        id="checkebox-md" checked="">
                                    <label class="form-check-label" for="checkebox-md">
                                        Souviens-toi de moi
                                    </label>
                                </div>
                                <div class="text-end">
                                    <a href="forgot-password.html" class="text-primary fw-medium link-hover">Mot de
                                        passe oublié ?</a>
                                </div>
                            </div>
                            <div class="mb-5">
                                <button type="submit" class="btn-action btn btn-primary w-100"
                                    data-loader-target="connecter">Se connecter</button>
                                <!-- Bouton initial -->
                                <!-- Bouton de chargement (caché au départ) -->
                                <button type="button" id="connecter" class="btn btn-outline-primary w-100"
                                    style="display: none;" disabled>
                                    <i class="fa fa-spinner fa-spin me-2"></i> Connexion en cours...
                                </button>
                            </div>
                            
                            <div class="text-center">
                                <p class="fw-medium text-gray">Copyright © 2025. Tous droits réservés. YodIngenierie
                                    Gabon</p>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- /Main Wrapper -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Gestion de la sélection du code entreprise
            document.querySelectorAll('.dropdown-item').forEach(item => {
                item.addEventListener('click', function(e) {
                    e.preventDefault();
                    const codeEntreprise = this.textContent.trim();
                    document.getElementById('validationCustom01').value = codeEntreprise;
                });
            });

            // Cible tous les boutons ayant l'attribut data-loader-target
            document.querySelectorAll('.btn-action[data-loader-target]').forEach(function(btn) {
                btn.addEventListener('click', function(event) {
                    // Récupérez les champs du formulaire par leur ID
                    const validationCustom01 = document.getElementById('validationCustom01');
                    const validationCustom02 = document.getElementById('validationCustom02');
                    const validationCustom03 = document.getElementById('validationCustom03');

                    // Vérifiez si l'un des champs est vide (après avoir retiré les espaces)
                    if (validationCustom01.value.trim() === '' || validationCustom03.value
                        .trim() === '' || validationCustom03.value.trim() === '') {
                        // 1. Empêche la soumission du formulaire
                        // 3. On arrête l'exécution ici, le bouton ne sera pas masqué
                        return;
                    }

                    // Si les champs sont remplis, on exécute le code original
                    const targetId = btn.getAttribute('data-loader-target');
                    const loaderBtn = document.getElementById(targetId);

                    if (loaderBtn) {
                        btn.style.display = 'none';
                        loaderBtn.style.display = 'inline-block';
                    }
                });
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
                window.location.href = "/loginGroupe";
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

    <!-- jQuery -->
    <script src="{{asset('assets/js/jquery-3.7.1.min.js')}}" type="f5900ce986c61fa82aeb3155-text/javascript"></script>

    <!-- Bootstrap Core JS -->
    <script src="{{asset('assets/js/bootstrap.bundle.min.js')}}" type="f5900ce986c61fa82aeb3155-text/javascript"></script>

    <!-- Feather Icon JS -->
    <script src="{{asset('assets/js/feather.min.js')}}" type="f5900ce986c61fa82aeb3155-text/javascript"></script>

    <!-- Slimscroll JS -->
    <script src="{{asset('assets/js/jquery.slimscroll.min.js')}}" type="f5900ce986c61fa82aeb3155-text/javascript"></script>

    <!-- Custom JS -->
    <script src="{{asset('assets/js/script.js')}}" type="f5900ce986c61fa82aeb3155-text/javascript"></script>

    <script src="{{ asset('assets/scripts/7d0fa10a/cloudflare-static/rocket-loader.min.js') }}"
        data-cf-settings="f5900ce986c61fa82aeb3155-|49" defer></script>
    <script defer src="https://static.cloudflareinsights.com/beacon.min.js/vcd15cbe7772f49c399c6a5babf22c1241717689176015"
        integrity="sha512-ZpsOmlRQV6y907TI0dKBHq9Md29nnaEIPlkf84rnaERnq6zvWvPUqr2ft8M1aS28oN72PdrCzSjY4U6VaAw1EQ=="
        data-cf-beacon='{"rayId":"94a720077e49e3c0","version":"2025.5.0","serverTiming":{"name":{"cfExtPri":true,"cfEdge":true,"cfOrigin":true,"cfL4":true,"cfSpeedBrain":true,"cfCacheStatus":true}},"token":"3ca157e612a14eccbb30cf6db6691c29","b":1}'
        crossorigin="anonymous"></script>
</body>


<!-- Mirrored from crms.dreamstechnologies.com/html/template/login.html by HTTrack Website Copier/3.x [XR&CO'2014], Wed, 04 Jun 2025 11:36:40 GMT -->

</html>
