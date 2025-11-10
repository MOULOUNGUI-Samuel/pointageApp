<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ConformitySubmission extends Model
{
    use HasUuids;

    // Statuts (facultatif mais recommandé)
    public const STATUS_SUBMITTED = 'soumis';
    public const STATUS_APPROVED  = 'approuvé';
    public const STATUS_REJECTED  = 'rejeté';

    protected $fillable = [
        'entreprise_id',
        'item_id',
        'periode_item_id',
        'submitted_by',
        'reviewed_by',
        'status',
        'submitted_at',
        'reviewed_at',
        'reviewer_notes',
    ];

    protected $casts = [
        'submitted_at' => 'datetime',
        'reviewed_at'  => 'datetime',
    ];

    // --- Relations ---
    public function entreprise(): BelongsTo { return $this->belongsTo(Entreprise::class, 'entreprise_id'); }
    public function item(): BelongsTo { return $this->belongsTo(Item::class, 'item_id'); }
    public function periodeItem(): BelongsTo { return $this->belongsTo(PeriodeItem::class, 'periode_item_id'); }
    public function submittedBy(): BelongsTo { return $this->belongsTo(User::class, 'submitted_by'); }
    public function reviewedBy(): BelongsTo { return $this->belongsTo(User::class, 'reviewed_by'); }
    public function answers(): HasMany { return $this->hasMany(ConformityAnswer::class, 'submission_id'); }
    public function notifications(): HasMany { return $this->hasMany(NotificationConformite::class, 'soumission_id'); }

    // --- Scopes ---
    public function scopeEnAttente($q)  { return $q->where('status', self::STATUS_SUBMITTED); }
    public function scopeApprouvees($q) { return $q->where('status', self::STATUS_APPROVED); }
    public function scopeRejetees($q)   { return $q->where('status', self::STATUS_REJECTED); }

    // --- Helpers de statut ---
    public function isFinal(): bool
    {
        return in_array($this->status, [self::STATUS_APPROVED, self::STATUS_REJECTED], true);
    }

    public function isPending(): bool
    {
        return $this->status === self::STATUS_SUBMITTED;
    }
}
