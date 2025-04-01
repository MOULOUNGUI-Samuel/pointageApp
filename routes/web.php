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

Route::post('/login', [AuthenticatedSessionController::class, 'store'])->name('login');

// Route::get('/LoginAdmin', [AdminController::class, 'index'])->name('loginAdmin');

Route::get('/loginPointe', function () {
    return view('auth.login');
});
Route::get('/sotier', [pointeController::class, 'index'])->name('sortie');
Route::get('/entrer', [pointeController::class, 'index1'])->name('entrer');

Route::get(
    '/dashboard',
    [AdminController::class, 'index_dashboard']
)->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(
    function () {
        // Route::get('/', [AdminController::class, 'dashboard']);
        Route::get('/liste_presence', [pointeController::class, 'liste_presence'])->name('liste_presence');
        Route::get('/sortie_intermediaire', [pointeController::class, 'sortie_intermediaire'])->name('sortie_intermediaire');

        Route::get('/liste_entreprise', [pointeController::class, 'liste_entreprise'])->name('liste_entreprise');
        Route::post('/ajoute_entreprise', [pointeController::class, 'ajoute_entreprise'])->name('ajoute_entreprise');

        Route::get('/liste_employer', [pointeController::class, 'liste_employer'])->name('liste_employer');
    }
);
require __DIR__ . '/auth.php';
Route::get('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');
