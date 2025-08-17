<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VariablePeriodeUser extends Model
{
    use HasUuids;

    protected $table = 'variable_periode_users';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'user_id',
        'periode_paie_id',
        'variable_id',
        'montant',
        'statut',
    ];

    protected $casts = [
        'montant' => 'decimal:2',
        'statut'  => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function periode(): BelongsTo
    {
        return $this->belongsTo(PeriodePaie::class, 'periode_paie_id');
    }

    public function variable(): BelongsTo
    {
        return $this->belongsTo(Variable::class);
    }
}
