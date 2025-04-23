<?php

namespace App\Http\Controllers;

use App\Models\DescriptionPointage;
use App\Models\Entreprise;
use App\Models\Pointage;
use App\Models\PointagesIntermediaire;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AdminController extends Controller
{
    /**
     * Display a listing of the resource.
     */
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
        $pointages_oui = Pointage::whereHas('user', fn($query) => $query->where('entreprise_id', $entreprise_id))
            ->where('date_arriver', now()->format('Y-m-d'))
            ->get();

        $users_non_existants = Pointage::whereDoesntHave('user', fn($query) => $query->where('entreprise_id', $entreprise_id))
            ->get();

        $pointage_intermediaires = PointagesIntermediaire::whereHas('pointage', fn($query) => $query->whereHas('user', fn($subQuery) => $subQuery->where('entreprise_id', $entreprise_id)))
            ->whereHas('pointage', fn($query) => $query->where('date_arriver', now()->format('Y-m-d')))
            ->get();

        return view("dashboard", compact('employes', 'pointages_oui', 'users_non_existants', 'pointage_intermediaires'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        try {
            $validated = $request->validate([
                'nom' => 'required|string',
                'prenom' => 'required|string',
                'date_naissance' => 'required|date_format:d/m/Y',
                'fonction' => 'required|string',
                'email' => 'required|email|unique:users,email',
                'matricule' => 'required|string|unique:users,matricule',
                'password' => 'required|string|min:6',
            ], [
                'nom.required' => 'Le nom est obligatoire.',
                'nom.max' => 'Le nom ne peut pas dépasser 100 caractères.',

                'prenom.required' => 'Le prénom est obligatoire.',
                'prenom.max' => 'Le prénom ne peut pas dépasser 100 caractères.',

                'date_naissance.required' => 'La date de naissance est obligatoire.',
                'date_naissance.date_format' => 'La date de naissance doit être au format JJ/MM/AAAA.',

                'fonction.required' => 'La fonction est obligatoire.',
                'fonction.max' => 'La fonction ne peut pas dépasser 100 caractères.',

                'email.required' => 'L\'adresse email est obligatoire.',
                'email.email' => 'L\'adresse email doit être valide.',
                'email.unique' => 'Cet email est déjà utilisé.',

                'matricule.required' => 'L\'identifiant (matricule) est obligatoire.',
                'matricule.unique' => 'Ce matricule est déjà utilisé.',
                'matricule.max' => 'Le matricule ne peut pas dépasser 50 caractères.',

                'password.required' => 'Le mot de passe est obligatoire.',
                'password.min' => 'Le mot de passe doit contenir au moins 6 caractères.',
            ]);

            $user = new User();
            $user->entreprise_id = $request->input('entreprise_id') ?? Auth::user()->entreprise_id;
            $user->nom = $request->input('nom');
            $user->prenom = $request->input('prenom');
            $user->matricule = $request->input('matricule');
            $user->email = $request->input('email');
            $user->password = Hash::make($request->input('password'));
            $user->date_naissance = \Carbon\Carbon::createFromFormat('d/m/Y', $request->input('date_naissance'))->format('Y-m-d');
            $user->fonction = $request->input('fonction');
            $user->role_user = $request->input('role_user'); // Default to 'Admin' if not provided
            $user->save();

            return redirect()->back()->with('success', 'Utilisateur ajouté avec succès.');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => 'Une erreur est survenue : ' . $e->getMessage()])->withInput();
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
    public function update(Request $request, $id)
    {
        // try {

        $validator = Validator::make(
            $request->all(),
            [
                'nom' => 'required|string',
                'prenom' => 'required|string',
                'date_naissance' => 'required',
                'fonction' => 'required|string',
                'email' => 'required|email|unique:users,email,' . $id,
                'matricule' => 'required|string|unique:users,matricule,' . $id,
                'password' => 'nullable|string|min:6',
            ],
            [
                'nom.required' => 'Le nom est obligatoire.',
                'prenom.required' => 'Le prénom est obligatoire.',
                'date_naissance.required' => 'La date de naissance est obligatoire.',
                'date_naissance.date_format' => 'La date de naissance doit être au format JJ/MM/AAAA.',
                'fonction.required' => 'La fonction est obligatoire.',
                'email.required' => 'L\'adresse email est obligatoire.',
                'email.email' => 'L\'adresse email doit être valide.',
                'email.unique' => 'Cet email est déjà utilisé.',
                'matricule.required' => 'L\'identifiant (matricule) est obligatoire.',
                'matricule.unique' => 'Ce matricule est déjà utilisé.',
                'password.min' => 'Le mot de passe doit contenir au moins 6 caractères.',
            ]
        );
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $user = User::findOrFail($id);
        if ($request->input('password')) {
            // Vérification du mot de passe
            if (!Hash::check($request->input('password1'), $user->password)) {
                return back()->withErrors(['password' => 'Ancien mot de passe est incorrect.'])->withInput();
            }
        }

        $user->nom = $request->input('nom');
        $user->prenom = $request->input('prenom');
        $user->matricule = $request->input('matricule');
        $user->email = $request->input('email');
        if ($request->input('password1') && $request->input('password')) {
            $user->password = Hash::make($request->input('password'));
        }
        $user->date_naissance = $request->input('date_naissance');
        $user->fonction = $request->input('fonction');
        $user->save();

        return redirect()->back()->with('success', 'Informations midifiées avec succès.');
        // } catch (\Exception $e) {
        //     return redirect()->back()->withErrors(['error' => 'Une erreur est survenue : ' . $e->getMessage()])->withInput();
        // }
    }

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

        if ($request->pointage_entrer) {
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
            // dd($distance);
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
                    $le_pointageIntermediaire->heure_entrer = now()->format('H:i:s');
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
                'heure_arriver' => now()->format('H:i:s'),
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
            $pointageIntermediaire->heure_sortie = now()->format('H:i:s');
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
    public function edit(string $id)
    {
        //
    }

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
