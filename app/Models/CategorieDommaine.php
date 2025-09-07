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
        'nom_categorie',
        'user_add_id',
        'user_update_id',
        'code_categorie',
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
    public function items()
    {
        return $this->hasMany(Item::class);
    }
}
