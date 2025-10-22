<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Item extends Model
{
    use HasFactory;
    protected $table = 'items';
    protected $keyType = 'string';
    public $incrementing = false;
    protected $fillable = [
        'categorie_domaine_id',
        'user_add_id',
        'type',
        'user_update_id',
        'nom_item',
        'description',
        'statut',
    ];
    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            $model->{$model->getKeyName()} = (string) Str::uuid();
        });
    }
    public function CategorieDommaine()
    {
        return $this->belongsTo(CategorieDommaine::class, 'categorie_domaine_id');
    }
    // Alias pour simplifier l'utilisation
    public function categorie()
    {
        return $this->CategorieDommaine();
    }
    public function entreprises()
    {
        return $this->belongsToMany(Entreprise::class, 'item_entreprises')
            ->withPivot('statut')
            ->withTimestamps();
    }
    public function periodeItems()
    {
        return $this->hasMany(PeriodeItem::class);
    }

    public function user_add()
    {
        return $this->belongsTo(User::class, 'user_add_id');
    }
    public function user_update()
    {
        return $this->belongsTo(User::class, 'user_update_id');
    }
    public function domaines()
    {
        return $this->belongsToMany(Domaine::class, 'categorie_item_entreprises')
            ->withPivot('statut')
            ->withTimestamps();
    }
    // app/Models/Item.php
    public function periodes()
    {
        return $this->hasMany(PeriodeItem::class, 'item_id');
    }
    public function options()
    {
        return $this->hasMany(ItemOption::class, 'item_id')->orderBy('position');
    }

    public function needsOptions(): bool
    {
        return in_array($this->type, ['liste', 'checkbox'], true);
    }
    public function periodeActive() // pratique pour l’affichage rapide
    {
        return $this->hasOne(PeriodeItem::class)
            ->where('statut', '1')
            ->whereDate('debut_periode', '<=', now())
            ->whereDate('fin_periode', '>=', now());
    }
    public function lastSubmission()
    {
        return $this->hasOne(\App\Models\ConformitySubmission::class)
            ->latestOfMany(); // dernière soumission
    }
}
