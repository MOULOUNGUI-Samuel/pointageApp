<?php

namespace App\Mail;

use App\Models\NotificationConformite;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class NotificationConformiteMail extends Mailable
{
    use Queueable, SerializesModels;

    public NotificationConformite $notification;
    public User $user;

    /**
     * Create a new message instance.
     */
    public function __construct(NotificationConformite $notification, User $user)
    {
        $this->notification = $notification;
        $this->user = $user;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: $this->notification->titre,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.notification-conformite',
            with: [
                'notification' => $this->notification,
                'user' => $this->user,
                'titre' => $this->notification->titre,
                'message' => $this->notification->message,
                'icone' => $this->notification->icone,
                'couleur' => $this->notification->couleur,
                'lienAction' => $this->notification->lien_action,
                'metadata' => $this->notification->metadata,
            ],
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}