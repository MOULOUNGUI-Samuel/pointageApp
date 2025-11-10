<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class NotificationConformite extends Model
{
    use HasUuids;

    protected $table = 'notifications_conformite';

    protected $fillable = [
        'entreprise_id',
        'user_id',
        'type',
        'titre',
        'message',
        'item_id',
        'periode_item_id',
        'soumission_id',
        'domaine_id',
        'categorie_domaine_id',
        'metadata',
        'lue',
        'lue_le',
        'email_envoye',
        'email_envoye_le',
        'priorite',
    ];

    protected $casts = [
        'metadata' => 'array',
        'lue' => 'boolean',
        'email_envoye' => 'boolean',
        'lue_le' => 'datetime',
        'email_envoye_le' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Constantes pour les types de notifications
     */
    const TYPE_NOUVELLE_PERIODE = 'nouvelle_periode';
    const TYPE_VALIDATION = 'validation';
    const TYPE_REFUS = 'refus';
    const TYPE_RAPPEL_ECHEANCE = 'rappel_echeance';
    const TYPE_PERIODE_EXPIREE = 'periode_expiree';
    const TYPE_NOUVELLE_SOUMISSION = 'nouvelle_soumission';
    const TYPE_RESOUMISSION = 'resoumission';
    const TYPE_RAPPORT_QUOTIDIEN = 'rapport_quotidien';

    /**
     * Constantes pour les priorités
     */
    const PRIORITE_BASSE = 'basse';
    const PRIORITE_NORMALE = 'normale';
    const PRIORITE_HAUTE = 'haute';
    const PRIORITE_URGENTE = 'urgente';

    /**
     * Relations
     */
    public function entreprise(): BelongsTo
    {
        return $this->belongsTo(Entreprise::class, 'entreprise_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function item(): BelongsTo
    {
        return $this->belongsTo(Item::class, 'item_id');
    }

    public function periodeItem(): BelongsTo
    {
        return $this->belongsTo(PeriodeItem::class, 'periode_item_id');
    }

    public function soumission(): BelongsTo
    {
        return $this->belongsTo(ConformitySubmission::class, 'soumission_id');
    }

    public function domaine(): BelongsTo
    {
        return $this->belongsTo(Domaine::class, 'domaine_id');
    }

    public function categorieDomaine(): BelongsTo
    {
        return $this->belongsTo(CategorieDommaine::class, 'categorie_domaine_id');
    }

    /**
     * Scopes
     */
    public function scopeNonLues($query)
    {
        return $query->where('lue', false);
    }

    public function scopeLues($query)
    {
        return $query->where('lue', true);
    }

    public function scopePourEntreprise($query, $entrepriseId)
    {
        return $query->where('entreprise_id', $entrepriseId);
    }

    public function scopePourUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function scopeRecentes($query, $jours = 30)
    {
        return $query->where('created_at', '>=', now()->subDays($jours));
    }

    public function scopeParType($query, $type)
    {
        return $query->where('type', $type);
    }

    public function scopeParPriorite($query, $priorite)
    {
        return $query->where('priorite', $priorite);
    }

    public function scopeEmailNonEnvoye($query)
    {
        return $query->where('email_envoye', false);
    }

    /**
     * Méthodes utilitaires
     */
    public function marquerCommeLue(): bool
    {
        return $this->update([
            'lue' => true,
            'lue_le' => now(),
        ]);
    }

    public function marquerEmailEnvoye(): bool
    {
        return $this->update([
            'email_envoye' => true,
            'email_envoye_le' => now(),
        ]);
    }

    /**
     * Obtenir l'icône selon le type
     */
    public function getIconeAttribute(): string
    {
        return match($this->type) {
            self::TYPE_NOUVELLE_PERIODE => '📋',
            self::TYPE_VALIDATION => '✅',
            self::TYPE_REFUS => '❌',
            self::TYPE_RAPPEL_ECHEANCE => '⚠️',
            self::TYPE_PERIODE_EXPIREE => '⏰',
            self::TYPE_NOUVELLE_SOUMISSION => '📩',
            self::TYPE_RESOUMISSION => '🔄',
            self::TYPE_RAPPORT_QUOTIDIEN => '📊',
            default => '🔔',
        };
    }

    /**
     * Obtenir la couleur selon le type/priorité
     */
    public function getCouleurAttribute(): string
    {
        if ($this->priorite === self::PRIORITE_URGENTE) {
            return 'red';
        }
        
        if ($this->priorite === self::PRIORITE_HAUTE) {
            return 'orange';
        }

        return match($this->type) {
            self::TYPE_VALIDATION => 'green',
            self::TYPE_REFUS => 'red',
            self::TYPE_RAPPEL_ECHEANCE => 'yellow',
            self::TYPE_PERIODE_EXPIREE => 'red',
            self::TYPE_NOUVELLE_SOUMISSION => 'blue',
            self::TYPE_RESOUMISSION => 'purple',
            default => 'gray',
        };
    }

    /**
     * Obtenir le lien d'action
     */
    public function getLienActionAttribute(): ?string
    {
        return match($this->type) {
            self::TYPE_NOUVELLE_PERIODE, 
            self::TYPE_RAPPEL_ECHEANCE => $this->item_id 
                ? route('entreprise.items.formulaire', $this->item_id)
                : null,
            
            self::TYPE_VALIDATION, 
            self::TYPE_REFUS => $this->soumission_id 
                ? route('entreprise.soumissions.detail', $this->soumission_id)
                : null,
            
            self::TYPE_NOUVELLE_SOUMISSION, 
            self::TYPE_RESOUMISSION => $this->soumission_id 
                ? route('admin.validations.kanban', ['soumission' => $this->soumission_id])
                : route('admin.validations.kanban'),
            
            self::TYPE_RAPPORT_QUOTIDIEN => route('admin.validations.kanban'),
            
            default => null,
        };
    }

    /**
     * Vérifier si la notification est pour un admin
     */
    public function isForAdmin(): bool
    {
        return in_array($this->type, [
            self::TYPE_NOUVELLE_SOUMISSION,
            self::TYPE_RESOUMISSION,
            self::TYPE_RAPPORT_QUOTIDIEN,
        ]);
    }

    /**
     * Vérifier si la notification est pour une entreprise
     */
    public function isForEntreprise(): bool
    {
        return in_array($this->type, [
            self::TYPE_NOUVELLE_PERIODE,
            self::TYPE_VALIDATION,
            self::TYPE_REFUS,
            self::TYPE_RAPPEL_ECHEANCE,
            self::TYPE_PERIODE_EXPIREE,
        ]);
    }
}