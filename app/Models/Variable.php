<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Variable extends Model
{
    use HasUuids;

    protected $table = 'variables';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = ['categorie_id', 'nom_variable', 'type', 'statut'];

    protected $casts = [
        'statut' => 'boolean',
    ];

    public function categorie(): BelongsTo
    {
        return $this->belongsTo(Categorie::class);
    }

    public function lignes(): HasMany
    {
        return $this->hasMany(VariablePeriodeUser::class);
    }
}
