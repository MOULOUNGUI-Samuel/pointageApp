<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class CategorieDommaine extends Model
{
    use HasFactory;
    protected $table = 'categorie_domaines';
    protected $keyType = 'string';
    public $incrementing = false;
    protected $fillable = [
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
    public function items()
    {
        return $this->hasMany(Item::class);
    }
}
