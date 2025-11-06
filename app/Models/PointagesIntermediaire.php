<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class PointagesIntermediaire extends Model
{
    protected $table = 'pointages_intermediaires';

    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'pointage_id',
        'heure_sortie',
        'heure_entrer',
        'statut',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            // Génère un UUID seulement si non déjà défini
            if (!$model->getKey()) {
                $model->{$model->getKeyName()} = (string) Str::uuid();
            }
        });
    }

    // Relations
    public function pointage()
    {
        return $this->belongsTo(Pointage::class, 'pointage_id');
    }

}
