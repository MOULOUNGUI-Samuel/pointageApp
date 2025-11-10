<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class ItemOption extends Model
{
    use HasFactory;

    protected $table = 'item_options';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'item_id',
        'kind',
        'label',
        'value',
        'position',
        'statut',
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($m) {
            $m->{$m->getKeyName()} = (string) Str::uuid();
        });
    }

    public function item(): BelongsTo
    {
        return $this->belongsTo(Item::class, 'item_id');
    }
}
