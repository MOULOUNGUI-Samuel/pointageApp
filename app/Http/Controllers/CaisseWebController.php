<?php

namespace App\Http\Controllers;

use App\Models\Categorie;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Helpers\CaisseHelper;
use App\Models\PeriodePaie;
use App\Models\User;
use App\Models\Variable;
use App\Models\VariablePeriodeUser;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use Illuminate\Support\Str;
use App\Helpers\PayrollTicket;


class CaisseWebController extends Controller
{

    public function handleLogin(Request $request): JsonResponse
    {
        // --- ÉTAPE 0 : VALIDATION DES CHAMPS ---
        $validated = $request->validate([
            'codesociete' => 'required|string',
            'login' => 'required|string',
            'mdp' => 'string|nullable',
        ], [
            'codesociete.required' => 'Le code société est obligatoire.',
            'login.required' => 'L\'identifiant est requis.',
        ]);


        // --- ÉTAPE 1 : VÉRIFICATION VIA L'API SOAP ---
        // On vérifie d'abord si les identifiants sont valides.
        $isLoginValid = CaisseHelper::loginCaisse($validated);

        // Si la première vérification échoue, on s'arrête immédiatement.
        if (!$isLoginValid) {
            return response()->json([
                'success' => false,
                'message' => '❌ Identifiants invalides ou accès refusé par le service de vérification.'
            ], 401);
        }


        // --- ÉTAPE 2 : CONNEXION ET RÉCUPÉRATION DU TOKEN VIA L'API REST ---
        // Cette étape n'est exécutée que si l'étape 1 a réussi.
        $apiResponse = CaisseHelper::loginCaisseEnvoiInfo($validated);

        // On vérifie le résultat de la deuxième API.
        if ($apiResponse['success']) {
            // Si la connexion réussit, on renvoie les infos de redirection.
            return response()->json($apiResponse, 200);
        }

        // Si la deuxième API échoue (par ex: service indisponible), on renvoie une erreur.
        // Le message d'erreur provient directement du Helper.
        return response()->json([
            'success' => false,
            'message' => $apiResponse['message'] ?? 'Le service de connexion à la caisse a rencontré un problème.'
        ], 503); // 503 Service Unavailable est plus approprié ici qu'un 401, car on sait que les identifiants sont bons.
    }

    public function paie()
    {
        $entreprise_id = session('entreprise_id');

        if (!$entreprise_id) {
            // La session 'entreprise_id' n'est pas définie
            return redirect()->route('erreur')->with('message', 'Session entreprise_id non définie.'); // Remplace 'erreur' par ta route d'erreur
        }

        $utilisateurs = User::with(['entreprise', 'categorieProfessionnelle', 'service', 'role', 'pays', 'ville'])
            ->orderBy('id', 'desc')
            ->where('statu_user', 1)
            ->where('entreprise_id', $entreprise_id)
            ->get();

        $categories = Categorie::where('statut', 1)->orderBy('nom_categorie')->get();

        $variables = Variable::with('categorie')->get();

        return view('components.yodirh.paie', compact('utilisateurs', 'categories', 'variables'));
    }

    public function storeMultiple(Request $request)
    {


        // Validation : tableau requis, éléments non vides, distincts dans la soumission
        $validated = $request->validate(
            [
                'nom_categorie'   => ['required', 'array', 'min:1'],
                'nom_categorie.*' => ['required', 'string', 'max:150', 'distinct'],
                // Si tu veux empêcher les doublons base de données, ajoute :
                // 'nom_categorie.*' => ['required','string','max:150','distinct','unique:categories,nom_categorie'],
            ],
            [
                'nom_categorie.required'   => 'Ajoutez au moins une catégorie.',
                'nom_categorie.*.required' => 'Le nom de catégorie est requis.',
                'nom_categorie.*.distinct' => 'Les noms doivent être distincts.',
                // 'nom_categorie.*.unique'   => 'Cette catégorie existe déjà.',
            ]
        );

        // Insertion : idempotente (n’insère pas un doublon existant)
        DB::transaction(function () use ($validated) {
            foreach ($validated['nom_categorie'] as $nom) {
                $nom = trim($nom);
                if ($nom === '') {
                    continue;
                }
                Categorie::firstOrCreate(
                    ['nom_categorie' => $nom],
                    ['statut' => true]
                );
            }
        });

        return back()->with('success', 'Catégories enregistrées avec succès.');
    }

