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
        // Toujours retourner du JSON (évite redirections Blade)
        $validator = Validator::make($request->all(), [
            'nom_variable' => ['required', 'string', 'max:150'],
            'type'         => ['required', 'in:gain,deduction'],
            'categorie_id' => ['required', 'uuid', 'exists:categories,id'],
        ], [
            'nom_variable.required' => 'Le nom de la variable est requis.',
            'type.in'               => 'Type invalide.',
            'categorie_id.required' => 'La catégorie est requise.',
            'categorie_id.exists'   => 'Catégorie introuvable.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation error',
                'errors'  => $validator->errors(),
            ], 422);
        }

        $data = $validator->validated();

        $variable = Variable::create([
            'nom_variable' => $data['nom_variable'],
            'type'         => $data['type'],
            'categorie_id' => $data['categorie_id'],
            'statut'       => true,
        ])->load('categorie'); // pour renvoyer le nom de la catégorie


        return response()->json(['data' => $variable, 'message' => 'Variable créée avec succès'], 201);
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
                'period.start'   => ['bail','required','date'],
                'period.end'     => ['bail','required','date','after_or_equal:period.start'],
                'employeeData'   => ['bail','required','array'],
                'employeeData.*' => ['bail','array'],
                'replace_ticket' => ['nullable','string','max:20'],
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
            $users = User::whereIn('matricule', $employeeKeys)
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
}
