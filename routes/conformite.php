<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use App\Models\ConformitySubmission;
use App\Models\PeriodeItem;
use App\Models\Item;

/*
|--------------------------------------------------------------------------
| Routes de Conformité
|--------------------------------------------------------------------------
|
| Routes pour le système de gestion de conformité
|
*/

Route::middleware(['auth'])->prefix('conformite')->name('conformite.')->group(function () {

    // Routes pour Super Admin uniquement
    Route::middleware(['SuperAdmin'])->group(function () {

        // Page de configuration des items
        Route::get('/items', fn() => view('conformite.items'))->name('items');

        // Page de configuration des périodes
        Route::get('/periodes', fn() => view('conformite.periodes'))->name('periodes');

        // Configuration des entreprises (wizard)
        Route::get('/configuration', fn() => view('conformite.configuration'))->name('configuration');
    });

    // Routes pour les validateurs (ValideAudit)
    Route::middleware(['validateur'])->group(function () {

        // Liste des soumissions en attente
        Route::get('/validation', function () {
            $submissions = ConformitySubmission::with([
                'item:id,nom_item,type',
                'entreprise:id,nom_entreprise',
                'submittedBy:id,nom,prenom',      // <- aligné
                'periodeItem:id,item_id,debut_periode,fin_periode', // <- aligné
            ])
                ->enAttente()                         // <- scope
                ->latest('submitted_at')
                ->paginate(15);

            return view('conformite.validation', compact('submissions'));
        })->name('validation');

        // Détails d'une soumission
        Route::get('/validation/{submission}', function (ConformitySubmission $submission) {
            $submission->load([
                'item:id,nom_item,type,description',
                'entreprise:id,nom_entreprise',
                'submittedBy:id,nom,prenom,email',   // <- aligné
                'reviewedBy:id,nom,prenom',          // <- aligné
                'periodeItem:id,item_id,debut_periode,fin_periode', // <- aligné
                'answers' => fn($q) => $q->orderBy('position'),
            ]);

            return view('conformite.review', compact('submission'));
        })->name('review');

        Route::get('/validation/{submission}/ia', function (ConformitySubmission $submission) {

            $submission->load([
                'item.CategorieDomaine.Domaine',
                'item.options',
                'entreprise',
                'periodeItem',
                'submittedBy',
                'reviewedBy',
                'answers' => fn($q) => $q->orderBy('position'),
            ]);

            return view('conformite.review-ia', compact('submission'));
        })->name('reviewIA');
    });

    // Routes pour les entreprises
    Route::middleware(['entreprise'])->group(function () {

        // Mes soumissions
        Route::get('/mes-soumissions', function () {
            $entrepriseId = session('entreprise_id');

            $submissions = ConformitySubmission::with([
                'item:id,nom_item,type',
                'periodeItem:id,item_id,debut_periode,fin_periode', // <- aligné
                'reviewedBy:id,nom,prenom',                          // <- aligné
            ])
                ->where('entreprise_id', $entrepriseId)
                ->latest('submitted_at')
                ->paginate(20);

            return view('conformite.mes-soumissions', compact('submissions'));
        })->name('mes-soumissions');

        // Soumettre pour un item
        Route::get('/soumettre/{item}', function (Item $item) {
            $entrepriseId = session('entreprise_id');

            // Vérifier si l'item est assigné à l'entreprise
            $isAssigned = DB::table('entreprise_items')
                ->where('entreprise_id', $entrepriseId)
                ->where('item_id', $item->id)
                ->where('statut', '1')
                ->exists();

            if (!$isAssigned) {
                abort(403, 'Cet item n\'est pas assigné à votre entreprise.');
            }

            // Récupérer la période active
            $periode = PeriodeItem::where('item_id', $item->id)
                ->where('entreprise_id', $entrepriseId)
                ->where('statut', '1')
                ->whereDate('debut_periode', '<=', now())
                ->whereDate('fin_periode', '>=', now())
                ->first();

            if (!$periode) {
                return redirect()->route('conformite.index')
                    ->with('error', 'Aucune période active pour cet item.');
            }

            return view('conformite.submit', compact('item', 'periode'));
        })->name('submit');

        // Historique d'un item
        Route::get('/historique/{item}', function (Item $item) {
            $entrepriseId = session('entreprise_id');

            $submissions = ConformitySubmission::with([
                'periodeItem:id,item_id,debut_periode,fin_periode', // <- aligné
                'submittedBy:id,nom,prenom',                        // <- aligné
                'reviewedBy:id,nom,prenom',                         // <- aligné
            ])
                ->where('entreprise_id', $entrepriseId)
                ->where('item_id', $item->id)
                ->latest('submitted_at')
                ->paginate(10);

            return view('conformite.historique', compact('item', 'submissions'));
        })->name('historique');
    });

    // API endpoints pour Livewire (si nécessaire)
    Route::prefix('api')->name('api.')->group(function () {

        // Statistiques globales
        Route::get('/stats', function () {
            $entrepriseId = session('entreprise_id');

            $base = ConformitySubmission::where('entreprise_id', $entrepriseId);

            return response()->json([
                'total_items' => DB::table('entreprise_items')
                    ->where('entreprise_id', $entrepriseId)
                    ->where('statut', '1')
                    ->count(),
                'pending'  => (clone $base)->enAttente()->count(),   // <- scopes
                'approved' => (clone $base)->approuvees()->count(),  // <- scopes
                'rejected' => (clone $base)->rejetees()->count(),    // <- scopes
            ]);
        })->name('stats');

        // Vérifier si une période est active
        Route::get('/periode-active/{item}', function (Item $item) {
            $entrepriseId = session('entreprise_id');

            $periode = PeriodeItem::where('item_id', $item->id)
                ->where('entreprise_id', $entrepriseId)
                ->where('statut', '1')
                ->whereDate('debut_periode', '<=', now())
                ->whereDate('fin_periode', '>=', now())
                ->first();

            return response()->json([
                'active'  => (bool) $periode,
                'periode' => $periode,
            ]);
        })->name('periode-active');
    });
});
