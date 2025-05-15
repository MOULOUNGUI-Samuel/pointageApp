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
        <div  class="container-fluid" style="margin-left: 50px;margin-right: 50px;">
          
            <div class="row">
                <div class="col-md-3 text-center">
                    <form action="{{ route('html.import.owncloud') }}" method="POST">
                        @csrf
                        <label>Lien de partage OwnCloud :</label>
                        <input type="text" name="cloud_url" placeholder="Collez ici votre lien public" required
                            class="form-control mb-3">
                        <button type="submit" class="btn btn-primary" style="margin-top: 10px">Importer depuis le cloud</button>
                    </form>
                </div>
                <div class="col-md-6">
                    <div class="text-center">
                        <span class="text-danger">Double-cliquez pour ouvrir un dossier</span>
                    </div>
                    <ul id="file-tree" class="tree" style="font-size: 25px;margin-top: 10px;">
                        {!! afficherArborescence($imported) !!}
                    </ul>
                </div>
                <div class="col-md-3 text-center">
                    <label>Rechercher un dossier ou fichier</label>
                    <input type="text" id="searchInput" class="form-control"
                        placeholder="🔍 Rechercher un dossier ou fichier...">
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
