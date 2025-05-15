<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Service extends Model
{
    protected $keyType = 'string';
    public $incrementing = false;
    protected $table = 'services';

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            $model->{$model->getKeyName()} = (string) Str::uuid();
        });
    }

    protected $fillable = [
        'entreprise_id',
        'nom_service',
        'description',
        'statut',
    ];

    public function entreprise()
    {
        return $this->belongsTo(Entreprise::class);
    }
}
