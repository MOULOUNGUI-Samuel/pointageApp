<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Support\Str;

class ContractHistory extends Model
{
    use HasFactory, HasUuids;

    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'contract_id',
        'user_id',
        'entreprise_id',
        'action',
        'changes',
        'comment',
        'modified_by',
    ];

    protected $casts = [
        'changes' => 'array',
    ];

    /**
     * Relations
     */
    public function contract(): BelongsTo
    {
        return $this->belongsTo(Contract::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function entreprise(): BelongsTo
    {
        return $this->belongsTo(Entreprise::class);
    }

    public function modifiedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'modified_by');
    }

    /**
     * Accesseurs
     */
    public function getActionLabelAttribute(): string
    {
        return match($this->action) {
            'created' => 'Création',
            'updated' => 'Modification',
            'renewed' => 'Renouvellement',
            'suspended' => 'Suspension',
            'terminated' => 'Terminé',
            'reactivated' => 'Réactivation',
            'deleted' => 'Suppression',
            default => $this->action,
        };
    }

    public function getActionBadgeAttribute(): string
    {
        return match($this->action) {
            'created' => 'badge-success',
            'updated' => 'badge-info',
            'renewed' => 'badge-primary',
            'suspended' => 'badge-warning',
            'terminated' => 'badge-secondary',
            'reactivated' => 'badge-success',
            'deleted' => 'badge-danger',
            default => 'badge-secondary',
        };
    }

    /**
     * Méthodes statiques pour créer des historiques
     */
    public static function logCreation(Contract $contract, User $modifiedBy, ?string $comment = null): self
    {
        return self::create([
            'contract_id' => $contract->id,
            'user_id' => $contract->user_id,
            'entreprise_id' => $contract->entreprise_id,
            'action' => 'created',
            'changes' => [
                'after' => $contract->toArray(),
            ],
            'comment' => $comment,
            'modified_by' => $modifiedBy->id,
        ]);
    }

    public static function logUpdate(Contract $contract, array $originalData, User $modifiedBy, ?string $comment = null): self
    {
        $changes = [];
        foreach ($contract->getChanges() as $key => $newValue) {
            if (isset($originalData[$key]) && $originalData[$key] != $newValue) {
                $changes[$key] = [
                    'before' => $originalData[$key],
                    'after' => $newValue,
                ];
            }
        }

        return self::create([
            'contract_id' => $contract->id,
            'user_id' => $contract->user_id,
            'entreprise_id' => $contract->entreprise_id,
            'action' => 'updated',
            'changes' => $changes,
            'comment' => $comment,
            'modified_by' => $modifiedBy->id,
        ]);
    }

    public static function logRenewal(Contract $newContract, Contract $oldContract, User $modifiedBy, ?string $comment = null): self
    {
        return self::create([
            'contract_id' => $newContract->id,
            'user_id' => $newContract->user_id,
            'entreprise_id' => $newContract->entreprise_id,
            'action' => 'renewed',
            'changes' => [
                'old_contract_id' => $oldContract->id,
                'new_contract_id' => $newContract->id,
            ],
            'comment' => $comment ?? "Renouvellement du contrat #{$oldContract->id}",
            'modified_by' => $modifiedBy->id,
        ]);
    }

    public static function logStatusChange(Contract $contract, string $action, User $modifiedBy, ?string $comment = null): self
    {
        return self::create([
            'contract_id' => $contract->id,
            'user_id' => $contract->user_id,
            'entreprise_id' => $contract->entreprise_id,
            'action' => $action,
            'changes' => [
                'statut' => [
                    'before' => $contract->getOriginal('statut'),
                    'after' => $contract->statut,
                ],
            ],
            'comment' => $comment,
            'modified_by' => $modifiedBy->id,
        ]);
    }
}
