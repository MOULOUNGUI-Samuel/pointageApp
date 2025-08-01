<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Laravel\Passport\Http\Routes\RouteRegistrar;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Ici sont enregistrées les routes de votre API.
|
*/
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;

// Route pour la connexion
Route::post('/login', [AuthenticatedSessionController::class, 'store']);

// Route pour l'inscription
Route::post('/register', [RegisteredUserController::class, 'store']);
// Cette ligne est correcte pour Laravel 10. Elle déclare les routes
// de Passport (/oauth/token, /oauth/authorize, etc.).
// Route::middleware('api')
//     ->prefix('oauth')
//     ->group(function ($router) {
//         (new RouteRegistrar($router))->all();
//     });

// --- VOS ROUTES PROTÉGÉES PAR PASSPORT ---

// L'appel à la route /api/user doit être protégé par le garde "api" de Passport.
Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

// Vous pouvez également protéger vos autres routes API de la même manière.
// Par exemple, pour votre route qui récupère la liste des utilisateurs :
Route::middleware('auth:api')->get('/users', [\App\Http\Controllers\Api\UserController::class, 'UserInfo'])->name('api.users.UserInfo');


