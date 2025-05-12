<?php

namespace App\Http\Controllers;

use App\Models\CategorieProfessionnelle;
use App\Models\Pointage;
use App\Models\PointagesIntermediaire;
use App\Models\Service;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DashboardRHController extends Controller
{
    //
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

        return view("components.yodirh.dashboard", compact('employes', 'pointages_oui', 'users_non_existants', 'pointage_intermediaires'));
    }

    public function categorieprofessionel(Request $request)
    {
        $categorieprofessionels = CategorieProfessionnelle::orderBy('created_at', 'desc')->get();
        return view('components.yodirh.categorieprofessionel', compact('categorieprofessionels'));
    }
    public function Ajoutcategorieprofessionels(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'nom_categorie_professionnelle' => 'required',
            ],
            [
                'nom_categorie_professionnelle.required' => 'Le nom de la catégorie est requis'
            ]
        );

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $categories = new CategorieProfessionnelle();

        $categories->nom_categorie_professionnelle = $request->nom_categorie_professionnelle;
        $categories->description = $request->description;
        $categories->statut = 1;
        $categories->save();

        return redirect()->back()->with('success', 'Module ajouté avec succès');
    }

    public function modifier_categorieprofessionel(Request $request, $id)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'nom_categorie_professionnelle' => 'required',
            ],
            [
                'nom_categorie_professionnelle.required' => 'Le nom de la catégorie est requis'
            ]
        );

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $categories = CategorieProfessionnelle::findOrFail($id);

        $categories->nom_categorie_professionnelle = $request->nom_categorie_professionnelle;
        $categories->description = $request->description;
        $categories->save();

        return redirect()->back()->with('success', 'Catégorie modifiée avec succès');
    }
    public function services(Request $request)
    {
        $services = Service::orderBy('created_at', 'desc')->get();
        return view('components.yodirh.services', compact('services'));
    }
    public function Ajoutservices(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'nom_service' => 'required',
            ],
            [
                'nom_service.required' => 'Le nom du service est requis'
            ]
        );

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $services = new Service();

        $services->nom_service = $request->nom_service;
        $services->description = $request->description;
        $services->statut = 1;
        $services->save();

        return redirect()->back()->with('success', 'Module ajouté avec succès');
    }

    public function modifier_service(Request $request, $id)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'nom_service' => 'required',
            ],
            [
                'nom_service.required' => 'Le nom du service est requis'
            ]
        );

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $services = Service::findOrFail($id);

        $services->nom_service = $request->nom_service;
        $services->description = $request->description;
        $services->save();

        return redirect()->back()->with('success', 'Service modifié avec succès');
    }
}
