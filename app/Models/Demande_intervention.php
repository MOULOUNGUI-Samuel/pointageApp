<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Demande_intervention extends Model
{
   use HasFactory, SoftDeletes, HasUuids;

    protected $fillable = [
        'titre',
        'user_id',
        'entreprise_id',
        'description',
        'date_souhaite',
        'piece_jointe_path',
        'statut', // par défaut "en_attente"
    ];

    protected $casts = [
        'date_souhaite' => 'date',
    ];

    public function entreprise()
    {
        return $this->belongsTo(Entreprise::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
