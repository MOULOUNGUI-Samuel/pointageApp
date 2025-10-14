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
    public function backfill(Request $request)
    {
        $request->validate([
            'date_debutperiode' => ['required','date'],
            'date_finperiode'   => ['required','date'],
        ]);

        // 1) Période
        $start = Carbon::parse($request->input('date_debutperiode'))->startOfDay();
        $end   = Carbon::parse($request->input('date_finperiode'))->endOfDay();
        if ($end->lt($start)) [$start, $end] = [$end, $start];

        // 2) Entreprise & horaires
        $entreprise_id = session('entreprise_id');
        $entreprise    = Entreprise::findOrFail($entreprise_id);

        $heureDebutJour = $entreprise->heure_ouverture ?: '08:30:00';
        $heureFinJour   = $entreprise->heure_fin       ?: '17:30:00';

        // (Optionnel) sauter WE & fériés ? mets à true si souhaité
        $skipWeekends = true;
        $skipHolidays = false; // nécessite Yasumi si tu veux activer

        $isHoliday = function (Carbon $d) use ($skipHolidays): bool {
            if (!$skipHolidays) return false;
            try {
                static $cache = [];
                $y = $d->year;
                if (!isset($cache[$y])) {
                    $cache[$y] = \Yasumi\Yasumi::create('France', $y);
                }
                return $cache[$y]->isHoliday($d);
            } catch (\Throwable $e) {
                return false;
            }
        };

        // 3) Utilisateurs actifs de l’entreprise
        $users = User::where('entreprise_id', $entreprise_id)
            ->where('statu_user', 1)
            ->where('statut', 1)
            ->get(['id']);

        if ($users->isEmpty()) {
            return back()->with('status', "Aucun utilisateur actif trouvé.");
        }

        $userIds = $users->pluck('id');

        // 4) Construire la liste des dates (selon règles)
        $dates = [];
        for ($d = $start->copy(); $d->lte($end); $d->addDay()) {
            if ($skipWeekends && $d->isWeekend()) continue;
            if ($isHoliday($d)) continue;
            $dates[] = $d->toDateString();
        }
        if (empty($dates)) {
            return back()->with('status', "Aucun jour ouvré dans cette période.");
        }

        // 5) Récupérer ce qui existe déjà dans pointages (pairs user_id + date_arriver)
        //    On ramène en "clé" 'user_id|date'
        $existingKeys = Pointage::whereIn('user_id', $userIds)
            ->whereBetween('date_arriver', [reset($dates), end($dates)])
            ->get(['user_id','date_arriver'])
            ->map(fn($p) => $p->user_id.'|'.$p->date_arriver)
            ->toBase() // collection base
            ->flip();  // pour tester existence en O(1)

        // 6) Préparer les insertions manquantes
        $now = now();
        $toInsert = [];

        foreach ($userIds as $uid) {
            foreach ($dates as $d) {
                $key = $uid.'|'.$d;
                if (isset($existingKeys[$key])) continue; // déjà présent → on saute

                $toInsert[] = [
                    'id'           => (string) Str::uuid(),   // <-- IMPORTANT si UUID
                    'user_id'      => $uid,
                    'date_arriver' => $d,
                    'heure_arriver'=> $heureDebutJour,
                    'heure_fin'    => $heureFinJour,
                    // complète ici si ton schéma a d'autres champs obligatoires:
                    // 'entreprise_id' => $entreprise_id,
                    // 'statut'        => null,
                    'created_at'   => $now,
                    'updated_at'   => $now,
                ];
            }
        }
dd($toInsert);
        if (empty($toInsert)) {
            return back()->with('status', "Aucun pointage à insérer : tout est déjà présent.");
        }

        // 7) Insert en masse (transaction + chunk si gros volume)
        DB::transaction(function () use ($toInsert) {
            foreach (array_chunk($toInsert, 1000) as $chunk) {
                Pointage::insert($chunk);
            }
        });

        return back()->with('status', count($toInsert)." pointage(s) inséré(s) avec les horaires entreprise.");
    }
}
