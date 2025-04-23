@extends('layouts.master')
@section('content')
    <div class="data-table-area">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="data-table-list">
                        <div class="row mb-3 mx-3">
                            <div class="col-md-6 mt-2">
                                <div class="basic-tb-hd">
                                    <h2>Liste des sorties intermediaires</h2>
                                </div>
                            </div>
                            <div class="col-md-3"></div>
                            <div class="col-md-3 mt-2">
                                <select class="form-select form-control  shadow-sm" id="filterStatus">
                                    <option value="">Tous les statuts</option>
                                    <option value="a_heure">Entrées</option>
                                    <option value="en_retard">Sorties</option>
                                </select>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table id="data-table-basic" class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Matricule</th>
                                        <th>Nom(s)</th>
                                        <th>Prenom(s)</th>
                                        <th>Date</th>
                                        <th>Heure</th>
                                        <th>Description</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody id="approbationsTable">
                                    @foreach ($cause_sorties['pointage_intermediaires'] as $pointage_intermediaire)
                                        <tr data-status="en_retard">
                                            <td>{{ $pointage_intermediaire->pointage->user->matricule }}</td>
                                            <td>{{ $pointage_intermediaire->pointage->user->nom }}</td>
                                            <td>{{ $pointage_intermediaire->pointage->user->prenom }}</td>
                                            <td> {{ \App\Helpers\DateHelper::convertirDateEnTexte(App\Helpers\DateHelper::convertirDateFormat($pointage_intermediaire->pointage->date_arriver)) }}
                                            </td>
                                            <td><span
                                                    class="badge badge-primary">{{ $pointage_intermediaire->heure_sortie }}</span>
                                            </td>
                                            <td>
                                                @foreach ($cause_sorties['DescriptionPointages'] as $description)
                                                    {{ $description->description }}
                                                @endforeach
                                            </td>
                                            <td>
                                                <button class="btn btn-primary btn-reco-mg btn-button-mg">
                                                    <i class="icon-eye" style="font-size: 20px; margin-right: 10px"></i>
                                                    <span>Profil</span>
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
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
@endsection
