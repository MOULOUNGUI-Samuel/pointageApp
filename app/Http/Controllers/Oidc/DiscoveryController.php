<?php

namespace App\Http\Controllers\Oidc;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class DiscoveryController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function index(): JsonResponse
    {
        $issuer = rtrim(config('app.url'), '/');
        return response()->json([
            "issuer" => $issuer,
            "authorization_endpoint" => $issuer . "/oauth/authorize",
            "token_endpoint"         => $issuer . "/oauth/token",
            "userinfo_endpoint"      => $issuer . "/oauth/userinfo",
            "jwks_uri"               => $issuer . "/oauth/jwks.json",
            "scopes_supported"       => ["openid", "profile", "email", "entreprise", "offline_access"],
            "response_types_supported" => ["code"],
            "grant_types_supported"  => ["authorization_code", "refresh_token"],
            "id_token_signing_alg_values_supported" => ["RS256"],
            "code_challenge_methods_supported" => ["S256"],
        ]);
    }
}
