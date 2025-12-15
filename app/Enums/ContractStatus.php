<?php

namespace App\Enums;

enum ContractStatus: string
{
    case BROUILLON = 'brouillon';
    case ACTIF = 'actif';
    case SUSPENDU = 'suspendu';
    case TERMINE = 'termine';
    case RESILIE = 'resilie';

    /**
     * Obtenir tous les statuts
     */
    public static function toArray(): array
    {
        return array_column(self::cases(), 'value');
    }

    /**
     * Obtenir les statuts pour un select
     */
    public static function forSelect(): array
    {
        return [
            self::BROUILLON->value => 'Brouillon',
            self::ACTIF->value => 'Actif',
            self::SUSPENDU->value => 'Suspendu',
            self::TERMINE->value => 'Terminé',
            self::RESILIE->value => 'Résilié',
        ];
    }

    /**
     * Obtenir le label formaté
     */
    public function label(): string
    {
        return match($this) {
            self::BROUILLON => 'Brouillon',
            self::ACTIF => 'Actif',
            self::SUSPENDU => 'Suspendu',
            self::TERMINE => 'Terminé',
            self::RESILIE => 'Résilié',
        };
    }

    /**
     * Obtenir la classe CSS pour le badge
     */
    public function badgeClass(): string
    {
        return match($this) {
            self::BROUILLON => 'badge-secondary',
            self::ACTIF => 'badge-success',
            self::SUSPENDU => 'badge-warning',
            self::TERMINE => 'badge-info',
            self::RESILIE => 'badge-danger',
        };
    }

    /**
     * Vérifier si le contrat peut être modifié
     */
    public function isEditable(): bool
    {
        return in_array($this, [self::BROUILLON, self::ACTIF, self::SUSPENDU]);
    }

    /**
     * Vérifier si le contrat peut être renouvelé
     */
    public function isRenewable(): bool
    {
        return in_array($this, [self::TERMINE, self::RESILIE]);
    }
}
