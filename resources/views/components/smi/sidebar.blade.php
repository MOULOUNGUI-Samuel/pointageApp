<div class="left-sidebar-pro">
    @php
        $dossier_info = \App\Helpers\DateHelper::dossier_info();

    @endphp
    <nav id="sidebar" class="">
        <div class="sidebar-header">
            <a href="{{ route('yodirh.dashboard') }}">
                <img class="main-logo" src="{{ asset('storage/' . $module_logo) }}" alt="" width="100" />
            </a>
            <strong><img src="{{ asset('storage/' . $module_logo) }}" alt="" /></strong>
        </div>
        <div class="left-custom-menu-adp-wrap comment-scrollbar">
            <nav class="sidebar-nav left-sidebar-menu-pro">
                <ul class="metismenu" id="menu1">
                    <li>
                        <a href="{{ route('yodirh.dashboard') }}"
                            class="{{ request()->routeIs('yodirh.dashboard') ? 'bg-primary' : '' }}">
                            <i class="fa big-icon fa-home icon-wrap"></i>
                            <span class="mini-click-non">Tableau de bord</span>
                        </a>
                    </li>

                    {{-- Gouvernance & Pilotage --}}
                    <li>
                        <a class="has-arrow" href="#" aria-expanded="false">
                            <i class="fa fa-tasks icon-wrap"></i>
                            <span class="mini-click-non">Gouvernance & Pilotage</span>
                        </a>
                        <ul class="submenu-angle" aria-expanded="false">
                            <li><a href="#dashboard"><i class="fa fa-bar-chart sub-icon-mg"></i> <span
                                        class="mini-sub-pro">Tableaux de bord SMI</span></a></li>
                            <li><a href="#reviews"><i class="fa fa-refresh sub-icon-mg"></i> <span
                                        class="mini-sub-pro">Revues de direction</span></a></li>
                            <li><a href="#orgchart"><i class="fa fa-sitemap sub-icon-mg"></i> <span
                                        class="mini-sub-pro">Structure organisationnelle</span></a></li>
                            <li><a href="#comms"><i class="fa fa-bullhorn sub-icon-mg"></i> <span
                                        class="mini-sub-pro">Communication interne</span></a></li>
                        </ul>
                    </li>

                    {{-- Gestion Documentaire --}}
                    <li class="{{ request()->routeIs('document.index') ? 'active' : '' }}">
                        <a class="has-arrow {{ request()->routeIs('document.index') ? 'bg-primary' : '' }}" aria-expanded="false">
                            <i class="fa fa-folder icon-wrap"></i>
                            <span class="mini-click-non">Gestion Documentaire</span>
                        </a>
                        <ul class="submenu-angle" aria-expanded="false">
                            <li><a href="#repository"><i class="fa fa-book sub-icon-mg"></i> <span
                                        class="mini-sub-pro">Référentiel documentaire</span></a></li>
                            <li><a href="#lifecycle"><i class="fa fa-clock-o sub-icon-mg"></i> <span
                                        class="mini-sub-pro">Cycle de vie des documents</span></a></li>
                            <li><a href="#distribution"><i class="fa fa-share-alt sub-icon-mg"></i> <span
                                        class="mini-sub-pro">Diffusion et consultation</span></a></li>
                            <li><a href="{{ route('document.index') }}" class="{{ request()->routeIs('document.index') ? 'bg-primary2' : '' }}"><i class="fa fa-cloud-download sub-icon-mg"></i> <span
                                        class="mini-sub-pro">Documentation externe</span></a></li>
                        </ul>
                    </li>

                    {{-- Processus & Procédures --}}
                    <li>
                        <a class="has-arrow" href="#" aria-expanded="false">
                            <i class="fa fa-cogs icon-wrap"></i>
                            <span class="mini-click-non">Processus & Procédures</span>
                        </a>
                        <ul class="submenu-angle" aria-expanded="false">
                            <li><a href="#mapping"><i class="fa fa-map sub-icon-mg"></i> <span
                                        class="mini-sub-pro">Cartographie des processus</span></a></li>
                            <li><a
                                    href="{{ route('indexprocedure', ['nom_lien' => $dossier_info['dossier']->nom_lien ?? 'procedures']) }}"><i
                                        class="fa fa-list-alt sub-icon-mg"></i> <span class="mini-sub-pro">Procédures
                                        opérationnelles</span></a></li>
                            <li><a href="#optimization"><i class="fa fa-line-chart sub-icon-mg"></i> <span
                                        class="mini-sub-pro">Analyse et optimisation</span></a></li>
                            <li><a href="#workinstruction"><i class="fa fa-pencil-square-o sub-icon-mg"></i> <span
                                        class="mini-sub-pro">Gestion des modes <br> opératoires</span></a></li>
                        </ul>
                    </li>

                    {{-- Conformité & Audits --}}
                    <li>
                        <a class="has-arrow" href="#" aria-expanded="false">
                            <i class="fa fa-check-square-o icon-wrap"></i>
                            <span class="mini-click-non">Conformité & Audits</span>
                        </a>
                        <ul class="submenu-angle" aria-expanded="false">
                            <li><a href="#requirements"><i class="fa fa-bookmark sub-icon-mg"></i> <span
                                        class="mini-sub-pro">Exigences & Référentiels</span></a></li>
                            <li><a href="#auditplan"><i class="fa fa-calendar sub-icon-mg"></i> <span
                                        class="mini-sub-pro">Programme d'audits</span></a></li>
                            <li><a href="#auditprep"><i class="fa fa-wrench sub-icon-mg"></i> <span
                                        class="mini-sub-pro">Préparation et réalisation</span></a></li>
                            <li><a href="#auditreports"><i class="fa fa-file-text sub-icon-mg"></i> <span
                                        class="mini-sub-pro">Rapports et suivi</span></a></li>
                        </ul>
                    </li>

                    {{-- Gestion des Risques --}}
                    <li>
                        <a class="has-arrow" href="#" aria-expanded="false">
                            <i class="fa fa-exclamation-triangle icon-wrap"></i>
                            <span class="mini-click-non">Gestion des Risques</span>
                        </a>
                        <ul class="submenu-angle" aria-expanded="false">
                            <li><a href="#riskanalysis"><i class="fa fa-search sub-icon-mg"></i> <span
                                        class="mini-sub-pro">Identification & Analyse</span></a></li>
                            <li><a href="#treatment"><i class="fa fa-medkit sub-icon-mg"></i> <span
                                        class="mini-sub-pro">Plans de traitement</span></a></li>
                            <li><a href="#crisis"><i class="fa fa-fire sub-icon-mg"></i> <span
                                        class="mini-sub-pro">Gestion de crise</span></a></li>
                            <li><a href="#watch"><i class="fa fa-eye sub-icon-mg"></i> <span
                                        class="mini-sub-pro">Veille stratégique</span></a></li>
                        </ul>
                    </li>

                    {{-- Non-conformités & Actions --}}
                    <li>
                        <a class="has-arrow" href="#" aria-expanded="false">
                            <i class="fa fa-ban icon-wrap"></i>
                            <span class="mini-click-non">Non-conformités & Actions</span>
                        </a>
                        <ul class="submenu-angle" aria-expanded="false">
                            <li><a href="#nc"><i class="fa fa-warning sub-icon-mg"></i> <span
                                        class="mini-sub-pro">Non-conformités & incidents</span></a></li>
                            <li><a href="#complaints"><i class="fa fa-comments-o sub-icon-mg"></i> <span
                                        class="mini-sub-pro">Réclamations clients</span></a></li>
                            <li><a href="#actions"><i class="fa fa-cogs sub-icon-mg"></i> <span
                                        class="mini-sub-pro">Actions correctives & <br> préventives</span></a></li>
                            <li><a href="#innovation"><i class="fa fa-lightbulb-o sub-icon-mg"></i> <span
                                        class="mini-sub-pro">Innovation & amélioration</span></a></li>
                        </ul>
                    </li>

                    {{-- Compétences & Formation --}}
                    <li>
                        <a class="has-arrow" href="#" aria-expanded="false">
                            <i class="fa fa-graduation-cap icon-wrap"></i>
                            <span class="mini-click-non">Compétences & Formation</span>
                        </a>
                        <ul class="submenu-angle" aria-expanded="false">
                            <li><a href="#skillmanagement"><i class="fa fa-sliders sub-icon-mg"></i> <span
                                        class="mini-sub-pro">Gestion des compétences</span></a></li>
                            <li><a href="#trainingplan"><i class="fa fa-book sub-icon-mg"></i> <span
                                        class="mini-sub-pro">Plan de formation</span></a></li>
                            <li><a href="#awareness"><i class="fa fa-bullhorn sub-icon-mg"></i> <span
                                        class="mini-sub-pro">Sensibilisation & Communication</span></a></li>
                            <li><a href="#certification"><i class="fa fa-certificate sub-icon-mg"></i> <span
                                        class="mini-sub-pro">Évaluation & Certification</span></a></li>
                        </ul>
                    </li>
                </ul>
            </nav>
        </div>
    </nav>
</div>
