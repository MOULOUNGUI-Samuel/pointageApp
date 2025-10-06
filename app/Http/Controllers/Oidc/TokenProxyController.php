<?php

namespace App\Http\Controllers\Oidc;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Firebase\JWT\JWT;

class TokenProxyController extends Controller
{
    //
    public function exchange(Request $request)
    {
        $issuer = rtrim(config('app.url'), '/');

        // 1) Appel du vrai /oauth/token (Passport)
        $passportResp = Http::asForm()->post($issuer.'/oauth/token', $request->all());
        if (!$passportResp->ok()) {
            return response()->json($passportResp->json(), $passportResp->status());
        }
        $data = $passportResp->json();
        $accessToken = $data['access_token'] ?? null;

        // 2) UserInfo via access_token
        $userInfo = Http::withToken($accessToken)->get($issuer.'/oauth/userinfo')->json();

        // 3) Construire ID Token RS256
        $kid = config('oidc.kid');
        $private = Storage::disk('local')->get('oidc/oidc-private.pem');

        $now = time();
        $payload = array_filter([
            'iss'   => $issuer,
            'aud'   => $request->input('client_id'),
            'sub'   => $userInfo['sub'] ?? null,
            'iat'   => $now,
            'exp'   => $now + 3600,
            'nonce' => $request->input('nonce'),
        ]);

        $idToken = JWT::encode($payload, $private, 'RS256', $kid);

        // 4) Ajouter id_token à la réponse
        $data['id_token'] = $idToken;

        return response()->json($data);
    }
}
