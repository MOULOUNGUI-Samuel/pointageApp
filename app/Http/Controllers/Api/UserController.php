<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;

class UserController extends Controller
{
    /**
     * @OA\Get(
     *      path="/users",
     *      operationId="getUsersList",
     *      tags={"Utilisateurs"},
     *      summary="Obtenir la liste des utilisateurs",
     *      description="Retourne une liste paginée des utilisateurs.",
     *      security={{"ApiKeyAuth":{}}},
     *      @OA\Response(
     *          response=200,
     *          description="Opération réussie",
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="current_page", type="integer", example=1),
     *              @OA\Property(
     *                  property="data",
     *                  type="array",
     *                  @OA\Items(ref="#/components/schemas/User")
     *              ),
     *              @OA\Property(property="first_page_url", type="string", example="http://localhost/api/users?page=1"),
     *              @OA\Property(property="from", type="integer", example=1),
     *              @OA\Property(property="last_page", type="integer", example=5),
     *              @OA\Property(property="last_page_url", type="string", example="http://localhost/api/users?page=5"),
     *              @OA\Property(property="path", type="string", example="http://localhost/api/users"),
     *              @OA\Property(property="per_page", type="integer", example=15),
     *              @OA\Property(property="to", type="integer", example=15),
     *              @OA\Property(property="total", type="integer", example=75)
     *          )
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Non authentifié"
     *      )
     * )
     */
    public function index(): JsonResponse
    {
        // La pagination est cruciale pour les listes pour ne pas surcharger le serveur
        $users = User::paginate(15);
        return response()->json($users);
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
    public function show(User $user): JsonResponse
    {
        // Grâce au "Route Model Binding" de Laravel,
        // si l'ID n'est pas trouvé, Laravel renverra automatiquement une erreur 404.
        return response()->json($user);
    }

    public function __construct()
        {
            $cleApiFournie = request()->header('X-API-KEY');
            $cleApiValide = env('Nedcore_API_KEY');
            if (!$cleApiFournie || $cleApiFournie !== $cleApiValide) {
            abort(401, 'Accès non autorisé.');
            }
        }

        /**
        * API n°1: Lister les inscriptions.
        * La réponse ne contient que les données, pas de token CSRF.
        */
        public function UserInfo()
        {
        $utilisateurs = User::select('id', 'telephone', 'nom', 'prenom', 'email', 'created_at')
        ->latest()
        ->paginate(25);

        return response()->json($utilisateurs);
        }
}
