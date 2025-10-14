<?php

use App\Http\Controllers\AbsenceController;
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
use Pusher\PushNotifications\PushNotifications; // Import the PushNotifications class
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CaisseWebController;
use App\Http\Controllers\DemandeInterventionController;
use App\Http\Controllers\Manager\ManagerAbsenceController;
use App\Http\Controllers\PdfController;
use App\Http\Controllers\AttestationStageController;
use Illuminate\Http\Request;
use Laravel\Sanctum\PersonalAccessToken;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\PushSubscriptionController;
use App\Models\User;
use App\Notifications\NewAlert;
use App\Http\Controllers\NotificationsController;

use App\Http\Controllers\OIDC\DiscoveryController;
use App\Http\Controllers\OIDC\UserInfoController;
use App\Http\Controllers\OIDC\JwksController;
use App\Http\Controllers\OIDC\LogoutController;
use App\Http\Controllers\OIDC\TokenProxyController;
use App\Http\Middleware\VerifyCsrfToken;
use App\Http\Controllers\PdfPointageController;


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

Route::get('/connexion', function () {
    return view('auth.mobile.login');
})->name('connexion');

Route::get('/inscription', function () {
    return view('auth.mobile.register');
})->name('inscription');

Route::get('/actualite', function () {
    return view('components.mobileApp.actualite');
})->name('actualite');



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

// Route::get('/beams/test', function () {
//     $beams = new PushNotifications([
//         'instanceId' => env('BEAMS_INSTANCE_ID'),
//         'secretKey'  => env('BEAMS_SECRET_KEY'),
//     ]);

//     $res = $beams->publishToInterests(
//         ['hello'], // intérêt sur lequel ton navigateur est abonné
//         [
//             'web' => [
//                 'notification' => [
//                     'title' => 'Hello',
//                     'body'  => 'Hello, world!',
//                     // 'icon'  => asset('assets/img/authentication/mobile.png'),
//                     // 'deep_link' => url('/notifications'), // optionnel
//                 ],
//             ],
//         ]
//     );

//     return 'OK'; // $res contient un publishId si tu veux l’afficher
// })->middleware('auth');
Route::get('/beams/token', function () {
    abort_unless(auth()->check(), 401);
    $userId = (string) auth()->id();

    $client = new \Pusher\PushNotifications\PushNotifications([
        'instanceId' => env('BEAMS_INSTANCE_ID'),
        'secretKey'  => env('BEAMS_SECRET_KEY'),
    ]);
    return $client->generateToken($userId);
})->middleware('auth');

// --- Endpoints publics (pas d'auth, pas de session) ---
// Route::get('/.well-known/openid-configuration', DiscoveryController::class);
Route::get('/.well-known/openid-configuration', [DiscoveryController::class, 'index'])->name('oidc.discovery');

Route::get('/oauth/jwks.json', [JwksController::class, 'index'])->name('oidc.jwks');

// --- Endpoints stateless OIDC (API, pas de CSRF) ---
Route::middleware('api')->group(function () {
    // Proxy token : POST depuis l'app cliente → doit être SANS CSRF
    Route::post('/oidc/token', [TokenProxyController::class, 'exchange'])->name('oidc.token');

    // UserInfo : nécessite un access_token → guard API uniquement
    Route::get('/oauth/userinfo', [UserInfoController::class,'index'])
        ->middleware('auth:api')
        ->name('oidc.userinfo');

    // Logout côté IdP si tu l’appelles en XHR/POST
    Route::match(['GET', 'POST'], '/oauth/logout', [LogoutController::class, 'logout'])->name('oidc.logout');
});

