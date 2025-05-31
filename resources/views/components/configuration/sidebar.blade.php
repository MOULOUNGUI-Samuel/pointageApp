<div class="left-sidebar-pro">
    <nav id="sidebar" class="">
        <div class="sidebar-header">
            <a href="{{ route('dashboard', $module_id) }}"><img class="main-logo"
                    src="{{ asset('storage/' . $module_logo) }}" alt="" width="100" /></a>
            <strong><img src="{{ asset('storage/' . $module_logo) }}" alt="" /></strong>
        </div>
        <div class="left-custom-menu-adp-wrap comment-scrollbar">
            <nav class="sidebar-nav left-sidebar-menu-pro">
                <ul class="metismenu" id="menu1">
                    <li>
                        <a href="{{ route('dashboard', $module_id) }}"
                            class="{{ request()->routeIs('dashboard') ? 'bg-primary' : '' }}">
                            <i class="fa big-icon fa-home icon-wrap"></i>
                            <span class="mini-click-non">Tableau de bord</span>
                        </a>

                    </li>
                    <li><a title="Gestion entreprise" href="{{ route('liste_entreprise') }}"
                            class="{{ request()->routeIs('liste_entreprise') ? 'bg-primary' : '' }}"><i
                                class="fa fa-building sub-icon-mg" aria-hidden="true"></i> <span
                                class="mini-sub-pro">Gestion des entreprises</span></a></li>
                    <li><a title="Gestion des modules" href="{{ route('ModuleAdmin') }}"
                            class="{{ request()->routeIs('ModuleAdmin') ? 'bg-primary' : '' }}"><i
                                class="fa fa-cubes sub-icon-mg" aria-hidden="true"></i> <span
                                class="mini-sub-pro">Gestion des modules</span></a></li>
                    <li><a title="Gestion des services" href="{{ route('services') }}"
                            class="{{ request()->routeIs('services') ? 'bg-primary' : '' }}"><i
                                class="fa fa-briefcase sub-icon-mg" aria-hidden="true"></i> <span
                                class="mini-sub-pro">Gestion des services</span></a></li>
                    <li><a title="Gestion des catégories professionnelles" href="{{ route('categorieprofessionel') }}"
                            class="{{ request()->routeIs('categorieprofessionel') ? 'bg-primary' : '' }}"><i
                                class="fa fa-users sub-icon-mg" aria-hidden="true"></i> <span
                                class="mini-sub-pro">Gestion des catégorie <br> professionnelle</span></a></li>

                </ul>
            </nav>
        </div>
    </nav>
</div>
