<?php

namespace App\OpenApi\Schemas;

/**
 * @OA\Schema(
 *     schema="User",
 *     type="object",
 *     title="User",
 *     description="Modèle de données d'un utilisateur",
 *     @OA\Property(property="id", type="string", format="uuid", readOnly=true, example="9a7a6c11-a27b-4638-a281-3ce9d49e3e44"),
 *     @OA\Property(property="entreprise_id", type="char", example=1),
 *     @OA\Property(property="nom", type="string", example="Durand"),
 *     @OA\Property(property="prenom", type="string", example="Marie"),
 *     @OA\Property(property="matricule", type="string", example="M012345"),
 *     @OA\Property(property="email", type="string", format="email", example="marie.durand@example.com"),
 *     @OA\Property(property="service_id", type="char", example=3),
 *     @OA\Property(property="role_id", type="char", example=2),
 *     @OA\Property(property="lieu_naissance", type="string", example="Paris"),
 *     @OA\Property(property="nationalite", type="string", example="Française"),
 *     @OA\Property(property="numero_securite_sociale", type="string", example="1 85 01 75 123 456"),
 *     @OA\Property(property="etat_civil", type="string", example="Célibataire"),
 *     @OA\Property(property="nombre_enfant", type="integer", example=0),
 *     @OA\Property(property="photo", type="string", format="uri", example="http://example.com/path/to/photo.jpg"),
 *     @OA\Property(property="adresse", type="string", example="123 Rue de la République"),
 *     @OA\Property(property="code_postal", type="string", example="75001"),
 *     @OA\Property(property="superieur_hierarchique", type="integer", description="ID de l'utilisateur supérieur hiérarchique", example=10),
 *     @OA\Property(property="niveau_etude", type="string", example="Bac +5"),
 *     @OA\Property(property="competence", type="string", example="Gestion de projet, PHP, Laravel"),
 *     @OA\Property(property="ville_id", type="char", example=1),
 *     @OA\Property(property="pays_id", type="char", example=1),
 *     @OA\Property(property="categorie_professionel_id", type="integer", example=4),
 *     @OA\Property(property="type_contrat", type="string", example="CDI"),
 *     @OA\Property(property="salaire", type="number", format="float", example=45000.00),
 *     @OA\Property(property="mode_paiement", type="string", example="Virement bancaire"),
 *     @OA\Property(property="iban", type="string", example="FR7630006000011234567890189"),
 *     @OA\Property(property="bic", type="string", example="SOGEFRPP"),
 *     @OA\Property(property="telephone", type="string", example="+33 6 12 34 56 78"),
 *     @OA\Property(property="fonction", type="string", example="Développeur Senior"),
 *     @OA\Property(property="cv", type="string", format="uri", example="http://example.com/path/to/cv.pdf"),
 *     @OA\Property(property="statut", type="string", description="Statut de l'utilisateur (ex: active, inactive)", example="active"),
 *     @OA\Property(property="created_at", type="string", format="date-time", readOnly=true),
 *     @OA\Property(property="updated_at", type="string", format="date-time", readOnly=true)
 * )
 */
class UserSchema
{
}