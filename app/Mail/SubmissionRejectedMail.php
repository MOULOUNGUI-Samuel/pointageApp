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

class SubmissionRejectedMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public ConformitySubmission $submission,
        public Item $item,
        public Entreprise $entreprise,
        public User $user,
        public ?User $reviewer = null
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '❌ Déclaration à corriger - ' . $this->item->nom_item,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.conformite.submission-rejected',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
