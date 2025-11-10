<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Builder;   // ✅ le bon Builder Eloquent
use Illuminate\Support\Str;

class PeriodeItem extends Model
{
    use HasFactory;

    protected $table = 'periode_items';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'item_id',
        'user_add_id',
        'entreprise_id',
        'user_update_id',
        'debut_periode',
        'fin_periode',
        'statut', // '1' actif, '0' inactif/clos
    ];

    protected $casts = [
        'debut_periode' => 'date',
        'fin_periode'   => 'date',
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            $model->{$model->getKeyName()} = (string) Str::uuid();
        });
    }

    // ---------- Relations ----------
    public function user_add(): BelongsTo     { return $this->belongsTo(User::class, 'user_add_id'); }
    public function item(): BelongsTo         { return $this->belongsTo(Item::class, 'item_id'); }
    public function entreprise(): BelongsTo   { return $this->belongsTo(Entreprise::class, 'entreprise_id'); }
    public function soumissions(): HasMany    { return $this->hasMany(ConformitySubmission::class, 'periode_item_id'); }
    public function notifications(): HasMany  { return $this->hasMany(NotificationConformite::class, 'periode_item_id'); }

    // ---------- Accessors utiles ----------
    /** Indique si la période couvre aujourd’hui et statut = '1' */
    public function getIsActiveAttribute(): bool
    {
        if ($this->statut !== '1') return false;
        $today = now()->startOfDay();
        return $this->debut_periode?->startOfDay() <= $today
            && $this->fin_periode?->endOfDay()     >= $today;
    }

    // ---------- Scopes ----------
    /** Actives aujourd’hui (dates + statut) */
    public function scopeActive(Builder $q): Builder
    {
        return $q->where('statut', '1')
                 ->whereDate('debut_periode', '<=', now())
                 ->whereDate('fin_periode',   '>=', now());
    }

    /** Expirées (statut '1' mais fin passée) */
    public function scopeExpired(Builder $q): Builder
    {
        return $q->where('statut', '1')
                 ->whereDate('fin_periode', '<', now());
    }

    /** Filtre entreprise */
    public function scopeForEntreprise(Builder $q, string $entrepriseId): Builder
    {
        return $q->where('entreprise_id', $entrepriseId);
    }

    /** Filtre item */
    public function scopeForItem(Builder $q, string $itemId): Builder
    {
        return $q->where('item_id', $itemId);
    }

    // ---------- Helpers métier ----------
    public static function hasActive(string $itemId, string $entrepriseId): bool
    {
        return static::query()
            ->forItem($itemId)
            ->forEntreprise($entrepriseId)
            ->active()
            ->exists();
    }

    public function estActive(): bool
    {
        return $this->is_active; // alias accessor
    }

    public function estExpiree(): bool
    {
        return now()->gt($this->fin_periode);
    }

    public function joursRestants(): int
    {
        return now()->diffInDays($this->fin_periode, false);
    }
}
