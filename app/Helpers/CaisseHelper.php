<?php

namespace App\Helpers;

use SoapClient;
use SoapFault;
use Illuminate\Support\Facades\Auth;
use App\Models\CaisseAuthentification;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;


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
}
