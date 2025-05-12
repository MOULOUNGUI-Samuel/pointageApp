<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\DashboardRHController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\pointeController;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Route::get('/yodirh.dashboard', function () {
//     return view('dashboard2');
// })->name('yodirh.dashboard');

Route::get('/login', function () {
    return view('auth.loginAdmin');
})->name('login.view');


Route::get('/loginGroupe/{id}', [AdminController::class, 'loginGroupe'])->name('loginGroupe');
Route::post('/login', [AuthenticatedSessionController::class, 'store'])->name('login');

// Route::get('/LoginAdmin', [AdminController::class, 'index'])->name('loginAdmin');

Route::get('/loginPointe', [pointeController::class, 'loginPointe'])->name('loginPointe');
Route::get('/sotier', [pointeController::class, 'index'])->name('sortie');
Route::get('/entrer', [pointeController::class, 'index1'])->name('entrer');
Route::get('/liste_modules', [pointeController::class, 'listemodules'])->name('components.liste_module');

Route::get(
    '/dashboard',
    [AdminController::class, 'index_dashboard']
)->middleware(['auth', 'verified'])->name('dashboard');

Route::get(
    '/dashboard_yodirh',
    [DashboardRHController::class, 'index_dashboard']
)->middleware(['auth', 'verified'])->name('yodirh.dashboard');

Route::middleware('auth')->group(
    function () {
        // Route::get('/', [AdminController::class, 'dashboard']);
        Route::get('/modules', [AdminController::class, 'modules'])->name('ModuleAdmin');
        Route::get('/liste_presence', [pointeController::class, 'liste_presence'])->name('liste_presence');
        Route::get('/sortie_intermediaire', [pointeController::class, 'sortie_intermediaire'])->name('sortie_intermediaire');

        Route::get('/liste_entreprise', [pointeController::class, 'liste_entreprise'])->name('liste_entreprise');
        Route::put('/modifier_entreprise/{id}', [pointeController::class, 'modifier_entreprise'])->name('modifier_entreprise');
        Route::post('/ajoute_entreprise', [pointeController::class, 'ajoute_entreprise'])->name('ajoute_entreprise');

        Route::post('/ajout_module', [pointeController::class, 'ajout_module'])->name('ajout_module');
        Route::put('/modifier_module/{id}', [pointeController::class, 'modifier_module'])->name('modifier_module');

        Route::get('/services', [DashboardRHController::class, 'services'])->name('services');
        Route::post('/Ajoutservices', [DashboardRHController::class, 'Ajoutservices'])->name('Ajoutservices');
        Route::put('/modifier_service/{id}', [DashboardRHController::class, 'modifier_service'])->name('modifier_service');

        Route::get('/categorieprofessionel', [DashboardRHController::class, 'categorieprofessionel'])->name('categorieprofessionel');
        Route::post('/Ajoutcategorieprofessionels', [DashboardRHController::class, 'Ajoutcategorieprofessionels'])->name('Ajoutcategorieprofessionels');
        Route::put('/modifier_categorieprofessionel/{id}', [DashboardRHController::class, 'modifier_categorieprofessionel'])->name('modifier_categorieprofessionel');

        Route::get('/Liste_utilisateur', [AdminController::class, 'affiche_utilisateur'])->name('yodirh.utilisateurs');
        Route::get('/utilisateur', [AdminController::class, 'formulaire'])->name('yodirh.formulaire_utilisateurs');
        Route::post('/ajoute_utilisateur', [AdminController::class, 'create'])->name('ajoute_utilisateur');
        Route::get('/modif_affiche_utilisateur/{id}', [AdminController::class, 'edit'])->name('modif_affiche_utilisateur');
        Route::put('/modifier_utilisateur/{id}', [AdminController::class, 'update'])->name('modifier_utilisateur');
        Route::get('/index_employer', [pointeController::class, 'index_employer'])->name('index_employer');
        Route::put('/modifier_employer/{id}', [AdminController::class, 'update'])->name('modifier_employer');

        Route::get('/pointage_compte', [pointeController::class, 'pointage_compte'])->name('pointage_compte');
        Route::get('/historique_pointage', [pointeController::class, 'historique_pointage'])->name('historique_pointage');
        Route::get('/Suivi_profil/{id}', [pointeController::class, 'Suivi_profil'])->name('Suivi_profil');
        Route::get('/profil_employe', [pointeController::class, 'profil_employe'])->name('profil_employe');

        Route::get('/pointage_sortie_connecter', [pointeController::class, 'pointage_sortie_connecter'])->name('pointage_sortie_connecter');
        Route::get('/liste_employer', [AdminController::class, 'liste_employer'])->name('liste_employer');
        Route::post('/login_connecter', [AdminController::class, 'pointage_connecter'])->name('login_connecter');


        Route::get('/Documents', [DocumentController::class, 'index'])->name('document.index');
        Route::post('/import-html', [DocumentController::class, 'import'])
            ->name('html.import');
        Route::get('/import-html', [DocumentController::class, 'import_affiche'])
            ->name('html.import.affiche');
        Route::post('/import-html/from-owncloud', [DocumentController::class, 'importFromOwncloud'])
            ->name('html.import.owncloud');
        // Chargement des routes dynamiques si le fichier existe
        $importedRoutesPath = base_path('routes/imported.php');

        if (File::exists($importedRoutesPath)) {
            require $importedRoutesPath;
        }
    }
);
Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');
Route::post('/logout_module/{id}', [AuthenticatedSessionController::class, 'logout_module'])->name('logout_module');
