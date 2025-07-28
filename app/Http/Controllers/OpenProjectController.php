<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use OpenAI\Factory; // <--- VÉRIFIEZ QUE CETTE LIGNE EST PRÉSENTE !
use App\Services\OpenProjectService;
use Illuminate\Http\JsonResponse; // Important
use Illuminate\Http\Client\RequestException; // Important pour gérer les erreurs API

class OpenProjectController extends Controller
{
    //
    private $openProjectUrl = 'https://tache.groupenedco.com';
    /**
     * Génère des tâches via l'API OpenAI et les crée dans OpenProject.
     */
    public function fetchProjects()
    {
        $user = Auth::user();
        $token = $user->openproject_api_token;

        if (!$token) {
            return response()->json(['error' => 'Jeton API OpenProject non configuré.'], 400);
        }

        $response = Http::withBasicAuth('apikey', $token)
            ->get("{$this->openProjectUrl}/api/v3/projects");

        if ($response->failed()) {
            return response()->json(['error' => 'Impossible de récupérer les projets. Vérifiez le jeton API.'], $response->status());
        }

        return $response->json();
    }

    public function generateAndCreateTasks(Request $request)
    {
        $validated = $request->validate([
            'project' => 'required|string',
            'prompt' => 'required|string|min:10',
            'nombre' => 'required|integer|min:1|max:20',
        ]);

        $user = Auth::user();
        $token = $user->openproject_api_token;
        // Récupération de la clé depuis config/services.php
        $openAiKey = config('services.openai.key');

        if (!$token || !$openAiKey) {
            return response()->json(['error' => 'Configuration API manquante côté serveur.'], 500);
        }

        // 1. Générer les tâches avec OpenAI
        try {
            // Grâce à "use OpenAI\Factory;", ceci est maintenant valide
            $client = (new \OpenAI\Factory())
                ->withApiKey($openAiKey)
                ->make();

            $response = $client->chat()->create([
                'model' => 'gpt-4',
                'messages' => [[
                    'role' => 'user',
                    'content' => "À partir de cette description :\n\"{$validated['prompt']}\"\nDonne exactement {$validated['nombre']} tâches simples, chacune avec un titre et une description, dans ce format :\n- Titre : ...\n  Description : ..."
                ]],
                'temperature' => 0.5,
            ]);
            $rawText = $response->choices[0]->message->content;
        } catch (\Exception $e) {
            // On renvoie le message d'erreur réel pour un meilleur débogage
            return response()->json(['error' => 'Erreur côté serveur: ' . $e->getMessage()], 500);
        }

        // Le reste du code est inchangé...
        $taches = $this->parseAiResponse($rawText, $validated['nombre']);

        $results = [];
        foreach ($taches as $tache) {
            $results[] = $this->createWorkPackage($token, $validated['project'], $tache['titre'], $tache['description']);
        }

        return response()->json($results);
    }

    /**
     * Parse la réponse textuelle de l'IA en un tableau de tâches.
     */
    private function parseAiResponse(string $text, int $limit): array
    {
        $taches = [];
        foreach (explode("\n", $text) as $line) {
            if (preg_match('/^- Titre ?: (.+)/i', $line, $match)) {
                $taches[] = ['titre' => trim($match[1]), 'description' => ''];
            } elseif (preg_match('/^\s*Description ?: (.+)/i', $line, $match) && !empty($taches)) {
                $taches[count($taches) - 1]['description'] = trim($match[1]);
            }
        }
        return array_slice($taches, 0, $limit);
    }

    /**
     * Crée un "work package" (tâche) dans un projet OpenProject.
     */
    /**
     * Crée un "work package" (tâche) dans un projet OpenProject.
     * Version corrigée qui trouve dynamiquement le bon type de tâche.
     */
    private function createWorkPackage(string $token, string $projectIdentifier, string $titre, string $description): array
    {
        // --- NOUVELLE ÉTAPE 1 : Récupérer les détails du projet pour trouver ses types de tâches ---
        $projectResponse = Http::withBasicAuth('apikey', $token)
            ->get("{$this->openProjectUrl}/api/v3/projects/{$projectIdentifier}");

        if ($projectResponse->failed()) {
            return ['ok' => false, 'titre' => $titre, 'message' => 'Projet introuvable (' . $projectResponse->status() . ')'];
        }

        // --- NOUVELLE ÉTAPE 2 : Chercher l'URL du type "Tâche" (ou "Task") ---
        $typesLink = $projectResponse->json()['_links']['types']['href'] ?? null;
        if (!$typesLink) {
            return ['ok' => false, 'titre' => $titre, 'message' => 'Impossible de trouver les types de tâches pour ce projet.'];
        }

        $typesResponse = Http::withBasicAuth('apikey', $token)->get("{$this->openProjectUrl}{$typesLink}");
        if ($typesResponse->failed()) {
            return ['ok' => false, 'titre' => $titre, 'message' => 'Impossible de charger les types de tâches.'];
        }

        $taskTypeHref = null;
        foreach ($typesResponse->json()['_embedded']['elements'] as $type) {
            // Cherche le type qui s'appelle "Tâche" ou "Task" (pour couvrir le français et l'anglais)
            if (strtolower($type['name']) === 'tâche' || strtolower($type['name']) === 'task') {
                $taskTypeHref = $type['_links']['self']['href'];
                break;
            }
        }

        // Si après la boucle on n'a pas trouvé de type "Tâche", on prend le premier de la liste par défaut.
        if (!$taskTypeHref && !empty($typesResponse->json()['_embedded']['elements'])) {
            $taskTypeHref = $typesResponse->json()['_embedded']['elements'][0]['_links']['self']['href'];
        }

        if (!$taskTypeHref) {
            return ['ok' => false, 'titre' => $titre, 'message' => 'Aucun type de tâche valide trouvé pour ce projet.'];
        }

        // --- ÉTAPE 3 : Construire le payload avec le bon `href` pour le type ---
        $payload = [
            "subject" => $titre,
            "description" => ["raw" => $description],
            "_links" => [
                "project" => ["href" => $projectResponse->json()['_links']['self']['href']],
                "type" => ["href" => $taskTypeHref] // On utilise le lien dynamique trouvé !
            ]
        ];

        $creationResponse = Http::withBasicAuth('apikey', $token)
            ->withBody(json_encode($payload), 'application/json')
            ->post("{$this->openProjectUrl}/api/v3/work_packages");

        return [
            'titre' => $titre,
            'ok' => $creationResponse->successful(),
            'message' => $creationResponse->created() ? 'Créé avec succès' : 'Erreur lors de la création (' . $creationResponse->status() . ')'
        ];
    }



    public function updateApiKey(Request $request)
    {
        $request->validate(
            [
                'openproject_api_token' => ['required', 'string', 'min:40'], // Validez la clé
            ],
        );

        $user = $request->user();
        $user->openproject_api_token = $request->openproject_api_token;
        $user->save();

        return redirect()->back()->with('status', 'Clé API OpenProject mise à jour !');
    }
}
