<?php

namespace App\Http\Controllers;

use App\Models\LienDoc;
use Illuminate\Support\Facades\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use ZipArchive;
use Normalizer;

class DocumentController extends Controller
{
    //
    // public function index()
    // {
    //     $targetPath = resource_path('views/components/yodirh/imported');
    //     $imported = [];

    //     if (File::exists($targetPath)) {
    //         $files = File::files($targetPath);

    //         foreach ($files as $file) {
    //             $fileName = pathinfo($file->getFilename(), PATHINFO_FILENAME);
    //             $imported[] = $fileName;
    //         }
    //     }
    //     return view('components.yodirh.documents', compact('imported'));
    // }
    public function index()
    {
        $basePath = resource_path('views/components/yodirh/imported');
        $imported = [];

        if (File::exists($basePath)) {
            $files = File::allFiles($basePath);

            foreach ($files as $file) {
                if ($file->getExtension() !== 'php') continue;

                // Extrait la partie relative du chemin
                $relativePath = str_replace($basePath . DIRECTORY_SEPARATOR, '', $file->getPathname());

                // Convertit en nom de vue : HTML/autre_contenu.blade.php => HTML.autre_contenu
                $viewName = str_replace(['/', '\\'], '.', str_replace('.blade.php', '', $relativePath));

                $imported[] = $viewName;
            }
        }

        return view('components.yodirh.documents', compact('imported'));
    }
    public function indexprocedure($nom_lien)
    {

        if ($nom_lien == 'procedures') {
            $basePath = resource_path('views/components/yodirh/imported');
        } else {
            $basePath = resource_path('views/components/yodirh/imported/' . $nom_lien);
        }
        $lienDocuments = LienDoc::where('entreprise_id', session('entreprise_id'))->get();

        $procedures = [];
        $baseImportedPath = resource_path('views/components/yodirh/imported');

        $Dossiers = LienDoc::where('entreprise_id', session('entreprise_id'))->get()->filter(function ($doc) use ($baseImportedPath) {
            $path = $baseImportedPath . '/' . $doc->nom_lien;
            return File::isDirectory($path) && collect(File::allFiles($path))->contains(function ($file) {
                return $file->getExtension() === 'php';
            });
        })->values(); // Réindexer proprement

        foreach ($Dossiers as $doc) {
            $nom_lien = $doc->nom_lien;
            $basePath = $baseImportedPath . '/' . $nom_lien;

            $files = File::allFiles($basePath);

            foreach ($files as $file) {
                if ($file->getExtension() !== 'php') continue;

                $relativePath = str_replace($basePath . DIRECTORY_SEPARATOR, '', $file->getPathname());
                $viewName = $nom_lien . '.' . str_replace(['/', '\\'], '.', str_replace('.blade.php', '', $relativePath));

                $procedures[] = $viewName;
            }
        }


        return view('components.smi.procedureOperationnelles', compact('procedures', 'lienDocuments'))
            ->with('procedures', $procedures)
            ->with('success', 'Les données ont été chargées avec succès.');
    }

    // public function import(Request $request)
    // {
    //     $files = $request->file('html_files');
    //     $targetPath = resource_path('views/components/yodirh/imported');
    //     $imported = [];

    //     if (!File::exists($targetPath)) {
    //         File::makeDirectory($targetPath, 0755, true);
    //     }

    //     foreach ($files as $file) {
    //         $fileName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
    //         $safeName = preg_replace('/[^a-zA-Z0-9_\-]/', '_', $fileName);
    //         $content = file_get_contents($file->getRealPath());

    //         File::put($targetPath . '/' . $safeName . '.blade.php', $content);
    //         $imported[] = $safeName;
    //     }

    //     return redirect()->route('html.import.affiche')
    //         ->with('success', 'Fichiers importés avec succès !');
    // }

    // public function importFromOwncloud(Request $request)
    // {
    //     $url = $request->input('cloud_url');

    //     // Télécharger le .zip
    //     $zipContent = @file_get_contents($url . '/download');
    //     if ($zipContent === false) {
    //         return back()->withErrors(['Impossible de récupérer les fichiers depuis le lien.']);
    //     }

