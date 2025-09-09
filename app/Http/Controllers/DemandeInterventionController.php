<?php

// app/Http/Controllers/DemandeInterventionController.php
namespace App\Http\Controllers;

use App\Models\Demande_intervention;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Mail\DemandeStatutMiseAJour;
use Illuminate\Support\Facades\Mail;
use App\Models\DemandeInterventionNotification;

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

        if (in_array($demande->statut, ['traitee', 'annulee'])) {
            return response()->json([
                'success' => false,
                'message' => 'Cette demande est finalisée et ne peut plus être modifiée.'
            ], 409);
        }

        $demande->update(['statut' => $validated['statut']]);
        $demande->refresh();

        // 🔔 Envoi mail aux personnels de l’entreprise
        $destinataire = \App\Models\User::where('id', $demande->user_id)
            ->whereNotNull('email_professionnel')
            ->first();

        if ($destinataire) {
            Mail::to($destinataire->email_professionnel)->queue(new DemandeStatutMiseAJour($demande));

            try {

                \App\Models\DemandeInterventionNotification::create([
                    'demande_intervention_id' => $demande->id,
                    'user_id'                 => $destinataire->id,
                    'channel'                 => 'mail',
                    'mailable'                => \App\Mail\DemandeStatutMiseAJour::class,
                    'status'                  => 'queued',
                ]);
            } catch (\Throwable $e) {
                \App\Models\DemandeInterventionNotification::create([
                    'demande_intervention_id' => $demande->id,
                    'user_id'                 => $destinataire->id,
                    'channel'                 => 'mail',
                    'mailable'                => \App\Mail\DemandeStatutMiseAJour::class,
                    'status'                  => 'failed',
                    'error'                   => $e->getMessage(),
                ]);
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Statut mis à jour et notifications envoyées.',
            'demande' => [
                'id'               => $demande->id,
                'statut'           => $demande->statut,
                'statut_effectif'  => $demande->statut_effectif,
                'jours_restant'    => $demande->jours_restant,
                'deadline_label'   => $demande->deadline_label,
            ],
        ]);
    }
    public function showNotifications(Demande_intervention $demande)
    {
        $destinataires = $demande->destinataires()->with('entreprise')->get();

        $logs = DemandeInterventionNotification::with('user')
            ->where('demande_intervention_id', $demande->id)
            ->orderByDesc('created_at')
            ->get();

        return view('admin.demandes.notifications', compact('demande', 'destinataires', 'logs'));
    }
}
