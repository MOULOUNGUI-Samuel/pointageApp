<?php

namespace App\Console\Commands;

use App\Services\NotificationConformiteService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class NettoyerNotifications extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'conformite:nettoyer-notifications {--jours=90 : Nombre de jours à conserver}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Supprime les anciennes notifications lues pour libérer de l\'espace';

    protected NotificationConformiteService $notificationService;

    /**
     * Create a new command instance.
     */
    public function __construct(NotificationConformiteService $notificationService)
    {
        parent::__construct();
        $this->notificationService = $notificationService;
    }

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $joursGarde = (int) $this->option('jours');

        $this->info("🧹 Nettoyage des notifications lues de plus de {$joursGarde} jours...");

        if ($this->confirm('Êtes-vous sûr de vouloir supprimer ces notifications ?', true)) {
            try {
                $deleted = $this->notificationService->supprimerAnciennesNotifications($joursGarde);

                $this->info("✅ {$deleted} notification(s) supprimée(s)");
                
                Log::info('Nettoyage notifications conformité', [
                    'jours_garde' => $joursGarde,
                    'nb_supprimees' => $deleted,
                ]);

                return Command::SUCCESS;

            } catch (\Exception $e) {
                $this->error('❌ Erreur lors du nettoyage : ' . $e->getMessage());
                
                Log::error('Erreur nettoyage notifications conformité', [
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString(),
                ]);

                return Command::FAILURE;
            }
        } else {
            $this->info('Opération annulée');
            return Command::SUCCESS;
        }
    }
}