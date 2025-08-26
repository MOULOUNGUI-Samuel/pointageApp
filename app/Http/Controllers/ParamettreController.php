<?php

namespace App\Http\Controllers;

use App\Models\CategorieProfessionnelle;
use App\Models\Entreprise;
use Illuminate\Http\Request;
use App\Models\Module;
use App\Models\Service;
use Illuminate\Support\Facades\Validator;

class ParamettreController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function listemodules()
    {
        $modules = Module::orderBy('created_at', 'asc')->get();
        $entreprises= Entreprise::orderBy('created_at', 'asc')->get();
        $utilisateurs = \App\Models\User::orderBy('created_at', 'asc')
            ->with('entreprise')
            ->get();
        return view('components.liste_modules', compact('modules', 'utilisateurs','entreprises'));
    }
    public function modules()
    {
        //
        $modules = Module::orderBy('created_at', 'asc')->get();

        return view("components.modul_admin", compact('modules'));
    }
    public function liste_entreprise()
    {
        $entreprises = Entreprise::orderBy('created_at', 'desc')->get();
        return view('components.yodirh.liste_entreprise', compact('entreprises'));
    }

    public function ajoute_entreprise(Request $request)
    {

        // Validate the request data

        //
        $validator = Validator::make(
            $request->all(),
            [
                'nom_entreprise' => 'required',
                'code_entreprise' => 'required',
                'heure_ouverture' => 'required',
                'heure_fin' => 'required',
                'heure_debut_pose' => 'required',
                'heure_fin_pose' => 'required',
                // 'latitude' => 'required',
                // 'longitude' => 'required',
            ],
            [
                'nom_entreprise.required' => 'Le nom de l\'entreprise est requis',
                'code_entreprise.required' => 'Le code de l\'entreprise est requis',
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
        $entreprise->code_entreprise = $request->code_entreprise;
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
    public function modifier_entreprise(Request $request, $id)
    {
        // Validate the request data
        $validator = Validator::make(
            $request->all(),
            [
                'nom_entreprise' => 'required',
                'code_entreprise' => 'required',
                'heure_ouverture' => 'required',
                'heure_fin' => 'required',
                'heure_debut_pose' => 'required',
                'heure_fin_pose' => 'required',
                'latitude' => 'required',
                'longitude' => 'required',
            ],
            [
                'nom_entreprise.required' => 'Le nom de l\'entreprise est requis',
                'code_entreprise.required' => 'Le code de l\'entreprise est requis',
                'heure_ouverture.required' => 'L\'heure d\'ouverture est requise',
                'heure_fin.required' => 'L\'heure de fin est requise',
                'heure_debut_pose.required' => 'L\'heure de début de pose est requise',
                'heure_fin_pose.required' => 'L\'heure de fin de pose est requise',
                'latitude.required' => 'La position X est requise',
                'longitude.required' => 'La position Y est requise',
            ]
        );

        // Return a success message if validation fails
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Find the existing Entreprise instance
        $entreprise = Entreprise::findOrFail($id);

        // Handle the logo upload if provided
        if ($request->hasFile('logo')) {
            $logo = $request->file('logo');
            $logoName = time() . '_' . $logo->getClientOriginalName();
            $logoPath = $logo->storeAs('logos', $logoName, 'public');
            $entreprise->logo = $logoPath;
        }

        // Update the entreprise details
        $entreprise->nom_entreprise = $request->nom_entreprise;
        $entreprise->code_entreprise = $request->code_entreprise;
        $entreprise->heure_ouverture = $request->heure_ouverture;
        $entreprise->heure_fin = $request->heure_fin;
        $entreprise->heure_debut_pose = $request->heure_debut_pose;
        $entreprise->heure_fin_pose = $request->heure_fin_pose;
        $entreprise->latitude = $request->latitude;
        $entreprise->longitude = $request->longitude;
        $entreprise->save();

        return redirect()->back()->with('success', 'Entreprise modifiée avec succès');
    }
    public function ajout_module(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'nom_module' => 'required',
            ],
            [
                'nom_module.required' => 'Le nom du module est requis'
            ]
        );

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $module = new Module();

        if ($request->hasFile('logo')) {
            $path = $request->file('logo')->store('images', 'public'); // correction ici
            $module->logo = $path;
        }

        $module->nom_module = $request->nom_module;
        $module->statut = 1;
        $module->save();

        return redirect()->back()->with('success', 'Module ajouté avec succès');
    }

    public function modifier_module(Request $request, $id)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'nom_module' => 'required',
            ],
            [
                'nom_module.required' => 'Le nom du module est requis'
            ]
        );

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $module = Module::findOrFail($id);

        if ($request->hasFile('logo')) {
            $path = $request->file('logo')->store('images', 'public');
            $module->logo = $path;
        }

        $module->nom_module = $request->nom_module;
        $module->save();

        return redirect()->back()->with('success', 'Module modifié avec succès');
    }


    public function services(Request $request)
    {
        $entreprise_id = session('entreprise_id');
        $services = Service::where('entreprise_id', $entreprise_id)->get();
        $entreprisesSansCeService = [];

        foreach ($services as $service) {
            $entreprisesSansCeService[$service->id] = Entreprise::whereDoesntHave('services', function ($query) use ($service) {
                $query->where('nom_service', $service->nom_service);
            })->get();
        }

        return view('components.yodirh.services', compact('services', 'entreprisesSansCeService'));
    }
    public function affecter_service(Request $request)
    {
        $request->validate([
            'entreprise_id' => 'required|exists:entreprises,id',
            'service_id' => 'required|exists:services,id',
        ]);

        $sourceService = Service::findOrFail($request->service_id);

        // Création d’un nouveau service pour l’entreprise cible
        $nouveauService = new Service();
        $nouveauService->nom_service = $sourceService->nom_service;
        $nouveauService->description = $sourceService->description;
        $nouveauService->entreprise_id = $request->entreprise_id;
        $nouveauService->save();

        return redirect()->back()->with('success', 'Le service a été intégré avec succès dans l\'entreprise sélectionnée.');
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

        $entreprise_id = session('entreprise_id');
        $services = new Service();
        $services->nom_service = $request->nom_service;
        $services->entreprise_id = $entreprise_id;
        $services->description = $request->description;
        $services->statut = 1;
        $services->save();


        return redirect()->back()->with('success', 'Service ajouté avec succès');
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
    public function supprimer_service($id)
    {
        $categories = Service::findOrFail($id);
        $categories->delete();
        return redirect()->back()->with('success', 'Service supprimée avec succès');
    }
    public function categorieprofessionel(Request $request)
    {
        $entreprise_id = session('entreprise_id');
        $categorieprofessionels = CategorieProfessionnelle::where('entreprise_id', $entreprise_id)->orderBy('created_at', 'desc')->get();

         $entreprisesSansCategorie = [];

        foreach ($categorieprofessionels as $categorieprofessionel) {
            $entreprisesSansCategorie
            [$categorieprofessionel->id] = Entreprise::whereDoesntHave('categorieProfessionels', function ($query) use ($categorieprofessionel) {
                $query->where('nom_categorie_professionnelle', $categorieprofessionel->nom_categorie_professionnelle);
            })->get();
        }

        return view('components.yodirh.categorieprofessionel', compact('categorieprofessionels', 'entreprisesSansCategorie'));
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
        $entreprise_id = session('entreprise_id');

        $categories = new CategorieProfessionnelle();
        $categories->entreprise_id = $entreprise_id;
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
    public function supprimer_categorieprofessionel($id)
    {
        $categories = CategorieProfessionnelle::findOrFail($id);
        $categories->delete();
        return redirect()->back()->with('success', 'Catégorie supprimée avec succès');
    }

    public function affecter_categorie(Request $request)
    {
        $request->validate([
            'entreprise_id' => 'required|exists:entreprises,id',
            'categorie_id' => 'required|exists:categorie_professionnelles,id',
        ]);

        $sourceCategorie = CategorieProfessionnelle::findOrFail($request->categorie_id);

        // Création d’un nouveau service pour l’entreprise cible
        $nouveauCategorie = new CategorieProfessionnelle();
        $nouveauCategorie->nom_categorie_professionnelle = $sourceCategorie->nom_categorie_professionnelle;
        $nouveauCategorie->description = $sourceCategorie->description;
        $nouveauCategorie->entreprise_id = $request->entreprise_id;
        $nouveauCategorie->save();

        return redirect()->back()->with('success', 'La Catégorie a été intégré avec succès dans l\'entreprise sélectionnée.');
    }
}