Route::middleware('auth')->group(
    function () {

        Route::get('/liste_modules', [ParamettreController::class, 'listemodules'])->name('components.liste_module');
        Route::patch('/demande-interventions/{demande}/status', [DemandeInterventionController::class, 'updateStatus'])
            ->name('demande_interventions.update_status');
        // La route que le JavaScript va appeler pour peupler la modale
        Route::get('/projects', [OpenProjectController::class, 'fetchProjects'])->name('projects');
        // La route que le JavaScript va appeler pour créer les tâches
        Route::post('/tasks', [OpenProjectController::class, 'generateAndCreateTasks'])->name('tasks.create');

        // Route::get('/', [AdminController::class, 'dashboard']);
        Route::get('/change_entreprise/{id}', [DashboardRHController::class, 'change_entreprise'])->name('change_entreprise');
        Route::get('/modules', [ParamettreController::class, 'modules'])->name('ModuleAdmin');
        Route::get('/liste_presence', [pointeController::class, 'liste_presence'])->name('liste_presence');
        Route::get('/sortie_intermediaire', [pointeController::class, 'sortie_intermediaire'])->name('sortie_intermediaire');
        Route::get('/liste-presence-imprime/{date_start}/{date_end}', [PdfController::class, 'imprimeListePresence'])->name('imprimeListe_presence');
        Route::get('/liste-presence-imprime/{date_start}/{date_end}/{userId}', [PdfController::class, 'imprimeListePresenceUser'])->name('imprimeListePresenceUser');

        Route::get('/liste_entreprise', [ParamettreController::class, 'liste_entreprise'])->name('liste_entreprise');
        Route::put('/modifier_entreprise/{id}', [ParamettreController::class, 'modifier_entreprise'])->name('modifier_entreprise');
        Route::post('/ajoute_entreprise', [ParamettreController::class, 'ajoute_entreprise'])->name('ajoute_entreprise');

        Route::post('/ajout_module', [ParamettreController::class, 'ajout_module'])->name('ajout_module');
        Route::put('/modifier_module/{id}', [ParamettreController::class, 'modifier_module'])->name('modifier_module');

        Route::get('/services', [ParamettreController::class, 'services'])->name('services');
        Route::post('/Ajoutservices', [ParamettreController::class, 'Ajoutservices'])->name('Ajoutservices');
        Route::put('/modifier_service/{id}', [ParamettreController::class, 'modifier_service'])->name('modifier_service');
        Route::delete('/supprimer_service/{id}', [ParamettreController::class, 'supprimer_service'])->name('supprimer_service');
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
        Route::get('/gestion_conge',  function () {
            return view('components.simulations.Simulateur_de_conges');
        })->name('gestion_conge');


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

        Route::post('/categories/multiple', [CaisseWebController::class, 'storeMultiple'])
            ->name('categories.storeMultiple');

        // Route::post('/variables/ajax', [CaisseWebController::class, 'storeAjax'])
        //     ->name('variables.ajax.store');

        Route::get('/variables/ajax', [CaisseWebController::class, 'variables'])->name('variables.ajax.index');
        Route::post('/variables', [CaisseWebController::class, 'storeAjax'])->name('variables.store');
        Route::get('/variables/{variable}', [CaisseWebController::class, 'showAjax'])
            ->name('variables.showAjax');
        // web.php
        Route::get('/variables/next-number', [CaisseWebController::class, 'nextNumber'])->name('variables.nextNumber');
        // web.php

        Route::put('/variables/{variable}', [CaisseWebController::class, 'updateAjax'])->name('variables.updateAjax');
        Route::delete('/variables/{id}', [CaisseWebController::class, 'destroy'])->name('variables.destroy');
        Route::post('/payroll/save-ticket', [CaisseWebController::class, 'saveByTicket'])->name('payroll.saveByTicket');
        Route::get('/payroll/tickets/replaceable', [CaisseWebController::class, 'listReplaceableTickets'])->name('payroll.tickets.replaceable');
        // routes/web.php
        Route::get('/payroll/period/{ticket}/data', [CaisseWebController::class, 'loadTicketData'])
            ->name('payroll.period.data');

        Route::post('/employees/update-base-salary', [CaisseWebController::class, 'updateBaseSalary'])->name('employees.updateBaseSalary');

        Route::get('/pdf/absent', [PdfController::class, 'absentsPdf'])->name('absentsPdf');
        Route::get('/pdf/presentPdf', [PdfController::class, 'presentPdf'])->name('presentPdf');
        Route::get('/pdf/payrollTablePdf/{ticket}', [PdfController::class, 'payrollTablePdf'])
            ->name('payrollTablePdf');
        Route::get('/pdf/detailParEmployerTablePdf/{ticket}', [PdfController::class, 'detailParEmployerTablePdf'])
            ->name('detailParEmployerTablePdf');

        Route::get('/pdf/fichePaie/{userId}/{tiketPeriode}', [PdfController::class, 'ficheDePaieDemo'])->name('ficheDePaieDemo');
        Route::get('/attestation-stage/{userId}/{tiketPeriode}', [AttestationStageController::class, 'attestationStage'])->name('attestationStage');


        // --- Routes pour l'employé ---

        Route::get('/absenceindex', [AbsenceController::class, 'index'])->name('absenceindex');
        // Afficher le formulaire de nouvelle demande
        Route::get('/absencecreate', [AbsenceController::class, 'create'])->name('absencecreate');
        // Enregistrer la nouvelle demande
        Route::post('/absencestore', [AbsenceController::class, 'store'])->name('absencestore');



        Route::view('/absences', 'absences.index')->name('managerindex');
        // Approuver ou rejeter une demande
        Route::patch('/{absence}/statut', [ManagerAbsenceController::class, 'updateStatus'])->name('managerupdateStatus');

        Route::post('/demande-interventions', [DemandeInterventionController::class, 'storeDemandeIntervention'])
            ->name('envoi_demande');


        Route::post('/push/subscribe', [PushSubscriptionController::class, 'store'])
            ->name('push.subscribe');
        Route::post('/push/unsubscribe', [PushSubscriptionController::class, 'destroy'])
            ->name('push.unsubscribe');

        // ================================== NOTIFICATION =================================
        Route::get('/dev/ping', function () {
            $entrepriseId = session('entreprise_id') ?? 'demo';
            broadcast(new \App\Events\ServiceCreated('Hello depuis Laravel', (string)$entrepriseId));
            return 'sent';
        });
        Route::get('/notifications', [NotificationsController::class, 'index'])->name('notifications.index');
        Route::post('/notifications/read/{id}', [NotificationsController::class, 'markAsRead'])->name('notifications.read');
        Route::post('/notifications/read-all', [NotificationsController::class, 'markAllAsRead'])->name('notifications.readAll');

        Route::get('/config-audit', function () {
            return view('components.configuration.config-audit');
        })->name('config-audit');

// Impression des pointages PDF
        Route::get('/pointages/pdf/{date_start}/{date_end}', [PdfPointageController::class, 'stream'])
    ->name('pointages.pdf.stream');
Route::get('/pointages/pdf/{date_start}/{date_end}/download', [PdfPointageController::class, 'download'])
    ->name('pointages.pdf.download');
Route::get('/pointages/pdf/{date_start}/{date_end}/save', [PdfPointageController::class, 'save'])
    ->name('pointages.pdf.save');

    Route::get('/pointage/user/{userId}/{date_start}/{date_end}/stream',   [PdfPointageController::class, 'streamUser'])->name('pointage.user.stream');
Route::get('/pointage/user/{userId}/{date_start}/{date_end}/download', [PdfPointageController::class, 'downloadUser'])->name('pointage.user.download');
Route::get('/pointage/user/{userId}/{date_start€}/{date_end}/save',     [PdfPointageController::class, 'saveUser'])->name('pointage.user.save');

    }

);
// ================================== OIDC avec Passport =================================



Route::get('/admin/demandes/{demande}/notifications', [DemandeInterventionController::class, 'showNotifications'])
    ->middleware(['auth', 'can:admin'])
    ->name('admin.demandes.notifications');
Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');
Route::post('/logout_module/{id}', [AuthenticatedSessionController::class, 'logout_module'])->name('logout_module');

// Gestion des routes API
// Cette seule ligne crée les routes pour index, show, store, update, destroy
Route::apiResource('users', UserController::class)->middleware('auth:sanctum');
