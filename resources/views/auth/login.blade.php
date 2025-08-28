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
     <meta name="theme-color" content="#05436b">
    <!-- Le chemin doit être absolu depuis la racine publique -->
    <link rel="manifest" href="/manifest.json">
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
<style>
    /* Style pour le bouton d'installation (Android) */
#installBtn {
    position: fixed;
    bottom: 20px;
    left: 50%;
    transform: translateX(-50%);
    padding: 12px 24px;
    background: #05436b; /* Votre couleur de thème */
    color: white;
    border: none;
    border-radius: 8px;
    font-size: 16px;
    font-weight: 500;
    cursor: pointer;
    box-shadow: 0 4px 8px rgba(0,0,0,0.2);
    opacity: 0;
    animation: fadeInUp 0.5s 0.5s ease forwards;
    z-index: 9999;
}

/* Style pour la bannière d'instructions (iOS) */
#ios-install-banner {
    position: fixed;
    bottom: 20px;
    left: 10px;
    right: 10px;
    background-color: rgba(40, 40, 40, 0.95);
    backdrop-filter: blur(5px);
    -webkit-backdrop-filter: blur(5px);
    color: white;
    padding: 15px;
    border-radius: 12px;
    text-align: center;
    font-size: 15px;
    z-index: 1000;
    box-shadow: 0 4px 15px rgba(0,0,0,0.3);
    animation: fadeInUp 0.5s 0.5s ease forwards;
}

#ios-install-banner .share-icon {
    display: inline-block;
    vertical-align: text-bottom;
    width: 20px;
    height: 20px;
    margin: 0 2px;
}

#close-install-banner {
    position: absolute;
    top: 5px;
    right: 10px;
    background: none;
    border: none;
    color: white;
    font-size: 24px;
    line-height: 1;
    padding: 0;
    cursor: pointer;
    opacity: 0.7;
}

/* Animation commune pour l'apparition */
@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(20px) translateX(-50%);
    }
    to {
        opacity: 1;
        transform: translateY(0) translateX(-50%);
    }
}
/* Ajustement pour la bannière qui est en pleine largeur */
#ios-install-banner {
    animation-name: slideUpFull;
}
@keyframes slideUpFull {
    from {
        opacity: 0;
        transform: translateY(100%);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}
</style>
<body>
         <!-- Ce bouton sera utilisé par Android/Chrome -->
<button id="installBtn" class="d-none"></button>

<!-- Cette bannière sera utilisée par iOS -->
<div id="ios-install-banner" class="d-none">
    <button id="close-install-banner" aria-label="Fermer">&times;</button>
    Pour une meilleure expérience, ajoutez cette application à votre écran d'accueil. Appuyez sur 
    <img src="https://img.icons8.com/ios/50/ffffff/share-3.png" alt="Share Icon" class="share-icon"> 
    puis sur "Sur l'écran d'accueil".
</div>
    <div class="login-content"
        style="background: linear-gradient(rgba(0, 0, 0, 0.795), rgba(0, 0, 0, 0.836)),
    url('{{ asset('src/images/login.webp') }}') no-repeat center center;
    background-size: cover;
    background-attachment: fixed;
    color: #fff;">
        <!-- Login -->
        <div class="nk-block toggled" id="l-login">
            <div class="text-center">
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
                    <a href="{{ route('entrer') }}" class="text-decoration-none shadow-sm">
                        <div class="card card-hover-zoom shadow-lg px-2">
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

        <!-- Forgot Password -->

    </div>
    <script>
       // ===============================================
//         SCRIPT PWA COMPLET ET UNIVERSEL
// ===============================================

// Enregistrement robuste du Service Worker
if ('serviceWorker' in navigator) {
    window.addEventListener('load', () => {
        navigator.serviceWorker.register('/sw.js')
            .then(registration => {
                console.log('✅ Service Worker enregistré avec succès:', registration.scope);
            })
            .catch(error => {
                console.error('❌ Échec de l\'enregistrement du Service Worker:', error);
            });
    });
}

// Logique d'installation gérant Android et iOS
document.addEventListener('DOMContentLoaded', function() {
    
    // --- Fonctions utilitaires ---
    const isIos = () => /iphone|ipad|ipod/.test(window.navigator.userAgent.toLowerCase());
    const isInStandaloneMode = () => ('standalone' in window.navigator) && (window.navigator.standalone);

    // Si l'application est déjà installée, on ne fait rien.
    if (isInStandaloneMode()) {
        console.log("🚀 Application lancée en mode standalone.");
        return;
    }

    // --- Logique pour iOS ---
    if (isIos()) {
        const banner = document.getElementById('ios-install-banner');
        if(banner) {
            banner.classList.remove('d-none'); // On affiche la bannière d'instructions
        }

        const closeBtn = document.getElementById('close-install-banner');
        if(closeBtn) {
            closeBtn.addEventListener('click', () => {
                banner.style.display = 'none';
            });
        }
    }

    // --- Logique pour Android & Chrome Desktop ---
    let deferredPrompt;
    window.addEventListener('beforeinstallprompt', (e) => {
        // L'événement est déclenché, mais on l'empêche si on est sur iOS (double sécurité)
        if (isIos()) {
            return;
        }
        
        // Empêche la mini-infobulle de Chrome de s'afficher
        e.preventDefault();
        // Sauvegarde l'événement pour pouvoir le déclencher plus tard
        deferredPrompt = e;

        // Met à jour l'interface utilisateur pour notifier l'utilisateur qu'il peut installer la PWA
        const installBtn = document.getElementById('installBtn');
        if(installBtn) {
            installBtn.textContent = '📲 Installer sur mon appareil !';
            installBtn.classList.remove('d-none'); // Affiche notre bouton personnalisé

            installBtn.addEventListener('click', () => {
                // Cache notre bouton
                installBtn.style.display = 'none';
                // Affiche la demande d'installation
                deferredPrompt.prompt();
                
                // Attend le choix de l'utilisateur
                deferredPrompt.userChoice.then((choiceResult) => {
                    if (choiceResult.outcome === 'accepted') {
                        console.log('✅ L\'utilisateur a accepté l\'installation');
                    } else {
                        console.log('❌ L\'utilisateur a refusé l\'installation');
                    }
                    deferredPrompt = null;
                });
            });
        }
    });

    // Écouteur pour savoir quand la PWA a été installée avec succès
    window.addEventListener('appinstalled', () => {
        console.log('🎉 PWA installée avec succès !');
        // Cache le bouton d'installation si l'événement a lieu
        const installBtn = document.getElementById('installBtn');
        if (installBtn) {
            installBtn.style.display = 'none';
        }
        deferredPrompt = null;
    });

});
    </script>
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
