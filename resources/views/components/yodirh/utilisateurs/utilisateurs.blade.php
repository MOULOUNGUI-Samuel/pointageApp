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
                                        <a href="javascript:void(0);" class="dropdown-toggle" data-bs-toggle="dropdown">
                                            <i class="ti ti-filter me-2"></i>
                                            Filtre de pointage
                                        </a>
                                        <div class="dropdown-menu dropdown-menu-end">
                                            <ul>
                                                <li>
                                                    <a href="javascript:void(0);" class="dropdown-item filter-btn"
                                                        data-filter="peut">
                                                        <i class="ti ti-eye me-1"></i>
                                                        Peut pointer
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="javascript:void(0);"
                                                        class="dropdown-item filter-btn text-warning" data-filter="ne_peut">
                                                        <i class="ti ti-eye me-1"></i>
                                                        Ne pointe pas
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="javascript:void(0);"
                                                        class="dropdown-item filter-btn text-primary" data-filter="all">
                                                        <i class="ti ti-reload me-1"></i>Tous
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                    <!-- Filtre archive -->
                                    <div class="dropdown me-2">
                                        <a href="javascript:void(0);" class="dropdown-toggle text-primary" data-bs-toggle="dropdown">
                                            <i class="ti ti-archive me-2"></i>
                                            Filtre d'archivage...
                                        </a>
                                        <div class="dropdown-menu dropdown-menu-end">
                                            <ul>
                                                <li>
                                                    <a href="javascript:void(0);" class="dropdown-item filter-btn2"
                                                        data-type="archive" data-filter="active">
                                                        <i class="ti ti-users me-1"></i>
                                                        utilisateurs actifs
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="javascript:void(0);"
                                                        class="dropdown-item filter-btn2 text-danger" data-type="archive"
                                                        data-filter="non_active">
                                                        <i class="ti ti-users me-1"></i>
                                                        utilisateurs archivés
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="javascript:void(0);"
                                                        class="dropdown-item filter-btn2 text-primary" data-type="archive"
                                                        data-filter="allarchive">
                                                        <i class="ti ti-reload me-1"></i>
                                                        Tous
                                                    </a>
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
                                        <i class="fas fa-spinner fa-spin me-2"></i>Chargement...
                                    </button>
                                </div>
                            </div>
                        </div>
                        <!-- /Search -->
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="example2" class="w-full table table-striped table-bordered">
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
                                        <tr data-pointer="{{ $user->statut ? '1' : '0' }}"
                                            data-archived="{{ $user->statu_user ? '0' : '1' }}">
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
                                            <td class="text-center">{{ $user->matricule }} <br>
                                                <span class="  {{ $user->statu_user==1 ? '' : 'px-2 rounded  border border-danger text-danger' }}">{{ $user->statu_user==1 ? '' : 'Inactif' }}</span>
                                            </td>
                                            <td>{{ $user->email_professionnel }}</td>
                                            <td>{{ \Carbon\Carbon::parse($user->date_embauche)->format('d/m/Y') }}</td>
                                            <td>{{ \Carbon\Carbon::parse($user->date_fin_contrat)->format('d/m/Y') }}</td>
                                            <td class="text-center">{{ Str::limit($user->fonction, 25, '...') }}<br>
                                                <span class="  {{ ($user->statut==1 && $user->statu_user==1)  ? '' : 'px-2 rounded  border border-dark text-dark' }}">{{ ($user->statut==1 && $user->statu_user==1) ? '' : 'Ne pointe pas' }}</span>
                                            </td>
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

                                                <div class="btn-group dropstart my-1">
                                                    <button type="button" class="btn btn-primary dropdown-toggle mb-0"
                                                        data-bs-toggle="dropdown" aria-expanded="false">
                                                        Actions
                                                    </button>
                                                    <ul class="dropdown-menu">
                                                        <li class="mb-1">
                                                            <a class="dropdown-item" href="#"
                                                                data-bs-toggle="modal"
                                                                data-bs-target="#absenceModal-{{ $user->id }}">
                                                                <i class="fas fa-file-signature me-2"></i> Demandes
                                                                d'absence
                                                            </a>
                                                        </li>

                                                        <li>
                                                            <a class="dropdown-item {{ ($user->statu_user===0) ? 'text-success' : 'text-danger' }}" href="#"
                                                                data-bs-toggle="modal"
                                                                data-bs-target="#archiver-{{ $user->id }}"><i
                                                                    class="fas fa-user me-2"></i>
                                                                    {{ ($user->statu_user===0) ? 'Activer' : 'Désactiver' }}</a>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </td>
                                        </tr>
                                        {{-- MODALE UNIQUE POUR CE USER --}}
                                        <div class="modal fade" id="absenceModal-{{ $user->id }}" tabindex="-1"
                                            role="dialog" aria-hidden="true" wire:ignore.self>
                                            <div class="modal-dialog modal-fullscreen p-3" role="document">
                                                <div class="modal-content" style="background-color: rgb(240, 243, 243)">
                                                    <div class="modal-header" style="background-color:white">
                                                        <h4 class="modal-title">Demandes d'absence : {{ $user->nom }}
                                                            {{ $user->prenom }}</h4>

                                                        <button type="button" class="btn-close"
                                                            data-bs-dismiss="modal"></button>
                                                    </div>
                                                    <div class="modal-body p-0 mx-3">
                                                        @livewire('absences.unique-demand-checker', ['forUserId' => $user->id, 'modalId' => 'absenceModal-' . $user->id], key('unique-' . $user->id))
                                                    </div>
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
                <div class="modal fade" id="archiver-{{ $user->id }}" tabindex="-1"
                    role="dialog" aria-labelledby="archiver" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header {{ ($user->statu_user===0) ? 'bg-success' : 'bg-danger' }} ">
                                <h4 class="modal-title text-white" id="archiver">
                                    <i class="bi bi-trash-fill"></i>{{ ($user->statu_user===0) ? 'Activé cet utilisateur' : 'Désactivé cet utilisateur' }} 
                                </h4>
                            </div>
                            <form action="{{ route('desactiver_user', $user->id) }}"
                                method="POST" style="display:inline-block;">
                                @csrf
                                @method('PUT')
                                <div class="modal-body">
                                    <div class="row">
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                            <div class="text-center">
                                                <h3 class="mb-2">{{ $user->nom }} {{ $user->prenom }}</h3>
                                                </h3>
                                                <p class="mb-2">Êtes-vous sûr de vouloir {{ ($user->statu_user===0) ? 'activé' : 'désactiver' }} cet utilisateur
                                                    ?</p>
                                                <div class="text-center">
                                                    @if ($user->photo)
                                                    <img src="{{ asset('storage/' . $user->photo) }}"
                                                        class="img-fluid rounded-circle border" alt="Photo de profil"
                                                        style="width: 90px; height: 90px;">
                                                @else
                                                    <img src="{{ asset('src/images/user.jpg') }}"
                                                        class="img-fluid rounded-circle border" alt="Photo de profil"
                                                        style="width: 90px; height: 90px;">
                                                @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary"
                                        data-bs-dismiss="modal">Annuler</button>
                                    <button type="submit" class="btn {{ ($user->statu_user===0) ? 'btn-success' : 'btn-danger' }}">Oui,
                                        {{ ($user->statu_user===0) ? 'activé' : 'désactiver' }} </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
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
                                        <div
                                            class="card-header text-white d-flex justify-content-between align-items-center">
                                            <h5 class="mb-0" style="padding: 5px;">Informations
                                                personnelles</h5>

                                            @if (Auth::user()->super_admin === 1)
                                                @if ($user->statut === 1)
                                                    <a href="{{ route('statut_pointage', $user->id) }}"
                                                        class="btn-action btn btn-warning text-right"
                                                        data-loader-target="non{{ $user->id }}">Ne valide pas la
                                                        présence</a>
                                                    <button type="button" id="non{{ $user->id }}"
                                                        class="btn btn-outline-warning" style="display: none;" disabled>
                                                        <i class="fas fa-spinner fa-spin me-2"></i>Chargement...
                                                    </button>
                                                @else
                                                    <a href="{{ route('statut_pointage', $user->id) }}"
                                                        class="btn-action btn btn-dark text-right"
                                                        data-loader-target="oui{{ $user->id }}">Valide la presence</a>
                                                    <button type="button" id="oui{{ $user->id }}"
                                                        class="btn btn-outline-dark" style="display: none;" disabled>
                                                        <i class="fas fa-spinner fa-spin me-2"></i>Chargement...
                                                    </button>
                                                @endif
                                            @endif
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
        document.addEventListener('DOMContentLoaded', function() {
            const filterArchiveButtons = document.querySelectorAll('.filter-btn2[data-type="archive"]');
            const rows = document.querySelectorAll('#approbationsTable2 tr');
        
            function applyDefault() {
                rows.forEach(row => {
                    const isArchived = row.dataset.archived === "1";
                    row.style.display = isArchived ? "none" : "";
                });
            }
        
            // appliquer par défaut (utilisateurs actifs seulement)
            applyDefault();
        
            filterArchiveButtons.forEach(btn => {
                btn.addEventListener('click', function() {
                    const filter = this.dataset.filter;
        
                    rows.forEach(row => {
                        const isArchived = row.dataset.archived === "1";
        
                        if (filter === "allarchive") {
                            row.style.display = "";
                        } else if (filter === "active" && !isArchived) {
                            row.style.display = "";
                        } else if (filter === "non_active" && isArchived) {
                            row.style.display = "";
                        } else {
                            row.style.display = "none";
                        }
                    });
                });
            });
        });
        </script>
        
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const filterButtons = document.querySelectorAll('.filter-btn');
            const rows = document.querySelectorAll('#approbationsTable2 tr');

            filterButtons.forEach(btn => {
                btn.addEventListener('click', function() {
                    const filter = this.dataset.filter;

                    rows.forEach(row => {
                        const canPoint = row.dataset.pointer === "1";

                        if (filter === "all") {
                            row.style.display = "";
                        } else if (filter === "peut" && canPoint) {
                            row.style.display = "";
                        } else if (filter === "ne_peut" && !canPoint) {
                            row.style.display = "";
                        } else {
                            row.style.display = "none";
                        }
                    });
                });
            });
        });
    </script>

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
