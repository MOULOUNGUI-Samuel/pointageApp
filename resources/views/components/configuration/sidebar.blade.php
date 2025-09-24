<div class="sidebar" id="sidebar">
    <div class="sidebar-inner slimscroll">
        <div id="sidebar-menu" class="sidebar-menu">
<div class="sidebar-header">
            <a href="{{ route('dashboard', $module_id) }}"><img class="main-logo"
                    src="{{ asset('storage/' . $module_logo) }}" alt="" width="100" /></a>
            <strong><img src="{{ asset('storage/' . $module_logo) }}" alt="" /></strong>
        </div>
            {{-- BLOC 1 : INFORMATIONS DE L'ADMINISTRATEUR CONNECTÉ --}}
            <ul>
                <li class="clinicdropdown">
                    <a href="#"> {{-- Lien vers le profil admin --}}
                        <img src="{{ asset('assets/img/user.jpg') }}" class="img-fluid" alt="Profile" />
                        <div class="user-names">
                            {{-- On utilise le guard par défaut pour l'admin --}}
                            <h5>{{ Auth::user()->nom ?? 'Admin' }}</h5>
                            <h6>{{ Auth::user()->fonction ?? 'Administrateur' }}</h6>
                        </div>
                    </a>
                </li>
            </ul>

            {{-- BLOC 2 : MENU PRINCIPAL DE L'ADMINISTRATION --}}
            <ul>
                <li>
                    {{-- Titre de la section --}}
                    <h6 class="submenu-hdr">Administration</h6>
                    <ul>
                        {{-- Lien 1 : Tableau de bord --}}
                        <li>
                            <a href="{{ route('dashboard', $module_id) }}" class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">
                                <i class="fa fa-home"></i><span>Tableau de bord</span>
                            </a>
                        </li>

                        {{-- Lien 2 : Gestion des entreprises --}}
                        <li>
                            <a href="{{ route('liste_entreprise') }}" class="{{ request()->routeIs('liste_entreprise') ? 'active' : '' }}">
                                <i class="fa fa-building"></i><span>Gestion des entreprises</span>
                            </a>
                        </li>

                        {{-- Lien 3 : Gestion des modules --}}
                        <li>
                            <a href="{{ route('ModuleAdmin') }}" class="{{ request()->routeIs('ModuleAdmin') ? 'active' : '' }}">
                                <i class="fa fa-cubes"></i><span>Gestion des modules</span>
                            </a>
                        </li>
                        
                        {{-- Lien 4 : Gestion des services --}}
                        <li>
                            <a href="{{ route('services') }}" class="{{ request()->routeIs('services') ? 'active' : '' }}">
                                <i class="fa fa-briefcase"></i><span>Gestion des services</span>
                            </a>
                        </li>

                        {{-- Lien 5 : Gestion des catégories professionnelles --}}
                        <li>
                            <a href="{{ route('categorieprofessionel') }}" class="{{ request()->routeIs('categorieprofessionel') ? 'active' : '' }}">
                                <i class="fa fa-users"></i><span>Catégories Professionnelles</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('entreprise-audit') }}" class="{{ request()->routeIs('entreprise-audit') ? 'active' : '' }}">
                                <i class="fa fa-shield"></i>
                                <span>Config audit entreprise</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('config-audit') }}" class="{{ request()->routeIs('config-audit') ? 'active' : '' }}">
                                <i class="fa fa-shield"></i>
                                <span>Configuration audit</span>
                            </a>
                        </li>

                        {{-- Lien 6 : Gestion des permissions --}}
                        <li>
                            <a href="{{ route('paramettre') }}" class="{{ request()->routeIs('paramettre') ? 'active' : '' }}">
                                <i class="fa fa-key"></i><span>Gestion des permissions</span>
                            </a>
                        </li>
                        
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</div>

