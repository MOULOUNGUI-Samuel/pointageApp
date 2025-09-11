<?php

// app/Http/Controllers/DemandeInterventionController.php
namespace App\Http\Controllers;

use App\Models\Demande_intervention;
use Illuminate\Http\Request;
use App\Mail\DemandeStatutMiseAJour;
use Illuminate\Validation\Rule;
use App\Mail\DemandeAssistance;
use Illuminate\Support\Facades\Mail;
use App\Models\DemandeInterventionNotification;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Services\Beams; 
use App\Notifications\NewAlert; // Import the NewAlert class
use Pusher\PushNotifications\PushNotifications; // Import the PushNotifications class
use Illuminate\Support\Facades\Notification; // Import the Notification facade
use App\Events\ServiceCreated; // Import the ServiceCreated event

class DemandeInterventionController extends Controller
{
    public function storeDemandeIntervention(Request $request)
    {
        $validated = $request->validate([
            'titre'         => ['required', 'string', 'max:255'],
            'entreprise_id' => ['required', 'uuid', Rule::exists('entreprises', 'id')],
            'description'   => ['nullable', 'string'],
            'date_souhaite' => ['required', 'date'],
            'piece_jointe'  => [
                'nullable',
                'file',
                'max:10240',
                'mimes:pdf,doc,docx,xls,xlsx,ppt,pptx,odt,ods,odp,rtf,jpg,jpeg,png,webp,gif'
            ],
        ]);

        // Upload PJ
        $pieceJointePath = null;
        if ($request->hasFile('piece_jointe')) {
            $file = $request->file('piece_jointe');
        
            // 1) Variante simple : juste enlever les espaces
            $base = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            $ext  = strtolower($file->getClientOriginalExtension());
            $safe = str_replace(' ', '_', $base) . '_' . now()->format('YmdHis') . '.' . $ext;
        
            // 2) Variante plus robuste (accents/signes -> slug)
            // $safe = Str::slug($base) . '_' . now()->format('YmdHis') . '.' . $ext;
        
            $pieceJointePath = $file->storeAs('demande_interventions', $safe, 'public');
        }

        // Création
        $demande = Demande_intervention::create([
            'titre'             => $validated['titre'],
            'entreprise_id'     => $validated['entreprise_id'],
            'user_id'           => Auth::id(),
            'description'       => $validated['description'] ?? null,
            'date_souhaite'     => $validated['date_souhaite'],
            'piece_jointe_path' => $pieceJointePath,
            'statut'            => 'en_attente',
        ]);

        // Relations pour l’email (logo etc.)
        $demande->loadMissing([
            'entreprise:id,nom_entreprise,logo',
            'user:id,nom,prenom,email,email_professionnel',
        ]);

        // Optionnel : tableau prêt pour JSON/vue
        $logoPath = $demande->entreprise->logo ?? null;

        $logoUrl = $logoPath
            ? (Str::startsWith($logoPath, ['http://','https://'])
                ? $logoPath
                : asset('storage/'.$logoPath))   // ← fonctionne avec le disk "public"
            : null;
            

        $demandeData = [
            'id'            => $demande->id,
            'titre'         => $demande->titre,
            'description'   => $demande->description,
            'date_souhaite' => optional($demande->date_souhaite)->format('Y-m-d'),
            'piece_jointe'  => $demande->piece_jointe_path,
            'statut'        => $demande->statut,
            'entreprise'    => [
                'id'       => $demande->entreprise_id,
                'nom'      => $demande->entreprise->nom_entreprise ?? null,
                'logo'     => $logoPath,
                'logo_url' => $logoUrl,
            ],
            'demandeur'     => [
                'id'     => $demande->user_id,
                'nom'    => $demande->user->nom ?? null,
                'prenom' => $demande->user->prenom ?? null,
                'email'  => $demande->user->email_professionnel ?? $demande->user->email,
            ],
        ];

        $title = 'Demande d\'intervention';
            $entreprise_id = $demande->entreprise_id;
            $body = "Une demande d'intervention a été envoyé par : "
                . ($demandeData['demandeur']['nom'] ? ($demandeData['demandeur']['nom'].' '.$demandeData['demandeur']['prenom']) : 'Utilisateur')
                . ". Elle a pour titre :  " .$demande->titre;
            $url  = url('/notifications');

            // IDs des destinataires = tous les users de l'entreprise SAUF l'auteur
            $uids = User::where('entreprise_id', $entreprise_id)->get();

            // Beams : publier vers les users (par batch si beaucoup d’IDs)
            foreach (array_chunk($uids, 1000) as $batch) {          // 1000 = limite confortable
                app(Beams::class)->publishToUsers(
                    $batch,
                    [
                        'title' => $title,
                        'body'  => $body,
                        'icon'  => asset('assets/img/authentication/logo_notif.JPG'),
                        'requireInteraction' => true,
                        'data'  => [
                            'url' => $url,
                            'type' => 'request_created',
                            'entreprise' => $entreprise_id,
                        ],
                    ],
                    $url // deep_link
                );
            }
            // B) TEMPS RÉEL IN-APP + HISTORIQUE
            $recipients = User::where('entreprise_id', $entreprise_id)
                ->get();
            Notification::send($recipients, new NewAlert($title, $body, $url));
            // Option : historiser aussi chez l’auteur
            $author = request()->user();
            $author?->notify(new NewAlert($title, $body, $url));
            $beams = new PushNotifications([
                'instanceId' => env('BEAMS_INSTANCE_ID'),
                'secretKey'  => env('BEAMS_SECRET_KEY'),
            ]);
            $res = $beams->publishToInterests(
                ['hello'], // intérêt sur lequel ton navigateur est abonné
                [
                    'web' => [
                        'notification' => [
                            'title' => $title,
                            'body'  => $body,
                            'icon'  => asset('assets/img/authentication/logo_notif.JPG'),
                            'deep_link' => url('/notifications'), // optionnel
                        ],
                    ],
                ]
            );

            // C) (Optionnel) événement broadcast "entreprise.{id}" si tu l’utilises pour d'autres listeners
            broadcast(new ServiceCreated($demande->titre, $entreprise_id));

        // ⚠️ ne PAS faire: $demande = $demandeData;

        // Destinataires (emails pro valides, uniques)
        $destinataires = User::where('entreprise_id', $validated['entreprise_id'])
            ->whereNotNull('email_professionnel')->where('email_professionnel', '!=', '')
            ->get(['id', 'email_professionnel', 'nom', 'prenom'])
            ->filter(fn($u) => filter_var($u->email_professionnel, FILTER_VALIDATE_EMAIL))
            ->unique('email_professionnel')->values();

        if ($destinataires->isEmpty()) {
            return back()->with('error', "Aucun destinataire valide trouvé pour l'entreprise.");
        }

        // Envoi + journalisation
        foreach ($destinataires as $user) {
            try {
                Mail::to($user->email_professionnel)->queue(new DemandeAssistance($demande)); // <- on passe le Model
                DemandeInterventionNotification::create([
                    'demande_intervention_id' => $demande->id,
                    'user_id'                 => $user->id,
                    'channel'                 => 'mail',
                    'mailable'                => \App\Mail\DemandeAssistance::class,
                    'status'                  => 'queued',
                ]);
            } catch (\Throwable $e) {
                report($e);
                DemandeInterventionNotification::create([
                    'demande_intervention_id' => $demande->id,
                    'user_id'                 => $user->id,
                    'channel'                 => 'mail',
                    'mailable'                => \App\Mail\DemandeAssistance::class,
                    'status'                  => 'failed',
                    'error'                   => $e->getMessage(),
                ]);
            }
        }

        return back()->with('success', 'Votre demande a été enregistrée avec succès.');
    }

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
