<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\DescriptionPointage;
use App\Models\Entreprise;
use App\Models\Module;
use App\Models\Pays;
use App\Models\Pointage;
use App\Models\PointagesIntermediaire;
use App\Models\User;
use App\Models\Ville;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.loginAdmin');
    }

    /**
     * Handle an incoming authentication request.
     */
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

    public function store(LoginRequest $request): RedirectResponse
    {

        try {

            $request->authenticate();
            $request->session()->regenerate();
            $user = Auth::user();

            if ($user->statut == 0) {
                $request->session()->invalidate();
                $request->session()->regenerateToken();
                return redirect()->back()
                    ->withInput($request->only('matricule', 'code_entreprise'))
                    ->with('error', 'Votre compte a été désactivé.');
            }

            if ($request->filled('code_entreprise')) {
                $code_entreprise = trim($request->code_entreprise);

                // Recherche de l'entreprise liée au code saisi
                $entreprise = Entreprise::where('code_entreprise', $code_entreprise)->first();

                // Vérifie si l’entreprise existe et si c’est bien celle du user connecté
                $isSameEntreprise = $entreprise && $user->entreprise_id === $entreprise->id;

                if (!$isSameEntreprise && $user->statut_vue_entreprise === 0) {
                    $request->session()->invalidate();
                    $request->session()->regenerateToken();

                    return redirect()->back()
                        ->withInput($request->only('matricule', 'code_entreprise'))
                        ->with('error', 'Le code de l\'entreprise ne correspond pas à votre compte.');
                }
            }

            $heure_actuelle = Carbon::now('Africa/Libreville')->format('H:i:s');
            if ($request->pointage_entrer) {
                $entreprise = Entreprise::find($user->entreprise_id);

                if (!$entreprise) {
                    $request->session()->invalidate();
                    $request->session()->regenerateToken();
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
                //     $request->session()->invalidate();
                //     $request->session()->regenerateToken();
                //     return redirect()->back()->with('error', 'Oups, quelque chose n’a pas fonctionné. Essayez à nouveau !');
                // }

                // Vérifier si le compte est actif
                if ($user->statut == 0) {
                    $request->session()->invalidate();
                    $request->session()->regenerateToken();
                    return redirect()->back()->with('error', 'Votre compte est désactivé.');
                }

                // Vérifier s’il a déjà pointé aujourd’hui
                $dejaPointage = Pointage::where('user_id', $user->id)
                    ->whereDate('date_arriver', now()->toDateString())
                    ->first();

                if (isset($dejaPointage) && $dejaPointage->heure_fin != null) {
                    return redirect()->route('loginPointe')->with('error', 'Vous ne pouvez plus pointer aujourd\'hui, car vous avez déjà enregistré votre sortie de fin de service.');
                }

                if ($dejaPointage && $dejaPointage->statut == 1) {
                    $request->session()->invalidate();
                    $request->session()->regenerateToken();
                    return redirect()->route('loginPointe')->with('error', 'Vous avez déjà pointé l\'entrée.');
                }

                // Vérifier s’il a déjà pointé sa sortie aujourd’hui
                if ($dejaPointage && $dejaPointage->statut == 0) {
                    $le_pointage = Pointage::find($dejaPointage->id);
                    $le_pointage->statut = 1;
                    $le_pointage->save();


                    $PointagesIntermediaires = PointagesIntermediaire::where('pointage_id', $dejaPointage->id)
                        ->orderBy('heure_sortie', 'desc')
                        ->first();
                    $le_pointageIntermediaire = PointagesIntermediaire::find($PointagesIntermediaires->id);
                    if ($le_pointageIntermediaire) {
                        $le_pointageIntermediaire->statut = 1;
                        $le_pointageIntermediaire->heure_entrer = $heure_actuelle;
                        $le_pointageIntermediaire->save();
                    }
                    // Vérifier s’il a déjà pointé sa sortie aujourd’hui
                    if ($PointagesIntermediaires) {
                        $ladescriptionPointages = DescriptionPointage::where('pointage_intermediaire_id', $PointagesIntermediaires->id)
                            ->get();

                        foreach ($ladescriptionPointages as $ladescriptionPointage) {
                            $le_pointageIntermediaire_ = DescriptionPointage::find($ladescriptionPointage->id);
                            if ($le_pointageIntermediaire_) {
                                $le_pointageIntermediaire_->statut = 1;
                                $le_pointageIntermediaire_->save();
                            }
                        }
                    }

                    $request->session()->invalidate();
                    $request->session()->regenerateToken();
                    return redirect()->route('loginPointe')->with('success', 'Vous avez pointé votre entrée avec succès.');
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
                $request->session()->invalidate();
                $request->session()->regenerateToken();
                return redirect()->route('loginPointe')->with('success', 'Vous avez pointé avec succès.');
            } elseif ($request->pointagesortie) {
                if (
                    !is_array($request->description) ||
                    count(array_filter($request->description, fn($d) => trim($d) !== '')) === 0
                ) {
                    $request->session()->invalidate();
                    $request->session()->regenerateToken();
                    return redirect()
                        ->back()
                        ->withInput() // pour que les anciennes valeurs restent
                        ->with('error', 'Veuillez définir au moins une cause de votre sortie.');
                }

                $entreprise = Entreprise::find($user->entreprise_id);

                if (!$entreprise) {
                    $request->session()->invalidate();
                    $request->session()->regenerateToken();
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

                // // Vérifier si la distance est supérieure à 200 mètres
                // if ($distance > 700) {
                //     $request->session()->invalidate();
                //     $request->session()->regenerateToken();
                //     return redirect()->back()->with('error', 'Oups, quelque chose n’a pas fonctionné. Essayez à nouveau !');
                // }

                // Vérifier si le compte est actif
                if ($user->statut == 0) {
                    $request->session()->invalidate();
                    $request->session()->regenerateToken();
                    return redirect()->back()->with('error', 'Votre compte est désactivé.');
                }

                // Vérifier s’il a déjà pointé aujourd’hui
                $dejaPointage = Pointage::where('user_id', $user->id)
                    ->whereDate('date_arriver', now()->toDateString())
                    ->first();
                if (isset($dejaPointage) && $dejaPointage->heure_fin != null) {
                    return redirect()->route('loginPointe')->with('error', 'Vous ne pouvez plus effectuer de pointage, car votre sortie de fin de journée a déjà été enregistrée.');
                }
                if (!$dejaPointage) {
                    $request->session()->invalidate();
                    $request->session()->regenerateToken();
                    return redirect()->route('loginPointe')->with('error', 'Vous n\'avez pas encore pointé aujourd\'hui.');
                }

                if ($dejaPointage->statut == 0) {
                    $request->session()->invalidate();
                    $request->session()->regenerateToken();
                    return redirect()->route('loginPointe')->with('error', 'Vous avez déjà pointé votre sortie,veuillez pointé votre entrée.');
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
                        if ($description == "fin de service") {
                            $le_pointageFin = Pointage::find($dejaPointage->id);
                            $le_pointageFin->heure_fin = $heure_actuelle;
                            $le_pointageFin->save();
                        }
                    }
                }

                if ($dejaPointage) {
                    $le_pointage = Pointage::find($dejaPointage->id);
                    $le_pointage->statut = 0;
                    $le_pointage->save();
                }

                $request->session()->invalidate();
                $request->session()->regenerateToken();
                return redirect()->route('loginPointe')->with('success', 'Le pointage de sortie a été effectué avec succès.');
            } else {
                // Récupérer une variable et la stocker en session

                $role_user = User::where('id', $user->id)->with('role')->first();

                if ($request->mobileforme) {
                    return redirect()->route('index_employer');
                }
                if ($role_user->role->nom == 'RH' || $role_user->role->nom == 'Admin') {
                    // dd($request->module_id);
                    if ($request->code_entreprise) {
                        // Récupérer le module_id et le stocker en session
                        $code_entreprise = trim($request->code_entreprise);
                        $entreprise = Entreprise::where('code_entreprise', $code_entreprise)->first();
                        // dd($module);
                        if ($entreprise) {
                            session()->put('entreprise_nom', $entreprise->nom_entreprise);
                            session()->put('entreprise_logo', $entreprise->logo);
                            session()->put('entreprise_id', $entreprise->id);
                        } else {
                            $request->session()->invalidate();
                            $request->session()->regenerateToken();
                            return redirect()->back()
                                ->withInput($request->only('matricule', 'code_entreprise'))
                                ->with('error', 'Le code de l\'entreprise est incorrect. Veuillez vérifier et réessayer.');
                        }
                    }
                    // Redirection pour RH et Admin
                    return redirect()->route('components.liste_module');
                } else {

                    // Redirection pour Employer
                    return redirect()->route('index_employer');
                }
            }
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput($request->only('matricule', 'code_entreprise'))
                ->withErrors(['login' => 'Informations de connexion incorrectes !']);
        }
    }


    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();
        // Supprime toute URL "intended" stockée
        $request->session()->forget('url.intended');

        return redirect('/loginGroupe');
    }
    public function logout_module(Request $request, $id): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        // Supprime toute URL "intended" stockée
        $request->session()->forget('url.intended');

        return redirect('/loginGroupe');
    }
}
