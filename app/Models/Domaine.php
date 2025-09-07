<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Domaine extends Model
{
    use HasFactory;
    protected $table = 'domaines';
    protected $keyType = 'string';
    public $incrementing = false;
    protected $fillable = [
        'entreprise_id',
        'nom_domaine',
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
    public function entreprises()
    {
        return $this->belongsToMany(Entreprise::class, 'domaine_item_entreprises')
            ->withPivot('statut')
            ->withTimestamps();
    }
    public function items()
    {
        return $this->belongsToMany(Item::class, 'domaine_item_entreprises')
            ->withPivot('statut')
            ->withTimestamps();
    }
    
}
