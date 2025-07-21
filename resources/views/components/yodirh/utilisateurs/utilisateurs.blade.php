@extends('layouts.master2')
@section('content2')
    <div class="section-admin container-fluid">

        <div class="row" style="margin-left: 20px; margin-right: 20px;">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="d-flex-justify-content-between mb-3 mt-3">
                    <h2 class="card-title text-primary">Gestion des utilisateurs</h2>

                </div>
                <div class="card ">
                    @if (session('success'))
                        <div class="alert alert-success" role="alert">
                            {{ session('success') }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif
                    <div class="card-header">
                        <!-- Search -->
                        <div class="row align-items-center">
                            <div class="col-sm-4">
                                <div class="icon-form mb-3 mb-sm-0">
                                    <span class="form-icon"><i class="ti ti-search"></i></span>
                                    <input type="text" onkeyup="searchTable()" id="searchInput2" class="form-control"
                                        placeholder="Rechercher un utilisateur...">
                                </div>
                            </div>
                            <div class="col-sm-8">
                                <div class="d-flex align-items-center flex-wrap row-gap-2 justify-content-sm-end">
                                    <div class="dropdown me-2">
                                        <a href="javascript:void(0);" class="dropdown-toggle" data-bs-toggle="dropdown"><i
                                                class="ti ti-package-export me-2"></i>Exporter</a>
                                        <div class="dropdown-menu  dropdown-menu-end">
                                            <ul>
                                                <li>
                                                    <a href="javascript:void(0);" class="dropdown-item"><i
                                                            class="ti ti-file-type-pdf text-danger me-1"></i>Exporter
                                                        en PDF</a>
                                                </li>
                                                <li>
                                                    <a href="javascript:void(0);" class="dropdown-item"><i
                                                            class="ti ti-file-type-xls text-green me-1"></i>Exporter
                                                        en Excel </a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                    <a href="{{ route('yodirh.formulaire_utilisateurs') }}" class="btn-action btn btn-primary" data-loader-target="ajout-utilisateur"><i
                                            class="ti ti-square-rounded-plus me-2"></i>Ajouter un utilisateur</a>
                                                <!-- Bouton de chargement (caché au départ) -->
                                                <button type="button" id="ajout-utilisateur"
                                                    class="btn btn-outline-primary" style="display: none;" disabled>
                                                    <i class="fas fa-spinner fa-spin me-2"></i>Chargement...
                                                </button>
                                </div>
                            </div>
                        </div>
                        <!-- /Search -->
                    </div>
                    <div class="card-body">
                         <div class="table-responsive">
                                <table id="data-table-basic" class="table table-striped">
                                    <thead>
                                        <tr>
                                        <th>Nom</th>
                                        <th>Prénom</th>
                                        <th>Matricule</th>
                                        <th>Email professionnel</th>
                                        <th>Entreprise</th>
                                        <th>Fonction</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody id="approbationsTable2">
                                    @foreach ($utilisateurs as $user)
                                        <tr>
                                            {{-- <td>{{ $user->id }}</td> --}}
                                            <td>{{ $user->nom }}</td>
                                            <td>{{ $user->prenom }}</td>
                                            <td>{{ $user->matricule }}</td>
                                            <td>{{ $user->email_professionnel }}</td>
                                            <td>{{ $user->entreprise->nom_entreprise ?? '-' }}</td>
                                            <td>{{ $user->fonction }}</td>
                                            <td>
                                                <!-- Bouton initial -->
                                                <button type="button" class="btn-action btn btn-dark" data-bs-toggle="modal"
                                                        data-bs-target="#detailsMondale{{ $user->id }}">
                                                    <i class="fas fa-eye me-2"></i>Détails
                                                </button>
                                                <!-- Bouton initial -->
                                                <a href="{{ route('modif_affiche_utilisateur', $user->id) }}" type="button" class="btn-action btn btn-primary"
                                                    data-loader-target="loader-like{{ $user->id }}">
                                                    <i class="fas fa-edit me-2"></i>Modifier
                                                </a>

                                                <!-- Bouton de chargement (caché au départ) -->
                                                <button type="button" id="loader-like{{ $user->id }}"
                                                    class="btn btn-outline-primary" style="display: none;" disabled>
                                                    <i class="fas fa-spinner fa-spin me-2"></i>Chargement...
                                                </button>


                                                {{-- <div class="dropdown table-action">
                                                <a href="#" class="action-icon "
                                                    data-bs-toggle="dropdown"aria-expanded="false">
                                                    <i class="fa fa-ellipsis-v"></i>
                                                </a>
                                                <div class="dropdown-menu dropdown-menu-right">
                                                    <a class="dropdown-item" href="#" data-bs-toggle="modal"
                                                        data-bs-target="#detailsMondale{{ $user->id }}">
                                                        <i class="ti ti-eye text-info"></i>Détails
                                                    </a>
                                                    <a class="dropdown-item" href="#" data-bs-toggle="offcanvas"
                                                        data-bs-target="#offcanvas_edit">
                                                        <i class="ti ti-edit text-blue"></i>Edit
                                                    </a>
                                                    <a class="dropdown-item" href="#" data-bs-toggle="modal"
                                                        data-bs-target="#delete_company">
                                                        <i class="ti ti-trash text-danger"></i>Delete
                                                    </a>
                                                </div>
                                            </div> --}}
                                            </td>
                                        </tr>
                                    @endforeach
                                    

                                </tbody>
                            </table>
                            <div id="aucun-resultat" style="display: none;">
                                <h4 colspan="8" class="text-center text-warning">Aucun pointage trouvé.</h4>
                            </div>
                        </div>
                    </div>
                </div>
                @foreach ($utilisateurs as $user)
                    <div id="detailsMondale{{ $user->id }}" class="modal fade" role="dialog"tabindex="-1"
                        role="dialog" aria-labelledby="detailsMondale" aria-hidden="true">
                        <div class="modal-dialog modal-lg" role="document">
                            <div class="modal-content">
                                <div class="modal-header bg-primary">
                                    <h4 class="modal-title text-light">{{ $user->nom }} {{ $user->prenom }}</h4>
                                </div>

                                <div class="modal-body">
                                    <div class="card shadow-sm mb-4">
                                        <div class="card-header text-white">
                                            <h5 class="mb-0" style="padding: 5px;margin-top:10px">Informations
                                                personnelles</h5>
                                        </div>
                                        <div class="card-body row g-4">
                                            @if ($user->photo)
                                                <div class="col-md-3 text-center">
                                                    <img src="{{ asset('storage/' . $user->photo) }}"
                                                        class="img-fluid rounded-circle border" width="150"
                                                        alt="Photo de profil">
                                                </div>
                                            @else
                                                <div class="col-md-3 text-center">
                                                    <img src="{{ asset('src/images/user.jpg') }}"
                                                        class="img-fluid rounded-circle border" width="150"
                                                        alt="Photo de profil">
                                                </div>
                                            @endif

                                            <div class="col-md-9 row g-3">
                                                @if ($user->date_naissance)
                                                    <div class="col-md-6"><strong>Date de naissance
                                                            :</strong><br><span>{{ $user->date_naissance }}</span>
                                                    </div>
                                                @endif
                                                @if ($user->date_embauche)
                                                    <div class="col-md-6"><strong>Date d'embauche
                                                            :</strong><br><span>{{ $user->date_embauche }}</span>
                                                    </div>
                                                @endif
                                                @if ($user->etat_civil)
                                                    <div class="col-md-6"><strong>État civil
                                                            :</strong><br><span>{{ $user->etat_civil }}</span>
                                                    </div>
                                                @endif
                                                @if ($user->adresse)
                                                    <div class="col-md-6"><strong>Adresse
                                                            :</strong><br><span>{{ $user->adresse }}</span></div>
                                                @endif
                                                @if ($user->ville?->nom)
                                                    <div class="col-md-6"><strong>Ville
                                                            :</strong><br><span>{{ $user->ville->nom }}</span>
                                                    </div>
                                                @endif
                                                @if ($user->pays?->nom)
                                                    <div class="col-md-6"><strong>Pays
                                                            :</strong><br><span>{{ $user->pays->nom }}</span></div>
                                                @endif
                                                @if ($user->telephone)
                                                    <div class="col-md-6"><strong>Téléphone
                                                            :</strong><br><span>{{ $user->telephone }}</span></div>
                                                @endif
                                                @if ($user->email_professionnel)
                                                    <div class="col-md-6"><strong>Email 
                                                            :</strong><br><span>{{ $user->email }}</span>
                                                    </div>
                                                @endif
                                                @if ($user->nationalite)
                                                    <div class="col-md-6"><strong>Nationalité
                                                            :</strong><br><span>{{ $user->nationalite }}</span>
                                                    </div>
                                                @endif
                                                @if ($user->nombre_enfant)
                                                    <div class="col-md-6"><strong>Nombre d'enfants
                                                            :</strong><br><span>{{ $user->nombre_enfant }}</span>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>

                                    <div class="card shadow-sm mb-4">
                                        <div class="card-header  text-white">
                                            <h5 class="mb-0" style="padding: 5px;margin-top:10px">Informations
                                                professionnelles</h5>
                                        </div>
                                        <div class="card-body row g-3">
                                            @if ($user->type_contrat)
                                                <div class="col-md-6"><strong>Type de contrat
                                                        :</strong><br><span>{{ $user->type_contrat }}</span></div>
                                            @endif
                                            @if ($user->mode_paiement)
                                                <div class="col-md-6"><strong>Mode de paiement
                                                        :</strong><br><span>{{ $user->mode_paiement }}</span></div>
                                            @endif
                                            @if ($user->salaire)
                                                <div class="col-md-6"><strong>Salaire
                                                        :</strong><br><span>{{ number_format($user->salaire, 0, ',', ' ') }}
                                                        FCFA</span></div>
                                            @endif
                                            @if ($user->iban)
                                                <div class="col-md-6"><strong>IBAN
                                                        :</strong><br><span>{{ $user->iban }}</span></div>
                                            @endif
                                            @if ($user->bic)
                                                <div class="col-md-6"><strong>BIC
                                                        :</strong><br><span>{{ $user->bic }}</span></div>
                                            @endif
                                            @if ($user->nom_banque)
                                                <div class="col-md-6"><strong>Banque
                                                        :</strong><br><span>{{ $user->nom_banque }}</span></div>
                                            @endif
                                            @if ($user->nom_agence)
                                                <div class="col-md-6"><strong>Agence bancaire
                                                        :</strong><br><span>{{ $user->nom_agence }}</span></div>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="card shadow-sm mb-4">
                                        <div class="card-header  text-white">
                                            <h5 class="mb-0" style="padding: 5px;margin-top:10px">Documents et
                                                compétences</h5>
                                        </div>
                                        <div class="card-body row g-3">
                                            @foreach (['cv', 'permis_conduire', 'piece_identite', 'diplome', 'certificat_travail'] as $doc)
                                                @if ($user->$doc)
                                                    <div class="col-md-4">
                                                        <strong>{{ ucfirst(str_replace('_', ' ', $doc)) }}</strong><br>
                                                        <a href="{{ asset('storage/' . $user->$doc) }}" target="_blank"
                                                            class="btn btn-outline-primary btn-sm mt-1">📄
                                                            Télécharger</a>
                                                    </div>
                                                @endif
                                            @endforeach

                                            @if ($user->competence)
                                                <div class="col-md-12"><strong>Compétences
                                                        :</strong><br>{{ $user->competence }}</div>
                                            @endif
                                            @if ($user->commmentaire_completaire)
                                                <div class="col-md-12"><strong>Commentaires
                                                        :</strong><br>{{ $user->commmentaire_completaire }}</div>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="text-right">
                                        <button type="button" class="btn btn-primary"
                                            data-bs-dismiss="modal">Fermer</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach

            </div>
        </div>
    </div>
    <script>
        function searchTable() {
            const input = document.getElementById('searchInput2').value.toLowerCase();
            const rows = document.querySelectorAll('#approbationsTable2 tr');

            rows.forEach(row => {
                const rowText = row.textContent.toLowerCase();
                row.style.display = rowText.includes(input) ? '' : 'none';
            });

            const message = document.getElementById('aucun-resultat');
            const visibleRows = Array.from(rows).filter(row => row.style.display !== 'none');
            message.style.display = visibleRows.length === 0 ? 'block' : 'none';
        }
    </script>
    <!-- End Email Statistic area-->
@endsection
