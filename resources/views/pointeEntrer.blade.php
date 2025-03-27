<!doctype html>
<html class="no-js" lang="">

<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>Login Register | Notika - Notika Admin Template</title>
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
    <div class="center-container">
        <div class="container px-5">
            <div class="row">
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

                </div>
                <div class="col-md-12">
                    <div class="row p-3">
                        <div class="col"></div>
                        <div class="col-md-8 col-sm-12">
                            <div class="input-group shadow-sm rounded mt-4"
                                style="background: none;border-bottom: 1px solid #fff">
                                <span class="input-group-addon nk-ic-st-pro"><i class="icon-list-numbered"
                                        style="font-size: 25px"></i></span>
                                <input type="number" class="form-control text-white" placeholder="Matricule"
                                    style="border:none;padding: 20px;background: transparent">
                            </div>

                            <div class="input-group mt-3 shadow-sm rounded"
                                style="background: none; border-bottom: 1px solid #fff">
                                <span class="input-group-addon nk-ic-st-pro">
                                    <i class="icon-key" style="font-size: 25px"></i>
                                </span>
                                <div class="nk-int-st">
                                    <input type="password" id="passwordField" class="form-control text-white"
                                        placeholder="Mot de passe"
                                        style="border: none; padding: 20px; background: transparent">
                                </div>
                                <!-- Icône pour afficher/masquer -->
                                <span class="input-group-addon nk-ic-st-pro" onclick="togglePassword()">
                                    <i id="toggleIcon" class="icon-eye" style="font-size: 25px; cursor: pointer;"></i>
                                </span>
                            </div>

                            <div class="d-flex justify-content-between mt-5">
                                <a href="{{ url('/login') }}" class="btn btn-gradient1 ">
                                    <i class="icon-close-solid"></i> Annuler
                                </a>
                                <button class="btn btn-gradient">
                                    <i class="icon-save-disk"></i> Enrégristrer
                                </button>

                            </div>
                        </div>
                        <div class="col"></div>
                    </div>
                </div>

            </div>
        </div>

    </div>
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
