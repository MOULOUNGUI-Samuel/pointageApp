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
                                    <h2>Liste des utilisateurs</h2>
                                </div>
                            </div>
                            <div class="@if (Auth::user()->role_user == 'Admin') col-md-4 @else col-md-7 @endif">
                                @if (session('success'))
                                    <div class="alert alert-success">{{ session('success') }}</div>
                                @endif

                                @if ($errors->any())
                                    <div class="alert alert-danger">
                                        <ul>
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif
                            </div>
                            @if (Auth::user()->role_user == 'Admin')
                                <div class="col-md-3 mt-2">
                                    <div class="bootstrap-select fm-cmp-mg">
                                        <select class="selectpicker" data-live-search="true" id="filterStatus">
                                            <option value="">Toutes les entréprises</option>
                                            @foreach ($entreprises as $entreprise)
                                                <option value="{{ $entreprise->id }}">{{ $entreprise->nom_entreprise }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-2" id="ajoutUtilisateur" style="display: none;">
                                    <button type="button" class="btn btn-primary" data-toggle="modal"
                                        data-target="#floatingLabelsModal">
                                        Ajouter un utilisateur
                                    </button>
                                </div>
                            @else
                                <div class="col-md-2">
                                    <button type="button" class="btn btn-primary" data-toggle="modal"
                                        data-target="#floatingLabelsModal">
                                        Ajouter un utilisateur
                                    </button>
                                </div>
                            @endif

                        </div>
                        <!-- Modal -->
                        <div class="modal fade" id="floatingLabelsModal" tabindex="-1" role="dialog"
                            aria-labelledby="floatingLabelsModalLabel" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header bg-primary">
                                        <h4 class="modal-title text-white" id="floatingLabelsModal">
                                            <i class="bi bi-calendar-event"></i> Enregistrement d'un utilisateur
                                        </h4>
                                    </div>
                                    <form action="{{ route('ajoute_utilisateur') }}" method="POST"
                                        enctype="multipart/form-data" style="display:inline-block;">
                                        @csrf
                                        <div class="modal-body">
                                            @if (Auth::user()->role_user == 'Admin')
                                                <input type="hidden" name="entreprise_id" id="IdEntreprise"
                                                    autocomplete="off">
                                            @endif
                                            <div class="row">
                                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                    <div class="form-group ic-cmp-int float-lb floating-lb">
                                                        <div class="form-ic-cmp">
                                                            <i class="icon-user"></i>
                                                        </div>
                                                        <div class="nk-int-st">
                                                            <input type="text" class="form-control"
                                                                placeholder="Nom(s) de l'employer" name="nom"
                                                                value="{{ old('nom') }}" autocomplete="off" required>
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
                                                                placeholder="Prénom(s) de l'employer" name="prenom"
                                                                value="{{ old('prenom') }}" autocomplete="off" required>
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
                                                                data-mask="99/99/9999" autocomplete="off"
                                                                name="date_naissance" value="{{ old('date_naissance') }}"
                                                                required>
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
                                                                placeholder="Poste de l'employer" autocomplete="off"
                                                                name="fonction" value="{{ old('fonction') }}" required>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                                    <div class="form-group ic-cmp-int float-lb floating-lb">
                                                        <div class="form-ic-cmp">
                                                            <i class="icon-email"></i>
                                                        </div>
                                                        <div class="nk-int-st">
                                                            <input type="text" class="form-control"
                                                                placeholder="Email de l'employer" name="email"
                                                                pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$"
                                                                title="Veuillez entrer une adresse email valide (ex: nom@example.com)"
                                                                value="{{ old('email') }}" required autocomplete="off">
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                    <div class="form-group ic-cmp-int float-lb floating-lb">
                                                        <div class="form-ic-cmp">
                                                            <i class="icon-lock"></i>
                                                        </div>
                                                        <div class="nk-int-st">
                                                            <input type="text" class="form-control"
                                                                placeholder="Identifiant de connexion" name="matricule"
                                                                value="{{ old('matricule') }}" autocomplete="off"
                                                                required>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                    <div class="form-group ic-cmp-int float-lb floating-lb">
                                                        <div class="form-ic-cmp">
                                                            <i class="icon-key"></i>
                                                        </div>
                                                        <div class="nk-int-st">
                                                            <input type="password" id="passwordField"
                                                                class="form-control" placeholder="Mot de passe"
                                                                name="password" autocomplete="off" required>
                                                        </div>
                                                        <!-- Icône pour afficher/masquer -->
                                                        <span class="input-group-addon nk-ic-st-pro"
                                                            onclick="togglePassword()">
                                                            <i id="toggleIcon" class="icon-eye"
                                                                style="font-size: 25px; cursor: pointer;"></i>
                                                        </span>
                                                    </div>
                                                </div>
                                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                    <div class="form-group ic-cmp-int float-lb floating-lb"
                                                        style="display: flex;justify-content: space-between;margin-top: 10px;margin-left: 20px;">
                                                        <div class="toggle-select-act">
                                                            <div class="nk-toggle-switch" data-ts-color="blue">
                                                                <input id="ts6" type="checkbox" hidden="hidden"
                                                                    name="role_user" value="Employer"
                                                                    {{ old('role_user') == 'Employer' ? 'checked' : '' }}
                                                                    autocomplete="off">
                                                                <label for="ts6" class="ts-helper"></label>
                                                                <label for="ts6" class="ts-label">Employé</label>
                                                            </div>
                                                        </div>

                                                        <div class="toggle-select-act">
                                                            <div class="nk-toggle-switch" data-ts-color="blue">
                                                                <input id="ts7" type="checkbox" hidden="hidden"
                                                                    name="role_user" value="RH"
                                                                    {{ old('role_user') == 'RH' ? 'checked' : '' }}
                                                                    autocomplete="off">
                                                                <label for="ts7" class="ts-helper"></label>
                                                                <label for="ts7" class="ts-label">RH</label>
                                                            </div>
                                                        </div>

                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary"
                                                data-dismiss="modal">Fermer</button>
                                            <button type="submit" class="btn btn-primary">Enregistrer</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table id="data-table-basic" class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Nom(s)</th>
                                        <th>Prénom(s)</th>
                                        <th>Date</th>
                                        <th>Email</th>
                                        <th>Login</th>
                                        <th>Fonction</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody id="approbationsTable">
                                    @foreach ($employes as $employe)
                                        <tr data-status="{{ $employe->entreprise_id }}">

                                            <td>{{ strtoupper($employe->nom) }}</td>
                                            <td>{{ ucfirst($employe->prenom) }}</td>
                                            <td>{{ \Carbon\Carbon::parse($employe->created_at)->locale('fr')->translatedFormat('l d F Y') }}
                                            </td>
                                            <td>{{ $employe->email }}</td>
                                            <td>{{ $employe->matricule }}</td>
                                            <td>{{ $employe->fonction }}</td>
                                            <td>
                                                <a href="" class="btn btn-info btn-reco-mg btn-button-mg">
                                                    <i class="icon-eye" style="font-size: 20px;"></i>
                                                </a>
                                                <a href="" class="btn btn-warning btn-reco-mg btn-button-mg">
                                                    <i class="icon-edit" style="font-size: 20px;"></i>
                                                </a>
                                                <form action="" method="POST" style="display:inline-block;">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit"
                                                        class="btn btn-danger btn-reco-mg btn-button-mg">
                                                        <i class="icon-box" style="font-size: 20px;"></i>
                                                    </button>
                                                </form>
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
        const employeCheckbox = document.getElementById('ts6');
        const rhCheckbox = document.getElementById('ts7');
        const form = document.querySelector('form');

        employeCheckbox.addEventListener('change', function() {
            if (this.checked) {
                rhCheckbox.checked = false;
            }
        });

        rhCheckbox.addEventListener('change', function() {
            if (this.checked) {
                employeCheckbox.checked = false;
            }
        });

        form.addEventListener('submit', function(event) {
            if (!employeCheckbox.checked && !rhCheckbox.checked) {
                event.preventDefault();
                alert('Veuillez sélectionner au moins un rôle (Employé ou RH).');
            }
        });
    </script>
    <script>
        function togglePassword() {
            let passwordField = document.getElementById("passwordField");
            let toggleIcon = document.getElementById("toggleIcon");

            if (passwordField.type === "password") {
                passwordField.type = "text"; // Affiche le mot de passe
                toggleIcon.classList.remove("icon-eye");
                toggleIcon.classList.add("icon-eye-blocked"); // Change l'icône
            } else {
                passwordField.type = "password"; // Masque le mot de passe
                toggleIcon.classList.remove("icon-eye-blocked");
                toggleIcon.classList.add("icon-eye");
            }
        }
    </script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Filtrage par catégorie et statut
            function filterApprobations() {
                let IdEntreprise = document.getElementById("IdEntreprise");
                let ajoutUtilisateur = document.getElementById("ajoutUtilisateur");
                let status = document.getElementById("filterStatus").value;

                document.querySelectorAll("#approbationsTable tr").forEach(row => {
                    let rowCategory = row.getAttribute("data-category");
                    let rowStatus = row.getAttribute("data-status");
                    let rowText = row.textContent.toLowerCase();
                    IdEntreprise.value = status; // Récupère le statut sélectionné dans le filtre
                    ajoutUtilisateur.style.display = status ? "block" :
                        "none"; // Affiche ou masque le bouton

                    // Vérifie si la ligne correspond au filtre
                    let matchStatus = status === "" || rowStatus === status;

                    row.style.display = matchStatus ? "" : "none";
                });
            }
            document.getElementById("filterStatus").addEventListener("change", filterApprobations);
        });
    </script>
@endsection
