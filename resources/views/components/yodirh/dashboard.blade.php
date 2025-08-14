@extends('layouts.master2')
@section('content2')
    <!-- Mobile Menu end -->
    <!-- Main Menu area start-->
    <!-- Main Menu area End-->
    <!-- Start Status area -->
    <style>
        @media (min-width: 1024px) {
            .section-admin {
                margin-left: 90px;
                margin-right: 90px;
            }
        }

        @media (min-width: 1024px) {
            .product-sales-area {
                margin-left: 90px;
                margin-right: 90px;
            }
        }
    </style>

    <!-- Welcome Wrap -->
    <div class="welcome-wrap mb-4 bg-primary shadow">
        <div class="d-flex align-items-center justify-content-between flex-wrap">
            <div class="d-flex">
                <div class="d-flex align-items-center justify-content-center me-3">
                    <img src="{{ asset('storage/' . $module_logo) }}" alt="Profile"
                        style="width: 90px; height: 90px; object-fit: cover; border-radius: 12px; box-shadow: 0 5px 8px rgba(243, 239, 239, 0.508); background: #fff; border: 1px solid #e5e7eb;" />
                </div>
                <div class="mb-3">
                    <h2 class="mb-1 text-white">Bienvenue, {{ Auth::user()->prenom ?? '' }}</h2>
                    <p class="text-light">Prêt à faire avancer votre équipe aujourd'hui ?</p>
                </div>
            </div>
            <div class="d-flex align-items-center flex-wrap mb-1">
                <a href="{{ route('yodirh.utilisateurs') }}" class="btn btn-dark btn-md me-2 mb-2">Employers</a>
                <a href="{{ route('liste_presence') }}" class="btn btn-light btn-md mb-2">Suivi des absances</a>
            </div>
        </div>
    </div>
    <!-- /Welcome Wrap -->

    <div class="row">

        <div class="col-xl-3 col-sm-6 d-flex">
            <div class="card flex-fill shadow">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <span class="avatar avatar-md rounded bg-dark mb-3">
                            <i class="fa fa-users fs-16"></i>
                        </span>
                        <span class="badge text-dark border border-dark fw-normal mb-3">
                            {{ $entreprise_nom }}
                        </span>
                    </div>
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <h2 class="mb-1">{{ count($employesActifs) }}</h2>
                            <h5>Employés</h5>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-2 col-sm-6 d-flex">
            <div class="card flex-fill shadow">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <span class="avatar avatar-md rounded bg-warning mb-3">
                            <i class="fa fa-users fs-16"></i>
                        </span>
                         <span class="badge text-dark border border-dark fw-normal mb-3">
                            {{ $entreprise_nom }}
                        </span>
                    </div>
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <h2 class="mb-1">{{ count($employesNePointePas) }}</h2>
                            <h5>Ne pointe pas</h5>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-2 col-sm-6 d-flex">
            <div class="card flex-fill shadow">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <span class="avatar avatar-md rounded bg-success mb-3">
                            <i class="fa fa-user fs-16"></i>
                        </span>
                         <span class="badge text-dark border border-dark fw-normal mb-3">
                            {{ $entreprise_nom }}
                        </span>
                    </div>
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <h2 class="mb-1">{{ count($pointages_oui) }}</h2>
                            <h5>Présents</h5>
                        </div>
                        <div class="company-bar1">Ayants pointé
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-2 col-sm-6 d-flex">
            <div class="card flex-fill shadow">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <span class="avatar avatar-md rounded bg-danger mb-3">
                            <i class="fa fa-user-times fs-16"></i>
                        </span>
                         <span class="badge text-dark border border-dark fw-normal mb-3">
                            {{ $entreprise_nom }}
                        </span>
                    </div>
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <h2 class="mb-1">{{ count($users_non_existants) }}</h2>
                            <h5>Absences</h5>
                        </div>
                        <div class="company-bar1">N'ayants pas pointé
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-sm-6 d-flex">
            <div class="card flex-fill shadow">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <span class="avatar avatar-md rounded bg-primary mb-3">
                            <i class="fa fa-sign-out fs-16"></i>
                        </span>
                         <span class="badge text-dark border border-dark fw-normal mb-3">
                            {{ $entreprise_nom }}
                        </span>
                    </div>
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <h2 class="mb-1">{{ count($pointage_intermediaires) }}</h2>
                            <h5>Sorties intermediaires</h5>
                        </div>
                        <div class="company-bar1">Aujourd'hui
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- =================================================== -->

    <div class="row">

        <!-- Recent Transactions -->
        <div class="col-xxl-4 col-xl-12 d-flex">
            <div class="card flex-fill shadow">
                <div class="card-header pb-2 d-flex align-items-center justify-content-between flex-wrap">
                    <h5 class="mb-2 text-danger">Employés absents</h5>
                    <div class="input-group" style="max-width: 300px;">
                        <span class="input-group-text"><i class="ti ti-search"></i></span>
                        <input type="text" class="form-control shadow" id="search1" placeholder="Rechercher...">
                    </div>
                </div>
                <div class="card-body pb-2" id="absentTable1" style="max-height: 300px; overflow-y: auto;">
                    @if (count($users_non_existants) > 0)
                        @foreach ($users_non_existants as $absent)
                            <div class="absent-item">
                                <div class="d-sm-flex justify-content-between flex-wrap mb-1">
                                    <div class="d-flex align-items-center mb-2">
                                        <a href="javascript:void(0);" class="avatar avatar-sm border flex-shrink-0">
                                            <img src="{{ asset('src/images/user.jpg') }}" class="img-fluid w-auto h-auto"
                                                alt="img">
                                        </a>
                                        <div class="ms-2 flex-fill">
                                            {{-- Le nom est ici --}}
                                            <h6 class="fs-medium text-truncate mb-1"><a
                                                    href="javascript:void(0);">{{ $absent->nom . ' ' . $absent->prenom }}</a>
                                            </h6>
                                            {{-- La fonction est dans le span .text-info --}}
                                            <p class="fs-13 d-inline-flex align-items-center"><span
                                                    class="text-info">{{ $absent->fonction }}</span></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="d-flex flex-column justify-content-center align-items-center" style="height: 200px;">
                            Aucun employé absent trouvé aujourd'hui.
                        </div>
                    @endif
                </div>
            </div>

        </div>
        <div class="col-xxl-4 col-xl-12 d-flex">
            <div class="card flex-fill shadow">
                <div class="card-header pb-2 d-flex align-items-center justify-content-between flex-wrap">
                    <h5 class="mb-2 text-success">Employés presents</h5>
                    <div class="input-group" style="max-width: 300px;">
                        <span class="input-group-text"><i class="ti ti-search"></i></span>
                        <input type="text" class="form-control shadow" id="search2" placeholder="Rechercher..."
                            width="50">
                    </div>
                </div>
                <div class="card-body pb-2" id="absentTable2" style="max-height: 300px; overflow-y: auto;">
                    @if (count($pointages_oui) > 0)
                        @foreach ($pointages_oui as $present)
                            <div class="absent-item">
                                <div class="d-sm-flex justify-content-between flex-wrap mb-1">
                                    <div class="d-flex align-items-center mb-2">
                                        <a href="javascript:void(0);" class="avatar avatar-sm border flex-shrink-0">
                                            <img src="{{ asset('src/images/user.jpg') }}" class="img-fluid w-auto h-auto"
                                                alt="img">
                                        </a>
                                        <div class="ms-2 flex-fill">
                                            {{-- Le nom est ici --}}
                                            <h6 class="fs-medium text-truncate mb-1"><a
                                                    href="javascript:void(0);">{{ $present->user->nom . ' ' . $present->user->prenom }}</a>
                                            </h6>
                                            {{-- La fonction est dans le span .text-info --}}
                                            <p class="fs-13 d-inline-flex align-items-center"><span
                                                    class="text-info">{{ $present->user->fonction }}</span></p>
                                        </div>
                                    </div>
                                    <div class="text-sm-end mb-2">
                                        @if ($present->heure_arriver > $present->user->entreprise->heure_ouverture)
                                            <span class="badge badge-danger p-2"
                                                style="background-color:rgba(196, 12, 12, 0.877)">
                                                {{ $present->heure_arriver }}
                                            </span>
                                        @else
                                            <span class="badge badge-success p-2" style="background-color:green">
                                                {{ $present->heure_arriver }}
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="d-flex flex-column justify-content-center align-items-center" style="height: 200px;">
                            Aucun employé présent trouvé.
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-xxl-4 col-xl-6 d-flex">
            <div class="card flex-fill shadow">
                <div class="card-header pb-2 d-flex align-items-center justify-content-between flex-wrap">
                    <h5 class="mb-2 text-primary">Contrats à renouveler</h5>

                    <div class="input-group" style="max-width: 250px;">
                        <span class="input-group-text"><i class="ti ti-search"></i></span>
                        <input type="text" class="form-control shadow" id="search3" placeholder="Rechercher...">
                    </div>
                </div>
                <div class="card-body pb-2" id="absentTable3" style="max-height: 300px; overflow-y: auto;">
                    @if (count($utilisateursFinContrats) > 0)
                        @foreach ($utilisateursFinContrats as $dateFinContrat)
                            @php
                                $dateFin = \Carbon\Carbon::parse($dateFinContrat->date_fin_contrat);
                                $dateActuelle = \Carbon\Carbon::now();
                                $joursRestants = $dateActuelle->startOfDay()->diffInDays($dateFin->startOfDay(), false);
                                $dateFinContrat->jours_restants = $joursRestants;
                                $dateFinContrat->contrat = $dateFin->format('d/m/Y');

                                if ($joursRestants < 0) {
                                    $periodeContrat = 'Expiré';
                                } elseif ($joursRestants == 0) {
                                    $periodeContrat = "Aujourd'hui";
                                } elseif ($joursRestants == 1) {
                                    $periodeContrat = 'Demain'; // 💡 plus logique que 'Hier' ici
                                } elseif ($joursRestants < 7) {
                                    $periodeContrat = "$joursRestants jours";
                                } elseif ($joursRestants < 30) {
                                    $weeks = floor($joursRestants / 7);
                                    $remainingDays = $joursRestants % 7;
                                    $periodeContrat = "$weeks semaine" . ($weeks > 1 ? 's' : '');
                                    if ($remainingDays > 0) {
                                        $periodeContrat .= " , $remainingDays jour" . ($remainingDays > 1 ? 's' : '');
                                    }
                                } else {
                                    $months = floor($joursRestants / 30);
                                    $remainingDays = $joursRestants % 30;
                                    $weeks = floor($remainingDays / 7);
                                    $extraDays = $remainingDays % 7;

                                    $periodeContrat = "$months mois";
                                    if ($weeks > 0) {
                                        $periodeContrat .= " , $weeks semaine" . ($weeks > 1 ? 's' : '');
                                    }
                                    if ($extraDays > 0) {
                                        $periodeContrat .= " , $extraDays jour" . ($extraDays > 1 ? 's' : '');
                                    }
                                }
                            @endphp
                            <div class="absent-item">
                                <div>
                                    <div class="d-sm-flex justify-content-between flex-wrap mb-3">
                                        <div class="d-flex align-items-center mb-2">
                                            <a href="javscript:void(0);" class="avatar avatar-sm border flex-shrink-0">
                                                <img src="{{ asset('src/images/user.jpg') }}"
                                                    class="img-fluid w-auto h-auto" alt="img">
                                            </a>
                                            <div class="ms-2 flex-fill">
                                                <h6 class="fs-medium text-truncate mb-1"><a
                                                        href="javscript:void(0);">{{ \Illuminate\Support\Str::limit($dateFinContrat->nom . ' ' . $dateFinContrat->prenom, 30, '...') }}</a>
                                                </h6>
                                                <p class="fs-13">Date fin contrat :
                                                    {{ \Carbon\Carbon::parse($dateFinContrat->date_fin_contrat)->format('d M Y') }}
                                                </p>
                                            </div>
                                        </div>
                                        <div class="text-sm-end mb-2">
                                            <h6
                                                class="mb-1 {{ $periodeContrat == "Aujourd'hui" || $periodeContrat == 'Expiré' ? 'text-danger' : 'text-warning' }}">
                                                {{ $periodeContrat }}</h6>
                                            <p class="fs-13">Date d'embauche :
                                                {{ \Carbon\Carbon::parse($dateFinContrat->date_embauche)->format('d M Y') }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="d-flex flex-column justify-content-center align-items-center" style="height: 200px;">
                            Aucun contrat à renouveler pour le moment.
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-xxl-6 col-xl-12 d-flex">
            <div class="card flex-fill shadow">
                <div class="card-header pb-2 d-flex align-items-center justify-content-between flex-wrap">
                    <h5 class="mb-2">Statut des employés</h5>

                </div>
                <div class="card-body">
                    <div id="plan-overview"></div>
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <p class="f-13 mb-0"><i class="ti ti-circle-filled text-primary me-1"></i>Employés Actifs </p>
                        <p class="f-13 fw-medium text-gray-9">
                            @if (isset($employes) && count($employes) > 0)
                                {{ (count($employesActifs) * 100) / count($employes) }} %
                            @else
                                0
                            @endif
                        </p>
                    </div>
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <p class="f-13 mb-0"><i class="ti ti-circle-filled text-warning me-1"></i>Employés en congé</p>
                        <p class="f-13 fw-medium text-gray-9">0 %</p>
                    </div>
                    <div class="d-flex align-items-center justify-content-between mb-0">
                        <p class="f-13 mb-0"><i class="ti ti-circle-filled text-danger me-1"></i>Employés Inactifs</p>
                        <p class="f-13 fw-medium text-gray-9">
                            @if (isset($employes) && count($employes) > 0)
                                {{ (count($employesInactifs) * 100) / count($employes) }} %
                            @else
                                0
                            @endif
                        </p>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xxl-6 col-xl-12 d-flex">
            <div class="card flex-fill shadow">
                <div class="card-header pb-2 d-flex align-items-center justify-content-between flex-wrap">
                    <h5 class="mb-2">Types de contrats</h5>

                </div>
                <div class="card-body">
                    <div id="plan-overview2"></div>
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <p class="f-13 mb-0"><i class="ti ti-circle-filled text-primary me-1"></i>Contrats Actifs </p>
                        <p class="f-13 fw-medium text-gray-9">90 %</p>
                    </div>
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <p class="f-13 mb-0"><i class="ti ti-circle-filled text-danger me-1"></i>Contrats Inactifs</p>
                        <p class="f-13 fw-medium text-gray-9">10 %</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        const chartData = {
            series: @json([$pourcentages['conges'], $pourcentages['actifs'], $pourcentages['inactifs']]),
            labels: ['Employés en congé', 'Employés Actifs', 'Employés Inactifs'],
            colors: ['#FFC107', '#05436b', '#FF0000']
        };
        const chartData2 = {
            series: @json([$pourcentagesContrats['Contrats actifs'], $pourcentagesContrats['Contrats inactifs']]),
            labels: ['Contrats Actifs', 'Contrats Inactifs'],
            colors: ['#05436b', '#FF0000']
        };
    </script>


    <!-- End Email Statistic area-->
@endsection
