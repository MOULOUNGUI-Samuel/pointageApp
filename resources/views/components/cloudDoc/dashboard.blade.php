@extends('layouts.master2')
@section('content2')
    <style>
        .tree ul {
            list-style-type: none;
            padding-left: 20px;
        }

        .tree li {
            margin: 5px 0;
        }

        .tree .folder-toggle {
            cursor: pointer;
        }

        .tree ul {
            display: none;
        }

        .tree>ul {
            display: block;
        }
    </style>
    @php
        $dossier_info = \App\Helpers\DateHelper::dossier_info();
    @endphp
    <div class="file-manager-area mg-tb-15">
        <div class="container-fluid" style="margin-left: 50px;margin-right: 50px;">

            <div class="row">
                @if (isset($dossier_info['lienDocuments']) && !$errors->any())
                    @if (session('success') || isset($success))
                        <div class="col-md-3"></div>

                        <div class="col-lg-6 text-left" style="margin-top: 20px;">
                            <div class="alert alert-success" style="font-size: 25px">
                                {{ session('success') ?? $success }}
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true" style="font-size: 35px">&times;</span>
                                </button>
                            </div>
                        </div>
                        <div class="col-md-3"></div>
                    @endif
                @endif
            </div>
            @if ($errors->any())
                <div class="row">
                    <div class="col-md-3"></div>
                    <div class="col-md-6">
                        @foreach ($errors->all() as $error)
                            <div class="alert alert-danger text-left" style="font-size: 20px" role="alert">
                                <span><i class="icon-warning" style="font-size: 35px"></i>
                                    {!! $error !!}
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true" style="font-size:35px">&times;</span>
                                    </button>
                                </span>
                            </div>
                        @endforeach
                    </div>
                    <div class="col-md-3"></div>
                </div>
            @endif
            <div class="row">
                @if (isset($procedures))
                    <div class="col-md-3">
                        <div class="hpanel responsive-mg-b-30">
                            <div class="panel-body" id="afficheAJoutLien" style="display: none">
                                <h3 class="text-primary">Ajoute un lien OwnCloud</h3>
                                <form action="{{ route('html.import.owncloudProcedure') }}" method="POST">
                                    @csrf
                                    <div class="row">
                                        <div class="col-md-12">
                                            <label>Nom du dossier :</label>
                                            <input type="text" name="nom_lien" placeholder="Nom du dossier" required
                                                class="form-control mb-3">
                                            <input type="hidden" name="user_id" value="{{ Auth::user()->id }}" required
                                                class="form-control mb-3">
                                            <input type="hidden" name="module_id" value="{{ $module_id }}" required
                                                class="form-control mb-3">
                                        </div>
                                        <div class="col-md-12" style="margin-top: 5px">
                                            <label>Lien du OwnCloud :</label>
                                            <input type="text" name="cloud_url"
                                                placeholder="Collez ici votre lien public" required
                                                class="form-control mb-3">
                                        </div>
                                        <div class="col-md-12 text-center" style="margin-top:23px">

                                            <button type="button" class="btn btn-dark text-left"
                                                onclick="document.getElementById('afficheAJoutLien').style.display = 'none';"
                                                style="margin-right: 50px">
                                                Annuler
                                            </button>

                                            <button type="submit" class="btn btn-primary text-right">Importer
                                                depuis le
                                                cloud</button>

                                        </div>
                                    </div>
                                </form>
                            </div>
                            @if (isset($utilisateursAssocies) && count($utilisateursAssocies) > 0)
                                <div class="white-box analytics-info-cs mg-b-10 res-mg-t-30" style="margin-top: 10px;">
                                    <h4 class="box-title badge"
                                        style="font-size: 15px;background-color:#05436b;padding: 5px;border-radius: 5px;">
                                        Liste des personnes associer au dossier</h4>
                                    <div class="mb-3" style="margin-bottom: 10px;">
                                        <input type="text" class="form-control shadow border border-dark rounded"
                                            id="search" placeholder="Rechercher...">
                                    </div>
                                    <div class="recent-items-inn" style="overflow-y: auto; max-height: 400px;">
                                        <table class="table table-inner table-vmiddle">
                                            <thead>
                                                <tr>
                                                    <th>ID</th>
                                                    <th>Nom(s) et prenoms(s)</th>
                                                </tr>
                                            </thead>
                                            <tbody id="participantsTable">
                                                @foreach ($utilisateursAssocies as $participant)
                                                    <tr>
                                                        <td class="f-500 c-cyan"><img
                                                                src="{{ asset('src/images/user.jpg') }}" alt=""
                                                                width="30"
                                                                style="border: 1px solid #05436b;border-radius:50px" />
                                                        </td>
                                                        <td>{{ $participant->nom }} {{ $participant->prenom }}</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                    <script>
                                        document.addEventListener('DOMContentLoaded', function() {
                                            const searchInput = document.getElementById('search');
                                            const participantsTable = document.getElementById('participantsTable');

                                            searchInput.addEventListener('input', function() {
                                                const query = this.value.toLowerCase();
                                                participantsTable.querySelectorAll('tr').forEach(row => {
                                                    const text = row.textContent.toLowerCase();
                                                    row.style.display = text.includes(query) ? '' : 'none';
                                                });
                                            });
                                        });
                                    </script>
                                </div>
                            @endif
                        </div>
                    </div>
                    <div class="col-md-9">
                        <div class="mb-3" style="margin-bottom: 10px">
                            <label>Rechercher un dossier ou fichier</label>
                            <input type="text" id="searchInput" class="form-control py-2 border border-dark rounded"
                                placeholder="🔍 Rechercher un dossier ou fichier...">
                        </div>
                        @php
                            $currentDossier = request()->segment(count(request()->segments()));
                        @endphp
                        <div class="text-center d-flex-justify-content-between">
                            <span class="text-danger">Cliquez 2 fois pour ouvrir un dossier</span>
                            <div class="text-center d-flex-justify-content-between">
                                <button type="button" data-toggle="modal" data-target="#partageLabelsModal"
                                    class="btn btn-sm btn-primary"
                                    style="font-size:15px;margin-right:5px;margin-top:-10px">
                                    <i class="fa fa-share-alt"></i> Partager le dossier
                                </button>
                                <form action="{{ route('lienDoc.destroy', $currentDossier) }}" method="POST"
                                    onsubmit="return confirm('');" class="ms-2" style="margin-right: 5px">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" data-toggle="modal" data-target="#floatingLabelsModal"
                                        class="btn btn-sm btn-danger" style="font-size:15px;margin-top:5px">
                                        <i class="fa fa-trash"></i> Supprimer le dossier
                                    </button>
                                </form>
                            </div>
                        </div>
                        <div class="modal fade" id="floatingLabelsModal" tabindex="-1" role="dialog"
                            aria-labelledby="floatingLabelsModalLabel" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header" style="background-color:rgba(152, 15, 15, 0.898)">
                                        <h4 class="modal-title" id="floatingLabelsModal" style="color: white">
                                            Suppresion du dossier
                                        </h4>
                                    </div>
                                    <form action="{{ route('lienDoc.destroy', $currentDossier) }}" method="POST"
                                        class="ms-2" style="margin-right: 5px">
                                        @csrf
                                        @method('DELETE')
                                        <div class="modal-body text-center">
                                            <h4 class="text-danger">Êtes-vous sûr de vouloir supprimer ce dossier ?</h4>
                                            <h3><i class="fa fa-folder text-warning "
                                                    style="margin-right: 5px"></i>{{ preg_replace('/-\\d{8}(-\\d+)*$/', '', $currentDossier) }}
                                            </h3>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary"
                                                data-dismiss="modal">Annuler</button>
                                            <button type="submit" class="btn btn-danger">Confirmer la
                                                suppression</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <div class="modal fade" id="partageLabelsModal" tabindex="-1" role="dialog"
                            aria-labelledby="partageLabelsModal" aria-hidden="true">
                            <div class="modal-dialog modal-lg" role="document">
                                <div class="modal-content">
                                    <div class="modal-header bg-primary">
                                        <h4 class="modal-title" id="partageLabelsModal" style="color: white">
                                            Partager le dossier
                                        </h4>
                                    </div>
                                    <form action="{{ route('partageFichier') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="nom_lien" value="{{ $currentDossier }}">
                                        <div class="modal-body" style="max-height: 500px; overflow-y: auto;">
                                            <div class="mb-3" style="margin-bottom: 10px;">
                                                <div class="mb-2"
                                                    style="display: flex; align-items: center;justify-content: space-between;">
                                                    <label>Rechercher un utilisateur</label>
                                                    <span id="checkedCount" class="badge ml-2"
                                                        style="font-size:16px;background-color:lightslategrey">0
                                                        sélectionné(s)</span>
                                                </div>

                                                <input type="text" id="searchInputUtilisateur"
                                                    class="form-control shadow rounded"
                                                    placeholder="🔍 Rechercher un utilisateur...">
                                            </div>
                                            <table id="data-table-basic" class="table table-striped">
                                                <thead>
                                                    <tr class="bg-primary" style="color: white">
                                                        <th data-field="state" data-checkbox="true"></th>
                                                        {{-- <th>ID</th> --}}
                                                        <th>Nom</th>
                                                        <th>Prénom</th>
                                                        <th>Entreprise</th>
                                                        <th>Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="approbationsTable2">
                                                    @foreach ($utilisateursNonAssocies as $user)
                                                        <tr style="cursor:pointer;"
                                                            onclick="this.querySelector('input[type=checkbox]').click();">
                                                            <td></td>
                                                            {{-- <td>{{ $user->id }}</td> --}}
                                                            <td>{{ $user->nom }}</td>
                                                            <td>{{ $user->prenom }}</td>
                                                            <td>{{ $user->entreprise->nom_entreprise ?? '-' }}</td>
                                                            <td>
                                                                <input type="checkbox" name="created_at[]"
                                                                    value="{{ $user->created_at }}"
                                                                    class="form-check-input"
                                                                    onclick="event.stopPropagation();">
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>

                                            <script>
                                                document.addEventListener('DOMContentLoaded', function() {
                                                    const input = document.getElementById('searchInputUtilisateur');
                                                    input.addEventListener('input', function() {
                                                        const query = this.value.toLowerCase();
                                                        document.querySelectorAll('#approbationsTable2 tr').forEach(row => {
                                                            const text = row.textContent.toLowerCase();
                                                            row.style.display = text.includes(query) ? '' : 'none';
                                                        });
                                                    });
                                                });
                                            </script>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary"
                                                data-dismiss="modal">Annuler</button>
                                            <button type="submit" class="btn btn-primary">Partager le dossier</button>
                                        </div>
                                    </form>


                                </div>
                            </div>
                        </div>

                        <ul id="file-tree" class="tree" style="font-size: 25px;margin-top: 10px;">
                            {!! \App\Helpers\FileTreeHelper::afficherArborescence($procedures) !!}
                        </ul>


                    </div>
                @else
                    <div class="col-md-3">
                        <div class="hpanel responsive-mg-b-30" id="afficheAJoutLien" style="display: none">
                            <div class="panel-body">
                                <h3 class="text-primary">Ajoute un lien OwnCloud</h3>
                                <form action="{{ route('html.import.owncloudProcedure') }}" method="POST">
                                    @csrf
                                    <div class="row">
                                        <div class="col-md-12">
                                            <label>Nom du dossier :</label>
                                            <input type="text" name="nom_lien" placeholder="Nom du dossier" required
                                                class="form-control mb-3">
                                            <input type="hidden" name="user_id" value="{{ Auth::user()->id }}" required
                                                class="form-control mb-3">
                                            <input type="hidden" name="module_id" value="{{ $module_id }}" required
                                                class="form-control mb-3">
                                        </div>
                                        <div class="col-md-12" style="margin-top: 5px">
                                            <label>Lien du OwnCloud :</label>
                                            <input type="text" name="cloud_url"
                                                placeholder="Collez ici votre lien public" required
                                                class="form-control mb-3">
                                        </div>
                                        <div class="col-md-12 text-center" style="margin-top:23px">

                                            <button type="button" class="btn btn-dark text-left"
                                                onclick="document.getElementById('afficheAJoutLien').style.display = 'none';"
                                                style="margin-right: 50px">
                                                Annuler
                                            </button>

                                            <button type="submit" class="btn btn-primary text-right">Importer
                                                depuis le
                                                cloud</button>

                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class=" text-center" style="margin-top: 20px;">
                            <div class="alert alert-info" style="font-size: 20px">
                                Veuillez choisir un dossier !
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                    </div>
                @endif
            </div>

        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            function updateCheckedCount() {
                const count = document.querySelectorAll('#approbationsTable2 input[type=checkbox]:checked').length;
                document.getElementById('checkedCount').textContent = count + ' sélectionné(s)';
            }
            document.querySelectorAll('#approbationsTable2 input[type=checkbox]').forEach(cb => {
                cb.addEventListener('change', updateCheckedCount);
            });
            updateCheckedCount();
        });
    </script>
    <script>
        function openInModal(url) {
            const modal = new bootstrap.Modal(document.getElementById('importedModal'));
            document.getElementById('importedModalContent').innerHTML = 'Chargement...';

            fetch(url)
                .then(res => res.text())
                .then(html => {
                    document.getElementById('importedModalContent').innerHTML = html;
                    modal.show();
                })
                .catch(err => {
                    document.getElementById('importedModalContent').innerHTML = 'Erreur de chargement.';
                });
        }
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const toggleBtn = document.getElementById('toggleTreeBtn');
            const formSection = document.getElementById('afficheAJoutLien');

            if (toggleBtn && formSection) {
                toggleBtn.addEventListener('click', function(e) {
                    e.preventDefault();
                    formSection.style.display = 'none';
                });
            }
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Dossiers repliables
            document.querySelectorAll('.folder-toggle').forEach(toggle => {
                toggle.addEventListener('click', function() {
                    const next = this.nextElementSibling;
                    if (next && next.tagName === 'UL') {
                        next.style.display = next.style.display === 'none' ? 'block' : 'none';
                    }
                });
            });

            // Recherche
            const input = document.getElementById('searchInput');
            input.addEventListener('input', function() {
                const query = this.value.toLowerCase();
                document.querySelectorAll('#file-tree li').forEach(el => {
                    const text = el.textContent.toLowerCase();
                    el.style.display = text.includes(query) ? 'list-item' : 'none';
                });
            });
        });
    </script>
@endsection
