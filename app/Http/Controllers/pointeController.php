<?php

namespace App\Http\Controllers;

use App\Models\DescriptionPointage;
use App\Models\Entreprise;
use App\Models\Pointage;
use App\Models\PointagesIntermediaire;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class pointeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function loginPointe()
    {
        //
        return view('auth.login');
    }
    public function index()
    {
        //
        return view('pointeSortie');
    }
    public function index1()
    {
        //
        return view('pointeEntrer');
    }
    public function liste_presence()
    {
        //
        $entreprise_id = auth()->user()->entreprise_id;
        $liste_pointages = Pointage::whereHas('user.entreprise', fn($query) => $query->where('entreprise_id', $entreprise_id))
            ->get();

        return view('components.pointages.liste_presence', compact('liste_pointages'));
    }
    public function sortie_intermediaire()
    {
        //
        $user = Auth::user();
        $Pointages = Pointage::with('user.entreprise')->whereHas('user', function ($subQuery) use ($user) {
            $subQuery->where('entreprise_id', $user->entreprise_id);
        })
            ->orderBy('date_arriver', 'desc')
            ->get();
        $cause_sorties = [];

        foreach ($Pointages as $Pointage) {
            $pointage_intermediaires = PointagesIntermediaire::where('pointage_id', $Pointage->id)
                ->orderBy('heure_sortie', 'desc')
                ->get();

            foreach ($pointage_intermediaires as $pointage_intermediaire) {
                $DescriptionPointages = DescriptionPointage::where('pointage_intermediaire_id', $pointage_intermediaire->id)->get();

                $cause_sorties[$Pointage->id][] = [
                    'pointage_intermediaire' => $pointage_intermediaire,
                    'descriptions' => $DescriptionPointages
                ];
            }
        }


        return view('components.pointages.sortie_intermediaire', compact('Pointages', 'cause_sorties'));
    }
    public function liste_entreprise()
    {
        $entreprises = Entreprise::All();
        return view('components.pointages.liste_entreprise', compact('entreprises'));
    }
    public function pointage_compte()
    {
        $entreprises = Entreprise::All();
        return view('components.pointages.pointage_compte');
    }
    public function index_employer()
    {
        $entreprises = Entreprise::All();
        return view('components.pointages.index_employer');
    }
    public function historique_pointage()
    {
        $user = Auth::user();
        $Pointages = Pointage::with('user.entreprise')->where('user_id', $user->id)
            ->orderBy('date_arriver', 'desc')
            ->get();
        $cause_sorties = [];

        foreach ($Pointages as $Pointage) {
            $pointage_intermediaires = PointagesIntermediaire::where('pointage_id', $Pointage->id)
                ->orderBy('heure_sortie', 'desc')
                ->get();

            foreach ($pointage_intermediaires as $pointage_intermediaire) {
                $DescriptionPointages = DescriptionPointage::where('pointage_intermediaire_id', $pointage_intermediaire->id)->get();

                $cause_sorties[$Pointage->id][] = [
                    'pointage_intermediaire' => $pointage_intermediaire,
                    'descriptions' => $DescriptionPointages
                ];
            }
        }

        return view('components.pointages.historique_pointage', compact('Pointages', 'cause_sorties'));
    }
    public function Suivi_profil($id)
    {
        $Pointages = Pointage::with('user.entreprise')->where('user_id', $id)
            ->orderBy('date_arriver', 'desc')
            ->get();
        $cause_sorties = [];
        $user = User::where('id', $id)->first();
        foreach ($Pointages as $Pointage) {
            $pointage_intermediaires = PointagesIntermediaire::where('pointage_id', $Pointage->id)
                ->orderBy('heure_sortie', 'desc')
                ->get();

            foreach ($pointage_intermediaires as $pointage_intermediaire) {
                $DescriptionPointages = DescriptionPointage::where('pointage_intermediaire_id', $pointage_intermediaire->id)->get();

                $cause_sorties[$Pointage->id][] = [
                    'pointage_intermediaire' => $pointage_intermediaire,
                    'descriptions' => $DescriptionPointages
                ];
            }
        }

       

        return view('components.pointages.Suivi_profile', compact('Pointages', 'cause_sorties','user'));
    }

    public function profil_employe()
    {
        return view('components.pointages.profil_employe');
    }
    public function pointage_sortie_connecter()
    {
        return view('components.pointages.pointage_sortie_connect');
    }

    public function ajoute_entreprise(Request $request)
    {

        // Validate the request data

        //
        $validator = Validator::make(
            $request->all(),
            [
                'nom_entreprise' => 'required',
                'heure_ouverture' => 'required',
                'heure_fin' => 'required',
                'heure_debut_pose' => 'required',
                'heure_fin_pose' => 'required',
                'latitude' => 'required',
                'longitude' => 'required',
            ],
            [
                'nom_entreprise.required' => 'Le nom de l\'entreprise est requis',
                'heure_ouverture.required' => 'L\'heure d\'ouverture est requise',
                'heure_fin.required' => 'L\'heure de fin est requise',
                'heure_debut_pose.required' => 'L\'heure de début de pose est requise',
                'heure_fin_pose.required' => 'L\'heure de fin de pose est requise',
                'latitude.required' => 'La position X est requise',
                'longitude.required' => 'La position Y est requise',
            ]
        );

        // Return a success message if validation passes
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Create a new Entreprise instance and save it to the database
        $entreprise = new Entreprise();
        // Handle the logo upload if provided
        if ($request->hasFile('logo')) {
            $logo = $request->file('logo');
            $logoName = time() . '_' . $logo->getClientOriginalName();
            $logoPath = $logo->storeAs('logos', $logoName, 'public');
            $entreprise->logo = $logoPath;
        }
        $entreprise->nom_entreprise = $request->nom_entreprise;
        $entreprise->heure_ouverture = $request->heure_ouverture;
        $entreprise->heure_fin = $request->heure_fin;
        $entreprise->heure_debut_pose = $request->heure_debut_pose;
        $entreprise->heure_fin_pose = $request->heure_fin_pose;
        $entreprise->latitude = $request->latitude;
        $entreprise->longitude = $request->longitude;
        $entreprise->statut = 1;
        $entreprise->save();
        return redirect()->back()->with('success', 'Entreprise ajoutée avec succès');
    }



    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
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
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
