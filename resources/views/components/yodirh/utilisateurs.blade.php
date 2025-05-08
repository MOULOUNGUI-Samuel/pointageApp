@extends('layouts.master2')
@section('content2')
    <div class="section-admin container-fluid">
        <div class="container">
            <div class="card-body my-4 shadow p-3" style="background-color: white;shadow: 0px 0px 10px #ccc;padding: 40px;">
                <h3 class="card-title">Créer un utilisateur</h3>
                <form method="POST" action="" enctype="multipart/form-data"
                    class="p-4 card border rounded shadow-sm bg-light">
                    @csrf

                    <h4 class="mb-3">Informations personnelles</h4>
                    <div class="row g-3">
                        <div class="form-group col-md-4">
                            <label class="form-label">Nom</label>
                            <input type="text" name="nom" class="form-control" required>
                        </div>
                        <div class="form-group col-md-4">
                            <label class="form-label">Prénom</label>
                            <input type="text" name="prenom" class="form-control" required>
                        </div>
                        <div class="form-group col-md-4">
                            <label class="form-label">Date de naissance</label>
                            <input type="date" name="date_naissance" class="form-control">
                        </div>
                        <div class="form-group col-md-4">
                            <label class="form-label">Lieu de naissance</label>
                            <input type="text" name="lieu_naissance" class="form-control">
                        </div>
                        <div class="form-group col-md-4">
                            <label class="form-label">Nationalité</label>
                            <input type="text" name="nationalite" class="form-control">
                        </div>
                        <div class="form-group col-md-4">
                            <label class="form-label">Numéro sécurité sociale</label>
                            <input type="text" name="numero_securite_sociale" class="form-control">
                        </div>
                        <div class="form-group col-md-4">
                            <label class="form-label">État civil</label>
                            <input type="text" name="etat_civil" class="form-control">
                        </div>
                        <div class="form-group col-md-4">
                            <label class="form-label">Nombre d'enfants</label>
                            <input type="number" name="nombre_enfant" class="form-control">
                        </div>
                        <div class="form-group col-md-4">
                            <label class="form-label">Adresse</label>
                            <input type="text" name="adresse" class="form-control">
                        </div>
                        <div class="form-group col-md-4">
                            <label class="form-label">Adresse complémentaire</label>
                            <input type="text" name="adresse_complementaire" class="form-control">
                        </div>
                        <div class="form-group col-md-4">
                            <label class="form-label">Code postal</label>
                            <input type="text" name="code_postal" class="form-control">
                        </div>
                        <div class="form-group col-md-4">
                            <label class="form-label">Téléphone</label>
                            <input type="text" name="telephone" class="form-control">
                        </div>
                        <div class="form-group col-md-4">
                            <label class="form-label">Email personnel</label>
                            <input type="email" name="email" class="form-control">
                        </div>
                    </div>

                    <h4 class="mt-4 mb-3">Informations professionnelles</h4>
                    <div class="row g-3">
                        <div class="form-group col-md-4">
                            <label class="form-label">Entreprise</label>
                            <select name="entreprise_id" class="form-select"></select>
                        </div>
                        <div class="form-group col-md-4">
                            <label class="form-label">Service</label>
                            <select name="service_id" class="form-select"></select>
                        </div>
                        <div class="form-group col-md-4">
                            <label class="form-label">Poste/Fonction</label>
                            <input type="text" name="fonction" class="form-control">
                        </div>
                        <div class="form-group col-md-4">
                            <label class="form-label">Matricule</label>
                            <input type="text" name="matricule" class="form-control" required>
                        </div>
                        <div class="form-group col-md-4">
                            <label class="form-label">Email professionnel</label>
                            <input type="email" name="email_professionnel" class="form-control">
                        </div>
                        <div class="form-group col-md-4">
                            <label class="form-label">Téléphone professionnel</label>
                            <input type="text" name="telephone_professionnel" class="form-control">
                        </div>
                        <div class="form-group col-md-4">
                            <label class="form-label">Date d'embauche</label>
                            <input type="date" name="date_embauche" class="form-control">
                        </div>
                        <div class="form-group col-md-4">
                            <label class="form-label">Rôle</label>
                            <select name="role_id" class="form-select"></select>
                        </div>
                        <div class="form-group col-md-4">
                            <label class="form-label">Responsable hiérarchique</label>
                            <select name="superieur_hierarchique" class="form-select"></select>
                        </div>
                        <div class="form-group col-md-4">
                            <label class="form-label">Niveau d'étude</label>
                            <input type="text" name="niveau_etude" class="form-control">
                        </div>
                        <div class="form-group col-md-4">
                            <label class="form-label">Compétences</label>
                            <textarea name="competence" class="form-control"></textarea>
                        </div>
                    </div>

                    <h4 class="mt-4 mb-3">Informations de rémunération</h4>
                    <div class="row g-3">
                        <div class="form-group col-md-4">
                            <label class="form-label">Salaire</label>
                            <input type="number" step="0.01" name="salaire" class="form-control">
                        </div>
                        <div class="form-group col-md-4">
                            <label class="form-label">Type de contrat</label>
                            <input type="text" name="type_contrat" class="form-control">
                        </div>
                        <div class="form-group col-md-4">
                            <label class="form-label">Mode de paiement</label>
                            <input type="text" name="mode_paiement" class="form-control">
                        </div>
                        <div class="form-group col-md-4">
                            <label class="form-label">IBAN</label>
                            <input type="text" name="iban" class="form-control">
                        </div>
                        <div class="form-group col-md-4">
                            <label class="form-label">BIC</label>
                            <input type="text" name="bic" class="form-control">
                        </div>
                        <div class="form-group col-md-4">
                            <label class="form-label">Titulaire du compte</label>
                            <input type="text" name="titulaire_compte" class="form-control">
                        </div>
                        <div class="form-group col-md-4">
                            <label class="form-label">Banque</label>
                            <input type="text" name="nom_banque" class="form-control">
                        </div>
                        <div class="form-group col-md-4">
                            <label class="form-label">Agence</label>
                            <input type="text" name="nom_agence" class="form-control">
                        </div>
                    </div>

                    <h4 class="mt-4 mb-3">Documents administratifs</h4>
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

                    <h4 class="mt-4 mb-3">Informations complémentaires</h4>
                    <div class="row g-3">
                        <div class="form-group col-md-4">
                            <label class="form-label">Nom du contact</label>
                            <input type="text" name="nom_completaire" class="form-control">
                        </div>
                        <div class="form-group col-md-4">
                            <label class="form-label">Lien de parenté</label>
                            <input type="text" name="lien_completaire" class="form-control">
                        </div>
                        <div class="form-group col-md-4">
                            <label class="form-label">Contact d'urgence</label>
                            <input type="text" name="contact_completaire" class="form-control">
                        </div>
                        <div class="form-group col-md-4">
                            <label class="form-label">Formation complémentaire</label>
                            <input type="text" name="formation_completaire" class="form-control">
                        </div>
                        <div class="form-group col-md-4">
                            <label class="form-label">Commentaire</label>
                            <textarea name="commmentaire_completaire" class="form-control"></textarea>
                        </div>
                        <div class="form-group col-md-4">
                            <label class="form-label">Mot de passe</label>
                            <input type="password" name="password" class="form-control">
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary mt-4">Créer l'utilisateur</button>
                </form>
            </div>

        </div>
    </div>
    <!-- End Email Statistic area-->
@endsection
