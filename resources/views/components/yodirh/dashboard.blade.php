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
    <div class="container-fluid">
        <div class="row">
            <div class=" col-lg-3 col-md-3 col-sm-3 col-xs-12">
                <div class="card p-3 shadow mb-3 bg-white rounded">
                    <div class="row">
                        <div class="col-md-2" style="text-align: left;">
                            <i class="fa fa-users fa-3x" aria-hidden="true" style="font-size: 40px"></i>
                        </div>
                        <div class="col-md-10" style="text-align: right;">
                            <h6 class="text-left text-uppercase  mb-2"><b>Nombre d'employés</b></h6>
                            <div class="text-right">
                                <h3>{{ count($employes) }}</h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12" style="margin-bottom:1px;">
                <div class="card p-3 shadow mb-3 bg-white rounded">
                    <div class="row">
                        <div class="col-md-2" style="text-align: left;">
                            <i class="fa fa-user fa-3x" aria-hidden="true" style="font-size: 40px"></i>
                        </div>
                        <div class="col-md-10" style="text-align: right;">
                            <h6 class="text-left text-uppercase  mb-2"><b>Employés présents</b></h6>
                            <div class="text-right">
                                <h3>{{ count($pointages_oui) }}</h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                <div class="card p-3 shadow mb-3 bg-white rounded">
                    <div class="row">
                        <div class="col-md-2" style="text-align: left;">
                            <i class="fa fa-sign-out fa-3x" aria-hidden="true" style="font-size: 40px"></i>
                        </div>
                        <div class="col-md-10" style="text-align: right;">
                            <h6 class="text-left text-uppercase  mb-2"><b>Sorties intermediaires</b></h6>
                            <div class="text-right">
                                <h3>{{ count($pointage_intermediaires) }}</h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                <div class="card p-3 shadow mb-3 bg-white rounded">
                    <div class="row">
                        <div class="col-md-2" style="text-align: left;">
                            <i class="fa fa-user-times fa-3x" aria-hidden="true" style="font-size: 40px"></i>
                        </div>
                        <div class="col-md-10" style="text-align: right;">
                            <h6 class="text-left text-uppercase  mb-2"><b>Employés absences</b></h6>
                            <div class="text-right">
                                <h3>{{ count($users_non_existants) }}</h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-3 col-md-2 col-sm-12 col-xs-12">

                <div class="my-3 card py-3 px-2 shadow bg-white rounded">
                    <h5 class="mx-2 box-title badge mb-1"
                        style="font-size: 13px;color:rgba(196, 12, 12, 0.877);border:1px solid rgba(196, 12, 12, 0.877);padding: 5px;border-radius: 5px;">
                        Employés sans trace de présence</h5>
                    <div class="mb-3 mx-2" style="margin-bottom: 10px;">
                        <input type="text" class="form-control-sm shadow border border-danger rounded w-100" id="search"
                            placeholder="Rechercher...">
                    </div>
                    <div class="" style="overflow-y: auto; max-height: 350px;">
                        <table class="table table-inner table-vmiddle">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Nom(s) et prenoms(s)</th>
                                </tr>
                            </thead>
                            <tbody id="absentTable">
                                @foreach ($users_non_existants as $absent)
                                    <tr>
                                        <td class="f-500 c-cyan"><img src="{{ asset('src/images/user.jpg') }}"
                                                alt="" width="30"
                                                style="border: 1px solid rgba(196, 12, 12, 0.877);border-radius:50px" />
                                        </td>
                                        <td>{{ \Illuminate\Support\Str::limit($absent->nom . ' ' . $absent->prenom, 15, '...') }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        const searchInput = document.getElementById('search');
                        const absentTable = document.getElementById('absentTable');

                        searchInput.addEventListener('input', function() {
                            const query = this.value.toLowerCase();
                            absentTable.querySelectorAll('tr').forEach(row => {
                                const text = row.textContent.toLowerCase();
                                row.style.display = text.includes(query) ? '' : 'none';
                            });
                        });
                    });
                </script>
            </div>
            <div class="col-lg-3 col-md-2 col-sm-12 col-xs-12">
                <div class="my-3 card p-2 shadow bg-white rounded">
                    <h5 class="box-title badge mb-1 mx-2"
                        style="font-size: 13px;color:green;border:1px solid green;padding: 5px;border-radius: 5px;">
                        Employés déjà presents</h5>
                    <div class="mb-3 mx-2" style="margin-bottom: 10px;">
                        <input type="text" class="form-control-sm shadow border border-success rounded w-100"
                            id="searchpresent" placeholder="Rechercher...">
                    </div>
                    <div class="" style="overflow-y: auto; max-height: 350px;">
                        <table class="table table-inner table-vmiddle">
                            <thead>
                                <tr>
                                    <th>Nom(s) et prenoms(s)</th>
                                    <th style="width: 60px">Heure</th>
                                </tr>
                            </thead>
                            <tbody id="presentTable">
                                @foreach ($pointages_oui as $present)
                                    <tr>
                                        <td>{{ \Illuminate\Support\Str::limit($present->nom . ' ' . $present->prenom, 15, '...') }}
                                        </td>
                                        <td class="f-500 c-cyan">{{ $present->heure_arriver }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="" style="text-align: center">
                        <a href="#">
                            <div class="recent-post-flex rc-ps-vw">
                                <div class="recent-post-line rct-pt-mg">
                                    <a href="{{ route('liste_presence') }}" style="color:green">Voir plus</a>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        const searchpresent = document.getElementById('searchpresent');
                        const presentTable = document.getElementById('presentTable');

                        searchpresent.addEventListener('input', function() {
                            const query = this.value.toLowerCase();
                            presentTable.querySelectorAll('tr').forEach(row => {
                                const text = row.textContent.toLowerCase();
                                row.style.display = text.includes(query) ? '' : 'none';
                            });
                        });
                    });
                </script>
            </div>
            <div class="col-lg-3 col-md-2 col-sm-12 col-xs-12">
                <div class="my-3 card py-3 px-2 shadow bg-white rounded">
                    <h4 class="mx-2 box-title badge mb-1"
                        style="font-size: 13px;border:1px solid ;padding: 5px;border-radius: 5px;">Statut des
                        employés
                    </h4>
                    <div class="">
                        <div class="row">
                            <div class="col-md-8 text-left">
                                <span>Actifs</span>
                            </div>
                            <div class="col-md-4 text-right">
                                @if (isset($employes) && count($employes) > 0)
                                    <span>{{ (count($employesActifs) * 100) / count($employes) }}</span>
                                @else
                                    <span>0</span>
                                @endif
                            </div>
                        </div>
                        <div class="progress progress-mini">
                            @if (isset($employes) && count($employes) > 0)
                                <div style="width: {{ (count($employesActifs) * 100) / count($employes) }}%;"
                                    class="progress-bar bg-green"></div>
                            @else
                                <div style="width: 0%;" class="progress-bar bg-green">0</div>
                            @endif

                        </div>
                        <div class="row">
                            <div class="col-md-8 text-left">
                                <span>En congé</span>
                            </div>
                            <div class="col-md-4 text-right">
                                <span>0%</span>
                            </div>
                        </div>
                        <div class="progress progress-mini">
                            <div style="width: 0%;" class="progress-bar bg-orange" style="background-color:orange">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-8 text-left">
                                <span>Inactifs</span>
                            </div>
                            <div class="col-md-4 text-right">
                                @if (isset($employes) && count($employes) > 0)
                                    <span>{{ (count($employesInactifs) * 100) / count($employes) }}</span>
                                @else
                                    <span>0</span>
                                @endif
                            </div>
                        </div>
                        <div class="progress progress-mini">
                            @if (isset($employes) && count($employes) > 0)
                                <div style="width: {{ (count($employesInactifs) * 100) / count($employes) }}%;"
                                    class="progress-bar bg-green"></div>
                            @else
                                <div style="width: 0%;" class="progress-bar bg-green">0</div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-2 col-sm-12 col-xs-12">
                <div class="my-3 card py-3 px-2 shadow bg-white rounded">
                    <h4 class="mx-2 box-title badge mb-1"
                        style="font-size: 13px;border:1px solid ;padding: 5px;border-radius: 5px;">Types de
                        contrats
                    </h4>
                    <div class="">
                        <div class="row">
                            <div class="col-md-8 text-left">
                                <span>Actifs</span>
                            </div>
                            <div class="col-md-4 text-right">
                                <span>60%</span>
                            </div>
                        </div>
                        <div class="progress progress-mini">
                            <div style="width: 60%;" class="progress-bar bg-green"></div>
                        </div>
                        <div class="row">
                            <div class="col-md-8 text-left">
                                <span>Inactifs</span>
                            </div>
                            <div class="col-md-4 text-right">
                                <span>10%</span>
                            </div>
                        </div>
                        <div class="progress progress-mini">
                            <div style="width: 10%;" class="progress-bar bg-red"></div>
                        </div>
                    </div>
                </div>
                <div class="my-3 card py-3 px-2 shadow bg-white rounded">
                    <h4 class="box-title badge mb-2"
                        style="font-size: 15px;background-color:#f8ac59;padding: 5px;border-radius: 5px;">Contrats à
                        renouveler
                    </h4>
                    <!-- Barre de recherche -->
                    <div class="mb-3" style="margin-bottom: 10px;">
                        <input type="text" class="form-control-sm shadow border border-dark rounded w-100" id="searchContrat"
                            placeholder="Rechercher un employé...">
                    </div>
                    @foreach ($utilisateursFinContrats as $dateFinContrat)
                        @php
                            $dateFin = \Carbon\Carbon::parse($dateFinContrat->date_fin_contrat);
                            $dateActuelle = \Carbon\Carbon::now();
                            $joursRestants = $dateFin->diffInDays($dateActuelle);
                            $dateFinContrat->jours_restants = $joursRestants;
                            $dateFinContrat->contrat = $dateFin->format('d/m/Y');

                            // ✅ Nouvelle logique d'affichage
