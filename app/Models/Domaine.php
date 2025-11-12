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
        'user_add_id',
        'user_update_id',
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
    public function user_add()
    {
        return $this->belongsTo(User::class, 'user_add_id');
    }
    public function user_update()
    {
        return $this->belongsTo(User::class, 'user_update_id');
    }
    public function categories()
    {
        return $this->hasMany(CategorieDomaine::class, 'domaine_id');
    }

    public function categorie_domaines()
    {
        return $this->belongsTo(CategorieDomaine::class);
    }


    public function entreprises()
    {
        return $this->belongsToMany(Entreprise::class, 'entreprise_domaines')
            ->withPivot('statut')
            ->withTimestamps();
    }
}
