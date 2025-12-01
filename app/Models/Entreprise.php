<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
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
    public function periodeItem()
    {
        return $this->hasMany(PeriodeItem::class);
    }
    public function demande_interventions()
    {
        return $this->hasMany(Demande_intervention::class);
    }
    public function domaines()
    {
        return $this->belongsToMany(Domaine::class, 'entreprise_domaines')
            ->withPivot('statut')->withTimestamps();
    }

    public function categories()
    {
        return $this->belongsToMany(
            \App\Models\CategorieDomaine::class,   // ton modèle tel quel
            'entreprise_categorie_domaines',        // table pivot
            'entreprise_id',                        // clé locale dans pivot
            'categorie_domaine_id'                  // clé “autre” dans pivot (⚠️ 1 seul m)
        )->withPivot('statut')->withTimestamps();
    }

    public function items()
    {
        return $this->belongsToMany(Item::class, 'entreprise_items')
            ->withPivot('statut')->withTimestamps();
    }
    /**
     * Relations
     */
    public function users(): HasMany
    {
        return $this->hasMany(User::class, 'entreprise_id');
    }


    public function periodeItems()
    {
        return $this->hasMany(PeriodeItem::class, 'entreprise_id');
    }

    // seulement celles avec statut = 1
    static function periodeItemsActives($structureId)
    {
        return PeriodeItem::where('statut', '1')->where('entreprise_id', $structureId)->whereDate('fin_periode', '>=', now());
    }
    // Soumissions au statut "soumis"
    static function soumissionsSoumises($structureId)
    {
        return ConformitySubmission::where('status', 'soumis')->where('entreprise_id', $structureId);
    }

    // Soumissions au statut "rejeté"
    static function soumissionsRejetees($structureId)
    {
        return ConformitySubmission::where('status', 'rejeté')->where('entreprise_id', $structureId);
    }
    public function notifications(): HasMany
    {
        return $this->hasMany(NotificationConformite::class, 'entreprise_id');
    }



    /**
     * Vérifie si l'entreprise a des domaines configurés
     */
    public function hasDomainesConfigures(): bool
    {
        return $this->domaines()->count() > 0;
    }

    /**
     * Récupère tous les items d'un domaine spécifique pour cette entreprise
     */
    public function itemsForDomaine($domaineId)
    {
        return $this->items()
            ->whereHas('categorie.domaine', function ($query) use ($domaineId) {
                $query->where('domaines.id', $domaineId);
            })
            ->get();
    }

    /**
     * Récupère toutes les catégories d'un domaine pour cette entreprise
     */
    public function categoriesForDomaine($domaineId)
    {
        return $this->categories()
            ->where('domaine_id', $domaineId)
            ->get();
    }
}
