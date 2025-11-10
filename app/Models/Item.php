<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Item extends Model
{
    use HasFactory;
    protected $table = 'items';
    protected $keyType = 'string';
    public $incrementing = false;
    protected $fillable = [
        'categorie_domaine_id',
        'user_add_id',
        'type',
        'user_update_id',
        'nom_item',
        'description',
        'statut',
    ];
    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            $model->{$model->getKeyName()} = (string) Str::uuid();
        });
    }
    public function CategorieDommaine(): BelongsTo
    {
        return $this->belongsTo(CategorieDommaine::class, 'categorie_domaine_id');
    }
    // Alias pour simplifier l'utilisation
    public function categorie()
    {
        return $this->CategorieDommaine();
    }
    public function entreprises()
    {
        return $this->belongsToMany(Entreprise::class, 'item_entreprises')
            ->withPivot('statut')
            ->withTimestamps();
    }
    public function periodeItems()
    {
        return $this->hasMany(PeriodeItem::class);
    }

    public function user_add()
    {
        return $this->belongsTo(User::class, 'user_add_id');
    }
    public function user_update()
    {
        return $this->belongsTo(User::class, 'user_update_id');
    }
    public function domaines()
    {
        return $this->belongsToMany(Domaine::class, 'categorie_item_entreprises')
            ->withPivot('statut')
            ->withTimestamps();
    }
    // app/Models/Item.php
    public function periodes(): HasMany
    {
        return $this->hasMany(PeriodeItem::class, 'item_id');
    }
    public function options(): HasMany
    {
        return $this->hasMany(ItemOption::class, 'item_id')->orderBy('position');
    }

    public function needsOptions(): bool
    {
        return in_array($this->type, ['liste', 'checkbox'], true);
    }
    public function periodeActive() // pratique pour l’affichage rapide
    {
        return $this->hasOne(PeriodeItem::class)
            ->where('statut', '1')
            ->whereDate('debut_periode', '<=', now())
            ->whereDate('fin_periode', '>=', now());
    }
    public function lastSubmission()
    {
        return $this->hasOne(ConformitySubmission::class, 'item_id')
            ->latestOfMany('submitted_at');
    }
    /** Dernière période (pour l’entreprise courante), toutes statuts confondus */
    public function lastPeriodeFor(?string $entrepriseId = null)
    {
        $entrepriseId ??= (string) session('entreprise_id');
        return $this->periodes()
            ->where('entreprise_id', $entrepriseId)
            ->orderByDesc('fin_periode')
            ->orderByDesc('created_at')
            ->first();
    }

    /** Accessor: $item->lastPeriode  */
    public function getLastPeriodeAttribute()
    {
        return $this->lastPeriodeFor();
    }

    /** Accessor: $item->periodeActive (strictement “statut=1” ET dates couvrent aujourd’hui) */
    public function getPeriodeActiveAttribute()
    {
        $entrepriseId = (string) session('entreprise_id');
        return $this->periodes()
            ->where('entreprise_id', $entrepriseId)
            ->where('statut', '1')
            ->whereDate('debut_periode', '<=', now())
            ->whereDate('fin_periode', '>=', now())
            ->latest('debut_periode')
            ->first();
    }

    /**
     * Accessor: $item->periode_state
     *  - 'active'   : statut=1 et aujourd’hui ∈ [début, fin]
     *  - 'expired'  : statut=1 mais fin < aujourd’hui
     *  - 'disabled' : statut != 1 (période clôturée manuellement)
     *  - 'none'     : aucune période
     *  (+ 'upcoming' si début > aujourd’hui et statut=1)
     */
    public function getPeriodeStateAttribute(): string
    {
        $p = $this->lastPeriode;
        if (!$p) return 'none';

        if ($p->statut !== '1') return 'disabled';

        $today = now()->startOfDay();
        $debut = \Illuminate\Support\Carbon::parse($p->debut_periode)->startOfDay();
        $fin   = \Illuminate\Support\Carbon::parse($p->fin_periode)->endOfDay();

        if ($today->betweenIncluded($debut, $fin)) return 'active';
        if ($today->lt($debut))                    return 'upcoming';
        return 'expired';
    }

    public function getLastSubmissionAttribute()
    {
        $entrepriseId = session('entreprise_id');
        if (!$entrepriseId) return null;

        return \App\Models\ConformitySubmission::where('item_id', $this->id)
            ->where('entreprise_id', $entrepriseId)
            ->latest('submitted_at')
            ->first();
    }
    public function submissions(): HasMany
    {
        return $this->hasMany(ConformitySubmission::class, 'item_id');
    }
}
