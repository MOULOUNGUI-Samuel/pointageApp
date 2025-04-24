<!-- Start Header Top Area -->
<div class="header-top-area">
    <div class="container">
        <div class="row">
            <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12">
                <div>
                    <a href="#"><img src="{{ asset('src/images/YODIPOINTE1.png') }}" alt="" width="96"
                            class="rounded" /></a>
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
                                    <a href="{{ route('logout') }}"
                                        onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                        <i class="icon-sign-out" style="font-size: 17px;margin-right:6px"></i>
                                        <span style="font-size: 15px">Déconnexion</span>
                                    </a>
                                    <form id="logout-form" action="{{ route('logout') }}" method="POST"
                                        style="display: none;">
                                        @csrf
                                    </form>
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
<!-- Mobile Menu start -->
<div class="mobile-menu-area">
    <div class="container">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="mobile-menu">
                    <nav id="dropdown">
                        <ul class="mobile-menu-nav">
                            <li><a href="#">Home</a>
                                <ul class="collapse dropdown-header-top">
                                    <li><a href="index.html">Dashboard One</a></li>
                                    <li><a href="index-2.html">Dashboard Two</a></li>
                                    <li><a href="index-3.html">Dashboard Three</a></li>
                                    <li><a href="index-4.html">Dashboard Four</a></li>
                                    <li><a href="analytics.html">Analytics</a></li>
                                    <li><a href="widgets.html">Widgets</a></li>
                                </ul>
                            </li>
                            <li><a data-toggle="collapse" data-target="#demoevent" href="#">Email</a>
                                <ul id="demoevent" class="collapse dropdown-header-top">
                                    <li><a href="inbox.html">Inbox</a></li>
                                    <li><a href="view-email.html">View Email</a></li>
                                    <li><a href="compose-email.html">Compose Email</a></li>
                                </ul>
                            </li>
                            <li><a data-toggle="collapse" data-target="#democrou" href="#">Interface</a>
                                <ul id="democrou" class="collapse dropdown-header-top">
                                    <li><a href="animations.html">Animations</a></li>
                                    <li><a href="google-map.html">Google Map</a></li>
                                    <li><a href="data-map.html">Data Maps</a></li>
                                    <li><a href="code-editor.html">Code Editor</a></li>
                                    <li><a href="image-cropper.html">Images Cropper</a></li>
                                    <li><a href="wizard.html">Wizard</a></li>
                                </ul>
                            </li>
                            <li><a data-toggle="collapse" data-target="#demolibra" href="#">Charts</a>
                                <ul id="demolibra" class="collapse dropdown-header-top">
                                    <li><a href="flot-charts.html">Flot Charts</a></li>
                                    <li><a href="bar-charts.html">Bar Charts</a></li>
                                    <li><a href="line-charts.html">Line Charts</a></li>
                                    <li><a href="area-charts.html">Area Charts</a></li>
                                </ul>
                            </li>
                            <li><a data-toggle="collapse" data-target="#demodepart" href="#">Tables</a>
                                <ul id="demodepart" class="collapse dropdown-header-top">
                                    <li><a href="normal-table.html">Normal Table</a></li>
                                    <li><a href="data-table.html">Data Table</a></li>
                                </ul>
                            </li>
                            <li><a data-toggle="collapse" data-target="#demo" href="#">Forms</a>
                                <ul id="demo" class="collapse dropdown-header-top">
                                    <li><a href="form-elements.html">Form Elements</a></li>
                                    <li><a href="form-components.html">Form Components</a></li>
                                    <li><a href="form-examples.html">Form Examples</a></li>
                                </ul>
                            </li>
                            <li><a data-toggle="collapse" data-target="#Miscellaneousmob" href="#">App
                                    views</a>
                                <ul id="Miscellaneousmob" class="collapse dropdown-header-top">
                                    <li><a href="notification.html">Notifications</a>
                                    </li>
                                    <li><a href="alert.html">Alerts</a>
                                    </li>
                                    <li><a href="modals.html">Modals</a>
                                    </li>
                                    <li><a href="buttons.html">Buttons</a>
                                    </li>
                                    <li><a href="tabs.html">Tabs</a>
                                    </li>
                                    <li><a href="accordion.html">Accordion</a>
                                    </li>
                                    <li><a href="dialog.html">Dialogs</a>
                                    </li>
                                    <li><a href="popovers.html">Popovers</a>
                                    </li>
                                    <li><a href="tooltips.html">Tooltips</a>
                                    </li>
                                    <li><a href="dropdown.html">Dropdowns</a>
                                    </li>
                                </ul>
                            </li>
                            <li><a data-toggle="collapse" data-target="#Pagemob" href="#">Pages</a>
                                <ul id="Pagemob" class="collapse dropdown-header-top">
                                    <li><a href="contact.html">Contact</a>
                                    </li>
                                    <li><a href="invoice.html">Invoice</a>
                                    </li>
                                    <li><a href="typography.html">Typography</a>
                                    </li>
                                    <li><a href="color.html">Color</a>
                                    </li>
                                    <li><a href="login-register.html">Login Register</a>
                                    </li>
                                    <li><a href="404.html">404 Page</a>
                                    </li>
                                </ul>
                            </li>
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="main-menu-area mg-tb-40">
    <div class="container">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <ul class="nav nav-tabs notika-menu-wrap menu-it-icon-pro">

                    <li class="active" style="cursor: pointer;">
                        <a href="{{ route('dashboard') }}">
                            <i class="icon-library" style="font-size: 19px;cursor: pointer;"></i> Accueil
                        </a>
                    </li>

                    <li class="dropdown-trig-sgn">
                        <a href="#" class="dropdown-toggle triger-zoomIn" data-toggle="dropdown"
                            role="button" aria-expanded="false">
                            <i class="icon-clock2" style="font-size: 19px"></i> Suivi des présences <span
                                class="caret"></span>
                        </a>
                        <ul class="dropdown-menu triger-zoomIn-dp" role="menu">
                            <li><a href="{{ route('liste_presence') }}"><i class="icon-list"
                                        style="font-size: 13px;margin-right:6px"></i>Liste des présences</a></li>
                            <li><a href="#"><i class="icon-clock2"
                                        style="font-size: 15px;margin-right:6px"></i>Heures supplémentaires</a></li>
                        </ul>
                    </li>

                    <li>
                        <a href="{{ route('sortie_intermediaire') }}">
                            <i class="icon-hour-glass" style="font-size: 19px;cursor: pointer;"></i> Sorties
                            intermédiaires
                        </a>
                    </li>
                    @if (Auth::user()->role_user === 'Admin')
                        <li class="dropdown-trig-sgn">
                            <a href="#" class="dropdown-toggle triger-zoomIn" data-toggle="dropdown"
                                role="button" aria-expanded="false">
                                <i class="icon-cogs" style="font-size: 19px;cursor: pointer;"></i> Paramètres <span
                                    class="caret"></span>
                            </a>
                            <ul class="dropdown-menu triger-zoomIn-dp" role="menu">
                                <li><a href="{{ route('liste_entreprise') }}"><i class="icon-database"
                                            style="font-size: 13px;margin-right:6px"></i>Gestion des entréprises</a>
                                </li>
                                <li><a href="{{ route('liste_employer') }}"><i class="icon-users"
                                            style="font-size: 15px;margin-right:6px"></i>Gestion des utilisateurs</a>
                                </li>
                            </ul>
                        </li>
                    @else
                        <li>
                            <a href="{{ route('liste_employer') }}"><i class="icon-users"
                                    style="font-size: 15px;margin-right:6px"></i>Gestion des utilisateurs</a>
                        </li>
                    @endif
                </ul>
            </div>
        </div>
    </div>
</div>
