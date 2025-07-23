<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\CaisseHelper;
use Illuminate\Http\JsonResponse;

class CaisseWebController extends Controller
{

    public function handleLogin(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'codesociete' => 'required|string',
            'login' => 'required|string',
            'mdp' => 'string|nullable',
        ], [
            'codesociete.required' => 'Le code société est obligatoire.',
            'codesociete.string' => 'Le code société doit être une chaîne de caractères.',

            'login.required' => 'L\'identifiant est requis.',
            'login.string' => 'L\'identifiant doit être une chaîne de caractères.',

            // 'mdp.required' => 'Le mot de passe est requis.',
            // 'mdp.string' => 'Le mot de passe doit être une chaîne de caractères.',
        ]);

        $success = CaisseHelper::loginCaisse($validated);

        if ($success) {
            return response()->json([
                // 'redirect' => route('dashboard') // redirige vers la page souhaitée
                 'message' => '✅ Connexion réussie.'
            ], 401);
        }

        return response()->json([
            'message' => '❌ Identifiants invalides ou accès refusé.'
        ], 401);
    }
}
