@extends('layouts.master')
@section('content')
    <div class="data-table-area">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="data-table-list">
                        <div class="row mb-3 mx-3">
                            <div class="col-md-3 mt-2">
                                <div class="basic-tb-hd">
                                    <h2>Liste des employers</h2>
                                </div>
                            </div>
                            <div class="col-md-4"></div>
                            <div class="col-md-3 mt-2">
                                {{-- <select class="form-select form-control  shadow-sm" id="filterStatus">
                                    <option value="">Tous les statuts</option>
                                    <option value="a_heure">A l'heure</option>
                                    <option value="en_retard">En retard</option>
                                </select> --}}
                                <div class="bootstrap-select fm-cmp-mg">
                                    <div class="chosen-select-act fm-cmp-mg">
                                        <select class="chosen" data-placeholder="choix du statut...">
                                            <option value="employer">employer</option>
                                            <option value="RH">RH</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <button type="button" class="btn btn-primary" data-toggle="modal"
                                    data-target="#floatingLabelsModal">
                                    Ajouter un employer
                                </button>
                            </div>
                        </div>
                        <!-- Modal -->
                        <div class="modal fade" id="floatingLabelsModal" tabindex="-1" role="dialog"
                            aria-labelledby="floatingLabelsModalLabel" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header bg-primary">
                                        <h4 class="modal-title text-white" id="floatingLabelsModal">
                                            <i class="bi bi-calendar-event"></i> Formulaire d'enregistrement d'un employer
                                        </h4>
                                    </div>
                                    <div class="modal-body">
                                        <div class="row">
                                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                <div class="form-group ic-cmp-int float-lb floating-lb">
                                                    <div class="form-ic-cmp">
                                                        <i class="icon-user"></i>
                                                    </div>
                                                    <div class="nk-int-st">
                                                        <input type="text" class="form-control"
                                                            placeholder="Nom(s) de l'employer">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                <div class="form-group ic-cmp-int float-lb floating-lb">
                                                    <div class="form-ic-cmp">
                                                        <i class="icon-user"></i>
                                                    </div>
                                                    <div class="nk-int-st">
                                                        <input type="text" class="form-control"
                                                            placeholder="Prénom(s) de l'employer">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                <div class="form-group ic-cmp-int float-lb floating-lb">
                                                    <div class="form-ic-cmp">
                                                        <i class="icon-calendar"></i>
                                                    </div>
                                                    <div class="nk-int-st">
                                                        <input type="text" class="form-control"
                                                            placeholder="Date de naissance de l'employer"
                                                            data-mask="99/99/9999">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                <div class="form-group ic-cmp-int float-lb floating-lb">
                                                    <div class="form-ic-cmp">
                                                        <i class="icon-clipboard"></i>
                                                    </div>
                                                    <div class="nk-int-st">
                                                        <input type="text" class="form-control"
                                                            placeholder="Poste de l'employer">
                                                    </div>
                                                </div>
                                            </div>

                                        </div>

                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary"
                                            data-dismiss="modal">Fermer</button>
                                        <button type="button" class="btn btn-primary">Enregistrer</button>
                                    </div>
                                </div>
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
                                        <th>Statut</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody id="approbationsTable">
                                    <tr data-status="en_retard">
                                        <td>12345</td>
                                        <td>MOULOUNGUI MOULOUNGUI</td>
                                        <td>Bienvenu Samuel</td>
                                        <td>Lundi 31 Décembre 2025</td>
                                        <td><span class="badge badge-primary">12 h 15 min</span></td>
                                        <td><span class="badge"
                                                style="background-color: rgba(189, 5, 5, 0.877);padding:5px">En
                                                retard</span></td>
                                        <td><button class="btn btn-primary btn-reco-mg btn-button-mg"><i class="icon-eye"
                                                    style="font-size: 20px;margin-right:10px"></i><span>Profil</span></button>
                                        </td>
                                    </tr>
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
