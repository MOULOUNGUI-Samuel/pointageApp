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

            {{-- BLOC 2 : MENU PRINCIPAL QUALITÉ (SMI) --}}
            <ul>
                <li>
                    <h6 class="submenu-hdr">Menu Qualité (SMI)</h6>
                    <ul>
                        {{-- Lien simple : Tableau de bord --}}
                        <li>
                            <a href="{{ route('dashboard', $module_id) }}" class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">
                                <i class="fa fa-home"></i><span>Tableau de bord</span>
                            </a>
                        </li>

                        {{-- Menu 1 : Gouvernance & Pilotage (avec sous-menu) --}}
                        <li class="submenu">
                            <a href="javascript:void(0);">
                                <i class="fa fa-tasks"></i><span>Gouvernance & Pilotage</span><span class="menu-arrow"></span>
                            </a>
                            <ul>
                                <li><a href="#dashboard">Tableaux de bord SMI</a></li>
                                <li><a href="#reviews">Revues de direction</a></li>
                                <li><a href="#orgchart">Structure organisationnelle</a></li>
                                <li><a href="#comms">Communication interne</a></li>
                            </ul>
                        </li>

                        {{-- Menu 2 : Gestion Documentaire (avec sous-menu) --}}
                        <li class="submenu">
                             <a href="javascript:void(0);">
                                <i class="fa fa-folder"></i><span>Gestion Documentaire</span><span class="menu-arrow"></span>
                            </a>
                            <ul>
                                <li><a href="#repository">Référentiel documentaire</a></li>
                                <li><a href="#lifecycle">Cycle de vie des documents</a></li>
                                <li><a href="#distribution">Diffusion et consultation</a></li>
                                <li><a href="#">Documentation externe</a></li>
                            </ul>
                        </li>

                        {{-- Menu 3 : Processus & Procédures (avec sous-menu) --}}
                        <li class="submenu">
                             <a href="javascript:void(0);" class="{{ request()->routeIs('indexprocedure') ? 'active show' : '' }}">
                                <i class="fa fa-cogs"></i><span>Processus & Procédures</span><span class="menu-arrow"></span>
                            </a>
                            <ul class="{{ request()->routeIs('indexprocedure') ? 'show' : '' }}">
                                <li><a href="#mapping">Cartographie des processus</a></li>
                                <li><a href="#" class="{{-- request()->routeIs('une_route_specifique') ? 'active' : '' --}}">Procédures opérationnelles</a></li>
                                <li><a href="#optimization">Analyse et optimisation</a></li>
                                <li><a href="#workinstruction">Gestion des modes opératoires</a></li>
                            </ul>
                        </li>

                        {{-- Menu 4 : Conformité & Audits (avec sous-menu) --}}
                        <li class="submenu">
                             <a href="javascript:void(0);">
                                <i class="fa fa-check-square-o"></i><span>Conformité & Audits</span><span class="menu-arrow"></span>
                            </a>
                            <ul>
                                <li><a href="#requirements">Exigences & Référentiels</a></li>
                                <li><a href="#auditplan">Programme d'audits</a></li>
                                <li><a href="#auditprep">Préparation et réalisation</a></li>
                                <li><a href="#auditreports">Rapports et suivi</a></li>
                            </ul>
                        </li>

                        {{-- Menu 5 : Gestion des Risques (avec sous-menu) --}}
                        <li class="submenu">
                             <a href="javascript:void(0);">
                                <i class="fa fa-exclamation-triangle"></i><span>Gestion des Risques</span><span class="menu-arrow"></span>
                            </a>
                            <ul>
                                <li><a href="#riskanalysis">Identification & Analyse</a></li>
                                <li><a href="#treatment">Plans de traitement</a></li>
                                <li><a href="#crisis">Gestion de crise</a></li>
                                <li><a href="#watch">Veille stratégique</a></li>
                            </ul>
                        </li>
                        
                        {{-- Menu 6 : Non-conformités & Actions (avec sous-menu) --}}
                        <li class="submenu">
                             <a href="javascript:void(0);">
                                <i class="fa fa-ban"></i><span>Non-conformités & Actions</span><span class="menu-arrow"></span>
                            </a>
                            <ul>
                                <li><a href="#nc">Non-conformités & incidents</a></li>
                                <li><a href="#complaints">Réclamations clients</a></li>
                                <li><a href="#actions">Actions correctives & préventives</a></li>
                                <li><a href="#innovation">Innovation & amélioration</a></li>
                            </ul>
                        </li>

                        {{-- Menu 7 : Compétences & Formation (avec sous-menu) --}}
                        <li class="submenu">
                             <a href="javascript:void(0);">
                                <i class="fa fa-graduation-cap"></i><span>Compétences & Formation</span><span class="menu-arrow"></span>
                            </a>
                            <ul>
                                <li><a href="#skillmanagement">Gestion des compétences</a></li>
                                <li><a href="#trainingplan">Plan de formation</a></li>
                                <li><a href="#awareness">Sensibilisation & Communication</a></li>
                                <li><a href="#certification">Évaluation & Certification</a></li>
                            </ul>
                        </li>

                    </ul>
                </li>
            </ul>
        </div>
    </div>
</div>

