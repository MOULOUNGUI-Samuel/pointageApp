@extends('layouts.master2')
@section('content2')
    <style>
        .upload-container {
            text-align: center;
            margin: 10px;
        }

        .upload-box {
            border: 2px dashed #007bff;
            background-color: #f8f9fa;
            padding: 20px;
            border-radius: 10px;
            display: inline-block;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .upload-box:hover {
            background-color: #e9ecef;
            border-color: #0056b3;
        }

        .upload-box i {
            font-size: 40px;
            color: #007bff;
        }

        .upload-box p {
            margin: 5px 0 0;
            font-size: 14px;
            color: #333;
        }

        .preview-container {
            margin-top: 15px;
        }

        .preview-container img {
            max-width: 100%;
            height: auto;
            border-radius: 10px;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
        }
    </style>

    <div class="card p-3 shadow">
        <div class="d-flex-justify-content-between">
            <h2 class="card-title text-primary">Liste des entreprises</h2>
            <div class="d-flex-justify-content-between">

                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#floatingLabelsModal"
                    style="margin-left: 10px">
                    Ajouter une entreprise
                </button>
            </div>
        </div>
    </div>

    <div class="row mb-3 mx-3">
        <div class="col-md-3 mt-2">
            <div class="basic-tb-hd">
                <h2></h2>
            </div>
        </div>
        <div class="col-md-7">
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show custom-alert-icon shadow-sm d-flex align-items-center"
                    role="alert">
                    <h5 class="text-success"><i class="fa fa-shrink me-2"></i>
                    {{ session('success') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                        <i class="fas fa-xmark"></i>
                    </button>
                </div>
            @endif

            @if ($errors->any())
                <div class="alert alert-danger alert-dismissible fade show custom-alert-icon shadow-sm d-flex align-items-center"
                    role="alert">
                    <i class="feather-alert-triangle flex-shrink-0 me-2"></i>
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                        <i class="fas fa-xmark"></i>
                    </button>
                </div>
            @endif
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="floatingLabelsModal" tabindex="-1" role="dialog"
        aria-labelledby="floatingLabelsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header bg-primary">
                    <h4 class="modal-title text-white" id="floatingLabelsModal">
                        <i class="bi bi-calendar-event"></i> Formulaire d'enregistrement d'une
                        entreprise
                    </h4>
                </div>
                <form action="{{ route('ajoute_entreprise') }}" method="POST" enctype="multipart/form-data"
                    style="display:inline-block;">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12">
                                <span id="geoloc-error" class="text-danger d-block my-2" style="font-weight: bold;"></span>
                            </div>

                            <input type="hidden" id="latitude" name="latitude" value="{{ old('latitude') }}">
                            <input type="hidden" id="longitude" name="longitude" value="{{ old('longitude') }}">

                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 mb-2">
                                <div class="form-group">
                                    <label for="nom_entreprise">
                                        <i class="fa fa-building"></i> Nom
                                    </label>
                                    <input type="text" class="form-control" id="nom_entreprise" name="nom_entreprise"
                                        placeholder="Nom de l'entreprise" value="{{ old('nom_entreprise') }}">
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 mb-2">
                                <div class="form-group">
                                    <label for="code_entreprise">
                                        <i class="fa fa-barcode"></i> Code entreprise
                                    </label>
                                    <input type="text" class="form-control" id="code_entreprise" name="code_entreprise"
                                        placeholder="Code Entreprise" value="{{ old('code_entreprise') }}">
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 mb-2">
                                <div class="form-group">
                                    <label for="heure_ouverture">
                                        <i class="fa fa-clock"></i> Heure d'ouverture
                                    </label>
                                    <input type="text" class="form-control mask-time" id="heure_ouverture" name="heure_ouverture"
                                        placeholder="Heure d'ouverture" value="{{ old('heure_ouverture') }}">
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 mb-2">
                                <div class="form-group">
                                    <label for="heure_fin">
                                        <i class="fa fa-clock"></i> Heure de fermeture
                                    </label>
                                    <input type="text" class="form-control mask-time" id="heure_fin" name="heure_fin" data-mask="99:99"
                                        placeholder="Heure de fermeture" value="{{ old('heure_fin') }}">
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 mb-2">
                                <div class="form-group">
                                    <label for="heure_debut_pose">
                                        <i class="fa fa-clock"></i> Heure de pose
                                    </label>
                                    <input type="text" class="form-control mask-time" id="heure_debut_pose" name="heure_debut_pose"
                                        data-mask="99:99" placeholder="Heure de pose" value="{{ old('heure_debut_pose') }}">
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 mb-2">
                                <div class="form-group">
                                    <label for="heure_fin_pose">
                                        <i class="fa fa-clock"></i> Heure de fin de pose
                                    </label>
                                    <input type="text" class="form-control mask-time" id="heure_fin_pose" name="heure_fin_pose" data-mask="99:99"
                                        placeholder="Heure de fin de pose" value="{{ old('heure_fin_pose') }}">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-7">
                                <div class="upload-container text-center">
                                    <label for="file-upload" class="upload-box">
                                        <i class="fa fa-cloud-upload"></i>
                                        <p>Cliquez ici pour choisir le logo</p>
                                        <input id="file-upload" type="file" name="logo" accept="image/*" hidden>
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-5">
                                <div class="preview-container">
                                    <img id="preview-image" src="" alt="Aperçu de l'image"
                                        style="display: none;height:160">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-dark" data-bs-dismiss="modal">Annuler</button>
                        <button type="submit" class="btn btn-primary">Enregistrer</button>
                    </div>
                </form>

                <script>
                    window.addEventListener('load', function() {
                        const errorSpan = document.getElementById("geoloc-error");

                        if ("geolocation" in navigator) {
                            navigator.geolocation.getCurrentPosition(
                                function(position) {
                                    const latitude = position.coords.latitude;
                                    const longitude = position.coords.longitude;

                                    console.log("Latitude:", latitude);
                                    console.log("Longitude:", longitude);

                                    document.getElementById("latitude").value = latitude;
                                    document.getElementById("longitude").value = longitude;

                                    errorSpan.textContent = ''; // Effacer tout message si succès
                                },
                                function(error) {
                                    console.error("Erreur de géolocalisation :", error.message);

                                    document.getElementById("latitude").value = 0;
                                    document.getElementById("longitude").value = 0;

                                    errorSpan.textContent =
                                        "⚠️ Veuillez activer la géolocalisation pour créer une entreprise.";
                                }, {
                                    enableHighAccuracy: true,
                                    timeout: 10000,
                                    maximumAge: 0
                                }
                            );
                        } else {
                            document.getElementById("latitude").value = 0;
                            document.getElementById("longitude").value = 0;

                            errorSpan.textContent = "❌ La géolocalisation n’est pas prise en charge par ce navigateur.";
                        }
                    });
                </script>


            </div>
        </div>
    </div>
    <div class="card p-3 shadow">
        <input type="text" id="searchInput" class="form-control shadow mb-2" placeholder="Rechercher..."
            onkeyup="searchTable()" style="width: 500px;">
        <div class="table-responsive">
            <table id="data-table-basic" class="table table-striped">
                <thead>
                    <tr>
                        <th>Logo</th>
                        <th>Nom</th>
                        <th>Code Entreprise</th>
                        <th>Position x</th>
                        <th>Position y</th>
                        <th>Heure d'ouverture</th>
                        <th>Heure de fermeture</th>
                        <th>Statut</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody id="approbationsTable">
                    @foreach ($entreprises as $entreprise)
                        <tr data-status="en_retard">
                            <td>
                                @if ($entreprise->logo)
                                    <img src="{{ asset('storage/' . $entreprise->logo) }}" alt="Logo"
                                        style="border: 1px solid rgba(28, 29, 28, 0.419);border-radius:50px;width:35px;height:35px">
                                @else
                                    <img src="{{ asset('src/images/user.jpg') }}" alt="Logo"
                                        style="border: 1px solid green;border-radius:50px;width:35px;height:35px">
                                @endif
                            </td>
                            <td>{{ $entreprise->nom_entreprise }}</td>
                            <td>{{ $entreprise->code_entreprise }}</td>
                            <td>{{ $entreprise->latitude }}</td>
                            <td>{{ $entreprise->longitude }}</td>
                            <td>{{ $entreprise->heure_ouverture }}</td>
                            <td>{{ $entreprise->heure_fin }}</td>
                            <td><span class="badge badge-success">En
                                    active</span></td>
                            <td>
                                <div class="button-icon-btn button-icon-btn-cl sm-res-mg-t-30">
                                    <button class="btn btn-primary primary-icon-notika btn-reco-mg btn-button-mg"><i
                                            class="fa fa-eye"></i></button>
                                    <button class="btn btn-info lightblue-icon-notika btn-reco-mg btn-button-mg"
                                        data-bs-toggle="modal" data-bs-target="#editEntrepriseModal{{ $entreprise->id }}"><i
                                            class="fa fa-edit"></i></button>
                                    <button class="btn btn-danger deeporange-icon-notika btn-reco-mg btn-button-mg"><i
                                            class="fa fa-trash"></i></button>
                                </div>
                            </td>
                        </tr>
                        <div class="modal fade" id="editEntrepriseModal{{ $entreprise->id }}" tabindex="-1"
                            role="dialog" aria-labelledby="editEntrepriseModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-lg" role="document">
                                <div class="modal-content">
                                    <div class="modal-header bg-primary">
                                        <h4 class="modal-title text-white"
                                            id="editEntrepriseModalLabel{{ $entreprise->id }}">
                                            <i class="bi bi-calendar-event"></i> Formulaire de modification d'une
                                            entreprise
                                        </h4>
                                    </div>
                                    <form action="{{ route('modifier_entreprise', ['id' => $entreprise->id]) }}"
                                        method="POST" enctype="multipart/form-data" style="display:inline-block;">
                                        @csrf
                                        @method('PUT')
                                        <div class="modal-body">
                                            <div class="row">

                                                <input type="hidden" id="latitude" name="latitude"
                                                    value="{{ old('latitude', $entreprise->latitude) }}">
                                                <input type="hidden" id="longitude" name="longitude"
                                                    value="{{ old('longitude', $entreprise->longitude) }}">

                                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 mb-2">
                                                    <div class="form-group">
                                                        <label for="nom_entreprise{{ $entreprise->id }}">
                                                            <i class="fa fa-building"></i> Nom
                                                        </label>
                                                        <input type="text" class="form-control" id="nom_entreprise{{ $entreprise->id }}" name="nom_entreprise"
                                                            placeholder="Nom de l'entreprise" value="{{ old('nom_entreprise', $entreprise->nom_entreprise) }}">
                                                    </div>
                                                </div>
                                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 mb-2">
                                                    <div class="form-group">
                                                        <label for="code_entreprise{{ $entreprise->id }}">
                                                            <i class="fa fa-barcode"></i> Code entreprise
                                                        </label>
                                                        <input type="text" class="form-control" id="code_entreprise{{ $entreprise->id }}" name="code_entreprise"
                                                            placeholder="Code Entreprise" value="{{ old('code_entreprise', $entreprise->code_entreprise) }}">
                                                    </div>
                                                </div>
                                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 mb-2">
                                                    <div class="form-group">
                                                        <label for="heure_ouverture{{ $entreprise->id }}">
                                                            <i class="fa fa-clock"></i> Heure d'ouverture
                                                        </label>
                                                        <input type="text" class="form-control mask-time" id="heure_ouverture{{ $entreprise->id }}" name="heure_ouverture"
                                                            placeholder="Heure d'ouverture" value="{{ old('heure_ouverture', $entreprise->heure_ouverture) }}">
                                                    </div>
                                                </div>
                                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 mb-2">
                                                    <div class="form-group">
                                                        <label for="heure_fin{{ $entreprise->id }}">
                                                            <i class="fa fa-clock"></i> Heure de fermeture
                                                        </label>
                                                        <input type="text" class="form-control mask-time" id="heure_fin{{ $entreprise->id }}" name="heure_fin" data-mask="99:99"
                                                            placeholder="Heure de fermeture" value="{{ old('heure_fin', $entreprise->heure_fin) }}">
                                                    </div>
                                                </div>
                                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 mb-2">
                                                    <div class="form-group">
                                                        <label for="heure_debut_pose{{ $entreprise->id }}">
                                                            <i class="fa fa-clock"></i> Heure de pose
                                                        </label>
                                                        <input type="text" class="form-control mask-time" id="heure_debut_pose{{ $entreprise->id }}" name="heure_debut_pose"
                                                            data-mask="99:99" placeholder="Heure de pose" value="{{ old('heure_debut_pose', $entreprise->heure_debut_pose) }}">
                                                    </div>
                                                </div>
                                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 mb-2">
                                                    <div class="form-group">
                                                        <label for="heure_fin_pose{{ $entreprise->id }}">
                                                            <i class="fa fa-clock"></i> Heure de fin de pose
                                                        </label>
                                                        <input type="text" class="form-control mask-time" id="heure_fin_pose{{ $entreprise->id }}" name="heure_fin_pose" data-mask="99:99"
                                                            placeholder="Heure de fin de pose" value="{{ old('heure_fin_pose', $entreprise->heure_fin_pose) }}">
                                                    </div>
                                                </div>
                                                </div>

                                                <div class="row">
                                                    <div class="col-md-7">
                                                        <div class="upload-container text-center">
                                                            <label for="file-upload{{ $entreprise->id }}" class="upload-box">
                                                                <i class="fa fa-cloud-upload"></i>
                                                                <p>Cliquez ici pour choisir le logo</p>
                                                                <input id="file-upload{{ $entreprise->id }}" type="file" name="logo" accept="image/*" hidden>
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-5">
                                                        <div class="preview-container">
                                                            <img id="preview-image{{ $entreprise->id }}"
                                                                src="{{ $entreprise->logo ? asset('storage/' . $entreprise->logo) : '' }}"
                                                                alt="Aperçu de l'image"
                                                                style="display: {{ $entreprise->logo ? 'block' : 'none' }};height:160px">
                                                        </div>
                                                    </div>
                                                </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-dark"
                                                data-bs-dismiss="modal">Fermer</button>
                                            <button type="submit" class="btn btn-primary">Modifier</button>
                                        </div>
                                    </form>

                                    <script>
                                        if ("geolocation" in navigator) {
                                            navigator.geolocation.getCurrentPosition(
                                                function(position) {
                                                    const latitude = position.coords.latitude;
                                                    const longitude = position.coords.longitude;

                                                    console.log("Latitude:", latitude);
                                                    console.log("Longitude:", longitude);

                                                    document.getElementById("latitude").value = latitude;
                                                    document.getElementById("longitude").value = longitude;
                                                },
                                                function(error) {
                                                    console.error("Erreur de géolocalisation :", error.message);
                                                }
                                            );
                                        } else {
                                            alert("La géolocalisation n’est pas prise en charge par ce navigateur.");
                                        }
                                    </script>

                                </div>
                            </div>
                        </div>
                        <script>
                            document.getElementById('file-upload{{ $entreprise->id }}').addEventListener('change', function(event) {
                                const file = event.target.files[0];
                                if (file) {
                                    const reader = new FileReader();
                                    reader.onload = function(e) {
                                        const preview = document.getElementById('preview-image{{ $entreprise->id }}');
                                        preview.src = e.target.result;
                                        preview.style.display = 'block';
                                    };
                                    reader.readAsDataURL(file);
                                }
                            });
                        </script>
                    @endforeach

                </tbody>
            </table>
            <div id="aucun-resultat" style="display: none;">
                <h4 colspan="8" class="text-center text-warning">Aucun pointage trouvé .</h4>
            </div>
        </div>
    </div>

    <script>
        function searchTable() {
            const input = document.getElementById('searchInput').value.toLowerCase();
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
    <script>
        document.getElementById('file-upload').addEventListener('change', function(event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const preview = document.getElementById('preview-image');
                    preview.src = e.target.result;
                    preview.style.display = 'block';
                };
                reader.readAsDataURL(file);
            }
        });
    </script>
    {{-- <script>
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
    </script> --}}
@endsection
