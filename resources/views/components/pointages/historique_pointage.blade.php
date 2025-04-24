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
    <!-- font awesome CSS
  ============================================ -->
    <link rel="stylesheet" href="{{ asset('src/css/font-awesome.min.css') }}">
    <!-- owl.carousel CSS
  ============================================ -->
    <link rel="stylesheet" href="{{ asset('src/css/owl.carousel.css') }}">
    <link rel="stylesheet" href="{{ asset('src/css/owl.theme.css') }}">
    <link rel="stylesheet" href="{{ asset('src/css/owl.transitions.css') }}">
    <link rel="stylesheet" href="{{ asset('icomoon/style.css') }}">
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
    .container-custom {
        background: rgba(255, 255, 255, 0.1);
        backdrop-filter: blur(10px);
        border-radius: 15px;
        padding: 20px;
        text-align: left;
        max-width: 100%;
        width: 100%;
        color: white;
        box-shadow: 0 0 15px rgba(0, 0, 0, 0.2);
    }

    .date-header {
        border: 1px solid rgba(19, 111, 208, 0.8);
        padding: 6px 12px;
        border-radius: 6px;
        display: inline-block;
        margin-bottom: 15px;
    }

    .time-block {
        border-bottom: 1px solid rgba(255, 255, 255, 0.2);
        padding-bottom: 10px;
        margin-bottom: 10px;
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
                <h2>Mon Profil</h2>
            </div>
        </div>
        {{-- <div class="row">
            <div class="col-12" style="text-align: center">
                <div class="text-center">
                    <img src="{{ asset('src/images/YODIPOINTE.png') }}" alt="Logo" class="mb-2"
                        style="max-width: 200px;">
                </div>
            </div>
        </div> --}}
        <div class="row text-center mt-4">

            <div class="col-6 text-left">
                <label for="filtre-date">DU :</label>
                <input type="date" id="filtre-date" name="date_debut" class="form-control mb-3">
            </div>
            <div class="col-6 text-left">
                <label for="filtre-date1">AU :</label>
                <input type="date" id="filtre-date1" name="date_fin" class="form-control mb-3">
            </div>

            <div class="container-custom">
                <!-- Champ visible (sélecteur de date) -->




                @foreach ($Pointages as $Pointage)
                    <div class="pointage-item{{ $Pointage->id }}" data-status="{{ $Pointage->date_arriver }}">
                        <div class="date-header shadow-sm text-capitalize">
                            {{ \App\Helpers\DateHelper::convertirDateEnTexte(App\Helpers\DateHelper::convertirDateFormat($Pointage->date_arriver)) }}
                        </div>

                        <div class="time-block">
                            <div class="mb-2">
                                <div class="d-flex justify-content-between">
                                    <span class="text-success" style="font-size: 20px">
                                        <i class="icon-enter text-success me-2"></i>Entrée :
                                        {{ $Pointage->heure_arriver }}
                                    </span>
                                    @if ($Pointage->heure_arriver > $Pointage->user->entreprise->heure_ouverture)
                                        <span class="text-danger" style="font-size: 20px">(en retard)</span>
                                    @endif
                                </div>
                            </div>
                            <div class="mb-2" style="font-size: 20px">
                                <i class="icon-exit text-warning me-2"></i>
                                <span class="text-warning">
                                    Sortie :
                                    {{ $Pointage->heure_sortie ?? '-- : -- : --' }}
                                </span>
                            </div>
                        </div>



                        @if (!empty($cause_sorties[$Pointage->id]))
                            <div class="shadow-sm mb-3"
                                style="border-bottom: 1px solid white;padding: 6px 12px; border-radius: 6px; display: inline-block;">
                                <h5>Sorties intermédiaires</h5>
                            </div>
                            @foreach ($cause_sorties[$Pointage->id] as $sortie)
                                <div class="time-block">
                                    <div class="mb-2" style="font-size: 20px">
                                        <i class="icon-exit text-warning me-2"></i>
                                        <span class="text-warning">Sortie :
                                            {{ $sortie['pointage_intermediaire']->heure_sortie }}</span>
                                    </div>
                                    <div class="mb-2" style="font-size: 20px">
                                        <i class="icon-enter text-success me-2"></i>
                                        <span class="text-success">Entrée :
                                            {{ $sortie['pointage_intermediaire']->heure_entrer ?? '-- : -- : --' }}</span>
                                    </div>

                                    @if (!empty($sortie['descriptions']))
                                        <div class="mb-2"><small>Motif de sortie :</small></div>
                                        <div class="mb-2 p-2 border border-light"
                                            style="background-color: transparent; border-radius:5px">
                                            <ul class="list-unstyled"
                                                style="padding-left: 15px; margin: 0; color:white">
                                                @foreach ($sortie['descriptions'] as $description)
                                                    <li style="margin-bottom: 5px;">◉
                                                        <small>{{ $description->description }}</small>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        @endif
                    </div>
                    <script>
                        function filtrerParPeriode() {
                            const dateDebut = document.getElementById('filtre-date').value;
                            const dateFin = document.getElementById('filtre-date1').value;
                            const items = document.querySelectorAll('.pointage-item{{ $Pointage->id }}');
                            let matchCount = 0;

                            items.forEach(item => {
                                const itemDate = item.getAttribute('data-status'); // format YYYY-MM-DD

                                if (
                                    (!dateDebut || itemDate >= dateDebut) &&
                                    (!dateFin || itemDate <= dateFin)
                                ) {
                                    item.style.display = 'block';
                                    matchCount++;
                                } else {
                                    item.style.display = 'none';
                                }
                            });

                            const message = document.getElementById('aucun-resultat');
                            message.style.display = matchCount === 0 ? 'block' : 'none';
                        }

                        // ⚡ Déclenchement automatique du filtrage
                        document.getElementById('filtre-date').addEventListener('change', filtrerParPeriode);
                        document.getElementById('filtre-date1').addEventListener('change', filtrerParPeriode);
                    </script>
                @endforeach

                <div id="aucun-resultat" class="alert alert-warning text-center" style="display: none;">
                    Aucun résultat trouvé pour cette date.
                </div>

            </div>
        </div>

    </div>







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
