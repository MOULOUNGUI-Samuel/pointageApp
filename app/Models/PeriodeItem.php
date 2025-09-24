<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
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
        'statut',
    ];
    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            $model->{$model->getKeyName()} = (string) Str::uuid();
        });
    }
    protected $casts = [
        'debut_periode' => 'date',
        'fin_periode'   => 'date',
    ];

    /** Période en cours (today ∈ [debut, fin]) ET statut=1 */
    public function getIsActiveAttribute(): bool
    {
        if ($this->statut !== '1') return false;
        $today = now()->startOfDay();
        return $this->debut_periode?->startOfDay() <= $today
            && $this->fin_periode?->endOfDay()   >= $today;
    }
    public function item()
    {
        return $this->belongsTo(Item::class);
    }
    public function user_add()
    {
        return $this->belongsTo(User::class, 'user_add_id');
    }

    public function entreprise()
    {
        return $this->belongsTo(Entreprise::class, 'entreprise_id');
    }
}
