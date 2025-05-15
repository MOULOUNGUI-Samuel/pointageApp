<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
class CategorieProfessionnelle extends Model
{
    protected $table = 'categorie_professionnelles';
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
        'entreprise_id',
        'nom_categorie_professionnelle',
        'description',
        'statut',
    ];
    public function entreprise()
    {
        return $this->belongsTo(Entreprise::class);
    }
}