<?php

namespace App\Http\Controllers;

use App\Models\Pointage;
use App\Models\PointagesIntermediaire;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardRHController extends Controller
{
    //
    public function index_dashboard()
    {
        //
        $entreprise_id = auth()->user()->entreprise_id;
        $employes = User::where('entreprise_id', $entreprise_id)->get();
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

        return view("components.yodirh.dashboard", compact('employes', 'pointages_oui', 'users_non_existants', 'pointage_intermediaires'));
    }
}
