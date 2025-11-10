<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class CategorieDommaine extends Model
{
    use HasFactory;

    protected $table = 'categorie_domaines';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'nom_categorie',
        'code_categorie',
        'description',
        'statut',
        'user_add_id',
        'user_update_id',
        'domaine_id',     // ✅ nouveau
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            $model->{$model->getKeyName()} = (string) Str::uuid();
        });
    }

    // ✅ relations hiérarchiques

    public function user_add()
    {
        return $this->belongsTo(User::class, 'user_add_id');
    }
    public function user_update()
    {
        return $this->belongsTo(User::class, 'user_update_id');
    }


    public function domaine(): BelongsTo
    {
        return $this->belongsTo(Domaine::class, 'domaine_id');
    }

    public function items(): HasMany
    {
        return $this->hasMany(Item::class, 'categorie_domaine_id');
    }
}
