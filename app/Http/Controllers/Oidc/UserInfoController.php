<?php

namespace App\Http\Controllers\Oidc;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class UserInfoController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request): JsonResponse
    {
        $user = $request->user(); // via access_token (guard api/passport)
        $scopes = collect(optional($user->token())?->scopes ?? []);

        $claims = [
            "sub" => (string)$user->id,
            "name" => $user->nom,
            "preferred_username" => $user->prenom ?? $user->email_professionnel,
            "email" => $user->email_professionnel,
            "picture" => $user->photo ?? null,
        ];

        if ($scopes->contains('entreprise')) {
            $societe = $user->societe ?? null;
            $claims["role"] = ["nom" => optional($user->role)->nom];
            $claims["entreprise"] = $societe ? [
                "code_societe" => $societe->code_societe ?? null,
                "nom_societe"  => $societe->nom_societe ?? null,
                "logo"         => $societe->logo_url ?? null,
                "email"        => $societe->email ?? null,
                "telephone"    => $societe->telephone ?? null,
                "statut"       => $societe->statut ?? null,
                "adresse"      => $societe->adresse ?? null,
            ] : null;
        }

        return response()->json($claims);
    }
}
