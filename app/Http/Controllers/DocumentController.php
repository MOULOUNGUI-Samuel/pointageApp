<?php

namespace App\Http\Controllers;

use App\Models\LienDoc;
use App\Models\User;
use Illuminate\Support\Facades\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use ZipArchive;
use Normalizer;
use DateTime;
use Illuminate\Support\Facades\Auth;

class DocumentController extends Controller
{
    public function dashboard($nom_lien)
    {
        $datetime = Auth::user()->created_at;
        if (!$datetime) {
            return redirect()->back()->withErrors(['Impossible de récupérer la date de création de l\'utilisateur.']);
        }

        // 🔹 Fonction pour générer le code à partir de la date
        function extraireCodeTemps($datetime)
        {
            $dt = new DateTime($datetime);
            return $dt->format('dHis'); // Exemple : 02172016
        }

        // 🔹 Fonction pour vérifier si le nom du dossier contient un nombre
        function contientNombreDansDossier($nom_lien, $nombre)
        {
            preg_match_all('/\\d+/', $nom_lien, $matches);
            return in_array((string)$nombre, $matches[0]);
        }

        // ✅ Code utilisateur
        $code = extraireCodeTemps($datetime);
        $baseImportedPath = resource_path('views/components/yodirh/imported');

        $lienDocuments = LienDoc::where('entreprise_id', session('entreprise_id'))->get();
        $utilisateurs = User::with(['entreprise', 'service', 'role', 'pays', 'ville'])
            ->orderBy('id', 'desc')
            ->get();
        // 🔍 Filtrer les dossiers de l’entreprise qui contiennent le code
        $Dossiers = LienDoc::All()->filter(function ($doc) use ($baseImportedPath, $code) {
            $nom_lien = $doc->nom_lien;
            $path = $baseImportedPath . '/' . $nom_lien;

            return contientNombreDansDossier($nom_lien, $code) &&
                File::isDirectory($path) &&
                collect(File::allFiles($path))->contains(function ($file) {
                    return $file->getExtension() === 'php';
                });
        })->values(); // Réindexe proprement

        // 📁 Charger les fichiers PHP pour chaque dossier filtré
        $procedures = [];

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
        // }


        return view('components.cloudDoc.dashboard', compact('procedures', 'lienDocuments', 'utilisateurs'))
            ->with('procedures', $procedures)
            ->with('success', 'Les données ont été chargées avec succès.');
    }


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
    /**
     * Importer des fichiers HTML depuis OwnCloud.
     */
    public function importFromOwncloudDoument(Request $request)
    {

        $url = $request->input('cloud_url');
        $nomLien = trim($request->input('nom_lien')) ?: trim($request->input('nom_lien_existant'));

        // 1. Télécharger le fichier ZIP depuis OwnCloud
        $zipContent = @file_get_contents($url . '/download');

        if ($zipContent === false) {
            return back()->withErrors(['Échec de la récupération des fichiers. Veuillez vérifier que le lien suivant contient bien des données accessibles : <a href="' . e($url) . '" target="_blank" style="color:#0d6efd;">' . e($url) . '</a>']);
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

        if ($request->filled('nom_lien')) {
            $nomLien = trim($request->input('nom_lien'));

            // Vérification si le nom_lien existe déjà pour cette entreprise
            $existe = LienDoc::where('nom_lien', $nomLien)
                ->where('entreprise_id', session('entreprise_id'))
                ->exists();

            if ($existe) {
                return back()->withErrors([
                    'Un dossier portant ce nom existe déjà. Veuillez choisir un nom différent.'
                ])->withInput();
            }
            $datetime = Auth::user()->created_at;
            if (!$datetime) {
                return redirect()->back()->withErrors(['Impossible de récupérer la date de création de l\'utilisateur.']);
            }
            function extraireCodeTemps($datetime)
            {
                $dt = new DateTime($datetime);
                return $dt->format('dHis'); // d = jour, H = heure, i = minute, s = seconde
            }
            // Extraire le code temps à partir de la date
            $code = extraireCodeTemps($datetime);
            // Enregistrement si aucun doublon
            $Doc = new LienDoc();
            $Doc->nom_lien = $nomLien . '-' . $code; // Ajout du code temps pour éviter les doublons
            $Doc->user_id = $request->input('user_id');
            $Doc->entreprise_id = session('entreprise_id');
            $Doc->module_id = $request->input('module_id');
            $Doc->lien = $url;
            $Doc->save();
        }


        // 8. Redirection
        return redirect()->route('dashboard_doc', ['nom_lien' => $nomLien]);
    }

    public function partageFichier(Request $request)
    {
        $nomDossier = $request->input('nom_lien');
        $created_at = $request->input('created_at');

        // Vérification de l'existence du dossier
        $lienDoc = LienDoc::where('nom_lien', $nomDossier)
            ->where('entreprise_id', session('entreprise_id'))
            ->first();

        if (!$lienDoc) {
            return back()->withErrors(['Le dossier spécifié n\'existe pas.']);
        }

        // Envoi de l'email
        try {
            \Mail::to($email)->send(new \App\Mail\PartageFichierMail($lienDoc));
            return back()->with('success', 'Le fichier a été partagé avec succès.');
        } catch (\Exception $e) {
            return back()->withErrors(['Erreur lors de l\'envoi de l\'email : ' . $e->getMessage()]);
        }
    }
   
    public function lienDoc_destroy($nom_dossier)
    {
        $entrepriseId = session('entreprise_id');

        // Recherche du lien à supprimer
        $lienDoc = LienDoc::where('nom_lien', $nom_dossier)
            ->where('entreprise_id', $entrepriseId)
            ->first();

        if ($lienDoc) {
            // 1. Supprimer le dossier physique (ex: views/components/yodirh/imported/NOM_DOSSIER)
            $cheminDossier = resource_path('views/components/yodirh/imported/' . $nom_dossier);

            if (File::exists($cheminDossier)) {
                File::deleteDirectory($cheminDossier);
            }

            // 2. Supprimer de la base de données
            $lienDoc->delete();

            return redirect()->route('dashboard', ['id' => session('module_id')]);
        }

        return back()->withErrors(['Ce dossier n\'existe pas ou a déjà été supprimé.']);
    }
}
