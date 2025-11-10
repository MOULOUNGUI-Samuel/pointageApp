<?php

namespace App\Console\Commands;

use App\Models\PeriodeItem;
use App\Models\ConformitySubmission;
use App\Services\NotificationConformiteService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class VerifierPeriodesConformite extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'conformite:verifier-periodes {--force : Forcer l\'exécution même en dehors des horaires}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Vérifie les périodes de conformité expirantes et envoie des alertes automatiques';

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
        $this->info('🔍 Vérification des périodes de conformité...');
        
        $stats = [
            'alertes_7j' => 0,
            'alertes_3j' => 0,
            'alertes_1j' => 0,
            'periodes_expirees' => 0,
        ];

        try {
            // 1. Vérifier les périodes à 7 jours
            $stats['alertes_7j'] = $this->verifierPeriodes(7);
            
            // 2. Vérifier les périodes à 3 jours
            $stats['alertes_3j'] = $this->verifierPeriodes(3);
            
            // 3. Vérifier les périodes à 1 jour
            $stats['alertes_1j'] = $this->verifierPeriodes(1);
            
            // 4. Marquer les périodes expirées
            $stats['periodes_expirees'] = $this->marquerPeriodesExpirees();

            // Afficher les résultats
            $this->newLine();
            $this->info('✅ Vérification terminée avec succès !');
            $this->table(
                ['Type d\'alerte', 'Nombre'],
                [
                    ['Alertes 7 jours', $stats['alertes_7j']],
                    ['Alertes 3 jours', $stats['alertes_3j']],
                    ['Alertes 1 jour', $stats['alertes_1j']],
                    ['Périodes expirées', $stats['periodes_expirees']],
                ]
            );

            Log::info('Vérification périodes conformité terminée', $stats);

            return Command::SUCCESS;

        } catch (\Exception $e) {
            $this->error('❌ Erreur lors de la vérification : ' . $e->getMessage());
            Log::error('Erreur vérification périodes conformité', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return Command::FAILURE;
        }
    }

    /**
     * Vérifier et envoyer des alertes pour les périodes à X jours
     */
    protected function verifierPeriodes(int $jours): int
    {
        $this->line("📅 Vérification des périodes à {$jours} jour(s)...");

        $dateVerification = now()->addDays($jours)->format('Y-m-d');
        $count = 0;

        // Récupérer les périodes qui expirent dans X jours
        $periodes = PeriodeItem::where('fin_periode', $dateVerification)
            ->where('statut', 1)
            ->with(['item.categorieDomaine.domaine', 'entreprise'])
            ->get();

        foreach ($periodes as $periode) {
            // Vérifier si l'entreprise a déjà soumis pour cette période
            $soumissionExiste = ConformitySubmission::where('entreprise_id', $periode->entreprise_id)
                ->where('item_id', $periode->item_id)
                ->where('periode_item_id', $periode->id)
                ->whereIn('status', ['soumis', 'approuvé'])
                ->exists();

            // Si pas de soumission, envoyer une alerte
            if (!$soumissionExiste && $periode->entreprise) {
                // Vérifier qu'on n'a pas déjà envoyé une alerte aujourd'hui pour cette période
                $alerteDejaEnvoyee = DB::table('notifications_conformite')
                    ->where('type', 'rappel_echeance')
                    ->where('periode_item_id', $periode->id)
                    ->where('entreprise_id', $periode->entreprise_id)
                    ->whereDate('created_at', now()->format('Y-m-d'))
                    ->exists();

                if (!$alerteDejaEnvoyee) {
                    $this->notificationService->notifierRappelEcheance(
                        $periode,
                        $periode->entreprise,
                        $jours
                    );
                    $count++;
                    $this->line("  ⚠️  Alerte envoyée pour : {$periode->item->nom_item} ({$periode->entreprise->nom_entreprise})");
                }
            }
        }

        return $count;
    }

    /**
     * Marquer les périodes expirées et envoyer des notifications
     */
    protected function marquerPeriodesExpirees(): int
    {
        $this->line('⏰ Vérification des périodes expirées...');

        $count = 0;
        $dateAujourdhui = now()->format('Y-m-d');

        // Récupérer les périodes expirées aujourd'hui
        $periodesExpirees = PeriodeItem::where('fin_periode', '<', $dateAujourdhui)
            ->where('statut', 1)
            ->with(['item', 'entreprise'])
            ->get();

        foreach ($periodesExpirees as $periode) {
            // Vérifier si l'entreprise a soumis et validé
            $soumissionValidee = ConformitySubmission::where('entreprise_id', $periode->entreprise_id)
                ->where('item_id', $periode->item_id)
                ->where('periode_item_id', $periode->id)
                ->where('status', 'approuvé')
                ->exists();

            if (!$soumissionValidee) {
                // Vérifier qu'on n'a pas déjà envoyé une notification d'expiration
                $notifExpirationEnvoyee = DB::table('notifications_conformite')
                    ->where('type', 'periode_expiree')
                    ->where('periode_item_id', $periode->id)
                    ->where('entreprise_id', $periode->entreprise_id)
                    ->exists();

                if (!$notifExpirationEnvoyee && $periode->entreprise) {
                    $this->notificationService->notifierPeriodeExpiree(
                        $periode,
                        $periode->entreprise
                    );
                    
                    $this->line("  ⏰ Notification d'expiration : {$periode->item->nom_item} ({$periode->entreprise->nom_entreprise})");
                }
            }

            // Marquer la période comme inactive (statut = 0)
            $periode->update(['statut' => 0]);
            $count++;
        }

        return $count;
    }

    /**
     * Envoyer un rapport quotidien aux admins
     */
    protected function envoyerRapportQuotidien(): void
    {
        $this->line('📊 Envoi du rapport quotidien aux administrateurs...');
        
        $notifications = $this->notificationService->notifierRapportQuotidien();
        
        if ($notifications->count() > 0) {
            $this->info("  ✉️  Rapport envoyé à {$notifications->count()} administrateur(s)");
        } else {
            $this->line("  ℹ️  Aucune soumission en attente, rapport non envoyé");
        }
    }
}