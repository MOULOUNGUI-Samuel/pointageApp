<?php

namespace App\Console\Commands;

use App\Services\NotificationConformiteService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class EnvoyerRapportQuotidien extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'conformite:rapport-quotidien';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Envoie un rapport quotidien des soumissions en attente aux administrateurs';

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
        $this->info('📊 Génération du rapport quotidien...');

        try {
            $notifications = $this->notificationService->notifierRapportQuotidien();

            if ($notifications->count() > 0) {
                $this->info("✅ Rapport envoyé à {$notifications->count()} administrateur(s)");
                
                Log::info('Rapport quotidien conformité envoyé', [
                    'nb_admins' => $notifications->count(),
                ]);

                return Command::SUCCESS;
            } else {
                $this->info('ℹ️  Aucune soumission en attente, rapport non envoyé');
                return Command::SUCCESS;
            }

        } catch (\Exception $e) {
            $this->error('❌ Erreur lors de l\'envoi du rapport : ' . $e->getMessage());
            
            Log::error('Erreur envoi rapport quotidien conformité', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return Command::FAILURE;
        }
    }
}