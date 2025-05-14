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
    <link rel="shortcut icon" type="image/x-icon" href="img/favicon.ico">
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
url('{{ asset('src/images/login.webp') }}') no-repeat center center;
background-size: cover;
background-attachment: fixed;
color: #fff;">
    <script>
        if (window.innerWidth > 1024) {
            document.body.innerHTML = `
          <div style="display: flex; justify-content: center; align-items: center; height: 100vh; text-align: center; font-family: Arial, sans-serif; padding: 20px;">
              <div>
                <h1>Salut <strong class="text-info mb-2">{{ Auth::user()->prenom }}</strong></h1>
                  <h2> Vous êtes un employé,cette application est accessible uniquement sur un appareil mobile.</h2>
                  <p>Veuillez utiliser un smartphone ou une tablette pour y accéder.</p>
              </div>
          </div>
      `;
        }
    </script>
    <!--[if lt IE 8]>
            <p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
        <![endif]-->
    <!-- Login Register area Start-->
    @php use Illuminate\Support\Str; @endphp
    <div class="mx-4" id="l-login">
        <div class="row" style="margin-top: 40px;">
            <div class="col-10 text-left" style="margin-top: 10px;">
                <h3>{{ Str::limit(Auth::user()->nom . ' ' . Auth::user()->prenom, 16, '...') }}</h3>
            </div>
            <div class="col-2 text-right">
                <a href="{{ route('logout') }}"
                    onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    <i class="icon-sign-out text-white" style="font-size: 2.5rem;"></i>
                </a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>
            </div>
        </div>
        <div class="text-center" style="margin-top: 50px;">
            <img src="{{ asset('src/images/YODIPOINTE.png') }}" alt="Logo" class="mb-4"
                style="max-width: 200px;">
        </div>
        <div class="row text-center mt-4">
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

            <!-- Bloc Entrée -->
            <div class="col-lg-6 col-sm-12 mb-4">
                <div class="row">
                    <div class="col-6">
                        <a href="{{ route('profil_employe') }}" class="text-decoration-none shadow-sm">
                            <div class="card card-hover-zoom shadow-lg px-2">
                                <div class="card-body">
                                    <i class="icon-profile" style="font-size: 40px"></i>
                                    <h4 class="fw-bold" style="font-size: 20px">Profil</h4>
                                    <p class="text-muted">Voir votre profil</p>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col-6">
                        <a href="{{ route('pointage_compte') }}" class="text-decoration-none shadow-sm">
                            <div class="card card-hover-zoom shadow-lg px-2">
                                <div
                                    class="card-body text-center d-flex flex-column align-items-center justify-content-center">
                                    <div class="mb-3 d-flex justify-content-center gap-3">
                                        <i class="icon-enter text-success" style="font-size: 43px;"></i>
                                        <i class="icon-exit text-danger" style="font-size: 43px;"></i>
                                    </div>
                                    <h4 class="fw-bold" style="font-size: 20px;">Pointage</h4>
                                    <p class="text-muted mb-0">Entrée / Sortie</p>
                                </div>

                            </div>
                        </a>
                    </div>
                </div>

            </div>

            <!-- Bloc Sortie -->
            <div class="col-lg-6 col-sm-12 mb-4">
                <a href="{{ route('historique_pointage') }}" class="text-decoration-none shadow-sm">
                    <div class="card card-hover-zoom shadow-lg px-2">
                        <div class="card-body">
                            <i class="icon-calendar" style="font-size: 45px"></i>
                            <h4 class="fw-bold" style="font-size: 20px">Historique des pointages</h4>
                            <p class="text-muted">Consultez vos entrées et sorties en temps réel.</p>
                        </div>
                    </div>
                </a>
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
                  <span class="me-3">📡 Connexion perdue.</span>
              </div>
          `;
          document.body.appendChild(popup);
      }
  
      function removeOfflinePopup() {
          const popup = document.getElementById('offline-popup');
          if (popup) popup.remove();
      }
  
      window.addEventListener('offline', showOfflinePopup);
      window.addEventListener('online', removeOfflinePopup);
  
      if (!navigator.onLine) {
          showOfflinePopup();
      }
  
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
          }, 1000);
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
              // Si requête échoue, considérer comme hors ligne
              showOfflinePopup();
          });
      }
  
      setInterval(checkSessionExpired, 60000); // Vérifie toutes les 60s
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
