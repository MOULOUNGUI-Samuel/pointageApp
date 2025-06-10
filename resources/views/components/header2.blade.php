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
                    <h4 style="margin-top: 8px;">{{ $entreprise_nom }}
                    </h4>
                    <div style="margin-left: 30px;margin-top: 5px">
                        @if (session('module_id'))
                            <a href="{{ route('logout_module', ['id' => session('module_id')]) }}"
                                onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                <i class="fa fa-sign-out" style="font-size: 20px;margin-right:6px"></i>
                                <span class="" style="font-size: 20px">Déconnexion</span>
                            </a>

                            <form id="logout-form" action="{{ route('logout_module', ['id' => session('module_id')]) }}"
                                method="POST" style="display: none;">
                                @csrf
                            </form>
                        @else
                            <a href="{{ route('logout') }}"
                                onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                <i class="fa fa-sign-out" style="font-size: 20px;margin-right:6px"></i>
                                <span style="font-size: 20px" class="">Déconnexion</span>
                            </a>

                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                @csrf
                            </form>
                        @endif
                    </div>
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
                        <div class="dropdown-menu dropdown-menu-end menus-info">
                            <div class="row">
                                <div class="col-md-12">
                                    <h4 class="text-primary pt-2"><i class="fa fa-tasks"></i>
                                        Liste des modules NedCore.
                                    </h4>
                                </div>
                                @foreach ($mesModules['modules'] as $module)
                                    <div class="col-md-6">
                                        <ul class="menu-list">
                                            <li>
                                                <a href="{{ route('dashboard', $module->id) }}">
                                                    <div class="menu-details">
                                                        <span class="menu-list-icon me-3">
                                                            <img class="shadow"
                                                                src="{{ asset('storage/' . $module->logo) }}"
                                                                alt=""
                                                                style="width: 45px;
  min-width: 36px;
  height: 36px;" />
                                                            {{-- <img src="{{ asset('storage/' . $module->logo) }}"
                                                                alt="" width="100" /> --}}
                                                        </span>
                                                        <div class="menu-details-content">
                                                            <p>{{ $module->nom_module }}</p>
                                                            <span>Module Actif</span>
                                                        </div>
                                                    </div>
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                @endforeach
                                
                            </div>
                            <div class="row">
                                <div class="col"></div>
                                <div class="col-md-4 mt-3">
                                    <a href="{{ route('components.liste_module') }}" class="btn btn-primary btn-sm w-100 btn-block"
                                        style="margin-bottom: 10px;color:white;font-size: 12px">
                                        Liste des modules
                                    </a>
                                </div>
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
                                onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                <i class="fa fa-sign-out text-primary" style="font-size: 17px;margin-right:6px"></i>
                                <span class=" text-primary" style="font-size: 15px">Déconnexion</span>
                            </a>

                            <form id="logout-form"
                                action="{{ route('logout_module', ['id' => session('module_id')]) }}" method="POST"
                                style="display: none;">
                                @csrf
                            </form>
                        @else
                            <a href="{{ route('logout') }}"
                                onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                <i class="fa fa-sign-out text-primary" style="font-size: 17px;margin-right:6px"></i>
                                <span style="font-size: 15px" class=" text-primary">Déconnexion</span>
                            </a>

                            <form id="logout-form" action="{{ route('logout') }}" method="POST"
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
                    onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    <i class="fa fa-sign-out text-primary" style="font-size: 17px;margin-right:6px"></i>
                    <span class=" text-primary" style="font-size: 15px">Déconnexion</span>
                </a>

                <form id="logout-form" action="{{ route('logout_module', ['id' => session('module_id')]) }}"
                    method="POST" style="display: none;">
                    @csrf
                </form>
            @else
                <a href="{{ route('logout') }}"
                    onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    <i class="fa fa-sign-out text-primary" style="font-size: 17px;margin-right:6px"></i>
                    <span style="font-size: 15px" class=" text-primary">Déconnexion</span>
                </a>

                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>
            @endif
        </div>
    </div>
    <!-- /Mobile Menu -->
</div>
<!-- /Header -->
