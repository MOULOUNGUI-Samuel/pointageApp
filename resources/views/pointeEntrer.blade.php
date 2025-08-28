<!doctype html>
<html class="no-js" lang="">

<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>NedCore</title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- favicon
  ============================================ -->
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('src/images/Logo_Nedco.png') }}">
    <!-- Google Fonts
  ============================================ -->
    <link href="https://fonts.googleapis.com/css?family=Roboto:100,300,400,700,900" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap CSS
  ============================================ -->
    <link rel="stylesheet" href="{{ asset('src/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('icomoon/style.css') }}">
    <!-- font awesome CSS
  ============================================ -->
    <link rel="stylesheet" href="{{ asset('src/css/font-awesome.min.css') }}">
    <!-- owl.carousel CSS
  ============================================ -->
    <link rel="stylesheet" href="{{ asset('src/css/owl.carousel.css') }}">
    <link rel="stylesheet" href="{{ asset('src/css/owl.theme.css') }}">
    <link rel="stylesheet" href="{{ asset('src/css/owl.transitions.css') }}">
    <!-- animate CSS
  ============================================ -->
    <link rel="stylesheet" href="{{ asset('src/css/animate.css') }}">
    <!-- normalize CSS
  ============================================ -->
    <link rel="stylesheet" href="{{ asset('src/css/normalize.css') }}">
    <!-- mCustomScrollbar CSS
  ============================================ -->
    <link rel="stylesheet" href="{{ asset('src/css/scrollbar/jquery.mCustomScrollbar.min.css') }}">
    <!-- wave CSS
  ============================================ -->
    <link rel="stylesheet" href="{{ asset('src/css/wave/waves.min.css') }}">
    <!-- Notika icon CSS
  ============================================ -->
    <link rel="stylesheet" href="{{ asset('src/css/notika-custom-icon.css') }}">
    <!-- main CSS
  ============================================ -->
    <link rel="stylesheet" href="{{ asset('src/css/main.css') }}">
    <!-- style CSS
  ============================================ -->
    <link rel="stylesheet" href="{{ asset('src/style.css') }}">
    <!-- responsive CSS
  ============================================ -->
    <link rel="stylesheet" href="{{ asset('src/css/responsive.css') }}">
    <!-- modernizr JS
  ============================================ -->
    <script src="{{ asset('src/js/vendor/modernizr-2.8.3.min.js') }}"></script>
