<?php

namespace App\Http\Controllers;

use App\Models\CategorieProfessionnelle;
use App\Models\Module;
use App\Models\Pointage;
use App\Models\PointagesIntermediaire;
use App\Models\Service;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

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
        $role_user = User::where('id', auth()->user()->id)->with('role')->first();


        $employes = User::where('entreprise_id', $entreprise_id)->get();
        $employesActifs = User::where('entreprise_id', $entreprise_id)->where('statu_user', 1)->get();
        $employesInactifs = User::where('entreprise_id', $entreprise_id)->where('statu_user', 0)->get();

        $pointages_oui = Pointage::whereHas('user', fn($query) => $query->where('entreprise_id', $entreprise_id))
            ->where('date_arriver', now()->format('Y-m-d'))
            ->get();

        $users_non_existants = User::where('entreprise_id', $entreprise_id)
            ->whereDoesntHave('pointage', function ($query) {
                $query->whereDate('date_arriver', now()->format('Y-m-d'));
            })
            ->get();

        $pointage_intermediaires = PointagesIntermediaire::whereHas('pointage', fn($query) => $query->whereHas('user', fn($subQuery) => $subQuery->where('entreprise_id', auth()->user()->entreprise_id)))
            ->whereHas('pointage', fn($query) => $query->where('date_arriver', now()->format('Y-m-d')))
            ->get();

        return view("dashboard", compact(
            'employes',
            'pointages_oui',
            'users_non_existants',
            'pointage_intermediaires',
            'employesActifs',
            'employesInactifs'
        ));
    }
}
