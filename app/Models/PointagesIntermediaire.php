<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class PointagesIntermediaire extends Model
{
    protected $table = 'pointages_intermediaire';
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
            $model->{$model->getKeyName()} = (string) Str::uuid();
        });
    }

    // Relation
    public function pointage()
    {
        return $this->belongsTo(Pointage::class, 'pointage_id');
    }
}
