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
        'categorie_item_id',
        'type_item_id',
        'user_add_id',
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
    public function categorieDomaine()
    {
        return $this->belongsTo(CategorieDommaine::class);
    }
    public function typeItem()
    {
        return $this->belongsTo(TypeItem::class);
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
    public function evaluationEntreprises()
    {
        return $this->hasMany(EvaluationEntreprise::class);
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
        return $this->belongsToMany(Domaine::class, 'domaine_item_entreprises')
            ->withPivot('statut')
            ->withTimestamps();
    }
}
