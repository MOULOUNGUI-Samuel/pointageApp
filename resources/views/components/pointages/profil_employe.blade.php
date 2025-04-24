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
    .profile-form {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border-radius: 15px;
            padding: 30px;
            width: 100%;
            max-width: 100%;
            color: white;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.2);
        }

        .form-control{
            padding: 10px;
            background:transparent;
            border:1px solid white;
            font-size: 20px;
            color:white
        }
        .form-control::placeholder {
            color: #ccc;

        }


        .form-group {
            margin-bottom: 15px;
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

<body style="background: linear-gradient(rgba(0, 0, 0, 0.795), rgba(0, 0, 0, 0.836)),
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
                @if (session('success'))
                    <div class="col-md-12" style="text-align: left">
                        <div class="alert alert-success " role="alert">
                            <i class="icon-user-check1" style="font-size: 20px;margin-right:10px"></i><strong>Succès
                                !</strong> {{ session('success') }}
                        </div>
                    </div>
                @endif

                @if (session('error'))
                    <div class="col-md-12" style="text-align: left">
                        <div class="alert alert-danger" role="alert">
                            <i class="icon-warning" style="font-size: 20px;margin-right:10px"></i><strong>Rappel
                                !</strong> {{ session('error') }}
                        </div>
                    </div>
                @endif
                @if ($errors->has('password'))
                    <div class="col-md-12" style="text-align: left">
                        <div class="alert alert-danger" role="alert">
                            <i class="icon-warning" style="font-size: 20px;margin-right:10px"></i><strong></strong> {{ $errors->first('password') }}
                        </div>
                    </div>
                @endif

                <div class="profile-form">
                    
                    <form action="{{ route('modifier_employer', Auth::user()->id) }}"
                        method="post" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="form-group">
                            <input type="text" class="form-control" placeholder="Matricule" name="matricule" value="{{ Auth::user()->matricule }}">
                        </div>
                        <div class="form-group">
                            <input type="text" class="form-control" placeholder="Nom" name="nom" value="{{ Auth::user()->nom }}">
                        </div>
                        <div class="form-group">
                            <input type="text" class="form-control" placeholder="Prénom" name="prenom" value="{{ Auth::user()->prenom }}">
                        </div>
                        <div class="form-group">
                            <input type="email" class="form-control" placeholder="Email" name="email" value="{{ Auth::user()->email }}">
                        </div>
                        <div class="form-group">
                            <input type="date" class="form-control" placeholder="date_naissance" name="date_naissance" value="{{ Auth::user()->date_naissance }}">
                        </div>
                        <div class="form-group">
                            <input type="text" class="form-control" placeholder="Fonction" name="fonction" value="{{ Auth::user()->fonction }}">
                        </div>
                        <div class="form-group">
                            <input type="password" class="form-control" name="password1" placeholder="Ancien mot de passe">
                        </div>
                        <div class="form-group">
                            <input type="password" class="form-control" name="password" placeholder="Nouveau mot de passe">
                        </div>
                        <button type="submit" class="btn btn-gradient w-100 mt-2">Enregistrer les modifications</button>
                    </form>
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
