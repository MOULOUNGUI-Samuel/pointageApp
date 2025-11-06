<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PeriodePaie extends Model
{
    use HasUuids;

    // Important: préciser le nom pour éviter "periode_paies"
    protected $table = 'periodes_paie';

    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = ['date_debut', 'date_fin', 'statut','ticket', 'entreprise_id'];

    protected $casts = [
        'date_debut' => 'date',
        'date_fin'   => 'date',
        'statut'     => 'boolean',
    ];

    public function entreprise(): BelongsTo
    {
        return $this->belongsTo(Entreprise::class);
    }

    public function lignes(): HasMany
    {
        return $this->hasMany(VariablePeriodeUser::class);
    }
}
