<?php

namespace App\Helpers;

use App\Models\ApprobaCaisse;
use App\Models\ApprobaCaution;
use App\Models\ApprobaEvenement;
use App\Models\RegimeAssurance;
use App\Models\Pays;
use App\Models\Ville;
use App\Models\Beneficiaire;
use App\Models\Entreprise;
use App\Models\LienDoc;
use App\Models\Module;
use App\Models\Permission;
use App\Models\PermissionUser;
use Carbon\Carbon;
use DateTime;
use Exception;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use Illuminate\Support\Facades\Auth;

class DateHelper
{
    private static function extraireCodeTemps($datetime)
    {
        // Extract day, hour, minute, and second as a unique code
        return $datetime->format('dHis');
    }

    public static function dossier_info()
    {
        $datetime = Auth::user()->created_at;

        // Generate the code using the private method
        $code = self::extraireCodeTemps(new DateTime($datetime));

        // Retrieve documents containing the code
        $lienDocuments = LienDoc::where('entreprise_id', session('entreprise_id'))
            ->where('nom_lien', 'LIKE', '%' . $code . '%')
            ->get();

        // Retrieve active modules
        $modules = Module::where('statut', 1)->get();
        $entreprise = Entreprise::where('statut', 1)->get();


        return [
            'lienDocuments' => $lienDocuments,
            'modules' => $modules,
            'entreprise' => $entreprise,
        ];
    }
    public static function convertirDateFormat($date)
    {
        // Convertir la date du format 'Y-m-d' en objet DateTime
        $dateObj = DateTime::createFromFormat('Y-m-d', $date);

        if ($dateObj) {
            // Reformater la date en 'd/m/Y'
            return $dateObj->format('d/m/Y');
        } else {
            // Si la date n'est pas valide, retourner null ou un message d'erreur
            return null; // Ou 'Format de date invalide'
        }
    }
    public static function convertirDateExcel($date)
    {
        try {
            // Vérifier si c'est une date brute Excel (numéro)
            if (is_numeric($date)) {
                $dateObject = Date::excelToDateTimeObject($date);
            } else {
                // Sinon, essayer de créer un objet DateTime à partir d'une chaîne
                $dateObject = new DateTime($date);
            }

            // Retourner la date formatée en jj/mm/aaaa
            return $dateObject->format('Y-m-d');
        } catch (Exception $e) {
            // Si une erreur survient, retourner null ou un message d'erreur
            return null;
        }
    }
    public static function convertirDateEnTexte($date)
    {
        // Convertir la date du format 'd/m/Y' en objet DateTime
        $dateObj = DateTime::createFromFormat('d/m/Y', $date);

        if ($dateObj) {
            // Traduction manuelle des jours et des mois
            $jours = ['Sunday' => 'dimanche', 'Monday' => 'lundi', 'Tuesday' => 'mardi', 'Wednesday' => 'mercredi', 'Thursday' => 'jeudi', 'Friday' => 'vendredi', 'Saturday' => 'samedi'];
            $mois = ['January' => 'janvier', 'February' => 'février', 'March' => 'mars', 'April' => 'avril', 'May' => 'mai', 'June' => 'juin', 'July' => 'juillet', 'August' => 'août', 'September' => 'septembre', 'October' => 'octobre', 'November' => 'novembre', 'December' => 'décembre'];

            // Format de la date en anglais
            $jourEn = $dateObj->format('l');
            $moisEn = $dateObj->format('F');
            $jour = $jours[$jourEn] ?? $jourEn;
            $mois = $mois[$moisEn] ?? $moisEn;

            // Formatter la date en 'lundi 12 février 2024'
            return $jour . ' ' . $dateObj->format('d') . ' ' . $mois . ' ' . $dateObj->format('Y');
        } else {
            // Si la date n'est pas valide, retourner null ou un message d'erreur
            return null;
        }
    }
    public static function formatNumber($number)
    {
        return number_format($number, 0, '.', ' '); // Formate le nombre avec des espaces comme séparateurs de milliers
    }
}
