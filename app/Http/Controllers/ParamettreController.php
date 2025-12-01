<?php

namespace App\Http\Controllers;

use App\Models\CategorieProfessionnelle;
use App\Models\Entreprise;
use Illuminate\Http\Request;
use App\Models\Module;
use App\Models\Service;
use Illuminate\Support\Facades\Validator;
use App\Models\Demande_intervention;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Notifications\NewAlert; // Ensure this import exists and points to the correct namespace
use App\Services\Beams; // Import the Beams class from the correct namespace
use Illuminate\Support\Facades\Notification;
use App\Events\ServiceCreated; // Import the ServiceCreated event
use Pusher\PushNotifications\PushNotifications; // Import the PushNotifications class
use App\Models\ConformitySubmission;

use App\Models\Item;
use App\Models\PeriodeItem;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Livewire\Attributes\On;
use App\Services\PeriodeItemChecker;

class ParamettreController extends Controller
{
    /**
     * Display a listing of the resource.
     */


    public function listemodules(Request $request)
    {
        $structureId = session('entreprise_id');

        abort_unless($structureId, 403, 'Structure non sélectionnée.');
        $search = $request->input('q');
        $filtreStatut = $request->input('statut'); // ex: en_attente, en_cours, traitee, annulee, en_retard
        $onlyMine      = $request->boolean('only_mine');   // ?only_mine=1 pour "mes demandes"

        $query = Demande_intervention::with([
            'entreprise:id,nom_entreprise',
            'user:id,nom,prenom',
        ])
            // >>> visibilité : structure OU mes propres demandes
            ->visibleFor(Auth::id(), $structureId);

        // Filtre "mes demandes uniquement" (optionnel)
        if ($onlyMine) {
            $query->ownedByUser(Auth::id());
        }

        // Filtre statut (inclut le pseudo-statut "en_retard")
        if ($filtreStatut) {
            if ($filtreStatut === 'en_retard') {
                $query->where('statut', '!=', 'traitee')
                    ->whereDate('date_souhaite', '<', now()->toDateString());
            } else {
                $query->where('statut', $filtreStatut);
            }
        }

        // Recherche plein texte simple (titre, description, entreprise, utilisateur)
        if ($search) {
            $query->search($search);
        }

        $demandes = $query
            ->orderByDesc('created_at')
            ->paginate(20)
            ->withQueryString();

        if ($search) $query->search($search);

        $modules = Module::orderBy('created_at', 'asc')->get();
        $entreprises = Entreprise::orderBy('created_at', 'asc')->get();
        $utilisateurs = \App\Models\User::orderBy('created_at', 'asc')
            ->with('entreprise')
            ->get();

        $soumissionsSoumises = ConformitySubmission::soumissionsSoumises($structureId)->count();

        $entrepriseId = session('entreprise_id');

    // Récupérer les domaines de l'entreprise
        /** @var \Illuminate\Support\Collection<string, object> $domaines */
        $domaines = DB::table('entreprise_domaines')
            ->where('entreprise_id', $entrepriseId)
            ->where('entreprise_domaines.statut', '1')
            ->join('domaines', 'domaines.id', '=', 'entreprise_domaines.domaine_id')
            ->select('domaines.id', 'domaines.nom_domaine')
            ->orderBy('domaines.nom_domaine')
            ->get();

        $domaineStats = [];

        /** @var object{ id:string, nom_domaine:string, icone:?string } $domaine */
        foreach ($domaines as $domaine) {

            // Récupérer les items de ce domaine pour cette entreprise
            $itemIds = DB::table('entreprise_items')
                ->join('items', 'items.id', '=', 'entreprise_items.item_id')
                ->join('categorie_domaines', 'categorie_domaines.id', '=', 'items.categorie_domaine_id')
                ->where('entreprise_items.entreprise_id', $entrepriseId)
                ->where('entreprise_items.statut', '1')
                ->where('categorie_domaines.domaine_id', $domaine->id)
                ->pluck('items.id');

            if ($itemIds->isEmpty()) {
                continue; // Ne pas afficher les domaines sans items
            }

            $totalItems = $itemIds->count();

            // Calculer les stats par periode_state, items valides ET items non conformes
            $items = Item::whereIn('id', $itemIds)->get();
            $periodeStats = [
                'active' => 0,
                'upcoming' => 0,
                'expired' => 0,
                'disabled' => 0,
                'none' => 0,
            ];
            $valides = 0;
            $nonConformes = 0;

            foreach ($items as $item) {
                // 1. Calculer periode_state
                $state = $item->periode_state; // Utilise l'accessor
                if (isset($periodeStats[$state])) {
                    $periodeStats[$state]++;
                }

                // 2. Récupérer la dernière période avec statut = 1 pour cet item
                $activePeriode = PeriodeItem::where('item_id', $item->id)
                    ->where('entreprise_id', $entrepriseId)
                    ->orderByDesc('debut_periode')
                    ->first();

                // 3. Récupérer la dernière soumission
                $lastSub = $item->lastSubmission()->where('entreprise_id', $entrepriseId)->first();

                // 4. Calculer si l'item est VALIDE
                // Valide = soumission approuvée pendant la période active (statut=1)
                if ($lastSub && $lastSub->status === 'approuvé') {
                    $submittedAt = \Carbon\Carbon::parse($lastSub->submitted_at);
                    $debutPeriode = \Carbon\Carbon::parse($activePeriode->debut_periode);

                    // Vérifier que la soumission a été faite pendant ou après le début de la période active
                    if ($submittedAt->greaterThanOrEqualTo($debutPeriode)) {
                        $valides++; // Item valide : soumission correspond à la période active
                    } else {
                        // Soumission approuvée mais pour une ancienne période
                        if (PeriodeItemChecker::hasActivePeriod($item->id, $entrepriseId)) {
                            $nonConformes++; // Nouvelle période active = non conforme
                        }
                    }
                } else {
                    // 5. Calculer si l'item est NON CONFORME
                    $hasActivePeriode = PeriodeItemChecker::hasActivePeriod($item->id, $entrepriseId);

                    if ($hasActivePeriode) {
                        if (!$lastSub) {
                            // Non conforme : période active sans soumission
                            $nonConformes++;
                        } elseif ($lastSub->status === 'rejeté') {
                            // Non conforme : soumission rejetée
                            $nonConformes++;
                        }
                    }
                }
            }

            $domaineStats[] = [
                'id'          => $domaine->id,
                'nom'         => $domaine->nom_domaine,
                // 'icone'       => $domaine->icone ?? 'ti-folder',
                'total'       => $totalItems,
                'valides'     => $valides,
                'non_conformes' => $nonConformes, // Ajout du nombre de non conformes
                'periode_stats' => $periodeStats,
            ];
        }
                
        return view('components.liste_modules', compact('modules', 'utilisateurs', 'entreprises', 'demandes', 'search', 'filtreStatut', 'onlyMine', 'soumissionsSoumises', 'domaineStats'));
    }
    public function modules()
    {
        $modules = Module::orderBy('created_at', 'asc')->get();

        return view("components.modul_admin", compact('modules'));
    }
    public function liste_entreprise()
    {
        $entreprises = Entreprise::orderBy('created_at', 'desc')->get();
        return view('components.yodirh.liste_entreprise', compact('entreprises'));
    }

