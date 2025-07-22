<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class CaisseAuthentification extends Model
{
    use HasFactory;
    protected $table = 'caisse_authentifications';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'user_id',
        'code_societe',
        'login',
        'mot_de_passe',
        'statut',
    ];
    

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            $model->{$model->getKeyName()} = (string) Str::uuid();
        });
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