    //     // Créer un fichier temporaire zip
    //     $tmpZipPath = storage_path('app/tmp_owncloud_import.zip');
    //     File::put($tmpZipPath, $zipContent);

    //     // Extraire le zip
    //     $zip = new ZipArchive;
    //     if ($zip->open($tmpZipPath) === true) {
    //         $extractPath = storage_path('app/tmp_owncloud_extracted');
    //         if (!File::exists($extractPath)) {
    //             File::makeDirectory($extractPath, 0755, true);
    //         }

    //         $zip->extractTo($extractPath);
    //         $zip->close();

    //         // Lister les fichiers .html
    //         $htmlFiles = File::allFiles($extractPath);
    //         $targetPath = resource_path('views/components/yodirh/imported');

    //         if (!File::exists($targetPath)) {
    //             File::makeDirectory($targetPath, 0755, true);
    //         }

    //         $imported = [];

    //         foreach ($htmlFiles as $file) {
    //             if ($file->getExtension() !== 'html') continue;

    //             $originalName = $file->getFilenameWithoutExtension();
    //             $safeName = preg_replace('/[^a-zA-Z0-9_\-]/', '_', $originalName);
    //             $content = File::get($file->getRealPath());

    //             $bladePath = $targetPath . '/' . $safeName . '.blade.php';

    //             // Supprime si déjà présent
    //             if (File::exists($bladePath)) {
    //                 File::delete($bladePath);
    //             }

    //             // Écrit le nouveau contenu
    //             File::put($bladePath, $content);
    //             $imported[] = $safeName;
    //         }

    //         // Nettoyer les fichiers temporaires
    //         File::delete($tmpZipPath);
    //         File::deleteDirectory($extractPath);
    //         return redirect()->back()->with('success', 'Fichier ajoutée avec succès', 'Fichiers HTML importés : ' . implode(', ', $imported));
    //     }

    //     return back()->withErrors(['Erreur lors de l\'extraction du fichier ZIP.']);
    // }

    /**
     * Nettoie une chaîne en supprimant les accents.
     */
    private function removeAccents($string)
    {
        if (!class_exists('Normalizer')) {
            return $string; // fallback au cas où intl n'est pas installé
        }

        // Normalisation unicode (NFD) + suppression des diacritiques
        $string = Normalizer::normalize($string, Normalizer::FORM_D);
        return preg_replace('/[\p{Mn}]/u', '', $string);
    }



