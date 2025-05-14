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
    .card-hover-zoom {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .card-hover-zoom:hover {
        transform: scale(1.05);
        box-shadow: 0 12px 25px rgba(0, 0, 0, 0.2);
        z-index: 2;
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


    .btn-gradient:hover {
        opacity: 0.8;
        transform: scale(1.05);
    }
</style>

<body
    style="background: linear-gradient(rgba(0, 0, 0, 0.795), rgba(0, 0, 0, 0.836)),
url('{{ asset('src/images/groupe.jpg') }}') no-repeat center center;
background-size: cover;
background-attachment: fixed;
color: #fff;">
    <!--[if lt IE 8]>
            <p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
        <![endif]-->
    <!-- Login Register area Start-->

    <!-- Login -->
    <div class="mx-5" id="l-login">
        <div class="text-center mt-5">
            <img src="{{ asset('src/images/Logo_Nedco.png') }}" alt="Logo" class="mb-4"
                style="max-width: 200px;">
        </div>
        <div style=" text-align: center;">
            <div>
                <h1>Bienvenue sur NedCore</h1>
            </div>
        </div>
        <div class="row text-center mt-4 mx-5">
            @if (session('success'))
                <div class="col-md-12" style="text-align: left">
                    <div class="alert alert-success " role="alert">
                        <i class="icon-user-check1" style="font-size: 20px;margin-right:10px"></i><strong>Succès
                            !</strong> {{ session('success') }}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Fermer">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                </div>
            @endif
            @if (session('error'))
                <div class="col-md-12" style="text-align: left">
                    <div class="alert alert-danger" role="alert">
                        <i class="icon-warning" style="font-size: 20px;margin-right:10px"></i><strong>Rappel
                            !</strong> {{ session('error') }}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Fermer">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                </div>
            @endif
            @foreach ($modules as $module)
                <!-- Bloc Entrée -->
                <div class="col-lg-2 col-sm-12 mb-4">
                    <a href="{{ route('loginGroupe', $module->id) }}" class="text-decoration-none shadow-sm">
                        <div class="card card-hover-zoom shadow-lg px-2">
                            <div class="card-body">
                                @if (!empty($module->logo))
                                    <img src="{{ asset('storage/' . $module->logo) }}" alt="Logo du module"
                                        style="max-width: 100px; height: auto;">
                                @else
                                    <i class="icon-user" style="font-size: 45px"></i>
                                @endif
                                <h3 class="fw-bold my-2">Gestion : {{ $module->nom_module ?? '' }}</h3>
                            </div>
                        </div>
                    </a>
                </div>
            @endforeach
        </div>

    </div>
    <script>
        function showOfflineMessage(redirect = false) {
            const existingPopup = document.getElementById('offline-popup');
            if (existingPopup) return; // Ne pas dupliquer

            const popup = document.createElement('div');
            popup.id = 'offline-popup';
            popup.innerHTML = `
             <div alert alert-danger text-center position-fixed bottom-0 start-0 end-0 m-3 shadow" role="alert" style="z-index: 9999;">
                  <span class="me-3">📡 Connexion perdue ou session expirée.</span>
              </div>
          `;
            document.body.appendChild(popup);

            if (redirect) {
                // Redirection automatique vers une route définie
                setTimeout(() => {
                    window.location.href = "/components.liste_module";
                }, 3000);
            }
        }

        function removeOfflineMessage() {
            const popup = document.getElementById('offline-popup');
            if (popup) popup.remove();
        }

        function retryAutoReload() {
            const btns = document.querySelectorAll('#offline-popup button');
            btns.forEach(btn => btn.disabled = true);

            const interval = setInterval(() => {
                if (navigator.onLine) {
                    clearInterval(interval);
                    location.reload();
                }
            }, 3000); // tente toutes les 3 secondes
        }

        function checkSessionExpired() {
            fetch(window.location.href, {
                    method: 'HEAD',
                    cache: 'no-store'
                })
                .then(response => {
                    if (response.status === 419 || response.status === 401) {
                        showOfflineMessage(true); // redirige vers /components/liste_module
                    }
                })
                .catch(() => {
                    // Si l’appel échoue complètement, probablement hors ligne
                    showOfflineMessage();
                });
        }

        window.addEventListener('offline', showOfflineMessage);
        window.addEventListener('online', removeOfflineMessage);

        // Vérifie l'état initial au chargement
        if (!navigator.onLine) {
            showOfflineMessage();
        }

        // Vérifie périodiquement si la session a expiré
        setInterval(checkSessionExpired, 60000); // toutes les 60 secondes
    </script>
    {{-- <script>
        function showOfflineMessage() {
            const popup = document.createElement('div');
            popup.innerHTML = `
              <div class="alert alert-danger text-center position-fixed bottom-0 start-0 end-0 m-3 shadow" role="alert" style="z-index: 9999;">
                  📡 Connexion perdue. 
              </div>
          `;
            document.body.appendChild(popup);
        }

        function removeOfflineMessage() {
            const popup = document.querySelector('.alert-danger');
            if (popup) popup.remove();
        }

        window.addEventListener('offline', showOfflineMessage);
        window.addEventListener('online', removeOfflineMessage);
    </script>

    <script>
        window.addEventListener('online', function() {
            const message = document.createElement('div');
            message.innerHTML = "✅ Connexion rétablie. Redirection en cours...";
            message.className = "alert alert-success mt-3";
            document.querySelector('.error-container').appendChild(message);

            setTimeout(() => {
                window.history.back();
            }, 2000);
        });
    </script> --}}

    <!-- Forgot Password -->

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
