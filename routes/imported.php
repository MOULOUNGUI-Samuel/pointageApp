<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\File;

// $files = File::files(resource_path('views/components/yodirh/imported'));

// foreach ($files as $file) {
//     $viewName = pathinfo($file->getFilename(), PATHINFO_FILENAME);
//     $cleanName = str_replace(['.blade.php', '.blade'], '', $viewName);
    
//     Route::get('/imported/' . $viewName, function () use ($cleanName) {
//         return view('components.yodirh.imported.' . $cleanName);
//     })->name('imported.' . $viewName);
// }



$importBase = resource_path('views/components/yodirh/imported');

if (File::exists($importBase)) {
    $files = File::allFiles($importBase);

    foreach ($files as $file) {
        if ($file->getExtension() !== 'php') continue;

        // Obtenir le chemin relatif depuis le dossier imported
        $relativePath = str_replace($importBase . DIRECTORY_SEPARATOR, '', $file->getPathname());

        // Nettoyer et transformer en nom de vue
        $viewName = str_replace(['/', '\\'], '.', str_replace('.blade.php', '', $relativePath));

        // Générer la route avec URL utilisant des slashes
        Route::get('/imported/' . str_replace('.', '/', $viewName), function () use ($viewName) {
            return view('components.yodirh.imported.' . $viewName);
        })->name('imported.' . $viewName);
    }
}

$nom_lien = session('nom_lien', 'procedures'); // valeur par défaut

if($nom_lien=='procedures'){
    $importBaseProcedure = resource_path('views/components/smi/procedures');
}else{
    $importBaseProcedure = resource_path('views/components/smi/procedures/'.$nom_lien);
}
// $importBaseProcedure = resource_path('views/components/smi/procedures');

if (File::exists($importBaseProcedure)) {
    $files = File::allFiles($importBaseProcedure);

    foreach ($files as $file) {
        if ($file->getExtension() !== 'php') continue;

        // Obtenir le chemin relatif depuis le dossier imported
        $relativePath = str_replace($importBaseProcedure . DIRECTORY_SEPARATOR, '', $file->getPathname());

        // Nettoyer et transformer en nom de vue
        $viewNameProcedure = str_replace(['/', '\\'], '.', str_replace('.blade.php', '', $relativePath));

        // Générer la route avec URL utilisant des slashes
        Route::get('/importedProcedure/' . str_replace('.', '/', $viewNameProcedure), function () use ($viewNameProcedure) {
            return view('components.smi.procedures.' . $viewNameProcedure);
        })->name('procedures.' . $viewNameProcedure);
    }
}