if ($joursRestants == 0) {
    $periodeContrat = "Aujourd'hui";
} elseif ($joursRestants == 1) {
    $periodeContrat = 'Hier';
} elseif ($joursRestants < 7) {
    $periodeContrat = "$joursRestants jours";
} elseif ($joursRestants < 30) {
    // Gestion des semaines + jours
    $weeks = floor($joursRestants / 7);
    $remainingDays = $joursRestants % 7;
    $periodeContrat = "$weeks semaine" . ($weeks > 1 ? 's' : '');
    if ($remainingDays > 0) {
        $periodeContrat .= " , $remainingDays jour" . ($remainingDays > 1 ? 's' : '');
    }
} else {
    // Gestion des mois + semaines + jours
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

                        <div id="contratsList">
                            <div class="mb-3 contrat-item" style="overflow-y: auto; max-height: 400px;">
                                <div>
                                    <h5 style="font-size: 14px;">
                                        {{ \Illuminate\Support\Str::limit($dateFinContrat->nom . ' ' . $dateFinContrat->prenom, 30, '...') }}
                                    </h5>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 text-left">
                                        <span
                                            style="font-size: 14px;">{{ \Illuminate\Support\Str::limit($dateFinContrat->type_contrat, 20, '...') }}</span>
                                    </div>
                                    <div class="col-md-6 text-right">
                                        <span class="text-warning" style="font-size: 14px;">
                                            {{ $periodeContrat }}
                                        </span>
                                    </div>
                                </div>
                                <hr>
                            </div>
                        </div>
                        <script>
                            document.addEventListener('DOMContentLoaded', function() {
                                const searchInput = document.getElementById('searchContrat');
                                const items = document.querySelectorAll('#contratsList .contrat-item');
                                searchInput.addEventListener('keyup', function() {
                                    const value = this.value.toLowerCase();
                                    items.forEach(function(item) {
                                        const text = item.textContent.toLowerCase();
                                        item.style.display = text.includes(value) ? '' : 'none';
                                    });
                                });
                            });
                        </script>
                    @endforeach
                </div>
            </div>

        </div>
    </div>

    <!-- End Email Statistic area-->
@endsection
