<?php

// app/Http/Controllers/DemandeInterventionController.php
namespace App\Http\Controllers;

use App\Models\Demande_intervention;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class DemandeInterventionController extends Controller
{
    // public function index(Request $request)
    // {
    //     $structureId = session('entreprise_id');
    //     abort_unless($structureId, 403, 'Structure non sélectionnée.');
    //     $search = $request->input('q');
    //     $filtreStatut = $request->input('statut'); // ex: en_attente, en_cours, traitee, annulee, en_retard

    //     $query = Demande_intervention::with([
    //             'entreprise:id,nom,nom_entreprise,entreprise_id',
    //             'user:id,name,username',
    //         ])
    //         ->ownedByStructure($structureId);

    //     if ($filtreStatut) {
    //         if ($filtreStatut === 'en_retard') {
    //             $query->where('statut', '!=', 'traitee')
    //                   ->whereDate('date_souhaite', '<', now()->toDateString());
    //         } else {
    //             $query->where('statut', $filtreStatut);
    //         }
    //     }

    //     if ($search) $query->search($search);

    //     $demandes = $query->orderByDesc('created_at')->paginate(20)->withQueryString();

    //     return view('demande_interventions.index', compact('demandes', 'search', 'filtreStatut'));
    // }

    public function updateStatus(Request $request, Demande_intervention $demande)
    {
        $validated = $request->validate([
            'statut' => ['required', Rule::in(['en_attente', 'en_cours', 'traitee', 'annulee'])],
        ]);
        // DemandeInterventionController@updateStatus
        if (in_array($demande->statut, ['traitee', 'annulee'])) {
            return response()->json([
                'success' => false,
                'message' => 'Cette demande est finalisée et ne peut plus être modifiée.'
            ], 409);
        }
        $demande->update(['statut' => $validated['statut']]);

        $demande->refresh(); // recalcul des attributs virtuels en vue/JSON

        return response()->json([
            'success' => true,
            'message' => 'Statut mis à jour.',
            'demande' => [
                'id'               => $demande->id,
                'statut'           => $demande->statut,
                'statut_effectif'  => $demande->statut_effectif,
                'jours_restant'    => $demande->jours_restant,
                'deadline_label'   => $demande->deadline_label,
            ],
        ]);
    }
}
