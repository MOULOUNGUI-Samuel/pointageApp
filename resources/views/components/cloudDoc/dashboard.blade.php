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
                        <div class="col-md-4"></div>

                        <div class="col-lg-4 text-center" style="margin-top: 20px;">
                            <div class="alert alert-success" style="font-size: 20px">
                                {{ session('success') ?? $success }}
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        </div>
                        <div class="col-md-4"></div>
                    @endif
                @endif
            </div>
            <div class="row">
                @if ($errors->any())
                    <div class="col-md-2"></div>
                    <div class="col-md-8">
                        <div class="alert alert-danger text-left" style="font-size: 16px" role="alert">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li style="display: flex; justify-content: space-between;">
                                        <span><i class="icon-warning" style="font-size: 20px"></i>
                                            <p>{!! $error !!}</p>
                                        </span>
                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                    <div class="col-md-2"></div>
                @endif
            </div>
            <div class="row">
                @if (isset($procedures))
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
                    <div class="col-md-9">
                        <div class="mb-3" style="margin-bottom: 10px">
                            <label>Rechercher un dossier ou fichier</label>
                            <input type="text" id="searchInput" class="form-control"
                                placeholder="🔍 Rechercher un dossier ou fichier...">
                        </div>
                        @php
                            $currentDossier = request()->segment(count(request()->segments()));
                        @endphp
                        <div class="text-center d-flex-justify-content-between">
                            <span class="text-danger">Double-cliquez pour ouvrir un dossier</span>
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
                                                    style="margin-right: 5px"></i>{{ $currentDossier }}</h3>
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
