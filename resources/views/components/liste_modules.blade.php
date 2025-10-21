<!DOCTYPE html>
<html lang="fr">

<head>

    <!-- Meta Tags -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description"
        content="NedCore est la solution numérique centralisée du Groupe NedCo, dédiée à la gestion intégrée de toutes les entités du groupe. Grâce à une interface moderne et intuitive, chaque collaborateur accède aux modules essentiels : gestion RH, finances, projets, documents et reporting stratégique">
    <meta name="keywords"
        content="NedCore, solution numérique, Groupe NedCo, gestion intégrée, gestion RH, finances, projets, documents, reporting stratégique, interface moderne, outils d'entreprise">
    <meta name="author" content="Dreams Technologies">
    <meta name="robots" content="index, follow">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Title -->
    <title>NedCore</title>

    <!-- Apple Touch Icon -->
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('assets/img/apple-touch-icon.png') }}">

    <!-- Favicon -->
    <link rel="icon" href="{{ asset('assets/img/authentication/logo_nedcore.JPG') }}" type="image/x-icon">
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('assets/img/authentication/logo_nedcore.JPG') }}">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Tabler Icon CSS -->
    <link rel="stylesheet" href="{{ asset('assets/plugins/tabler-icons/tabler-icons.css') }}">

    <!-- Fontawesome CSS -->
    <link rel="stylesheet" href="{{ asset('assets/plugins/fontawesome/css/fontawesome.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/plugins/fontawesome/css/all.min.css') }}">

    <!-- Datetimepicker CSS -->
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap-datetimepicker.min.css') }}">

    <!-- Animation CSS -->
    <link rel="stylesheet" href="{{ asset('assets/css/animate.css') }}">

    <!-- Select2 CSS -->
    <link rel="stylesheet" href="{{ asset('assets/plugins/select2/css/select2.min.css') }}">

    <!-- Owl Carousel -->
    <link rel="stylesheet" href="{{ asset('assets/css/owl.carousel.min.css') }}">

    <!-- Color Picker CSS -->
    <link rel="stylesheet" href="{{ asset('assets/plugins/flatpickr/flatpickr.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/plugins/@simonwep/pickr/themes/nano.min.css') }}">

    <!-- Main CSS -->
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">

</head>

