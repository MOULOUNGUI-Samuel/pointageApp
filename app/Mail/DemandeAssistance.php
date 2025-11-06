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

class DemandeAssistance extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(public Demande_intervention $demande) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Demande d’assistance : ' . ($this->demande->titre ?? 'Nouvelle demande')
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.demande-assistance', // ← ta vue Blade HTML
            with: ['demande' => $this->demande]
        );
    }

    public function attachments(): array
    {
        if (empty($this->demande->piece_jointe_path)) {
            return [];
        }

        $disk = 'public';
        $path = $this->demande->piece_jointe_path;

        return [
            Attachment::fromStorageDisk($disk, $path)
                ->as(basename($path)),   // pas de ->withMime(...)
        ];
    }
}
