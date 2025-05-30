@extends('layouts.master2')
@section('content2')
    <div class="section-admin container-fluid">

        <div class="container">
            <div class="card-body">
                <div class="justify-content-between align-items-center mb-4">
                    <h2 class="card-title text-primary">Modification : {{ $utilisateur->nom }} {{ $utilisateur->prenom }}</h2>
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
                <form method="POST" action="{{ route('modifier_utilisateur', $utilisateur->id) }}"
                    enctype="multipart/form-data" class="px-4 card border rounded shadow-sm bg-light">
                    @csrf
                    @method('PUT')
                    <h4 class="mb-3 text-primary">Informations personnelles</h4>
                    <div class="row g-3">
                        <div class="form-group col-md-4">
                            <label class="form-label">Nom</label>
                            <input type="text" name="nom" class="form-control" required autocomplete="false"
                                value="{{ old('nom', $utilisateur->nom) }}">
                        </div>
                        <div class="form-group col-md-4">
                            <label class="form-label">Prénom</label>
                            <input type="text" name="prenom" class="form-control" required autocomplete="false"
                                value="{{ old('prenom', $utilisateur->prenom) }}">
                        </div>
                        <div class="col-md-4">
                            <div class="form-group" id="data_1">
                                <label class="form-label">Date de naissance</label>
                                <div class="input-group date">
                                    <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                    <input type="text" class="form-control" name="date_naissance"
                                    value="{{ old('date_naissance', \Carbon\Carbon::parse($utilisateur->date_naissance)->format('d/m/Y')) }}"
                                        data-mask="99/99/9999" autocomplete="false">
                                </div>
                            </div>
                        </div>
                        <div class="form-group col-md-4">
                            <label class="form-label">Lieu de naissance</label>
                            <input type="text" name="lieu_naissance" class="form-control" autocomplete="false"
                                value="{{ old('lieu_naissance', $utilisateur->lieu_naissance) }}">
                        </div>
                        <div class="form-group col-md-4">
                            <label class="form-label">Nationalité</label>
                            <input type="text" name="nationalite" class="form-control" autocomplete="false"
                                value="{{ old('nationalite', $utilisateur->nationalite) }}">
                        </div>
                        <div class="form-group col-md-4">
                            <label class="form-label">Numéro sécurité sociale</label>
                            <input type="text" name="numero_securite_sociale" class="form-control" autocomplete="false"
                                value="{{ old('numero_securite_sociale', $utilisateur->numero_securite_sociale) }}">
                        </div>
                        <div class="form-group col-md-4">
                            <label class="form-label">État civil</label>
                            <input type="text" name="etat_civil" class="form-control" autocomplete="false"
                                value="{{ old('etat_civil', $utilisateur->etat_civil) }}">
                        </div>
                        <div class="form-group col-md-4">
                            <label class="form-label">Nombre d'enfants</label>
                            <input type="number" name="nombre_enfant" class="form-control" min="0" max="50"
                                value="{{ old('nombre_enfant', $utilisateur->nombre_enfant) }}">
                        </div>
                        <div class="form-group col-md-4">
                            <label class="form-label">Adresse</label>
                            <input type="text" name="adresse" class="form-control" required autocomplete="false"
                                value="{{ old('adresse', $utilisateur->adresse) }}">
                        </div>
                        <div class="form-group col-md-4">
                            <label class="form-label">Adresse complémentaire</label>
                            <input type="text" name="adresse_complementaire" class="form-control" autocomplete="false"
                                value="{{ old('adresse_complementaire', $utilisateur->adresse_complementaire) }}">
                        </div>
                        <div class="form-group col-md-4">
                            <label class="form-label">Code postal</label>
                            <input type="text" name="code_postal" class="form-control" data-mask="99999"
                                autocomplete="false" value="{{ old('code_postal', $utilisateur->code_postal) }}">
                        </div>
                        <div class="form-group col-md-4">
                            <label class="form-label">Téléphone</label>
                            <input type="text" name="telephone" class="form-control" required
                                data-mask="999 99 99 99" autocomplete="false"
                                value="{{ old('telephone', $utilisateur->telephone) }}">
                        </div>
                        <div class="form-group col-md-4">
                            <label class="form-label">Email personnel</label>
                            <input type="email" name="email" class="form-control" autocomplete="false"
                                value="{{ old('email', $utilisateur->email) }}">
                        </div>
                        <div class="form-group  col-md-4">
                            <label class="form-label">Pays</label>
                            <select class="select2_demo_3 form-control" name="pays_id" style="width: 100%;">
                                <option value=""></option>
                                @foreach ($pays as $Lepays)
                                    <option value="{{ $Lepays->id }}"
                                        {{ old('pays_id', $utilisateur->pays_id) == $Lepays->id ? 'selected' : '' }}>
                                        {{ $Lepays->nom_pays }}
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
                                        {{ old('ville_id', $utilisateur->ville_id) == $ville->id ? 'selected' : '' }}>
                                        {{ $ville->nom_ville }}
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
                                        {{ old('entreprise_id', $utilisateur->entreprise_id) == $entreprise->id ? 'selected' : '' }}>
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
                                        {{ old('service_id', $utilisateur->service_id) == $service->id ? 'selected' : '' }}>
                                        {{ $service->nom_service }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group  col-md-4">
                            <label class="form-label">Catégorie professionnelle</label>
                            <select class="select2_demo_3 form-control" name="categorie_professionel_id"
                                style="width: 100%;">
                                <option value=""></option>
                                @foreach ($categorie_professionelles as $categorie_professionel)
                                    <option value="{{ $categorie_professionel->id }}"
                                        {{ old('categorie_professionel_id', $utilisateur->categorie_professionel_id) == $categorie_professionel->id ? 'selected' : '' }}>
                                        {{ $categorie_professionel->nom_categorie_professionnelle }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-md-4">
                            <label class="form-label">Poste/Fonction</label>
                            <input type="text" name="fonction" class="form-control"
                                value="{{ old('fonction', $utilisateur->fonction) }}">
                        </div>
                        <div class="form-group col-md-4">
                            <label class="form-label">Matricule</label>
                            <input type="text" name="matricule" class="form-control" required autocomplete="false"
                                value="{{ old('matricule', $utilisateur->matricule) }}">
                        </div>
                        <div class="form-group col-md-4">
                            <label class="form-label">Email professionnel</label>
                            <input type="email" name="email_professionnel" class="form-control" autocomplete="false"
                                value="{{ old('email_professionnel', $utilisateur->email_professionnel) }}">
                        </div>
                        <div class="form-group col-md-4">
                            <label class="form-label">Téléphone professionnel</label>
                            <input type="text" name="telephone_professionnel" class="form-control"
                                data-mask="999 99 99 99" autocomplete="false"
                                value="{{ old('telephone_professionnel', $utilisateur->telephone_professionnel) }}">
                        </div>

                        <div class="col-md-4">
                            <div class="form-group" id="data_1">
                                <label class="form-label">Date d'embauche</label>
                                <div class="input-group date">
                                    <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                    <input type="text" class="form-control" name="date_embauche"
                                        value="{{ old('date_embauche',\Carbon\Carbon::parse($utilisateur->date_embauche)->format('d/m/Y')) }}" required
                                        data-mask="99/99/9999" autocomplete="false">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group" id="data_2">
                                <label class="form-label">Date de fin de contrat(<span style="color: red;font-size:12px">***</span>) </label>
                                <div class="input-group date">
                                    <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                    <input type="text" class="form-control" name="date_fin_contrat"
                                        value="{{ old('date_fin_contrat',\Carbon\Carbon::parse($utilisateur->date_fin_contrat)->format('d/m/Y')) }}" required data-mask="99/99/9999"
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
                                        {{ old('role_id', $utilisateur->role_id) == $role->id ? 'selected' : '' }}>
                                        {{ $role->nom }}</option>
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
                                        {{ old('superieur_hierarchique', $utilisateur->superieur_hierarchique) == $user->nom . ' ' . $user->prenom ? 'selected' : '' }}>
                                        {{ $user->nom }} {{ $user->prenom }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-md-4">
                            <label class="form-label">Niveau d'étude</label>
                            <input type="text" name="niveau_etude" class="form-control" autocomplete="false"
                                value="{{ old('niveau_etude', $utilisateur->niveau_etude) }}">
                        </div>
                        <div class="form-group col-md-4">
                            <label class="form-label">Compétences</label>
                            <textarea name="competence" class="form-control">{{ old('competence', $utilisateur->competence) }}</textarea>
                        </div>
                    </div>

                    <h4 class="mt-4 mb-3 text-primary">Informations de rémunération</h4>
                    <div class="row g-3">
                        <div class="form-group col-md-4">
                            <label class="form-label">Salaire</label>
                            <input type="number" step="0.01" name="salaire" class="form-control"
                                autocomplete="false" value="{{ old('salaire', $utilisateur->salaire) }}">
                        </div>
                        <div class="form-group col-md-4">
                            <label class="form-label">Type de contrat</label>
                            <input type="text" name="type_contrat" class="form-control" autocomplete="false"
                                value="{{ old('type_contrat', $utilisateur->type_contrat) }}">
                        </div>
                        <div class="form-group col-md-4">
                            <label class="form-label">Mode de paiement</label>
                            <input type="text" name="mode_paiement" class="form-control" autocomplete="false"
                                value="{{ old('mode_paiement', $utilisateur->mode_paiement) }}">
                        </div>
                        <div class="form-group col-md-4">
                            <label class="form-label">IBAN</label>
                            <input type="text" name="iban" class="form-control" autocomplete="false"
                                value="{{ old('iban', $utilisateur->iban) }}">
                        </div>
                        <div class="form-group col-md-4">
                            <label class="form-label">BIC</label>
                            <input type="text" name="bic" class="form-control" autocomplete="false"
                                value="{{ old('bic', $utilisateur->bic) }}">
                        </div>
                        <div class="form-group col-md-4">
                            <label class="form-label">Titulaire du compte</label>
                            <input type="text" name="titulaire_compte" class="form-control" autocomplete="false"
                                value="{{ old('titulaire_compte', $utilisateur->titulaire_compte) }}">
                        </div>
                        <div class="form-group col-md-4">
                            <label class="form-label">Banque</label>
                            <input type="text" name="nom_banque" class="form-control" autocomplete="false"
                                value="{{ old('nom_banque', $utilisateur->nom_banque) }}">
                        </div>
                        <div class="form-group col-md-4">
                            <label class="form-label">Agence</label>
                            <input type="text" name="nom_agence" class="form-control" autocomplete="false"
                                value="{{ old('nom_agence', $utilisateur->nom_agence) }}">
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
                                value="{{ old('nom_completaire', $utilisateur->nom_completaire) }}">
                        </div>
                        <div class="form-group col-md-4">
                            <label class="form-label">Lien de parenté</label>
                            <input type="text" name="lien_completaire" class="form-control" autocomplete="false"
                                value="{{ old('lien_completaire', $utilisateur->lien_completaire) }}">
                        </div>
                        <div class="form-group col-md-4">
                            <label class="form-label">Contact d'urgence</label>
                            <input type="text" name="contact_completaire" class="form-control"
                                data-mask="999 99 99 99" autocomplete="false"
                                value="{{ old('contact_completaire', $utilisateur->contact_completaire) }}">
                        </div>
                        <div class="form-group col-md-4">
                            <label class="form-label">Formation complémentaire</label>
                            <input type="text" name="formation_completaire" class="form-control" autocomplete="false"
                                value="{{ old('formation_completaire', $utilisateur->formation_completaire) }}">
                        </div>
                        <div class="form-group col-md-4">
                            <label class="form-label">Commentaire</label>
                            <textarea name="commmentaire_completaire" class="form-control">{{ old('commmentaire_completaire', $utilisateur->commmentaire_completaire) }}</textarea>
                        </div>
                        
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Modifier les informations</button>
                    </div>
                </form>
            </div>

        </div>
    </div>
    <!-- End Email Statistic area-->
@endsection
