<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Models\Absence;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ManagerAbsenceController extends Controller
{
    // Affiche le tableau de bord avec les demandes en attente
    public function index()
    {
        $pendingAbsences = Absence::where('status', 'demandé')
            ->with('user') // Eager loading pour éviter les requêtes N+1
            ->orderBy('created_at', 'asc')
            ->get();

        return view('components.manager.absences.index', compact('pendingAbsences'));
    }

    // Met à jour le statut d'une demande (Approuver / Rejeter)
    public function updateStatus(Request $request, Absence $absence)
    {
        $validated = $request->validate([
            'status' => 'required|in:approuvé,rejeté',
        ]);

        $absence->status = $validated['status'];
        $absence->approved_by = Auth::id();
        $absence->approved_at = now();
        $absence->save();

        // Ici, vous pourriez aussi envoyer une notification à l'employé.

        return redirect()->route('managerindex')->with('success', 'La demande a été mise à jour.');
    }
}