<?php

namespace App\Http\Controllers;

use App\Models\Absence;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AbsenceController extends Controller
{
    // Affiche la liste des absences de l'utilisateur connecté
    public function index()
    {
        $absences = Absence::where('user_id', Auth::id())
            ->orderBy('start_datetime', 'desc')
            ->get();
            
        return view('components.absences.index', compact('absences'));
    }

    // Affiche le formulaire de création
    public function create()
    {
        return view('components.absences.create');
    }

    // Enregistre la nouvelle demande d'absence
    public function store(Request $request)
    {
        $validated = $request->validate([
            'type' => 'required|string|max:255',
            'start_datetime' => 'required|date',
            'end_datetime' => 'required|date|after_or_equal:start_datetime',
            'reason' => 'required|string|max:1000',
        ]);

        Absence::create([
            'user_id' => Auth::id(),
            'type' => $validated['type'],
            'start_datetime' => $validated['start_datetime'],
            'end_datetime' => $validated['end_datetime'],
            'reason' => $validated['reason'],
            'status' => 'demandé', // Statut par défaut à la création
        ]);

        return redirect()->route('absenceindex')->with('success', 'Votre demande d\'absence a été soumise avec succès.');
    }
}