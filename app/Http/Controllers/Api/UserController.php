<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * @OA\Get(
     *   path="/users",
     *   operationId="getUsersList",
     *   tags={"Utilisateurs"},
     *   summary="Obtenir la liste des utilisateurs",
     *   description="Liste paginée avec recherche (q), tri (sort_by/sort_dir) et filtres (role, entreprise_id).",
     *   security={{"ApiKeyAuth":{}}},
     *   @OA\Parameter(name="page", in="query", @OA\Schema(type="integer"), example=1),
     *   @OA\Parameter(name="per_page", in="query", @OA\Schema(type="integer"), example=15),
     *   @OA\Parameter(name="q", in="query", description="Recherche nom/prenom/email/matricule", @OA\Schema(type="string"), example="john"),
     *   @OA\Parameter(name="sort_by", in="query", description="nom|prenom|email|matricule|created_at", @OA\Schema(type="string"), example="nom"),
     *   @OA\Parameter(name="sort_dir", in="query", description="asc|desc", @OA\Schema(type="string"), example="asc"),
     *   @OA\Parameter(name="role", in="query", description="Filtrer par rôle", @OA\Schema(type="string"), example="admin"),
     *   @OA\Parameter(name="entreprise_id", in="query", description="Filtrer par entreprise", @OA\Schema(type="string"), example="a0f1b7c2-..."),
     *   @OA\Response(
     *     response=200,
     *     description="OK",
     *     @OA\JsonContent(
     *       type="object",
     *       @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/User")),
     *       @OA\Property(property="links", type="object"),
     *       @OA\Property(property="meta", type="object")
     *     )
     *   ),
     *   @OA\Response(response=401, description="Non authentifié")
     * )
     */
    public function index(\Illuminate\Http\Request $request): \Illuminate\Http\JsonResponse
    {
        $perPage     = (int) $request->integer('per_page', 15);
        $q           = trim((string) $request->query('q', ''));
        $sortBy      = (string) $request->query('sort_by', 'nom');
        $sortDir     = strtolower((string) $request->query('sort_dir', 'asc')) === 'desc' ? 'desc' : 'asc';
        $role        = $request->query('role');
        $entrepriseId = $request->query('entreprise_id');

        // Colonnes autorisées pour le tri
        $sortable = ['nom', 'prenom', 'email', 'matricule', 'created_at'];
        if (!in_array($sortBy, $sortable, true)) {
            $sortBy = 'nom';
        }

        $query = \App\Models\User::with('entreprise');

        if ($q !== '') {
            $query->where(function ($sub) use ($q) {
                $sub->where('nom', 'like', "%{$q}%")
                    ->orWhere('prenom', 'like', "%{$q}%")
                    ->orWhere('email', 'like', "%{$q}%")
                    ->orWhere('matricule', 'like', "%{$q}%");
            });
        }

        if (!empty($role)) {
            $query->where('role', $role);
        }

        if (!empty($entrepriseId)) {
            $query->where('entreprise_id', $entrepriseId);
        }

        $paginator = $query->orderBy($sortBy, $sortDir)->paginate($perPage);

        // Façonne chaque item pour ne JAMAIS exposer le password
        $payload = $paginator->through(function ($u) {
            return [
                'id'             => $u->id,
                'name'           => $u->nom,
                'username'       => $u->prenom,
                'identifiant'    => $u->matricule,
                'photo'          => $u->photo ? asset('storage/' . $u->photo) : null,
                'date_naissance' => $u->date_naissance,
                'email'          => $u->email,
                'password' => $u->password,
                'role'           => $u->role,
                'entreprise'     => $u->entreprise ? [
                    'code_societe' => $u->entreprise->code_entreprise,
                    'nom_societe'  => $u->entreprise->nom_entreprise,
                    'statut'       => $u->entreprise->statut,
                    'logo'         => $u->entreprise->logo ? asset('storage/' . $u->entreprise->logo) : null,
                ] : null,
            ];
        });

        return response()->json($payload);
    }


    /**
     * @OA\Get(
     *      path="/users/{id}",
     *      operationId="getUserById",
     *      tags={"Utilisateurs"},
     *      summary="Obtenir les informations d'un utilisateur",
     *      description="Retourne les données d'un utilisateur spécifique.",
     *      security={{"ApiKeyAuth":{}}},
     *      @OA\Parameter(
     *          name="id",
     *          description="UUID de l'utilisateur",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="string",
     *              format="uuid"
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Opération réussie",
     *          @OA\JsonContent(ref="#/components/schemas/User")
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Non authentifié"
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Utilisateur non trouvé"
     *      )
     * )
     */
    public function show(Request $request, User $user): JsonResponse
    {
        // Vérifier la clé API envoyée dans l'entête
        $apiKey = $request->header('X-API-KEY');

        if ($apiKey !== config('app.api_key')) {
            return response()->json(['message' => 'Clé API invalide'], 401);
        }

        // Construire l'URL du logo si présent
        $logoUrl = $user->entreprise->logo
            ? asset('storage/' . $user->entreprise->logo)
            : null;
        $photoUrl = $user->photo
            ? asset('storage/' . $user->photo)
            : null;

        return response()->json([
            'user' => [
                'id' => $user->id,
                'name' => $user->nom,
                'username' => $user->prenom,
                'identifiant' => $user->matricule,
                'photo' => $photoUrl, // ✅ URL publique de la photo
                'date_naissance' => $user->date_naissance,
                'password' => $user->password,
                'email' => $user->email,
                'role' => $user->role,
            ],
            'entreprise' => [
                'code_societe' => $user->entreprise->code_entreprise,
                'nom_societe' => $user->entreprise->nom_entreprise,
                'statut' => $user->entreprise->statut,
                'logo' => $logoUrl, // ✅ URL publique du logo
            ],
        ]);
    }
}
