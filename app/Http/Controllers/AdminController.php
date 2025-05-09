<?php

namespace App\Http\Controllers;

use App\Models\CategorieProfessionnelle;
use App\Models\DescriptionPointage;
use App\Models\Entreprise;
use App\Models\Module;
use App\Models\Pays;
use App\Models\Pointage;
use App\Models\PointagesIntermediaire;
use App\Models\Role;
use App\Models\Service;
use App\Models\User;
use App\Models\Ville;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class AdminController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function loginGroupe($id)
    {
        //
        $module = Module::find($id);

        if (!$module) {
            return redirect()->back()->with('error', 'Module non trouvé.');
        }

        return view('auth.loginGroupe', compact('module'));
    }
    public function modules()
    {
        //
        $modules = Module::All();
        return view("components.modul_admin", compact('modules'));
    }
    public function index()
    {
        //
        return view("auth.loginAdmin");
    }
    public function index_dashboard()
    {
        //
        $entreprise_id = auth()->user()->entreprise_id;
        $employes = User::where('entreprise_id', $entreprise_id)->get();
        $pointages_oui = Pointage::whereHas('user', fn($query) => $query->where('entreprise_id', $entreprise_id))
            ->where('date_arriver', now()->format('Y-m-d'))
            ->get();

        $users_non_existants = User::where('entreprise_id', $entreprise_id)
            ->whereDoesntHave('pointage', function ($query) {
                $query->whereDate('date_arriver', now()->format('Y-m-d'));
            })
            ->get();

        $pointage_intermediaires = PointagesIntermediaire::whereHas('pointage', fn($query) => $query->whereHas('user', fn($subQuery) => $subQuery->where('entreprise_id', auth()->user()->entreprise_id)))
            ->whereHas('pointage', fn($query) => $query->where('date_arriver', now()->format('Y-m-d')))
            ->get();

        return view("dashboard", compact('employes', 'pointages_oui', 'users_non_existants', 'pointage_intermediaires'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function affiche_utilisateur()
    {
        //
        $utilisateurs = User::with(['entreprise', 'service', 'role', 'pays', 'ville'])
            ->orderBy('id', 'desc')
            ->get();

        return view('components.yodirh.utilisateurs.utilisateurs', compact('utilisateurs'));
    }
    public function formulaire()
    {
        //
        $entreprises = Entreprise::all();
        $services = Service::all();
        $roles = Role::all();
        $users = User::all();
        $pays = Pays::all();
        $villes = Ville::all();
        $categorie_professionelles = CategorieProfessionnelle::all();
        $utilisateurs = User::with(['entreprise', 'service', 'role', 'pays', 'ville'])
            ->orderBy('id', 'desc')
            ->get();

        return view('components.yodirh.utilisateurs.ajouter_utilisateur', compact('entreprises', 'services', 'roles', 'users', 'pays', 'villes', 'categorie_professionelles', 'utilisateurs'));
    }

    public function edit(Request $request, string $id)
    {
        $entreprises = Entreprise::all();
        $services = Service::all();
        $roles = Role::all();
        $users = User::all();
        $pays = Pays::all();
        $villes = Ville::all();
        $categorie_professionelles = CategorieProfessionnelle::all();
        $utilisateurs = User::with(['entreprise', 'service', 'role', 'pays', 'ville'])
            ->orderBy('id', 'desc')
            ->get();
        $utilisateur = User::findOrFail($id);

        return view('components.yodirh.utilisateurs.modif_utilisateur', compact('entreprises', 'services', 'roles', 'users', 'pays', 'villes', 'categorie_professionelles', 'utilisateurs', 'utilisateur'));
    }

    public function create(Request $request)
    {
        try {
            // Validation
            $validator = Validator::make([
                'nom' => 'required|string|max:100',
                'prenom' => 'required|string|max:100',
                'date_naissance' => 'required|date_format:d/m/Y',
                'adresse' => 'required|string|max:255',
                'telephone' => 'required|string|max:20',
                'matricule' => 'required|string|max:50|unique:users,matricule',
                'email' => 'nullable|email|unique:users,email',
                'email_professionnel' => 'nullable|email|unique:users,email_professionnel',
                'password' => 'nullable|string|min:6',
                'salaire' => 'nullable|numeric|min:0',
                'photo' => 'nullable|file|mimes:jpg,jpeg,png|max:2048',
                'cv' => 'nullable|file|mimes:pdf,doc,docx|max:4096',
                'permis_conduire' => 'nullable',
                'piece_identite' => 'nullable',
                'diplome' => 'nullable',
                'pays_id' => 'required',
                'ville_id' => 'required',
                'type_contrat' => 'nullable',
                'certificat_travail' => 'nullable',
            ], [
                'nom.required' => 'Le nom est obligatoire.',
                'prenom.required' => 'Le prénom est obligatoire.',
                'date_naissance.required' => 'La date de naissance est obligatoire.',
                'matricule.required' => 'Le matricule est obligatoire.',
                'matricule.unique' => 'Ce matricule est déjà utilisé.',
                'pays_id.required' => 'Le pays est obligatoire.',
                'ville_id.required' => 'La ville est obligatoire.',
                'email.unique' => 'Cet email est déjà utilisé.',
                'email_professionnel.unique' => 'Cet email professionnel est déjà utilisé.',
                'password.min' => 'Le mot de passe doit contenir au moins 6 caractères.',
            ]);
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }
            // Création de l'utilisateur
            $user = new User();
            $user->entreprise_id = $request->input('entreprise_id') ?? auth()->user()->entreprise_id;
            $user->service_id = $request->input('service_id');
            $user->role_id = $request->input('role_id');
            $user->pays_id = $request->input('pays_id');
            $user->ville_id = $request->input('ville_id');
            $user->nom = $request->input('nom');
            $user->prenom = $request->input('prenom');
            $user->date_naissance = Carbon::createFromFormat('d/m/Y', $request->input('date_naissance'))->format('Y-m-d');
            $user->lieu_naissance = $request->input('lieu_naissance');
            $user->nationalite = $request->input('nationalite');
            $user->numero_securite_sociale = $request->input('numero_securite_sociale');
            $user->etat_civil = $request->input('etat_civil');
            $user->nombre_enfant = $request->input('nombre_enfant');
            $user->adresse = $request->input('adresse');
            $user->adresse_complementaire = $request->input('adresse_complementaire');
            $user->code_postal = $request->input('code_postal');
            $user->telephone = $request->input('telephone');
            $user->email = $request->input('email');
            $user->email_professionnel = $request->input('email_professionnel');
            $user->telephone_professionnel = $request->input('telephone_professionnel');
            $user->date_embauche = $request->input('date_embauche') ? Carbon::createFromFormat('d/m/Y', $request->input('date_embauche'))->format('Y-m-d') : null;
            $user->fonction = $request->input('fonction');
            $user->matricule = $request->input('matricule');
            $user->superieur_hierarchique = $request->input('superieur_hierarchique');
            $user->niveau_etude = $request->input('niveau_etude');
            $user->competence = $request->input('competence');
            $user->salaire = $request->input('salaire');
            $user->type_contrat = $request->input('type_contrat');
            $user->mode_paiement = $request->input('mode_paiement');
            $user->iban = $request->input('iban');
            $user->bic = $request->input('bic');
            $user->titulaire_compte = $request->input('titulaire_compte');
            $user->nom_banque = $request->input('nom_banque');
            $user->nom_agence = $request->input('nom_agence');
            $user->nom_completaire = $request->input('nom_completaire');
            $user->lien_completaire = $request->input('lien_completaire');
            $user->contact_completaire = $request->input('contact_completaire');
            $user->formation_completaire = $request->input('formation_completaire');
            $user->commmentaire_completaire = $request->input('commmentaire_completaire');

            // Hash mot de passe s’il est fourni
            if ($request->filled('password')) {
                $user->password = Hash::make($request->input('password'));
            }

            // Gestion des fichiers
            foreach (['photo', 'cv', 'permis_conduire', 'piece_identite', 'diplome', 'certificat_travail'] as $fileField) {
                if ($request->hasFile($fileField)) {
                    $path = $request->file($fileField)->store('documents_utilisateur', 'public');
                    $user->$fileField = $path;
                }
            }

            $user->save();

            return redirect()->route('yodirh.utilisateurs')->with('success', 'Utilisateur enregistré avec succès.');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => 'Une erreur inattendue s\'est produite. Veuillez réessayer plus tard.'])->withInput();
        }
    }

    public function update(Request $request,$id)
    {
        try {
            // Validation
            $validator = Validator::make($request->all(), [
                'nom' => 'required|string|max:100',
                'prenom' => 'required|string|max:100',
                'date_naissance' => 'required|date_format:d/m/Y',
                'adresse' => 'required|string|max:255',
                'telephone' => 'required|string|max:20',
                'matricule' => 'required|string|max:50|unique:users,matricule,' . $id,
                'email' => 'nullable|email|unique:users,email,' . $id,
                'email_professionnel' => 'nullable|email|unique:users,email_professionnel,' . $id,
                'password' => 'nullable|string|min:6',
                'salaire' => 'nullable|numeric|min:0',
                'photo' => 'nullable|file|mimes:jpg,jpeg,png|max:2048',
                'cv' => 'nullable|file|mimes:pdf,doc,docx|max:4096',
                'permis_conduire' => 'nullable',
                'piece_identite' => 'nullable',
                'diplome' => 'nullable',
                'pays_id' => 'required',
                'ville_id' => 'required',
                'type_contrat' => 'nullable',
                'certificat_travail' => 'nullable',
            ], [
                'nom.required' => 'Le nom est obligatoire.',
                'prenom.required' => 'Le prénom est obligatoire.',
                'date_naissance.required' => 'La date de naissance est obligatoire.',
                'matricule.required' => 'Le matricule est obligatoire.',
                'matricule.unique' => 'Ce matricule est déjà utilisé.',
                'pays_id.required' => 'Le pays est obligatoire.',
                'ville_id.required' => 'La ville est obligatoire.',
                'email.unique' => 'Cet email est déjà utilisé.',
                'email_professionnel.unique' => 'Cet email professionnel est déjà utilisé.',
                'password.min' => 'Le mot de passe doit contenir au moins 6 caractères.',
            ]);

            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }

            // Récupération de l'utilisateur
            $user = User::findOrFail($id);

            $user->entreprise_id = $request->input('entreprise_id') ?? $user->entreprise_id;
            $user->service_id = $request->input('service_id');
            $user->role_id = $request->input('role_id');
            $user->pays_id = $request->input('pays_id');
            $user->ville_id = $request->input('ville_id');
            $user->nom = $request->input('nom');
            $user->prenom = $request->input('prenom');
            $user->date_naissance = Carbon::createFromFormat('d/m/Y', $request->input('date_naissance'))->format('Y-m-d');
            $user->lieu_naissance = $request->input('lieu_naissance');
            $user->nationalite = $request->input('nationalite');
            $user->numero_securite_sociale = $request->input('numero_securite_sociale');
            $user->etat_civil = $request->input('etat_civil');
            $user->nombre_enfant = $request->input('nombre_enfant');
            $user->adresse = $request->input('adresse');
            $user->adresse_complementaire = $request->input('adresse_complementaire');
            $user->code_postal = $request->input('code_postal');
            $user->telephone = $request->input('telephone');
            $user->email = $request->input('email');
            $user->email_professionnel = $request->input('email_professionnel');
            $user->telephone_professionnel = $request->input('telephone_professionnel');
            $user->date_embauche = $request->input('date_embauche') ? Carbon::createFromFormat('d/m/Y', $request->input('date_embauche'))->format('Y-m-d') : $user->date_embauche;
            $user->fonction = $request->input('fonction');
            $user->matricule = $request->input('matricule');
            $user->superieur_hierarchique = $request->input('superieur_hierarchique');
            $user->niveau_etude = $request->input('niveau_etude');
            $user->competence = $request->input('competence');
            $user->salaire = $request->input('salaire');
            $user->type_contrat = $request->input('type_contrat');
            $user->mode_paiement = $request->input('mode_paiement');
            $user->iban = $request->input('iban');
            $user->bic = $request->input('bic');
            $user->titulaire_compte = $request->input('titulaire_compte');
            $user->nom_banque = $request->input('nom_banque');
            $user->nom_agence = $request->input('nom_agence');
            $user->nom_completaire = $request->input('nom_completaire');
            $user->lien_completaire = $request->input('lien_completaire');
            $user->contact_completaire = $request->input('contact_completaire');
            $user->formation_completaire = $request->input('formation_completaire');
            $user->commmentaire_completaire = $request->input('commmentaire_completaire');

            // Gestion des fichiers
            foreach (['photo', 'cv', 'permis_conduire', 'piece_identite', 'diplome', 'certificat_travail'] as $fileField) {
                if ($request->hasFile($fileField)) {
                    $path = $request->file($fileField)->store('documents_utilisateur', 'public');
                    $user->$fileField = $path;
                }
            }

            $user->save();

            return redirect()->route('yodirh.utilisateurs')->with('success', 'Utilisateur modifié avec succès.');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => 'Une erreur inattendue s\'est produite. Veuillez réessayer plus tard.'])->withInput();
        }
    }

    public function liste_employer()
    {
        $entreprise_id = auth()->user()->entreprise_id;
        if ($entreprise_id == null) {
            $employes = User::all();
        } else {
            $employes = User::where('entreprise_id', $entreprise_id)->get();
        }
        $entreprises = Entreprise::All();
        return view('components.pointages.liste_employer', compact('employes', 'entreprises'));
    }
    /**
     * Store a newly created resource in storage.
     */
    // public function update(Request $request, $id)
    // {
    //     // try {

    //     $validator = Validator::make(
    //         $request->all(),
    //         [
    //             'nom' => 'required|string',
    //             'prenom' => 'required|string',
    //             'date_naissance' => 'required',
    //             'fonction' => 'required|string',
    //             'email' => 'required|email|unique:users,email,' . $id,
    //             'matricule' => 'required|string|unique:users,matricule,' . $id,
    //             'password' => 'nullable|string|min:6',
    //         ],
    //         [
    //             'nom.required' => 'Le nom est obligatoire.',
    //             'prenom.required' => 'Le prénom est obligatoire.',
    //             'date_naissance.required' => 'La date de naissance est obligatoire.',
    //             'date_naissance.date_format' => 'La date de naissance doit être au format JJ/MM/AAAA.',
    //             'fonction.required' => 'La fonction est obligatoire.',
    //             'email.required' => 'L\'adresse email est obligatoire.',
    //             'email.email' => 'L\'adresse email doit être valide.',
    //             'email.unique' => 'Cet email est déjà utilisé.',
    //             'matricule.required' => 'L\'identifiant (matricule) est obligatoire.',
    //             'matricule.unique' => 'Ce matricule est déjà utilisé.',
    //             'password.min' => 'Le mot de passe doit contenir au moins 6 caractères.',
    //         ]
    //     );
    //     if ($validator->fails()) {
    //         return redirect()->back()->withErrors($validator)->withInput();
    //     }

    //     $user = User::findOrFail($id);
    //     if ($request->input('password')) {
    //         // Vérification du mot de passe
    //         if (!Hash::check($request->input('password1'), $user->password)) {
    //             return back()->withErrors(['password' => 'Ancien mot de passe est incorrect.'])->withInput();
    //         }
    //     }
    //     if (Carbon::hasFormat($request->input('date_naissance'), 'Y-m-d')) {
    //         $date = $request->input('date_naissance');
    //     } else {
    //         // Mauvais format
    //         $date = \Carbon\Carbon::createFromFormat('d/m/Y', $request->input('date_naissance'))->format('Y-m-d');
    //     }

    //     $user->nom = $request->input('nom');
    //     $user->prenom = $request->input('prenom');
    //     $user->matricule = $request->input('matricule');
    //     $user->email = $request->input('email');
    //     if ($request->input('password1') && $request->input('password')) {
    //         $user->password = Hash::make($request->input('password'));
    //     }
    //     if ($request->input('role_user')) {
    //         $user->role_user = $request->input('role_user');
    //     }

    //     $user->date_naissance = $date;
    //     $user->fonction = $request->input('fonction');
    //     $user->save();

    //     return redirect()->back()->with('success', 'Informations modifiées avec succès.');
    //     // } catch (\Exception $e) {
    //     //     return redirect()->back()->withErrors(['error' => 'Une erreur est survenue : ' . $e->getMessage()])->withInput();
    //     // }
    // }

    private function calculateDistance($lat1, $lon1, $lat2, $lon2)
    {
        $earthRadius = 90371000; // en mètres

        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);

        $a = sin($dLat / 2) ** 2 +
            cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
            sin($dLon / 2) ** 2;

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
        // Calculer la distance
        return $earthRadius * $c;
    }

    public function pointage_connecter(Request $request)
    {
        $user = Auth::user();
        $heure_actuelle = Carbon::now('Africa/Libreville')->format('H:i:s');
        if ($request->pointage_entrer) {
            $entreprise = Entreprise::find($user->entreprise_id);


            if (!$entreprise) {
                return redirect()->back()->with('error', 'Entreprise non trouvée.');
            }

            // dd($request->latitude."/". $request->longitude);
            // Vérification de la géolocalisation
            // $distance = $this->calculateDistance(
            //     $entreprise->latitude,
            //     $entreprise->longitude,
            //     $request->latitude,
            //     $request->longitude
            // );
            // // dd($distance);
            // // Vérifier si la distance est supérieure à 200 mètres
            // if ($distance > 700) {
            //     return redirect()->back()->with('error', 'Oups, quelque chose n’a pas fonctionné. Essayez à nouveau !');
            // }

            // Vérifier si le compte est actif
            if ($user->statut == 0) {
                return redirect()->back()->with('error', 'Votre compte est désactivé.');
            }

            // Vérifier s’il a déjà pointé aujourd’hui
            $dejaPointage = Pointage::where('user_id', $user->id)
                ->whereDate('date_arriver', now()->toDateString())
                ->first();

            if ($dejaPointage && $dejaPointage->statut == 1) {
                return redirect()->back()->with('error', 'Vous avez déjà pointé l\'entrée.');
            }

            // Vérifier s’il a déjà pointé sa sortie aujourd’hui
            if ($dejaPointage && $dejaPointage->statut == 0) {
                $le_pointage = Pointage::find($dejaPointage->id);
                $le_pointage->statut = 1;
                $le_pointage->save();

                $le_pointageIntermediaire = PointagesIntermediaire::where('pointage_id', $dejaPointage->id)
                    ->orderBy('heure_sortie', 'desc')
                    ->first();


                if ($le_pointageIntermediaire) {
                    $le_pointageIntermediaire->statut = 1;
                    $le_pointageIntermediaire->heure_entrer = $heure_actuelle;
                    $le_pointageIntermediaire->save();
                }

                // Vérifier s’il a déjà pointé sa sortie aujourd’hui
                if ($le_pointageIntermediaire) {
                    $ladescriptionPointages = DescriptionPointage::where('pointage_intermediaire_id', $le_pointageIntermediaire->id)
                        ->get();

                    foreach ($ladescriptionPointages as $ladescriptionPointage) {
                        $le_pointageIntermediaire_ = DescriptionPointage::find($ladescriptionPointage->id);
                        if ($le_pointageIntermediaire_) {
                            $le_pointageIntermediaire_->statut = 1;
                            $le_pointageIntermediaire_->save();
                        }
                    }
                }

                return redirect()->back()->with('success', 'Vous avez pointé votre entrée avec succès.');
            }
            // Enregistrer le pointage
            Pointage::create([
                'user_id' => $user->id,
                'date_arriver' => now()->toDateString(),
                'date_fin' => null,
                'heure_arriver' => $heure_actuelle,
                'heure_fin' => null,
                'statut' => 1,
                'autorisation_absence' => 'Non',
            ]);
            return redirect()->back()->with('success', 'Vous avez pointé avec succès.');
        } elseif ($request->pointagesortie) {
            if (
                !is_array($request->description) ||
                count(array_filter($request->description, fn($d) => trim($d) !== '')) === 0
            ) {
                return redirect()
                    ->back()
                    ->withInput() // pour que les anciennes valeurs restent
                    ->with('error', 'Veuillez définir au moins une cause de votre sortie.');
            }

            $entreprise = Entreprise::find($user->entreprise_id);

            if (!$entreprise) {
                return redirect()->back()->with('error', 'Entreprise non trouvée.');
            }

            // dd($request->latitude."/". $request->longitude);
            // Vérification de la géolocalisation
            $distance = $this->calculateDistance(
                $entreprise->latitude,
                $entreprise->longitude,
                $request->latitude,
                $request->longitude
            );

            // Vérifier si la distance est supérieure à 200 mètres
            if ($distance > 700) {
                return redirect()->back()->with('error', 'Oups, quelque chose n’a pas fonctionné. Essayez à nouveau !');
            }

            // Vérifier si le compte est actif
            if ($user->statut == 0) {
                return redirect()->back()->with('error', 'Votre compte est désactivé.');
            }

            // Vérifier s’il a déjà pointé aujourd’hui
            $dejaPointage = Pointage::where('user_id', $user->id)
                ->whereDate('date_arriver', now()->toDateString())
                ->first();

            if (!$dejaPointage) {
                return redirect()->route('pointage_compte')->with('error', 'Vous n\'avez pas encore pointé aujourd\'hui.');
            }

            if ($dejaPointage->statut == 0) {
                return redirect()->route('pointage_compte')->with('error', 'Vous avez déjà pointé votre sortie,veuillez pointé votre entrée.');
            }
            // Enregistrer le pointage de sortie

            $pointageIntermediaire = new PointagesIntermediaire();
            $pointageIntermediaire->pointage_id = $dejaPointage->id;
            $pointageIntermediaire->heure_sortie = $heure_actuelle;
            $pointageIntermediaire->heure_entrer = null;
            $pointageIntermediaire->statut = 1;
            $pointageIntermediaire->save();
            // Enregistrer les descriptions
            foreach ((array) $request->description as $description) {
                $description = trim($description);

                if ($description !== '') {
                    DescriptionPointage::create([
                        'pointage_intermediaire_id' => $pointageIntermediaire->id,
                        'description' => $description,
                    ]);
                }
            }

            if ($dejaPointage) {
                $le_pointage = Pointage::find($dejaPointage->id);
                $le_pointage->statut = 0;
                $le_pointage->save();
            }
            return redirect()->route('index_employer')->with('success', 'Le pointage de sortie a été effectué avec succès.');
        }
    }
    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */


    /**
     * Update the specified resource in storage.
     */

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
