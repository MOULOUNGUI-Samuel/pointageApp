<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\File;
use Illuminate\Http\Request;
use ZipArchive;

class DocumentController extends Controller
{
    //
    public function index()
    {
        $targetPath = resource_path('views/components/yodirh/imported');
        $imported = [];

        if (File::exists($targetPath)) {
            $files = File::files($targetPath);

            foreach ($files as $file) {
                $fileName = pathinfo($file->getFilename(), PATHINFO_FILENAME);
                $imported[] = $fileName;
            }
        }
        return view('components.yodirh.documents', compact('imported'));
    }

    public function import(Request $request)
    {
        $files = $request->file('html_files');
        $targetPath = resource_path('views/components/yodirh/imported');
        $imported = [];

        if (!File::exists($targetPath)) {
            File::makeDirectory($targetPath, 0755, true);
        }

        foreach ($files as $file) {
            $fileName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            $safeName = preg_replace('/[^a-zA-Z0-9_\-]/', '_', $fileName);
            $content = file_get_contents($file->getRealPath());

            File::put($targetPath . '/' . $safeName . '.blade.php', $content);
            $imported[] = $safeName;
        }

        return redirect()->route('html.import.affiche')
            ->with('success', 'Fichiers importés avec succès !');
    }

    public function importFromOwncloud(Request $request)
    {
        $url = $request->input('cloud_url');

        // Télécharger le .zip
        $zipContent = @file_get_contents($url . '/download');
        if ($zipContent === false) {
            return back()->withErrors(['Impossible de récupérer les fichiers depuis le lien.']);
        }

        // Créer un fichier temporaire zip
        $tmpZipPath = storage_path('app/tmp_owncloud_import.zip');
        File::put($tmpZipPath, $zipContent);

        // Extraire le zip
        $zip = new ZipArchive;
        if ($zip->open($tmpZipPath) === true) {
            $extractPath = storage_path('app/tmp_owncloud_extracted');
            if (!File::exists($extractPath)) {
                File::makeDirectory($extractPath, 0755, true);
            }

            $zip->extractTo($extractPath);
            $zip->close();

            // Lister les fichiers .html
            $htmlFiles = File::allFiles($extractPath);
            $targetPath = resource_path('views/components/yodirh/imported');

            if (!File::exists($targetPath)) {
                File::makeDirectory($targetPath, 0755, true);
            }

            $imported = [];

            foreach ($htmlFiles as $file) {
                if ($file->getExtension() !== 'html') continue;

                $originalName = $file->getFilenameWithoutExtension();
                $safeName = preg_replace('/[^a-zA-Z0-9_\-]/', '_', $originalName);
                $content = File::get($file->getRealPath());

                $bladePath = $targetPath . '/' . $safeName . '.blade.php';

                // Supprime si déjà présent
                if (File::exists($bladePath)) {
                    File::delete($bladePath);
                }

                // Écrit le nouveau contenu
                File::put($bladePath, $content);
                $imported[] = $safeName;
            }

            // Nettoyer les fichiers temporaires
            File::delete($tmpZipPath);
            File::deleteDirectory($extractPath);
            return redirect()->back()->with('success', 'Entreprise ajoutée avec succès','Fichiers HTML importés : ' . implode(', ', $imported));

        }

        return back()->withErrors(['Erreur lors de l\'extraction du fichier ZIP.']);
    }


}
