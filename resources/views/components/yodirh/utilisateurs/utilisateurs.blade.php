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
                                                        <strong>Nationalité</strong><br>{{ $user->nationalite }}
                                                    </div>
                                                @endif
                                                @if ($user->adresse)
                                                    <div class="col-md-4">
                                                        <strong>Adresse</strong><br>{{ $user->adresse }}
                                                    </div>
                                                @endif
                                                @if ($user->telephone)
                                                    <div class="col-md-4">
                                                        <strong>Téléphone</strong><br>{{ $user->telephone }}
                                                    </div>
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
                                                        <strong>Ville</strong><br>{{ $user->ville->nom }}
                                                    </div>
                                                @endif
                                                @if ($user->pays?->nom)
                                                    <div class="col-md-4">
                                                        <strong>Pays</strong><br>{{ $user->pays->nom }}
                                                    </div>
                                                @endif
                                                @if ($user->salaire)
                                                    <div class="col-md-4">
                                                        <strong>Salaire</strong><br>{{ $user->salaire }} FCFA
                                                    </div>
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
                                                        <strong>Banque</strong><br>{{ $user->nom_banque }}
                                                    </div>
                                                @endif
                                                @if ($user->nom_agence)
                                                    <div class="col-md-4">
                                                        <strong>Agence</strong><br>{{ $user->nom_agence }}
                                                    </div>
                                                @endif
                                                @if ($user->competence)
                                                    <div class="col-md-12">
                                                        <strong>Compétences</strong><br>{{ $user->competence }}
                                                    </div>
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
                                                            <a href="{{ asset('storage/' . $user->$doc) }}" target="_blank"
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
    <!-- End Email Statistic area-->
@endsection
