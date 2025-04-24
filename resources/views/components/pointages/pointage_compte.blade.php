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
    <!--[if lt IE 8]>
            <p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
        <![endif]-->
    <!-- Login Register area Start-->
    <!-- Login -->
    <div class="mx-4" id="l-login">
        <div class="row" style="margin-top: 40px;">
            <div class="col-6 text-left">
                <a href="{{ route('index_employer') }}">
                    <i class="fa fa-arrow-left text-white" style="font-size: 2.5rem;"></i>
                </a>
            </div>
            <div class="col-6 text-right" style="margin-top: 10px;">
                <h2>Pointage</h2>
            </div>
        </div>
        <div class="text-center mt-5">
            <img src="{{ asset('src/images/YODIPOINTE.png') }}" alt="Logo" class="mb-2"
                style="max-width: 200px;">
        </div>
        <div class="row text-center mt-1">
            <div class="col-12 mb-3">
                <div class="text-center my-1">
                    <h1 id="currentTime" class="display-3 fw-bold" style="font-size: 50px; color: #f7f7f7;"></h1>
                </div>
                <div class="text-center my-1">
                    <h4><span id="currentDateTime" style="font-size: 25px"></span>
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
            </div>
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
            <div class="col-lg-6 col-sm-12 mb-4 ">
                <form action="{{ route('login_connecter') }}" method="POST">
                    @csrf
                    <input type="hidden" id="latitude" name="latitude" value="{{ old('latitude') }}"
                        style="color:black">
                    <input type="hidden" id="longitude" name="longitude" value="{{ old('longitude') }}"
                        style="color:black">
                    <input type="hidden" name="pointage_entrer" value="1">
                    <button type="submit" class="text-decoration-none shadow-sm w-100"
                        style="background-color: transparent;border:none">
                        <div class="card card-hover-zoom shadow-lg px-2">
                            <div class="card-body">
                                <i class="icon-enter text-success" style="font-size: 45px"></i>
                                <h3 class="text-success fw-bold">Entrée</h3>
                                <p class="text-muted">Cliquez ici pour signaler votre arrivée.</p>
                            </div>
                        </div>
                    </button>
                </form>
            </div>

            <!-- Bloc Sortie -->
            <div class="col-lg-6 col-sm-12 mb-4">
                
                        <a href="{{ route('pointage_sortie_connecter') }}" class="text-decoration-none shadow-sm ">
                            <div class="card card-hover-zoom shadow-lg px-2">
                                <div class="card-body">
                                    <i class="icon-exit text-danger" style="font-size: 45px"></i>
                                    <h3 class="text-danger fw-bold">Sortie</h3>
                                    <p class="text-muted">Cliquez ici pour signaler votre départ.</p>
                                </div>
                            </div>
                        </a>
            </div>
        </div>

    </div>
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
