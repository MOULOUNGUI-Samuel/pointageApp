<?php

namespace App\Http\Controllers;

use App\Models\Entreprise;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

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
        return view("dashboard");
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
        $entreprise_id=auth()->user()->entreprise_id;
        if ($entreprise_id == null) {
            $employes = User::all();
        }else {
            $employes = User::where('entreprise_id', $entreprise_id)->get();
        }
        $entreprises = Entreprise::All();
        return view('components.pointages.liste_employer', compact('employes','entreprises'));
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
