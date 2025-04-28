<!doctype html>
<html class="no-js" lang="">

<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>Yodipointe</title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- favicon
  ============================================ -->
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('src/img/') }}">
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

    .btn {
        position: relative;
        padding: 10px 20px;
        font-size: 16px;
        cursor: pointer;
        margin: 10px;
    }

    .btn[disabled] {
        opacity: 0.6;
        cursor: not-allowed;
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

    @keyframes spin {
        0% {
            transform: translateY(-50%) rotate(0deg);
        }

        100% {
            transform: translateY(-50%) rotate(360deg);
        }
    }
</style>

<body
    style="background: linear-gradient(rgba(0, 0, 0, 0.795), rgba(0, 0, 0, 0.836)),
url('{{ asset('src/images/admin.jpg') }}') no-repeat center center;
background-size: cover;
background-attachment: fixed;
color: #fff;">
    <!-- Login Register area Start-->
    <div class="center-container">

        <div class="container px-5 d-none d-lg-block">
            <div class="row">
                <div class="col-md-12">
                    <div class="text-center my-1">
                        <img src="{{ asset('src/images/YODIPOINTE.png') }}" alt="Logo" class="mb-4"
                            style="max-width: 150px;">
                        <h4>Bienvenue ! Veuillez vous connecter pour continuer.
                        </h4>
                    </div>

                </div>
                <div class="col-md-12">
                    <div class="row">
                        <div class="col"></div>
                        <div class="col-md-6">
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
                    <form action="{{ route('login') }}" method="post">
                        @csrf
                        <div class="row p-3">
                            <div class="col"></div>
                            <div class="col-md-6 col-sm-12">
                                <div class="input-group shadow-sm rounded mt-4"
                                    style="background: none;border-bottom: 1px solid #fff">
                                    <span class="input-group-addon nk-ic-st-pro"><i class="icon-lock"
                                            style="font-size: 25px"></i></span>
                                    <input type="text" class="form-control text-white" name="matricule"
                                        value="{{ old('matricule') }}" placeholder="Votre identifiant"
                                        style="border:none;padding: 20px;background: transparent" required>
                                </div>

                                <div class="input-group mt-3 shadow-sm rounded"
                                    style="background: none; border-bottom: 1px solid #fff">
                                    <span class="input-group-addon nk-ic-st-pro">
                                        <i class="icon-key" style="font-size: 25px"></i>
                                    </span>
                                    <div class="nk-int-st">
                                        <input type="password" id="passwordField" name="password"
                                            class="form-control text-white" placeholder="Mot de passe"
                                            style="border: none; padding: 20px; background: transparent" required>
                                    </div>
                                    <!-- Icône pour afficher/masquer -->
                                    <span class="input-group-addon nk-ic-st-pro" onclick="togglePassword()">
                                        <i id="toggleIcon" class="icon-eye"
                                            style="font-size: 25px; cursor: pointer;"></i>
                                    </span>
                                </div>

                                @if (session('error'))
                                    <div class="alert alert-danger text-center mt-3">
                                        {{ session('error') }}
                                    </div>
                                @endif
                                {{-- <button type="button" class="btn btn-primary loading-btn">
                                    Valider 3
                                    <span class="spinner"></span>
                                </button> --}}
                                <div class="text-center mt-5">
                                    <button type="submit" class="btn btn-gradient w-50 loading-btn">
                                        Se Connecter
                                        <span class="spinner"></span>
                                    </button>
                                </div>
                            </div>
                            <div class="col"></div>
                        </div>
                    </form>
                </div>

            </div>
        </div>
        <div class="container-fluid d-block d-lg-none">

            <div class="row">
                <div class="col-md-12">
                    <div class="text-center my-1">
                        <img src="{{ asset('src/images/YODIPOINTE.png') }}" alt="Logo" class="mb-4"
                            style="max-width: 150px;">
                        <h4>Bienvenue ! Veuillez vous connecter pour continuer.
                        </h4>
                    </div>

                </div>
                <div class="col-md-12">
                    <div class="row">
                        <div class="col"></div>
                        <div class="col-md-6">
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

                            @if (request()->has('expired'))
                                <div class="alert alert-danger text-left" style="font-size: 16px" role="alert">
                                    Votre session a expiré. Veuillez vous reconnecter.
                                    <button type="button" class="btn-close" data-dismiss="alert"
                                        aria-label="Close"></button>
                                </div>
                            @endif

                        </div>
                        <div class="col"></div>
                    </div>
                    <form action="{{ route('login') }}" method="post">
                        @csrf
                        <div class="row p-3">
                            <div class="col-md-12 col-sm-12">
                                <div class="input-group shadow-sm rounded mt-4"
                                    style="background: none;border-bottom: 1px solid #fff">
                                    <span class="input-group-addon nk-ic-st-pro"><i class="icon-lock"
                                            style="font-size: 25px"></i></span>
                                    <input type="text" class="form-control text-white" name="matricule"
                                        value="{{ old('matricule') }}" placeholder="Votre identifiant"
                                        style="border:none;padding: 20px;background: transparent" required>
                                </div>

                                <div class="input-group mt-4 shadow-sm rounded"
                                    style="background: none; border-bottom: 1px solid #fff">
                                    <span class="input-group-addon nk-ic-st-pro">
                                        <i class="icon-key" style="font-size: 25px"></i>
                                    </span>
                                    <div class="nk-int-st">
                                        <input type="password" id="passwordField1" name="password"
                                            class="form-control text-white" placeholder="Mot de passe"
                                            style="border: none; padding: 20px; background: transparent" required>
                                    </div>
                                    <!-- Icône pour afficher/masquer -->
                                    <span class="input-group-addon nk-ic-st-pro" onclick="togglePassword1()">
                                        <i id="toggleIcon1" class="icon-eye"
                                            style="font-size: 25px; cursor: pointer;"></i>
                                    </span>
                                </div>

                                @if (session('error'))
                                    <div class="alert alert-danger text-center mt-3">
                                        {{ session('error') }}
                                    </div>
                                @endif

                                <div class="text-center mt-5">
                                    <button type="submit" class="btn btn-gradient w-100 loading-btn">
                                        Se Connecter
                                        <span class="spinner"></span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>

            </div>
        </div>

        <div class="footer-copyright-area d-none d-lg-block" style="position: fixed; bottom: 0; width: 100%;">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="footer-copy-right">
                            <p>Copyright © 2025. Tous droits réservés. YodIngenierie Gabon</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        function togglePassword1() {
            let passwordField = document.getElementById("passwordField1");
            let toggleIcon = document.getElementById("toggleIcon1");

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
