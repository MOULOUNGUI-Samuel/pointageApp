<?php

namespace App\Helpers;

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
        $html =
            '<ul style="background-color:white; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); border-radius: 5px; padding-top: 5px; padding-bottom: 5px;">';

        foreach ($arbre as $cle => $valeur) {
            if ($cle === '__files') {
                foreach ($valeur as $file) {
                    $html .=
                        '<li class="file-item"><i class="fa fa-file text-success"></i> <a href="' .
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
                $html .= self::afficherNiveau($valeur);
                $html .= '</li>';
            }
        }

        $html .= '</ul>';
        return $html;
    }
}
