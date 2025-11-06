@extends('layouts.master2')
@section('content2')
    <style>
        .container-custom {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border-radius: 15px;
            padding: 20px;
            text-align: left;
            max-width: 100%;
            width: 100%;
            color: white;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.2);
        }

        .date-header {
            border: 1px solid rgba(245, 247, 249, 0.8);
            padding: 6px 12px;
            border-radius: 6px;
            display: inline-block;
            margin-bottom: 15px;
        }

        .date-header1 {
            border: 1px solid white;
            padding: 6px 12px;
            border-radius: 6px;
            display: inline-block;
        }

        .time-block {
            border-bottom: 1px solid rgba(255, 255, 255, 0.2);
            padding-bottom: 10px;
            margin-bottom: 10px;
        }


        .btn-gradient {
            background: linear-gradient(135deg, #3f81b3d8, #d0d7db);
            border: none;
            border-radius: 10px;
            padding: 10px 24px;
            color: white;
            font-size: 16px;
            font-weight: bold;
            transition: all 0.3s ease-in-out;
            /* box-shadow: 0px 0px 20px rgba(255, 255, 255, 0.4); Grand shadow blanc */
        }


        .btn-gradient:hover {
            opacity: 0.8;
            transform: scale(1.05);
        }
    </style>

    <!-- Settings Menu -->
    <div class="card">
        <div class="card-body pb-0 pt-2">
            <div class="d-flex justify-content-between align-items-center py-2">
                <ul class="nav nav-tabs nav-tabs-bottom">
                    <li class="nav-item me-3">
                        <a href="profile.html" class="nav-link px-0 active" id="order-tab" data-bs-toggle="tab"
                            data-bs-target="#parametreConfig" type="button" role="tab" aria-controls="home-tab-pane"
                            aria-selected="true">
                            <i class="ti ti-settings-cog me-2"></i>Paramètres généraux
                        </a>
                    </li>
                    <li class="nav-item me-3">
                        <a href="company-settings.html" class="nav-link px-0" id="order-tab1" data-bs-toggle="tab"
                            data-bs-target="#suivi_absence" type="button" role="tab" aria-controls="home-tab-pane"
                            aria-selected="true">
                            <i class="ti ti-world-cog me-2"></i>Suivi de présence
                        </a>
                    </li>
                </ul>
                <div class="">
                    <a href="{{ route('liste_presence') }}" class="btn-action btn btn-primary"
                        data-loader-target="fiche-utilisateur"><i class="fa fa-list me-2"></i>Fiche
                        de
                        pointage</a>
                    <!-- Bouton de chargement (caché au départ) -->
                    <button type="button" id="fiche-utilisateur" class="btn btn-outline-primary" style="display: none;"
                        disabled>
                        <i class="fas fa-spinner fa-spin me-2"></i>Chargement...
                    </button>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="tab-content" id="myTabContent">
            <!-- Settings Info -->
            <div class="tab-pane fade show active text-muted" id="parametreConfig" role="tabpanel"
                aria-labelledby="home-tab-1">
                <div class="card">
                    <div class="card-body">
                        <h4 class="fw-semibold mb-3">Paramètres généraux</h4>
                        <form method="POST" action="{{ route('modifier_utilisateur', $user->id) }}"
                            enctype="multipart/form-data" class="px-4 card border rounded shadow-sm bg-light">
                            @csrf
                            @method('PUT')
                            <div class="d-flex justify-content-between  border-bottom mb-3 pb-3">
                                <div>
                                    <h5 class="fw-semibold mb-1">{{ $user->nom }} {{ $user->prenom }}</h5>
                                    <p>{{ $user->fonction }}</p>
                                </div>
                                <div class="mb-3 align-items-center">
                                    <div class="profile-upload">
                                        <div class="profile-upload-img">
                                            <span><i class="ti ti-photo"></i></span>
                                            <img id="ImgPreview" src="assets/img/profiles/avatar-02.jpg" alt="img"
                                                class="preview1">
                                            <button type="button" id="removeImage1" class="profile-remove">
                                                <i class="feather-x"></i>
                                            </button>
                                        </div>
                                        <div class="profile-upload-content">
                                            <label class="profile-upload-btn">
                                                <i class="ti ti-file-broken"></i> Upload File
                                                <input type="file" id="imag" class="input-img">
                                            </label>
                                            <p>JPG, GIF or PNG. Max size of 800K</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @if (session('success'))
                                <div class="alert alert-success alert-dismissible fade show" role="alert">
                                    {{ session('success') }}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"
                                        aria-label="Close"></button>
                                </div>
                            @endif

                            @if ($errors->any())
                                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    <ul class="mb-0">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"
                                        aria-label="Close"></button>
                                </div>
                            @endif
                            <div class="border-bottom mb-3">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label class="form-label">Nom <span class="text-danger">*</span></label>
                                            <input type="text" name="nom" class="form-control"
                                                value="{{ old('nom', $user->nom) }}">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label class="form-label">Prénom <span class="text-danger">*</span></label>
                                            <input type="text" name="prenom" class="form-control"
                                                value="{{ old('prenom', $user->prenom) }}">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label class="form-label">Matricule</label>
                                            <input type="text" name="matricule" class="form-control"
                                                value="{{ old('matricule', $user->matricule) }}">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label class="form-label">Mot de passe</label>
                                            <input type="password" name="password" class="form-control"
                                                autocomplete="new-password">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label class="form-label">Email <span class="text-danger">*</span></label>
                                            <input type="email" name="email" class="form-control"
                                                value="{{ old('email', $user->email) }}">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label class="form-label">Email professionnel</label>
                                            <input type="email" name="email_professionnel" class="form-control"
                                                value="{{ old('email_professionnel', $user->email_professionnel) }}">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label class="form-label">Date de naissance</label>
                                            <input type="date" name="date_naissance" class="form-control"
                                                value="{{ old('date_naissance', \Carbon\Carbon::parse($user->date_naissance)->format('d/m/Y')) }}">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label class="form-label">Date d'embauche(<span class="text-danger">non
                                                    modifiable</span>)</label>
                                            <input type="date" name="date_embauche" class="form-control"
                                                value="{{ old('date_embauche', \Carbon\Carbon::parse($user->date_embauche)->format('d/m/Y')) }}"
                                                readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label class="form-label">Date fin de contrat(<span class="text-danger">non
                                                    modifiable</span>)</label>
                                            <input type="date" name="date_fin_contrat" class="form-control"
                                                value="{{ old('date_fin_contrat', \Carbon\Carbon::parse($user->date_fin_contrat)->format('d/m/Y')) }}"
                                                readonly>
                                        </div>
                                    </div>

                                    <input type="hidden" name="service_id" class="form-control"
                                        value="{{ old('service_id', $user->service_id) }}">
                                    <input type="hidden" name="role_id" class="form-control"
                                        value="{{ old('role_id', $user->role_id) }}">
                                    <input type="hidden" name="ville_id" class="form-control"
                                        value="{{ old('ville_id', $user->ville_id) }}">
                                    <input type="hidden" name="pays_id" class="form-control"
                                        value="{{ old('pays_id', $user->pays_id) }}">
                                    <input type="hidden" name="categorie_professionel_id" class="form-control"
                                        value="{{ old('categorie_professionel_id', $user->categorie_professionel_id) }}">
                                    <input type="hidden" name="type_contrat" class="form-control"
                                        value="{{ old('type_contrat', $user->type_contrat) }}">
                                    <input type="hidden" name="salaire" class="form-control"
                                        value="{{ old('salaire', $user->salaire) }}">
                                    <input type="hidden" name="mode_paiement" class="form-control"
                                        value="{{ old('mode_paiement', $user->mode_paiement) }}">
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label class="form-label">Lieu de naissance</label>
                                            <input type="text" name="lieu_naissance" class="form-control"
                                                value="{{ old('lieu_naissance', $user->lieu_naissance) }}">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label class="form-label">Nationalité (<span class="text-danger">non
                                                    modifiable</span>)</label>
                                            <input type="text" name="nationalite" class="form-control"
                                                value="{{ old('nationalite', $user->nationalite) }}" readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label class="form-label">N° Sécurité Sociale(<span class="text-danger">non
                                                    modifiable</span>)</label>
                                            <input type="text" name="numero_securite_sociale" class="form-control"
                                                value="{{ old('numero_securite_sociale', $user->numero_securite_sociale) }}"
                                                readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label class="form-label">État civil (<span class="text-danger">non
                                                    modifiable</span>)</label>
                                            <input type="text" name="etat_civil" class="form-control"
                                                value="{{ old('etat_civil', $user->etat_civil) }}" readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label class="form-label">Nombre d'enfants</label>
                                            <input type="number" name="nombre_enfant" class="form-control"
                                                value="{{ old('nombre_enfant', $user->nombre_enfant) }}">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label class="form-label">Adresse</label>
                                            <input type="text" name="adresse" class="form-control"
                                                value="{{ old('adresse', $user->adresse) }}">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label class="form-label">Adresse complémentaire</label>
                                            <input type="text" name="adresse_complementaire" class="form-control"
                                                value="{{ old('adresse_complementaire', $user->adresse_complementaire) }}">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label class="form-label">Code postal</label>
                                            <input type="text" name="code_postal" class="form-control"
                                                value="{{ old('code_postal', $user->code_postal) }}">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label class="form-label">Supérieur hiérarchique (<span
                                                    class="text-danger">non modifiable</span>)</label>
                                            <input type="text" name="superieur_hierarchique" class="form-control"
                                                value="{{ old('superieur_hierarchique', $user->superieur_hierarchique) }}"
                                                readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label class="form-label">Niveau d'étude</label>
                                            <input type="text" name="niveau_etude" class="form-control"
                                                value="{{ old('niveau_etude', $user->niveau_etude) }}">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label class="form-label">Compétence</label>
                                            <textarea name="competence" class="form-control">{{ old('competence', $user->competence) }}</textarea>
                                        </div>
                                    </div>



                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label class="form-label">IBAN</label>
                                            <input type="text" name="iban" class="form-control"
                                                value="{{ old('iban', $user->iban) }}">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label class="form-label">BIC</label>
                                            <input type="text" name="bic" class="form-control"
                                                value="{{ old('bic', $user->bic) }}">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label class="form-label">Titulaire du compte</label>
                                            <input type="text" name="titulaire_compte" class="form-control"
                                                value="{{ old('titulaire_compte', $user->titulaire_compte) }}">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label class="form-label">Nom de la banque</label>
                                            <input type="text" name="nom_banque" class="form-control"
                                                value="{{ old('nom_banque', $user->nom_banque) }}">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label class="form-label">Nom de l'agence</label>
                                            <input type="text" name="nom_agence" class="form-control"
                                                value="{{ old('nom_agence', $user->nom_agence) }}">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label class="form-label">Téléphone</label>
                                            <input type="text" name="telephone" class="form-control"
                                                value="{{ old('telephone', $user->telephone) }}">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label class="form-label">Téléphone professionnel</label>
                                            <input type="text" name="telephone_professionnel" class="form-control"
                                                value="{{ old('telephone_professionnel', $user->telephone_professionnel) }}">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label class="form-label">Fonction (<span class="text-danger">non
                                                    modifiable</span>)</label>
                                            <input type="text" name="fonction" class="form-control"
                                                value="{{ old('fonction', $user->fonction) }}" readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label class="form-label">CV</label>
                                            <input type="file" name="cv" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label class="form-label">Permis de conduire</label>
                                            <input type="file" name="permis_conduire" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label class="form-label">Pièce d'identité</label>
                                            <input type="file" name="piece_identite" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label class="form-label">Diplôme</label>
                                            <input type="file" name="diplome" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label class="form-label">Certificat de travail</label>
                                            <input type="file" name="certificat_travail" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label class="form-label">Nom complémentaire</label>
                                            <input type="text" name="nom_completaire" class="form-control"
                                                value="{{ old('nom_completaire', $user->nom_completaire) }}">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label class="form-label">Lien complémentaire</label>
                                            <input type="text" name="lien_completaire" class="form-control"
                                                value="{{ old('lien_completaire', $user->lien_completaire) }}">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label class="form-label">Contact complémentaire</label>
                                            <input type="text" name="contact_completaire" class="form-control"
                                                value="{{ old('contact_completaire', $user->contact_completaire) }}">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label class="form-label">Formation complémentaire</label>
                                            <input type="text" name="formation_completaire" class="form-control"
                                                value="{{ old('formation_completaire', $user->formation_completaire) }}">
                                        </div>
                                    </div>
                                    <div class="col-md-8">
                                        <div class="mb-3">
                                            <label class="form-label">Commentaire complémentaire</label>
                                            <textarea name="commmentaire_completaire" class="form-control">{{ old('commmentaire_completaire', $user->commmentaire_completaire) }}</textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div style="text-align:right">
                                <button type="submit" class="btn btn-primary"
                                    data-loader-target="modif-utilisateur">Enregistrer les modifications</button>
                                <!-- Bouton de chargement (caché au départ) -->
                                <button type="button" id="modif-utilisateur" class="btn btn-outline-primary"
                                    style="display: none;" disabled>
                                    <i class="fas fa-spinner fa-spin me-2"></i>Chargement...
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="tab-pane fade text-muted" id="suivi_absence" role="tabpanel" aria-labelledby="profile-tab-2"
                tabindex="0">
                <div class="bg-white shadow p-2">
                    <!-- /Search -->
                    <div class="d-flex justify-content-between  border-bottom mb-3 pb-3">
                        <div>
                            <h5 class="fw-semibold mb-1">{{ $user->nom }} {{ $user->prenom }}</h5>
                            <p>{{ $user->fonction }}</p>
                            <div class="icon-form mb-3 mb-sm-0">
                                <span class="form-icon"><i class="ti ti-search"></i></span>
                                <input type="text" onkeyup="searchTable1()" id="searchInput1" class="form-control"
                                    placeholder="Rechercher..." style="width: 500px">
                            </div>
                        </div>
                        <div class="row align-items-center mt-4">
                            <div class="col-6">
                                <div class="align-items-center">
                                    <label for="filtre-date" class="me-2 mb-0">Date début :</label>
                                    <input type="date" id="filtre-date" name="date_debut" class="form-control"
                                        style="width: 300px">
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="align-items-center">
                                    <label for="filtre-date1" class="me-2 mb-0">Date fin :</label>
                                    <input type="date" id="filtre-date1" name="date_fin" class="form-control"
                                        style="width: 300px">
                                </div>
                            </div>
                        </div>
                        <div class="mb-3 align-items-center">
                            <div class="profile-upload">
                                <div class="profile-upload-img">
                                    <img id="ImgPreview" src="{{ asset('storage/' . $module_logo) }}" alt="img"
                                        style="height: 100%;width:100%">
                                </div>
                            </div>
                        </div>
                    </div>
                    <script>
                        function searchTable1() {
                            const input = document.getElementById('searchInput1').value.toLowerCase();
                            const rows = document.querySelectorAll('#approbationsTable');

                            rows.forEach(row => {
                                const rowText = row.textContent.toLowerCase();
                                row.style.display = rowText.includes(input) ? '' : 'none';
                            });

                            const message = document.getElementById('aucun-resultat');
                            const visibleRows = Array.from(rows).filter(row => row.style.display !== 'none');
                            message.style.display = visibleRows.length === 0 ? 'block' : 'none';
                        }
                    </script>
                    <div class="card-body">
                        <div class="container-custom">
                            <div class="row" id="approbationsTable">
                                @foreach ($Pointages as $Pointage)
                                    <div class="col-md-4">
                                        <div class="pointage-item{{ $Pointage->id }}"
                                            data-status="{{ $Pointage->date_arriver }}">
                                            <div class="date-header shadow-sm text-capitalize text-primary"
                                                style="font-size: 19px">
                                                {{ \App\Helpers\DateHelper::convertirDateEnTexte(App\Helpers\DateHelper::convertirDateFormat($Pointage->date_arriver)) }}
                                            </div>

                                            <div class="time-block">
                                                <div class="mb-2">
                                                    <div class="d-flex justify-content-between">
                                                        <span class="text-success" style="font-size: 19px">
                                                            <i class="icon-enter text-success me-2"></i> Entrée :
                                                            {{ $Pointage->heure_arriver }}
                                                        </span>
                                                        @if ($Pointage->heure_arriver > $Pointage->user->entreprise->heure_ouverture)
                                                            <span class="text-danger" style="font-size: 20px">(en
                                                                retard)</span>
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="mb-2" style="font-size: 19px;margin-top:5px">
                                                    <i class="icon-exit me-2" style="color:darkorange"></i>
                                                    <span style="color:darkorange"> Sortie :
                                                        {{ $Pointage->heure_fin ?? '-- : -- : --' }}
                                                    </span>
                                                </div>
                                            </div>



                                            @if (!empty($cause_sorties[$Pointage->id]))
                                                <div class="shadow-sm mb-3">
                                                    <button type="button" class="btn btn-primary" data-toggle="modal"
                                                        data-target="#floatingLabelsModal{{ $Pointage->id }}">
                                                        Sorties intermédiaires
                                                    </button>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                    <script>
                                        function filtrerParPeriode() {
                                            const dateDebut = document.getElementById('filtre-date').value;
                                            const dateFin = document.getElementById('filtre-date1').value;
                                            const items = document.querySelectorAll('.pointage-item{{ $Pointage->id }}');
                                            let matchCount = 0;

                                            items.forEach(item => {
                                                const itemDate = item.getAttribute('data-status'); // format YYYY-MM-DD

                                                if (
                                                    (!dateDebut || itemDate >= dateDebut) &&
                                                    (!dateFin || itemDate <= dateFin)
                                                ) {
                                                    item.style.display = 'block';
                                                    matchCount++;
                                                } else {
                                                    item.style.display = 'none';
                                                }
                                            });


                                        }

                                        // ⚡ Déclenchement automatique du filtrage
                                        document.getElementById('filtre-date').addEventListener('change', filtrerParPeriode);
                                        document.getElementById('filtre-date1').addEventListener('change', filtrerParPeriode);
                                    </script>
                                @endforeach
                            </div>

                            <div id="aucun-resultat" class="alert alert-warning text-center" style="display: none;">
                                Aucun résultat trouvé.
                            </div>
                        </div>
                    </div>
                    @foreach ($Pointages as $Pointage)
                        @if (!empty($cause_sorties[$Pointage->id]))
                            <div class="modal fade" id="floatingLabelsModal{{ $Pointage->id }}" tabindex="-1"
                                role="dialog" aria-labelledby="floatingLabelsModalLabel" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header bg-primary">
                                            <h4 class="modal-title text-white"
                                                id="floatingLabelsModal{{ $Pointage->id }}">
                                                <i class="bi bi-calendar-event"></i>
                                                <div class="date-header1 shadow-sm text-capitalize">
                                                    {{ \App\Helpers\DateHelper::convertirDateEnTexte(App\Helpers\DateHelper::convertirDateFormat($Pointage->date_arriver)) }}
                                                </div>
                                            </h4>
                                        </div>
                                        <div class="modal-body">
                                            <div class="row" style="margin-left: 5px">
                                                @foreach ($cause_sorties[$Pointage->id] as $sortie)
                                                    <div class="col-md-6">
                                                        <div class="time-block">
                                                            <div class="mb-2" style="font-size: 20px">
                                                                <i class="icon-exit me-2" style="color:darkorange"></i>
                                                                <span style="color:darkorange">Sortie :
                                                                    {{ $sortie['pointage_intermediaire']->heure_sortie }}</span>
                                                            </div>
                                                            <div class="mb-2" style="font-size: 20px">
                                                                <i class="icon-enter text-success me-2"></i>
                                                                <span class="text-success">Entrée :
                                                                    {{ $sortie['pointage_intermediaire']->heure_entrer ?? '-- : -- : --' }}</span>
                                                            </div>

                                                            @if (!empty($sortie['descriptions']))
                                                                <div class="mb-2"
                                                                    style="margin-bottom: 5px;margin-top:5px">
                                                                    <small
                                                                        style="color:rgb(255, 255, 255); cursor: pointer;font-size: 15px;padding:5px; background:rgb(169, 169, 169);border-radius:30px"
                                                                        data-toggle="collapse"
                                                                        data-target="#motifSortie{{ $loop->index }}">
                                                                        Motif de sortie :
                                                                    </small>
                                                                </div>

                                                                <div id="motifSortie{{ $loop->index }}"
                                                                    class="collapse mb-2"
                                                                    style="background-color: transparent; border-radius:5px; border:1px solid #0d0d0efa">
                                                                    <ul class="list-unstyled"
                                                                        style="padding-left: 15px; margin: 0;">
                                                                        @foreach ($sortie['descriptions'] as $description)
                                                                            <li
                                                                                style="margin-bottom: 5px; color: black; font-size: 15px">
                                                                                ◉
                                                                                <small>{{ $description->description }}</small>
                                                                            </li>
                                                                        @endforeach
                                                                    </ul>
                                                                </div>
                                                            @endif
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-primary"
                                                data-dismiss="modal">Fermer</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    @endforeach
                </div>
            </div>

        </div>
    </div>
@endsection
