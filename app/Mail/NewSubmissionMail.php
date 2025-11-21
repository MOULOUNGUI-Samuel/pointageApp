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
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;

class NewSubmissionMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public ConformitySubmission $submission,
        public Item $item,
        public Entreprise $entreprise,
        public User $submitter,
        public User $admin
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '📩 Nouvelle soumission à valider - ' . $this->item->nom_item,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.conformite.new-submission',
        );
    }

    /**
     * Attacher les documents de la soumission à l'email
     */
    public function attachments(): array
    {
        $attachments = [];

        // Charger les réponses de la soumission
        $this->submission->load('answers');

        foreach ($this->submission->answers as $answer) {
            // Vérifier si c'est une réponse de type document et qu'elle a un fichier
            if ($answer->kind === 'documents' && $answer->file_path) {
                // Vérifier que le fichier existe
                if (Storage::disk('public')->exists($answer->file_path)) {
                    $attachments[] = Attachment::fromStorage('public/' . $answer->file_path)
                        ->as(basename($answer->file_path))
                        ->withMime($this->getMimeType($answer->file_path));
                } elseif (file_exists(storage_path('app/public/' . $answer->file_path))) {
                    // Fallback si le fichier est dans storage/app/public
                    $attachments[] = Attachment::fromPath(storage_path('app/public/' . $answer->file_path))
                        ->as(basename($answer->file_path))
                        ->withMime($this->getMimeType($answer->file_path));
                }
            }
        }

        return $attachments;
    }

    /**
     * Déterminer le type MIME du fichier
     */
    private function getMimeType(string $filePath): string
    {
        $extension = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));

        return match ($extension) {
            'pdf' => 'application/pdf',
            'jpg', 'jpeg' => 'image/jpeg',
            'png' => 'image/png',
            'gif' => 'image/gif',
            'doc' => 'application/msword',
            'docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            'xls' => 'application/vnd.ms-excel',
            'xlsx' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'zip' => 'application/zip',
            default => 'application/octet-stream',
        };
    }
}