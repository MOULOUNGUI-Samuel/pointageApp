@extends('layouts.master2')
@section('content2')
    <div class="section-admin container-fluid">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="sparkline13-list">
                        <div class="sparkline13-hd">
                            <div class="main-sparkline13-hd">
                                <h1>Products <span class="table-project-n">Data</span> Table</h1>
                            </div>
                        </div>
                        <div class="sparkline13-graph">
                            <div class="datatable-dashv1-list custom-datatable-overright">
                                <div id="toolbar">
                                    <select class="form-control">
                                        <option value="">Exporter de base</option>
                                        <option value="all">Exporter tout</option>
                                        <option value="selected">Exporter sélectionné</option>
                                    </select>
                                </div>
                                <table id="table" data-toggle="table" data-pagination="true" data-search="true"
                                    data-show-columns="true" data-show-pagination-switch="true" data-show-refresh="true"
                                    data-key-events="true" data-show-toggle="true" data-resizable="true" data-cookie="true"
                                    data-cookie-id-table="saveId" data-show-export="true" data-click-to-select="true"
                                    data-toolbar="#toolbar">
                                    <thead>
                                        <tr>
                                            <th data-field="state" data-checkbox="true"></th>
                                            {{-- <th>ID</th> --}}
                                            <th>Nom</th>
                                            <th>Prénom</th>
                                            <th>Matricule</th>
                                            <th>Email</th>
                                            <th>Entreprise</th>
                                            <th>Fonction</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($utilisateurs as $user)
                                            <tr>
                                                <td></td>
                                                {{-- <td>{{ $user->id }}</td> --}}
                                                <td>{{ $user->nom }}</td>
                                                <td>{{ $user->prenom }}</td>
                                                <td>{{ $user->matricule }}</td>
                                                <td>{{ $user->email }}</td>
                                                <td>{{ $user->entreprise->nom_entreprise ?? '-' }}</td>
                                                <td>{{ $user->fonction }}</td>
                                                <td>
                                                    <button class="btn btn-sm btn-info" data-toggle="modal"
                                                        data-target="#detailsMondale{{ $user->id }}">
                                                        <i class="fa fa-eye"></i> Détails
                                                    </button>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>

                            </div>
                            @foreach ($utilisateurs as $user)
                                <div id="detailsMondale{{ $user->id }}"
                                    class="modal modal-adminpro-general default-popup-PrimaryModal fade" role="dialog">
                                    <div class="modal-dialog modal-2xl modal-dialog-scrollable">
                                        <div class="modal-content">
                                            <div class="modal-header header-color-modal bg-color-1">
                                                <h4 class="modal-title">{{ $user->nom }} {{ $user->prenom }}</h4>
                                                <div class="modal-close-area modal-close-df">
                                                    <a class="close" data-dismiss="modal" href="#"><i
                                                            class="fa fa-close"></i></a>
                                                </div>
                                            </div>

                                            <div class="modal-body">
                                                <div class="row text-left">
                                                    @if ($user->date_naissance)
                                                        <div class="col-md-4"><strong>Date
                                                                naissance</strong><br>{{ $user->date_naissance }}</div>
                                                    @endif
                                                    @if ($user->date_embauche)
                                                        <div class="col-md-4"><strong>Date
                                                                embauche</strong><br>{{ $user->date_embauche }}</div>
                                                    @endif
                                                    @if ($user->nationalite)
                                                        <div class="col-md-4">
                                                            <strong>Nationalité</strong><br>{{ $user->nationalite }}</div>
                                                    @endif
                                                    @if ($user->adresse)
                                                        <div class="col-md-4">
                                                            <strong>Adresse</strong><br>{{ $user->adresse }}</div>
                                                    @endif
                                                    @if ($user->telephone)
                                                        <div class="col-md-4">
                                                            <strong>Téléphone</strong><br>{{ $user->telephone }}</div>
                                                    @endif
                                                    @if ($user->email_professionnel)
                                                        <div class="col-md-4"><strong>Email
                                                                professionnel</strong><br>{{ $user->email_professionnel }}
                                                        </div>
                                                    @endif
                                                    @if ($user->etat_civil)
                                                        <div class="col-md-4"><strong>État
                                                                civil</strong><br>{{ $user->etat_civil }}</div>
                                                    @endif
                                                    @if ($user->nombre_enfant)
                                                        <div class="col-md-4"><strong>Nombre
                                                                d'enfants</strong><br>{{ $user->nombre_enfant }}</div>
                                                    @endif
                                                    @if ($user->ville?->nom)
                                                        <div class="col-md-4">
                                                            <strong>Ville</strong><br>{{ $user->ville->nom }}</div>
                                                    @endif
                                                    @if ($user->pays?->nom)
                                                        <div class="col-md-4">
                                                            <strong>Pays</strong><br>{{ $user->pays->nom }}</div>
                                                    @endif
                                                    @if ($user->salaire)
                                                        <div class="col-md-4">
                                                            <strong>Salaire</strong><br>{{ $user->salaire }} FCFA</div>
                                                    @endif
                                                    @if ($user->type_contrat)
                                                        <div class="col-md-4"><strong>Type de
                                                                contrat</strong><br>{{ $user->type_contrat }}</div>
                                                    @endif
                                                    @if ($user->mode_paiement)
                                                        <div class="col-md-4"><strong>Mode de
                                                                paiement</strong><br>{{ $user->mode_paiement }}</div>
                                                    @endif
                                                    @if ($user->iban)
                                                        <div class="col-md-4"><strong>IBAN</strong><br>{{ $user->iban }}
                                                        </div>
                                                    @endif
                                                    @if ($user->bic)
                                                        <div class="col-md-4"><strong>BIC</strong><br>{{ $user->bic }}
                                                        </div>
                                                    @endif
                                                    @if ($user->nom_banque)
                                                        <div class="col-md-4">
                                                            <strong>Banque</strong><br>{{ $user->nom_banque }}</div>
                                                    @endif
                                                    @if ($user->nom_agence)
                                                        <div class="col-md-4">
                                                            <strong>Agence</strong><br>{{ $user->nom_agence }}</div>
                                                    @endif
                                                    @if ($user->competence)
                                                        <div class="col-md-12">
                                                            <strong>Compétences</strong><br>{{ $user->competence }}</div>
                                                    @endif
                                                    @if ($user->commmentaire_completaire)
                                                        <div class="col-md-12">
                                                            <strong>Commentaires</strong><br>{{ $user->commmentaire_completaire }}
                                                        </div>
                                                    @endif
                                                </div>

                                                <hr>

                                                <div class="row mt-3 text-start">
                                                    @if ($user->photo)
                                                        <div class="col-md-4">
                                                            <p><strong>Photo</strong></p>
                                                            <img src="{{ asset('storage/' . $user->photo) }}"
                                                                class="img-thumbnail" width="100">
                                                        </div>
                                                    @endif

                                                    @foreach (['cv', 'permis_conduire', 'piece_identite', 'diplome', 'certificat_travail'] as $doc)
                                                        @if ($user->$doc)
                                                            <div class="col-md-4">
                                                                <p><strong>{{ ucfirst(str_replace('_', ' ', $doc)) }}</strong>
                                                                </p>
                                                                <a href="{{ asset('storage/' . $user->$doc) }}"
                                                                    target="_blank"
                                                                    class="btn btn-outline-primary btn-sm">Télécharger</a>
                                                            </div>
                                                        @endif
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach


                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="container">
            <div class="card-body">
                <div class="justify-content-between align-items-center mb-4">
                    <h2 class="card-title text-primary">Gestion des utilisateurs</h2>
                </div>
            </div>
            <div class="card-body shadow p-3" style="background-color: white;shadow: 0px 0px 10px #ccc;padding: 40px;">
                @if (session('success'))
                    <div class="alert alert-success" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if (session('error'))
                    <div class="alert alert-danger" role="alert">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
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
                <form method="POST" action="{{ route('ajoute_utilisateur') }}" enctype="multipart/form-data"
                    class="px-4 card border rounded shadow-sm bg-light">
                    @csrf
                    <h4 class="mb-3 text-primary">Informations personnelles</h4>
                    <div class="row g-3">
                        <div class="form-group col-md-4">
                            <label class="form-label">Nom</label>
                            <input type="text" name="nom" class="form-control" required autocomplete="false"
                                value="{{ old('nom') }}">
                        </div>
                        <div class="form-group col-md-4">
                            <label class="form-label">Prénom</label>
                            <input type="text" name="prenom" class="form-control" required autocomplete="false"
                                value="{{ old('prenom') }}">
                        </div>
                        <div class="col-md-4">
                            <div class="form-group" id="data_1">
                                <label class="form-label">Date de naissance</label>
                                <div class="input-group date">
                                    <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                    <input type="text" class="form-control" name="date_naissance"
                                        value="{{ old('date_naissance') }}" required data-mask="99/99/9999"
                                        autocomplete="false">
                                </div>
                            </div>
                        </div>
                        <div class="form-group col-md-4">
                            <label class="form-label">Lieu de naissance</label>
                            <input type="text" name="lieu_naissance" class="form-control" autocomplete="false"
                                value="{{ old('lieu_naissance') }}">
                        </div>
                        <div class="form-group col-md-4">
                            <label class="form-label">Nationalité</label>
                            <input type="text" name="nationalite" class="form-control" autocomplete="false"
                                value="{{ old('nationalite') }}">
                        </div>
                        <div class="form-group col-md-4">
                            <label class="form-label">Numéro sécurité sociale</label>
                            <input type="text" name="numero_securite_sociale" class="form-control"
                                autocomplete="false" value="{{ old('numero_securite_sociale') }}">
                        </div>
                        <div class="form-group col-md-4">
                            <label class="form-label">État civil</label>
                            <input type="text" name="etat_civil" class="form-control" autocomplete="false"
                                value="{{ old('etat_civil') }}">
                        </div>
                        <div class="form-group col-md-4">
                            <label class="form-label">Nombre d'enfants</label>
                            <input type="number" name="nombre_enfant" class="form-control" min="0"
                                max="50" value="{{ old('nombre_enfant', 0) }}">
                        </div>
                        <div class="form-group col-md-4">
                            <label class="form-label">Adresse</label>
                            <input type="text" name="adresse" class="form-control" required autocomplete="false"
                                value="{{ old('adresse') }}">
                        </div>
                        <div class="form-group col-md-4">
                            <label class="form-label">Adresse complémentaire</label>
                            <input type="text" name="adresse_complementaire" class="form-control"
                                autocomplete="false" value="{{ old('adresse_complementaire') }}">
                        </div>
                        <div class="form-group col-md-4">
                            <label class="form-label">Code postal</label>
                            <input type="text" name="code_postal" class="form-control" data-mask="99999"
                                autocomplete="false" value="{{ old('code_postal') }}">
                        </div>
                        <div class="form-group col-md-4">
                            <label class="form-label">Téléphone</label>
                            <input type="text" name="telephone" class="form-control" required
                                data-mask="999 99 99 99" autocomplete="false" value="{{ old('telephone') }}">
                        </div>
                        <div class="form-group col-md-4">
                            <label class="form-label">Email personnel</label>
                            <input type="email" name="email" class="form-control" autocomplete="false"
                                value="{{ old('email') }}">
                        </div>
                        <div class="form-group  col-md-4">
                            <label class="form-label">Pays</label>
                            <select class="select2_demo_3 form-control" name="pays_id" style="width: 100%;">
                                <option value=""></option>
                                @foreach ($pays as $Lepays)
                                    <option value="{{ $Lepays->id }}"
                                        {{ old('pays_id') == $Lepays->id ? 'selected' : '' }}>{{ $Lepays->nom_pays }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group  col-md-4">
                            <label class="form-label">Ville</label>
                            <select class="select2_demo_3 form-control" name="ville_id" style="width: 100%;">
                                <option value=""></option>
                                @foreach ($villes as $ville)
                                    <option value="{{ $ville->id }}"
                                        {{ old('ville_id') == $ville->id ? 'selected' : '' }}>{{ $ville->nom_ville }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <h4 class="mt-4 mb-3 text-primary">Informations professionnelles</h4>
                    <div class="row g-3">
                        <div class="form-group  col-md-4">
                            <label class="form-label">Entreprise</label>
                            <select class="select2_demo_3 form-control" name="entreprise_id" style="width: 100%;">
                                <option value=""></option>
                                @foreach ($entreprises as $entreprise)
                                    <option value="{{ $entreprise->id }}"
                                        {{ old('entreprise_id') == $entreprise->id ? 'selected' : '' }}>
                                        {{ $entreprise->nom_entreprise }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group  col-md-4">
                            <label class="form-label">Service</label>
                            <select class="select2_demo_3 form-control" name="service_id" style="width: 100%;">
                                <option value=""></option>
                                @foreach ($services as $service)
                                    <option value="{{ $service->id }}"
                                        {{ old('service_id') == $service->id ? 'selected' : '' }}>
                                        {{ $service->nom_service }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group  col-md-4">
                            <label class="form-label">categoriepr ofessionelle</label>
                            <select class="select2_demo_3 form-control" name="categorie_professionel_id "
                                style="width: 100%;">
                                <option value=""></option>
                                @foreach ($categorie_professionelles as $categorie_professionel)
                                    <option value="{{ $categorie_professionel->id }}"
                                        {{ old('categorie_professionel_id ') == $categorie_professionel->id ? 'selected' : '' }}>
                                        {{ $categorie_professionel->nom_categorie_professionnelle }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-md-4">
                            <label class="form-label">Poste/Fonction</label>
                            <input type="text" name="fonction" class="form-control" value="{{ old('fonction') }}">
                        </div>
                        <div class="form-group col-md-4">
                            <label class="form-label">Matricule</label>
                            <input type="text" name="matricule" class="form-control" required autocomplete="false"
                                value="{{ old('matricule') }}">
                        </div>
                        <div class="form-group col-md-4">
                            <label class="form-label">Email professionnel</label>
                            <input type="email" name="email_professionnel" class="form-control" autocomplete="false"
                                value="{{ old('email_professionnel') }}">
                        </div>
                        <div class="form-group col-md-4">
                            <label class="form-label">Téléphone professionnel</label>
                            <input type="text" name="telephone_professionnel" class="form-control"
                                data-mask="999 99 99 99" autocomplete="false"
                                value="{{ old('telephone_professionnel') }}">
                        </div>

                        <div class="col-md-4">
                            <div class="form-group" id="data_1">
                                <label class="form-label">Date d'embauche</label>
                                <div class="input-group date">
                                    <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                    <input type="text" class="form-control" name="date_embauche"
                                        value="{{ old('date_embauche') }}" required data-mask="99/99/9999"
                                        autocomplete="false">
                                </div>
                            </div>
                        </div>
                        <div class="form-group col-md-4">
                            <label class="form-label">Rôle</label>
                            <select class="select2_demo_3 form-control" name="role_id" style="width: 100%;">
                                <option value=""></option>
                                @foreach ($roles as $role)
                                    <option value="{{ $role->id }}"
                                        {{ old('role_id') == $role->id ? 'selected' : '' }}>{{ $role->nom }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group  col-md-4">
                            <label class="form-label">Responsable hiérarchique</label>
                            <select class="select2_demo_3 form-control" name="superieur_hierarchique"
                                style="width: 100%;">
                                <option value=""></option>
                                @foreach ($users as $user)
                                    <option value="{{ $user->nom }} {{ $user->prenom }}"
                                        {{ old('superieur_hierarchique') == $user->nom . ' ' . $user->prenom ? 'selected' : '' }}>
                                        {{ $user->nom }} {{ $user->prenom }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-md-4">
                            <label class="form-label">Niveau d'étude</label>
                            <input type="text" name="niveau_etude" class="form-control" autocomplete="false"
                                value="{{ old('niveau_etude') }}">
                        </div>
                        <div class="form-group col-md-4">
                            <label class="form-label">Compétences</label>
                            <textarea name="competence" class="form-control">{{ old('competence') }}</textarea>
                        </div>
                    </div>

                    <h4 class="mt-4 mb-3 text-primary">Informations de rémunération</h4>
                    <div class="row g-3">
                        <div class="form-group col-md-4">
                            <label class="form-label">Salaire</label>
                            <input type="number" step="0.01" name="salaire" class="form-control"
                                autocomplete="false" value="{{ old('salaire') }}">
                        </div>
                        <div class="form-group col-md-4">
                            <label class="form-label">Type de contrat</label>
                            <input type="text" name="type_contrat" class="form-control" autocomplete="false"
                                value="{{ old('type_contrat') }}">
                        </div>
                        <div class="form-group col-md-4">
                            <label class="form-label">Mode de paiement</label>
                            <input type="text" name="mode_paiement" class="form-control" autocomplete="false"
                                value="{{ old('mode_paiement') }}">
                        </div>
                        <div class="form-group col-md-4">
                            <label class="form-label">IBAN</label>
                            <input type="text" name="iban" class="form-control" autocomplete="false"
                                value="{{ old('iban') }}">
                        </div>
                        <div class="form-group col-md-4">
                            <label class="form-label">BIC</label>
                            <input type="text" name="bic" class="form-control" autocomplete="false"
                                value="{{ old('bic') }}">
                        </div>
                        <div class="form-group col-md-4">
                            <label class="form-label">Titulaire du compte</label>
                            <input type="text" name="titulaire_compte" class="form-control" autocomplete="false"
                                value="{{ old('titulaire_compte') }}">
                        </div>
                        <div class="form-group col-md-4">
                            <label class="form-label">Banque</label>
                            <input type="text" name="nom_banque" class="form-control" autocomplete="false"
                                value="{{ old('nom_banque') }}">
                        </div>
                        <div class="form-group col-md-4">
                            <label class="form-label">Agence</label>
                            <input type="text" name="nom_agence" class="form-control" autocomplete="false"
                                value="{{ old('nom_agence') }}">
                        </div>
                    </div>

                    <h4 class="mt-4 mb-3 text-primary">Documents administratifs</h4>
                    <div class="row g-3">
                        <div class="form-group col-md-4">
                            <label class="form-label">Photo</label>
                            <input type="file" name="photo" class="form-control">
                        </div>
                        <div class="form-group col-md-4">
                            <label class="form-label">CV</label>
                            <input type="file" name="cv" class="form-control">
                        </div>
                        <div class="form-group col-md-4">
                            <label class="form-label">Permis de conduire</label>
                            <input type="file" name="permis_conduire" class="form-control">
                        </div>
                        <div class="form-group col-md-4">
                            <label class="form-label">Pièce d'identité</label>
                            <input type="file" name="piece_identite" class="form-control">
                        </div>
                        <div class="form-group col-md-4">
                            <label class="form-label">Diplôme</label>
                            <input type="file" name="diplome" class="form-control">
                        </div>
                        <div class="form-group col-md-4">
                            <label class="form-label">Certificat de travail</label>
                            <input type="file" name="certificat_travail" class="form-control">
                        </div>
                    </div>

                    <h4 class="mt-4 mb-3  text-primary">Informations complémentaires</h4>
                    <div class="row g-3">
                        <div class="form-group col-md-4">
                            <label class="form-label">Nom du contact</label>
                            <input type="text" name="nom_completaire" class="form-control" autocomplete="false"
                                value="{{ old('nom_completaire') }}">
                        </div>
                        <div class="form-group col-md-4">
                            <label class="form-label">Lien de parenté</label>
                            <input type="text" name="lien_completaire" class="form-control" autocomplete="false"
                                value="{{ old('lien_completaire') }}">
                        </div>
                        <div class="form-group col-md-4">
                            <label class="form-label">Contact d'urgence</label>
                            <input type="text" name="contact_completaire" class="form-control"
                                data-mask="999 99 99 99" autocomplete="false" value="{{ old('contact_completaire') }}">
                        </div>
                        <div class="form-group col-md-4">
                            <label class="form-label">Formation complémentaire</label>
                            <input type="text" name="formation_completaire" class="form-control" autocomplete="false"
                                value="{{ old('formation_completaire') }}">
                        </div>
                        <div class="form-group col-md-4">
                            <label class="form-label">Commentaire</label>
                            <textarea name="commmentaire_completaire" class="form-control">{{ old('commmentaire_completaire') }}</textarea>
                        </div>
                        <div class="form-group col-md-4">
                            <label class="form-label">Mot de passe</label>
                            <input type="password" name="password" class="form-control" autocomplete="false">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal"
                            style="color:black">Fermer</button>
                        <button type="submit" class="btn btn-primary">Enregistrer</button>
                    </div>
                </form>
            </div>

        </div>
    </div>
    <!-- End Email Statistic area-->
@endsection
