<?php

namespace App\Models;

use App\Enums\ContractStatus;
use App\Enums\ContractType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Support\Str;
use Carbon\Carbon;

class Contract extends Model
{
    use HasFactory, SoftDeletes, HasUuids;

    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'user_id',
        'entreprise_id',
        'type_contrat',
        'date_debut',
        'date_fin',
        'salaire_base',
        'mode_paiement',
        'avantages',
        'statut',
        'version',
        'parent_contract_id',
        'notes',
        'document_path',
        'fichier_joint',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'date_debut' => 'date',
        'date_fin' => 'date',
        'salaire_base' => 'decimal:2',
        'version' => 'integer',
    ];

    protected $appends = [
        'is_active',
        'is_expired',
        'jours_restants',
        'statut_badge',
    ];

    /**
     * Relations
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function entreprise(): BelongsTo
    {
        return $this->belongsTo(Entreprise::class);
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function parentContract(): BelongsTo
    {
        return $this->belongsTo(Contract::class, 'parent_contract_id');
    }

    public function renewedContracts(): HasMany
    {
        return $this->hasMany(Contract::class, 'parent_contract_id');
    }

    public function histories(): HasMany
    {
        return $this->hasMany(ContractHistory::class)->orderBy('created_at', 'desc');
    }

    /**
     * Scopes
     */
    public function scopeActif($query)
    {
        return $query->where('statut', ContractStatus::ACTIF->value);
    }

    public function scopeExpiringSoon($query, $days = 30)
    {
        return $query->where('statut', ContractStatus::ACTIF->value)
            ->whereNotNull('date_fin')
            ->whereBetween('date_fin', [now(), now()->addDays($days)]);
    }

    public function scopeExpired($query)
    {
        return $query->where('statut', ContractStatus::ACTIF->value)
            ->whereNotNull('date_fin')
            ->where('date_fin', '<', now());
    }

    public function scopeForEntreprise($query, $entrepriseId)
    {
        return $query->where('entreprise_id', $entrepriseId);
    }

    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId)->orderBy('date_debut', 'desc');
    }

    /**
     * Accesseurs
     */
    public function getIsActiveAttribute(): bool
    {
        return $this->statut === ContractStatus::ACTIF->value;
    }

    public function getIsExpiredAttribute(): bool
    {
        if (!$this->date_fin || $this->statut !== ContractStatus::ACTIF->value) {
            return false;
        }

        return $this->date_fin->isPast();
    }

    public function getJoursRestantsAttribute(): ?int
    {
        if (!$this->date_fin || $this->statut !== ContractStatus::ACTIF->value) {
            return null;
        }

        return now()->diffInDays($this->date_fin, false);
    }

    public function getStatutBadgeAttribute(): string
    {
        try {
            $status = ContractStatus::from($this->statut);
            return $status->badgeClass();
        } catch (\ValueError $e) {
            return 'badge-secondary';
        }
    }

    public function getStatutLabelAttribute(): string
    {
        try {
            $status = ContractStatus::from($this->statut);
            return $status->label();
        } catch (\ValueError $e) {
            return $this->statut;
        }
    }

    public function getTypeContratLabelAttribute(): string
    {
        try {
            $type = ContractType::from($this->type_contrat);
            return $type->label();
        } catch (\ValueError $e) {
            return $this->type_contrat;
        }
    }

    /**
     * Méthodes métier
     */
    public function estModifiable(): bool
    {
        try {
            $status = ContractStatus::from($this->statut);
            return $status->isEditable();
        } catch (\ValueError $e) {
            return false;
        }
    }

    public function estRenouvelable(): bool
    {
        // Un contrat est renouvelable s'il est terminé/résilié
        // OU s'il est actif et proche de sa date de fin
        if (in_array($this->statut, [ContractStatus::TERMINE->value, ContractStatus::RESILIE->value])) {
            return true;
        }

        if ($this->statut === ContractStatus::ACTIF->value && $this->date_fin) {
            // Renouvelable si moins de 60 jours avant la fin
            return $this->date_fin->diffInDays(now()) <= 60;
        }

        return false;
    }

    public function marquerCommeTermine(): void
    {
        $this->update(['statut' => ContractStatus::TERMINE->value]);
    }

    public function marquerCommeResilie(): void
    {
        $this->update(['statut' => ContractStatus::RESILIE->value]);
    }

    public function suspendre(): void
    {
        $this->update(['statut' => ContractStatus::SUSPENDU->value]);
    }

    public function reactiver(): void
    {
        $this->update(['statut' => ContractStatus::ACTIF->value]);
    }
}
