<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="logo-pro">
                <a href="#"><img class="main-logo" src="{{ asset('src2/img/logo/logo.png') }}" alt="" /></a>
            </div>
        </div>
    </div>
</div>
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
<div class="header-advance-area">
    <div class="header-top-area">
        <div class="container-fluid" style="background-color: white;margin-left: 20px">

            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="header-top-wraper">
                        <div class="row" style="margin-left: 20px">
                            <div class="col-lg-7 col-md-0 col-sm-1 col-xs-12" style="display: flex">
                                <div class="d-flex" style=";margin-top: 5px">
                                    <img src="{{ asset('storage/' . $entreprise_logo) }}" alt="Logo"
                                        class="logo-unifie mb-4">
                                </div>
                                <h3 style="margin-left: 15px;margin-top: 15px;color: #05426bce">{{ $entreprise_nom }}
                                </h3>
                                <div style="margin-left: 30px;margin-top: 20px">
                                    @if (session('module_id'))
                                        <a href="{{ route('logout_module', ['id' => session('module_id')]) }}"
                                            onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                            <i class="fa fa-sign-out text-primary"
                                                style="font-size: 20px;margin-right:6px"></i>
                                            <span class=" text-primary"
                                                style="font-size: 20px">Déconnexion</span>
                                        </a>

                                        <form id="logout-form"
                                            action="{{ route('logout_module', ['id' => session('module_id')]) }}"
                                            method="POST" style="display: none;">
                                            @csrf
                                        </form>
                                    @else
                                        <a href="{{ route('logout') }}"
                                            onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                            <i class="fa fa-sign-out text-primary"
                                                style="font-size: 20px;margin-right:6px"></i>
                                            <span style="font-size: 20px"
                                                class=" text-primary">Déconnexion</span>
                                        </a>

                                        <form id="logout-form" action="{{ route('logout') }}"
                                            method="POST" style="display: none;">
                                            @csrf
                                        </form>
                                    @endif
                                </div>
                            </div>
                            {{-- <div class="col-lg- col-md-7 col-sm-6 col-xs-12"> --}}
                            {{-- <div class="header-top-menu tabl-d-n">
                                    <ul class="nav navbar-nav mai-top-nav">
                                        <li class="nav-item"><a href="#" class="nav-link"
                                                style="color: #05436b">Home</a>
                                        </li>
                                        <li class="nav-item"><a href="#" class="nav-link"
                                                style="color: #05436b">About</a>
                                        </li>
                                        <li class="nav-item"><a href="#" class="nav-link"
                                                style="color: #05436b">Services</a>
                                        </li>
                                        <li class="nav-item"><a href="#" class="nav-link"
                                                style="color: #05436b">Support</a>
                                        </li>
                                    </ul>
                                </div> --}}
                            {{-- </div> --}}
                            <div class="col-lg-5 col-md-5 col-sm-12 col-xs-12">
                                <div class="header-right-info">
                                    <ul class="nav navbar-nav mai-top-nav header-right-menu">
                                        <li class="nav-item">
                                            <a href="#" data-toggle="dropdown" role="button"
                                                aria-expanded="false" class="nav-link dropdown-toggle">
                                                <i class="fa fa-user adminpro-user-rounded header-riht-inf"
                                                    aria-hidden="true" style="color: #05436b"></i>
                                                <span class="admin-name"
                                                    style="color: #05436b">{{ Auth::user()->nom ?? 'Utilisateur' }}</span>
                                                <i class="fa fa-angle-down adminpro-icon adminpro-down-arrow"></i>
                                            </a>
                                            <ul role="menu"
                                                class="dropdown-header-top author-log dropdown-menu animated zoomIn"
                                                style="background-color: white;border:1px solid #05436b;border-radius:5px">

                                                <li>
                                                    @if (session('module_id'))
                                                        <a href="{{ route('logout_module', ['id' => session('module_id')]) }}"
                                                            onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                                            <i class="fa fa-sign-out text-primary"
                                                                style="font-size: 17px;margin-right:6px"></i>
                                                            <span class=" text-primary"
                                                                style="font-size: 15px">Déconnexion</span>
                                                        </a>

                                                        <form id="logout-form"
                                                            action="{{ route('logout_module', ['id' => session('module_id')]) }}"
                                                            method="POST" style="display: none;">
                                                            @csrf
                                                        </form>
                                                    @else
                                                        <a href="{{ route('logout') }}"
                                                            onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                                            <i class="fa fa-sign-out text-primary"
                                                                style="font-size: 17px;margin-right:6px"></i>
                                                            <span style="font-size: 15px"
                                                                class=" text-primary">Déconnexion</span>
                                                        </a>

                                                        <form id="logout-form" action="{{ route('logout') }}"
                                                            method="POST" style="display: none;">
                                                            @csrf
                                                        </form>
                                                    @endif
                                                </li>

                                            </ul>
                                        </li>
                                        <li class="nav-item nav-setting-open"><a href="#" data-toggle="dropdown"
                                                role="button" aria-expanded="false" class="nav-link dropdown-toggle"><i
                                                    class=" fa fa-th-large" style="color: #05436b"></i></a>

                                            <div role="menu"
                                                class="admintab-wrap menu-setting-wrap menu-setting-wrap-bg dropdown-menu animated zoomIn"
                                                style="background-color: white;border:1px solid #05436b;border-radius:5px">
                                                <div class="tab-content custom-bdr-nt">
                                                    <div id="Notes" class="tab-pane fade in active">
                                                        <div class="notes-area-wrap">
                                                            <div class="note-heading-indicate">
                                                                <h2 class="text-primary"><i class="fa fa-tasks"></i>
                                                                    Liste des modules NedCore.
                                                                </h2>
                                                                <p class="text-primary">Nombre de modules disponibles :
                                                                    {{ count($mesModules['modules']) }}.</p>
                                                            </div>
                                                            <div class="notes-list-area notes-menu-scrollbar">
                                                                <ul class="notes-menu-list"
                                                                    style="max-height: 1000px; overflow-y: auto;">
                                                                    @foreach ($mesModules['modules'] as $module)
                                                                        <li
                                                                            style="margin-bottom: 10px;margin-top:10px;margin-left:4px;">
                                                                            <a
                                                                                href="{{ route('dashboard', $module->id) }}">
                                                                                <div class="notes-list-flow">
                                                                                    <div class="notes-img"
                                                                                        style="box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);border:1px solid #05436b;border-radius:5px; transition: transform 0.3s ease;">
                                                                                        <img src="{{ asset('storage/' . $module->logo) }}"
                                                                                            alt="" />
                                                                                    </div>
                                                                                    <style>
                                                                                        .notes-img:hover {
                                                                                            transform: scale(1.1);
                                                                                        }
                                                                                    </style>
                                                                                </div>
                                                                            </a>
                                                                        </li>
                                                                    @endforeach
                                                                </ul>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </li>
                                        
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Mobile Menu start -->
    <div class="mobile-menu-area">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="mobile-menu">
                        <nav id="dropdown">
                            <ul class="mobile-menu-nav">
                                <li><a data-toggle="collapse" data-target="#Charts" href="#">Home <span
                                            class="admin-project-icon adminpro-icon adminpro-down-arrow"></span></a>
                                    <ul class="collapse dropdown-header-top">
                                        <li><a href="index.html">Dashboard v.1</a></li>
                                        <li><a href="index-1.html">Dashboard v.2</a></li>
                                        <li><a href="index-3.html">Dashboard v.3</a></li>
                                        <li><a href="product-list.html">Product List</a></li>
                                        <li><a href="product-edit.html">Product Edit</a></li>
                                        <li><a href="product-detail.html">Product Detail</a></li>
                                        <li><a href="product-cart.html">Product Cart</a></li>
                                        <li><a href="product-payment.html">Product Payment</a></li>
                                        <li><a href="analytics.html">Analytics</a></li>
                                        <li><a href="widgets.html">Widgets</a></li>
                                    </ul>
                                </li>
                                <li><a data-toggle="collapse" data-target="#demo" href="#">Mailbox <span
                                            class="admin-project-icon adminpro-icon adminpro-down-arrow"></span></a>
                                    <ul id="demo" class="collapse dropdown-header-top">
                                        <li><a href="mailbox.html">Inbox</a>
                                        </li>
                                        <li><a href="mailbox-view.html">View Mail</a>
                                        </li>
                                        <li><a href="mailbox-compose.html">Compose Mail</a>
                                        </li>
                                    </ul>
                                </li>
                                <li><a data-toggle="collapse" data-target="#others" href="#">Miscellaneous
                                        <span class="admin-project-icon adminpro-icon adminpro-down-arrow"></span></a>
                                    <ul id="others" class="collapse dropdown-header-top">
                                        <li><a href="file-manager.html">File Manager</a></li>
                                        <li><a href="contacts.html">Contacts Client</a></li>
                                        <li><a href="projects.html">Project</a></li>
                                        <li><a href="project-details.html">Project Details</a></li>
                                        <li><a href="blog.html">Blog</a></li>
                                        <li><a href="blog-details.html">Blog Details</a></li>
                                        <li><a href="404.html">404 Page</a></li>
                                        <li><a href="500.html">500 Page</a></li>
                                    </ul>
                                </li>
                                <li><a data-toggle="collapse" data-target="#Miscellaneousmob"
                                        href="#">Interface
                                        <span class="admin-project-icon adminpro-icon adminpro-down-arrow"></span></a>
                                    <ul id="Miscellaneousmob" class="collapse dropdown-header-top">
                                        <li><a href="google-map.html">Google Map</a>
                                        </li>
                                        <li><a href="data-maps.html">Data Maps</a>
                                        </li>
                                        <li><a href="pdf-viewer.html">Pdf Viewer</a>
                                        </li>
                                        <li><a href="x-editable.html">X-Editable</a>
                                        </li>
                                        <li><a href="code-editor.html">Code Editor</a>
                                        </li>
                                        <li><a href="tree-view.html">Tree View</a>
                                        </li>
                                        <li><a href="preloader.html">Preloader</a>
                                        </li>
                                        <li><a href="images-cropper.html">Images Cropper</a>
                                        </li>
                                    </ul>
                                </li>
                                <li><a data-toggle="collapse" data-target="#Chartsmob" href="#">Charts <span
                                            class="admin-project-icon adminpro-icon adminpro-down-arrow"></span></a>
                                    <ul id="Chartsmob" class="collapse dropdown-header-top">
                                        <li><a href="bar-charts.html">Bar Charts</a>
                                        </li>
                                        <li><a href="line-charts.html">Line Charts</a>
                                        </li>
                                        <li><a href="area-charts.html">Area Charts</a>
                                        </li>
                                        <li><a href="rounded-chart.html">Rounded Charts</a>
                                        </li>
                                        <li><a href="c3.html">C3 Charts</a>
                                        </li>
                                        <li><a href="sparkline.html">Sparkline Charts</a>
                                        </li>
                                        <li><a href="peity.html">Peity Charts</a>
                                        </li>
                                    </ul>
                                </li>
                                <li><a data-toggle="collapse" data-target="#Tablesmob" href="#">Tables <span
                                            class="admin-project-icon adminpro-icon adminpro-down-arrow"></span></a>
                                    <ul id="Tablesmob" class="collapse dropdown-header-top">
                                        <li><a href="static-table.html">Static Table</a>
                                        </li>
                                        <li><a href="data-table.html">Data Table</a>
                                        </li>
                                    </ul>
                                </li>
                                <li><a data-toggle="collapse" data-target="#formsmob" href="#">Forms <span
                                            class="admin-project-icon adminpro-icon adminpro-down-arrow"></span></a>
                                    <ul id="formsmob" class="collapse dropdown-header-top">
                                        <li><a href="basic-form-element.html">Basic Form Elements</a>
                                        </li>
                                        <li><a href="advance-form-element.html">Advanced Form Elements</a>
                                        </li>
                                        <li><a href="password-meter.html">Password Meter</a>
                                        </li>
                                        <li><a href="multi-upload.html">Multi Upload</a>
                                        </li>
                                        <li><a href="tinymc.html">Text Editor</a>
                                        </li>
                                        <li><a href="dual-list-box.html">Dual List Box</a>
                                        </li>
                                    </ul>
                                </li>
                                <li><a data-toggle="collapse" data-target="#Appviewsmob" href="#">App views
                                        <span class="admin-project-icon adminpro-icon adminpro-down-arrow"></span></a>
                                    <ul id="Appviewsmob" class="collapse dropdown-header-top">
                                        <li><a href="basic-form-element.html">Basic Form Elements</a>
                                        </li>
                                        <li><a href="advance-form-element.html">Advanced Form Elements</a>
                                        </li>
                                        <li><a href="password-meter.html">Password Meter</a>
                                        </li>
                                        <li><a href="multi-upload.html">Multi Upload</a>
                                        </li>
                                        <li><a href="tinymc.html">Text Editor</a>
                                        </li>
                                        <li><a href="dual-list-box.html">Dual List Box</a>
                                        </li>
                                    </ul>
                                </li>
                                <li><a data-toggle="collapse" data-target="#Pagemob" href="#">Pages <span
                                            class="admin-project-icon adminpro-icon adminpro-down-arrow"></span></a>
                                    <ul id="Pagemob" class="collapse dropdown-header-top">
                                        <li><a href="login.html">Login</a>
                                        </li>
                                        <li><a href="register.html">Register</a>
                                        </li>
                                        <li><a href="lock.html">Lock</a>
                                        </li>
                                        <li><a href="password-recovery.html">Password Recovery</a>
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
    <style>
        @media (min-width: 1024px) {
            .top-section {
                margin-left: 90px;
                margin-right: 90px;
            }
        }
    </style>
    <!-- Mobile Menu end -->
    @if (request()->routeIs('dashboard'))
        <div class="breadcome-area">
            <div class="top-section container-fluid">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="breadcome-list">
                            <div class="row">
                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                                    <div class="breadcome-heading">
                                        <form role="search" class="">
                                            <input type="text" placeholder="Rechercher..." class="form-control">
                                            <a href=""><i class="fa fa-search"
                                                    style="margin-top:10px"></i></a>
                                        </form>
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                                    <ul class="breadcome-menu">
                                        <li><a href="#">Tableau de bord</a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