<body class="account-page" style="background-color: rgb(240, 243, 243)">

    <!-- Main Wrapper -->
    <div class="main-wrapper" id="main-content">
        @php
            $lesEntreprises = \App\Helpers\DateHelper::dossier_info();
        @endphp
        <div class="header shadow-sm">

            <!-- Logo -->
            <div class="header-left active">
                <a href="#" class="logo logo-normal">
                    <img src="{{ asset('assets/img/authentication/logo_nedcore.JPG') }}" alt="Logo">
                    <img src="{{ asset('assets/img/authentication/logo_nedcore.JPG') }}" class="white-logo"
                        alt="Logo">
                </a>
                <a href="#" class="logo-small">
                    <img src="{{ asset('assets/img/authentication/logo_nedcore.JPG') }}" alt="Logo">
                </a>

            </div>


            <div class="header-user">
                <ul class="nav user-menu">

                    <!-- Search -->
                    <li class="nav-item nav-search-inputs me-auto">
                        <button class="btn btn-outline-primary" type="button"
                            @if (Auth::user()->statut_vue_entreprise === 1) data-bs-toggle="offcanvas"
                        data-bs-target="#offcanvasWithBackdrop1"
                        aria-controls="offcanvasWithBackdrop1" @endif>{{ $entreprise_nom }}</button>
                    </li>
                    <li class="nav-item flex-fill is-hidden d-flex text-center gap-5" id="blocApres" role="presentation"
                        aria-hidden="true" style="margin-left: 390px">
                        @foreach ($lesEntreprises['entreprise'] as $index => $entreprise)
                            @if ($entreprise->code_entreprise !== 'YOD')
                                <button
                                    class="nav-link border-0 bg-transparent p-2 {{ $index === 0 ? 'active' : '' }} mb-2"
                                    id="pills2-{{ $entreprise->id }}-tab" data-bs-toggle="pill"
                                    data-bs-target="#pills2-{{ $entreprise->id }}" type="button" role="tab"
                                    aria-controls="pills2-{{ $entreprise->id }}"
                                    aria-selected="{{ $index === 0 ? 'true' : 'false' }}">
                                    <div class="text-center card-hover-zoom">
                                        <div class="d-flex align-items-center justify-content-center mx-auto mb-2 shadow-sm"
                                            style="width:50px;height:40px;transition:.3s;border-radius:12px;background:white;">
                                            <img src="{{ asset('storage/' . $entreprise->logo) }}"
                                                alt="{{ $entreprise->nom_entreprise }}" class="img-fluid"
                                                style="width:60px;height:60px;object-fit:contain;">
                                        </div>
                                    </div>
                                </button>
                                <script>
                                    document.getElementById('pill-{{ $entreprise->id }}').addEventListener('click', function() {
                                        // Vérifie que le bouton est bien de type "button"
                                        if (this.type === 'button') {
                                            document.getElementById('pills-{{ $entreprise->id }}').click(); // Simule le clic
                                        }
                                    });
                                </script>
                            @endif
                        @endforeach
                    </li>
                    <!-- /Search -->
                    <li class="d-flex justify-content-center align-items-center gap-3">
                        {{-- @auth
                            <button class="btn btn-primary" onclick="enablePush()">Activer les notifications</button>
                        @endauth --}}
                        @php $unread = auth()->user()?->unreadNotifications()->count() ?? 0; @endphp

                        <a href="{{ route('notifications.index') }}" class="btn btn-sm position-relative">
                            Notifications
                            <span id="notifBadge"
                                class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger {{ $unread ? '' : 'd-none' }}">
                                {{ $unread }}
                            </span>
                        </a>

                        {{-- zone d’affichage des toasts --}}
                        <div id="toast-area" class="position-fixed top-0 end-0 p-3" style="z-index: 1080;"></div>

                        {{-- bouton d’abonnement push (si tu veux le garder) --}}
                        @auth
                            <button class="btn btn-primary btn-sm ms-2" onclick="enablePush()">Activer les
                                notifications</button>
                        @endauth

                    </li>
                    <!-- Nav List -->
                    <li class="nav-item nav-list">
                        <ul class="nav">
                            <li>
                                <div>
                                    <a href="#" class="btn btn-icon border btn-menubar btnFullscreen">
                                        <i class="ti ti-maximize"></i>
                                    </a>
                                </div>
                            </li>
                            <li class="dark-mode-list">
                                <a href="javascript:void(0);" id="dark-mode-toggle" class="dark-mode-toggle">
                                    <i class="ti ti-sun light-mode active"></i>
                                    <i class="ti ti-moon dark-mode"></i>
                                </a>
                            </li>
                        </ul>
                    </li>
                    <!-- /Nav List -->

                    <!-- Profile Dropdown -->
                    <li class="nav-item dropdown has-arrow main-drop">
                        <a href="javascript:void(0);" class="nav-link userset" data-bs-toggle="dropdown">
                            <span class="user-info">
                                <span class="user-letter">
                                    <img src="{{ asset('storage/' . $entreprise_logo) }}" alt="Profile">
                                </span>
                                <span class="badge badge-success rounded-pill"></span>
                            </span>
                        </a>
                        <div class="dropdown-menu menu-drop-user">
                            <div class="profilename">
                                <a class="dropdown-item" href="#">
                                    <i class="ti ti-user-pin"></i> Mon Profil
                                </a>
                                <a href="{{ route('logout') }}"
                                    onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                    <i class="fa fa-sign-out" style="font-size: 20px;margin-right:6px"></i>
                                    <span style="font-size: 20px" class="">Déconnexion</span>
                                </a>

                                <form id="logout-form" action="{{ route('logout') }}" method="POST"
                                    style="display: none;">
                                    @csrf
                                </form>
                            </div>
                        </div>
                    </li>
                    <!-- /Profile Dropdown -->
                </ul>
            </div>
            @php
                $mesModules = \App\Helpers\DateHelper::dossier_info();
            @endphp


        </div>

        <div class="notes-page-wrapper">
            <div class="content">

                <div class="row">
                    <div class="col-xl-3 theiaStickySidebar">

                        <div class="mt-5">
                            <div style="border-left: 4px solid #05426bce; padding-left: 12px;">
                                <h4 class="mb-0">ESPACE UTILITAIRES</h4>
                            </div>
                        </div>
                        @if (session('success'))
                            <div class="alert alert-success rounded-pill alert-dismissible fade show mt-2">
                                <strong class="me-5"><i class="fas fa-check me-2"></i>
                                    {{ session('success') }}</strong>
                                <button type="button" class="btn-close custom-close" data-bs-dismiss="alert"
                                    aria-label="Close"><i class="fas fa-xmark"></i></button>
                            </div>
                        @endif
                        @if (session('error'))
                            <div class="alert alert-danger rounded-pill alert-dismissible fade show">
                                <strong class="me-5">
                                    {{ session('error') }}</strong>
                                <button type="button" class="btn-close custom-close" data-bs-dismiss="alert"
                                    aria-label="Close"><i class="fas fa-xmark"></i></button>
                            </div>
                        @endif

                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }} <button type="button" class="close"
                                                data-dismiss="alert" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button></li>
                                    @endforeach
                                </ul>

                            </div>
                        @endif
                        @include('components.utilitaires._utilitaires_menu')

                    </div>
                    <!-- Modal -->
                    <div class="modal fade" id="generateTasksModal" tabindex="-1"
                        aria-labelledby="generateTasksModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="generateTasksModalLabel">Générateur de tâches
                                        IA - OpenProject</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <div class="modal-body">

                                    <!-- Le jeton API n'est plus un champ ! -->

                                    <div class="mb-3">
                                        <label for="project" class="form-label">Choisissez un projet</label>
                                        <select id="project" class="form-select">
                                            <option value="">-- Chargement des projets... --</option>
                                        </select>
                                    </div>

                                    <div class="mb-3">
                                        <label for="prompt" class="form-label">Description / prompt</label>
                                        <textarea id="prompt" rows="5" class="form-control"
                                            placeholder="Décrivez votre projet ici pour générer les tâches..."></textarea>
                                    </div>

                                    <div class="mb-3">
                                        <label for="nombre" class="form-label">Nombre de tâches à
                                            générer</label>
                                        <input type="number" id="nombre" class="form-control" min="1"
                                            max="20" placeholder="Ex: 5" value="5">
                                    </div>

                                    <div class="result" id="result"></div>

                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary"
                                        data-bs-dismiss="modal">Fermer</button>
                                    <button type="button" class="btn btn-primary" onclick="genererTaches()">Générer
                                        et créer les tâches</button>
                                </div>
                            </div>
                        </div>
                    </div>


                    <style>
                        /* Quand on dépasse le seuil, on fixe la barre en haut */

                        /* Animation propre */
                        #blocAvant,
                        #blocApres {
                            transition: opacity .25s ease, transform .25s ease;
                        }

                        /* Masqué (ne prend pas de place) */
                        .is-hidden {
                            opacity: 0;
                            visibility: hidden;
                            height: 0;
                            overflow: hidden;
                            pointer-events: none;
                        }

                        /* Option : si tu veux garder la place dans la mise en page, remplace par :
.is-hidden { opacity:0; visibility:hidden; }
*/
                    </style>
                    <div class="col-xl-6 my-5">
                        <div class="card">

                            <div class="card-body">
                                <div class="my-2" style="border-left: 4px solid #05426bce; padding-left: 12px;">
                                    <h3 class="mb-0">Actualités entréprises</h3>
                                </div>

                                <ul class="nav nav-pills d-flex mb-3 sticky-top py-2" id="pills-tab" role="tablist"
                                    style="top:0; z-index:1030;">
                                    @foreach ($lesEntreprises['entreprise'] as $index => $entreprise)
                                        @if ($entreprise->code_entreprise !== 'YOD')
                                            <li class="nav-item flex-fill" role="presentation">
                                                <button
                                                    class="nav-link w-100 border-0 bg-transparent p-2 {{ $index === 0 ? 'active' : '' }} "
                                                    id="pills-{{ $entreprise->id }}-tab" data-bs-toggle="pill"
                                                    data-bs-target="#pills-{{ $entreprise->id }}" type="button"
                                                    role="tab" aria-controls="pills-{{ $entreprise->id }}"
                                                    aria-selected="{{ $index === 0 ? 'true' : 'false' }}">
                                                    <div class="text-center card-hover-zoom">
                                                        <div class="d-flex align-items-center justify-content-center mx-auto mb-2 shadowlogo shadow"
                                                            style="width:80px;height:70px;transition:.3s;border-radius:12px;background:white;">
                                                            <img src="{{ asset('storage/' . $entreprise->logo) }}"
                                                                alt="{{ $entreprise->nom_entreprise }}"
                                                                class="img-fluid"
                                                                style="width:60px;height:60px;object-fit:contain;">
                                                        </div>
                                                    </div>
                                                </button>
                                            </li>
                                        @endif
                                    @endforeach
                                </ul>

                                <div class="tab-content" id="pills-tabContent">
                                    @foreach ($lesEntreprises['entreprise'] as $index => $entreprise)
                                        <div class="tab-pane fade {{ $index === 0 ? 'show active' : '' }}"
                                            id="pills-{{ $entreprise->id }}" role="tabpanel"
                                            aria-labelledby="pills-{{ $entreprise->id }}-tab">
                                            @php
                                                // Configuration des widgets par code société
                                                $widgetConfig = match ($entreprise->code_entreprise) {
                                                    'BFEV' => ['id' => '300959', 'hasWidget' => true],
                                                    // 'EZER' => ['id' => '304184', 'hasWidget' => true],
                                                    // 'EGCC' => ['id' => '304185', 'hasWidget' => 1],
                                                    'COMKETING' => ['id' => '304779', 'hasWidget' => true],
                                                    'YODI' => ['id' => '304182', 'hasWidget' => true],
                                                    'YOD' => ['id' => '304182', 'hasWidget' => true],
                                                    'NEH' => ['id' => '300959', 'hasWidget' => true],
                                                    // 'ING' => ['id' => '304185', 'hasWidget' => true],
                                                    default => ['id' => null, 'hasWidget' => false],
                                                };
                                            @endphp

                                            @if ($widgetConfig['hasWidget'] && $widgetConfig['id'])
                                                {{-- Loading state --}}
                                                <div id="taggbox-loading-{{ $entreprise->id }}"
                                                    class="text-center py-5">
                                                    <div class="spinner-border text-primary" role="status">
                                                        <span class="visually-hidden">Chargement...</span>
                                                    </div>
                                                    <p class="mt-2">Chargement des actualités en cours...</p>
                                                </div>

                                                {{-- Widget Taggbox --}}
                                                <div class="taggbox" style="width:100%; height:100%; overflow:auto;"
                                                    data-widget-id="{{ $widgetConfig['id'] }}" data-website="1">
                                                </div>

                                                {{-- Script pour masquer le loading une fois le widget chargé --}}
                                                <script>
                                                    document.addEventListener('DOMContentLoaded', function() {
                                                        setTimeout(function() {
                                                            const loading = document.getElementById('taggbox-loading-{{ $entreprise->id }}');
                                                            if (loading) loading.style.display = 'none';
                                                        }, 2000);
                                                    });
                                                </script>
                                            @else
                                                {{-- Message si pas d'actualités --}}
                                                <div class="text-center py-5">
                                                    <div class="mb-4">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="64"
                                                            height="64" fill="currentColor"
                                                            class="bi bi-newspaper text-muted" viewBox="0 0 16 16">
                                                            <path
                                                                d="M0 2.5A1.5 1.5 0 0 1 1.5 1h11A1.5 1.5 0 0 1 14 2.5v10.528c0 .3-.05.654-.238.972h.738a.5.5 0 0 0 .5-.5v-9a.5.5 0 0 1 1 0v9a1.5 1.5 0 0 1-1.5 1.5H1.497A1.497 1.497 0 0 1 0 13.5zM12 14c.37 0 .654-.211.853-.441.092-.106.147-.279.147-.531V2.5a.5.5 0 0 0-.5-.5h-11a.5.5 0 0 0-.5.5v11c0 .278.223.5.497.5z" />
                                                            <path
                                                                d="M2 3h10v2H2zm0 3h4v3H2zm0 4h4v1H2zm0 2h4v1H2zm5-6h2v1H7zm3 0h2v1h-2zM7 8h2v1H7zm3 0h2v1h-2zm-3 2h2v1H7zm3 0h2v1h-2zm-3 2h2v1H7zm3 0h2v1h-2z" />
                                                        </svg>
                                                    </div>
                                                    <h5 class="text-muted mb-2">Aucune actualité disponible</h5>
                                                    <p class="text-muted small">
                                                        Les actualités de
                                                        <strong>{{ $entreprise->nom_entreprise }}</strong>
                                                        seront bientôt disponibles ici.
                                                    </p>
                                                </div>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>

                            </div>
                            <style>
                                /* Style par défaut */
                                .nav-pills .nav-link {
                                    transition: all 0.3s ease;
                                    border-radius: 12px;
                                }

                                .nav-pills .nav-link:hover {
                                    background-color: rgba(0, 0, 0, 0.05);
                                    transform: translateY(-2px);
                                }

                                /* Style actif - beaucoup plus visible */
                                .nav-pills .nav-link.active {
                                    background: transparent !important;
                                    transform: scale(1.05);
                                }


                                .nav-pills .nav-link.active .shadowlogo {
                                    box-shadow: 0 4px 15px rgba(40, 40, 41, 0.4);
                                    transform: scale(1.2);

                                }

                                .card-hover-zoom {
                                    transition: all 0.5s ease;
                                }
                            </style>
                            {{-- <div class="card shadow-sm mx-5">
                            <div class="card-header border-0 pb-0">
                                <div
                                    class="d-flex align-items-center justify-content-between border-bottom flex-wrap row-gap-3 pb-3">
                                    <div class="d-flex align-items-center">
                                        <a href="javascript:void(0);"
                                            class="avatar avatar-lg avatar-rounded flex-shrink-0 me-2">
                                            <img src="{{ asset('assets/img/user.jpg') }}" alt="Img">
                                        </a>
                                        <div>
                                            <h5 class="mb-1"><a href="javascript:void(0);">Groupe Ned&Co <i
                                                        class="ti ti-circle-check-filled text-success"></i></a></h5>
                                            <p class="d-flex align-items-center">
                                                <span class="text-info">@groupe_nedco</span>
                                                <i class="ti ti-circle-filled fs-5 mx-2"></i>
                                                Actualités
                                            </p>
                                        </div>
                                    </div>
                                    <div class="d-flex align-items-center">
                                        <p class="mb-0 text-dark">Aujourd'hui</p>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="mb-2">
                                    <p class="text-dark fw-medium">
                                        🌟 <strong>GROUPE NED&CO - ÉCONOMIE SOCIALE</strong> 🌟
                                        <br><br>
                                        Le Groupe NED&CO est le bras séculier de l'ONG NÉHÉMIE International : nous
                                        prônons une économie sociale où le business sert la transformation sociétale !
                                        🇬🇦
                                        <br><br>
                                        <strong>Notre Modèle Unique :</strong>
                                        <br>
                                        📋 5 Sociétés Supports apportent leur expertise complète aux ASMG (entreprises
                                        opérationnelles) pour les développer :
                                        <!-- Lien pour dérouler le contenu -->
                                        <a class="text-info link-hover" data-bs-toggle="collapse"
                                            href="#post-details" role="button" aria-expanded="false"
                                            aria-controls="post-details">
                                            Voir plus...
                                        </a>
                                    </p>

                                    <!-- Contenu masqué qui se déroule -->
                                    <div class="collapse" id="post-details">
                                        <ul class="mt-2">
                                            <li><strong>BFEV</strong> = Stratégie, conseil business, mentorat
                                                entrepreneurs 📈</li>
                                            <li><strong>COMKETING</strong> = Marketing digital, communication,
                                                événementiel 📢</li>
                                            <li><strong>YOD INGÉNIERIE</strong> = Solutions IT, système NEDCORE,
                                                sécurité informatique 💻</li>
                                            <li><strong>FCI</strong> = Finance, investissements, levées de fonds 💰</li>
                                            <li><strong>ALPHON PARTNERS</strong> = Comptabilité, juridique, conformité
                                                fiscale ⚖️</li>
                                        </ul>
                                        <p class="text-dark fw-medium">
                                            <strong>Résultat :</strong>
                                        </p>
                                        <ul>
                                            <li>✅ ASMG développées et performantes</li>
                                            <li>✅ Sociétés Supports expertes</li>
                                            <li>✅ Tous ensemble = Mission d'économie sociale accomplie</li>
                                            <li>✅ Profits au service de l'impact social et des actions caritatives</li>
                                        </ul>
                                        <p class="text-dark fw-medium">
                                            Notre expertise collective transforme le business gabonais selon les valeurs
                                            chrétiennes ! 🚀
                                        </p>
                                    </div>
                                    <p class="mt-2">
                                        <a href="#" class="text-info link-hover">#ÉconomieSociale</a>
                                        <a href="#" class="text-info link-hover">#NED&CO</a>
                                        <a href="#" class="text-info link-hover">#Excellence</a>
                                        <a href="#" class="text-info link-hover">#ImpactSocial</a>
                                    </p>
                                </div>
                                <div class="card shadow-none mb-3">
                                    <div class="card-img card-img-hover rounded-0 text-center">
                                        <!-- Assurez-vous que le chemin vers votre nouvelle image est correct -->
                                        <img src="{{ asset('src/images/annonce2.jpeg') }}"
                                            class="rounded w-50 shadow-sm" alt="Le Groupe NED&CO"
                                            style="width: 200px">
                                    </div>
                                    <div class="card-body p-2">
                                        <h6 class="mb-1 text-truncate">
                                            Le Groupe NED&CO : Moteur de transformation de l'écosystème entrepreneurial
                                        </h6>
                                        <a href="javascript:void(0);">Groupe NED&CO</a>
                                    </div>
                                </div>
                                <div class="d-flex align-items-center justify-content-between flex-wrap row-gap-3">
                                    <div class="d-flex align-items-center flex-wrap row-gap-3">
                                        <a href="javascript:void(0);" class="d-inline-flex align-items-center me-3">
                                            <i class="ti ti-heart me-2"></i>1.2K J'aime
                                        </a>
                                        <a href="javascript:void(0);" class="d-inline-flex align-items-center me-3">
                                            <i class="ti ti-message-dots me-2"></i>132 Commentaires
                                        </a>
                                        <a href="javascript:void(0);" class="d-inline-flex align-items-center">
                                            <i class="ti ti-share-3 me-2"></i>58 Partages
                                        </a>
                                    </div>
                                    <div class="d-flex align-items-center">
                                        <a href="javascript:void(0);" class="btn btn-icon btn-sm rounded-circle"><i
                                                class="ti ti-heart-filled text-danger"></i></a>
                                        <a href="javascript:void(0);" class="btn btn-icon btn-sm rounded-circle"><i
                                                class="ti ti-share"></i></a>
                                        <a href="javascript:void(0);" class="btn btn-icon btn-sm rounded-circle"><i
                                                class="ti ti-message-star"></i></a>
                                        <a href="javascript:void(0);" class="btn btn-icon btn-sm rounded-circle"><i
                                                class="ti ti-bookmark-filled text-warning"></i></a>
                                    </div>
                                </div>
                                <div class="d-flex align-items-start mt-3">
                                    <span class="avatar avatar-rounded me-2 flex-shrink-0">
                                        <img src="{{ asset('assets/img/user.jpg') }}" alt="Img">
                                    </span>
                                    <input type="text" class="form-control" placeholder="Écrire un commentaire">
                                </div>
                            </div>
                        </div>
                        <div class="card shadow-sm mx-5">
                            <div class="card-header border-0 pb-0">
                                <div
                                    class="d-flex align-items-center justify-content-between border-bottom flex-wrap row-gap-3 pb-3">
                                    <div class="d-flex align-items-center">
                                        <a href="javascript:void(0);"
                                            class="avatar avatar-lg avatar-rounded flex-shrink-0 me-2">
                                            <img src="{{ asset('assets/img/user.jpg') }}" alt="Img">
                                        </a>
                                        <div>
                                            <h5 class="mb-1"><a href="javascript:void(0);">Groupe Ned&Co <i
                                                        class="ti ti-circle-check-filled text-success"></i></a></h5>
                                            <p class="d-flex align-items-center">
                                                <span class="text-info">@yoding</span>
                                                <i class="ti ti-circle-filled fs-5 mx-2"></i>
                                                Actualités
                                            </p>
                                        </div>
                                    </div>
                                    <div class="d-flex align-items-center">
                                        <p class="mb-0 text-dark">Aujourd'hui</p>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="mb-2">
                                    <p class="text-dark fw-medium">
                                        🚀 <strong>Nouvelle version de l'application disponible !</strong>
                                        <br><br>
                                        Nous sommes ravis de vous présenter les dernières améliorations de notre
                                        plateforme :
                                        <br>
                                        • Interface utilisateur modernisée pour une meilleure expérience
                                        <br>
                                        • Temps de chargement optimisé
                                        <br>
                                        • Nouvelles fonctionnalités de reporting
                                        <br><br>
                                        N'hésitez pas à nous faire part de vos retours !
                                        <a href="#" class="text-info link-hover">#Nouveauté</a>
                                        <a href="#" class="text-info link-hover">#Innovation</a>
                                    </p>
                                </div>
                                <div class="card shadow-none mb-3">
                                    <div class="card-img card-img-hover rounded-0">
                                        <img src="{{ asset('src/images/annonce.png') }}"
                                            class="rounded w-100 shadow-sm" alt="Nouvelle Interface">
                                    </div>
                                    <div class="card-body p-2">
                                        <h6 class="mb-1 text-truncate">
                                            Découvrez la nouvelle interface utilisateur plus intuitive et performante 🎯
                                        </h6>
                                        <a href="javascript:void(0);">Équipe Technique</a>
                                    </div>
                                </div>
                                <div class="d-flex align-items-center justify-content-between flex-wrap row-gap-3">
                                    <div class="d-flex align-items-center flex-wrap row-gap-3">
                                        <a href="javascript:void(0);" class="d-inline-flex align-items-center me-3">
                                            <i class="ti ti-heart me-2"></i>1.2K J'aime
                                        </a>
                                        <a href="javascript:void(0);" class="d-inline-flex align-items-center me-3">
                                            <i class="ti ti-message-dots me-2"></i>132 Commentaires
                                        </a>
                                        <a href="javascript:void(0);" class="d-inline-flex align-items-center">
                                            <i class="ti ti-share-3 me-2"></i>58 Partages
                                        </a>
                                    </div>
                                    <div class="d-flex align-items-center">
                                        <a href="javascript:void(0);" class="btn btn-icon btn-sm rounded-circle"><i
                                                class="ti ti-heart-filled text-danger"></i></a>
                                        <a href="javascript:void(0);" class="btn btn-icon btn-sm rounded-circle"><i
                                                class="ti ti-share"></i></a>
                                        <a href="javascript:void(0);" class="btn btn-icon btn-sm rounded-circle"><i
                                                class="ti ti-message-star"></i></a>
                                        <a href="javascript:void(0);" class="btn btn-icon btn-sm rounded-circle"><i
                                                class="ti ti-bookmark-filled text-warning"></i></a>
                                    </div>
                                </div>
                                <div class="d-flex align-items-start mb-3 mt-3">
                                    <a href="javascript:void(0);" class="avatar avatar-rounded flex-shrink-0 me-2">
                                        <img src="{{ asset('assets/img/user.jpg') }}" alt="Img">
                                    </a>
                                    <div class="bg-light rounded flex-fill p-2">
                                        <div class="d-flex align-items-center mb-1">
                                            <h5><a href="javascript:void(0);"> MBADINGA Joelle.</a></h5>
                                            <span class="ms-2">09:15</span>
                                        </div>
                                        <p class="mb-1">La nouvelle interface est vraiment intuitive. La navigation
                                            est beaucoup plus fluide maintenant ! 👍</p>
                                        <div class="d-flex align-items-center">
                                            <a href="javascript:void(0);"
                                                class="btn btn-icon btn-sm rounded-circle"><i
                                                    class="ti ti-heart-filled text-danger"></i></a>
                                            <a href="javascript:void(0);"
                                                class="btn btn-icon btn-sm rounded-circle"><i
                                                    class="ti ti-share"></i></a>
                                            <a href="javascript:void(0);"
                                                class="btn btn-icon btn-sm rounded-circle"><i
                                                    class="ti ti-message-star"></i></a>
                                            <a href="javascript:void(0);"
                                                class="btn btn-icon btn-sm rounded-circle"><i
                                                    class="ti ti-bookmark-filled text-warning"></i></a>
                                        </div>
                                    </div>
                                </div>
                                <div class="d-flex align-items-start mb-3">
                                    <a href="javascript:void(0);" class="avatar avatar-rounded flex-shrink-0 me-2">
                                        <img src="{{ asset('assets/img/user.jpg') }}" alt="Img">
                                    </a>
                                    <div class="bg-light rounded flex-fill p-2">
                                        <div class="d-flex align-items-center mb-1">
                                            <h5><a href="javascript:void(0);">NDZIGHE ONGONE FREDDY.</a></h5>
                                            <span class="ms-2">09:33</span>
                                        </div>
                                        <p class="mb-1">Les nouveaux rapports sont très complets. Ça va nous faire
                                            gagner un temps précis ! 💯</p>
                                        <div class="d-flex align-items-center">
                                            <a href="javascript:void(0);"
                                                class="btn btn-icon btn-sm rounded-circle"><i
                                                    class="ti ti-heart-filled text-danger"></i></a>
                                            <a href="javascript:void(0);"
                                                class="btn btn-icon btn-sm rounded-circle"><i
                                                    class="ti ti-share"></i></a>
                                            <a href="javascript:void(0);"
                                                class="btn btn-icon btn-sm rounded-circle"><i
                                                    class="ti ti-message-star"></i></a>
                                            <a href="javascript:void(0);"
                                                class="btn btn-icon btn-sm rounded-circle"><i
                                                    class="ti ti-bookmark-filled text-warning"></i></a>
                                        </div>

                                    </div>
                                </div>
                                <div class="d-flex align-items-start mb-3">
                                    <a href="javascript:void(0);" class="avatar avatar-rounded flex-shrink-0 me-2">
                                        <img src="{{ asset('assets/img/user.jpg') }}" alt="Img">
                                    </a>
                                    <div class="bg-light rounded flex-fill p-2">
                                        <div class="d-flex align-items-center mb-1">
                                            <h5><a href="javascript:void(0);">MAGNI BALLA LAURE NAIKE</a></h5>
                                            <span class="ms-2">09:46</span>
                                        </div>
                                        <p class="mb-1">L'application est plus rapide, c'est vraiment agréable à
                                            utiliser maintenant. Bon travail l'équipe ! 👏</p>
                                    </div>
                                </div>
                                <div class="d-flex align-items-start">
                                    <span class="avatar avatar-rounded me-2 flex-shrink-0">
                                        <img src="{{ asset('assets/img/user.jpg') }}" alt="Img">
                                    </span>
                                    <input type="text" class="form-control" placeholder="Écrire un commentaire">
                                </div>
                            </div>
                        </div> --}}
                        </div>
                    </div>
                    <script>
                        (function() {
                            const threshold = 80; // px
                            const blocAvant = document.getElementById('pills-tab');
                            const blocApres = document.getElementById('blocApres');

                            function update() {
                                const scrolled = window.scrollY > threshold;

                                // toggle visibilité
                                blocAvant.classList.toggle('is-hidden', scrolled);
                                blocApres.classList.toggle('is-hidden', !scrolled);

                                // accessibilité
                                blocAvant.setAttribute('aria-hidden', scrolled ? 'true' : 'false');
                                blocApres.setAttribute('aria-hidden', scrolled ? 'false' : 'true');
                            }

                            window.addEventListener('scroll', update, {
                                passive: true
                            });
                            window.addEventListener('resize', update);
                            document.addEventListener('DOMContentLoaded', update);
                        })();
                    </script>



                    <div class="col-xl-3 theiaStickySidebar">
                        <div class="mt-5">
                            <div style="border-left: 4px solid #05426bce; padding-left: 12px;">
                                <h4 class="mb-0">MODULES NEDCORE</h4>
                            </div>
                        </div>
                        @include('components.modules_nedcore.mdodules')
                    </div>

                </div>

            </div>
        </div>
        <div class="offcanvas offcanvas-start" tabindex="-1" id="offcanvasWithBackdrop1"
            aria-labelledby="offcanvasWithBackdropLabel">
            <div class="offcanvas-header">
                <h5 class="offcanvas-title ps-3 mb-3" id="offcanvasWithBackdropLabel"
                    style="border-left: 5px solid #05436b; color: #333;">
                    Actuellement sur : {{ Str::limit($entreprise_nom, 15, '...') }}
                </h5>
                <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas"
                    aria-label="Close"></button>
            </div> <!-- end offcanvas-header-->

            <div class="offcanvas-body">
                <div class="p-3" style="overflow-y: auto;">
                    <div class="row row-cols-3 g-2">
                        @foreach ($lesEntreprises['entreprise'] as $entreprise)
                            <div class="col text-center  card-hover-zoom">
                                <a href="{{ route('change_entreprise', $entreprise->id) }}"
                                    class="text-decoration-none text-dark d-block">
                                    <div class="d-flex align-items-center justify-content-center mx-auto mb-2 shadow"
                                        style="width: 80px;height: 70px; transition: transform 0.3s;border-radius: 5px;">
                                        <img src="{{ asset('storage/' . $entreprise->logo) }}"
                                            alt="{{ $entreprise->nom_entreprise }}" class="img-fluid rounded"
                                            style="width: 80px;height: 70px; object-fit: contain;border-radius: 5px;border-radius: 20px">
                                    </div>
                                    <small class="fw-medium d-block text-truncate"
                                        title="{{ $entreprise->nom_entreprise }}">{{ $entreprise->nom_entreprise }}</small>
                                </a>
                            </div>
                        @endforeach
                        <style>
                            .card-hover-zoom {
                                transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
                            }

                            .card-hover-zoom:hover {
                                transform: scale(1.15);
                                z-index: 2;
                            }
                        </style>
                    </div>
                </div>
            </div> <!-- end offcanvas-body-->
        </div>
        @include('components._demande_interventions', [
            'entreprises' => $entreprises,
            'demandes' => $demandes,
            'search' => $search,
            'filtreStatut',
            $filtreStatut,
            'onlyMine' => $onlyMine,
        ])
        @include('components.fenetre_simulation')
        <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasWithBackdrop3"
            aria-labelledby="offcanvasWithBackdropLabel1">
            <div class="offcanvas-header">
                <h5 class="offcanvas-title ps-3 mb-3" id="offcanvasWithBackdropLabel1"
                    style="border-left: 5px solid #05436b; color: #333;">
                    Clé API OpenProject
                </h5>
                <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas"
                    aria-label="Close"></button>
            </div> <!-- end offcanvas-header-->
            <div class="offcanvas-body">
                <!-- Dans votre vue Blade, par exemple resources/views/profile/edit.blade.php -->

                <!-- Formulaire pour enregistrer la clé (vous l'avez déjà) -->
                <form method="post" action="{{ route('profile.update.apikey') }}">
                    @csrf
                    @method('patch')
                    <!-- ... champ input pour la clé ... -->
                    <div class="mb-3">
                        <label for="api_key" class="form-label">Clé API OpenProject</label>
                        <input type="text" class="form-control mb-3" id="api_key" name="openproject_api_token"
                            value="{{ old('openproject_api_token') }}"
                            placeholder="Entrez votre clé API OpenProject">
                        <button type="submit" class="btn btn-primary text-center">Enregistrer la
                            clé</button>
                </form>

            </div> <!-- end offcanvas-body-->

        </div>

    </div>

    <script>
        (function() {
            // 1) Quand on clique un bouton secondaire, on active l’onglet principal correspondant
            const secondaryBtns = document.querySelectorAll('button[id^="pills2-"][id$="-tab"]');

            secondaryBtns.forEach(btn => {
                btn.addEventListener('click', function(e) {
                    // Récupère l'id “entreprise” depuis pills2-<ID>-tab
                    const id = this.id.replace(/^pills2-/, '').replace(/-tab$/, '');
                    // Trouve le bouton d’onglet principal associé
                    const mainBtn = document.getElementById(`pills-${id}-tab`);
                    if (!mainBtn) return;

                    // Active l’onglet principal via l’API Bootstrap
                    const tab = bootstrap.Tab.getOrCreateInstance(mainBtn);
                    tab.show();

                    // Visuel : active le bouton secondaire cliqué, désactive les autres
                    secondaryBtns.forEach(b => b.classList.remove('active'));
                    this.classList.add('active');
                    this.setAttribute('aria-selected', 'true');
                    secondaryBtns.forEach(b => {
                        if (b !== this) b.setAttribute('aria-selected', 'false');
                    });
                });
            });

            // 2) Quand un onglet principal change, on synchronise le bouton secondaire correspondant
            const mainBtns = document.querySelectorAll('button[id^="pills-"][id$="-tab"][data-bs-toggle="pill"]');

            mainBtns.forEach(btn => {
                btn.addEventListener('shown.bs.tab', function(e) {
                    // e.target est l’onglet ACTIVÉ (pills-<ID>-tab)
                    const id = e.target.id.replace(/^pills-/, '').replace(/-tab$/, '');
                    const sec = document.getElementById(`pills2-${id}-tab`);
                    if (!sec) return;

                    // Active le bon bouton secondaire, désactive les autres
                    secondaryBtns.forEach(b => {
                        const isTarget = b.id === `pills2-${id}-tab`;
                        b.classList.toggle('active', isTarget);
                        b.setAttribute('aria-selected', isTarget ? 'true' : 'false');
                    });
                });
            });
        })();
    </script>

    <!-- /Main Wrapper -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://widget.taggbox.com/embed.min.js" type="text/javascript"></script>
    <!-- À la fin de votre fichier Blade, avant </body> -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Cible tous les boutons ayant l'attribut data-loader-target
            document.querySelectorAll('[data-loader-target]').forEach(function(btn) { // Simplification du sélecteur
                btn.addEventListener('click', function(event) {
                    const targetId = btn.getAttribute('data-loader-target');
                    const loaderBtn = document.getElementById(targetId);

                    if (btn.type === 'submit') {
                        const form = btn.closest('form');
                        if (form && !form.checkValidity()) {
                            // Si le formulaire n'est pas valide, empêche l'action par défaut
                            event.preventDefault();
                            event.stopPropagation();
                            form.classList.add(
                                'was-validated'
                            ); // Ajoute la classe Bootstrap pour afficher les erreurs
                            return;
                        }
                    }

                    if (loaderBtn) {
                        btn.style.display = 'none';
                        loaderBtn.style.display = 'inline-block';
                    }
                });
            });
        });
    </script>
    <script>
        // On utilise jQuery car votre code l'utilise déjà
        $('#login-caisse-form').on('submit', function(e) {
            e.preventDefault(); // Empêche le rechargement de la page
            const form = $(this);
            const errorDiv = $('#login-error');
            const submitButton = form.find('button[type="submit"]');

            // Cacher les erreurs et désactiver le bouton pour éviter les double-clics
            errorDiv.hide().text('');
            submitButton.prop('disabled', true).text('Connexion...');

            $.ajax({
                url: form.attr('action'),
                type: 'POST',
                data: form.serialize(),
                success: function(response) {
                    // Si la réponse contient une 'redirect_url', on y va !
                    if (response.success && response.redirect_url) {
                        window.location.href = response.redirect_url;
                    } else {
                        // Cas d'erreur inattendu où success=true mais pas d'URL
                        errorDiv.text(response.message || 'Une erreur de redirection est survenue.')
                            .show();
                        submitButton.prop('disabled', false).text('Se connecter');
                    }
                },
                error: function(xhr) {
                    // Gérer les erreurs (401, 500, etc.)
                    let message = xhr.responseJSON?.message || 'Une erreur est survenue.';
                    errorDiv.text(message).show();
                    submitButton.prop('disabled', false).text('Se connecter');
                }
            });
        });
    </script>

    <script>
        // Configuration pour les requêtes AJAX avec le token CSRF de Laravel
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        const projectSelect = document.getElementById('project');
        const resultDiv = document.getElementById('result');
        const generateTasksModal = document.getElementById('generateTasksModal');

        // Écouteur d'événement pour charger les projets à l'ouverture de la modale
        generateTasksModal.addEventListener('show.bs.modal', async function() {
            projectSelect.innerHTML = '<option>Chargement en cours...</option>';
            resultDiv.innerHTML = ''; // Nettoyer les anciens résultats

            try {
                const response = await fetch("{{ route('projects') }}");

                if (!response.ok) {
                    const errorData = await response.json();
                    projectSelect.innerHTML =
                        `<option value=''>Erreur: ${errorData.error || 'Jeton invalide ou manquant.'}</option>`;
                    return;
                }

                const data = await response.json();
                if (data._embedded.elements.length === 0) {
                    projectSelect.innerHTML = `<option value=''>-- Aucun projet trouvé --</option>`;
                    return;
                }

                const options = data._embedded.elements.map(proj => `
          <option value="${proj.identifier}">${proj.name} (${proj.identifier})</option>
        `).join('');
                projectSelect.innerHTML = options;

            } catch (error) {
                console.error('Erreur lors du chargement des projets:', error);
                projectSelect.innerHTML = '<option>Erreur de connexion.</option>';
            }
        });

        async function genererTaches() {
            const project = projectSelect.value;
            const prompt = document.getElementById('prompt').value.trim();
            const nombre = parseInt(document.getElementById('nombre').value);

            if (!project || !prompt || isNaN(nombre)) {
                resultDiv.innerHTML = '<div class="alert alert-danger">Veuillez remplir tous les champs.</div>';
                return;
            }

            resultDiv.innerHTML =
                '<div class="alert alert-info">⏳ Génération et création en cours... Veuillez patienter.</div>';

            try {
                const res = await fetch("{{ route('tasks.create') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                            'content') // Important pour Laravel
                    },
                    body: JSON.stringify({
                        project,
                        prompt,
                        nombre
                    })
                });

                const data = await res.json();

                if (!res.ok) {
                    resultDiv.innerHTML =
                        `<div class="alert alert-danger">❌ Erreur côté serveur: ${data.error || data.message}</div>`;
                    return;
                }

                let htmlResult = '<b>Résultat :</b><br><br>';
                data.forEach((t, i) => {
                    const statusClass = t.ok ? "text-success" : "text-danger";
                    const icon = t.ok ? "✅" : "❌";
                    const message = t.message ? `- ${t.message}` : "";
                    htmlResult += `<div class="${statusClass}">${icon} ${i + 1}. ${t.titre} ${message}</div>`;
                });
                resultDiv.innerHTML = htmlResult;

            } catch (error) {
                console.error('Erreur:', error);
                resultDiv.innerHTML = '<div class="alert alert-danger">❌ Une erreur inattendue est survenue.</div>';
            }
        }
    </script>
    <!-- jQuery -->
    <script src="{{ asset('assets/js/jquery-3.7.1.min.js') }}" type="text/javascript"></script>

    <!-- Feather Icon JS -->
    <script src="{{ asset('assets/js/feather.min.js') }}" type="text/javascript"></script>

    <!-- Owl Carousel -->
    <script src="{{ asset('assets/js/owl.carousel.min.js') }}" type="text/javascript"></script>

    <!-- Slimscroll JS -->
    <script src="{{ asset('assets/js/jquery.slimscroll.min.js') }}" type="text/javascript"></script>

    <!-- Bootstrap Core JS -->
    <script src="{{ asset('assets/js/bootstrap.bundle.min.js') }}" type="text/javascript"></script>

    <!-- Select2 JS -->
    <script src="{{ asset('assets/plugins/select2/js/select2.min.js') }}" type="text/javascript"></script>

    <!-- Sticky-sidebar -->
    <script src="{{ asset('assets/plugins/theia-sticky-sidebar/ResizeSensor.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/plugins/theia-sticky-sidebar/theia-sticky-sidebar.js') }}" type="text/javascript">
    </script>

    <!-- Color Picker JS -->
    <script src="{{ asset('assets/plugins/@simonwep/pickr/pickr.es5.min.js') }}" type="text/javascript"></script>

    <!-- Custom JS -->
    <script src="{{ asset('assets/js/theme-colorpicker.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/js/script.js') }}" type="text/javascript"></script>

    <!-- Cloudflare Rocket Loader -->
    <script src="{{ asset('cdn-cgi/scripts/7d0fa10a/cloudflare-static/rocket-loader.min.js') }}" defer></script>
    <script defer src="https://static.cloudflareinsights.com/beacon.min.js/vcd15cbe7772f49c399c6a5babf22c1241717689176015"
        integrity="sha512-ZpsOmlRQV6y907TI0dKBHq9Md29nnaEIPlkf84rnaERnq6zvWvPUqr2ft8M1aS28oN72PdrCzSjY4U6VaAw1EQ=="
        data-cf-beacon='{"rayId":"94a720077e49e3c0","version":"2025.5.0","serverTiming":{"name":{"cfExtPri":true,"cfEdge":true,"cfOrigin":true,"cfL4":true,"cfSpeedBrain":true,"cfCacheStatus":true}},"token":"3ca157e612a14eccbb30cf6db6691c29","b":1}'
        crossorigin="anonymous"></script>
    <!-- JQuery -->

</body>

</html>
