<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Categorie extends Model
{
    use HasUuids;

    protected $table = 'categories';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = ['nom_categorie', 'statut'];

    protected $casts = [
        'statut' => 'boolean',
    ];

    public function variables(): HasMany
    {
        return $this->hasMany(Variable::class);
    }
}
