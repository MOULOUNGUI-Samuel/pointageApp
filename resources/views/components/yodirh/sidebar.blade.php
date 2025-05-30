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
                    <li
                        class="{{ request()->routeIs('yodirh.utilisateurs', 'liste_presence', 'Suivi_profil', 'yodirh.formulaire_utilisateurs') ? 'active' : '' }}">
                        <a class="has-arrow  {{ request()->routeIs('yodirh.utilisateurs', 'liste_presence', 'Suivi_profil', 'yodirh.formulaire_utilisateurs') ? 'bg-primary' : '' }}"
                            href="#" aria-expanded="false"><i class="fa big-icon fa-users icon-wrap"></i> <span
                                class="mini-click-non">Gestion du
                                personnel...</span></a>
                        <ul class="submenu-angle" aria-expanded="false">
                            <li><a title="Gestion des employés" href="{{ route('yodirh.utilisateurs') }}"
                                    class="{{ request()->routeIs('yodirh.utilisateurs', 'yodirh.formulaire_utilisateurs') ? 'bg-primary2' : '' }}"><i
                                        class="fa fa-user sub-icon-mg" aria-hidden="true"></i> <span
                                        class="mini-sub-pro">Gestion des
                                        employés</span></a></li>
                            <li><a title="Contrats de travail" href="#"><i class="fa fa-file-text sub-icon-mg"
                                        aria-hidden="true"></i> <span class="mini-sub-pro">Contrats de
                                        travail</span></a></li>
                            <li><a title="Suivi des absences et congés" href="{{ route('liste_presence') }}"
                                    class="{{ request()->routeIs('liste_presence', 'Suivi_profil') ? 'bg-primary2' : '' }}"><i
                                        class="fa fa-calendar sub-icon-mg" aria-hidden="true"></i> <span
                                        class="mini-sub-pro">Suivi des absences <br> et
                                        congés</span></a></li>
                            <li><a title="Organigramme" href="#"><i class="fa fa-sitemap sub-icon-mg"
                                        aria-hidden="true"></i> <span class="mini-sub-pro">Organigramme</span></a></li>
                        </ul>
                    </li>
                    <li>
                        <a class="has-arrow" href="#" aria-expanded="false"><i
                                class="fa big-icon fa-briefcase icon-wrap"></i> <span
                                class="mini-click-non">Recrutement</span></a>
                        <ul class="submenu-angle" aria-expanded="false">
                            <li><a title="Publication d'offres" href="#"><i class="fa fa-bullhorn sub-icon-mg"
                                        aria-hidden="true"></i> <span class="mini-sub-pro">Publication
                                        d'offres</span></a></li>
                            <li><a title="Gestion des candidatures" href="#"><i class="fa fa-file sub-icon-mg"
                                        aria-hidden="true"></i> <span class="mini-sub-pro">Gestion des
                                        candidatures</span></a></li>
                            <li><a title="Suivi des entretiens" href="#"><i class="fa fa-comments sub-icon-mg"
                                        aria-hidden="true"></i> <span class="mini-sub-pro">Suivi des
                                        entretiens</span></a></li>
                            <li><a title="Onboarding" href="#"><i class="fa fa-handshake-o sub-icon-mg"
                                        aria-hidden="true"></i> <span class="mini-sub-pro">Onboarding</span></a></li>
                        </ul>
                    </li>
                    <li>
                        <a class="has-arrow" href="#" aria-expanded="false"><i
                                class="fa big-icon fa-money icon-wrap"></i> <span class="mini-click-non">Paie</span></a>
                        <ul class="submenu-angle" aria-expanded="false">
                            <li><a title="Calcul et édition des bulletins" href="#"><i
                                        class="fa fa-calculator sub-icon-mg" aria-hidden="true"></i> <span
                                        class="mini-sub-pro">Calcul et édition des <br> bulletins</span></a></li>
                            <li><a title="Déclarations sociales" href="#"><i class="fa fa-file-text sub-icon-mg"
                                        aria-hidden="true"></i> <span class="mini-sub-pro">Déclarations
                                        sociales</span></a></li>
                            <li><a title="Gestion des avantages" href="#"><i class="fa fa-gift sub-icon-mg"
                                        aria-hidden="true"></i> <span class="mini-sub-pro">Gestion des
                                        avantages</span></a></li>
                        </ul>
                    </li>
                    <li>
                        <a class="has-arrow" href="#" aria-expanded="false"><i
                                class="fa big-icon fa-graduation-cap icon-wrap"></i> <span
                                class="mini-click-non">Formation et Dév...</span></a>
                        <ul class="submenu-angle" aria-expanded="false">
                            <li><a title="Plans de formation" href="#"><i class="fa fa-book sub-icon-mg"
                                        aria-hidden="true"></i> <span class="mini-sub-pro">Plans de
                                        formation</span></a></li>
                            <li><a title="Suivi des compétences" href="#"><i
                                        class="fa fa-line-chart sub-icon-mg" aria-hidden="true"></i> <span
                                        class="mini-sub-pro">Suivi des compétences</span></a></li>
                            <li><a title="Évaluations et entretiens annuels" href="#"><i
                                        class="fa fa-check-circle sub-icon-mg" aria-hidden="true"></i> <span
                                        class="mini-sub-pro">Évaluations et entretiens <br> annuels</span></a></li>
                        </ul>
                    </li>                                 
                </ul>
            </nav>
        </div>
    </nav>
</div>
