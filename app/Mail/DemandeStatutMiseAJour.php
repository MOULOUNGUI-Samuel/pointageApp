<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Queue\SerializesModels;
use App\Models\Demande_intervention;

class DemandeStatutMiseAJour extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(public Demande_intervention $demande) {}

    /**
     * Sujet du mail
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Mise à jour de la demande : ' . ($this->demande->titre ?? 'Demande assistance')
        );
    }

    /**
     * Vue Blade utilisée
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.demande-statut',
            with: [
                'demande' => $this->demande,
            ]
        );
    }

    /**
     * Pièces jointes éventuelles
     */
    public function attachments(): array
    {
        return !empty($this->demande->piece_jointe_path)
            ? [
                Attachment::fromStorage($this->demande->piece_jointe_path)
                    ->as(basename($this->demande->piece_jointe_path))
              ]
            : [];
    }
}
