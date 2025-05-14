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

    <div class="file-manager-area mg-tb-15">
        <div class="container-fluid" style="margin-left: 50px;margin-right: 50px;">
            <div class="row" id="afficheAJoutLien" style="display: none">
                <div class="col-md-2">
                </div>
                <div class="col-md-8">
                    <form action="{{ route('html.import.owncloudProcedure') }}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-md-5">
                                <label>Nom du dossier :</label>
                                <input type="text" name="nom_lien" placeholder="Nom du dossier" required
                                    class="form-control mb-3">
                                <input type="hidden" name="user_id" value="{{ Auth::user()->id }}" required
                                    class="form-control mb-3">
                                <input type="hidden" name="module_id" value="{{ $module_id }}" required
                                    class="form-control mb-3">
                            </div>
                            <div class="col-md-5">
                                <label>Lien du OwnCloud :</label>
                                <input type="text" name="cloud_url" placeholder="Collez ici votre lien public" required
                                    class="form-control mb-3">
                            </div>
                            <div class="col-md-2 text-left">
                                <button type="submit" class="btn btn-primary" style="margin-top:23px">Importer depuis le
                                    cloud</button>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="col-md-2">
                </div>
            </div>

            <div class="row mt-2" style="margin-top: 20px;margin-bottom: 20px;">
                <div class="col-md-3"></div>
                <div class="col-md-6 mb-3">
                    <label>Rechercher un dossier ou fichier</label>
                    <input type="text" id="searchInput" class="form-control"
                        placeholder="🔍 Rechercher un dossier ou fichier...">
                </div>
                <div class="col-md-3"></div>
            </div>
            <div class="row">
                <div class="col-md-3 col-md-3 col-sm-3 col-xs-12">
                    <div class="hpanel responsive-mg-b-30">
                        <div class="panel-body">
                            <a class="btn btn-primary btn-block" style="margin-bottom: 10px;color:white"
                                onclick="document.getElementById('afficheAJoutLien').style.display = 'block';">
                                AJOUTER UN LIEN CLOUD
                            </a>
                            <div class="text-center">
                                <h3 class="text-info">Gestion des fichiers par liens</h3>
                                <p class="text-muted" style="font-size:15px">Gérez vos fichiers et dossiers</p>
                            </div>
                            <ul class="h-list m-t">
                                @foreach ($lienDocuments as $lienDocument)
                                    <li class="active">
                                        <a href="{{ route('html.import.owncloudProcedure') }}"
                                            onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                            <i class="fa fa-folder text-warning"
                                                style="margin-right: 8px"></i>{{ $lienDocument->nom_lien }}</a>

                                        <form id="logout-form" action="{{ route('html.import.owncloudProcedure') }}"
                                            method="POST" style="display: none;">
                                            @csrf
                                            <input type="hidden" name="user_id" value="{{ Auth::user()->id }}" required
                                                class="form-control mb-3">
                                            <input type="hidden" name="module_id" value="{{ $module_id }}" required
                                                class="form-control mb-3">
                                            <input type="hidden" name="cloud_url"
                                                value="{{ $lienDocument->lien }}" required
                                                class="form-control mb-3">
                                        </form>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="text-center">
                        <span class="text-danger">Double-cliquez pour ouvrir un dossier</span>
                    </div>
                    <ul id="file-tree" class="tree" style="font-size: 25px;margin-top: 10px;">
                        {!! afficherArborescence($imported) !!}
                    </ul>
                </div>
            </div>


            @php
                function afficherArborescence($files)
                {
                    $tree = [];

                    // Regrouper fichiers par chemin
                    foreach ($files as $view) {
                        $parts = explode('.', $view);
                        $current = &$tree;

                        foreach ($parts as $i => $part) {
                            if ($i === count($parts) - 1) {
                                $current['__files'][] = [
                                    'name' => $part,
                                    'full' => $view,
                                ];
                            } else {
                                $current[$part] = $current[$part] ?? [];
                                $current = &$current[$part];
                            }
                        }
                    }

                    return afficherNiveau($tree);
                }

                function afficherNiveau($arbre)
                {
                    $html =
                        '<ul style="background-color:white; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); border-radius: 5px; padding-top: 5px; padding-bottom: 5px;">';
                    foreach ($arbre as $cle => $valeur) {
                        if ($cle === '__files') {
                            foreach ($valeur as $file) {
                                $html .=
                                    '<li class="file-item" ><i class="fa fa-file text-success"></i> <a href="' .
                                    route('imported.' . $file['full']) .
                                    '" target="_blank">' .
                                    $file['name'] .
                                    '</a></li>';
                            }
                        } else {
                            $html .=
                                '<li class="folder-item"><span class="folder-toggle"><i class="fa fa-folder text-warning"></i> ' .
                                $cle .
                                '</span>';
                            $html .= afficherNiveau($valeur);
                            $html .= '</li>';
                        }
                    }
                    $html .= '</ul>';
                    return $html;
                }
            @endphp


        </div>
    </div>
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
