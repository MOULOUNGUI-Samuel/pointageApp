<?php

namespace App\Services;

use App\Models\User;
use App\Models\Absence;
use App\Models\Pointage; // IMPORTANT: Assurez-vous d'avoir ce modèle
use Carbon\Carbon;
use Yasumi\Yasumi;

class AttendanceService
{
    /**
     * Vérifie le statut d'un utilisateur pour un jour donné.
     *
     * @param User $user
     * @param Carbon $date
     * @return string Statut : 'présent', 'weekend', 'jour_ferie', 'absence_approuvee', 'absent_injustifie'
     */
    public function getUserStatusForDate(User $user, Carbon $date): string
    {
        // 1. Week-end
        if ($date->isWeekend()) {
            return 'weekend';
        }

        // 2. Jour férié
        // Note: Pensez à rendre le pays configurable si nécessaire
        $holidays = Yasumi::create('France', $date->year);
        if ($holidays->isHoliday($date)) {
            return 'jour_ferie';
        }

        // 3. Absence approuvée
        $isApprovedAbsence = Absence::where('user_id', $user->id)
            ->where('status', 'approuvé')
            ->whereDate('start_datetime', '<=', $date)
            ->whereDate('end_datetime', '>=', $date)
            ->exists();

        if ($isApprovedAbsence) {
            return 'absence_approuvee';
        }

        // 4. ADAPTATION PRINCIPALE : Vérification dans votre table 'pointages'
        $hasClockingRecord = Pointage::where('user_id', $user->id)
            ->whereDate('date_arriver', $date)
            ->exists();

        if ($hasClockingRecord) {
            return 'présent';
        }

        // 5. Si rien d'autre, absent injustifié
        return 'absent_injustifie';
    }
}