    public function storeAjax(Request $request)
    {
        // Normalise le taux côté serveur aussi (sécurité / robustesse)
        $payload = $request->all();
        if (isset($payload['tauxVariable']) && $payload['tauxVariable'] !== null) {
            $payload['tauxVariable'] = str_replace(',', '.', trim($payload['tauxVariable']));
        }
        if (isset($payload['tauxVariableEntreprise']) && $payload['tauxVariableEntreprise'] !== null) {
            $payload['tauxVariableEntreprise'] = str_replace(',', '.', trim($payload['tauxVariableEntreprise']));
        }

        $validator = Validator::make($payload, [
            'nom_variable'       => ['required', 'string', 'max:150'],
            'type'               => ['required', 'in:gain,deduction'],
            'categorie_id'       => ['required', 'uuid', 'exists:categories,id'],
            'statutVariable'     => ['sometimes', 'boolean'], // case à cocher
            'variableImposable'  => ['sometimes', 'boolean'],
            // taux requis si statutVariable = true|1 ; bornes 0..100
            'tauxVariable'       => ['nullable', 'numeric', 'gte:0', 'lte:100', 'required_if:statutVariable,1'],
            'tauxVariableEntreprise'       => ['nullable', 'numeric', 'gte:0', 'lte:100', 'required_if:statutVariable,1'],
        ], [
            'nom_variable.required'  => 'Le nom de la variable est requis.',
            'type.required'          => 'Veuillez choisir un type.',
            'type.in'                => 'Type invalide.',
            'categorie_id.required'  => 'La catégorie est requise.',
            'categorie_id.exists'    => 'Catégorie introuvable.',

            'tauxVariable.required_if' => 'Le taux est requis pour une variable de cotisation.',
            'tauxVariable.numeric'     => 'Le taux doit être un nombre valide.',
            'tauxVariable.gte'         => 'Le taux doit être supérieur ou égal à 0.',
            'tauxVariable.lte'         => 'Le taux doit être inférieur ou égal à 100.',

            'tauxVariableEntreprise.required_if' => 'Le taux est requis pour une variable de cotisation.',
            'tauxVariableEntreprise.numeric'     => 'Le taux doit être un nombre valide.',
            'tauxVariableEntreprise.gte'         => 'Le taux doit être supérieur ou égal à 0.',
            'tauxVariableEntreprise.lte'         => 'Le taux doit être inférieur ou égal à 100.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation error',
                'errors'  => $validator->errors(),
            ], 422);
        }

        $data = $validator->validated();

        // Si ce n’est pas une variable de cotisation, on force le taux à NULL (ou 0 si la colonne n’est pas nullable)
        $isCotisation = !empty($data['statutVariable']) && (int)$data['statutVariable'] === 1;
        $taux = $isCotisation ? ($data['tauxVariable'] ?? null) : null; // ou 0 si colonne NOT NULL
        $taux1 = $isCotisation ? ($data['tauxVariableEntreprise'] ?? null) : null; // ou 0 si colonne NOT NULL

        $variable = Variable::create([
            'categorie_id'       => $data['categorie_id'],
            'nom_variable'       => $data['nom_variable'],
            'type'               => $data['type'],
            'statutVariable'     => $isCotisation,
            'variableImposable'  => !empty($data['variableImposable']),
            'tauxVariable'       => $taux, // ou 0.00 si NOT NULL
            'tauxVariableEntreprise'       => $taux1, // ou 0.00 si NOT NULL
            'statut'             => true,
        ])->load('categorie');

