<?php

/**
 * Helpers pour le système de conformité
 */

use App\Models\ConformitySubmission;
use App\Models\PeriodeItem;
use App\Models\Item;
use Illuminate\Support\Facades\DB;

if (!function_exists('get_status_badge')) {
    /**
     * Retourne le HTML d'un badge coloré selon le statut
     *
     * @param string $status
     * @return string
     */
    function get_status_badge(string $status): string
    {
        $badges = [
            'soumis' => '<span class="badge bg-warning-subtle text-warning border"><i class="ti ti-hourglass-high me-1"></i>En attente</span>',
            'approuvé' => '<span class="badge bg-success-subtle text-success border"><i class="ti ti-circle-check me-1"></i>Approuvé</span>',
            'rejeté' => '<span class="badge bg-danger-subtle text-danger border"><i class="ti ti-circle-x me-1"></i>Rejeté</span>',
        ];

        return $badges[$status] ?? '<span class="badge bg-secondary">Inconnu</span>';
    }
}

if (!function_exists('get_type_icon')) {
    /**
     * Retourne l'icône correspondant au type d'item
     *
     * @param string $type
     * @return string
     */
    function get_type_icon(string $type): string
    {
        $icons = [
            'texte' => 'ti ti-text-size',
            'documents' => 'ti ti-paperclip',
            'liste' => 'ti ti-list-check',
            'checkbox' => 'ti ti-checkbox',
        ];

        return $icons[$type] ?? 'ti ti-file';
    }
}

if (!function_exists('format_file_size')) {
    /**
     * Formate une taille de fichier en Ko/Mo/Go
     *
     * @param int $bytes
     * @return string
     */
    function format_file_size(int $bytes): string
    {
        $units = ['o', 'Ko', 'Mo', 'Go'];
        $i = 0;
        
        while ($bytes >= 1024 && $i < 3) {
            $bytes /= 1024;
            $i++;
        }
        
        return round($bytes, 2) . ' ' . $units[$i];
    }
}

if (!function_exists('is_periode_active')) {
    /**
     * Vérifie si une période est actuellement active
     *
     * @param string $itemId
     * @param string $entrepriseId
     * @return bool
     */
    function is_periode_active(string $itemId, string $entrepriseId): bool
    {
        return PeriodeItem::where('item_id', $itemId)
            ->where('entreprise_id', $entrepriseId)
            ->where('statut', '1')
            ->whereDate('debut_periode', '<=', now())
            ->whereDate('fin_periode', '>=', now())
            ->exists();
    }
}

if (!function_exists('get_active_periode')) {
    /**
     * Récupère la période active pour un item
     *
     * @param string $itemId
     * @param string $entrepriseId
     * @return PeriodeItem|null
     */
    function get_active_periode(string $itemId, string $entrepriseId): ?PeriodeItem
    {
        return PeriodeItem::where('item_id', $itemId)
            ->where('entreprise_id', $entrepriseId)
            ->where('statut', '1')
            ->whereDate('debut_periode', '<=', now())
            ->whereDate('fin_periode', '>=', now())
            ->first();
    }
}

if (!function_exists('can_submit_item')) {
    /**
     * Vérifie si une entreprise peut soumettre pour un item
     *
     * @param string $itemId
     * @param string $entrepriseId
     * @return bool
     */
    function can_submit_item(string $itemId, string $entrepriseId): bool
    {
        // Vérifier si l'item est assigné
        $isAssigned = DB::table('entreprise_items')
            ->where('entreprise_id', $entrepriseId)
            ->where('item_id', $itemId)
            ->where('statut', '1')
            ->exists();

        if (!$isAssigned) {
            return false;
        }

        // Vérifier s'il y a une période active
        return is_periode_active($itemId, $entrepriseId);
    }
}

if (!function_exists('get_latest_submission')) {
    /**
     * Récupère la dernière soumission d'un item pour une entreprise
     *
     * @param string $itemId
     * @param string $entrepriseId
     * @return ConformitySubmission|null
     */
    function get_latest_submission(string $itemId, string $entrepriseId): ?ConformitySubmission
    {
        return ConformitySubmission::where('item_id', $itemId)
            ->where('entreprise_id', $entrepriseId)
            ->latest('submitted_at')
            ->first();
    }
}

if (!function_exists('count_pending_submissions')) {
    /**
     * Compte le nombre de soumissions en attente
     *
     * @param string|null $entrepriseId Optionnel, pour filtrer par entreprise
     * @return int
     */
    function count_pending_submissions(?string $entrepriseId = null): int
    {
        $query = ConformitySubmission::where('status', 'soumis');
        
        if ($entrepriseId) {
            $query->where('entreprise_id', $entrepriseId);
        }
        
        return $query->count();
    }
}

