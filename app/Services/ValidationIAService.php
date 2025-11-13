<?php

namespace App\Services;

use App\Models\ConformitySubmission;
use App\Models\Item;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Http;   // ⬅️ AJOUT
use OpenAI\Laravel\Facades\OpenAI;
use Illuminate\Support\Str;

class ValidationIAService
{
    /**
     * Analyse une soumission et fournit une recommandation de validation
     * 
     * @param ConformitySubmission $submission
     * @return array
     */
    public function analyserSoumission(ConformitySubmission $submission): array
    {
        try {
            $prompt = $this->construirePromptAnalyse($submission);

            // ⬇️ NOUVEL APPEL HTTP À L’API OPENAI
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . config('services.openai.key'),
                'Content-Type'  => 'application/json',
            ])
                ->timeout(60)
                ->post('https://api.openai.com/v1/chat/completions', [
                    'model' => 'gpt-4o',
                    'messages' => [
                        [
                            'role' => 'system',
                            'content' => "Tu es un expert en audit de conformité. 
                            Tu analyses les soumissions de conformité et fournis des recommandations objectives 
                            pour aider les validateurs dans leur décision.
                            
                            Tu DOIS répondre UNIQUEMENT avec du JSON valide,
                            SANS texte explicatif, SANS balises Markdown, SANS ```."
                        ],
                        [
                            'role' => 'user',
                            'content' => $prompt,
                        ],
                    ],
                    'temperature' => 0.2,    // faible pour rester objectif
                    'max_tokens'  => 2000,
                    // ⬇️ on demande explicitement un JSON
                    'response_format' => [
                        'type' => 'json_object',
                    ],
                ]);

            // 🚨 Vérification HTTP
            if ($response->failed()) {
                throw new \Exception('Erreur API OpenAI : ' . $response->body());
            }

            // ✅ Récupération propre du contenu
            $data    = $response->json();
            $content = $data['choices'][0]['message']['content'] ?? null;

            if (! $content) {
                throw new \Exception("Réponse OpenAI invalide ou vide.");
            }

            // (optionnel mais utile pour debug)
            Log::info('[ValidationIAService] Réponse brute analyse IA', [
                'submission_id' => $submission->id,
                'content'       => $content,
            ]);

            // Parser la réponse JSON
            $analyse = json_decode($content, true);

            if (json_last_error() !== JSON_ERROR_NONE || ! $analyse) {
                throw new \Exception("Impossible de parser la réponse de l'IA : " . json_last_error_msg());
            }

            return [
                'success'      => true,
                'analyse'      => $analyse,
                'raw_response' => $content,
            ];
        } catch (\Exception $e) {
            Log::error('[ValidationIAService] Erreur analyse soumission', [
                'submission_id' => $submission->id,
                'error'         => $e->getMessage(),
            ]);

            return [
                'success' => false,
                'error'   => $e->getMessage(),
                'analyse' => $this->getFallbackAnalyse(),
            ];
        }
    }


    /**
     * Génère un commentaire d'approbation automatique basé sur l'analyse
     * 
     * @param ConformitySubmission $submission
     * @param array $analysisResults
     * @return string
     */
    public function genererCommentaireApprobation(
        ConformitySubmission $submission,
        array $analysisResults
    ): string {
        try {
            $prompt = $this->construirePromptCommentaireApprobation($submission, $analysisResults);

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . config('services.openai.key'),
                'Content-Type'  => 'application/json',
            ])
                ->timeout(60)
                ->post('https://api.openai.com/v1/chat/completions', [
                    'model' => 'gpt-4o',
                    'messages' => [
                        [
                            'role' => 'system',
                            'content' => "Tu génères des commentaires d'approbation professionnels 
                            et encourageants pour les soumissions de conformité validées.
                            Réponds avec un texte clair, sans balises Markdown."
                        ],
                        [
                            'role' => 'user',
                            'content' => $prompt,
                        ],
                    ],
                    'temperature' => 0.5,
                    'max_tokens'  => 300,
                ]);

            if ($response->failed()) {
                throw new \Exception('Erreur API OpenAI : ' . $response->body());
            }

            $data    = $response->json();
            $content = $data['choices'][0]['message']['content'] ?? null;

            if (! $content) {
                throw new \Exception("Réponse OpenAI invalide ou vide.");
            }

            return trim($content);
        } catch (\Exception $e) {
            Log::error('[ValidationIAService] Erreur génération commentaire', [
                'submission_id' => $submission->id,
                'error'         => $e->getMessage(),
            ]);

            return "Soumission approuvée après vérification.";
        }
    }


    /**
     * Génère un commentaire de rejet avec suggestions d'amélioration
     * 
     * @param ConformitySubmission $submission
     * @param array $analysisResults
     * @param string|null $reasonFromValidator
     * @return string
     */
    public function genererCommentaireRejet(
        ConformitySubmission $submission,
        array $analysisResults,
        ?string $reasonFromValidator = null
    ): string {
        try {
            $prompt = $this->construirePromptCommentaireRejet($submission, $analysisResults, $reasonFromValidator);

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . config('services.openai.key'),
                'Content-Type'  => 'application/json',
            ])
                ->timeout(60)
                ->post('https://api.openai.com/v1/chat/completions', [
                    'model' => 'gpt-4o',
                    'messages' => [
                        [
                            'role' => 'system',
                            'content' => "Tu génères des commentaires de rejet constructifs et détaillés 
                            pour aider l'entreprise à corriger sa soumission de conformité.
                            Réponds avec un texte clair, sans balises Markdown."
                        ],
                        [
                            'role' => 'user',
                            'content' => $prompt,
                        ],
                    ],
                    'temperature' => 0.4,
                    'max_tokens'  => 500,
                ]);

            if ($response->failed()) {
                throw new \Exception('Erreur API OpenAI : ' . $response->body());
            }

            $data    = $response->json();
            $content = $data['choices'][0]['message']['content'] ?? null;

            if (! $content) {
                throw new \Exception("Réponse OpenAI invalide ou vide.");
            }

            return trim($content);
        } catch (\Exception $e) {
            Log::error('[ValidationIAService] Erreur génération commentaire rejet', [
                'submission_id' => $submission->id,
                'error'         => $e->getMessage(),
            ]);

            return $reasonFromValidator ?? "Veuillez corriger les problèmes identifiés et soumettre à nouveau.";
        }
    }


    /**
     * Construit le prompt d'analyse de la soumission
     */
    private function construirePromptAnalyse(ConformitySubmission $submission): string
    {
        $item       = $submission->item;
        $entreprise = $submission->entreprise;
        $periode    = $submission->periode;

        // Récupérer les données soumises
        $answersData = [];
        foreach ($submission->answers as $answer) {
            $data = [
                'type' => $answer->kind,
            ];

            if ($answer->kind === 'texte') {
                $data['contenu'] = $answer->value_text;
            } elseif ($answer->kind === 'documents' || $answer->kind === 'file') {
                $data['document_fourni'] = ! empty($answer->file_path);
                $data['nom_fichier']     = $answer->file_path ? basename($answer->file_path) : null;

                $data['nom_fichier']     = $answer->file_path ? basename($answer->file_path) : null;

                if (! empty($answer->extracted_text)) {
                    // on limite un peu pour éviter des prompts énormes
                    $data['contenu_document'] = Str::limit($answer->extracted_text, 8000, "\n... (contenu tronqué)");
                    $data['contenu_disponible'] = true;
                } else {
                    $data['contenu_document']   = null;
                    $data['contenu_disponible'] = false;
                }
            } elseif (in_array($answer->kind, ['liste', 'checkbox'], true)) {
                $data['selections'] = $answer->selectedMany();
                $data['labels']     = $answer->selectedLabels();
            }

            $answersData[] = $data;
        }

        $periodeInfo = $periode
            ? "Période : {$periode->debut_periode->format('d/m/Y')} - {$periode->fin_periode->format('d/m/Y')}"
            : "Pas de période définie";

        $dataJson = json_encode($answersData, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

        $type = $item->type;

        /**
         * 1) Bloc spécifique selon le TYPE d’item
         */
        $typeSpecificRules = '';

        if ($type === 'texte') {
            $typeSpecificRules = <<<TEXTE
    RÈGLES SPÉCIFIQUES POUR UN ITEM DE TYPE TEXTE :
    - Tu dois évaluer la clarté, la complétude et la pertinence du texte fourni pour cet item.
    - Tu ne dois pas parler de documents ou de fichiers pour ce type d’item.
    - Si le texte est vide ou très insuffisant, tu peux considérer cela comme un problème important.
    TEXTE;
        } elseif ($type === 'liste' || $type === 'checkbox') {
            $typeSpecificRules = <<<LISTE
    RÈGLES SPÉCIFIQUES POUR UN ITEM DE TYPE LISTE / CHECKBOX :
    - Tu dois analyser uniquement les options sélectionnées dans les données fournies.
    - Ne parle JAMAIS de documents ou de fichiers manquants pour ce type d’item.
    - Tu peux signaler comme problème :
      - Aucune option sélectionnée alors qu’un choix est manifestement attendu.
      - Un choix incohérent par rapport au contexte de l’item (si les données le permettent).
    LISTE;
        } elseif ($type === 'documents' || $type === 'file') {
            $typeSpecificRules = <<<DOCS
    RÈGLES SPÉCIFIQUES POUR UN ITEM DE TYPE DOCUMENT / FICHIER :
    - Tu n'as PAS accès directement au fichier, seulement aux données fournies dans le JSON.
    - Si un champ comme "contenu_document", "resume_document" ou "contenu" est présent, tu DOIS l'utiliser
      pour juger si le document est pertinent par rapport à l'item de conformité.
    - Si aucun contenu texte du document n'est fourni dans les données, tu ne dois pas faire comme si tu avais lu le fichier.
    - Le simple fait qu'un document soit indiqué comme fourni dans les données (ex: "document_fourni": true)
      signifie que l'entreprise a bien transmis un document.
    - Tu NE DOIS PAS pénaliser la soumission parce que le nom du fichier est peu descriptif
      (par exemple "scan1.pdf" ne doit pas être considéré comme un point faible ou un problème majeur).
    - Tu peux considérer comme problème majeur UNIQUEMENT si :
      - Les données indiquent clairement qu'aucun document n'est fourni alors qu'un document est manifestement attendu pour cet item, OU
      - Les données fournies indiquent explicitement que le document est vide, erroné ou totalement hors sujet.
    DOCS;
        } else {
            // Sécurité pour tout autre type : on interdit de parler de "document manquant"
            $typeSpecificRules = <<<AUTRE
    NOTE IMPORTANTE :
    - Cet item n'est PAS un item de type "documents" ou "file".
    - Tu ne dois jamais indiquer qu'un document manque ou devrait être fourni pour cet item.
    - Concentre-toi uniquement sur les données effectivement présentes dans le JSON (texte, options, etc.).
    AUTRE;
        }

        /**
         * 2) Bloc général (commum à tous les types)
         */
        return <<<PROMPT
    CONTEXTE DE LA SOUMISSION :
    - Entreprise : {$entreprise->nom}
    - Secteur : {$entreprise->secteur}
    - Item : {$item->nom_item}
    - Description : {$item->description}
    - Type : {$item->type}
    - {$periodeInfo}
    - Date de soumission : {$submission->submitted_at->format('d/m/Y H:i')}
    
    DONNÉES SOUMISES (structure JSON déjà préparée) :
    {$dataJson}
    
    {$typeSpecificRules}
    
    RÈGLES GÉNÉRALES D'ÉVALUATION (tous types confondus) :
    1. Complétude : Les données sont-elles présentes (texte, options sélectionnées, document indiqué, etc.) ?
    2. Pertinence : Au vu des informations disponibles dans le JSON, les données semblent-elles respecter le cadre de l'item de conformité ?
    3. Qualité : Le contenu est-il exploitable et suffisamment précis quand il est visible ?
    4. Cohérence : Y a-t-il des incohérences ou contradictions dans les données visibles ?
    5. Conformité : Les informations semblent-elles respecter les exigences probables de conformité ?
    
    FORMAT DE RÉPONSE JSON ATTENDU :
    {
      "recommandation": "approuver" | "rejeter" | "approuver_avec_reserve",
      "score_global": 0-100,
      "scores_details": {
        "completude": 0-100,
        "pertinence": 0-100,
        "qualite": 0-100,
        "coherence": 0-100,
        "conformite": 0-100
      },
      "points_forts": ["Point fort 1", "Point fort 2"],
      "points_faibles": ["Point faible 1", "Point faible 2"],
      "problemes_majeurs": ["Problème 1", "Problème 2"],
      "suggestions_amelioration": ["Suggestion 1", "Suggestion 2"],
      "resume_analyse": "Résumé en 2-3 phrases de l'analyse globale",
      "justification_recommandation": "Explication claire de la recommandation"
    }
    
    IMPORTANT :
    - Sois objectif et factuel.
    - Base-toi uniquement sur les données disponibles dans le JSON (par exemple "contenu", "contenu_document", "resume_document", "selections", etc.).
    - Pour les items de type documents/fichiers, tu dois juger la pertinence du CONTENU uniquement si un texte représentant ce contenu
      est présent dans les données.
    - Ne crée JAMAIS de points faibles ou problèmes majeurs basés uniquement sur le fait que le contenu réel d'un fichier
      n'est pas accessible depuis les données, sauf si les données indiquent explicitement un problème.
    - Si des informations importantes manquent réellement (texte vide, aucune option sélectionnée pour une liste, document explicitement manquant pour un item de type documents, etc.),
      tu peux le considérer comme un point faible ou un problème majeur.
    - Réponds UNIQUEMENT avec le JSON demandé, sans aucun texte avant ou après.
    PROMPT;
    }



    /**
     * Construit le prompt pour un commentaire d'approbation
     */
    private function construirePromptCommentaireApprobation(
        ConformitySubmission $submission,
        array $analysisResults
    ): string {
        $pointsForts = isset($analysisResults['points_forts'])
            ? implode(', ', $analysisResults['points_forts'])
            : 'Données conformes';

        $score = $analysisResults['score_global'] ?? 85;

        return <<<PROMPT
Génère un commentaire d'approbation professionnel et encourageant pour cette soumission de conformité.

CONTEXTE :
- Item : {$submission->item->nom_item}
- Score obtenu : {$score}/100
- Points forts identifiés : {$pointsForts}

INSTRUCTIONS :
- Commence par une félicitation
- Mentionne 1-2 points forts spécifiques
- Reste concis (2-3 phrases maximum)
- Ton professionnel et positif

Exemple : "Félicitations, votre soumission est approuvée. Les informations fournies sont complètes et bien structurées. Votre rigueur dans la présentation des données est appréciée."

Génère maintenant le commentaire (sans guillemets, juste le texte) :
PROMPT;
    }

    /**
     * Construit le prompt pour un commentaire de rejet
     */
    private function construirePromptCommentaireRejet(
        ConformitySubmission $submission,
        array $analysisResults,
        ?string $reasonFromValidator
    ): string {
        $problemes = isset($analysisResults['problemes_majeurs'])
            ? implode(', ', $analysisResults['problemes_majeurs'])
            : 'Problèmes de conformité détectés';

        $suggestions = isset($analysisResults['suggestions_amelioration'])
            ? implode(', ', $analysisResults['suggestions_amelioration'])
            : '';

        $reasonPart = $reasonFromValidator
            ? "\n- Raison du validateur : {$reasonFromValidator}"
            : "";

        return <<<PROMPT
Génère un commentaire de rejet constructif et détaillé pour cette soumission de conformité.

CONTEXTE :
- Item : {$submission->item->nom_item}
- Problèmes identifiés : {$problemes}
- Suggestions : {$suggestions}{$reasonPart}

INSTRUCTIONS :
- Sois constructif et respectueux
- Explique clairement les problèmes
- Fournis des pistes d'amélioration concrètes
- Encourage à resoumettre après correction
- 3-5 phrases maximum
- Structure claire avec points numérotés si plusieurs problèmes

Génère maintenant le commentaire (sans guillemets, juste le texte) :
PROMPT;
    }

    /**
     * Analyse de secours en cas d'erreur IA
     */
    private function getFallbackAnalyse(): array
    {
        return [
            'recommandation' => 'approuver_avec_reserve',
            'score_global' => 70,
            'scores_details' => [
                'completude' => 70,
                'pertinence' => 70,
                'qualite' => 70,
                'coherence' => 70,
                'conformite' => 70
            ],
            'points_forts' => ['Soumission reçue'],
            'points_faibles' => ['Analyse IA indisponible'],
            'problemes_majeurs' => [],
            'suggestions_amelioration' => ['Veuillez vérifier manuellement'],
            'resume_analyse' => 'L\'analyse automatique n\'est pas disponible. Une vérification manuelle est recommandée.',
            'justification_recommandation' => 'Système d\'analyse temporairement indisponible.'
        ];
    }
}
