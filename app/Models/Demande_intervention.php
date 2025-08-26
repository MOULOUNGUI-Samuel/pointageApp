<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Demande_intervention extends Model
{
    use HasFactory, SoftDeletes, HasUuids;

    protected $table = 'demande_interventions';

    protected $fillable = [
        'titre',
        'entreprise_id',
        'user_id',
        'description',
        'date_souhaite',
        'piece_jointe_path',
        'statut',
    ];

    protected $casts = [
        'date_souhaite' => 'date',
    ];
    public function scopeVisibleFor($q, $userId, $structureId)
    {
        // ( A ∨ B ) où
        // A = demandes appartenant à la structure courante
        // B = demandes créées par l'utilisateur connecté
        $q->where(function ($qq) use ($structureId, $userId) {
            $qq->ownedByStructure($structureId)
                ->orWhere('user_id', $userId);
        });
    }
    /** Optionnel : si tu veux un filtre "mes demandes uniquement" */
    public function scopeOwnedByUser($q, $userId)
    {
        return $q->where('user_id', $userId);
    }
    // ----- Relations
    public function entreprise()
    {
        return $this->belongsTo(Entreprise::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // ----- Scopes utiles
    public function scopeOwnedByStructure($q, $structureId)
    {
        return $q->whereHas('entreprise', fn($qq) => $qq->where('entreprise_id', $structureId));
    }
    public function scopeSearch($q, $term)
    {
        $t = trim((string)$term);
        if ($t === '') return $q;
        return $q->where(function ($qq) use ($t) {
            $qq->where('titre', 'like', "%{$t}%")
                ->orWhere('description', 'like', "%{$t}%")
                ->orWhereHas('entreprise', fn($q2) => $q2->where('nom', 'like', "%{$t}%")
                    ->orWhere('nom_entreprise', 'like', "%{$t}%"))
                ->orWhereHas('user', fn($q3) => $q3->where('name', 'like', "%{$t}%")
                    ->orWhere('username', 'like', "%{$t}%"));
        });
    }

    // ----- Attributs calculés
    public function getJoursRestantAttribute(): ?int
    {
        if (!$this->date_souhaite) return null;
        // diffInDays avec signe (négatif si déjà dépassé)
        return now()->startOfDay()->diffInDays($this->date_souhaite, false);
    }

    public function getEstTraiteeAttribute(): bool
    {
        return $this->statut === 'traitee';
    }

    public function getEstEnRetardAttribute(): bool
    {
        return !$this->est_traitee && isset($this->jours_restant) && $this->jours_restant < 0;
    }

    public function getStatutEffectifAttribute(): string
    {
        if ($this->est_traitee) return 'traitee';
        if ($this->est_en_retard) return 'en_retard'; // prime si dépassement
        return $this->statut ?: 'en_attente';
    }

    public function getDeadlineLabelAttribute(): ?string
    {
        if (!isset($this->jours_restant)) return null;
        $d = $this->jours_restant;
        if ($d > 1)  return "{$d} j restants";
        if ($d === 1) return "Demain";
        if ($d === 0) return "Aujourd'hui";
        if ($d === -1) return "Hier (retard)";
        return "En retard de " . abs($d) . " j";
    }
}
