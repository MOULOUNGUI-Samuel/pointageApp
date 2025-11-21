<?php

namespace App\Mail;

use App\Models\ConformitySubmission;
use App\Models\Item;
use App\Models\Entreprise;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Queue\SerializesModels;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Log;


class SubmissionApprovedMail extends Mailable
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
            subject: '✅ Déclaration approuvée - ' . $this->item->nom_item,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.conformite.submission-approved',
            with: [
                'submission' => $this->submission,
                'item' => $this->item,
                'entreprise' => $this->entreprise,
                'user' => $this->user,
                'reviewer' => $this->reviewer,
                'reviewerName' => $this->reviewer 
                    ? $this->reviewer->nom . ' ' . $this->reviewer->prenom
                    : 'L\'équipe de validation',
            ],
        );
    }

    public function attachments(): array
    {
        try {
            $pdf = Pdf::loadView('emails.conformite.submission-approved-pdf', [
                'submission' => $this->submission,
                'item' => $this->item,
                'entreprise' => $this->entreprise,
                'reviewer' => $this->reviewer,
            ]);

            return [
                Attachment::fromData(
                    fn () => $pdf->output(), 
                    'validation-' . $this->item->nom_item . '.pdf'
                )->withMime('application/pdf'),
            ];
        } catch (\Exception $e) {
            Log::error('Erreur génération PDF approbation', ['error' => $e->getMessage()]);
            return [];
        }
    }
}