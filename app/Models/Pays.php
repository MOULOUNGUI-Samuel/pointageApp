<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Pays extends Model
{
    protected $keyType = 'string';
    public $incrementing = false;
    protected $table = 'pays';

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            $model->{$model->getKeyName()} = (string) Str::uuid();
        });
    }

    protected $fillable = [
        'nom_pays',
        'code_pays',
        'description',
        'statut',
    ];
}
