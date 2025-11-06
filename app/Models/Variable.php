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

    protected $fillable = ['categorie_id', 'nom_variable', 
    'type',
    'statutVariable',
    'tauxVariable',
    'tauxVariableEntreprise',
    'variableImposable',
    'numeroVariable',
     'statut'];

     protected $casts = [
<<<<<<< HEAD
        'statut'          => 'boolean',
=======
        'statut'            => 'boolean',
>>>>>>> 2051fda2857123fbe9a12379eed1c410c7e5bfce
        'statutVariable'    => 'boolean',
        'variableImposable' => 'boolean',
        'tauxVariable'      => 'decimal:2', // Laravel cast en string formattée; OK pour l’API
        'tauxVariableEntreprise'      => 'decimal:2', // Laravel cast en string formattée; OK pour l’API
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
