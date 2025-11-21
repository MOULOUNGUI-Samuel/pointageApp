<?php

namespace App\Mail;

use App\Models\PeriodeItem;
use App\Models\Item;
use App\Models\Entreprise;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PeriodeCanceledMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public PeriodeItem $periode,
        public Item $item,
        public Entreprise $entreprise,
        public User $user,
        public string $raison = ''
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '❌ Annulation de période - ' . $this->item->nom_item,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.conformite.periode-canceled',
            with: [
                'periode' => $this->periode,
                'item' => $this->item,
                'entreprise' => $this->entreprise,
                'user' => $this->user,
                'raison' => $this->raison,
            ],
        );
    }

    public function attachments(): array
    {
        return [];
    }
}