<?php

namespace App\Services;

use App\Models\ConformitySubmission;
use App\Models\Item;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Http;   // ⬅️ AJOUT
use OpenAI\Laravel\Facades\OpenAI;

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

            $response = OpenAI::chat()->create([
                'model' => 'gpt-4-turbo-preview',
                'messages' => [
                    [
                        'role' => 'system',
                        'content' => "Tu génères des commentaires d'approbation professionnels et encourageants pour les soumissions de conformité validées."
                    ],
                    [
                        'role' => 'user',
                        'content' => $prompt
                    ]
                ],
                'temperature' => 0.5,
                'max_tokens' => 300,
            ]);

            return $response->choices[0]->message->content;
        } catch (\Exception $e) {
            Log::error('[ValidationIAService] Erreur génération commentaire', [
                'submission_id' => $submission->id,
                'error' => $e->getMessage()
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

            $response = OpenAI::chat()->create([
                'model' => 'gpt-4-turbo-preview',
                'messages' => [
                    [
                        'role' => 'system',
                        'content' => "Tu génères des commentaires de rejet constructifs et détaillés pour aider l'entreprise à corriger sa soumission de conformité."
                    ],
                    [
                        'role' => 'user',
                        'content' => $prompt
                    ]
                ],
                'temperature' => 0.4,
                'max_tokens' => 500,
            ]);

            return $response->choices[0]->message->content;
        } catch (\Exception $e) {
            Log::error('[ValidationIAService] Erreur génération commentaire rejet', [
                'submission_id' => $submission->id,
                'error' => $e->getMessage()
            ]);

            return $reasonFromValidator ?? "Veuillez corriger les problèmes identifiés et soumettre à nouveau.";
        }
    }

    /**
     * Construit le prompt d'analyse de la soumission
     */
    private function construirePromptAnalyse(ConformitySubmission $submission): string
    {
        $item = $submission->item;
        $entreprise = $submission->entreprise;
        $periode = $submission->periode;

        // Récupérer les données soumises
        $answersData = [];
        foreach ($submission->answers as $answer) {
            $data = [
                'type' => $answer->kind
            ];

            if ($answer->kind === 'texte') {
                $data['contenu'] = $answer->value_text;
            } elseif ($answer->kind === 'documents') {
                $data['fichier'] = basename($answer->file_path);
                // On ne peut pas analyser le contenu du fichier ici
            } elseif (in_array($answer->kind, ['liste', 'checkbox'])) {
                $data['selections'] = $answer->selectedMany();
                $data['labels'] = $answer->selectedLabels();
            }

            $answersData[] = $data;
        }

        $periodeInfo = $periode
            ? "Période : {$periode->debut_periode->format('d/m/Y')} - {$periode->fin_periode->format('d/m/Y')}"
            : "Pas de période définie";

        $dataJson = json_encode($answersData, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

        return <<<PROMPT
CONTEXTE DE LA SOUMISSION :
- Entreprise : {$entreprise->nom}
- Secteur : {$entreprise->secteur}
- Item : {$item->nom_item}
- Description : {$item->description}
- Type : {$item->type}
- {$periodeInfo}
- Date de soumission : {$submission->submitted_at->format('d/m/Y H:i')}

DONNÉES SOUMISES :
{$dataJson}

MISSION :
Analyse cette soumission de conformité et fournis une recommandation objective.

CRITÈRES D'ÉVALUATION :
1. Complétude : Les données sont-elles complètes ?
2. Pertinence : Les données correspondent-elles à ce qui est attendu ?
3. Qualité : Les données sont-elles de qualité suffisante ?
4. Cohérence : Y a-t-il des incohérences ou contradictions ?
5. Conformité : Les données respectent-elles les exigences réglementaires probables ?

FORMAT DE RÉPONSE JSON :
{
  "recommandation": "approuver|rejeter|approuver_avec_reserve",
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
- Sois objectif et factuel
- Base-toi uniquement sur les données fournies
- Si des informations manquent, signale-le
- Réponds UNIQUEMENT avec le JSON, sans texte avant ou après
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