        return response()->json([
            'data'    => $variable,
            'message' => 'Variable créée avec succès',
        ], 201);
    }
    public function updateAjax(Request $request, Variable $variable)
    {
        $payload = $request->all();
        if (isset($payload['tauxVariable']) && $payload['tauxVariable'] !== null) {
            $payload['tauxVariable'] = str_replace(',', '.', trim($payload['tauxVariable']));
        }
        if (isset($payload['tauxVariableEntreprise']) && $payload['tauxVariableEntreprise'] !== null) {
            $payload['tauxVariableEntreprise'] = str_replace(',', '.', trim($payload['tauxVariableEntreprise']));
        }
    
        $validator = Validator::make($payload, [
            'nom_variable'       => ['required', 'string', 'max:150'],
            'type'               => ['required', 'in:gain,deduction'],
            'categorie_id'       => ['required', 'uuid', 'exists:categories,id'],
            'statutVariable'     => ['sometimes', 'boolean'],
            'variableImposable'  => ['sometimes', 'boolean'],
            'tauxVariable'       => ['nullable','numeric','gte:0','lte:100','required_if:statutVariable,1'],
            'tauxVariableEntreprise'       => ['nullable','numeric','gte:0','lte:100','required_if:statutVariable,1'],
        ], [
            'nom_variable.required'  => 'Le nom de la variable est requis.',
            'type.required'          => 'Veuillez choisir un type.',
            'type.in'                => 'Type invalide.',
            'categorie_id.required'  => 'La catégorie est requise.',
            'categorie_id.exists'    => 'Catégorie introuvable.',
            'tauxVariable.required_if' => 'Le taux est requis pour une variable de cotisation.',
            'tauxVariable.numeric'     => 'Le taux doit être un nombre valide.',
            'tauxVariable.gte'         => 'Le taux doit être ≥ 0.',
            'tauxVariable.lte'         => 'Le taux doit être ≤ 100.',

            'tauxVariableEntreprise.required_if' => 'Le taux est requis pour une variable de cotisation.',
            'tauxVariableEntreprise.numeric'     => 'Le taux doit être un nombre valide.',
            'tauxVariableEntreprise.gte'         => 'Le taux doit être ≥ 0.',
            'tauxVariableEntreprise.lte'         => 'Le taux doit être ≤ 100.',
        ]);
    
        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation error',
                'errors'  => $validator->errors(),
            ], 422);
        }
    
        $data = $validator->validated();
        $isCotisation = !empty($data['statutVariable']) && (int)$data['statutVariable'] === 1;
    
        $variable->update([
            'nom_variable'       => $data['nom_variable'],
            'type'               => $data['type'],
            'categorie_id'       => $data['categorie_id'],
            'statutVariable'     => $isCotisation,
            'variableImposable'  => !empty($data['variableImposable']),
            'tauxVariable'       => $isCotisation ? ($data['tauxVariable'] ?? null) : null, // ou 0.00 si NOT NULL
            'tauxVariableEntreprise'       => $isCotisation ? ($data['tauxVariableEntreprise'] ?? null) : null, // ou 0.00 si NOT NULL
            'statut'             => $variable->statut ?? true,
        ]);
    
        $variable->load('categorie');
    
        return response()->json([
            'data'    => $variable,
            'message' => 'Variable mise à jour avec succès',
        ], 200);
    }
    
    public function variables()
    {
        $variables = Variable::with('categorie')->get(); // Charger la relation "categorie"
        return response()->json(['data' => $variables]);
    }

    public function destroy($id)
    {
        $variable = Variable::findOrFail($id);
        $variable->delete();

        return response()->json(['message' => 'Variable supprimée avec succès !']);
    }


    public function saveByTicket(Request $request)
    {
        $v = Validator::make(
            $request->all(),
            [
                'period.start'   => ['bail', 'required', 'date'],
                'period.end'     => ['bail', 'required', 'date', 'after_or_equal:period.start'],
                'employeeData'   => ['bail', 'required', 'array'],
                'employeeData.*' => ['bail', 'array'],
                'replace_ticket' => ['nullable', 'string', 'max:20'],
            ],
            [
                'required'                    => 'Le champ :attribute est obligatoire.',
                'date'                        => 'Le champ :attribute doit être une date valide.',
                'array'                       => 'Le champ :attribute doit être un tableau.',
                'period.end.after_or_equal'   => 'La date de fin doit être postérieure ou égale à la date de début.',
            ],
            [
                'period.start'   => 'date de début',
                'period.end'     => 'date de fin',
                'employeeData'   => 'données de paie',
                'employeeData.*' => 'variables de l’employé',
                'replace_ticket' => 'ticket à remplacer',
            ]
        );

        if ($v->fails()) {
            $errors = $v->errors();
            return response()->json([
                'message'     => 'Données invalides. Veuillez corriger les erreurs.',
                'first_error' => $errors->first(),     // pratique pour une toast
                'errors'      => $errors->toArray(),   // ex: ['period.start'=>['...'], ...]
            ], 422);
        }



        $data = $v->validated();

        $entrepriseId = session('entreprise_id');
        if (!$entrepriseId) {
            return response()->json(['errors' => ['entreprise' => ['Entreprise absente en session.']]], 422);
        }

        $start     = Carbon::parse($data['period']['start'])->toDateString();
        $end       = Carbon::parse($data['period']['end'])->toDateString();
        $newTicket = PayrollTicket::fromDates($start, $end);

        $replaceTicket = $data['replace_ticket'] ?? null;
        if ($replaceTicket && !PayrollTicket::isValid($replaceTicket)) {
            return response()->json(['errors' => ['replace_ticket' => ['Ticket à remplacer invalide.']]], 422);
        }

        $result = DB::transaction(function () use ($entrepriseId, $start, $end, $newTicket, $replaceTicket, $data) {

            // ---------- Préchargements (performances) ----------
            $employeeKeys = array_keys($data['employeeData']); // clés = matricules ou ids
            $users = User::whereIn('id', $employeeKeys)
                ->orWhereIn('id', $employeeKeys)
                ->get(['id', 'matricule']);

            $userIndex = [];
            foreach ($users as $u) {
                if ($u->matricule) $userIndex[$u->matricule] = $u->id;
                $userIndex[$u->id] = $u->id;
            }

            $varNames = [];
            foreach ($data['employeeData'] as $vars) {
                foreach ($vars as $n => $v) $varNames[] = trim((string)$n);
            }
            $varNames  = array_values(array_unique(array_filter($varNames)));
            $variables = Variable::whereIn('nom_variable', $varNames)->get(['id', 'nom_variable']);

            $varIndex = [];
            foreach ($variables as $v) $varIndex[$v->nom_variable] = $v->id;

            // ---------- Helpers : buildRows (déduplique) & replaceRows (delete+insert) ----------
            // ⚠️ Si un montant arrive à 0 (ou null / ''), on SUPPRIME la ligne (ne pas réinsérer).
            $buildRows = function (string $periodeId) use ($data, $userIndex, $varIndex) {
                $rowsByKey = []; // clé unique: "userId|variableId"
                $now = now();

                foreach ($data['employeeData'] as $empKey => $map) {
                    $userId = $userIndex[$empKey] ?? null;
                    if (!$userId) continue;

                    foreach ($map as $varName => $montant) {
                        $varId = $varIndex[trim((string)$varName)] ?? null;
                        if (!$varId) continue;

                        // normaliser la valeur reçue
                        $val = is_numeric($montant)
                            ? (float) $montant
                            : (float) str_replace(',', '.', (string) $montant);

                        $key = $userId . '|' . $varId;

                        // 👉 Si la dernière valeur reçue pour ce couple est <= 0,
                        //    on s'assure que la ligne ne sera PAS réinsérée.
                        if ($val <= 0) {
                            if (isset($rowsByKey[$key])) {
                                unset($rowsByKey[$key]);
                            }
                            continue; // ne pas ajouter de ligne pour 0
                        }

                        // valeur > 0 -> on (ré)enregistre cette ligne
                        $rowsByKey[$key] = [
                            'id'               => (string) Str::uuid(),
                            'user_id'          => $userId,
                            'periode_paie_id'  => $periodeId,
                            'variable_id'      => $varId,
                            'montant'          => $val,
                            'statut'           => true,
                            'created_at'       => $now,
                            'updated_at'       => $now,
                        ];
                    }
                }

                return array_values($rowsByKey);
            };

            $replaceRows = function (string $periodeId) use ($buildRows) {
                // Si SoftDeletes sur le modèle, remplacer par withTrashed()->forceDelete()
                VariablePeriodeUser::where('periode_paie_id', $periodeId)->delete();

                $rows = $buildRows($periodeId);
                if (!empty($rows)) {
                    VariablePeriodeUser::insert($rows);
                }
                return count($rows);
            };

            // ---------- CAS A : l’utilisateur a choisi un ticket à remplacer ----------
            if ($replaceTicket) {
                $periodeOld = PeriodePaie::where('entreprise_id', $entrepriseId)
                    ->where('ticket', $replaceTicket)
                    ->lockForUpdate()
                    ->first();

                if (!$periodeOld) {
                    return [
                        'http'    => 422,
                        'message' => "Le ticket à remplacer n'existe pas.",
                    ];
                }

                if ($newTicket !== $replaceTicket) {
                    // collision éventuelle avec un autre enregistrement
                    $collision = PeriodePaie::where('entreprise_id', $entrepriseId)
                        ->where('ticket', $newTicket)
                        ->where('id', '!=', $periodeOld->id)
                        ->exists();

                    if ($collision) {
                        return [
                            'http'    => 422,
                            'message' => "Un ticket {$newTicket} existe déjà. Sélectionnez-le ou changez l'intervalle.",
                        ];
                    }

                    // renommer le ticket + mettre à jour les dates
                    $periodeOld->update([
                        'ticket'     => $newTicket,
                        'date_debut' => $start,
                        'date_fin'   => $end,
                    ]);
                } else {
                    // même ticket : on aligne juste les dates
                    $periodeOld->update(['date_debut' => $start, 'date_fin' => $end]);
                }

                $inserted = $replaceRows($periodeOld->id);

                return [
                    'http'    => 200,
                    'mode'    => ($newTicket !== $replaceTicket) ? 'renamed_and_replaced' : 'replaced',
                    'ticket'  => $newTicket,
                    'message' => ($newTicket !== $replaceTicket)
                        ? "Le ticket {$replaceTicket} a été renommé en {$newTicket} et les données ont été remplacées."
                        : "Ticket {$newTicket} : données remplacées.",
                    'count'   => $inserted,
                ];
            }

            // ---------- CAS B : aucun ticket à remplacer -> on travaille avec le ticket généré ----------
            $periode = PeriodePaie::where('entreprise_id', $entrepriseId)
                ->where('ticket', $newTicket)
                ->lockForUpdate()
                ->first();

            if (!$periode) {
                $periode = PeriodePaie::create([
                    'entreprise_id' => $entrepriseId,
                    'ticket'        => $newTicket,
                    'date_debut'    => $start,
                    'date_fin'      => $end,
                    'statut'        => true,
                ]);
                $mode = 'created';
            } else {
                // MAJ des dates et remplacement total des lignes
                $periode->update(['date_debut' => $start, 'date_fin' => $end]);
                $mode = 'replaced';
            }

            $inserted = $replaceRows($periode->id);

            return [
                'http'    => 200,
                'mode'    => $mode,
                'ticket'  => $newTicket,
                'message' => $mode === 'created'
                    ? 'Ticket créé : période enregistrée et lignes insérées.'
                    : 'Ticket existant : données remplacées.',
                'count'   => $inserted,
            ];
        });

        if (($result['http'] ?? 200) !== 200) {
            return response()->json(['message' => $result['message']], $result['http']);
        }

        return response()->json([
            'message' => $result['message'],
            'data'    => [
                'mode'   => $result['mode']   ?? null,
                'ticket' => $result['ticket'] ?? null,
                'count'  => $result['count']  ?? 0,
            ],
        ], 200);
    }


    // Liste des tickets remplaçables (ex: statut=0)
    public function listReplaceableTickets()
    {
        $entrepriseId = session('entreprise_id');
        $tickets = PeriodePaie::where('entreprise_id', $entrepriseId)
            ->where('statut', true)   // à adapter à ta logique "non passée"
            ->orderBy('date_debut', 'desc')
            ->get(['ticket', 'date_debut', 'date_fin']);
        return response()->json(['data' => $tickets]);
    }

    public function loadTicketData(Request $request, string $ticket)
    {
        $entrepriseId = session('entreprise_id');

        if (!PayrollTicket::isValid($ticket)) {
            return response()->json([
                'message' => 'Ticket invalide.',
            ], 422);
        }

        $periode = PeriodePaie::where('entreprise_id', $entrepriseId)
            ->where('ticket', $ticket)
            ->first();

        if (!$periode) {
            return response()->json([
                'message' => "Aucune période trouvée pour le ticket {$ticket}.",
            ], 404);
        }

        // On récupère toutes les lignes de la période, avec le nom de la variable, son type et sa catégorie
        $rows = DB::table('variable_periode_users as vpu')
            ->join('users as u', 'u.id', '=', 'vpu.user_id')
            ->join('variables as v', 'v.id', '=', 'vpu.variable_id')
            ->leftJoin('categories as c', 'c.id', '=', 'v.categorie_id')
            ->where('vpu.periode_paie_id', $periode->id)
            ->get([
                'u.id as user_id',
                'u.matricule',
                'v.nom_variable',
                'v.type',
                'c.nom_categorie',
                'vpu.montant',
            ]);

        // Transforme en structure employeeData attendue côté front
        // et récupère la liste unique des variables pour compléter payrollVariables si besoin
        $employeeData = [];
        $variables = []; // unique par nom_variable

        foreach ($rows as $r) {
            // ⚠️ Ne renvoie pas les lignes à 0 : elles doivent être considérées "supprimées"
            if ((float)$r->montant <= 0) continue;

            $empKey = (string)$r->user_id; // correspond à ton mapping front
            if (!isset($employeeData[$empKey])) $employeeData[$empKey] = [];
            $employeeData[$empKey][$r->nom_variable] = (float)$r->montant;

            if (!isset($variables[$r->nom_variable])) {
                $variables[$r->nom_variable] = [
                    'name'     => $r->nom_variable,
                    'type'     => $r->type,                   // 'gain' | 'deduction'
                    'category' => $r->nom_categorie ?? '—',
                ];
            }
        }

        return response()->json([
            'data' => [
                'ticket'  => $periode->ticket,
                'period'  => [
                    'start' => $periode->date_debut instanceof \Carbon\Carbon
                        ? $periode->date_debut->toDateString()
                        : (string)$periode->date_debut,
                    'end'   => $periode->date_fin instanceof \Carbon\Carbon
                        ? $periode->date_fin->toDateString()
                        : (string)$periode->date_fin,
                ],
                // map prêt à l’emploi pour ton JS
                'employeeData' => $employeeData,
                // liste des variables présentes sur ce ticket
                'variables'    => array_values($variables),
            ],
        ], 200);
    }

    public function updateBaseSalary(Request $request)
    {
        $request->validate([
            'employee_id' => 'required|exists:users,id', // Assurez-vous d'utiliser le bon champ
            'base_salary' => 'required|numeric|min:0'
        ]);

        $employee = User::where('id', $request->employee_id)->firstOrFail(); // Utilisez le bon champ pour trouver l'employé
        $employee->salairebase = $request->base_salary; // Assurez-vous que le champ est correct
        $employee->save();

        return response()->json(['message' => 'Salaire de base mis à jour avec succès']);
    }
}
