<?php

namespace App\Http\Controllers;

use App\Models\CategorieProfessionnelle;
use App\Models\Entreprise;
use App\Models\Module;
use App\Models\Pointage;
use App\Models\PointagesIntermediaire;
use App\Models\Service;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\ConformitySubmission;
class DashboardRHController extends Controller
{
    //
    public function index_dashboard($module_id)
    {
        //
        if (session()->has('module_nom')) {
            session()->forget('module_nom');
        }
        if (session()->has('module_logo')) {
            session()->forget('module_logo');
        }
        if (session()->has('module_id')) {
            session()->forget('module_id');
        }
        if ($module_id) {
            // Récupérer le module_id et le stocker en session
            $module = Module::find($module_id);
            // dd($module);
            if ($module) {
                session()->put('module_nom', $module->nom_module);
                session()->put('module_logo', $module->logo);
                session()->put('module_id', $module->id);
            } else {
                return redirect()->back()->with('error', 'Module non trouvé.');
            }
        }
        $entreprise_id = session('entreprise_id');

        if(session('module_nom')=='Audit de conformité'){
            // Compter les soumissions "en attente" (scope enAttente) pour CETTE entreprise
            $pendingCount = ConformitySubmission::where('entreprise_id', $entreprise_id)
            ->enAttente()
            ->count();
        }else{
            $pendingCount=null;
        }
        $deuxSemainesPlusTard = now()->addWeeks(2)->endOfDay();
        $entreprises = Entreprise::All();
        $modules = Module::All();
        $services = Service::where('entreprise_id', $entreprise_id)->get();
        $categories = CategorieProfessionnelle::where('entreprise_id', $entreprise_id)->get();

        $utilisateursFinContrats = User::where('entreprise_id', $entreprise_id)
            ->whereNotNull('date_fin_contrat')
            ->whereBetween('date_fin_contrat', [now()->startOfDay(), $deuxSemainesPlusTard])
            ->get()
            ->map(function ($user) {
                $user->jours_restant = now()->diffInDays(\Carbon\Carbon::parse($user->date_fin_contrat), false);
                return $user;
            });

        $role_user = User::where('id', auth()->user()->id)->with('role')->first();
        if (!$role_user) {
            return redirect()->back()->with('error', 'Utilisateur non trouvé.');
        }


        $employes = User::where('entreprise_id', $entreprise_id)->get();
        $employesActifs = User::where('entreprise_id', $entreprise_id)->where('statu_user', 1)->get();
        $employesNePointePas = User::where('entreprise_id', $entreprise_id)->where('statu_user', 1)->where('statut', 0)->get();
        $employesInactifs = User::where('entreprise_id', $entreprise_id)->where('statu_user', 0)->get();

        $pointages_oui = Pointage::whereHas('user', fn($query) => $query->where('entreprise_id', $entreprise_id)->where('statu_user', 1)->where('statut', 1))
            ->where('date_arriver', now()->format('Y-m-d'))
            ->get();

        $users_non_existants = User::where('entreprise_id', $entreprise_id)
            ->whereDoesntHave('pointage', function ($query) {
                $query->whereDate('date_arriver', now()->format('Y-m-d'));
            })
            ->where('statu_user', 1)
            ->where('statut', 1)
            ->get();

        $pointage_intermediaires = PointagesIntermediaire::whereHas('pointage', fn($query) => $query->whereHas('user', fn($subQuery) => $subQuery->where('entreprise_id', auth()->user()->entreprise_id)))
            ->whereHas('pointage', fn($query) => $query->where('date_arriver', now()->format('Y-m-d')))
            ->get();

        $total = count($employes);
        $pourcentages = [
            'actifs' => $total > 0 ? round((count($employesActifs) * 100) / $total, 2) : 0,
            'conges' => 0, // adapte ici si tu veux un vrai calcul
            'inactifs' => $total > 0 ? round((count($employesInactifs) * 100) / $total, 2) : 0,
        ];
        $pourcentagesContrats = [
            'Contrats actifs' => $total > 0 ? 90 : 0,
            'Contrats inactifs' => $total > 0 ? 10 : 0,
        ];

        return view("dashboard", compact(
            'employes',
            'pointages_oui',
            'users_non_existants',
            'pointage_intermediaires',
            'employesActifs',
            'employesInactifs',
            'employesNePointePas',
            'pourcentages',
            'pourcentagesContrats',
            'utilisateursFinContrats',
            'entreprises',
            'modules',
            'pendingCount',
            'services',
            'categories',
        ));
    }

    public function change_entreprise($entreprise_id)
    { 
        session()->put('entreprise_id', $entreprise_id);
        $entreprise = Entreprise::find($entreprise_id);
        session()->put('entreprise_nom', $entreprise->nom_entreprise);
        session()->put('entreprise_logo', $entreprise->logo);
        session()->put('entreprise_code', $entreprise->code_entreprise);
        return redirect()->back();
    }
}
