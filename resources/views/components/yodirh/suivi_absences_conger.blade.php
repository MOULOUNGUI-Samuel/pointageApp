@extends('layouts.master2')
@section('content2')
    <div class="section-admin container-fluid">

        <div class="row" style="margin-left: 20px; margin-right: 20px;">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="data-table-list">
                    <div class="d-flex-justify-content-between mb-3 mt-3" style="margin-top: 10px">
                        <h2 class="card-title text-primary">Liste des presences</h2>
                        <!-- Modal -->
                        <div class="modal fade" id="sortiesIntermediairesModal" tabindex="-1" role="dialog"
                            aria-labelledby="sortiesIntermediairesModalLabel" aria-hidden="true">
                            <div class="modal-dialog  modal-lg" role="document">
                                <div class="modal-content">
                                    <div class="modal-header bg-primary">
                                        <h4 class="modal-title text-white" id="sortiesIntermediairesModalLabel">Sorties
                                            Intermédiaires
                                        </h4>
                                    </div>
                                    <div class="modal-body">
                                        <div class="row">
                                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                <div class="data-table-list">
                                                    <div class="row mx-3 " style="margin-bottom: 10px">
                                                        <div class="col-md-4 mt-2">
                                                            <label for="searchInput">Recherche</label>
                                                            <input type="text" id="searchInput" class="form-control"
                                                                placeholder="Rechercher..." onkeyup="searchTable1()"
                                                                style="width: 100%;">

                                                            <script>
                                                                function searchTable() {
                                                                    const input = document.getElementById('searchInput').value.toLowerCase();
                                                                    const rows = document.querySelectorAll('#approbationsTable2 tr');

                                                                    rows.forEach(row => {
                                                                        const rowText = row.textContent.toLowerCase();
                                                                        row.style.display = rowText.includes(input) ? '' : 'none';
                                                                    });

                                                                    const message = document.getElementById('aucun-resultat2');
                                                                    const visibleRows = Array.from(rows).filter(row => row.style.display !== 'none');
                                                                    message.style.display = visibleRows.length === 0 ? 'block' : 'none';
                                                                }
                                                            </script>
                                                        </div>
                                                        <div class="col-md-4 mb-2">
                                                            <label for="filtre-date2" class="me-2 mb-0">Date début :</label>
                                                            <input type="date" id="filtre-date2" name="date_debut2"
                                                                class="form-control">
                                                        </div>

                                                        <div class="col-md-4 mb-2">
                                                            <label for="filtre-date3" class="me-2 mb-0">Date fin :</label>
                                                            <input type="date" id="filtre-date3" name="date_fin2"
                                                                class="form-control">
                                                        </div>
                                                    </div>

                                                    <div class="table-responsive2">
                                                        <table id="data-table-basic" class="table table-striped">
                                                            <thead>
                                                                <tr class="bg-primary" class="text-primary">
                                                                    <th class="text-primary">Nom(s)</th>
                                                                    <th class="text-primary">Date</th>
                                                                    <th class="text-primary">Heure sortie</th>
                                                                    <th class="text-primary">Heure retour</th>
                                                                    <th class="text-primary">Action</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody id="approbationsTable2">
                                                                @foreach ($Pointages as $pointage)
                                                                    @if (!empty($cause_sorties[$pointage->id]))
                                                                        @foreach ($cause_sorties[$pointage->id] as $data)
                                                                            @php
                                                                                $pointage_intermediaire =
                                                                                    $data['pointage_intermediaire'];
                                                                                $descriptions = $data['descriptions'];
                                                                            @endphp

                                                                            <tr class="pointage-item2"
                                                                                data-date="{{ $pointage->date_arriver }}">
                                                                                <td>{{ $pointage->user->nom }}
                                                                                    {{ $pointage->user->prenom }}</td>
                                                                                <td>
                                                                                    {{ \App\Helpers\DateHelper::convertirDateEnTexte(
                                                                                        \App\Helpers\DateHelper::convertirDateFormat($pointage->date_arriver),
                                                                                    ) }}
                                                                                </td>
                                                                                <td>
                                                                                    <span class="badge badge-warning p-2"
                                                                                        style="background-color:rgb(146, 97, 5)">
                                                                                        {{ $pointage_intermediaire->heure_sortie }}
                                                                                    </span>
                                                                                </td>
                                                                                <td>
                                                                                    <span class="badge badge-success p-2"
                                                                                        style="background-color:green">
                                                                                        {{ $pointage_intermediaire->heure_entrer }}
                                                                                    </span>
                                                                                </td>
                                                                                <td>
                                                                                    <button
                                                                                        class="btn btn-primary btn-reco-mg btn-button-mg">
                                                                                        <i class="icon-eye"
                                                                                            style="font-size: 20px; margin-right: 10px"></i>
                                                                                        <span>Profil</span>
                                                                                    </button>
                                                                                </td>
                                                                            </tr>
                                                                        @endforeach
                                                                    @endif
                                                                @endforeach


                                                            </tbody>
                                                        </table>
                                                        <div id="aucun-resultat2" style="display: none;">
                                                            <h4 colspan="8" class="text-center text-warning">Aucun
                                                                pointage trouvé.</h4>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-primary"
                                            data-bs-dismiss="modal">Fermer</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card ">
                        <div class="card-header">
                            <!-- Search -->
                            <div class="row align-items-center">
                                <div class="col-sm-4">
                                    <div class="icon-form mb-3 mb-sm-0">
                                        <span class="form-icon"><i class="ti ti-search"></i></span>
                                        <input type="text" onkeyup="searchTable1()" id="searchInput1"
                                            class="form-control" placeholder="Rechercher un utilisateur...">
                                    </div>
                                </div>
                                <div class="col-sm-8">
                                    <div class="d-flex align-items-center flex-wrap row-gap-2 justify-content-sm-end">
                                        <div class="">
                                            <a href="{{ route('yodirh.utilisateurs') }}" class="btn-action btn btn-primary"
                                                data-loader-target="liste-utilisateur"><i class="fas fa-list me-2"></i>Liste
                                                des utilisateurs</a>
                                            <!-- Bouton de chargement (caché au départ) -->
                                            <button type="button" id="liste-utilisateur" class="btn btn-outline-primary"
                                                style="display: none;" disabled>
                                                <i class="fas fa-spinner fa-spin me-2"></i>Chargement...
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- /Search -->
                            <script>
                                function searchTable1() {
                                    const input = document.getElementById('searchInput1').value.toLowerCase();
                                    const rows = document.querySelectorAll('#approbationsTable tr');

                                    rows.forEach(row => {
                                        const rowText = row.textContent.toLowerCase();
                                        row.style.display = rowText.includes(input) ? '' : 'none';
                                    });

                                    const message = document.getElementById('aucun-resultat');
                                    const visibleRows = Array.from(rows).filter(row => row.style.display !== 'none');
                                    message.style.display = visibleRows.length === 0 ? 'block' : 'none';
                                }
                            </script>
                        </div>

                        <div class="card-body">
                            <div class="d-flex align-items-center justify-content-between flex-wrap row-gap-2 mb-4">
                                <div class="d-flex align-items-center flex-wrap row-gap-2">
                                    <div class="dropdown me-2">

                                        <label for="filtre-date" class="me-2 mb-0">Date début :</label>
                                        <input type="date" id="filtre-date" name="date_debut" class="form-control">

                                    </div>
                                    <div class="icon-form">
                                        <label for="filtre-date1" class="me-2 mb-0">Date fin :</label>
                                        <input type="date" id="filtre-date1" name="date_fin" class="form-control">
                                    </div>
                                </div>
                                <div class="d-flex align-items-center flex-wrap row-gap-2 mt-4">
                                    <div class="dropdown me-2 mt-1">
                                        <button type="button" class="btn btn-primary" style="margin-bottom: 5px;"
                                            data-bs-toggle="modal" data-bs-target="#sortiesIntermediairesModal">
                                            <i class="fa fa-plus"></i> Sorties intermediaires
                                        </button>

                                    </div>
                                    <div class="form-sorts dropdown">
                                        <a href="javascript:void(0);" class="dropdown-toggle"
                                            data-bs-toggle="dropdown"><i
                                                class="ti ti-package-export me-2"></i>Exporter</a>
                                        <div class="dropdown-menu  dropdown-menu-end">
                                            <ul>
                                                <li>
                                                    <a href="javascript:void(0);" class="dropdown-item"><i
                                                            class="ti ti-file-type-pdf text-danger me-1"></i>Exporter
                                                        en PDF</a>
                                                </li>
                                                <li>
                                                    <a href="javascript:void(0);" class="dropdown-item"><i
                                                            class="ti ti-file-type-xls text-green me-1"></i>Exporter
                                                        en Excel </a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="table-responsive">
                                <table id="data-table-basic" class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th class="text-primary">Matricule</th>
                                            <th class="text-primary">Nom(s)</th>
                                            <th class="text-primary">Prenom(s)</th>
                                            <th class="text-primary">Date</th>
                                            <th class="text-primary">Heure arrivée</th>
                                            <th class="text-primary">Heure sortie</th>
                                            <th class="text-primary">Statut</th>
                                            <th class="text-primary">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody id="approbationsTable">
                                        @foreach ($liste_pointages as $pointage)
                                            <tr class="pointage-item" data-date="{{ $pointage->date_arriver }}">
                                                <td>{{ $pointage->user->matricule }}</td>
                                                <td>{{ $pointage->user->nom }}</td>
                                                <td>{{ $pointage->user->prenom }}</td>
                                                <td>
                                                    {{ \App\Helpers\DateHelper::convertirDateEnTexte(
                                                        \App\Helpers\DateHelper::convertirDateFormat($pointage->date_arriver),
                                                    ) }}
                                                </td>
                                                <td>
                                                    <span class="badge badge-success p-2" style="background-color:green">
                                                        {{ $pointage->heure_arriver }}
                                                    </span>
                                                </td>
                                                <td>
                                                    <span class="badge badge-warning p-2"
                                                        style="background-color:rgb(146, 97, 5)">
                                                        {{ $pointage->heure_fin ?? '-- : -- : --' }}
                                                    </span>
                                                </td>
                                                <td>
                                                    @if ($pointage->heure_arriver > $pointage->user->entreprise->heure_ouverture)
                                                        <span class="badge badge-danger p-2"
                                                            style="background-color:rgba(196, 12, 12, 0.877)">
                                                            En retard
                                                        </span>
                                                    @else
                                                        <span class="badge badge-success p-2"
                                                            style="background-color:green">
                                                            A l'heure
                                                        </span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <a href="{{ route('Suivi_profil', $pointage->user->id) }}"
                                                        class="btn-action btn btn-primary btn-reco-mg btn-button-mg"
                                                        data-loader-target="profile{{ $pointage->user->id }}">
                                                        <i class="fa fa-eye"
                                                            style="font-size: 15px; margin-right: 10px"></i>
                                                        <span>Profil</span>
                                                    </a>
                                                    <button type="button" id="profile{{ $pointage->user->id }}"
                                                        class="btn btn-outline-primary" style="display: none;" disabled>
                                                        <i class="fas fa-spinner fa-spin me-2"></i>Chargement...
                                                    </button>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                <div id="aucun-resultat" style="display: none;">
                                    <h4 colspan="8" class="text-center text-warning">Aucun pointage trouvé pour cette
                                        période.</h4>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <script>
            function getTodayDate2() {
                const today = new Date();
                const yyyy = today.getFullYear();
                const mm = String(today.getMonth() + 1).padStart(2, '0'); // Janvier = 0
                const dd = String(today.getDate()).padStart(2, '0');
                return `${yyyy}-${mm}-${dd}`; // format YYYY-MM-DD
            }

            function filtrerParPeriode2() {
                const dateDebut = document.getElementById('filtre-date2').value;
                const dateFin = document.getElementById('filtre-date3').value;
                const items = document.querySelectorAll('.pointage-item2');
                let matchCount = 0;

                items.forEach(item => {
                    const itemDate = item.getAttribute('data-date2'); // format YYYY-MM-DD

                    if (
                        (!dateDebut || itemDate >= dateDebut) &&
                        (!dateFin || itemDate <= dateFin)
                    ) {
                        item.style.display = '';
                        matchCount++;
                    } else {
                        item.style.display = 'none';
                    }
                });

                const message = document.getElementById('aucun-resultat2');
                if (message) {
                    message.style.display = matchCount === 0 ? 'block' : 'none';
                }
            }

            document.addEventListener('DOMContentLoaded', function() {
                const today = getTodayDate2();
                document.getElementById('filtre-date2').value = today;
                document.getElementById('filtre-date3').value = today;

                // Lance le filtrage automatiquement
                filtrerParPeriode2();
            });

            document.getElementById('filtre-date2').addEventListener('change', filtrerParPeriode);
            document.getElementById('filtre-date3').addEventListener('change', filtrerParPeriode);
        </script>
        <script>
            function getTodayDate() {
                const today = new Date();
                const yyyy = today.getFullYear();
                const mm = String(today.getMonth() + 1).padStart(2, '0'); // Janvier = 0
                const dd = String(today.getDate()).padStart(2, '0');
                return `${yyyy}-${mm}-${dd}`; // format YYYY-MM-DD
            }

            function filtrerParPeriode() {
                const dateDebut = document.getElementById('filtre-date').value;
                const dateFin = document.getElementById('filtre-date1').value;
                const items = document.querySelectorAll('.pointage-item');
                let matchCount = 0;

                items.forEach(item => {
                    const itemDate = item.getAttribute('data-date'); // format YYYY-MM-DD

                    if (
                        (!dateDebut || itemDate >= dateDebut) &&
                        (!dateFin || itemDate <= dateFin)
                    ) {
                        item.style.display = '';
                        matchCount++;
                    } else {
                        item.style.display = 'none';
                    }
                });

                const message = document.getElementById('aucun-resultat');
                if (message) {
                    message.style.display = matchCount === 0 ? 'block' : 'none';
                }
            }

            document.addEventListener('DOMContentLoaded', function() {
                const today = getTodayDate();
                document.getElementById('filtre-date').value = today;
                document.getElementById('filtre-date1').value = today;

                // Lance le filtrage automatiquement
                filtrerParPeriode();
            });

            document.getElementById('filtre-date').addEventListener('change', filtrerParPeriode);
            document.getElementById('filtre-date1').addEventListener('change', filtrerParPeriode);
        </script>

        <script>
            document.addEventListener("DOMContentLoaded", function() {
                // Filtrage par catégorie et statut
                function filterApprobations() {
                    let status = document.getElementById("filterStatus").value;

                    document.querySelectorAll("#approbationsTable tr").forEach(row => {
                        let rowCategory = row.getAttribute("data-category");
                        let rowStatus = row.getAttribute("data-status");
                        let rowText = row.textContent.toLowerCase();

                        let matchStatus = status === "" || rowStatus === status;

                        row.style.display = matchStatus ? "" : "none";
                    });
                }
                document.getElementById("filterStatus").addEventListener("change", filterApprobations);
            });
        </script>
    </div>
    <!-- End Email Statistic area-->
@endsection
