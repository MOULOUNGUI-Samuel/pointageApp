<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\CaisseHelper;
use Illuminate\Http\JsonResponse;

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
}
