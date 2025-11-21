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

class PeriodeModifiedMail extends Mailable
{
    use Queueable, SerializesModels;

    public PeriodeItem $periode;
    public Item $item;
    public Entreprise $entreprise;
    public User $user;
    public array $changes;

    public function __construct(
        PeriodeItem $periode,
        Item $item,
        Entreprise $entreprise,
        User $user,
        array $changes = []
    ) {
        $this->periode = $periode;
        $this->item = $item;
        $this->entreprise = $entreprise;
        $this->user = $user;
        $this->changes = $changes;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '🔄 Modification de période - ' . $this->item->nom_item,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.conformite.periode-pdf',
            with: [
                'periode' => $this->periode,
                'item' => $this->item,
                'entreprise' => $this->entreprise,
                'user' => $this->user,
                'changes' => $this->changes,
                'dateDebut' => $this->periode->debut_periode->format('d/m/Y'),
                'dateFin' => $this->periode->fin_periode->format('d/m/Y'),
            ],
        );
    }

    public function attachments(): array
    {
        return [];
    }
}