    public function importFromOwncloud(Request $request)
    {

        $url = $request->input('cloud_url');

        $zipContent = @file_get_contents($url . '/download');

        if ($zipContent === false) {
            return back()->withErrors(['Impossible de récupérer les fichiers depuis le lien.']);
        }

        // 1. Télécharger l'archive ZIP temporaire
        $tmpZipPath = storage_path('app/tmp_owncloud_import.zip');
        File::put($tmpZipPath, $zipContent);

        // 2. Extraire le ZIP temporairement
        $extractPath = storage_path('app/tmp_owncloud_extracted');
        File::deleteDirectory($extractPath);
        File::makeDirectory($extractPath, 0755, true);

        $zip = new ZipArchive;
        if ($zip->open($tmpZipPath) === true) {
            $zip->extractTo($extractPath);
            $zip->close();
        } else {
            return back()->withErrors(['Erreur lors de l\'extraction du fichier ZIP.']);
        }

        // 3. Préparer le dossier cible
        $baseViewPath = resource_path('views/components/yodirh/imported');
        File::makeDirectory($baseViewPath, 0755, true, true);

        // 4. Parcourir les fichiers HTML extraits
        $htmlFiles = File::allFiles($extractPath);
        $imported = [];

        foreach ($htmlFiles as $file) {
            if ($file->getExtension() !== 'html') continue;

            // Ex: "Support/HTML Help Update/file.html"
            $relativePath = ltrim(str_replace($extractPath, '', $file->getPathname()), DIRECTORY_SEPARATOR);
            $relativeDir = dirname($relativePath); // Ex: "Support/HTML Help Update"

            $originalName = pathinfo($file->getFilename(), PATHINFO_FILENAME);
            $safeName = preg_replace('/[^a-zA-Z0-9_\-]/', '_', $originalName); // Sécurité des noms
            $targetDir = $baseViewPath . '/' . $relativeDir;

            // Supprimer le fichier existant s’il existe
            $bladePath = $targetDir . '/' . $safeName . '.blade.php';
            if (File::exists($bladePath)) {
                File::delete($bladePath);
            }

            // Supprimer tout le dossier si on traite un dossier complet (optionnel)
            if (!File::exists($targetDir)) {
                File::makeDirectory($targetDir, 0755, true, true);
            }

            // Copier le nouveau fichier
            File::put($bladePath, File::get($file->getRealPath()));

            // Construction du nom de vue
            $viewName = str_replace(['/', '\\'], '.', trim($relativeDir . '/' . $safeName, '/\\'));
            $imported[] = $viewName;
        }

        // 5. Nettoyer les fichiers temporaires
        File::delete($tmpZipPath);
        File::deleteDirectory($extractPath);

        return redirect()->back()->with('success', 'Importation réussie')->with('imported', $imported);
    }
    public function importFromOwncloudProcedure(Request $request)
    {

        $url = $request->input('cloud_url');
        $nomLien = trim($request->input('nom_lien')) ?: trim($request->input('nom_lien_existant'));

        // 1. Télécharger le fichier ZIP depuis OwnCloud
        $zipContent = @file_get_contents($url . '/download');

        if ($zipContent === false) {
            return back()->withErrors(['Impossible de récupérer les fichiers depuis le lien.']);
        }

        // 2. Enregistrer le ZIP temporairement
        $tmpZipPath = storage_path('app/tmp_owncloud_import.zip');
        File::put($tmpZipPath, $zipContent);

        // 3. Extraire le contenu dans un dossier temporaire
        $extractPath = storage_path('app/tmp_owncloud_extracted');
        File::deleteDirectory($extractPath);
        File::makeDirectory($extractPath, 0755, true);

        $zip = new ZipArchive;
        if ($zip->open($tmpZipPath) === true) {
            $zip->extractTo($extractPath);
            $zip->close();
        } else {
            return back()->withErrors(['Erreur lors de l\'extraction de l\'archive.']);
        }

        // 4. Déterminer le chemin cible du dossier dynamique
        $baseViewPath = resource_path('views/components/yodirh/imported/' . $nomLien);

        // Supprimer le dossier s’il existe déjà pour écrasement propre
        if (File::exists($baseViewPath)) {
            File::deleteDirectory($baseViewPath);
        }
        File::makeDirectory($baseViewPath, 0755, true, true);

        // 5. Parcourir et enregistrer les fichiers HTML extraits
        $htmlFiles = File::allFiles($extractPath);
        $procedures = [];

        foreach ($htmlFiles as $file) {
            if ($file->getExtension() !== 'html') continue;

            $relativePath = ltrim(str_replace($extractPath, '', $file->getPathname()), DIRECTORY_SEPARATOR);
            $relativeDir = dirname($relativePath);
            $originalName = pathinfo($file->getFilename(), PATHINFO_FILENAME);

            $normalizedName = $this->removeAccents($originalName); // -> 'autreee contenu'
            $safeName = preg_replace('/[^a-zA-Z0-9_\-]/', '_', $normalizedName); // -> 'autreee_contenu'

            $targetDir = $baseViewPath . '/' . $relativeDir;

            if (!File::exists($targetDir)) {
                File::makeDirectory($targetDir, 0755, true, true);
            }

            $bladePath = $targetDir . '/' . $safeName . '.blade.php';

            File::put($bladePath, File::get($file->getRealPath()));

            $viewName = str_replace(['/', '\\'], '.', trim($relativeDir . '/' . $safeName, '/\\'));
            $procedures[] = $viewName;
        }

        // 6. Nettoyer les fichiers temporaires
        File::delete($tmpZipPath);
        File::deleteDirectory($extractPath);

        // 7. Enregistrer le lien dans la base
        LienDoc::updateOrCreate(
            [
                'nom_lien' => $nomLien,
                'user_id' => $request->input('user_id'),
                'entreprise_id' => session('entreprise_id'),
                'module_id' => $request->input('module_id'),
            ],
            [
                'lien' => $url,
            ]
        );
        // 8. Redirection
        return redirect()->route('indexprocedure', ['nom_lien' => $nomLien]);
    }
}
