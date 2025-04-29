<!-- Start Header Top Area -->
<div class="header-top-area">
    <div class="container">
        <div class="row">
            <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12">
                <div>
                    <a href="#">
                        @if (isset($module_logo))
                            <img src="{{ asset('storage/' . $module_logo) }}" alt="" width="96"
                                class="rounded" />
                        @else
                            <img src="{{ asset('src/images/YODIPOINTE1.png') }}" alt="" width="96"
                                class="rounded" />
                        @endif
                    </a>
                </div>
            </div>

            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                <div style="margin-top: 17px; text-align: left;color:aliceblue">
                    <h4 style="font-size: 25px"> <span id="currentDateTime" class="text-capitalize"> </span> : <span
                            id="currentTime"></span>
                    </h4>
                </div>
            </div>

            <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                <div class="header-top-menu">
                    <ul class="nav navbar-nav notika-top-nav">
                        @if (Auth::user()->role_user !== 'Admin')
                            <li>
                                <div style="margin-top: 17px; text-align: right;color:aliceblue">
                                    <i>
                                        <h4 style="font-size: 20px"><i class="icon-library"></i>
                                            {{ Auth::user()->entreprise->nom_entreprise }}
                                        </h4>
                                    </i>
                                </div>

                            </li>
                        @endif
                        <li class="nav-item nc-al">
                            <a href="#" data-toggle="dropdown" role="button" aria-expanded="false"
                                class="nav-link dropdown-toggle"><span><i class="notika-icon notika-alarm"></i></span>
                            </a>

                            <div role="menu" class="dropdown-menu message-dd notification-dd animated zoomIn">
                                <div class="hd-mg-tt">
                                    <h2>Notification</h2>
                                </div>
                                <div class="hd-message-info">
                                    <a href="#">
                                        <div class="hd-message-sn">
                                            <div class="hd-message-img">
                                                <img src="{{ asset('src/img/post/1.jpg') }}" alt="" />
                                            </div>
                                            <div class="hd-mg-ctn">
                                                <h3>David Belle</h3>
                                                <p>Cum sociis natoque penatibus et magnis dis parturient montes</p>
                                            </div>
                                        </div>
                                    </a>


                                </div>
                                <div class="hd-mg-va">
                                    <a href="#">View All</a>
                                </div>
                            </div>
                        </li>
                        <li>
                            <a href="#" data-toggle="dropdown" role="button" aria-expanded="false"
                                class="nav-link dropdown-toggle">
                                <div class="spinner-4">{{ Auth::user()->nom ?? 'Utilisateur' }}<span class="caret"
                                        style="margin-left: 5px"></span></div>
                            </a>
                            <ul class="dropdown-menu triger-zoomIn-dp" role="menu">
                                <li><a href="#"><i class="icon-user"
                                            style="font-size: 17px;margin-right:6px"></i><span
                                            style="font-size: 15px">Profil</span></a></li>
                                <li>
                                    @if (session('module_id'))
                                        <a href="{{ route('logout_module', ['id' => session('module_id')]) }}"
                                            onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                            <i class="icon-sign-out" style="font-size: 17px;margin-right:6px"></i>
                                            <span style="font-size: 15px">Déconnexion</span>
                                        </a>

                                        <form id="logout-form"
                                            action="{{ route('logout_module', ['id' => session('module_id')]) }}"
                                            method="POST" style="display: none;">
                                            @csrf
                                        </form>
                                    @else
                                        <a href="{{ route('logout') }}"
                                            onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                            <i class="icon-sign-out" style="font-size: 17px;margin-right:6px"></i>
                                            <span style="font-size: 15px">Déconnexion</span>
                                        </a>

                                        <form id="logout-form" action="{{ route('logout') }}" method="POST"
                                            style="display: none;">
                                            @csrf
                                        </form>
                                    @endif



                                </li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- End Header Top Area -->

