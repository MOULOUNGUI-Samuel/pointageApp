<!-- Header -->
<div class="header shadow-sm">
    @php
        $mesModules = \App\Helpers\DateHelper::dossier_info();
    @endphp
    <style>
        .logo-unifie {
            height: 50px;
            width: 60px;
            border-radius: 5px;
            border: 1px solid #05426b60;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            object-fit: cover;
            /* Pour éviter les déformations */
        }
    </style>
    <!-- Logo -->
    <div class="header-left active mt-1">
        <a href="#" class="logo logo-normal">
            <img src="{{ asset('assets/img/authentication/logo_nedcore.JPG') }}" alt="Logo" class="rounded">
            <img src="{{ asset('assets/img/authentication/logo_nedcore.JPG') }}" class="white-logo rounded" alt="Logo">
        </a>
        <a href="#" class="logo-small">
            <img src="{{ asset('assets/img/authentication/logo_nedcore.JPG') }}" class="rounded" alt="Logo">
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
                <div class="" style="display: flex">
                    {{-- <span class="user-info">
                        <span class="user-letter">
                            <img src="{{ asset('storage/' . $entreprise_logo) }}" alt="Profile" />
                        </span>
                    </span> --}}
                    <button class="btn btn-outline-primary" type="button"
                        @if (Auth::user()->statut_vue_entreprise === 1) data-bs-toggle="offcanvas"
                        data-bs-target="#offcanvasWithBackdrop"
                        aria-controls="offcanvasWithBackdrop" @endif>{{ $entreprise_nom }}</button>

                    <div style="margin-left: 30px;margin-top: 5px">
                        @if (session('module_id'))
                            <a href="{{ route('logout_module', ['id' => session('module_id')]) }}"
                                onclick="event.preventDefault(); document.getElementById('logout-form1').submit();">
                                <i class="fa fa-sign-out" style="font-size: 20px;margin-right:6px"></i>
                                <span class="" style="font-size: 20px">Déconnexion</span>
                            </a>

                            <form id="logout-form1" action="{{ route('logout_module', ['id' => session('module_id')]) }}"
                                method="POST" style="display: none;">
                                @csrf
                            </form>
                        @else
                            <a href="{{ route('logout') }}"
                                onclick="event.preventDefault(); document.getElementById('logout-form2').submit();">
                                <i class="fa fa-sign-out" style="font-size: 20px;margin-right:6px"></i>
                                <span style="font-size: 20px" class="">Déconnexion</span>
                            </a>

                            <form id="logout-form2" action="{{ route('logout') }}" method="POST" style="display: none;">
                                @csrf
                            </form>
                        @endif
                    </div>
                </div>
            </li>

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
                    <button class="btn btn-primary btn-sm ms-2" onclick="enablePush()">Activer les notifications</button>
                @endauth

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
                        <div class="dropdown-menu  p-3"
                            style="width: 380px; max-height: 80vh; overflow-y: auto; border-radius: 12px;border: 3px solid #bebdbdd7;">
                            <div class="row row-cols-4 g-3">
                                @foreach ($mesModules['modules'] as $module)
                                    @if ($module->nom_module === 'Caisses')
                                        @if (Auth::user()->statut_vue_caisse === 1)
                                            <div class="col text-center  card-hover-zoom">
                                                <a href="https://caisse.nedcore.net/authenticate/{{ Auth::user()->id }}"
                                                    class="text-decoration-none text-dark d-block">
                                                    <div class="d-flex align-items-center justify-content-center mx-auto mb-2 shadow"
                                                        style="width: 60px;height: 50px; transition: transform 0.3s;border-radius: 5px;">
                                                        <img src="{{ asset('storage/' . $module->logo) }}"
                                                            alt="{{ $module->nom_module }}" class="img-fluid rounded"
                                                            style="width: 50px;height: 40px; object-fit: contain;border-radius: 5px;">
                                                    </div>
                                                    <small class="fw-medium d-block text-truncate"
                                                        title="{{ $module->nom_module }}">{{ $module->nom_module }}</small>
                                                </a>
                                                {{-- <a type="button" data-bs-toggle="offcanvas"
                                                    data-bs-target="#offcanvasWithBackdrop2"
                                                    aria-controls="offcanvasWithBackdrop2"
                                                    class="text-decoration-none text-dark d-block">
                                                    <div class="d-flex align-items-center justify-content-center mx-auto mb-2 shadow"
                                                        style="width: 60px;height: 50px; transition: transform 0.3s;border-radius: 5px;">
                                                        <img src="{{ asset('storage/' . $module->logo) }}"
                                                            alt="{{ $module->nom_module }}" class="img-fluid rounded"
                                                            style="width: 50px;height: 40px; object-fit: contain;border-radius: 5px;">
                                                    </div>
                                                    <small class="fw-medium d-block text-truncate"
                                                        title="{{ $module->nom_module }}">{{ $module->nom_module }}</small>
                                                </a> --}}
                                            </div>
                                        @endif
                                    @elseif($module->nom_module === 'Agenda')
                                        <div class="col text-center  card-hover-zoom">
                                            <a href="https://agenda.groupenedco.com/authenticate/{{ Auth::user()->id }}"
                                                class="text-decoration-none text-dark d-block">
                                                <div class="d-flex align-items-center justify-content-center mx-auto mb-2 shadow"
                                                    style="width: 60px;height: 50px; transition: transform 0.3s;border-radius: 5px;">
                                                    <img src="{{ asset('storage/' . $module->logo) }}"
                                                        alt="{{ $module->nom_module }}" class="img-fluid rounded"
                                                        style="width: 50px;height: 40px; object-fit: contain;border-radius: 5px;">
                                                </div>
                                                <small class="fw-medium d-block text-truncate"
                                                    title="{{ $module->nom_module }}">{{ $module->nom_module }}</small>
                                            </a>
                                        </div>
                                    @elseif($module->nom_module === 'GED')
                                        <div class="col text-center  card-hover-zoom">
                                            <a href="https://ged.nedcore.net/ged/authenticate/{{ Auth::user()->id }}"
                                                class="text-decoration-none text-dark d-block">
                                                <div class="d-flex align-items-center justify-content-center mx-auto mb-2 shadow"
                                                    style="width: 60px;height: 50px; transition: transform 0.3s;border-radius: 5px;">
                                                    <img src="{{ asset('storage/' . $module->logo) }}"
                                                        alt="{{ $module->nom_module }}" class="img-fluid rounded"
                                                        style="width: 50px;height: 40px; object-fit: contain;border-radius: 5px;">
                                                </div>
                                                <small class="fw-medium d-block text-truncate"
                                                    title="{{ $module->nom_module }}">{{ $module->nom_module }}</small>
                                            </a>
                                        </div>
                                    @elseif($module->nom_module === 'Configurations')
                                        @if (Auth::user()->super_admin === 1)
                                            <div class="col text-center  card-hover-zoom">
                                                <a href="{{ route('dashboard', $module->id) }}"
                                                    class="text-decoration-none text-dark d-block">
                                                    <div class="d-flex align-items-center justify-content-center mx-auto mb-2 shadow"
                                                        style="width: 60px;height: 50px; transition: transform 0.3s;border-radius: 5px;">
                                                        <img src="{{ asset('storage/' . $module->logo) }}"
                                                            alt="{{ $module->nom_module }}" class="img-fluid rounded"
                                                            style="width: 50px;height: 40px; object-fit: contain;border-radius: 5px;">
                                                    </div>
                                                    <small class="fw-medium d-block text-truncate"
                                                        title="{{ $module->nom_module }}">{{ $module->nom_module }}</small>
                                                </a>
                                            </div>
                                        @endif
                                    @else
                                        <div class="col text-center  card-hover-zoom">
                                            <a href="{{ route('dashboard', $module->id) }}"
                                                class="text-decoration-none text-dark d-block">
                                                <div class="d-flex align-items-center justify-content-center mx-auto mb-2 shadow"
                                                    style="width: 60px;height: 50px; transition: transform 0.3s;border-radius: 5px;">
                                                    <img src="{{ asset('storage/' . $module->logo) }}"
                                                        alt="{{ $module->nom_module }}" class="img-fluid rounded"
                                                        style="width: 50px;height: 40px; object-fit: contain;border-radius: 5px;">
                                                </div>
                                                <small class="fw-medium d-block text-truncate"
                                                    title="{{ $module->nom_module }}">{{ $module->nom_module }}</small>
                                            </a>
                                        </div>
                                    @endif
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
                            <div class="mt-4">
                                <a href="{{ route('components.liste_module') }}"
                                    class="btn btn-outline-primary btn-sm w-100 btn-block"
                                    style="margin-bottom: 10px;color:white;font-size: 12px">
                                    <i class="ti ti-arrow-left"></i>
                                    Retour sur la page d'actualité
                                </a>
                            </div>
                        </div>
                    </li>
                </ul>
            </li>
            <!-- /Nav List -->
            <!-- /Notifications -->

            <!-- Profile Dropdown -->
            <li class="nav-item dropdown has-arrow main-drop">
                <a href="javascript:void(0);" class="nav-link userset" data-bs-toggle="dropdown">
                    <span class="user-info">
                        <span class="user-letter">
                            <img src="{{ asset('storage/' . $entreprise_logo) }}" alt="Profile" />
                        </span>
                        <span class="badge badge-success rounded-pill"></span>
                    </span>
                </a>
                <div class="dropdown-menu menu-drop-user">
                    <div class="profilename">
                        <a class="dropdown-item" href="#">
                            <i class="ti ti-user-pin"></i> Mon Profil
                        </a>
                        {{-- @if (session('module_id'))
                            <a class="dropdown-item" href="#">
                                <i class="ti ti-lock"></i> Déconnexion
                            </a> --}}
                        @if (session('module_id'))
                            <a href="{{ route('logout_module', ['id' => session('module_id')]) }}"
                                onclick="event.preventDefault(); document.getElementById('logout-form-0').submit();">
                                <i class="fa fa-sign-out text-primary" style="font-size: 17px;margin-right:6px"></i>
                                <span class=" text-primary" style="font-size: 15px">Déconnexion</span>
                            </a>

                            <form id="logout-form-0"
                                action="{{ route('logout_module', ['id' => session('module_id')]) }}" method="POST"
                                style="display: none;">
                                @csrf
                            </form>
                        @else
                            <a href="{{ route('logout') }}"
                                onclick="event.preventDefault(); document.getElementById('logout-form-3').submit();">
                                <i class="fa fa-sign-out text-primary" style="font-size: 17px;margin-right:6px"></i>
                                <span style="font-size: 15px" class=" text-primary">Déconnexion</span>
                            </a>

                            <form id="logout-form-3" action="{{ route('logout') }}" method="POST"
                                style="display: none;">
                                @csrf
                            </form>
                        @endif
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
            @if (session('module_id'))
                <a href="{{ route('logout_module', ['id' => session('module_id')]) }}"
                    onclick="event.preventDefault(); document.getElementById('logout-form-2').submit();">
                    <i class="fa fa-sign-out text-primary" style="font-size: 17px;margin-right:6px"></i>
                    <span class=" text-primary" style="font-size: 15px">Déconnexion</span>
                </a>

                <form id="logout-form-2" action="{{ route('logout_module', ['id' => session('module_id')]) }}"
                    method="POST" style="display: none;">
                    @csrf
                </form>
            @else
                <a href="{{ route('logout') }}"
                    onclick="event.preventDefault(); document.getElementById('logout-form-1').submit();">
                    <i class="fa fa-sign-out text-primary" style="font-size: 17px;margin-right:6px"></i>
                    <span style="font-size: 15px" class=" text-primary">Déconnexion</span>
                </a>

                <form id="logout-form-1" action="{{ route('logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>
            @endif
        </div>
    </div>
    <!-- /Mobile Menu -->
</div>
<!-- /Header -->
