<?php

namespace App\Listeners;

use App\Events\NotificationConformiteCreated;
use App\Mail\NotificationConformiteMail;
use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class EnvoyerEmailNotificationConformite implements ShouldQueue
{
    use InteractsWithQueue;

    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(NotificationConformiteCreated $event): void
    {
        $notification = $event->notification;

        try {
            // Si la notification est pour un user spécifique
            if ($notification->user_id) {
                $user = User::find($notification->user_id);
                
                if ($user && $user->email) {
                    Mail::to($user->email)->send(
                        new NotificationConformiteMail($notification, $user)
                    );
                    
                    $notification->marquerEmailEnvoye();
                }
            } 
            // Si la notification est pour toute l'entreprise
            else if ($notification->entreprise_id) {
                $users = User::where('entreprise_id', $notification->entreprise_id)
                    ->where('statut', 1)
                    ->whereNotNull('email')
                    ->get();

                foreach ($users as $user) {
                    Mail::to($user->email)->send(
                        new NotificationConformiteMail($notification, $user)
                    );
                }

                $notification->marquerEmailEnvoye();
            }

            Log::info('Email de notification envoyé', [
                'notification_id' => $notification->id,
                'type' => $notification->type,
            ]);

        } catch (\Exception $e) {
            Log::error('Erreur lors de l\'envoi de l\'email de notification', [
                'notification_id' => $notification->id,
                'error' => $e->getMessage(),
            ]);

            // Retry si échec
            $this->release(60); // Réessayer après 60 secondes
        }
    }

    /**
     * Determine whether the listener should be queued.
     */
    public function shouldQueue(NotificationConformiteCreated $event): bool
    {
        // Ne pas envoyer d'email pour les rapports quotidiens (déjà groupés)
        return $event->notification->type !== 'rapport_quotidien';
    }
}