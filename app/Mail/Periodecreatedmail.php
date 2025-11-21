<?php

namespace App\Mail;

use App\Models\PeriodeItem;
use Illuminate\Support\Facades\Log;
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
    

class PeriodeCreatedMail extends Mailable
{
    use Queueable, SerializesModels;

    public PeriodeItem $periode;
    public Item $item;
    public Entreprise $entreprise;
    public User $user;

    public function __construct(
        PeriodeItem $periode,
        Item $item,
        Entreprise $entreprise,
        User $user
    ) {
        $this->periode = $periode;
        $this->item = $item;
        $this->entreprise = $entreprise;
        $this->user = $user;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '📅 Nouvelle période de conformité - ' . $this->item->nom_item,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.conformite.periode-created',
            with: [
                'periode' => $this->periode,
                'item' => $this->item,
                'entreprise' => $this->entreprise,
                'user' => $this->user,
                'dateDebut' => $this->periode->debut_periode->format('d/m/Y'),
                'dateFin' => $this->periode->fin_periode->format('d/m/Y'),
                'joursRestants' => now()->diffInDays($this->periode->fin_periode, false),
            ],
        );
    }

   public function attachments(): array
{
    try {
        // Générer un PDF avec les détails de la période
        $pdf = Pdf::loadView('emails.conformite.periode-pdf', [
            'periode' => $this->periode,
            'item' => $this->item,
            'entreprise' => $this->entreprise,
            'user' => $this->user, // 🔥 AJOUT OBLIGATOIRE
            'dateDebut' => $this->periode->debut_periode->format('d/m/Y'),
            'dateFin' => $this->periode->fin_periode->format('d/m/Y'),
            'joursRestants' => now()->diffInDays($this->periode->fin_periode, false),
        ]);

        return [
            Attachment::fromData(fn() => $pdf->output(), 'periode-' . $this->item->nom_item . '.pdf')
                ->withMime('application/pdf'),
        ];
    } catch (\Exception $e) {
        Log::error('Erreur génération PDF période', ['error' => $e->getMessage()]);
        return [];
    }
}

}