<div class="main-menu-area mg-tb-40">
    <div class="container">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <ul class="nav nav-tabs notika-menu-wrap menu-it-icon-pro">

                    <li class="{{ request()->routeIs('dashboard') ? 'active' : '' }}"
                        style="{{ request()->routeIs('dashboard')
                            ? 'box-shadow: 0 1px 3px ; transition: all 0.3s ease-in-out; border-radius: 2px;margin-right:5px;background-color:#0384ca87'
                            : 'margin-right:5px;cursor: pointer;background-color:white;border-radius: 2px' }}">
                        <a href="{{ route('dashboard') }}"
                            style="{{ request()->routeIs('dashboard')
                                ? 'font-size: 17px;cursor: pointer;background-color:transparent;color:aliceblue'
                                : 'font-size: 17px;cursor: pointer;' }}">
                            <i class="icon-library"></i> Accueil
                        </a>
                    </li>
                    <li class="{{ request()->routeIs('liste_presence', 'Suivi_profil', 'sortie_intermediaire', 'liste_employer') ? 'active' : '' }}"
                        style="{{ request()->routeIs('liste_presence', 'Suivi_profil', 'sortie_intermediaire', 'liste_employer')
                            ? 'box-shadow: 0 1px 3px ; transition: all 0.3s ease-in-out; border-radius: 2px;margin-right:5px;background-color:#0384ca87'
                            : 'margin-right:5px;cursor: pointer;background-color:white;border-radius: 2px' }}">
                        <a data-toggle="tab" href="#Personnel"
                            style="{{ request()->routeIs('liste_presence', 'Suivi_profil', 'sortie_intermediaire', 'liste_employer')
                                ? 'font-size: 17px;cursor: pointer;background-color:transparent;color:aliceblue'
                                : 'font-size: 17px;cursor: pointer;' }}">
                            <i class="icon-groups"></i> Gestion du Personnel
                        </a>
                    </li>
                    @if (Auth::user()->role_user === 'Admin')
                        <li class="dropdown-trig-sgn"
                            style="{{ request()->routeIs('liste_entreprise', 'liste_employer')
                                ? 'box-shadow: 0 1px 3px; transition: all 0.3s ease-in-out; border-radius: 2px;margin-right:5px;background-color:#0384ca87'
                                : 'margin-right:5px; background-color:white;border-radius: 2px' }}">
                            <a href="#" class="dropdown-toggle triger-zoomIn" data-toggle="dropdown"
                                role="button" aria-expanded="false" style="font-size: 17px;cursor: pointer;">
                                <i class="icon-cogs"
                                    style="{{ request()->routeIs('liste_employer')
                                        ? 'font-size: 17px;cursor: pointer;background-color:transparent;color:aliceblue'
                                        : 'font-size: 17px;cursor: pointer;' }}"></i>
                                Paramètres <span class="caret"></span>
                            </a>
                            <ul class="dropdown-menu triger-zoomIn-dp" role="menu">
                                <li
                                    style="{{ request()->routeIs('liste_entreprise') ? 'background-color: #0384ca87;color:white' : '' }}">
                                    <a href="{{ route('liste_entreprise') }}"><i class="icon-database"
                                            style="font-size: 17px;cursor: pointer;"></i>Gestion des entréprises</a>
                                </li>
                                <li
                                    style="{{ request()->routeIs('liste_employer') ? 'background-color: #0384ca87;color:white' : '' }}">
                                    <a href="{{ route('liste_employer') }}"><i class="icon-users"
                                            style="font-size: 15px;margin-right:6px"></i>Gestion des utilisateurs</a>
                                </li>
                                <li
                                    style="{{ request()->routeIs('ModuleAdmin') ? 'background-color: #0384ca87;color:white' : '' }}">
                                    <a href="{{ route('ModuleAdmin') }}"><i class="icon-users"
                                            style="font-size: 15px;margin-right:6px"></i>Gestion des modules</a>
                                </li>
                            </ul>
                        </li>
                    @endif
                </ul>
                <div class="tab-content custom-menu-content" style="margin-bottom: 10px;margin-top: 5px">
                    <div id="Personnel"
                        class="{{ request()->routeIs('liste_presence', 'Suivi_profil', 'sortie_intermediaire', 'liste_employer') ? 'tab-pane in active notika-tab-menu-bg animated flipInX' : 'tab-pane notika-tab-menu-bg animated flipInX' }}">
                        <ul class="notika-main-menu-dropdown">
                            @if (Auth::user()->role_user !== 'Admin')
                                <li
                                    style="{{ request()->routeIs('liste_employer') ? 'background-color: #0384ca87;color:white' : '' }}">
                                    <a href="{{ route('liste_employer') }}"
                                        style="{{ request()->routeIs('liste_employer') ? 'color:white' : '' }}"><i
                                            class="icon-users" style="margin-right: 5px"></i>Gestion des
                                        utilisateurs</a>
                                </li>
                            @endif
                            <li><a href="#"><i class="icon-address-book" style="margin-right: 5px"></i>
                                    Contrats de travail</a>
                            </li>
                            <li
                                style="{{ request()->routeIs('liste_presence', 'Suivi_profil') ? 'background-color: #0384ca87;color:white' : '' }}">
                                <a href="{{ route('liste_presence') }}"
                                    style="{{ request()->routeIs('liste_presence', 'Suivi_profil') ? 'color:white' : '' }}">
                                    <i class="icon-clock2"></i> Suivi des absences et congés</a>
                            </li>
                            <li
                                style="{{ request()->routeIs('sortie_intermediaire') ? 'background-color: #0384ca87;color:white' : '' }}">
                                <a href="{{ route('sortie_intermediaire') }}"
                                    style="{{ request()->routeIs('sortie_intermediaire') ? 'color:white' : '' }}"> <i
                                        class="icon-hour-glass"></i> Sorties
                                    intermédiaires</a>
                            </li>
                            <li><a href="#"><i class="icon-pie-chart"
                                        style="margin-right: 5px"></i>Organigramme</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
