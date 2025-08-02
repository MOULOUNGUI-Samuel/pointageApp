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
                'email' => $user->email,
                'role' => $user->role,
            ],
            'entreprise' => [
                'code_societe' => $user->entreprise->code_entreprise,
                'nom_societe' => $user->entreprise->nom_entreprise,
                'email' => $user->entreprise->email,
                'telephone' => $user->entreprise->telephone,
                'statut' => $user->entreprise->statut,
                'logo' => $logoUrl, // ✅ URL publique du logo
                'adresse' => $user->entreprise->adresse,
            ],
        ]);
    }
}
