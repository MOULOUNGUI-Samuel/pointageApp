<?php

namespace App\Services;

use App\Models\PeriodeItem;

class PeriodeItemChecker
{
    /**
     * Retourne la période de validité active (statut = '1' et dates OK)
     * pour un item + entreprise, ou null s’il n’y en a pas.
     */
    public static function getActivePeriod(string $itemId, string $entrepriseId): ?PeriodeItem
    {
        return PeriodeItem::query()
            ->forItem($itemId)         // scope forItem($q, $itemId)
            ->forEntreprise($entrepriseId) // scope forEntreprise($q, $entrepriseId)
            ->where('statut', '1')                // scope active() : statut = '1' + dates
            ->orderByDesc('debut_periode')
            ->first();
    }

    /**
     * Vérifie simplement si l’item a une période de validité active (statut = '1').
     */
    public static function hasActivePeriod(string $itemId, string $entrepriseId): bool
    {
        return PeriodeItem::query()
            ->forItem($itemId)
            ->forEntreprise($entrepriseId)
            ->where('statut', '1')
            ->exists();
    }

    /**
     * Vérifie s’il existe une *nouvelle* période active
     * différente de la période actuelle (par ex. pour déclencher une notif).
     *
     * @param string|null $currentPeriodeItemId  id de la période déjà connue (ou null si aucune)
     */
    public static function hasNewActivePeriod(string $itemId, string $entrepriseId, ?string $currentPeriodeItemId): bool
    {
        $active = self::getActivePeriod($itemId, $entrepriseId);

        if (! $active) {
            return false; // aucune période active
        }

        // "nouvelle" = l'id de la période active est différent de celle qu'on connaît déjà
        return $currentPeriodeItemId === null || $active->id !== $currentPeriodeItemId;
    }
}
