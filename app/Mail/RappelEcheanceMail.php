<?php

namespace App\Mail;

use App\Models\ConformitySubmission;
use App\Models\PeriodeItem;
use App\Models\Item;
use App\Models\Entreprise;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class RappelEcheanceMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public PeriodeItem $periode,
        public Item $item,
        public Entreprise $entreprise,
        public User $user,
        public int $joursRestants,
        public string $urgence = 'normal'
    ) {}

    public function envelope(): Envelope
    {
        $emoji = match($this->urgence) {
            'critique' => '🚨',
            'urgent' => '⚠️',
            'important' => '⏰',
            default => '📌'
        };

        $subject = $this->joursRestants === 0
            ? $emoji . ' URGENT : Échéance dans 1 heure - ' . $this->item->nom_item
            : $emoji . ' Rappel : Échéance dans ' . $this->joursRestants . ' jour(s) - ' . $this->item->nom_item;

        return new Envelope(subject: $subject);
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.conformite.rappel-echeance',
            with: [
                'couleurUrgence' => match($this->urgence) {
                    'critique' => '#dc2626',
                    'urgent' => '#ea580c',
                    'important' => '#f59e0b',
                    default => '#3b82f6'
                },
            ],
        );
    }

    public function attachments(): array
    {
        return [];
    }
}