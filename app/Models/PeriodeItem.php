<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class PeriodeItem extends Model
{
    use HasFactory;
    protected $table = 'periode_items';
    protected $keyType = 'string';
    public $incrementing = false;
    protected $fillable = [
        'item_id',
        'debut_periode',
        'fin_periode',
        'statut',
    ];
    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            $model->{$model->getKeyName()} = (string) Str::uuid();
        });
    }
    public function item()
    {
        return $this->belongsTo(Item::class);
    }
}