    public function ajoute_entreprise(Request $request)
    {

        // Validate the request data

        //
        $validator = Validator::make(
            $request->all(),
            [
                'nom_entreprise' => 'required',
                'code_entreprise' => 'required',
                'heure_ouverture' => 'required',
                'heure_fin' => 'required',
                'heure_debut_pose' => 'required',
                'heure_fin_pose' => 'required',
                // 'latitude' => 'required',
                // 'longitude' => 'required',
            ],
            [
                'nom_entreprise.required' => 'Le nom de l\'entreprise est requis',
                'code_entreprise.required' => 'Le code de l\'entreprise est requis',
                'heure_ouverture.required' => 'L\'heure d\'ouverture est requise',
                'heure_fin.required' => 'L\'heure de fin est requise',
                'heure_debut_pose.required' => 'L\'heure de début de pose est requise',
                'heure_fin_pose.required' => 'L\'heure de fin de pose est requise',
                'latitude.required' => 'La position X est requise',
                'longitude.required' => 'La position Y est requise',
            ]
        );

        // Return a success message if validation passes
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Create a new Entreprise instance and save it to the database
        $entreprise = new Entreprise();
        // Handle the logo upload if provided
        if ($request->hasFile('logo')) {
            $logo = $request->file('logo');
            $logoName = time() . '_' . $logo->getClientOriginalName();
            $logoPath = $logo->storeAs('logos', $logoName, 'public');
            $entreprise->logo = $logoPath;
        }
        $entreprise->nom_entreprise = $request->nom_entreprise;
        $entreprise->code_entreprise = $request->code_entreprise;
        $entreprise->heure_ouverture = $request->heure_ouverture;
        $entreprise->heure_fin = $request->heure_fin;
        $entreprise->heure_debut_pose = $request->heure_debut_pose;
        $entreprise->heure_fin_pose = $request->heure_fin_pose;
        $entreprise->latitude = $request->latitude;
        $entreprise->longitude = $request->longitude;
        $entreprise->statut = 1;
        $entreprise->save();
        return redirect()->back()->with('success', 'Entreprise ajoutée avec succès');
    }
    public function modifier_entreprise(Request $request, $id)
    {
        // Validate the request data
        $validator = Validator::make(
            $request->all(),
            [
                'nom_entreprise' => 'required',
                'code_entreprise' => 'required',
                'heure_ouverture' => 'required',
                'heure_fin' => 'required',
                'heure_debut_pose' => 'required',
                'heure_fin_pose' => 'required',
                'latitude' => 'required',
                'longitude' => 'required',
            ],
            [
                'nom_entreprise.required' => 'Le nom de l\'entreprise est requis',
                'code_entreprise.required' => 'Le code de l\'entreprise est requis',
                'heure_ouverture.required' => 'L\'heure d\'ouverture est requise',
                'heure_fin.required' => 'L\'heure de fin est requise',
                'heure_debut_pose.required' => 'L\'heure de début de pose est requise',
                'heure_fin_pose.required' => 'L\'heure de fin de pose est requise',
                'latitude.required' => 'La position X est requise',
                'longitude.required' => 'La position Y est requise',
            ]
        );

        // Return a success message if validation fails
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Find the existing Entreprise instance
        $entreprise = Entreprise::findOrFail($id);

        // Handle the logo upload if provided
        if ($request->hasFile('logo')) {
            $logo = $request->file('logo');
            $logoName = time() . '_' . $logo->getClientOriginalName();
            $logoPath = $logo->storeAs('logos', $logoName, 'public');
            $entreprise->logo = $logoPath;
        }

        // Update the entreprise details
        $entreprise->nom_entreprise = $request->nom_entreprise;
        $entreprise->code_entreprise = $request->code_entreprise;
        $entreprise->heure_ouverture = $request->heure_ouverture;
        $entreprise->heure_fin = $request->heure_fin;
        $entreprise->heure_debut_pose = $request->heure_debut_pose;
        $entreprise->heure_fin_pose = $request->heure_fin_pose;
        $entreprise->latitude = $request->latitude;
        $entreprise->longitude = $request->longitude;
        $entreprise->save();

        return redirect()->back()->with('success', 'Entreprise modifiée avec succès');
    }
    public function ajout_module(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'nom_module' => 'required',
            ],
            [
                'nom_module.required' => 'Le nom du module est requis'
            ]
        );

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $module = new Module();

        if ($request->hasFile('logo')) {
            $path = $request->file('logo')->store('images', 'public'); // correction ici
            $module->logo = $path;
        }

        $module->nom_module = $request->nom_module;
        $module->statut = 1;
        $module->save();

        return redirect()->back()->with('success', 'Module ajouté avec succès');
    }

    public function modifier_module(Request $request, $id)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'nom_module' => 'required',
            ],
            [
                'nom_module.required' => 'Le nom du module est requis'
            ]
        );

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $module = Module::findOrFail($id);

        if ($request->hasFile('logo')) {
            $path = $request->file('logo')->store('images', 'public');
            $module->logo = $path;
        }

        $module->nom_module = $request->nom_module;
        $module->save();

        return redirect()->back()->with('success', 'Module modifié avec succès');
    }


    public function services(Request $request)
    {
        $entreprise_id = session('entreprise_id');
        $services = Service::where('entreprise_id', $entreprise_id)->get();
        $entreprisesSansCeService = [];

        foreach ($services as $service) {
            $entreprisesSansCeService[$service->id] = Entreprise::whereDoesntHave('services', function ($query) use ($service) {
                $query->where('nom_service', $service->nom_service);
            })->get();
        }

        return view('components.yodirh.services', compact('services', 'entreprisesSansCeService'));
    }
    public function affecter_service(Request $request)
    {
        $request->validate([
            'entreprise_id' => 'required|exists:entreprises,id',
            'service_id' => 'required|exists:services,id',
        ]);

        $sourceService = Service::findOrFail($request->service_id);

        // Création d’un nouveau service pour l’entreprise cible
        $nouveauService = new Service();
        $nouveauService->nom_service = $sourceService->nom_service;
        $nouveauService->description = $sourceService->description;
        $nouveauService->entreprise_id = $request->entreprise_id;
        $nouveauService->save();

        return redirect()->back()->with('success', 'Le service a été intégré avec succès dans l\'entreprise sélectionnée.');
    }
    public function Ajoutservices(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nom_service' => 'required',
        ], [
            'nom_service.required' => 'Le nom du service est requis'
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $entreprise_id = (string) session('entreprise_id');

        $service = new Service();
        $service->nom_service   = $request->nom_service;
        $service->entreprise_id = $entreprise_id;
        $service->description   = $request->description;
        $service->statut        = 1;
        $service->save();

        // ---- Contenu commun
        $title = 'Nouveau service ajouté';
        $body  = '« ' . $service->nom_service . ' » a été créé.';
        $url   = url('/services');

        // A) PUSH OS (Beams) – vers tous les navigateurs abonnés de l’entreprise
        app(Beams::class)->publishToUsers(
            ['entreprise-' . $entreprise_id],
            [
                'title' => $title,
                'body'  => $body,
                // Ajoutez une icône personnalisée si vous en avez une
                // 'icon' => asset('path/to/your/icon.png'),
            ],
            $url // deep link
        );

        // B) TEMPS RÉEL IN-APP + HISTORIQUE
        $recipients = User::where('entreprise_id', $entreprise_id)
            ->where('id', '!=', Auth::id())
            ->get();

        Notification::send($recipients, new NewAlert($title, $body, $url));

        // Option : historiser aussi chez l’auteur
        $author = $request->user();
        $author?->notify(new NewAlert($title, $body, $url));

        $beams = new PushNotifications([
            'instanceId' => env('BEAMS_INSTANCE_ID'),
            'secretKey'  => env('BEAMS_SECRET_KEY'),
        ]);

        $res = $beams->publishToInterests(
            ['hello'], // intérêt sur lequel ton navigateur est abonné
            [
                'web' => [
                    'notification' => [
                        'title' => $title,
                        'body'  => $body,
                        'icon'  => asset('assets/img/authentication/logo_notif.JPG'),
                        'deep_link' => url('/notifications'), // optionnel
                    ],
                ],
            ]
        );

        // C) (Optionnel) événement broadcast "entreprise.{id}" si tu l’utilises pour d'autres listeners
        broadcast(new ServiceCreated($service->nom_service, $entreprise_id));

        return back()->with('success', 'Service ajouté avec succès');
    }

    public function modifier_service(Request $request, $id)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'nom_service' => 'required',
            ],
            [
                'nom_service.required' => 'Le nom du service est requis'
            ]
        );

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $services = Service::findOrFail($id);

        $services->nom_service = $request->nom_service;
        $services->description = $request->description;
        $services->save();

        return redirect()->back()->with('success', 'Service modifié avec succès');
    }
    public function supprimer_service($id)
    {
        $categories = Service::findOrFail($id);
        $categories->delete();
        return redirect()->back()->with('success', 'Service supprimée avec succès');
    }
    public function categorieprofessionel(Request $request)
    {
        $entreprise_id = session('entreprise_id');
        $categorieprofessionels = CategorieProfessionnelle::where('entreprise_id', $entreprise_id)->orderBy('created_at', 'desc')->get();

        $entreprisesSansCategorie = [];

        foreach ($categorieprofessionels as $categorieprofessionel) {
            $entreprisesSansCategorie[$categorieprofessionel->id] = Entreprise::whereDoesntHave('categorieProfessionels', function ($query) use ($categorieprofessionel) {
                $query->where('nom_categorie_professionnelle', $categorieprofessionel->nom_categorie_professionnelle);
            })->get();
        }

        return view('components.yodirh.categorieprofessionel', compact('categorieprofessionels', 'entreprisesSansCategorie'));
    }
    public function Ajoutcategorieprofessionels(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'nom_categorie_professionnelle' => 'required',
            ],
            [
                'nom_categorie_professionnelle.required' => 'Le nom de la catégorie est requis'
            ]
        );

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        $entreprise_id = session('entreprise_id');

        $categories = new CategorieProfessionnelle();
        $categories->entreprise_id = $entreprise_id;
        $categories->nom_categorie_professionnelle = $request->nom_categorie_professionnelle;
        $categories->description = $request->description;
        $categories->statut = 1;
        $categories->save();

        return redirect()->back()->with('success', 'Module ajouté avec succès');
    }

    public function modifier_categorieprofessionel(Request $request, $id)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'nom_categorie_professionnelle' => 'required',
            ],
            [
                'nom_categorie_professionnelle.required' => 'Le nom de la catégorie est requis'
            ]
        );

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $categories = CategorieProfessionnelle::findOrFail($id);

        $categories->nom_categorie_professionnelle = $request->nom_categorie_professionnelle;
        $categories->description = $request->description;
        $categories->save();

        return redirect()->back()->with('success', 'Catégorie modifiée avec succès');
    }
    public function supprimer_categorieprofessionel($id)
    {
        $categories = CategorieProfessionnelle::findOrFail($id);
        $categories->delete();
        return redirect()->back()->with('success', 'Catégorie supprimée avec succès');
    }

    public function affecter_categorie(Request $request)
    {
        $request->validate([
            'entreprise_id' => 'required|exists:entreprises,id',
            'categorie_id' => 'required|exists:categorie_professionnelles,id',
        ]);

        $sourceCategorie = CategorieProfessionnelle::findOrFail($request->categorie_id);

        // Création d’un nouveau service pour l’entreprise cible
        $nouveauCategorie = new CategorieProfessionnelle();
        $nouveauCategorie->nom_categorie_professionnelle = $sourceCategorie->nom_categorie_professionnelle;
        $nouveauCategorie->description = $sourceCategorie->description;
        $nouveauCategorie->entreprise_id = $request->entreprise_id;
        $nouveauCategorie->save();

        return redirect()->back()->with('success', 'La Catégorie a été intégré avec succès dans l\'entreprise sélectionnée.');
    }
}
