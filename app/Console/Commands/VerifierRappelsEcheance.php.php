<?php

namespace App\Console\Commands;

use App\Services\EmailConformiteService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class VerifierRappelsEcheance extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'conformite:verifier-rappels
                          {--force : Forcer l\'envoi des rappels même si déjà envoyés aujourd\'hui}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Vérifie les périodes et envoie les rappels d\'échéance (7j, 3j, 1j, 1h)';

    protected EmailConformiteService $emailService;

    /**
     * Create a new command instance.
     */
    public function __construct(EmailConformiteService $emailService)
    {
        parent::__construct();
        $this->emailService = $emailService;
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('🔍 Vérification des périodes en cours...');
        
        try {
            $rappelsEnvoyes = $this->emailService->verifierEtEnvoyerRappels();
            
            if ($rappelsEnvoyes > 0) {
                $this->info("✅ {$rappelsEnvoyes} rappel(s) d'échéance envoyé(s) avec succès.");
            } else {
                $this->info('ℹ️ Aucun rappel à envoyer pour le moment.');
            }
            
            return Command::SUCCESS;
            
        } catch (\Exception $e) {
            $this->error('❌ Erreur lors de la vérification des rappels : ' . $e->getMessage());
            Log::error('Erreur commande verifier-rappels', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return Command::FAILURE;
        }
    }
}