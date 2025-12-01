<?php

namespace App\Console\Commands;

use App\Models\PeriodeItem;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class RenewExpiredPeriodes extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'periodes:renew-expired
                            {--dry-run : Afficher ce qui serait renouvelé sans le faire}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Renouvelle automatiquement les périodes expirées avec auto_renew activé';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $isDryRun = $this->option('dry-run');

        $this->info('🔄 Recherche des périodes expirées avec renouvellement automatique...');

        $today = Carbon::today();

        // Récupérer les périodes expirées avec auto_renew = true
        $periodesToRenew = PeriodeItem::where('statut', '1')
            ->where('auto_renew', true)
            ->whereDate('fin_periode', '<', $today)
            ->whereNotNull('renew_duration_value')
            ->get();

        if ($periodesToRenew->isEmpty()) {
            $this->info('✅ Aucune période à renouveler aujourd\'hui.');
            return 0;
        }

        $this->info("📋 {$periodesToRenew->count()} période(s) à renouveler trouvée(s).");

        $renewed = 0;
        $errors = 0;

        foreach ($periodesToRenew as $periode) {
            try {
                DB::beginTransaction();

                $item = $periode->item;
                $entreprise = $periode->entreprise;

                // Calculer les nouvelles dates
                $newStartDate = Carbon::parse($periode->fin_periode)->addDay(); // Jour suivant la fin
                $newEndDate = $this->calculateNewEndDate($newStartDate, $periode->renew_duration_value, $periode->renew_duration_unit);

                if ($isDryRun) {
                    $this->line("   🔸 [DRY-RUN] Période {$periode->id}");
                    $this->line("      Item: {$item->nom_item}");
                    $this->line("      Entreprise: {$entreprise->nom_entreprise}");
                    $this->line("      Ancienne période: {$periode->debut_periode} → {$periode->fin_periode}");
                    $this->line("      Nouvelle période: {$newStartDate->format('Y-m-d')} → {$newEndDate->format('Y-m-d')}");
                    $renewed++;
                    continue;
                }

                // Clôturer l'ancienne période
                $periode->update(['statut' => '0']);

                // Créer la nouvelle période avec les mêmes paramètres
                $newPeriode = PeriodeItem::create([
                    'id' => \Illuminate\Support\Str::uuid(),
                    'item_id' => $periode->item_id,
                    'entreprise_id' => $periode->entreprise_id,
                    'debut_periode' => $newStartDate->format('Y-m-d'),
                    'fin_periode' => $newEndDate->format('Y-m-d'),
                    'statut' => '1',
                    'auto_renew' => true, // Conserver le renouvellement automatique
                    'renew_duration_value' => $periode->renew_duration_value,
                    'renew_duration_unit' => $periode->renew_duration_unit,
                ]);

                DB::commit();

                $this->line("   ✅ Période renouvelée: {$item->nom_item}");
                $this->line("      Nouvelle période: {$newStartDate->format('d/m/Y')} → {$newEndDate->format('d/m/Y')}");

                Log::info('[RenewExpiredPeriodes] Période renouvelée', [
                    'item_id' => $periode->item_id,
                    'item_nom' => $item->nom_item,
                    'entreprise_id' => $periode->entreprise_id,
                    'old_periode_id' => $periode->id,
                    'new_periode_id' => $newPeriode->id,
                    'old_dates' => "{$periode->debut_periode} → {$periode->fin_periode}",
                    'new_dates' => "{$newStartDate->format('Y-m-d')} → {$newEndDate->format('Y-m-d')}",
                ]);

                $renewed++;

            } catch (\Exception $e) {
                DB::rollBack();
                $errors++;

                $this->error("   ❌ Erreur lors du renouvellement de la période {$periode->id}: {$e->getMessage()}");

                Log::error('[RenewExpiredPeriodes] Erreur renouvellement', [
                    'periode_id' => $periode->id,
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString(),
                ]);
            }
        }

        // Résumé
        $this->newLine();
        if ($isDryRun) {
            $this->info("🔍 Mode DRY-RUN: {$renewed} période(s) seraient renouvelée(s)");
        } else {
            $this->info("✅ Résumé: {$renewed} période(s) renouvelée(s), {$errors} erreur(s)");
        }

        return 0;
    }

    /**
     * Calcule la nouvelle date de fin selon la durée et l'unité
     */
    private function calculateNewEndDate(Carbon $startDate, int $value, string $unit): Carbon
    {
        $endDate = $startDate->copy();

        return match ($unit) {
            'days' => $endDate->addDays($value),
            'months' => $endDate->addMonths($value),
            'years' => $endDate->addYears($value),
            default => $endDate->addMonths($value),
        };
    }
}
