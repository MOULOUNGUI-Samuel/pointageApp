<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Entreprise extends Model
{
    protected $table = 'entreprises';
    protected $keyType = 'string';
    public $incrementing = false;

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            $model->{$model->getKeyName()} = (string) Str::uuid();
        });
    }

    protected $fillable = [
        'nom_entreprise',
        'heure_ouverture',
        'heure_fin',
        'code_entreprise',
        'logo',
        'heure_debut_pose',
        'heure_fin_pose',
        'latitude',
        'longitude',
        'rayon_autorise',
        'statut',
    ];
}
