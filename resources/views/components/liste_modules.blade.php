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

<body class="account-page">

    <!-- Main Wrapper -->
    <div class="main-wrapper">

        <div class="header shadow-sm">

            <!-- Logo -->
            <div class="header-left active">
                <a href="index.html" class="logo logo-normal">
                    <img src="{{ asset('assets/img/authentication/logo_nedcore.JPG') }}" alt="Logo">
                    <img src="{{ asset('assets/img/authentication/logo_nedcore.JPG') }}" class="white-logo"
                        alt="Logo">
                </a>
                <a href="index.html" class="logo-small">
                    <img src="{{ asset('assets/img/authentication/logo_nedcore.JPG') }}" alt="Logo">
                </a>

            </div>
            <!-- /Logo -->

            <a id="mobile_btn" class="mobile_btn" href="#sidebar">
                <span class="bar-icon">
                    <span></span>
                    <span></span>
                    <span></span>
                </span>
            </a>


            <div class="header-user">
                <ul class="nav user-menu">

                    <!-- Search -->
                    <li class="nav-item nav-search-inputs me-auto">
                        <div class="top-nav-search">
                            <a href="javascript:void(0);" class="responsive-search">
                                <i class="fa fa-search"></i>
                            </a>
                            <form action="#" class="dropdown">
                                <div class="searchinputs" id="dropdownMenuClickable">
                                    <input type="text" placeholder="Rechercher...">
                                    <div class="search-addon">
                                        <button type="submit"><i class="ti ti-command"></i></button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </li>
                    <!-- /Search -->

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
                            <li class="nav-item dropdown">
                                <a href="javascript:void(0);" class="btn btn-header-list" data-bs-toggle="dropdown">
                                    <i class="ti ti-layout-grid-add"></i>
                                </a>
                                <div class="dropdown-menu p-3"
                                    style="width: 380px; max-height: 80vh; overflow-y: auto; border-radius: 12px;border: 3px solid #bebdbdd7;">
                                    <h5 class="fw-bold mb-3"><i class="fa fa-tasks me-2"></i>Liste des
                                        modules NedCore</h5>

                                    @php
                                        $mesModules = \App\Helpers\DateHelper::dossier_info();
                                    @endphp

                                    <div class="row row-cols-4 g-3">
                                        @foreach ($mesModules['modules'] as $module)
                                            <div class="col text-center  card-hover-zoom">
                                                <a href="{{ route('dashboard', $module->id) }}"
                                                    class="text-decoration-none text-dark d-block">
                                                    <div class="d-flex align-items-center justify-content-center mx-auto mb-2 shadow"
                                                        style="width: 60px;height: 50px; transition: transform 0.3s;border-radius: 5px;">
                                                        <img src="{{ asset('storage/' . $module->logo) }}"
                                                            alt="{{ $module->nom_module }}"
                                                            class="img-fluid rounded"
                                                            style="width: 50px;height: 40px; object-fit: contain;border-radius: 5px;">
                                                    </div>
                                                    <small class="fw-medium d-block text-truncate"
                                                        title="{{ $module->nom_module }}">{{ $module->nom_module }}</small>
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

                            </li>
                        </ul>
                    </li>
                    <!-- /Nav List -->

                    <!-- Profile Dropdown -->
                    <li class="nav-item dropdown has-arrow main-drop">
                        <a href="javascript:void(0);" class="nav-link userset" data-bs-toggle="dropdown">
                            <span class="user-info">
                                <span class="user-letter">
                                    <img src="{{ asset('assets/img/user.jpg') }}" alt="Profile">
                                </span>
                                <span class="badge badge-success rounded-pill"></span>
                            </span>
                        </a>
                        <div class="dropdown-menu menu-drop-user">
                            <div class="profilename">
                                <a class="dropdown-item" href="#">
                                    <i class="ti ti-user-pin"></i> Mon Profil
                                </a>
                                <a class="dropdown-item" href="#">
                                    <i class="ti ti-lock"></i> Déconnexion
                                </a>
                            </div>
                        </div>
                    </li>
                    <!-- /Profile Dropdown -->
                </ul>
            </div>

            <!-- Mobile Menu -->
            <div class="dropdown mobile-user-menu">
                <a href="javascript:void(0);" class="nav-link dropdown-toggle" data-bs-toggle="dropdown"
                    aria-expanded="false"><i class="fa fa-ellipsis-v"></i></a>
                <div class="dropdown-menu">
                    <a class="dropdown-item" href="#">
                        <i class="ti ti-user-pin"></i> Mon Profil
                    </a>
                    <a class="dropdown-item" href="#">
                        <i class="ti ti-lock"></i> Déconnexion
                    </a>
                </div>
            </div>
            <!-- /Mobile Menu -->

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
                    </div>
                    <div class="col-xl-6 mt-5">
                        <div class="card shadow-sm">
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
                                                Célébration
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
                                        🎉 Aujourd’hui, nous célébrons l’anniversaire du grand baobab du Groupe Ned&Co :
                                        notre Président !
                                        <br><br>
                                        🌟 Toute la famille <strong>Ned&Co</strong> lui souhaite un <strong>joyeux
                                            anniversaire</strong> plein de santé, de sagesse et d’inspiration continue.
                                        <br><br>
                                        Vous êtes notre repère, notre visionnaire, et notre force motrice.
                                        <a href="#"
                                            class="text-info link-hover">#JoyeuxAnniversairePrésident</a>
                                        <a href="#" class="text-info link-hover">#FiertéNedCo</a>
                                    </p>
                                </div>
                                <div class="card shadow-none mb-3">
                                    <div class="card-img card-img-hover rounded-0">
                                        <img src="{{ asset('src2/img/President1.png') }}" class="rounded-top w-100"
                                            alt="Anniversaire Président">
                                    </div>
                                    <div class="card-body p-2">
                                        <h6 class="mb-1 text-truncate">
                                            Un hommage vibrant au leadership et à l'humanité de notre Président 🙌
                                        </h6>
                                        <a href="javascript:void(0);">YodIngénierie</a>
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
                                            <h5><a href="javascript:void(0);">Fatou B.</a></h5>
                                            <span class="ms-2">09:15</span>
                                        </div>
                                        <p class="mb-1">Joyeux anniversaire Président 🎂 Que Dieu vous comble encore
                                            de sagesse et de réussite !</p>
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
                                            <h5><a href="javascript:void(0);">Kevin M.</a></h5>
                                            <span class="ms-2">09:33</span>
                                        </div>
                                        <p class="mb-1">Merci pour votre vision et votre humanité. Un modèle pour
                                            nous tous 🙏</p>
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
                                            <h5><a href="javascript:void(0);">Nadine A.</a></h5>
                                            <span class="ms-2">09:46</span>
                                        </div>
                                        <p class="mb-1">Bon anniversaire Chef 🎉 Que cette année vous apporte encore
                                            plus de succès !</p>
                                    </div>
                                </div>
                                <div class="d-flex align-items-start">
                                    <span class="avatar avatar-rounded me-2 flex-shrink-0">
                                        <img src="{{ asset('assets/img/user.jpg') }}" alt="Img">
                                    </span>
                                    <input type="text" class="form-control" placeholder="Écrire un commentaire">
                                </div>
                            </div>
                        </div>
                        <div>
                            <div class="card shadow-sm">
                                <div class="card-header border-0 pb-0">
                                    <div
                                        class="d-flex align-items-center justify-content-between border-bottom flex-wrap row-gap-3 pb-3">
                                        <div class="d-flex align-items-center">
                                            <a href="javascript:void(0);"
                                                class="avatar avatar-lg avatar-rounded flex-shrink-0 me-2">
                                                <img src="{{ asset('assets/img/user.jpg') }}" alt="Img">
                                            </a>
                                            <div>
                                                <h5 class="mb-1"><a href="javascript:void(0);">Service Communication
                                                        <i class="ti ti-circle-check-filled text-success"></i></a></h5>
                                                <p class="d-flex align-items-center">
                                                    <span class="text-info">@communication</span>
                                                    <i class="ti ti-circle-filled fs-5 mx-2"></i>
                                                    Siège Groupe NEDCO
                                                </p>
                                            </div>
                                        </div>
                                        <div class="d-flex align-items-center">
                                            <p class="mb-0 text-dark">Il y a environ 1 heure</p>
                                            <div class="dropdown mx-1">
                                                <button
                                                    class="btn btn-icon shadow-none dropdown-toggle bg-transparent d-flex align-items-center text-dark border-0 p-0 btn-sm"
                                                    type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                </button>
                                                <ul class="dropdown-menu dropdown-menu-end">
                                                    <li><a class="dropdown-item" href="javascript:void(0);">Privé</a>
                                                    </li>
                                                    <li><a class="dropdown-item" href="javascript:void(0);">Public</a>
                                                    </li>
                                                </ul>
                                            </div>
                                            <div class="dropdown">
                                                <a href="javascript:void(0);"
                                                    class="d-inline-flex align-items-center show p-1"
                                                    data-bs-toggle="dropdown" aria-expanded="true">
                                                    <i class="ti ti-dots-vertical"></i>
                                                </a>
                                                <ul class="dropdown-menu dropdown-menu-end p-3">
                                                    <li><a href="javascript:void(0);"
                                                            class="dropdown-item rounded-1"><i
                                                                class="ti ti-edit me-2"></i>Modifier</a></li>
                                                    <li><a href="javascript:void(0);"
                                                            class="dropdown-item rounded-1"><i
                                                                class="ti ti-eye me-2"></i>Masquer</a></li>
                                                    <li><a href="javascript:void(0);"
                                                            class="dropdown-item rounded-1"><i
                                                                class="ti ti-report me-2"></i>Signaler</a></li>
                                                    <li><a href="javascript:void(0);"
                                                            class="dropdown-item rounded-1"><i
                                                                class="ti ti-trash-x me-2"></i>Supprimer</a></li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="mb-2">
                                        <p class="text-dark fw-medium">
                                            "Croyez en vous et en tout ce que vous êtes. Sachez qu'il y a quelque chose
                                            en vous qui est plus grand que n'importe quel obstacle."
                                            <a href="javascript:void(0);"
                                                class="text-info link-hover">#MotivationDuLundi</a>
                                            <a href="javascript:void(0);"
                                                class="text-info link-hover">#Inspiration</a> ✨
                                        </p>
                                    </div>
                                    <div class="mb-2">
                                        <img src="assets/img/social/social-feed-01.jpg" class="rounded"
                                            alt="Img">
                                    </div>
                                    <div class="social-gallery-slider owl-carousel mb-3">
                                        <a href="assets/img/social/gallery-big-01.jpg" data-fancybox="gallery"
                                            class="gallery-item">
                                            <img src="assets/img/social/gallery-01.jpg" class="rounded"
                                                alt="img">
                                            <span class="avatar avatar-md avatar-rounded"><i
                                                    class="ti ti-eye"></i></span>
                                        </a>
                                        <a href="assets/img/social/gallery-big-02.jpg" data-fancybox="gallery"
                                            class="gallery-item">
                                            <img src="assets/img/social/gallery-02.jpg" class="rounded"
                                                alt="img">
                                            <span class="avatar avatar-md avatar-rounded"><i
                                                    class="ti ti-eye"></i></span>
                                        </a>
                                        <a href="assets/img/social/gallery-big-03.jpg" data-fancybox="gallery"
                                            class="gallery-item">
                                            <img src="assets/img/social/gallery-03.jpg" class="rounded"
                                                alt="img">
                                            <span class="avatar avatar-md avatar-rounded"><i
                                                    class="ti ti-eye"></i></span>
                                        </a>
                                        <a href="assets/img/social/gallery-big-04.jpg" data-fancybox="gallery"
                                            class="gallery-item">
                                            <img src="assets/img/social/gallery-04.jpg" class="rounded"
                                                alt="img">
                                            <span class="avatar avatar-md avatar-rounded"><i
                                                    class="ti ti-eye"></i></span>
                                        </a>
                                        <a href="assets/img/social/gallery-big-01.jpg" data-fancybox="gallery"
                                            class="gallery-item">
                                            <img src="assets/img/social/gallery-01.jpg" class="rounded"
                                                alt="img">
                                            <span class="avatar avatar-md avatar-rounded"><i
                                                    class="ti ti-eye"></i></span>
                                        </a>
                                    </div>
                                    <div
                                        class="d-flex align-items-center justify-content-between flex-wrap row-gap-3 mb-3">
                                        <div class="d-flex align-items-center flex-wrap row-gap-3">
                                            <a href="javascript:void(0);"
                                                class="d-inline-flex align-items-center me-3">
                                                <i class="ti ti-heart me-2"></i>340K J'aime
                                            </a>
                                            <a href="javascript:void(0);"
                                                class="d-inline-flex align-items-center me-3">
                                                <i class="ti ti-message-dots me-2"></i>45 Commentaires
                                            </a>
                                            <a href="javascript:void(0);" class="d-inline-flex align-items-center">
                                                <i class="ti ti-share-3 me-2"></i>28 Partages
                                            </a>
                                        </div>
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
                                    <div class="d-flex align-items-start">
                                        <a href="javascript:void(0);"
                                            class="avatar avatar-rounded me-2 flex-shrink-0">
                                            <img src="{{ asset('assets/img/user.jpg') }}" alt="Img">
                                        </a>
                                        <input type="text" class="form-control"
                                            placeholder="Écrire un commentaire">
                                    </div>
                                </div>
                            </div>
                        </div>


                    </div>
                    <div class="col-xl-3 theiaStickySidebar mt-5">
                        <div class="card">
                            <div class="card-body  mt-3">
                                <h5 class="mb-3">Équipe Ned&Co</h5>
                                <ul class="nav nav-pills border d-flex p-2 rounded mb-3" id="pills-tab"
                                    role="tablist">
                                    <li class="nav-item flex-fill" role="presentation">
                                        <button class="nav-link btn active w-100" data-bs-toggle="pill"
                                            data-bs-target="#pills-home" type="button" role="tab"
                                            aria-selected="true">
                                            Collaborateurs
                                        </button>
                                    </li>
                                    <li class="nav-item flex-fill" role="presentation">
                                        <button class="nav-link btn w-100" data-bs-toggle="pill"
                                            data-bs-target="#pills-profile" type="button" role="tab"
                                            aria-selected="false">
                                            Responsables
                                        </button>
                                    </li>
                                </ul>
                                <div class="tab-content">
                                    <div class="tab-pane fade show active" id="pills-home" role="tabpanel">
                                        <div>
                                            <div class="d-flex align-items-center justify-content-between mb-3">
                                                <div class="d-flex align-items-center">
                                                    <a href="javascript:void(0);"
                                                        class="avatar avatar-rounded flex-shrink-0 me-2">
                                                        <img src="{{ asset('assets/img/user.jpg') }}" alt="Img">
                                                    </a>
                                                    <div>
                                                        <h6 class="fw-medium mb-1">Esther Mavoungou</h6>
                                                        <span class="fs-12 d-block">Service RH</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="d-flex align-items-center justify-content-between mb-3">
                                                <div class="d-flex align-items-center">
                                                    <a href="javascript:void(0);"
                                                        class="avatar avatar-rounded flex-shrink-0 me-2">
                                                        <img src="{{ asset('assets/img/user.jpg') }}" alt="Img">
                                                    </a>
                                                    <div>
                                                        <h6 class="fw-medium mb-1">Yvan Obame</h6>
                                                        <span class="fs-12 d-block">Département Finance</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="d-flex align-items-center justify-content-between mb-3">
                                                <div class="d-flex align-items-center">
                                                    <a href="javascript:void(0);"
                                                        class="avatar avatar-rounded flex-shrink-0 me-2">
                                                        <img src="{{ asset('assets/img/user.jpg') }}" alt="Img">
                                                    </a>
                                                    <div>
                                                        <h6 class="fw-medium mb-1">Céline Nzamba</h6>
                                                        <span class="fs-12 d-block">Direction Juridique</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <a href="javascript:void(0);" class="btn btn-outline-light w-100 border">Voir
                                            toute l'équipe<i class="ti ti-arrow-right ms-2"></i></a>
                                    </div>
                                    <div class="tab-pane fade" id="pills-profile" role="tabpanel">
                                        <div>
                                            <div class="d-flex align-items-center justify-content-between mb-3">
                                                <div class="d-flex align-items-center">
                                                    <a href="javascript:void(0);"
                                                        class="avatar avatar-rounded flex-shrink-0 me-2">
                                                        <img src="{{ asset('assets/img/user.jpg') }}" alt="Img">
                                                    </a>
                                                    <div>
                                                        <h6 class="fw-medium mb-1">Président Ned&Co</h6>
                                                        <span class="fs-12 d-block">Direction Générale</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="d-flex align-items-center justify-content-between mb-3">
                                                <div class="d-flex align-items-center">
                                                    <a href="javascript:void(0);"
                                                        class="avatar avatar-rounded flex-shrink-0 me-2">
                                                        <img src="{{ asset('assets/img/user.jpg') }}" alt="Img">
                                                    </a>
                                                    <div>
                                                        <h6 class="fw-medium mb-1">Lucie Moussavou</h6>
                                                        <span class="fs-12 d-block">Directrice Générale Adjointe</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <a href="javascript:void(0);" class="btn btn-outline-light w-100 border">Voir
                                            les responsables<i class="ti ti-arrow-right ms-2"></i></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-body">
                                <h5 class="mb-3">Documents Récents</h5>
                                <div class="bg-light rounded p-2 mb-2">
                                    <p class="text-dark fw-medium"><a href="javascript:void(0);">Plan stratégique
                                            2025-2027</a></p>
                                </div>
                                <div class="bg-light rounded p-2 mb-2">
                                    <p class="text-dark fw-medium"><a href="javascript:void(0);">Rapport d'activité
                                            2024</a></p>
                                </div>
                                <div class="bg-light rounded p-2">
                                    <p class="text-dark fw-medium"><a href="javascript:void(0);">Règlement intérieur
                                            Ned&Co</a></p>
                                </div>
                                <div class="mt-3">
                                    <a href="javascript:void(0);" class="btn btn-outline-light w-100 border">Voir tous
                                        les documents<i class="ti ti-arrow-right ms-2"></i></a>
                                </div>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-body">
                                <h5 class="mb-3">Tendances</h5>
                                <div class="d-flex align-items-center flex-wrap gap-1">
                                    <a href="javascript:void(0);"
                                        class="text-info d-inline-flex link-hover">#NedCore</a>
                                    <a href="javascript:void(0);"
                                        class="text-info d-inline-flex link-hover">#AnniversairePresident</a>
                                    <a href="javascript:void(0);"
                                        class="text-info d-inline-flex link-hover">#ExcellenceNedCo</a>
                                    <a href="javascript:void(0);"
                                        class="text-info d-inline-flex link-hover">#FamilleNed</a>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

                <div class="load-btn text-center">
                    <a href="javascript:void(0);" class="btn btn-primary"><i
                            class="ti ti-loader ms-0 me-1"></i>Charger plus</a>
                </div>

            </div>
            <!-- /Content -->

        </div>
    </div>
    <!-- /Main Wrapper -->

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
    <script src="{{ asset('assets/scripts/7d0fa10a/cloudflare-static/rocket-loader.min.js') }}" defer></script>
    <script defer src="https://static.cloudflareinsights.com/beacon.min.js/vcd15cbe7772f49c399c6a5babf22c1241717689176015"
        integrity="sha512-ZpsOmlRQV6y907TI0dKBHq9Md29nnaEIPlkf84rnaERnq6zvWvPUqr2ft8M1aS28oN72PdrCzSjY4U6VaAw1EQ=="
        data-cf-beacon='{"rayId":"94a720077e49e3c0","version":"2025.5.0","serverTiming":{"name":{"cfExtPri":true,"cfEdge":true,"cfOrigin":true,"cfL4":true,"cfSpeedBrain":true,"cfCacheStatus":true}},"token":"3ca157e612a14eccbb30cf6db6691c29","b":1}'
        crossorigin="anonymous"></script>
</body>


<!-- Mirrored from crms.dreamstechnologies.com/html/template/# by HTTrack Website Copier/3.x [XR&CO'2014], Wed, 04 Jun 2025 11:36:40 GMT -->

</html>
