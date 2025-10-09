<?php

namespace App\Http\Controllers\Oidc;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Throwable;
use Illuminate\Support\Facades\Log;

class TokenProxyController extends Controller
{
    public function exchange(Request $request)
    {
        // 1) Appeler Passport /oauth/token SANS passer par le réseau
        //    (évite le deadlock du serveur de dev)
        $sub = Request::create('/oauth/token', 'POST', $request->all());
        $sub->headers->set('Accept', 'application/json');

        $resp = app()->handle($sub);                    // sous-requête interne
        $status = $resp->getStatusCode();

        if ($status >= 400) {
            // renvoyer l’erreur Passport telle quelle
            return response($resp->getContent(), $status, $resp->headers->all());
        }

        $data = json_decode($resp->getContent(), true);
        $accessToken = $data['access_token'] ?? null;
        if (!$accessToken) {
            return response()->json(['error' => 'server_error', 'hint' => 'no access_token'], 500);
        }

        // 2) Récup UserInfo
        $issuer = rtrim(config('app.url'), '/');
        $userInfo = Http::withToken($accessToken)
            ->acceptJson()
            ->get($issuer.'/oauth/userinfo')
            ->json();

        // 3) Fabriquer l’ID Token RS256
        $kid     = config('oidc.kid');
        $private = Storage::disk('local')->get('oidc/oidc-private.pem');

        $now = time();
        $payload = array_filter([
            'iss'   => $issuer,
            'aud'   => $request->input('client_id'),
            'sub'   => $userInfo['sub'] ?? null,
            'iat'   => $now,
            'exp'   => $now + 3600,
            // 'nonce' => $request->input('nonce'),
        ]);

        $idToken = JWT::encode($payload, $private, 'RS256', $kid);

        // 4) Ajouter id_token et retourner la réponse complète
        $data['id_token'] = $idToken;
        return response()->json($data);
    }
}
