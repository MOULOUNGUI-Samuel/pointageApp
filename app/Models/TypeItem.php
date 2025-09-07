<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class TypeItem extends Model
{
    use HasFactory;
    protected $table = 'type_items';
    protected $keyType = 'string';
    public $incrementing = false;
    protected $fillable = [
        'nom_type',
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
