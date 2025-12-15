<?php

namespace App\Enums;

enum ContractType: string
{
    case CDI = 'CDI';
    case CDD = 'CDD';
    case STAGE = 'Stage';
    case APPRENTISSAGE = 'Apprentissage';
    case INTERIM = 'Intérim';
    case FREELANCE = 'Freelance';
    case PRESTATION = 'Prestation';

    /**
     * Obtenir tous les types de contrats
     */
    public static function toArray(): array
    {
        return array_column(self::cases(), 'value');
    }

    /**
     * Obtenir les types de contrats pour un select
     */
    public static function forSelect(): array
    {
        return array_combine(
            array_column(self::cases(), 'value'),
            array_column(self::cases(), 'value')
        );
    }

    /**
     * Obtenir le label formaté
     */
    public function label(): string
    {
        return $this->value;
    }

    /**
     * Vérifier si le contrat a une date de fin obligatoire
     */
    public function requiresEndDate(): bool
    {
        return match($this) {
            self::CDD, self::STAGE, self::APPRENTISSAGE, self::INTERIM => true,
            self::CDI, self::FREELANCE, self::PRESTATION => false,
        };
    }
}
