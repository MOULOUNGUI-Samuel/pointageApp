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

    .card-hover-zoom {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .card-hover-zoom:hover {
        transform: scale(1.05);
        box-shadow: 0 6px 20px rgba(0, 0, 0, 0.15);
    }

    .carousel-item img {
        height: 800px;
        object-fit: cover;
        border-radius: 12px;
    }

    .carousel-caption {
        background: rgba(0, 0, 0, 0.55);
        border-radius: 10px;
        padding: 15px;
    }

    .nedcore-intro {
        background: linear-gradient(135deg, #f2f6fa, #dce3e8);
        border-left: 5px solid #05436b;
        padding: 25px;
        border-radius: 10px;
        box-shadow: 0 6px 12px rgba(0, 0, 0, 0.1);
    }

    .nedcore-intro h2 {
        font-weight: bold;
        color: #05436b;
    }

    .nedcore-intro p {
        font-size: 1.1rem;
        color: #333;
    }
</style>

<body style="background-color: #ccc;">
    <!--[if lt IE 8]>
            <p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
        <![endif]-->
    <!-- Login Register area Start-->

    <!-- Login -->
    <div class="container-fluid">
        <div class="d-flex" style="height: 100vh; overflow: hidden;">

            <!-- SIDEBAR (fixe) -->
            <aside class="bg-light text-dark px-4 shadow-sm d-flex flex-column"
                style="width: 550px; border-right: 2px solid #ddd;">
                <div class="my-2 d-flex justify-content-between align-items-center bg-light">
                    <img src="{{ asset('storage/' . $entreprise_logo) }}" alt="Logo entreprise"
                        style="width: 50px; height: 40px; border-radius: 10px;margin-left: 10px">

                    <img src="{{ asset('src/images/Logo_Nedco.png') }}" alt="Logo Nedco"
                        style="width: 60px; height: 50px;">
                </div>
                <div class="row row-cols-3 g-3">
                    @foreach ($modules as $module)
                        <div class="col">
                            <a href="{{ route('dashboard', $module->id) }}" class="text-decoration-none">
                                <div class="border rounded p-2 text-center shadow-sm card-hover-zoom"
                                    style="background-color: #f8f9fa;">
                                    @if (!empty($module->logo))
                                        <img src="{{ asset('storage/' . $module->logo) }}" alt="Logo"
                                            class="rounded"
                                            style="width: 100%; height: 50px; object-fit: contain; border-radius: 5px;">
                                    @endif
                                </div>
                            </a>
                        </div>
                    @endforeach
                </div>
            </aside>

            <!-- MAIN CONTENT (scrollable) -->
            <main class="flex-grow-1 p-4" style="overflow-y: auto; scrollbar-width: none; -ms-overflow-style: none;">
                <div class=" bg-white rounded shadow-sm p-4">
                    <div class="container-fluid px-4">

                        <!-- Présentation NedCore -->
                        {{-- <div class="nedcore-intro mb-4">
            <h2 class="mb-3">Bienvenue sur la plateforme NedCore</h2>
            <p>
              <strong>NedCore</strong> est la solution numérique centralisée du <strong>Groupe NedCo</strong>,
              dédiée à la gestion intégrée de toutes les entités du groupe. Grâce à une interface moderne et
              intuitive,
              chaque collaborateur accède aux modules essentiels : gestion RH, finances, projets, documents et
              reporting stratégique.
            </p>
            <p>
              Notre objectif : optimiser l'efficacité, améliorer la traçabilité et encourager une collaboration fluide
              dans l'ensemble du groupe.
            </p>
          </div> --}}
                        <div class="nedcore-intro mb-4">
                            <h2 class="mb-3">Aujourd’hui, nous célébrons l'anniversaire du grand baobab du Groupe Ned&Co</h2>
                            <p>
                                En ce jour exceptionnel, toute la famille <strong>Ned&Co</strong> s’unit pour souhaiter
                                un <strong>joyeux anniversaire</strong> à son Président, source de sagesse, de vision et
                                de stabilité.
                            </p>
                            <p>
                                À l’image d’un baobab majestueux, vous êtes un pilier pour toutes les entités du groupe,
                                un repère pour chaque collaborateur et un moteur d’excellence au quotidien.
                            </p>
                            <p>
                                Que cette journée soit à la hauteur de l’homme que vous êtes : inspirant, déterminé et
                                profondément humain.
                            </p>
                            <p>
                                <strong>YodIngénierie</strong>, avec reconnaissance et admiration, vous rend hommage et
                                vous renouvelle ses vœux de bonheur, de santé et de réussite continue.
                            </p>
                        </div>
                        <!-- Carousel d'actualité -->
                        <div id="nedcoreCarousel" class="carousel slide shadow rounded mb-4" data-bs-ride="carousel">
                            <div class="carousel-inner">
                                <div class="carousel-item active">
                                    <img src="{{asset('src2/img/President1.png')}}"
                                        class="d-block w-100" alt="Module RH"
                                        style="height: 500px; object-fit: cover; border-radius: 10px;">
                                    {{-- <img src="https://img.freepik.com/photos-premium/groupe-diversifie-professionnels-costumes-affaires-debout-ensemble_412311-21321.jpg"
                                        class="d-block w-100" alt="Module RH"
                                        style="height: 500px; object-fit: cover; border-radius: 10px;"> --}}
                                    <div class="carousel-caption d-none d-md-block bg-dark bg-opacity-50 rounded p-2">
                                        <h5>JOYEUX ANNIVERSAIRE PRÉSIDENT !</h5>
                                        <p>Votre vision, votre engagement et votre leadership à la téte du Groupe Ned&Co sont une source constante d'inspiration. Nous sommes honorës de faire partie de cette grande aventure á vos cótes.</p>
                                    </div>
                                </div>
                                {{-- <div class="carousel-item">
                                    <img src="https://img.freepik.com/photos-premium/equipe-commerciale-jeunes-africains-costumes_219728-237.jpg?uid=R89361838&ga=GA1.1.1094856274.1737491130&semt=ais_hybrid&w=740"
                                        class="d-block w-100" alt="Finance"
                                        style="height: 500px; object-fit: cover; border-radius: 10px;">
                                    <div class="carousel-caption d-none d-md-block bg-dark bg-opacity-50 rounded p-2">
                                        <h5>Finances</h5>
                                        <p>Suivez les indicateurs de performance financière en temps réel.</p>
                                    </div>
                                </div>
                                <div class="carousel-item">
                                    <img src="https://img.freepik.com/photos-gratuite/portrait-corporatif-equipe-trois-personnes_1262-20069.jpg?uid=R89361838&ga=GA1.1.1094856274.1737491130&semt=ais_hybrid&w=740"
                                        class="d-block w-100" alt="Projet"
                                        style="height: 500px; object-fit: cover; border-radius: 10px;">
                                    <div class="carousel-caption d-none d-md-block bg-dark bg-opacity-50 rounded p-2">
                                        <h5>Gestion de Projet</h5>
                                        <p>Pilotez les projets internes avec une visibilité à 360°.</p>
                                    </div>
                                </div> --}}
                            </div>
                            <button class="carousel-control-prev" type="button" data-bs-target="#nedcoreCarousel"
                                data-bs-slide="prev">
                                <span class="carousel-control-prev-icon bg-dark rounded-circle"
                                    aria-hidden="true"></span>
                                <span class="visually-hidden">Précédent</span>
                            </button>
                            <button class="carousel-control-next" type="button" data-bs-target="#nedcoreCarousel"
                                data-bs-slide="next">
                                <span class="carousel-control-next-icon bg-dark rounded-circle"
                                    aria-hidden="true"></span>
                                <span class="visually-hidden">Suivant</span>
                            </button>
                        </div>

                    </div>
                </div>
            </main>

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

    {{--
  <script>
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
    window.addEventListener('online', function () {
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
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

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
