@extends('layouts.master2')
@section('content2')
    <div class="section-admin container-fluid">

        <div class="container">
            <div class="card-body">
                <div class="justify-content-between align-items-center mb-4">
                    <h2 class="card-title text-primary">Modification : {{ $utilisateur->nom }} {{ $utilisateur->prenom }}
                    </h2>
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
                    enctype="multipart/form-data" class="px-4 card border rounded shadow-sm bg-light needs-validation"
                    novalidate>
                    @csrf
                    @method('PUT')
                    <h4 class="mb-3 text-primary">Informations personnelles</h4>
                    <div class="row g-3">
                        <div class="form-group col-md-4">
                            <label class="form-label">Nom(<span style="color: red;font-size:12px">***</span>)</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fa fa-user"></i></span>
                                <input type="text" name="nom" class="form-control" required autocomplete="off"
                                    value="{{ old('nom', $utilisateur->nom) }}">
                            </div>
                        </div>
                        <div class="form-group col-md-4">
                            <label class="form-label">Prénom(<span style="color: red;font-size:12px">***</span>)</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fa fa-user"></i></span>
                                <input type="text" name="prenom" class="form-control" required autocomplete="off"
                                    value="{{ old('prenom', $utilisateur->prenom) }}">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group" id="group_date_naissance_modif">
                                <label class="form-label">Date de naissance(<span
                                        style="color: red;font-size:12px">***</span>)</label>
                                <div class="input-group date">
                                    <span class="input-group-text"><i class="fa fa-calendar"></i></span>
                                    <input type="text" class="form-control mask-date" name="date_naissance"
                                        value="{{ old('date_naissance', \Carbon\Carbon::parse($utilisateur->date_naissance)->format('d/m/Y')) }}"
                                        required autocomplete="off">
                                </div>
                            </div>
                        </div>
                        <div class="form-group col-md-4">
                            <label class="form-label">Lieu de naissance(<span
                                    style="color: red;font-size:12px">***</span>)</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fa fa-map-marker"></i></span>
                                <input type="text" name="lieu_naissance" class="form-control" autocomplete="off"
                                    value="{{ old('lieu_naissance', $utilisateur->lieu_naissance) }}" required>
                            </div>
                        </div>
                        <div class="form-group col-md-4">
                            <label class="form-label">Nationalité(<span
                                    style="color: red;font-size:12px">***</span>)</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fa fa-flag"></i></span>
                                <input type="text" name="nationalite" class="form-control" autocomplete="off"
                                    value="{{ old('nationalite', $utilisateur->nationalite) }}" required>
                            </div>
                        </div>
                        <div class="form-group col-md-4">
                            <label class="form-label">Numéro sécurité sociale</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fa fa-id-card"></i></span>
                                <input type="text" name="numero_securite_sociale" class="form-control mask-ssn-fr"
                                    autocomplete="off"
                                    value="{{ old('numero_securite_sociale', $utilisateur->numero_securite_sociale) }}">
                            </div>
                        </div>
                        <div class="form-group col-md-4">
                            <label class="form-label">État civil(<span style="color: red;font-size:12px">***</span>)</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fa fa-heart"></i></span>
                                <input type="text" name="etat_civil" class="form-control" autocomplete="off"
                                    value="{{ old('etat_civil', $utilisateur->etat_civil) }}" required>
                            </div>
                        </div>
                        <div class="form-group col-md-4">
                            <label class="form-label">Nombre d'enfants</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fa fa-child"></i></span>
                                <input type="number" name="nombre_enfant" class="form-control" min="0"
                                    max="50" value="{{ old('nombre_enfant', $utilisateur->nombre_enfant) }}">
                            </div>
                        </div>
                        <div class="form-group col-md-4">
                            <label class="form-label">Adresse(<span style="color: red;font-size:12px">***</span>)</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fa fa-home"></i></span>
                                <input type="text" name="adresse" class="form-control" required autocomplete="off"
                                    value="{{ old('adresse', $utilisateur->adresse) }}">
                            </div>
                        </div>
                        <div class="form-group col-md-4">
                            <label class="form-label">Adresse complémentaire</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fa fa-plus-square"></i></span>
                                <input type="text" name="adresse_complementaire" class="form-control"
                                    autocomplete="off"
                                    value="{{ old('adresse_complementaire', $utilisateur->adresse_complementaire) }}">
                            </div>
                        </div>
                        <div class="form-group col-md-4">
                            <label class="form-label">Code postal</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fa fa-map-pin"></i></span>
                                <input type="text" name="code_postal" class="form-control mask-postal-code"
                                    autocomplete="off" value="{{ old('code_postal', $utilisateur->code_postal) }}">
                            </div>
                        </div>
                        <div class="form-group col-md-4">
                            <label class="form-label">Téléphone personel</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fa fa-phone"></i></span>
                                <input type="text" name="telephone" class="form-control" autocomplete="off"
                                    value="{{ old('telephone', $utilisateur->telephone) }}">
                            </div>
                        </div>
                        <div class="form-group col-md-4">
                            <label class="form-label">Email personnel</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fa fa-envelope"></i></span>
                                <input type="email" name="email" class="form-control" autocomplete="off"
                                    value="{{ old('email', $utilisateur->email) }}">
                            </div>
                        </div>
                        <div class="form-group col-md-4">
                            <label class="form-label">Pays(<span style="color: red;font-size:12px">***</span>)</label>
                            <div class="input-group">
                                <select class="select2 form-control" name="pays_id" style="width: 100%;" required>
                                    <option value="">Veuillez selectionner</option>
                                    @foreach ($pays as $Lepays)
                                        <option value="{{ $Lepays->id }}"
                                            {{ old('pays_id', $utilisateur->pays_id) == $Lepays->id ? 'selected' : '' }}>
                                            {{ $Lepays->nom_pays }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group col-md-4">
                            <label class="form-label">Ville(<span style="color: red;font-size:12px">***</span>)</label>
                            <div class="input-group">
                                <select class="select2 form-control" name="ville_id" style="width: 100%;" required>
                                    <option value="">Veuillez selectionner</option>
                                    @foreach ($villes as $ville)
                                        <option value="{{ $ville->id }}"
                                            {{ old('ville_id', $utilisateur->ville_id) == $ville->id ? 'selected' : '' }}>
                                            {{ $ville->nom_ville }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <h4 class="mt-4 mb-3 text-primary">Informations professionnelles</h4>
                    <div class="row g-3">
                        <div class="form-group col-md-4">
                            <label class="form-label">Entreprise(<span
                                    style="color: red;font-size:12px">***</span>)</label>
                            <div class="input-group">
                                <select class="select2 form-control" name="entreprise_id" style="width: 100%;" required>
                                    <option value="">Veuillez selectionner</option>
                                    @foreach ($entreprises as $entreprise)
                                        <option value="{{ $entreprise->id }}"
                                            {{ old('entreprise_id', $utilisateur->entreprise_id) == $entreprise->id ? 'selected' : '' }}>
                                            {{ $entreprise->nom_entreprise }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group col-md-4">
                            <label class="form-label">Service(<span style="color: red;font-size:12px">***</span>)</label>
                            <div class="input-group">
                                <select class="select2 form-control" name="service_id" style="width: 100%;" required>
                                    <option value="">Veuillez selectionner</option>
                                    @foreach ($services as $service)
                                        <option value="{{ $service->id }}"
                                            {{ old('service_id', $utilisateur->service_id) == $service->id ? 'selected' : '' }}>
                                            {{ $service->nom_service }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group col-md-4">
                            <label class="form-label">Categorie professionelle(<span
                                    style="color: red;font-size:12px">***</span>)</label>
                            <div class="input-group">
                                <select class="select2 form-control" name="categorie_professionel_id"
                                    style="width: 100%;" required>
                                    <option value="">Veuillez selectionner</option>
                                    @foreach ($categorie_professionelles as $categorie_professionel)
                                        <option value="{{ $categorie_professionel->id }}"
                                            {{ old('categorie_professionel_id', $utilisateur->categorie_professionel_id) == $categorie_professionel->id ? 'selected' : '' }}>
                                            {{ $categorie_professionel->nom_categorie_professionnelle }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group col-md-4">
                            <label class="form-label">Poste/Fonction(<span
                                    style="color: red;font-size:12px">***</span>)</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fa fa-id-badge"></i></span>
                                <input type="text" name="fonction" class="form-control"
                                    value="{{ old('fonction', $utilisateur->fonction) }}" required>
                            </div>
                        </div>
                        <div class="form-group col-md-4">
                            <label class="form-label">Matricule(<span
                                    style="color: red;font-size:12px">***</span>)</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fa fa-barcode"></i></span>
                                <input type="text" name="matricule" class="form-control" required autocomplete="off"
                                    value="{{ old('matricule', $utilisateur->matricule) }}">
                            </div>
                        </div>
                        <div class="form-group col-md-4">
                            <label class="form-label">Email professionnel</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fa fa-envelope"></i></span>
                                <input type="email" name="email_professionnel" class="form-control" autocomplete="off"
                                    value="{{ old('email_professionnel', $utilisateur->email_professionnel) }}">
                            </div>
                        </div>
                        <div class="form-group col-md-4">
                            <label class="form-label">Téléphone professionnel</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fa fa-phone-square"></i></span>
                                <input type="text" name="telephone_professionnel" class="form-control"
                                    autocomplete="off"
                                    value="{{ old('telephone_professionnel', $utilisateur->telephone_professionnel) }}">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group" id="group_date_embauche_modif">
                                <label class="form-label">Date d'embauche(<span
                                        style="color: red;font-size:12px">***</span>)</label>
                                <div class="input-group date">
                                    <span class="input-group-text"><i class="fa fa-calendar-plus"></i></span>
                                    <input type="text" class="form-control mask-date" name="date_embauche"
                                        value="{{ old('date_embauche', \Carbon\Carbon::parse($utilisateur->date_embauche)->format('d/m/Y')) }}"
                                        required autocomplete="off">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group" id="group_date_fin_contrat_modif">
                                <label class="form-label">Date de fin de contrat</label>
                                <div class="input-group date">
                                    <span class="input-group-text"><i class="fa fa-calendar-times"></i></span>
                                    <input type="text" class="form-control mask-date" name="date_fin_contrat"
                                        value="{{ old('date_fin_contrat', $utilisateur->date_fin_contrat ? \Carbon\Carbon::parse($utilisateur->date_fin_contrat)->format('d/m/Y') : '') }}"
                                        autocomplete="off">
                                </div>
                            </div>
                        </div>
                        <div class="form-group col-md-4">
                            <label class="form-label">Rôle(<span style="color: red;font-size:12px">***</span>)</label>
                            <div class="input-group">
                                <select class="select2 form-control" name="role_id" style="width: 100%;" required>
                                    <option value="">Veuillez selectionner</option>
                                    @foreach ($roles as $role)
                                        <option value="{{ $role->id }}"
                                            {{ old('role_id', $utilisateur->role_id) == $role->id ? 'selected' : '' }}>
                                            {{ $role->nom }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group col-md-4">
                            <label class="form-label">Responsable hiérarchique(<span
                                    style="color: red;font-size:12px">***</span>)</label>
                            <div class="input-group">
                                <select class="select2 form-control" name="superieur_hierarchique" style="width: 100%;"
                                    required>
                                    <option value="">Veuillez selectionner</option>
                                    @foreach ($users as $user)
                                        <option value="{{ $user->nom }} {{ $user->prenom }}"
                                            {{ old('superieur_hierarchique', $utilisateur->superieur_hierarchique) == $user->nom . ' ' . $user->prenom ? 'selected' : '' }}>
                                            {{ $user->nom }} {{ $user->prenom }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group col-md-4">
                            <label class="form-label">Niveau d'étude</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fa fa-graduation-cap"></i></span>
                                <input type="text" name="niveau_etude" class="form-control" autocomplete="off"
                                    value="{{ old('niveau_etude', $utilisateur->niveau_etude) }}">
                            </div>
                        </div>
                        <div class="form-group col-md-4">
                            <label class="form-label">Compétences</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fa fa-tasks"></i></span>
                                <textarea name="competence" class="form-control">{{ old('competence', $utilisateur->competence) }}</textarea>
                            </div>
                        </div>
                    </div>

                    <h4 class="mt-4 mb-3 text-primary">Informations de rémunération</h4>
                    <div class="row g-3">
                        <div class="form-group col-md-4">
                            <label class="form-label">Salaire(<span style="color: red;font-size:12px">***</span>)</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fa fa-eur"></i></span>
                                <input type="number" step="0.01" name="salaire" class="form-control"
                                    autocomplete="off" value="{{ old('salaire', $utilisateur->salaire) }}" required>
                            </div>
                        </div>
                        <div class="form-group col-md-4">
                            <label class="form-label">Type de contrat</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fa fa-file-text"></i></span>
                                <input type="text" name="type_contrat" class="form-control" autocomplete="off"
                                    value="{{ old('type_contrat', $utilisateur->type_contrat) }}">
                            </div>
                        </div>
                        <div class="form-group col-md-4">
                            <label class="form-label">Mode de paiement</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fa fa-credit-card"></i></span>
                                <input type="text" name="mode_paiement" class="form-control" autocomplete="off"
                                    value="{{ old('mode_paiement', $utilisateur->mode_paiement) }}">
                            </div>
                        </div>
                        <div class="form-group col-md-4">
                            <label class="form-label">IBAN</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fa fa-bank"></i></span>
                                <input type="text" name="iban" class="form-control mask-iban" autocomplete="off"
                                    value="{{ old('iban', $utilisateur->iban) }}">
                            </div>
                        </div>
                        <div class="form-group col-md-4">
                            <label class="form-label">BIC</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fa fa-hashtag"></i></span>
                                <input type="text" name="bic" class="form-control mask-bic" autocomplete="off"
                                    value="{{ old('bic', $utilisateur->bic) }}">
                            </div>
                        </div>
                        <div class="form-group col-md-4">
                            <label class="form-label">Titulaire du compte</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fa fa-user"></i></span>
                                <input type="text" name="titulaire_compte" class="form-control" autocomplete="off"
                                    value="{{ old('titulaire_compte', $utilisateur->titulaire_compte) }}">
                            </div>
                        </div>
                        <div class="form-group col-md-4">
                            <label class="form-label">Banque</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fa fa-university"></i></span>
                                <input type="text" name="nom_banque" class="form-control" autocomplete="off"
                                    value="{{ old('nom_banque', $utilisateur->nom_banque) }}">
                            </div>
                        </div>
                        <div class="form-group col-md-4">
                            <label class="form-label">Agence</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fa fa-building"></i></span>
                                <input type="text" name="nom_agence" class="form-control" autocomplete="off"
                                    value="{{ old('nom_agence', $utilisateur->nom_agence) }}">
                            </div>
                        </div>
                    </div>

                    <h4 class="mt-4 mb-3 text-primary">Documents administratifs</h4>
                    <div class="row g-3">
                        <div class="form-group col-md-4">
                            <label class="form-label">Photo</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fa fa-camera"></i></span>
                                <input type="file" name="photo" class="form-control">
                            </div>
                            @if ($utilisateur->photo)
                                <a href="{{ asset('storage/' . $utilisateur->photo) }}"
                                    onclick="ouvrirPopup(event, '{{ asset('storage/' . $utilisateur->photo) }}')"
                                    class="mt-1 d-block">
                                    📷 Voir la photo actuelle
                                </a>
                            @endif
                            
                        </div>
                        <div class="form-group col-md-4">
                            <label class="form-label">CV</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fa fa-file-pdf"></i></span>
                                <input type="file" name="cv" class="form-control">
                            </div>
                            @if ($utilisateur->cv)
                                <a href="{{ asset('storage/' . $utilisateur->cv) }}"
                                    onclick="ouvrirPopup(event, '{{ asset('storage/' . $utilisateur->cv) }}')"
                                    class="mt-1 d-block">
                                    <i class="fas fa-file-alt me-1"></i>
                                    Voir le CV actuel
                                </a>
                            @endif
                        </div>
                        <div class="form-group col-md-4">
                            <label class="form-label">Permis de conduire</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fa fa-car"></i></span>
                                <input type="file" name="permis_conduire" class="form-control">
                            </div>
                            @if ($utilisateur->permis_conduire)
                                <a href="{{ asset('storage/' . $utilisateur->permis_conduire) }}" target="_blank"
                                    class="mt-1 d-block">Voir le permis actuel</a>
                            @endif
                        </div>
                        <div class="form-group col-md-4">
                            <label class="form-label">Pièce d'identité</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fa fa-id-card"></i></span>
                                <input type="file" name="piece_identite" class="form-control">
                            </div>
                            @if ($utilisateur->piece_identite)
                                <a href="{{ asset('storage/' . $utilisateur->piece_identite) }}" target="_blank"
                                    class="mt-1 d-block">Voir la pièce actuelle</a>
                            @endif
                        </div>
                        <div class="form-group col-md-4">
                            <label class="form-label">Diplôme</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fa fa-certificate"></i></span>
                                <input type="file" name="diplome" class="form-control">
                            </div>
                            @if ($utilisateur->diplome)
                                <a href="{{ asset('storage/' . $utilisateur->diplome) }}" target="_blank"
                                    class="mt-1 d-block">Voir le diplôme actuel</a>
                            @endif
                        </div>
                        <div class="form-group col-md-4">
                            <label class="form-label">Certificat de travail</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fa fa-file-word"></i></span>
                                <input type="file" name="certificat_travail" class="form-control">
                            </div>
                            @if ($utilisateur->certificat_travail)
                                <a href="{{ asset('storage/' . $utilisateur->certificat_travail) }}" target="_blank"
                                    class="mt-1 d-block">Voir le certificat actuel</a>
                            @endif
                        </div>
                    </div>

                    <h4 class="mt-4 mb-3 text-primary">Informations complémentaires</h4>
                    <div class="row g-3">
                        <div class="form-group col-md-4">
                            <label class="form-label">Nom du contact</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fa fa-address-book"></i></span>
                                <input type="text" name="nom_completaire" class="form-control" autocomplete="off"
                                    value="{{ old('nom_completaire', $utilisateur->nom_completaire) }}">
                            </div>
                        </div>
                        <div class="form-group col-md-4">
                            <label class="form-label">Lien de parenté</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fa fa-link"></i></span>
                                <input type="text" name="lien_completaire" class="form-control" autocomplete="off"
                                    value="{{ old('lien_completaire', $utilisateur->lien_completaire) }}">
                            </div>
                        </div>
                        <div class="form-group col-md-4">
                            <label class="form-label">Contact d'urgence</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fa fa-ambulance"></i></span>
                                <input type="text" name="contact_completaire" class="form-control mask-phone-fr"
                                    autocomplete="off"
                                    value="{{ old('contact_completaire', $utilisateur->contact_completaire) }}">
                            </div>
                        </div>
                        <div class="form-group col-md-4">
                            <label class="form-label">Formation complémentaire</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fa fa-plus-circle"></i></span>
                                <input type="text" name="formation_completaire" class="form-control"
                                    autocomplete="off"
                                    value="{{ old('formation_completaire', $utilisateur->formation_completaire) }}">
                            </div>
                        </div>
                        <div class="form-group col-md-4">
                            <label class="form-label">Commentaire</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fa fa-commenting"></i></span>
                                <textarea name="commmentaire_completaire" class="form-control">{{ old('commmentaire_completaire', $utilisateur->commmentaire_completaire) }}</textarea>
                            </div>
                        </div>
                    </div>
                    {{-- <h4 class="mt-4 mb-3 text-primary">Changer le mot de passe</h4>
    <p class="text-muted small">Laissez ces champs vides si vous ne souhaitez pas modifier le mot de passe.</p>
    <div class="row g-3">
        <div class="col-md-4">
            <div class="form-group">
                <label class="form-label">Nouveau mot de passe</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="fa fa-lock"></i></span>
                    <input type="password" name="password" class="form-control" id="password_modif" autocomplete="new-password">
                    <span class="input-group-text" id="togglePasswordModif" style="cursor: pointer"><i class="fa fa-eye"></i></span>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label class="form-label">Confirmer le mot de passe</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="fa fa-lock"></i></span>
                    <input type="password" name="password_confirmation" class="form-control" autocomplete="new-password">
                </div>
            </div>
        </div>
    </div> --}}
                    <div class="modal-footer">
                        <a href="{{ url()->previous() }}" class="btn btn-secondary me-2">Annuler</a>
                        <button type="submit" class="btn btn-primary">Modifier les informations</button>
                    </div>
                </form>
            </div>

        </div>
    </div>
    <script>
                                function ouvrirPopup(event, url) {
                                    event.preventDefault(); // Empêche le lien de s'ouvrir normalement

                                    const width = 800; // largeur de la popup
                                    const height = 900; // hauteur de la popup

                                    // Calcul pour centrer la popup
                                    const left = (window.screen.width / 2) - (width / 2);
                                    const top = (window.screen.height / 2) - (height / 2);

                                    // Ouvrir la popup centrée
                                    window.open(
                                        url,
                                        'popupDocument',
                                        `width=${width},height=${height},top=${top},left=${left},scrollbars=yes,resizable=yes`
                                    );
                                }
                            </script>
    {{-- <script>
        // Script to toggle password visibility
        document.getElementById('togglePasswordModif').addEventListener('click', function() {
            const passwordField = document.getElementById('password_modif');
            const icon = this.querySelector('i');
            if (passwordField.type === 'password') {
                passwordField.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                passwordField.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        });
    </script> --}}
    <!-- End Email Statistic area-->
@endsection
