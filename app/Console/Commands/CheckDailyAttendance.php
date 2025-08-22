<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Services\AttendanceService;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;

class CheckDailyAttendance extends Command
{
    protected $signature = 'attendance:check-daily';
    protected $description = 'Vérifie la présence des utilisateurs pour la journée précédente et signale les absences injustifiées.';

    public function handle(AttendanceService $attendanceService): int
    {
        $this->info('Début de la vérification des présences pour la journée précédente...');

        $dateToCheck = Carbon::yesterday();
        
        // ADAPTATION : La variable s'appelle $users pour la cohérence.
        $users = User::where('is_active', true)->get(); // Adaptez cette requête si besoin

        if ($users->isEmpty()) {
            $this->info('Aucun utilisateur actif à vérifier. Tâche terminée.');
            return self::SUCCESS;
        }

        $this->info("Vérification pour le {$dateToCheck->toDateString()} pour {$users->count()} utilisateurs.");
        $unjustifiedAbsencesCount = 0;

        foreach ($users as $user) {
            // ADAPTATION : Appel de la méthode adaptée du service
            $status = $attendanceService->getUserStatusForDate($user, $dateToCheck);

            if ($status === 'absent_injustifie') {
                $unjustifiedAbsencesCount++;
                Log::warning("Absence injustifiée détectée pour l'utilisateur ID: {$user->id} ({$user->name}) à la date du {$dateToCheck->toDateString()}");
                $this->warn("Absence injustifiée détectée pour: {$user->name}");

                // Ici, vous pouvez ajouter l'envoi de notifications, etc.
            }
        }

        $this->info("Vérification terminée. {$unjustifiedAbsencesCount} absence(s) injustifiée(s) détectée(s).");
        return self::SUCCESS;
    }
}