<div class="sidebar shadow" id="sidebar">
    <div class="sidebar-inner slimscroll">
        <div id="sidebar-menu" class="sidebar-menu">
            <ul>
                <li class="clinicdropdown">
                    <a href="{{ route('dashboard', $module_id) }}" class="logo">
                        <img src="{{ asset('assets/img/user.jpg') }}" class="img-fluid" alt="Profile" />
                        <div class="user-names">
                            <h5>{{ Auth::user()->nom ?? 'Utilisateur' }}</h5>
                            <h6>{{ Auth::user()->fonction ?? 'fonction' }}</h6>
                        </div>
                    </a>
                </li>
            </ul>
            <ul>
                <li>
                    <h6 class="submenu-hdr">Menu Ned&Co RH</h6>
                    <ul>
                        <li>
                            <a href="{{ route('dashboard', $module_id) }}"
                                class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">
                                <i class="fa fa-home"></i><span>Tableau de bord </span>
                            </a>
                        </li>
                        <li class="submenu">
                            <a href="javascript:void(0);"
                                class="{{ request()->routeIs('yodirh.utilisateurs', 'liste_presence','modif_affiche_utilisateur','yodirh.formulaire_utilisateurs','Suivi_profil') ? 'active' : '' }}">
                                <i class="fa fa-users"></i><span>Gestion du personnel</span><span
                                    class="menu-arrow"></span></a>
                            <ul>
                                <li><a href="{{ route('yodirh.utilisateurs') }}"
                                        class="{{ request()->routeIs('yodirh.utilisateurs','modif_affiche_utilisateur','yodirh.formulaire_utilisateurs') ? 'active' : '' }}">
                                        Dossiers des employés</a></li>
                                <li><a href="#">Contrats de travail</a></li>
                                <li><a href="{{ route('liste_presence') }}"
                                        class="{{ request()->routeIs('liste_presence','Suivi_profil') ? 'active' : '' }}">Suivi de pointages</a></li>
                                        
                                {{-- <li><a href="{{ route('absenceindex') }}"
                                        class="{{ request()->routeIs('absenceindex') ? 'active' : '' }}">Demandes d'absence</a></li> --}}
                                <li><a href="#">Organigramme</a></li>
                            </ul>
                        </li>
                        {{-- <li class="submenu">
                            <a href="javascript:void(0);"><i class="fa fa-briefcase"></i><span>Recrutement</span><span
                                    class="menu-arrow"></span></a>
                            <ul>
                                <li><a href="#">Publication d'offres</a></li>
                                <li><a href="#">Gestion des candidatures</a></li>
                                <li><a href="#">Suivi des entretiens</a></li>
                                <li><a href="#">Onboarding</a></li>
                            </ul>
                        </li> --}}
                        <li>
                            <a href="{{ route('paie') }}" class="{{ request()->routeIs('paie') ? 'active' : '' }}"><i class="fa fa-credit-card"></i><span>Paie</span></a>
                        </li>
                        {{-- <li class="submenu">
                            <a href="javascript:void(0);"><i class="fa fa-graduation-cap"></i><span>Formation &
                                    Développement</span><span class="menu-arrow"></span></a>
                            <ul>
                                <li><a href="#">Plans de formation</a></li>
                                <li><a href="#">Suivi des compétences</a></li>
                                <li><a href="#">Évaluations et entretiens annuels</a></li>
                            </ul>
                        </li> --}}
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</div>
