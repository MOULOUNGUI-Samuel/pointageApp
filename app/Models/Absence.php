<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Absence extends Model
{
    use HasFactory, HasUuids;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'type',
        'status',
        'start_datetime',
        'end_datetime',
        'reason',
        'attachment_path',          // <-- NEW (pièce jointe de la demande)
        'approved_by',
        'approved_at',
        'justification',

        // Retour d'absence
        'return_confirmed_at',      // <-- NEW
        'returned_on_time',         // <-- NEW (bool)
        'return_notes',             // <-- NEW (si retard/non respect)
        'return_attachment_path',   // <-- NEW (pièce jointe retour)
    ];

    protected $casts = [
        'start_datetime'       => 'datetime',
        'end_datetime'         => 'datetime',
        'approved_at'          => 'datetime',
        'return_confirmed_at'  => 'datetime',
        'returned_on_time'     => 'boolean',
    ];

    /**
     * ADAPTATION : La relation est renommée 'user' pour la clarté.
     * Obtient l'utilisateur (employé) qui a soumis la demande d'absence.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Obtient le manager/admin qui a approuvé la demande.
     */
    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }
    public function scopeMine($query)
    {
        return $query->where('user_id', auth()->id());
    }
}
