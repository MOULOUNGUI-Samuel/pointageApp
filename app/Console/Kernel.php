<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // Vérification des périodes de conformité
        // Exécuté tous les jours à 8h00
        $schedule->command('conformite:verifier-periodes')
            ->dailyAt('08:00')
            ->timezone('Africa/Libreville') // Ajuster selon votre timezone
            ->emailOutputOnFailure(config('mail.admin_email'));

        // Envoi du rapport quotidien aux admins
        // Exécuté tous les jours à 9h00
        $schedule->command('conformite:rapport-quotidien')
            ->dailyAt('09:00')
            ->timezone('Africa/Libreville')
            ->when(function () {
                // Ne pas envoyer le weekend
                return now()->isWeekday();
            });

        // Nettoyage des anciennes notifications (optionnel)
        // Exécuté toutes les semaines le dimanche à minuit
        $schedule->command('conformite:nettoyer-notifications')
            ->weeklyOn(0, '00:00')
            ->timezone('Africa/Libreville');

            // Exécute la commande tous les jours à une heure précise (ex: 2h du matin)
        $schedule->command('attendance:check-daily')->dailyAt('02:00');
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
