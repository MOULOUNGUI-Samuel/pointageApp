<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Route;

class FileTreeHelper
{
    public static function afficherArborescence($files)
    {
        $tree = [];

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

        return self::afficherNiveau($tree);
    }

    private static function afficherNiveau($arbre)
    {
        $html = '<ul style="background-color:white; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); border-radius: 5px; padding-top: 5px; padding-bottom: 5px;">';

        foreach ($arbre as $cle => $valeur) {
            if ($cle === '__files') {
                foreach ($valeur as $file) {
                    $routeName = 'imported.' . str_replace('/', '.', str_replace('.blade.php', '', $file['full']));
                    if (Route::has($routeName)) {
                        $url = route($routeName);
                        $popupFunction = 'popup_' . md5($file['full']);
                        $html .= '<li class="file-item">
                                <i class="fa fa-file text-success"></i>
                                <a href="#" onclick="' . $popupFunction . '(); return false;">' . htmlspecialchars($file['name']) . '</a>
                              </li>
                              <script>
                                function ' . $popupFunction . '() {
                                    const width = 1300;
                                    const height = 650;
                                    const left = (window.screen.width / 2) - (width / 2);
                                    const top = (window.screen.height / 2) - (height / 2);
                                    window.open("' . $url . '", "popupWindow", "width=" + width + ",height=" + height + ",top=" + top + ",left=" + left + ",scrollbars=yes,resizable=no");
                                }
                              </script>';
                    }
                }
            } else {
                $html .= '<li class="folder-item">
                        <span class="folder-toggle">
                            <i class="fa fa-folder text-warning"></i> ' . htmlspecialchars(preg_replace('/-\\d{8}(-\\d+)*$/', '',$cle)) . '
                        </span>';
                $html .= self::afficherNiveau($valeur);
                $html .= '</li>';
            }
        }

        $html .= '</ul>';
        return $html;
    }
}