</head>
<style>
    html,
    body {
        height: 100%;
    }

    .center-container {
        display: flex;
        justify-content: center;
        /* Centre horizontalement */
        align-items: center;
        /* Centre verticalement */
        height: 100vh;
        /* Prend toute la hauteur de l'écran */
    }

    .btn-gradient {
        background: linear-gradient(135deg, #3f81b3d8, #d0d7db);
        border: none;
        border-radius: 10px;
        padding: 10px 24px;
        color: white;
        font-size: 16px;
        font-weight: bold;
        transition: all 0.3s ease-in-out;
        /* box-shadow: 0px 0px 20px rgba(255, 255, 255, 0.4); Grand shadow blanc */
    }

    .btn-gradient1 {
        background: linear-gradient(135deg, #7a785ad8, #d0d6db);
        border: none;
        border-radius: 10px;
        padding: 10px 24px;
        color: white;
        font-size: 16px;
        font-weight: bold;
        transition: all 0.3s ease-in-out;
        /* box-shadow: 0px 0px 20px rgba(255, 255, 255, 0.26);  */
    }

    .btn-gradient:hover {
        opacity: 0.8;
        transform: scale(1.05);
    }

    .spinner {
        display: none;
        position: absolute;
        right: 45%;
        top: 50%;
        transform: translateY(-50%);
        width: 30px;
        height: 30px;
        border: 5px solid white;
        border-top: 3px solid transparent;
        border-radius: 50%;
        animation: spin 1s linear infinite;
    }

    .btn.loading .spinner {
        display: inline-block;
    }
</style>

<body
    style="background: linear-gradient(rgba(0, 0, 0, 0.795), rgba(0, 0, 0, 0.836)),
url('{{ asset('src/images/login.webp') }}') no-repeat center center;
background-size: cover;
background-attachment: fixed;
color: #fff;">
    <!--[if lt IE 8]>
            <p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
        <![endif]-->
    <!-- Login Register area Start-->

    {{-- <div class="center-container"> --}}
    <div class="container-fluid">
        <div class="row">
            <div class="col-6 text-left" style="margin-top: 80px;">
                <a href="{{ route('loginPointe') }}">
                    <i class="fa fa-arrow-left text-white" style="font-size: 2.5rem;"></i>
                </a>
            </div>
        </div>
        <div class="row" style="margin-top: 150px">
            {{-- <div class="col-md-2 text-left">
                    <img src="{{ asset('src/images/YODIPOINTE.png') }}" alt="Logo" class="mb-4"
                        style="max-width: 150px;">
                </div> --}}
            <div class="col-md-12">
                <div class="text-center my-1">
                    <h1 id="currentTime" class="display-3 fw-bold" style="font-size: 60px; color: #f7f7f7;"></h1>
                </div>
                <div class="text-center my-1">
                    <h4>Bienvenue ! Vous pointez pour : <span id="currentDateTime" style="font-size: 25px"></span>
                    </h4>
                </div>
                <script>
                    function updateDateTime() {
                        let now = new Date();
                        let dateTimeString = now.toLocaleString("fr-FR", {
                            weekday: "long",
                            year: "numeric",
                            month: "long",
                            day: "numeric"
                        });
                        document.getElementById("currentDateTime").innerText = dateTimeString;
                    }
                    setInterval(updateDateTime, 1000); // Met à jour chaque seconde
                    updateDateTime();
                </script>
                <script>
                    function updateTime() {
                        let now = new Date();
                        let hours = now.getHours().toString().padStart(2, '0');
                        let minutes = now.getMinutes().toString().padStart(2, '0');
                        let seconds = now.getSeconds().toString().padStart(2, '0');
                        document.getElementById("currentTime").innerText = hours + ":" + minutes + ":" + seconds;
                    }

                    setInterval(updateTime, 1000); // Met à jour l'heure chaque seconde
                    updateTime(); // Exécute immédiatement au chargement
                </script>
                @if (session('success'))
                    <div class="alert alert-success " role="alert">
                        <i class="icon-user-check1" style="font-size: 20px;margin-right:10px"></i><strong>Succès
                            !</strong> {{ session('success') }}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Fermer">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endif

                @if (session('error'))
                    <div class="alert alert-danger" role="alert">
                        <i class="icon-warning" style="font-size: 20px;margin-right:10px"></i><strong>Rappel
                            !</strong> {{ session('error') }}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Fermer">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endif

                <div class="row">
                    <div class="col"></div>
                    <div class="col-md-10 col-sm-12">
                        @if ($errors->any())
                            <div class="alert alert-danger text-left" style="font-size: 16px" role="alert">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li style="display: flex; justify-content: space-between;">
                                            <span><i class="icon-warning" style="font-size: 20px"></i>
                                                {{ $error }}</span>
                                            <button type="button" class="btn-close" data-dismiss="alert"
                                                aria-label="Close"></button>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                    </div>
                    <div class="col"></div>
                </div>
            </div>
            <div class="col-md-12">
                <form action="{{ route('login') }}" method="post">
                    @csrf
                    <div class="row p-3">
                        <input type="hidden" id="latitude" name="latitude" value="{{ old('latitude') }}"
                            style="color:black">
                        <input type="hidden" id="longitude" name="longitude" value="{{ old('longitude') }}"
                            style="color:black">
                        <input type="hidden" name="pointage_entrer" value="1">

                        <div class="col-md-12 col-sm-12">
                            <div class="input-group shadow-sm rounded mt-4"
                                style="background: none;border-bottom: 1px solid #fff">
                                <span class="input-group-addon nk-ic-st-pro"><i class="icon-lock"
                                        style="font-size: 25px"></i></span>
                                <input type="text" class="form-control text-white"
                                    placeholder="Identifiant de connexion"
                                    style="border:none;padding: 20px;background: transparent" name="matricule"
                                    autocomplete="off" autocapitalize="off" spellcheck="false" required>
                            </div>

                            <div class="input-group mt-3 shadow-sm rounded"
                                style="background: none; border-bottom: 1px solid #fff">
                                <span class="input-group-addon nk-ic-st-pro">
                                    <i class="icon-key" style="font-size: 25px"></i>
                                </span>
                                <div class="nk-int-st">
                                    <input type="password" id="passwordField" class="form-control text-white"
                                        placeholder="Mot de passe" name="password"
                                        style="border: none; padding: 20px; background: transparent"
                                        autocomplete="new-password" required>
                                </div>
                                <!-- Icône pour afficher/masquer -->
                                <span class="input-group-addon nk-ic-st-pro" onclick="togglePassword()">
                                    <i id="toggleIcon" class="icon-eye"
                                        style="font-size: 25px; cursor: pointer;"></i>
                                </span>
                            </div>

                            <div class="text-center mt-5">
                                {{-- <a href="{{ url('/loginPointe') }}" class="btn btn-gradient1 loading-btn">
                                    <i class="icon-close-solid"></i> Annuler
                                    <span class="spinner"></span>
                                </a> --}}
                                <button type="submit" class="btn btn-gradient loading-btn w-75">
                                    <i class="icon-save-disk"></i> Vaider le pointage
                                    <span class="spinner"></span>
                                </button>

                            </div>
                        </div>

                    </div>
                </form>
            </div>

        </div>
    </div>
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
                window.location.href = "/loginPointe";
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
        document.addEventListener('DOMContentLoaded', () => {
            const buttons = document.querySelectorAll('.loading-btn');

            buttons.forEach(button => {
                button.addEventListener('click', function(event) {
                    const form = button.closest('form');
                    const type = button.getAttribute('type') || 'submit';

                    // Si le bouton est de type submit et qu'il est dans un formulaire
                    if (type === 'submit' && form) {
                        // Si formulaire invalide, bloquer la soumission pour afficher les erreurs natives
                        if (!form.checkValidity()) {
                            event.preventDefault();
                            form
                                .reportValidity(); // affiche les erreurs HTML5 (required, pattern, etc.)
                            return;
                        }
                        // Si valide : laisser faire la soumission, mais activer le loading
                        button.classList.add('loading');
                        // button.disabled = true;
                    }

                    // Si c’est un bouton normal (type="button"), on le désactive directement
                    if (type === 'button') {
                        button.classList.add('loading');
                        button.disabled = true;
                        // ... tu peux faire une action JS ici (ex: AJAX, etc.)
                    }
                });
            });
        });
    </script>
    {{-- </div> --}}
    <script>
        if ("geolocation" in navigator) {
            navigator.geolocation.getCurrentPosition(
                function(position) {
                    const latitude = position.coords.latitude;
                    const longitude = position.coords.longitude;

                    console.log("Latitude:", latitude);
                    console.log("Longitude:", longitude);

                    // Tu peux ensuite les envoyer à Laravel par AJAX ou les insérer dans un formulaire :
                    document.getElementById("latitude").value = latitude;
                    document.getElementById("longitude").value = longitude;
                },
                function(error) {
                    console.error("Erreur de géolocalisation :", error.message);
                }
            );
        } else {
            alert("La géolocalisation n’est pas prise en charge par ce navigateur.");
        }
    </script>
    <script>
        function togglePassword() {
            let passwordField = document.getElementById("passwordField");
            let toggleIcon = document.getElementById("toggleIcon");

            if (passwordField.type === "password") {
                passwordField.type = "text"; // Affiche le mot de passe
                toggleIcon.classList.remove("icon-eye");
                toggleIcon.classList.add("icon-eye-blocked"); // Change l'icône
            } else {
                passwordField.type = "password"; // Masque le mot de passe
                toggleIcon.classList.remove("icon-eye-blocked");
                toggleIcon.classList.add("icon-eye");
            }
        }
    </script>
    <!-- Login Register area End-->
    <!-- jquery
  ============================================ -->
    <script src="{{ asset('src/js/vendor/jquery-1.12.4.min.js') }}"></script>
    <!-- bootstrap JS
  ============================================ -->
    <script src="{{ asset('src/js/bootstrap.min.js') }}"></script>
    <!-- wow JS
  ============================================ -->
    <script src="{{ asset('src/js/wow.min.js') }}"></script>
    <!-- price-slider JS
  ============================================ -->
    <script src="{{ asset('src/js/jquery-price-slider.js') }}"></script>
    <!-- owl.carousel JS
  ============================================ -->
    <script src="{{ asset('src/js/owl.carousel.min.js') }}"></script>
    <!-- scrollUp JS
  ============================================ -->
    <script src="{{ asset('src/js/jquery.scrollUp.min.js') }}"></script>
    <!-- meanmenu JS
  ============================================ -->
    <script src="{{ asset('src/js/meanmenu/jquery.meanmenu.js') }}"></script>
    <!-- counterup JS
  ============================================ -->
    <script src="{{ asset('src/js/counterup/jquery.counterup.min.js') }}"></script>
    <script src="{{ asset('src/js/counterup/waypoints.min.js') }}"></script>
    <script src="{{ asset('src/js/counterup/counterup-active.js') }}"></script>
    <!-- mCustomScrollbar JS
  ============================================ -->
    <script src="{{ asset('src/js/scrollbar/jquery.mCustomScrollbar.concat.min.js') }}"></script>
    <!-- sparkline JS
  ============================================ -->
    <script src="{{ asset('src/js/sparkline/jquery.sparkline.min.js') }}"></script>
    <script src="{{ asset('src/js/sparkline/sparkline-active.js') }}"></script>
    <!-- flot JS
  ============================================ -->
    <script src="{{ asset('src/js/flot/jquery.flot.js') }}"></script>
    <script src="{{ asset('src/js/flot/jquery.flot.resize.js') }}"></script>
    <script src="{{ asset('src/js/flot/flot-active.js') }}"></script>
    <!-- knob JS
  ============================================ -->
    <script src="{{ asset('src/js/knob/jquery.knob.js') }}"></script>
    <script src="{{ asset('src/js/knob/jquery.appear.js') }}"></script>
    <script src="{{ asset('src/js/knob/knob-active.js') }}"></script>
    <!--  Chat JS
  ============================================ -->
    <script src="{{ asset('src/js/chat/jquery.chat.js') }}"></script>
    <!--  wave JS
  ============================================ -->
    <script src="{{ asset('src/js/wave/waves.min.js') }}"></script>
    <script src="{{ asset('src/js/wave/wave-active.js') }}"></script>
    <!-- icheck JS
  ============================================ -->
    <script src="{{ asset('src/js/icheck/icheck.min.js') }}"></script>
    <script src="{{ asset('src/js/icheck/icheck-active.js') }}"></script>
    <!--  todo JS
  ============================================ -->
    <script src="{{ asset('src/js/todo/jquery.todo.js') }}"></script>
    <!-- Login JS
  ============================================ -->
    <script src="{{ asset('src/js/login/login-action.js') }}"></script>
    <!-- plugins JS
  ============================================ -->
    <script src="{{ asset('src/js/plugins.js') }}"></script>
    <!-- main JS
  ============================================ -->
    <script src="{{ asset('src/js/main.js') }}"></script>

</body>

</html>