if (!function_exists('get_conformite_stats')) {
    /**
     * Récupère les statistiques de conformité pour une entreprise
     *
     * @param string $entrepriseId
     * @return array
     */
    function get_conformite_stats(string $entrepriseId): array
    {
        $itemIds = DB::table('entreprise_items')
            ->where('entreprise_id', $entrepriseId)
            ->where('statut', '1')
            ->pluck('item_id');

        return [
            'total_items' => $itemIds->count(),
            'avec_periode_active' => PeriodeItem::whereIn('item_id', $itemIds)
                ->where('entreprise_id', $entrepriseId)
                ->where('statut', '1')
                ->whereDate('debut_periode', '<=', now())
                ->whereDate('fin_periode', '>=', now())
                ->distinct('item_id')
                ->count('item_id'),
            'en_attente' => ConformitySubmission::whereIn('item_id', $itemIds)
                ->where('entreprise_id', $entrepriseId)
                ->where('status', 'soumis')
                ->count(),
            'approuves' => ConformitySubmission::whereIn('item_id', $itemIds)
                ->where('entreprise_id', $entrepriseId)
                ->where('status', 'approuvé')
                ->count(),
            'rejetes' => ConformitySubmission::whereIn('item_id', $itemIds)
                ->where('entreprise_id', $entrepriseId)
                ->where('status', 'rejeté')
                ->count(),
            'taux_conformite' => function() use ($itemIds, $entrepriseId) {
                $total = $itemIds->count();
                if ($total === 0) return 0;
                
                $approuves = ConformitySubmission::whereIn('item_id', $itemIds)
                    ->where('entreprise_id', $entrepriseId)
                    ->where('status', 'approuvé')
                    ->distinct('item_id')
                    ->count('item_id');
                
                return round(($approuves / $total) * 100, 2);
            }
        ];
    }
}

if (!function_exists('days_until_deadline')) {
    /**
     * Calcule le nombre de jours avant la fin d'une période
     *
     * @param PeriodeItem $periode
     * @return int
     */
    function days_until_deadline(PeriodeItem $periode): int
    {
        if (!$periode->fin_periode) {
            return 0;
        }
        
        return now()->diffInDays($periode->fin_periode, false);
    }
}

if (!function_exists('is_deadline_soon')) {
    /**
     * Vérifie si la deadline approche (moins de 7 jours)
     *
     * @param PeriodeItem $periode
     * @return bool
     */
    function is_deadline_soon(PeriodeItem $periode): bool
    {
        $days = days_until_deadline($periode);
        return $days >= 0 && $days <= 7;
    }
}

if (!function_exists('get_urgency_badge')) {
    /**
     * Retourne un badge d'urgence selon le nombre de jours restants
     *
     * @param PeriodeItem $periode
     * @return string
     */
    function get_urgency_badge(PeriodeItem $periode): string
    {
        $days = days_until_deadline($periode);
        
        if ($days < 0) {
            return '<span class="badge bg-danger"><i class="ti ti-alert-triangle me-1"></i>Expiré</span>';
        }
        
        if ($days <= 3) {
            return '<span class="badge bg-danger"><i class="ti ti-clock me-1"></i>' . $days . ' jour(s)</span>';
        }
        
        if ($days <= 7) {
            return '<span class="badge bg-warning"><i class="ti ti-clock me-1"></i>' . $days . ' jour(s)</span>';
        }
        
        return '<span class="badge bg-success"><i class="ti ti-clock me-1"></i>' . $days . ' jour(s)</span>';
    }
}

if (!function_exists('sanitize_filename')) {
    /**
     * Nettoie un nom de fichier
     *
     * @param string $filename
     * @return string
     */
    function sanitize_filename(string $filename): string
    {
        $filename = preg_replace('/[^a-zA-Z0-9_\-\.]/', '_', $filename);
        $filename = preg_replace('/_+/', '_', $filename);
        return trim($filename, '_');
    }
}

if (!function_exists('get_item_completion_rate')) {
    /**
     * Calcule le taux de complétion d'un item pour une entreprise
     *
     * @param string $itemId
     * @param string $entrepriseId
     * @return float
     */
    function get_item_completion_rate(string $itemId, string $entrepriseId): float
    {
        $totalPeriodes = PeriodeItem::where('item_id', $itemId)
            ->where('entreprise_id', $entrepriseId)
            ->count();

        if ($totalPeriodes === 0) {
            return 0;
        }

        $periodesWithSubmission = PeriodeItem::where('item_id', $itemId)
            ->where('entreprise_id', $entrepriseId)
            ->whereHas('submissions')
            ->count();

        return round(($periodesWithSubmission / $totalPeriodes) * 100, 2);
    }
}