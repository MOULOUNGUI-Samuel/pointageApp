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
    <link rel="stylesheet" href="{{asset('src/css/bootstrap.min.css')}}">
    <link rel="stylesheet" href="{{ asset('icomoon/style.css') }}">
    <!-- font awesome CSS
		============================================ -->
    <link rel="stylesheet" href="{{asset('src/css/font-awesome.min.css')}}">
    <!-- owl.carousel CSS
		============================================ -->
    <link rel="stylesheet" href="{{asset('src/css/owl.carousel.css')}}">
    <link rel="stylesheet" href="{{asset('src/css/owl.theme.css')}}">
    <link rel="stylesheet" href="{{asset('src/css/owl.transitions.css')}}">
    <!-- animate CSS
		============================================ -->
    <link rel="stylesheet" href="{{asset('src/css/animate.css')}}">
    <!-- normalize CSS
		============================================ -->
    <link rel="stylesheet" href="{{asset('src/css/normalize.css')}}">
    <!-- mCustomScrollbar CSS
		============================================ -->
    <link rel="stylesheet" href="{{asset('src/css/scrollbar/jquery.mCustomScrollbar.min.css')}}">
    <!-- wave CSS
		============================================ -->
    <link rel="stylesheet" href="{{asset('src/css/wave/waves.min.css')}}">
    <!-- Notika icon CSS
		============================================ -->
    <link rel="stylesheet" href="{{asset('src/css/notika-custom-icon.css')}}">
    <!-- main CSS
		============================================ -->
    <link rel="stylesheet" href="{{asset('src/css/main.css')}}">
    <!-- style CSS
		============================================ -->
    <link rel="stylesheet" href="{{asset('src/style.css')}}">
    <!-- responsive CSS
		============================================ -->
    <link rel="stylesheet" href="{{asset('src/css/responsive.css')}}">
    <!-- modernizr JS
		============================================ -->
    <script src="{{asset('src/js/vendor/modernizr-2.8.3.min.js')}}"></script>
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
</style>
<body>
    <!--[if lt IE 8]>
            <p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
        <![endif]-->
    <!-- Login Register area Start-->
    <div class="login-content" style="background: linear-gradient(rgba(0, 0, 0, 0.632), rgba(0, 0, 0, 0.696)),
                url('{{ asset('src/images/login.webp') }}') no-repeat center center;
    background-size: cover;
    background-attachment: fixed;
    color: #fff;">
        <!-- Login -->
        <div class="nk-block toggled" id="l-login">
            <div class="row text-center mt-4">
                <!-- Bloc Entrée -->
                <div class="col-lg-6 col-sm-12 mb-4">
                    <a href="{{ route('entrer') }}" class="text-decoration-none shadow-sm">
                        <div class="card card-hover-zoom shadow-lg px-2 border-success border-start border-1">
                            <div class="card-body">
                                <i class="icon-enter text-success" style="font-size: 45px"></i>
                                <h3 class="text-success fw-bold">Entrée</h3>
                                <p class="text-muted">Cliquez ici pour signaler votre arrivée.</p>
                            </div>
                        </div>
                    </a>
                </div>

                <!-- Bloc Sortie -->
                <div class="col-lg-6 col-sm-12 mb-4">
                    <a href="{{ route('sortie') }}" class="text-decoration-none shadow-sm">
                        <div class="card card-hover-zoom shadow-lg px-2 border-danger border-start border-1">
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
        <div  class="nk-block animate-four"  id="l-entrer">
            <div class="nk-form">
                <h5>Vous faite une Entrée</h5>
                <div class="input-group">
                    <span class="input-group-addon nk-ic-st-pro"><i class="notika-icon notika-support"></i></span>
                    <div class="nk-int-st">
                        <input type="text" class="form-control" placeholder="Username">
                    </div>
                </div>
                <div class="input-group mg-t-15">
                    <span class="input-group-addon nk-ic-st-pro"><i class="notika-icon notika-edit"></i></span>
                    <div class="nk-int-st">
                        <input type="password" class="form-control" placeholder="Password">
                    </div>
                </div>
                <div class="fm-checkbox">
                    <label><input type="checkbox" class="i-checks"> <i></i> Keep me signed in</label>
                </div>
                {{-- <a href="#l-register" data-ma-action="nk-login-switch" data-ma-block="#l-register" class="btn btn-login btn-success btn-float"><i class="notika-icon notika-right-arrow right-arrow-ant"></i></a> --}}
            </div>

            {{-- <div class="nk-navigation nk-lg-ic">
                <a href="#" data-ma-action="nk-login-switch" data-ma-block="#l-register"><i class="notika-icon notika-plus-symbol"></i> <span>Register</span></a>
                <a href="#" data-ma-action="nk-login-switch" data-ma-block="#l-forget-password"><i>?</i> <span>Forgot Password</span></a>
            </div> --}}
        </div>

        <!-- Register -->
        <div class="nk-block" id="l-sortie">
            <div class="nk-form">
                <h5>Vous faite une Sortie</h5>
                <div class="input-group">
                    <span class="input-group-addon nk-ic-st-pro"><i class="notika-icon notika-support"></i></span>
                    <div class="nk-int-st">
                        <input type="text" class="form-control" placeholder="Username">
                    </div>
                </div>

                <div class="input-group mg-t-15">
                    <span class="input-group-addon nk-ic-st-pro"><i class="notika-icon notika-mail"></i></span>
                    <div class="nk-int-st">
                        <input type="text" class="form-control" placeholder="Email Address">
                    </div>
                </div>

                <div class="input-group mg-t-15">
                    <span class="input-group-addon nk-ic-st-pro"><i class="notika-icon notika-edit"></i></span>
                    <div class="nk-int-st">
                        <input type="password" class="form-control" placeholder="Password">
                    </div>
                </div>

                {{-- <a href="#l-login" data-ma-action="nk-login-switch" data-ma-block="#l-login" class="btn btn-login btn-success btn-float"><i class="notika-icon notika-right-arrow"></i></a> --}}
            </div>

            {{-- <div class="nk-navigation rg-ic-stl">
                <a href="#" data-ma-action="nk-login-switch" data-ma-block="#l-login"><i class="notika-icon notika-right-arrow"></i> <span>Sign in</span></a>
                <a href="" data-ma-action="nk-login-switch" data-ma-block="#l-forget-password"><i>?</i> <span>Forgot Password</span></a>
            </div> --}}
        </div>

        <!-- Forgot Password -->

    </div>
    <!-- Login Register area End-->
    <!-- jquery
		============================================ -->
    <script src="{{asset('src/js/vendor/jquery-1.12.4.min.js')}}"></script>
    <!-- bootstrap JS
		============================================ -->
    <script src="{{asset('src/js/bootstrap.min.js')}}"></script>
    <!-- wow JS
		============================================ -->
    <script src="{{asset('src/js/wow.min.js')}}"></script>
    <!-- price-slider JS
		============================================ -->
    <script src="{{asset('src/js/jquery-price-slider.js')}}"></script>
    <!-- owl.carousel JS
		============================================ -->
    <script src="{{asset('src/js/owl.carousel.min.js')}}"></script>
    <!-- scrollUp JS
		============================================ -->
    <script src="{{asset('src/js/jquery.scrollUp.min.js')}}"></script>
    <!-- meanmenu JS
		============================================ -->
    <script src="{{asset('src/js/meanmenu/jquery.meanmenu.js')}}"></script>
    <!-- counterup JS
		============================================ -->
    <script src="{{asset('src/js/counterup/jquery.counterup.min.js')}}"></script>
    <script src="{{asset('src/js/counterup/waypoints.min.js')}}"></script>
    <script src="{{asset('src/js/counterup/counterup-active.js')}}"></script>
    <!-- mCustomScrollbar JS
		============================================ -->
    <script src="{{asset('src/js/scrollbar/jquery.mCustomScrollbar.concat.min.js')}}"></script>
    <!-- sparkline JS
		============================================ -->
    <script src="{{asset('src/js/sparkline/jquery.sparkline.min.js')}}"></script>
    <script src="{{asset('src/js/sparkline/sparkline-active.js')}}"></script>
    <!-- flot JS
		============================================ -->
    <script src="{{asset('src/js/flot/jquery.flot.js')}}"></script>
    <script src="{{asset('src/js/flot/jquery.flot.resize.js')}}"></script>
    <script src="{{asset('src/js/flot/flot-active.js')}}"></script>
    <!-- knob JS
		============================================ -->
    <script src="{{asset('src/js/knob/jquery.knob.js')}}"></script>
    <script src="{{asset('src/js/knob/jquery.appear.js')}}"></script>
    <script src="{{asset('src/js/knob/knob-active.js')}}"></script>
    <!--  Chat JS
		============================================ -->
    <script src="{{asset('src/js/chat/jquery.chat.js')}}"></script>
    <!--  wave JS
		============================================ -->
    <script src="{{asset('src/js/wave/waves.min.js')}}"></script>
    <script src="{{asset('src/js/wave/wave-active.js')}}"></script>
    <!-- icheck JS
		============================================ -->
    <script src="{{asset('src/js/icheck/icheck.min.js')}}"></script>
    <script src="{{asset('src/js/icheck/icheck-active.js')}}"></script>
    <!--  todo JS
		============================================ -->
    <script src="{{asset('src/js/todo/jquery.todo.js')}}"></script>
    <!-- Login JS
		============================================ -->
    <script src="{{asset('src/js/login/login-action.js')}}"></script>
    <!-- plugins JS
		============================================ -->
    <script src="{{asset('src/js/plugins.js')}}"></script>
    <!-- main JS
		============================================ -->
    <script src="{{asset('src/js/main.js')}}"></script>

</body>

</html>
