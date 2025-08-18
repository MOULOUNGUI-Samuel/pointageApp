<?php

namespace App\Http\Controllers;

use App\Models\Categorie;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Helpers\CaisseHelper;
use App\Models\User;
use App\Models\Variable;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
class CaisseWebController extends Controller
{

    public function handleLogin(Request $request): JsonResponse
    {
        // --- ÉTAPE 0 : VALIDATION DES CHAMPS ---
        $validated = $request->validate([
            'codesociete' => 'required|string',
            'login' => 'required|string',
            'mdp' => 'string|nullable',
        ], [
            'codesociete.required' => 'Le code société est obligatoire.',
            'login.required' => 'L\'identifiant est requis.',
        ]);


        // --- ÉTAPE 1 : VÉRIFICATION VIA L'API SOAP ---
        // On vérifie d'abord si les identifiants sont valides.
        $isLoginValid = CaisseHelper::loginCaisse($validated);

        // Si la première vérification échoue, on s'arrête immédiatement.
        if (!$isLoginValid) {
            return response()->json([
                'success' => false,
                'message' => '❌ Identifiants invalides ou accès refusé par le service de vérification.'
            ], 401);
        }


        // --- ÉTAPE 2 : CONNEXION ET RÉCUPÉRATION DU TOKEN VIA L'API REST ---
        // Cette étape n'est exécutée que si l'étape 1 a réussi.
        $apiResponse = CaisseHelper::loginCaisseEnvoiInfo($validated);

        // On vérifie le résultat de la deuxième API.
        if ($apiResponse['success']) {
            // Si la connexion réussit, on renvoie les infos de redirection.
            return response()->json($apiResponse, 200);
        }

        // Si la deuxième API échoue (par ex: service indisponible), on renvoie une erreur.
        // Le message d'erreur provient directement du Helper.
        return response()->json([
            'success' => false,
            'message' => $apiResponse['message'] ?? 'Le service de connexion à la caisse a rencontré un problème.'
        ], 503); // 503 Service Unavailable est plus approprié ici qu'un 401, car on sait que les identifiants sont bons.
    }

    public function paie()
    {
        $entreprise_id = session('entreprise_id');
    
        if (!$entreprise_id) {
            // La session 'entreprise_id' n'est pas définie
            return redirect()->route('erreur')->with('message', 'Session entreprise_id non définie.'); // Remplace 'erreur' par ta route d'erreur
        }
    
        $utilisateurs = User::with(['entreprise','categorieProfessionnelle', 'service', 'role', 'pays', 'ville'])
            ->orderBy('id', 'desc')
            ->where('statu_user', 1)
            ->where('entreprise_id', $entreprise_id)
            ->get();
            $categories=Categorie::all();
    
        return view('components.yodirh.paie',compact('utilisateurs','categories'));
    }

    public function storeMultiple(Request $request)
    {
        // Validation : tableau requis, éléments non vides, distincts dans la soumission
        $validated = $request->validate(
            [
                'nom_categorie'   => ['required','array','min:1'],
                'nom_categorie.*' => ['required','string','max:150','distinct'],
                // Si tu veux empêcher les doublons base de données, ajoute :
                // 'nom_categorie.*' => ['required','string','max:150','distinct','unique:categories,nom_categorie'],
            ],
            [
                'nom_categorie.required'   => 'Ajoutez au moins une catégorie.',
                'nom_categorie.*.required' => 'Le nom de catégorie est requis.',
                'nom_categorie.*.distinct' => 'Les noms doivent être distincts.',
                // 'nom_categorie.*.unique'   => 'Cette catégorie existe déjà.',
            ]
        );

        // Insertion : idempotente (n’insère pas un doublon existant)
        DB::transaction(function () use ($validated) {
            foreach ($validated['nom_categorie'] as $nom) {
                $nom = trim($nom);
                if ($nom === '') {
                    continue;
                }
                Categorie::firstOrCreate(
                    ['nom_categorie' => $nom],
                    ['statut' => true]
                );
            }
        });

        return back()->with('success', 'Catégories enregistrées avec succès.');
    }

    public function storeAjax(Request $request)
    {
        // Toujours retourner du JSON (évite redirections Blade)
        $validator = Validator::make($request->all(), [
            'nom_variable' => ['required','string','max:150'],
            'type'         => ['required','in:gain,deduction'],
            'categorie_id' => ['required','uuid','exists:categories,id'],
        ], [
            'nom_variable.required' => 'Le nom de la variable est requis.',
            'type.in'               => 'Type invalide.',
            'categorie_id.required' => 'La catégorie est requise.',
            'categorie_id.exists'   => 'Catégorie introuvable.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation error',
                'errors'  => $validator->errors(),
            ], 422);
        }

        $data = $validator->validated();

        $variable = Variable::create([
            'nom_variable' => $data['nom_variable'],
            'type'         => $data['type'],
            'categorie_id' => $data['categorie_id'],
            'statut'       => true,
        ])->load('categorie'); // pour renvoyer le nom de la catégorie

        return response()->json([
            'message' => 'Créé',
            'data'    => [
                'id'           => $variable->id,
                'nom_variable' => $variable->nom_variable,
                'type'         => $variable->type,
                'categorie'    => [
                    'id'            => $variable->categorie->id,
                    'nom_categorie' => $variable->categorie->nom_categorie,
                ],
            ],
        ], 201);
    }

}
