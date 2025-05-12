{{-- @extends('layouts.master2')
@section('content2')
    <style>
        .container {
            background-color: #fff;
            border-radius: 8px;
            padding: 30px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        h2 {
            color: #102147;
            font-weight: bold;
            border-bottom: 2px solid #3498db;
            padding-bottom: 10px;
        }

        .search-input {
            margin-bottom: 20px;
            padding: 12px;
            border-radius: 6px;
            border: 1px solid #ccc;
            width: 100%;
            font-size: 16px;
        }

        .file-list ul {
            list-style: none;
            padding-left: 20px;
        }

        .file-list li {
            margin: 6px 0;
            padding: 5px;
            border-radius: 4px;
            transition: background-color 0.2s ease;
        }

        .file-list li:hover {
            background-color: #f1f9ff;
        }

        .file-icon {
            margin-right: 6px;
        }

        .folder-label {
            font-weight: bold;
            color: #f39c12;
        }

        .file-link {
            color: #007bff;
            text-decoration: none;
        }

        .file-link:hover {
            text-decoration: underline;
        }

        .hidden {
            display: none;
        }
    </style>

    <div class="container mt-4">
        <h2>📁 Sommaire des fichiers extraits</h2>

        <input type="text" id="searchInput" class="search-input" placeholder="🔍 Rechercher un fichier ou un dossier...">

        <div class="file-list mt-3" id="fileTree">
            @if (empty($structure))
                <p class="text-muted">Aucun fichier trouvé.</p>
            @else
                {!! afficherStructure($structure) !!}
            @endif
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const searchInput = document.getElementById('searchInput');
            const tree = document.getElementById('fileTree');

            searchInput.addEventListener('input', function () {
                const term = this.value.toLowerCase();

                tree.querySelectorAll('li').forEach(function (item) {
                    const text = item.textContent.toLowerCase();
                    if (text.includes(term)) {
                        item.classList.remove('hidden');
                        showParents(item);
                    } else {
                        item.classList.add('hidden');
                    }
                });

                function showParents(el) {
                    let parent = el.parentElement;
                    while (parent && parent.tagName.toLowerCase() === 'ul') {
                        parent.previousElementSibling?.classList.remove('hidden');
                        parent = parent.parentElement;
                    }
                }
            });
        });
    </script>
@endsection

@php
function afficherStructure($items)
{
    $html = '<ul>';
    foreach ($items as $item) {
        if ($item['type'] === 'directory') {
            $html .= '<li><span class="file-icon">📁</span><span class="folder-label">' . e($item['name']) . '</span>';
            $html .= afficherStructure($item['children']);
            $html .= '</li>';
        } else {
            $path = storage_path('app/tmp_owncloud_extracted/' . $item['path']);
            $webPath = asset('storage/tmp_owncloud_extracted/' . $item['path']); // For future if needed
            $ext = strtolower(pathinfo($item['name'], PATHINFO_EXTENSION));
            $url = asset('tmp-preview?f=' . urlencode($item['path'])); // Laravel route vers preview

            $openable = in_array($ext, ['pdf', 'jpg', 'jpeg', 'png', 'gif', 'html', 'htm']);
            $target = $openable ? ' target="_blank"' : '';

            $html .= '<li><span class="file-icon">📄</span><a class="file-link" href="' . $url . '" ' . $target . '>' . e($item['name']) . '</a></li>';
        }
    }
    $html .= '</ul>';
    return $html;
}
@endphp --}}
