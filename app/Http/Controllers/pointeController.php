<?php

namespace App\Http\Controllers;

use App\Models\DescriptionPointage;
use App\Models\Entreprise;
use App\Models\Module;
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
        // $entreprise_id = auth()->user()->entreprise_id;
        // $liste_pointages = Pointage::whereHas('user.entreprise', fn($query) => $query->where('entreprise_id', $entreprise_id))
        //     ->get();
        $entreprise_id = session('entreprise_id');
        $liste_pointages = Pointage::with('user.entreprise')->whereHas('user.entreprise', function ($subQuery) use ($entreprise_id) {
            $subQuery->where('id', $entreprise_id);
        })
            ->orderBy('date_arriver', 'desc')
            ->get();

        
        $Pointages = Pointage::with('user.entreprise')->whereHas('user.entreprise', function ($subQuery) use ($entreprise_id) {
            $subQuery->where('id', $entreprise_id);
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

        return view('components.yodirh.suivi_absences_conger', compact('liste_pointages', 'Pointages', 'cause_sorties'));
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



        return view('components.yodirh.Suivi_profile', compact('Pointages', 'cause_sorties', 'user'));
    }

    public function profil_employe()
    {
        return view('components.pointages.profil_employe');
    }
    public function pointage_sortie_connecter()
    {
        return view('components.pointages.pointage_sortie_connect');
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
