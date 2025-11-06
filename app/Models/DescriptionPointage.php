<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class DescriptionPointage extends Model
{
    protected $table = 'description_pointages';

    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'pointage_intermediaire_id',
        'description',
        'statut',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (!$model->getKey()) {
                $model->{$model->getKeyName()} = (string) Str::uuid();
            }
        });
    }

    // Relation inverse
    public function pointageIntermediaire()
    {
        return $this->belongsTo(PointagesIntermediaire::class, 'pointage_intermediaire_id');
    }
}
