<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\DashboardRHController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\ParamettreController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\pointeController;
use App\Http\Controllers\OpenProjectController;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CaisseWebController;
use Illuminate\Http\Request;
use Laravel\Sanctum\PersonalAccessToken;
use Illuminate\Support\Facades\Auth;
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


Route::get('/login', function () {
    return view('auth.loginAdmin');
})->name('login.view');

Route::get('/', function () {
    return redirect('/loginGroupe');
});
Route::get('/loginGroupe', [AdminController::class, 'loginGroupe'])->name('loginGroupe');
Route::post('/login', [AuthenticatedSessionController::class, 'store'])->name('login');

// Route::get('/LoginAdmin', [AdminController::class, 'index'])->name('loginAdmin');

Route::get('/loginPointe', [pointeController::class, 'loginPointe'])->name('loginPointe');
Route::get('/sotier', [pointeController::class, 'index'])->name('sortie');
Route::get('/entrer', [pointeController::class, 'index1'])->name('entrer');

Route::get(
    '/dashboard/{id}',
    [DashboardRHController::class, 'index_dashboard']
)->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(
    function () {
        Route::get('/liste_modules', [ParamettreController::class, 'listemodules'])->name('components.liste_module');
        // La route que le JavaScript va appeler pour peupler la modale
        Route::get('/projects', [OpenProjectController::class, 'fetchProjects'])->name('projects');
        // La route que le JavaScript va appeler pour créer les tâches
        Route::post('/tasks', [OpenProjectController::class, 'generateAndCreateTasks'])->name('tasks.create');

        // Route::get('/', [AdminController::class, 'dashboard']);
        Route::get('/change_entreprise/{id}', [DashboardRHController::class, 'change_entreprise'])->name('change_entreprise');
        Route::get('/modules', [ParamettreController::class, 'modules'])->name('ModuleAdmin');
        Route::get('/liste_presence', [pointeController::class, 'liste_presence'])->name('liste_presence');
        Route::get('/sortie_intermediaire', [pointeController::class, 'sortie_intermediaire'])->name('sortie_intermediaire');

        Route::get('/liste_entreprise', [ParamettreController::class, 'liste_entreprise'])->name('liste_entreprise');
        Route::put('/modifier_entreprise/{id}', [ParamettreController::class, 'modifier_entreprise'])->name('modifier_entreprise');
        Route::post('/ajoute_entreprise', [ParamettreController::class, 'ajoute_entreprise'])->name('ajoute_entreprise');

        Route::post('/ajout_module', [ParamettreController::class, 'ajout_module'])->name('ajout_module');
        Route::put('/modifier_module/{id}', [ParamettreController::class, 'modifier_module'])->name('modifier_module');

        Route::get('/services', [ParamettreController::class, 'services'])->name('services');
        Route::post('/Ajoutservices', [ParamettreController::class, 'Ajoutservices'])->name('Ajoutservices');
        Route::put('/modifier_service/{id}', [ParamettreController::class, 'modifier_service'])->name('modifier_service');
        Route::delete('/supprimer_service/{id}', [ParamettreController::class, 'supprimer_categorieprofessionel'])->name('supprimer_service');
        Route::post('/affecter_service', [ParamettreController::class, 'affecter_service'])->name('affecter_service');

        Route::get('/categorieprofessionel', [ParamettreController::class, 'categorieprofessionel'])->name('categorieprofessionel');
        Route::post('/Ajoutcategorieprofessionels', [ParamettreController::class, 'Ajoutcategorieprofessionels'])->name('Ajoutcategorieprofessionels');
        Route::put('/modifier_categorieprofessionel/{id}', [ParamettreController::class, 'modifier_categorieprofessionel'])->name('modifier_categorieprofessionel');
        Route::delete('/supprimer_categorieprofessionel/{id}', [ParamettreController::class, 'supprimer_categorieprofessionel'])->name('supprimer_categorieprofessionel');
        Route::post('/affecter_categorie', [ParamettreController::class, 'affecter_categorie'])->name('affecter_categorie');

        Route::get('/Liste_utilisateur', [AdminController::class, 'affiche_utilisateur'])->name('yodirh.utilisateurs');
        Route::get('/utilisateur', [AdminController::class, 'formulaire'])->name('yodirh.formulaire_utilisateurs');
        Route::post('/ajoute_utilisateur', [AdminController::class, 'create'])->name('ajoute_utilisateur');
        Route::get('/modif_affiche_utilisateur/{id}', [AdminController::class, 'edit'])->name('modif_affiche_utilisateur');
        Route::put('/modifier_utilisateur/{id}', [AdminController::class, 'update'])->name('modifier_utilisateur');
        Route::put('/updateCompteUser/{id}', [AdminController::class, 'updateCompteUser'])->name('updateCompteUser');
        Route::get('/index_employer', [pointeController::class, 'index_employer'])->name('index_employer');
        Route::put('/index_employer/{id}', [AdminController::class, 'desactiver_user'])->name('desactiver_user');
        Route::get('/statut_pointage/{id}', [AdminController::class, 'statut_pointe'])->name('statut_pointage');

        Route::get('/pointage_compte', [pointeController::class, 'pointage_compte'])->name('pointage_compte');
        Route::get('/historique_pointage', [pointeController::class, 'historique_pointage'])->name('historique_pointage');
        Route::get('/Suivi_profil/{id}', [pointeController::class, 'Suivi_profil'])->name('Suivi_profil');
        Route::get('/profil_employe', [pointeController::class, 'profil_employe'])->name('profil_employe');

        Route::get('/pointage_sortie_connecter', [pointeController::class, 'pointage_sortie_connecter'])->name('pointage_sortie_connecter');
        Route::get('/liste_employer', [AdminController::class, 'liste_employer'])->name('liste_employer');
        Route::post('/login_connecter', [AdminController::class, 'pointage_connecter'])->name('login_connecter');


        // Gestion des documents
        Route::get('/indexcartographie', [DocumentController::class, 'indexcartographie'])->name('document.indexcartographie');
        Route::get('/dashboard_doc/{nom_lien}', [DocumentController::class, 'dashboard'])->name('dashboard_doc');
        Route::delete('/lienDoc.destroy/{nom_dossier}', [DocumentController::class, 'lienDoc_destroy'])->name('lienDoc.destroy');
        // Route::get('/indexprocedure/{nom_lien}', [DocumentController::class, 'indexprocedure'])->name('indexprocedure');
        Route::post('/import-html/from-owncloudProcedure', [DocumentController::class, 'importFromOwncloudDoument'])->name('html.import.owncloudProcedure');

        // Routes pour l'intégration OpenProject


        // Import depuis OwnCloud
        Route::get('/documents', [DocumentController::class, 'index'])->name('document.index');
        Route::post('/import-html/from-owncloud', [DocumentController::class, 'importFromOwncloud'])->name('html.import.owncloud');
        Route::post('/partageFichier', [DocumentController::class, 'partageFichier'])->name('partageFichier');


        //Espace Utilitaires
        Route::get('/annuaire', [DocumentController::class, 'annuaire'])->name('annuaire');
        Route::get('/Simulateur_des_prets',  function () {
            return view('components.simulations.Simulateur_des_prets');
        })->name('Simulateur_des_prets');
        Route::get('/SIMULATEUR_FACTURATION_hots_TVA',  function () {
            return view('components.simulations.SIMULATEUR_FACTURATION_hots_TVA');
        })->name('SIMULATEUR_FACTURATION_hots_TVA');


        Route::get('/auto-login', function (Request $request) {
            $token = $request->query('token');

            if (!$token) {
                return redirect('/login')->withErrors('Token manquant.');
            }

            // On trouve le token dans la base de données
            $accessToken = PersonalAccessToken::findToken($token);

            if (!$accessToken || !$user = $accessToken->tokenable) {
                return redirect('/login')->withErrors('Token invalide ou expiré.');
            }

            // On connecte l'utilisateur associé à ce token
            Auth::login($user);

            // On supprime le token pour qu'il ne soit utilisé qu'une seule fois (sécurité)
            $accessToken->delete();

            // On redirige l'utilisateur vers son tableau de bord
            return redirect('/dashboard');
        })->name('auto-login');
        // Chargement des vues importées dynamiques
        $importedRoutesPath = base_path('routes/imported.php');
        if (File::exists($importedRoutesPath)) {
            require $importedRoutesPath;
        }

        Route::get('/paramettre', [PermissionController::class, 'paramettre'])->name('paramettre');
        Route::post('/enregistre_permissions', [PermissionController::class, 'permissions'])->name('enregistre_permissions');
        Route::delete('/supprimer_groupe/{id}', [PermissionController::class, 'supprimer_groupe'])->name('supprimer_groupe');
        Route::delete('/supprimer_permission/{id}', [PermissionController::class, 'supprimer_permission'])->name('supprimer_permission');
        Route::put('/groupe_permissions/{id}', [PermissionController::class, 'groupe_permissions'])->name('groupe_permissions');
        Route::put('/modif_permissions/{id}', [PermissionController::class, 'modif_permissions'])->name('modif_permissions');
        Route::post('/permissions', [PermissionController::class, 'permission'])->name('permission');

        Route::post('/login-caisse', [CaisseWebController::class, 'handleLogin'])->name('caisse.login');
        Route::get('/caisse-dashboard', function () {
            return view('dashboard');
        })->name('caisse.dashboard');

        Route::post('/openproject/test-connection', [OpenProjectController::class, 'testConnection'])->name('openproject.test');
        // NOUVELLE ROUTE POUR LA REDIRECTION VERS LE DASHBOARD
        Route::get('/openproject/redirect', [OpenProjectController::class, 'redirectToDashboard'])->name('openproject.redirect');
        Route::patch('/profile/api-key', [OpenProjectController::class, 'updateApiKey'])->name('profile.update.apikey');

        Route::get('/paie', [CaisseWebController::class, 'paie'])->name('paie');
    }
);
Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');
Route::post('/logout_module/{id}', [AuthenticatedSessionController::class, 'logout_module'])->name('logout_module');

// Gestion des routes API
// Cette seule ligne crée les routes pour index, show, store, update, destroy
Route::apiResource('users', UserController::class)->middleware('auth:sanctum');
