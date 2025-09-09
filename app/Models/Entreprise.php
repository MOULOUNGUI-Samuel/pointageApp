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
        'minute_pointage_limite',
        'latitude',
        'longitude',
        'rayon_autorise',
        'statut',
    ];

    public function services()
    {
        return $this->hasMany(Service::class);
    }
    public function categorieProfessionels()
    {
        return $this->hasMany(CategorieProfessionnelle::class);
    }
    public function employes()
    {
        return $this->hasMany(User::class);
    }
    public function demande_interventions()
    {
        return $this->hasMany(Demande_intervention::class);
    }
    public function items()
    {
        return $this->belongsToMany(Item::class, 'categorie_item_entreprises')
            ->withPivot('statut')
            ->withTimestamps();
    }
    public function domaines()
    {
        return $this->belongsToMany(Domaine::class, 'categorie_item_entreprises')
            ->withPivot('statut')
            ->withTimestamps();
    }
    public function evaluationEntreprises()
    {
        return $this->hasMany(EvaluationEntreprise::class);
    }
}
