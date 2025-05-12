@extends('layouts.master2')
@section('content2')
    <div class="section-admin container-fluid">

        <div class="row" style="margin-left: 20px; margin-right: 20px;">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="d-flex-justify-content-between mb-3 mt-3">
                    <h2 class="card-title text-primary">Gestion des utilisateurs</h2>

                    <a href="{{ route('yodirh.formulaire_utilisateurs') }}" class="btn btn-primary" style="margin-bottom: 5px;">
                        <i class="fa fa-plus"></i> Ajouter un utilisateur
                    </a>
                </div>
                <div class="sparkline13-list">
                    @if (session('success'))
                        <div class="alert alert-success" role="alert">
                            {{ session('success') }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif
                    <div class="sparkline13-graph">
                        <div class="datatable-dashv1-list custom-datatable-overright">
                            {{-- <div id="toolbar">
                                <select class="form-control">
                                    <option value="">Exporter de base</option>
                                    <option value="all">Exporter tout</option>
                                    <option value="selected">Exporter sélectionné</option>
                                </select>
                            </div> --}}
                            <table id="data-table-basic" class="table table-striped">
                                <thead>
                                    <tr class="bg-primary" style="color: white">
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
                                                <a href="{{ route('modif_affiche_utilisateur', $user->id) }}"
                                                    class="btn btn-sm btn-warning">
                                                    <i class="fa fa-edit"></i> Modifier
                                                </a>
                                                {{-- <form action=""
                                                        method="POST" class="d-inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-danger"
                                                            onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet utilisateur ?')">
                                                            <i class="fa fa-trash"></i> Supprimer
                                                        </button>
                                                    </form> --}}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>

                        </div>
                        @foreach ($utilisateurs as $user)
                            <div id="detailsMondale{{ $user->id }}"
                                class="modal fade" role="dialog"tabindex="-1" role="dialog" aria-labelledby="detailsMondale" aria-hidden="true">
                                <div class="modal-dialog modal-lg" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header bg-primary">
                                            <h4 class="modal-title">{{ $user->nom }} {{ $user->prenom }}</h4>
                                        </div>

                                        <div class="modal-body">
                                            <div class="card shadow-sm mb-4">
                                                <div class="card-header bg-info text-white">
                                                    <h5 class="mb-0" style="padding: 5px;margin-top:10px">Informations personnelles</h5>
                                                </div>
                                                <div class="card-body row g-4">
                                                    @if ($user->photo)
                                                        <div class="col-md-3 text-center">
                                                            <img src="{{ asset('storage/' . $user->photo) }}" class="img-fluid rounded-circle border" width="150" alt="Photo de profil">
                                                        </div>
                                                    @else
                                                        <div class="col-md-3 text-center">
                                                            <img src="{{ asset('src/images/user.jpg') }}" class="img-fluid rounded-circle border" width="150" alt="Photo de profil">
                                                        </div>
                                                    @endif
                                            
                                                    <div class="col-md-9 row g-3">
                                                        @if ($user->date_naissance)
                                                            <div class="col-md-6"><strong>Date de naissance :</strong><br><span>{{ $user->date_naissance }}</span></div>
                                                        @endif
                                                        @if ($user->date_embauche)
                                                            <div class="col-md-6"><strong>Date d'embauche :</strong><br><span>{{ $user->date_embauche }}</span></div>
                                                        @endif
                                                        @if ($user->etat_civil)
                                                            <div class="col-md-6"><strong>État civil :</strong><br><span>{{ $user->etat_civil }}</span></div>
                                                        @endif
                                                        @if ($user->adresse)
                                                            <div class="col-md-6"><strong>Adresse :</strong><br><span>{{ $user->adresse }}</span></div>
                                                        @endif
                                                        @if ($user->ville?->nom)
                                                            <div class="col-md-6"><strong>Ville :</strong><br><span>{{ $user->ville->nom }}</span></div>
                                                        @endif
                                                        @if ($user->pays?->nom)
                                                            <div class="col-md-6"><strong>Pays :</strong><br><span>{{ $user->pays->nom }}</span></div>
                                                        @endif
                                                        @if ($user->telephone)
                                                            <div class="col-md-6"><strong>Téléphone :</strong><br><span>{{ $user->telephone }}</span></div>
                                                        @endif
                                                        @if ($user->email_professionnel)
                                                            <div class="col-md-6"><strong>Email professionnel :</strong><br><span>{{ $user->email_professionnel }}</span></div>
                                                        @endif
                                                        @if ($user->nationalite)
                                                            <div class="col-md-6"><strong>Nationalité :</strong><br><span>{{ $user->nationalite }}</span></div>
                                                        @endif
                                                        @if ($user->nombre_enfant)
                                                            <div class="col-md-6"><strong>Nombre d'enfants :</strong><br><span>{{ $user->nombre_enfant }}</span></div>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <div class="card shadow-sm mb-4">
                                                <div class="card-header bg-info text-white">
                                                    <h5 class="mb-0"  style="padding: 5px;margin-top:10px">Informations professionnelles</h5>
                                                </div>
                                                <div class="card-body row g-3">
                                                    @if ($user->type_contrat)
                                                        <div class="col-md-6"><strong>Type de contrat :</strong><br><span>{{ $user->type_contrat }}</span></div>
                                                    @endif
                                                    @if ($user->mode_paiement)
                                                        <div class="col-md-6"><strong>Mode de paiement :</strong><br><span>{{ $user->mode_paiement }}</span></div>
                                                    @endif
                                                    @if ($user->salaire)
                                                        <div class="col-md-6"><strong>Salaire :</strong><br><span>{{ number_format($user->salaire, 0, ',', ' ') }} FCFA</span></div>
                                                    @endif
                                                    @if ($user->iban)
                                                        <div class="col-md-6"><strong>IBAN :</strong><br><span>{{ $user->iban }}</span></div>
                                                    @endif
                                                    @if ($user->bic)
                                                        <div class="col-md-6"><strong>BIC :</strong><br><span>{{ $user->bic }}</span></div>
                                                    @endif
                                                    @if ($user->nom_banque)
                                                        <div class="col-md-6"><strong>Banque :</strong><br><span>{{ $user->nom_banque }}</span></div>
                                                    @endif
                                                    @if ($user->nom_agence)
                                                        <div class="col-md-6"><strong>Agence bancaire :</strong><br><span>{{ $user->nom_agence }}</span></div>
                                                    @endif
                                                </div>
                                            </div>
                                            
                                            <div class="card shadow-sm mb-4">
                                                <div class="card-header bg-info text-white">
                                                    <h5 class="mb-0" style="padding: 5px;margin-top:10px">Documents et compétences</h5>
                                                </div>
                                                <div class="card-body row g-3">
                                                    @foreach (['cv', 'permis_conduire', 'piece_identite', 'diplome', 'certificat_travail'] as $doc)
                                                        @if ($user->$doc)
                                                            <div class="col-md-4">
                                                                <strong>{{ ucfirst(str_replace('_', ' ', $doc)) }}</strong><br>
                                                                <a href="{{ asset('storage/' . $user->$doc) }}" target="_blank" class="btn btn-outline-primary btn-sm mt-1">📄 Télécharger</a>
                                                            </div>
                                                        @endif
                                                    @endforeach
                                            
                                                    @if ($user->competence)
                                                        <div class="col-md-12"><strong>Compétences :</strong><br>{{ $user->competence }}</div>
                                                    @endif
                                                    @if ($user->commmentaire_completaire)
                                                        <div class="col-md-12"><strong>Commentaires :</strong><br>{{ $user->commmentaire_completaire }}</div>
                                                    @endif
                                                </div>
                                            </div>
                                            
                                            <div class="text-right">
                                                <button type="button" class="btn btn-primary" data-dismiss="modal">Fermer</button>
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
    <!-- End Email Statistic area-->
@endsection
