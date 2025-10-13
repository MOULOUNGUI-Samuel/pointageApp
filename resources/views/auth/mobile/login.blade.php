<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Nedcore">
    <meta name="keywords" content="Nedcore">
    <meta name="author" content="Nedcore">
    <link rel="manifest" href="manifest.json">
    <link rel="icon" href="{{ asset('assets/img/authentication/logo_nedcore.JPG') }}" type="image/x-icon">
    <title>Nedcore</title>
    <link rel="apple-touch-icon" href="{{ asset('assets/img/authentication/logo_nedcore.JPG') }}">
    <meta name="theme-color" content="#2777FC">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta name="apple-mobile-web-app-title" content="Nedcore">
    <meta name="msapplication-TileImage" content="{{ asset('assets/img/authentication/logo_nedcore.JPG') }}">
    <meta name="msapplication-TileColor" content="#FFFFFF">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <!-- font link -->
    <link rel="stylesheet" href="{{asset('asset/css/br-hendrix.css')}}">

    <!-- bootstrap css -->
    <link rel="stylesheet" type="text/css" id="rtl-link" href="{{asset('asset/css/vendors/bootstrap.css')}}">

    <!-- iconsax icon css -->
    <link rel="stylesheet" type="text/css" href="{{asset('asset/css/vendors/iconsax.css')}}">

    <!-- Theme css -->
    <link rel="stylesheet" id="change-link" type="text/css" href="{{asset('asset/css/style.css')}}">
</head>

<body>
    <!-- loader start-->
    <div class="loader-wrapper" id="loader">
        <span class="loader"></span>
    </div>
    <!-- loader end -->

    <!-- header start -->
    <header class="main-header">
        <div class="custom-container">
            <div class="header-panel">
                <a href="onboarding.html">
                    <img class="img-fluid icon-btn back-arrow" src="{{asset('asset/images/svg/back-arrow.svg')}}" alt="back-arrow">
                </a>
            </div>
        </div>
    </header>
    <!-- header end -->

    <!-- login section starts -->
    <section class="section-sm-t-space section-b-space">
        <div class="custom-container">
            <div class="auth-content">
                <img class="img-fluid logo-sm" src="{{ asset('assets/img/authentication/logo_nedcore.JPG') }}" alt="logo-sm" width="150">
                <h1 class="title-color">Welcome back!</h1>
                <h5 class="fw-normal content-color mt-2">We happy to see you here again. enter your email address and
                    password
                </h5>
            </div>
            <form class="theme-form">
                <div class="form-group">
                    <label class="form-label" for="inputemail">Email</label>
                    <input type="email" class="form-control wo-icon" id="inputemail" placeholder="Enter email">
                </div>


                <div class="form-group">
                    <label class="form-label" for="inputpassword">Password</label>
                    <input type="password" class="form-control wo-icon" id="inputpassword" placeholder="Enter password">
                </div>

                <div class="form-group">
                    <div class="form-check">
                        <input class="form-check-input m-0" type="checkbox" id="flexCheckDefault">
                        <label class="form-check-label mb-0 fw-normal  title-color"
                            for="flexCheckDefault">Remember</label>
                    </div>
                    <a class="forgot white-nowrap" href="forgot-password.html">Forgot password</a>
                </div>
            </form>

            <a href="home.html" class="btn theme-btn w-100 auth-btn">Login now</a>

            <div class="d-flex align-items-center justify-content-center gap-3 mt-24">
                <a href="https://www.google.com/" target="_blank"
                    class="btn white-btn d-flex align-items-center justify-content-center gap-3 w-100">
                    <img class="img-fluid login-icon" src="{{{asset('asset/images/icon/svg/google.svg')}}}" alt="google"> Google
                </a>
                <a href="https://www.apple.com/" target="_blank"
                    class="btn white-btn  d-flex align-items-center justify-content-center gap-3 w-100">
                    <img class="img-fluid login-icon apple-img" src="{{asset('asset/images/icon/svg/apple.svg')}}" alt="apple">
                    Apple
                </a>
            </div>

            <h5 class="content-color fw-normal text-center mt-24"> Don’t have an
                account? <a href="signup.html" class="theme-color">Sign up for free</a>
            </h5>
        </div>
    </section>
    <!-- login section ends -->

    <!-- bootstrap js -->
    <script src="{{asset('asset/js/bootstrap.bundle.min.js')}}"></script>

    <!-- iconsax icon -->
    <script src="{{asset('asset/js/iconsax-icon.js')}}"></script>

    <!-- template-setting js -->
    <script src="{{asset('asset/js/template-setting.js')}}"></script>

    <!-- script js -->
    <script src="{{asset('asset/js/script.js')}}"></script>
</body>


<!-- Mirrored from themes.pixelstrap.com/pwa/Nedcore/login.html by HTTrack Website Copier/3.x [XR&CO'2014], Fri, 12 Sep 2025 08:07:24 GMT -->
</html>