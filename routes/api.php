<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
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
use App\Http\Controllers\Api\UserController; // Ensure this class exists in the specified namespace

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
Route::middleware('api.key')->group(function () {
    Route::get('/users', [UserController::class, 'index']);
});
// Route::get('/users', [UserController::class, 'index']);   // ✅ Public
// Ensure the UserController class and 'show' method are implemented correctly
Route::get('/users/{user}', [UserController::class, 'show']);


