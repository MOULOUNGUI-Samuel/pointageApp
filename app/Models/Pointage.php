<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Pointage extends Model
{
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'user_id',
        'date_arriver',
        'date_fin',
        'heure_arriver',
        'heure_fin',
        'statut',
        'autorisation_absence',
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            $model->{$model->getKeyName()} = (string) Str::uuid();
        });
    }

    // Relations
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

}
