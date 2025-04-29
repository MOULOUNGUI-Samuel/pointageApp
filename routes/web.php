<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\pointeController;
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

Route::get('/dashboard_2', function () {
    return view('dashboard2');
})->name('dashboard_2');


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

Route::middleware('auth')->group(
    function () {
        // Route::get('/', [AdminController::class, 'dashboard']);
        Route::get('/modules', [AdminController::class, 'modules'])->name('ModuleAdmin');
        Route::get('/liste_presence', [pointeController::class, 'liste_presence'])->name('liste_presence');
        Route::get('/sortie_intermediaire', [pointeController::class, 'sortie_intermediaire'])->name('sortie_intermediaire');

        Route::get('/liste_entreprise', [pointeController::class, 'liste_entreprise'])->name('liste_entreprise');
        Route::post('/ajoute_entreprise', [pointeController::class, 'ajoute_entreprise'])->name('ajoute_entreprise');

        Route::post('/ajout_module', [pointeController::class, 'ajout_module'])->name('ajout_module');
        Route::put('/modifier_module/{id}', [pointeController::class, 'modifier_module'])->name('modifier_module');

        Route::post('/ajoute_utilisateur', [AdminController::class, 'create'])->name('ajoute_utilisateur');
        Route::get('/index_employer', [pointeController::class, 'index_employer'])->name('index_employer');
        Route::put('/modifier_employer/{id}', [AdminController::class, 'update'])->name('modifier_employer');

        Route::get('/pointage_compte', [pointeController::class, 'pointage_compte'])->name('pointage_compte');
        Route::get('/historique_pointage', [pointeController::class, 'historique_pointage'])->name('historique_pointage');
        Route::get('/Suivi_profil/{id}', [pointeController::class, 'Suivi_profil'])->name('Suivi_profil');
        Route::get('/profil_employe', [pointeController::class, 'profil_employe'])->name('profil_employe');

        Route::get('/pointage_sortie_connecter', [pointeController::class, 'pointage_sortie_connecter'])->name('pointage_sortie_connecter');
        Route::get('/liste_employer', [AdminController::class, 'liste_employer'])->name('liste_employer');
        Route::post('/login_connecter', [AdminController::class, 'pointage_connecter'])->name('login_connecter');
    }
);
Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');
Route::post('/logout_module/{id}', [AuthenticatedSessionController::class, 'logout_module'])->name('logout_module');
