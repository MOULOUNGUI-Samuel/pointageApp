<?php
// app/Events/ServiceCreated.php
namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\PrivateChannel; // préférable, sinon Channel pour public
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;

class ServiceCreated implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(public string $nom, public int|string $entrepriseId) {}

    public function broadcastOn(): Channel
    {
        // Public pour démarrer facilement :
        return new Channel('entreprise.'.$this->entrepriseId);

        // (Plus tard, en privé :)
        // return new PrivateChannel('entreprise.'.$this->entrepriseId);
    }

    public function broadcastAs(): string
    {
        return 'service.created';
    }

    public function broadcastWith(): array
    {
        return ['nom' => $this->nom];
    }
}