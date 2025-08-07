@extends('layouts.master2')
@section('content2')
    <div class="section-admin container-fluid">

        <div class="row" style="margin-left: 20px; margin-right: 20px;">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="d-flex-justify-content-between mb-3 mt-3">
                    <h2 class="card-title text-primary">Gestion des utilisateurs</h2>
                    @if (session('success'))
                        <div class="alert alert-success rounded-pill alert-dismissible fade show">
                            <strong class="me-5"><i class="fas fa-check me-2"></i> {{ session('success') }}</strong>
                            <button type="button" class="btn-close custom-close" data-bs-dismiss="alert"
                                aria-label="Close"><i class="fas fa-xmark"></i></button>
                        </div>
                    @endif
                </div>
                <div class="card">

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
                                    <a href="{{ route('yodirh.formulaire_utilisateurs') }}"
                                        class="btn-action btn btn-primary" data-loader-target="ajout-utilisateur"><i
                                            class="ti ti-square-rounded-plus me-2"></i>Ajouter un utilisateur</a>
                                    <!-- Bouton de chargement (caché au départ) -->
                                    <button type="button" id="ajout-utilisateur" class="btn btn-outline-primary"
                                        style="display: none;" disabled>
                                        <i class="fas fa-spinner fa-spin me-2"></i>Charg...
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
                                        <th>Photo</th>
                                        <th>Nom</th>
                                        <th>Matricule</th>
                                        <th>Email professionnel</th>
                                        <th>Date d'embauche</th>
                                        <th>Date de fin</th>
                                        <th>Fonction</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody id="approbationsTable2">
                                    @foreach ($utilisateurs as $user)
                                        <tr>
                                            {{-- <td>{{ $user->id }}</td> --}}
                                            <td>
                                                @if ($user->photo)
                                                    <img src="{{ asset('storage/' . $user->photo) }}"
                                                        class="img-fluid rounded-circle border" alt="Photo de profil"
                                                        style="width: 40px; height: 40px;">
                                                @else
                                                    <img src="{{ asset('src/images/user.jpg') }}"
                                                        class="img-fluid rounded-circle border" alt="Photo de profil"
                                                        style="width: 40px; height: 40px;">
                                                @endif
                                            </td>
                                            <td>{{ Str::limit($user->nom . ' ' . $user->prenom, 20, '...') }}</td>
                                            <td>{{ $user->matricule }}</td>
                                            <td>{{ $user->email_professionnel }}</td>
                                            <td>{{ \Carbon\Carbon::parse($user->date_embauche)->format('d/m/y') }}</td>
                                            <td>{{ \Carbon\Carbon::parse($user->date_fin_contrat)->format('d/m/y') }}</td>
                                            <td>{{ Str::limit($user->fonction, 25, '...') }}</td>
                                            <td>
                                                <!-- Bouton initial -->
                                                <button type="button" class="btn-action btn btn-dark"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#detailsMondale{{ $user->id }}">
                                                    <i class="fas fa-eye me-2"></i> voir
                                                </button>
                                                <!-- Bouton initial -->
                                                <a href="{{ route('modif_affiche_utilisateur', $user->id) }}"
                                                    type="button" class="btn-action btn btn-primary"
                                                    data-loader-target="loader-like{{ $user->id }}">
                                                    <i class="fas fa-edit me-2"></i>Modif...
                                                </a>

                                                <!-- Bouton de chargement (caché au départ) -->
                                                <button type="button" id="loader-like{{ $user->id }}"
                                                    class="btn btn-outline-primary" style="display: none;" disabled>
                                                    <i class="fas fa-spinner fa-spin me-2"></i>Charg...
                                                </button>

                                                <button type="button" class="btn-action btn btn-danger"
                                                    data-bs-toggle="modal" data-bs-target="#archiver-{{ $user->id }}">
                                                    <i class="fas fa-archive me-2"></i>
                                                    Arch...
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
                                        <div class="modal fade" id="archiver-{{ $user->id }}" tabindex="-1"
                                            role="dialog" aria-labelledby="archiver" aria-hidden="true">
                                            <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header bg-danger">
                                                        <h4 class="modal-title text-white" id="archiver">
                                                            <i class="bi bi-trash-fill"></i> Archiver un utilisateur
                                                        </h4>
                                                    </div>
                                                    <form action="{{ route('desactiver_user', $user->id) }}" method="POST"
                                                        style="display:inline-block;">
                                                        @csrf
                                                        @method('PUT')
                                                        <div class="modal-body">
                                                            <div class="row">
                                                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                                    <div class="text-center">
                                                                        <h3>{{ $user->nom }} {{ $user->prenom }}</h3>
                                                                        </h3>
                                                                        <p>Êtes-vous sûr de vouloir archiver cet utilisateur
                                                                            ?</p>
                                                                        <p class="text-danger">Cette action est
                                                                            irréversible.</p>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary"
                                                                data-bs-dismiss="modal">Annuler</button>
                                                            <button type="submit" class="btn btn-danger">Oui,
                                                                archiver</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
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
                                <div class="modal-header bg-primary  d-flex justify-content-between align-items-center">
                                    <h4 class="modal-title text-light">Détails sur : {{ $user->nom }}
                                        {{ $user->prenom }}</h4>
                                    @if ($user->photo)
                                        <img src="{{ asset('storage/' . $user->photo) }}"
                                            class="img-fluid rounded-circle border" alt="Photo de profil"
                                            style="width: 70px; height: 70px;">
                                    @else
                                        <img src="{{ asset('src/images/user.jpg') }}"
                                            class="img-fluid rounded-circle border" alt="Photo de profil"
                                            style="width: 70px; height: 70px;">
                                    @endif
                                </div>

                                <div class="modal-body" style="max-height: 70vh; overflow-y: auto;">
                                    <div class="card shadow-sm">
                                        <div class="card-header text-white">
                                            <h5 class="mb-0" style="padding: 5px;">Informations
                                                personnelles</h5>
                                        </div>
                                        <div class="card-body row g-4">
                                            <div class="col-md-12 row g-4">
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
                                                @if ($user->numero_securite_sociale)
                                                    <div class="col-md-6"><strong>Numéro sécurité sociale
                                                            :</strong><br><span>{{ $user->numero_securite_sociale }}</span>
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

                                    <div class="card shadow-sm ">
                                        <div class="card-header  text-white">
                                            <h5 class="mb-0" style="padding: 5px;">Informations
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

                                    <div class="card shadow-sm ">
                                        <div class="card-header  text-white">
                                            <h5 class="mb-0" style="padding: 5px;">Documents et
                                                compétences</h5>
                                        </div>
                                        <div class="card-body row g-3">
                                            @foreach (['cv', 'permis_conduire', 'piece_identite', 'diplome', 'certificat_travail'] as $doc)
                                                @if ($user->$doc)
                                                    <div class="col-md-4 mb-2">
                                                        <strong>{{ ucfirst(str_replace('_', ' ', $doc)) }}</strong><br>

                                                        <a href="{{ asset('storage/' . $user->$doc) }}"
                                                            onclick="ouvrirDocument(event, '{{ asset('storage/' . $user->$doc) }}')"
                                                            class="btn btn-outline-primary btn-sm mt-1">
                                                            <i class="fas fa-file-alt me-1"></i>
                                                            Ouvrir
                                                        </a>
                                                    </div>
                                                @endif
                                            @endforeach
                                            <script>
                                                function ouvrirDocument(event, url) {
                                                    event.preventDefault(); // Empêche l'ouverture normale du lien

                                                    const width = 800; // largeur de la popup
                                                    const height = 900; // hauteur de la popup

                                                    // Calcul pour centrer la popup
                                                    const left = (window.screen.width / 2) - (width / 2);
                                                    const top = (window.screen.height / 2) - (height / 2);

                                                    // Ouvrir la popup centrée avec le document
                                                    window.open(
                                                        url,
                                                        'documentPopup',
                                                        `width=${width},height=${height},top=${top},left=${left},scrollbars=yes,resizable=yes`
                                                    );
                                                }
                                            </script>
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

                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-primary text-right"
                                        data-bs-dismiss="modal">Fermer</button>
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
