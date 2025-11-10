<?php

namespace App\Events;

use App\Models\NotificationConformite;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NotificationConformiteCreated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public NotificationConformite $notification;

    /**
     * Create a new event instance.
     */
    public function __construct(NotificationConformite $notification)
    {
        $this->notification = $notification;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        $channels = [];

        // Si notification pour un user spécifique
        if ($this->notification->user_id) {
            $channels[] = new PrivateChannel('user.' . $this->notification->user_id);
        }

        // Si notification pour toute l'entreprise (user_id null)
        if (!$this->notification->user_id && $this->notification->entreprise_id) {
            $channels[] = new PrivateChannel('entreprise.' . $this->notification->entreprise_id);
        }

        // Canal global pour les admins si c'est une notification admin
        if ($this->notification->isForAdmin()) {
            $channels[] = new PrivateChannel('admins.conformite');
        }

        return $channels;
    }

    /**
     * The event's broadcast name.
     */
    public function broadcastAs(): string
    {
        return 'notification.created';
    }

    /**
     * Get the data to broadcast.
     */
    public function broadcastWith(): array
    {
        return [
            'id' => $this->notification->id,
            'type' => $this->notification->type,
            'titre' => $this->notification->titre,
            'message' => $this->notification->message,
            'icone' => $this->notification->icone,
            'couleur' => $this->notification->couleur,
            'priorite' => $this->notification->priorite,
            'lien_action' => $this->notification->lien_action,
            'metadata' => $this->notification->metadata,
            'created_at' => $this->notification->created_at->toISOString(),
            'item' => $this->notification->item ? [
                'id' => $this->notification->item->id,
                'nom' => $this->notification->item->nom_item,
            ] : null,
            'entreprise' => $this->notification->entreprise ? [
                'id' => $this->notification->entreprise->id,
                'nom' => $this->notification->entreprise->nom_entreprise,
            ] : null,
        ];
    }
}