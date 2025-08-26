<?php

namespace App\Http\Controllers;

use App\Models\Demande_intervention;
use App\Models\Entreprise;
use App\Models\LienDoc;
use App\Models\Service;
use App\Models\User;
use Illuminate\Support\Facades\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use ZipArchive;
use Normalizer;
use DateTime;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class DocumentController extends Controller
{
    public function dashboard($nom_lien)
    {

        $baseImportedPath = resource_path('views/components/yodirh/imported');

        $lienDocuments = LienDoc::where('entreprise_id', session('entreprise_id'))->first();

        $utilisateurs = User::with(['entreprise', 'service', 'role', 'pays', 'ville'])
            ->orderBy('id', 'desc')
            ->get();

        // Filtrer ceux dont le code dHis est dans le nom du document
        $utilisateursAssocies = $utilisateurs->filter(function ($user) use ($nom_lien) {
            $code = (new DateTime($user->created_at))->format('dHis');
            return strpos($nom_lien, $code) !== false;
        })->values(); // Réindexe le tableau
        $utilisateursNonAssocies = $utilisateurs->filter(function ($user) use ($nom_lien) {
            $code = (new DateTime($user->created_at))->format('dHis');
            return strpos($nom_lien, $code) === false;
        })->values(); // Réindexe le tableau



        $procedures = [];
        $Dossiers = LienDoc::All()->filter(function ($nom_lien) use ($baseImportedPath) {
            $path = $baseImportedPath . '/' . $nom_lien;
            return File::isDirectory($path) && collect(File::allFiles($path))->contains(function ($file) {
                return $file->getExtension() === 'php';
            });
        })->values(); // Réindexer proprement

        // foreach ($Dossiers as $doc) {
        //     $nom_lien = $doc->nom_lien;
        $basePath = $baseImportedPath . '/' . $nom_lien;

        $files = File::allFiles($basePath);

        foreach ($files as $file) {
            if ($file->getExtension() !== 'php') continue;

            $relativePath = str_replace($basePath . DIRECTORY_SEPARATOR, '', $file->getPathname());
            $viewName = $nom_lien . '.' . str_replace(['/', '\\'], '.', str_replace('.blade.php', '', $relativePath));

            $procedures[] = $viewName;
        }
        // }


        return view('components.cloudDoc.dashboard', compact('procedures', 'lienDocuments', 'utilisateursAssocies', 'utilisateursNonAssocies'))
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
        if ($request->input('nom_lien')) {
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
            $nouveauNomLien = $nomLien . '-' . $code;
        } else {
            $nouveauNomLien = $nomLien;
        }


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
        $baseViewPath = resource_path('views/components/yodirh/imported/' . $nouveauNomLien);

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
            // Enregistrement si aucun doublon
            // Vérification si le nom_lien existe déjà pour cette entreprise
            $existe = LienDoc::where('nom_lien', 'like', $nouveauNomLien . '-%')
                ->where('entreprise_id', session('entreprise_id'))
                ->where('user_id', Auth::user()->id)
                ->exists();

            if ($existe) {
                return back()->withErrors([
                    'Un dossier portant ce nom existe déjà. Veuillez choisir un nom différent.'
                ])->withInput();
            }
            $Doc = new LienDoc();
            $Doc->nom_lien = $nouveauNomLien; // Ajout du code temps pour éviter les doublons
            $Doc->user_id = $request->input('user_id');
            $Doc->entreprise_id = session('entreprise_id');
            $Doc->module_id = $request->input('module_id');
            $Doc->lien = $url;
            $Doc->save();
        }


        // 8. Redirection
        return redirect()->route('dashboard_doc', ['nom_lien' => $nouveauNomLien]);
    }

    public function partageFichier(Request $request)
    {
        $nomDossier = $request->input('nom_lien');
        $created_at_list = $request->input('created_at'); // tableau de dates
        $email = $request->input('email'); // Assure-toi qu'un champ 'email' est présent

        if (!is_array($created_at_list) || empty($created_at_list)) {
            return back()->withErrors(['Aucune date fournie pour générer le code.']);
        }

        // if (empty($email)) {
        //     return back()->withErrors(['Adresse email non spécifiée.']);
        // }

        // Fonction pour générer le code à partir de la date
        function extraireCodeTemps2($date)
        {
            $dt = new DateTime($date);
            return $dt->format('dHis'); // Exemple : 02172016
        }

        // Génération du tableau des codes
        $codeTab = array_map(fn($date) => extraireCodeTemps2($date), $created_at_list);
        $codeConcat = implode('-', $codeTab);

        // dd($codeConcat);

        // Vérification de l'existence du dossier
        $lienDoc = LienDoc::where('nom_lien', $nomDossier)
            ->first();
        // ->where('entreprise_id', session('entreprise_id'))
        // ->where('module_id', session('module_id'))
        if (!$lienDoc) {
            return back()->withErrors(['Le dossier spécifié n\'existe pas.']);
        }
        $lienDoc->nom_lien = $nomDossier . '-' . $codeConcat; // Ajout du code concaténé
        $lienDoc->save();

        $nouveauNomLien = $nomDossier . '-' . $codeConcat;
        $ancienPath = resource_path('views/components/yodirh/imported/' . $nomDossier);
        $nouveauPath = resource_path('views/components/yodirh/imported/' . $nouveauNomLien);
        // Vérifie si le dossier original existe et effectue le renommage
        if (File::exists($ancienPath)) {
            File::move($ancienPath, $nouveauPath);
        } else {
            return back()->withErrors(['Le dossier d\'origine est introuvable.']);
        }

        $baseImportedPath = resource_path('views/components/yodirh/imported');

        $lienDocuments = LienDoc::where('entreprise_id', session('entreprise_id'))->get();
        $utilisateurs = User::with(['entreprise', 'service', 'role', 'pays', 'ville'])
            ->orderBy('id', 'desc')
            ->get();
        // Filtrer ceux dont le code dHis est dans le nom du document
        $utilisateursAssocies = $utilisateurs->filter(function ($user) use ($nouveauNomLien) {
            $code = (new DateTime($user->created_at))->format('dHis');
            return strpos($nouveauNomLien, $code) !== false;
        })->values(); // Réindexe le tableau
        $utilisateursNonAssocies = $utilisateurs->filter(function ($user) use ($nouveauNomLien) {
            $code = (new DateTime($user->created_at))->format('dHis');
            return strpos($nouveauNomLien, $code) === false;
        })->values(); // Réindexe le tableau

        $procedures = [];
        $Dossiers = LienDoc::All()->filter(function ($nouveauNomLien) use ($baseImportedPath) {
            $path = $baseImportedPath . '/' . $nouveauNomLien;
            return File::isDirectory($path) && collect(File::allFiles($path))->contains(function ($file) {
                return $file->getExtension() === 'php';
            });
        })->values(); // Réindexer proprement

        // foreach ($Dossiers as $doc) {
        //     $nom_lien = $doc->nom_lien;
        $basePath = $baseImportedPath . '/' . $nouveauNomLien;

        $files = File::allFiles($basePath);

        foreach ($files as $file) {
            if ($file->getExtension() !== 'php') continue;

            $relativePath = str_replace($basePath . DIRECTORY_SEPARATOR, '', $file->getPathname());
            $viewName = $nouveauNomLien . '.' . str_replace(['/', '\\'], '.', str_replace('.blade.php', '', $relativePath));

            $procedures[] = $viewName;
        }
        // }


        return view('components.cloudDoc.dashboard', compact('procedures', 'lienDocuments', 'utilisateursAssocies', 'utilisateursNonAssocies'))
            ->with('procedures', $procedures)
            ->with('success', 'Dossier partagé avec succès.');
    }


    public function lienDoc_destroy($nom_dossier)
    {
        $entrepriseId = session('entreprise_id');

        // Recherche du lien à supprimer
        $lienDoc = LienDoc::where('nom_lien', $nom_dossier)
            ->where('user_id', Auth::user()->id)
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
        } else {
            // Si le lien n'existe pas, rediriger avec un message d'erreur
            return back()->withErrors(['Vous n\'avez pas le droit de supprimer ce dossier,car vous avez été invité .']);
        }

        return back()->withErrors(['Ce dossier n\'existe pas ou a déjà été supprimé.']);
    }

    public function annuaire()
    {
        $utilisateurs = \App\Models\User::orderBy('created_at', 'asc')
            ->with('entreprise')
            ->with('service')
            ->get();

        $entreprises = Entreprise::all();
        $services = Service::all();
        return view('components.annuaire', compact('utilisateurs', 'entreprises', 'services'));
    }
    public function storeDemandeIntervention(Request $request)
    {
        // Validation
        $validated = $request->validate([
            'titre'          => ['required', 'string', 'max:255'],
            'entreprise_id'  => ['required', 'uuid', Rule::exists('entreprises', 'id')],
            'description'    => ['nullable', 'string'],
            'date_souhaite'  => ['required', 'date'],
            'piece_jointe' => [
        'nullable',
        'file',
        'max:10240', // 10 Mo
        // Autorisés: PDF + Office + OpenDocument + RTF + images
        'mimes:pdf,doc,docx,xls,xlsx,ppt,pptx,odt,ods,odp,rtf,jpg,jpeg,png,webp,gif'
    ],
        ]
    );

        // (Optionnel) Sécurité multi-structure : s'assurer que l'entreprise appartient
        // bien à la structure en session (si ton schéma a bien `entreprises.structure_id`)
        // $structureId = session('structure_id');
        // abort_unless(
        //     Entreprise::where('id', $validated['entreprise_id'])
        //               ->where('structure_id', $structureId)
        //               ->exists(),
        //     403, 'Entreprise invalide pour cette structure.'
        // );

        // Upload fichier si fourni
        $pieceJointePath = null;
        if ($request->hasFile('piece_jointe')) {
            // nécessite: php artisan storage:link
            $pieceJointePath = $request->file('piece_jointe')->store('demande_interventions', 'public');
        }

        // Création
        $demande = Demande_intervention::create([
            'titre'             => $validated['titre'],
            'entreprise_id'     => $validated['entreprise_id'],
            'user_id'           => Auth::id(),
            'description'       => $validated['description'] ?? null,
            'date_souhaite'     => $validated['date_souhaite'],
            'piece_jointe_path' => $pieceJointePath,
            'statut'            => 'en_attente', // par défaut
        ]);

        return back()->with('success', 'Votre demande a été enregistrée avec succès.');
    }
}
