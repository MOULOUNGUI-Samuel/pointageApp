<?php

namespace App\Helpers;

use SoapClient;
use SoapFault;
use Illuminate\Support\Facades\Auth;
use App\Models\CaisseAuthentification;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use GuzzleHttp\Client;


class CaisseHelper
{
    protected static string $wsdlUrl = 'http://45.155.249.99/WS_YODIGEST_ERP_WEB/awws/WS_YODIGEST_ERP.awws?wsdl';

    public static function loginCaisse(array $data): bool
    {
        // if (!isset($data['codesociete'], $data['login'], $data['mdp'])) {
        //     return false;
        // }

        try {
            // 1. Vérifier la validité avec le service SOAP
            $soap = new \SoapClient(self::$wsdlUrl);

            $result = $soap->Mobile_verifie_login(
                $data['codesociete'],
                $data['login'],
                $data['mdp']
            );

            if ($result !== true) {
                return false; // Si SOAP échoue, stop ici
            }

            // 2. Vérifie si l'utilisateur existe en local
            $userCaisse = CaisseAuthentification::where('user_id', Auth::id())->first();

            if ($userCaisse) {
                // Vérifie si les données sont différentes
                $needsUpdate = $userCaisse->code_societe !== $data['codesociete']
                    || $userCaisse->login !== $data['login']
                    || !Hash::check($data['mdp'], $userCaisse->mot_de_passe);

                if ($needsUpdate) {
                    $userCaisse->update([
                        'code_societe' => $data['codesociete'],
                        'login' => $data['login'],
                        'mot_de_passe' => Hash::make($data['mdp']),
                    ]);
                }

                return true;
            }

            // Si l'utilisateur n'existe pas → création avec hash du mdp
            CaisseAuthentification::create([
                'user_id' => Auth::id(),
                'code_societe' => $data['codesociete'],
                'login' => $data['login'],
                'mot_de_passe' => Hash::make($data['mdp']),
            ]);

            return true;
        } catch (\SoapFault $e) {
            Log::error('SOAP erreur : ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Appelle l'API REST de l'application de caisse et gère la réponse.
     *
     * @param array $credentials
     * @return array ['success' => bool, 'redirect_url' => ?string]
     */
    public static function loginCaisseEnvoiInfo(array $credentials): array
    {
        // L'URL de l'API de l'app de caisse (celle avec Sanctum)
        $apiCaisseUrl = 'http://URL_DE_VOTRE_APP_CAISSE/api/login';

        $client = new Client(['timeout' => 10.0]); // Ajout d'un timeout

        try {
            $response = $client->request('POST', $apiCaisseUrl, [
                'headers' => ['Accept' => 'application/json'],
                'form_params' => [
                    // Assurez-vous que les noms des clés correspondent à ce que l'API de caisse attend
                    // Par exemple, si l'app de caisse a un champ 'email' et non 'login' :
                    'email' => $credentials['login'],
                    'password' => $credentials['mdp'],
                    // Si 'codesociete' est nécessaire, il faut l'ajouter ici. Sinon, on le retire.
                    // 'codesociete' => $credentials['codesociete'],
                ]
            ]);

            // Si la requête réussit (code 200), on traite la réponse
            if ($response->getStatusCode() === 200) {
                $data = json_decode($response->getBody()->getContents(), true);

                // On récupère le token d'accès
                $accessToken = $data['access_token'] ?? null;

                if (!$accessToken) {
                    // Si l'API n'a pas renvoyé de token, c'est un échec
                    return ['success' => false, 'redirect_url' => null];
                }

                // On construit l'URL de redirection vers l'app de caisse
                // On passe le token en paramètre pour une connexion automatique "single sign-on" (SSO)
                $redirectUrl = 'http://URL_DE_VOTRE_APP_CAISSE/auto-login?token=' . $accessToken;

                return [
                    'success' => true,
                    'redirect_url' => $redirectUrl,
                    'message' => 'Connexion réussie.'
                ];
            } else {
                // Cas où l'API répond mais avec un code d'erreur
                return ['success' => false, 'message' => 'Les identifiants fournis sont incorrects.'];
            }
        } catch (\GuzzleHttp\Exception\ConnectException $e) {
            // Erreur spécifique si le serveur de caisse est injoignable
            Log::error('Erreur de connexion à l´API de la caisse: ' . $e->getMessage());
            return ['success' => false, 'message' => 'Le service de connexion à la caisse est indisponible.'];
        } catch (\Exception $e) {
            // Toutes les autres erreurs
            Log::error('Erreur Guzzle générique: ' . $e->getMessage());
            return ['success' => false, 'message' => 'Une erreur technique est survenue.'];
        }

    }
}
