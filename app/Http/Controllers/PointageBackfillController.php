<?php

namespace App\Http\Controllers;

use App\Models\Entreprise;
use App\Models\Pointage;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PointageBackfillController extends Controller
{
    private function pointagesUsesUuid(): bool
    {
        $forced = config('pointages.id_is_uuid'); // mets true/false dans config si tu veux forcer
        if ($forced !== null) {
            return (bool) $forced;
        }

        // SHOW COLUMNS marche partout avec MySQL/MariaDB
        $col = DB::selectOne("SHOW COLUMNS FROM `pointages` LIKE 'id'");
        if (!$col) {
            return false;
        }
        $type = strtolower($col->Type ?? $col->type ?? '');
        return str_contains($type, 'char(36)') || str_contains($type, 'varchar(36)');
    }
    public function backfill(Request $request)
    {
        $request->validate([
            'date_debutperiode' => ['required', 'date'],
            'date_finperiode'   => ['required', 'date'],
        ]);

        $start = Carbon::parse($request->input('date_debutperiode'))->startOfDay();
        $end   = Carbon::parse($request->input('date_finperiode'))->endOfDay();
        if ($end->lt($start)) [$start, $end] = [$end, $start];

        $entreprise_id = session('entreprise_id');
        $entreprise    = Entreprise::findOrFail($entreprise_id);

        $heureDebutJour = $entreprise->heure_ouverture ?: '08:30:00';
        $heureFinJour   = $entreprise->heure_fin       ?: '17:30:00';

        $skipWeekends   = true;

        // 1) Utilisateurs actifs (tous ceux à insérer potentiellement)
        $userIds = User::where('entreprise_id', $entreprise_id)
            ->where('statu_user', 1)
            ->where('statut', 1)
            ->pluck('id');


        if ($userIds->isEmpty()) {
            return back()->with('status', "Aucun utilisateur actif trouvé.");
        }

        // 2) Liste des dates (selon règles)
        $dates = [];
        for ($d = $start->copy(); $d->lte($end); $d->addDay()) {
            if ($skipWeekends && $d->isWeekend()) continue;
            $dates[] = $d->toDateString();
        }
        if (!$dates) {
            return back()->with('status', "Aucun jour ouvré dans cette période.");
        }

        // 3) Récupérer LES EXISTANTS sans rater personne
        //    → on scanne par batch d'utilisateurs pour éviter des “trous”
        $existingKeys = [];
        $usersChunk = $userIds->chunk(500);

        foreach ($usersChunk as $chunk) {
            // si Pointage a un GlobalScope entreprise, commente withoutGlobalScopes()
            $rows = Pointage::/*withoutGlobalScopes()->*/whereIn('user_id', $chunk)
                // si date_arriver est DATE, c'est bon; si DATETIME, préfère whereBetween sur 00:00:00..23:59:59
                ->whereBetween('date_arriver', [reset($dates), end($dates)])
                ->get(['user_id', 'date_arriver']);

            foreach ($rows as $r) {
                $existingKeys[$r->user_id . '|' . $r->date_arriver] = true;
            }
        }

        // 4) Préparer insertions
        $now = now();
        $hasEntrepriseColumn = DB::getSchemaBuilder()->hasColumn('pointages', 'entreprise_id');
        $useUuid = $this->pointagesUsesUuid();

        $toInsert = [];

        foreach ($userIds as $uid) {
            foreach ($dates as $d) {
                $key = $uid . '|' . $d;
                if (!empty($existingKeys[$key])) continue;

                $row = [
                    'user_id'       => $uid,
                    'date_arriver'  => $d,
                    'heure_arriver' => $heureDebutJour,
                    'heure_fin'     => $heureFinJour,
                    'created_at'    => $now,
                    'updated_at'    => $now,
                ];
                if ($hasEntrepriseColumn) $row['entreprise_id'] = (int) $entreprise_id;
                if ($useUuid)            $row['id']            = (string) Str::uuid();

                $toInsert[] = $row;
            }
        }

        if (empty($toInsert)) {
            return back()->with('status', "Aucun pointage à insérer : tout est déjà présent.");
        }

        // 5) Insert en masse
        DB::transaction(function () use ($toInsert) {
            foreach (array_chunk($toInsert, 1000) as $chunk) {
                Pointage::insert($chunk);
            }
        });

        return back()->with('status', count($toInsert) . " pointage(s) inséré(s) avec les horaires entreprise.");
    }
}
