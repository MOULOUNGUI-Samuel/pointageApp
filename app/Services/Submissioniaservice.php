<?php

namespace App\Services;

use App\Models\Item;
use App\Models\Entreprise;
use App\Models\PeriodeItem;
use Illuminate\Support\Facades\Log;
use OpenAI\Laravel\Facades\OpenAI;
use Illuminate\Support\Facades\Http;

class SubmissionIAService
{
    /**
     * Suggère du contenu pour aider à remplir un item de conformité
     * 
     * @param Item $item
     * @param Entreprise $entreprise
     * @param PeriodeItem|null $periode
     * @param string|null $contexteSupplementaire
     * @return array
     */
    public function suggererContenu(
        Item $item,
        Entreprise $entreprise,
        ?PeriodeItem $periode = null,
        ?string $contexteSupplementaire = null
    ): array {
        try {
            // 🧠 1. Construire le prompt à envoyer à l’IA
            $prompt = $this->construirePromptSuggestion($item, $entreprise, $periode, $contexteSupplementaire);

            // 🛰️ 2. Appel à l’API OpenAI
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . config('services.openai.key'),
                'Content-Type' => 'application/json',
            ])
                ->timeout(60)
                ->post('https://api.openai.com/v1/chat/completions', [
                    'model' => 'gpt-4o',
                    'messages' => [
                        [
                            'role' => 'system',
                            'content' => 'Tu es un expert en conformité et gestion de périodes de validité. 
                                      Tu réponds UNIQUEMENT avec du JSON valide contenant des propositions de périodes,
                                      sans texte explicatif ni balises Markdown.'
                        ],
                        [
                            'role' => 'user',
                            'content' => $prompt,
                        ],
                    ],
                    'temperature' => 0.7,
                    'max_tokens' => 2000,
                ]);

            // 🚨 3. Vérification d’erreur HTTP
            if ($response->failed()) {
                throw new \Exception('Erreur API OpenAI : ' . $response->body());
            }

            // ✅ 4. Récupération propre du contenu
            // ✅ 4. Récupération propre du contenu
            $data = $response->json();

            // Vérifie que la structure est correcte
            $content = $data['choices'][0]['message']['content'] ?? null;

            if (!$content) {
                throw new \Exception("Réponse OpenAI invalide ou vide.");
            }

            // Log pour debug si besoin
            Log::info('[PeriodeIAService] Réponse brute OpenAI', [
                'content' => $content,
            ]);

            // 🔍 5. Nettoyage du contenu avant json_decode

            $clean = trim($content);

            // Cas classique : bloc ```json ... ```
            $clean = preg_replace('/^```json\s*/i', '', $clean);
            $clean = preg_replace('/^```/i', '', $clean);
            $clean = preg_replace('/```$/', '', $clean);
            $clean = trim($clean);

            // Si jamais il y a encore du texte autour, on essaie d'extraire le bloc JSON principal
            $start = strpos($clean, '{');
            $end   = strrpos($clean, '}');

            if ($start !== false && $end !== false && $end > $start) {
                $clean = substr($clean, $start, $end - $start + 1);
            }

            // Deuxième tentative de décodage
            $suggestions = json_decode($clean, true);

            if (json_last_error() !== JSON_ERROR_NONE || ! $suggestions) {
                // Pour t’aider au debug, on log aussi le JSON "nettoyé"
                Log::error('[PeriodeIAService] JSON IA invalide', [
                    'clean'   => $clean,
                    'error'   => json_last_error_msg(),
                ]);

                throw new \Exception("Réponse IA non valide : impossible de parser le JSON.");
            }

            // 🟢 6. Retour normalisé
            return [
                'success'      => true,
                'suggestions'  => $suggestions,
                'raw_response' => $content,
            ];
        } catch (\Exception $e) {
            // 🔴 7. Gestion des erreurs
            Log::error('[PeriodeIAService] Erreur génération suggestions', [
                'item_id' => $item->id ?? null,
                'entreprise_id' => $entreprise->id ?? null,
                'error' => $e->getMessage(),
            ]);

            return [
                'success' => false,
                'error' => $e->getMessage(),
                'suggestions' => $this->getFallbackSuggestions($item),
            ];
        }
    }

    /**
     * Analyse une soumission avant envoi pour détecter des erreurs potentielles
     * 
     * @param Item $item
     * @param array $submissionData
     * @param Entreprise $entreprise
     * @return array
     */
    public function analyserAvantSoumission(
        Item $item,
        array $submissionData,
        Entreprise $entreprise
    ): array {
        try {
            $prompt = $this->construirePromptAnalyse($item, $submissionData, $entreprise);
    
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
                            'content' => "Tu es un expert en conformité. 
                                Tu DOIS répondre UNIQUEMENT avec du JSON valide,
                                SANS texte explicatif, SANS balises Markdown, SANS ```."
                        ],
                        [
                            'role' => 'user',
                            'content' => $prompt,
                        ],
                    ],
                    'temperature' => 0.3,
                    'max_tokens'  => 1000,
                    // ⬇️ on demande explicitement un JSON
                    'response_format' => [
                        'type' => 'json_object',
                    ],
                ]);
    
            // 🚨 1) Vérification HTTP
            if ($response->failed()) {
                throw new \Exception('Erreur API OpenAI : ' . $response->body());
            }
    
            // ✅ 2) Récupération du contenu
            $data = $response->json();
            $content = $data['choices'][0]['message']['content'] ?? null;
    
            if (! $content) {
                throw new \Exception("Réponse OpenAI invalide ou vide.");
            }
    
            // Log brut pour debug si besoin
            Log::info('[SubmissionIAService] Réponse brute analyse IA', [
                'content' => $content,
            ]);
    
            // 🔍 3) Nettoyage avant json_decode
    
            $clean = trim($content);
    
            // Si jamais le modèle a quand même renvoyé ```json ... ```
            $clean = preg_replace('/^```json\s*/i', '', $clean);
            $clean = preg_replace('/^```/i', '', $clean);
            $clean = preg_replace('/```$/', '', $clean);
            $clean = trim($clean);
    
            // On essaie d'extraire la partie entre le premier { et le dernier }
            $start = strpos($clean, '{');
            $end   = strrpos($clean, '}');
    
            if ($start !== false && $end !== false && $end > $start) {
                $clean = substr($clean, $start, $end - $start + 1);
            }
    
            // 🔎 4) Décodage JSON
            $analyse = json_decode($clean, true);
    
            if (json_last_error() !== JSON_ERROR_NONE || ! is_array($analyse)) {
                Log::error('[SubmissionIAService] JSON analyse IA invalide', [
                    'clean' => $clean,
                    'json_error' => json_last_error_msg(),
                ]);
    
                throw new \Exception("Impossible de parser l'analyse de l'IA");
            }
    
            // 🟢 5) Retour normalisé
            return [
                'success'     => true,
                'analyse'     => $analyse,
                'can_submit'  => $analyse['can_submit']  ?? true,
                'warnings'    => $analyse['warnings']    ?? [],
                'suggestions' => $analyse['suggestions'] ?? [],
            ];
    
        } catch (\Exception $e) {
            Log::error('[SubmissionIAService] Erreur analyse pré-soumission', [
                'item_id' => $item->id ?? null,
                'error'   => $e->getMessage(),
            ]);
    
            return [
                'success'     => false,
                'error'       => $e->getMessage(),
                'can_submit'  => true,   // on laisse passer la soumission en cas d’erreur IA
                'warnings'    => [],
                'suggestions' => [],
            ];
        }
    }
    

    /**
     * Construit le prompt pour les suggestions de contenu
     */
    private function construirePromptSuggestion(
        Item $item,
        Entreprise $entreprise,
        ?PeriodeItem $periode,
        ?string $contexteSupplementaire
    ): string {
        $type = $item->type;
        $categorie = $item->CategorieDomaine?->nom_categorie ?? 'Non catégorisé';
        $domaine = $item->CategorieDomaine?->Domaine?->nom_domaine ?? 'Non spécifié';

        $periodeInfo = $periode
            ? "Période de validité : du {$periode->debut_periode->format('d/m/Y')} au {$periode->fin_periode->format('d/m/Y')}"
            : "Aucune période de validité définie";

        $contexteSup = $contexteSupplementaire
            ? "\n\nContexte supplémentaire fourni par l'entreprise :\n{$contexteSupplementaire}"
            : "";

        $prompt = <<<PROMPT
CONTEXTE DE L'ENTREPRISE :
- Nom : {$entreprise->nom}
- Secteur : {$entreprise->secteur}
- Pays : {$entreprise->pays}

ITEM DE CONFORMITÉ À REMPLIR :
- Nom : {$item->nom_item}
- Description : {$item->description}
- Type de données : {$type}
- Catégorie : {$categorie}
- Domaine : {$domaine}
- {$periodeInfo}{$contexteSup}

MISSION :
Fournis des suggestions pertinentes pour aider l'entreprise à remplir cet item correctement.

PROMPT;

        // Ajout selon le type
        if ($type === 'texte') {
            $prompt .= <<<TEXTE

Pour un champ TEXTE, fournis :
1. Un exemple de texte type (2-3 paragraphes)
2. Les points clés à inclure
3. Les erreurs courantes à éviter

FORMAT DE RÉPONSE JSON :
{
  "type": "texte",
  "exemple_texte": "Texte d'exemple complet et professionnel",
  "points_cles": ["Point 1", "Point 2", "Point 3"],
  "erreurs_eviter": ["Erreur 1", "Erreur 2"],
  "conseils": "Conseils généraux pour bien remplir"
}
TEXTE;
        } elseif ($type === 'liste' || $type === 'checkbox') {
            $options = $item->options()->pluck('label')->toArray();
            $optionsStr = implode(', ', $options);

            $prompt .= <<<LISTE

Pour un champ LISTE/CHECKBOX avec les options suivantes : {$optionsStr}

Indique :
1. Quelles options sont les plus pertinentes pour cette entreprise
2. Pourquoi ces options sont recommandées
3. Des mises en garde sur certaines options

FORMAT DE RÉPONSE JSON :
{
  "type": "liste",
  "options_recommandees": [
    {
      "option": "Nom de l'option",
      "raison": "Pourquoi cette option est pertinente",
      "priorite": "haute|moyenne|basse"
    }
  ],
  "mises_en_garde": ["Attention 1", "Attention 2"],
  "conseils": "Conseils pour faire le bon choix"
}
LISTE;
        } elseif ($type === 'documents') {
            $prompt .= <<<DOCS

Pour un champ DOCUMENTS (upload de fichier) :
1. Quel type de document est attendu
2. Format recommandé
3. Informations essentielles que le document doit contenir
4. Points de vérification avant upload

FORMAT DE RÉPONSE JSON :
{
  "type": "documents",
  "document_attendu": "Description du type de document",
  "formats_acceptes": ["PDF", "DOCX", "etc"],
  "taille_max_recommandee": "5 MB",
  "contenu_essentiel": ["Info 1", "Info 2", "Info 3"],
  "checklist_verification": ["Check 1", "Check 2", "Check 3"],
  "exemple_nom_fichier": "exemple-nom-fichier.pdf",
  "conseils": "Conseils pour préparer le document"
}
DOCS;
        }

        $prompt .= "\n\nIMPORTANT : Réponds UNIQUEMENT avec le JSON, sans texte avant ou après.";

        return $prompt;
    }

    /**
     * Construit le prompt pour l'analyse pré-soumission
     */
    private function construirePromptAnalyse(
        Item $item,
        array $submissionData,
        Entreprise $entreprise
    ): string {
        $type = $item->type;
        $dataJson = json_encode($submissionData, JSON_PRETTY_PRINT);

        return <<<PROMPT
CONTEXTE :
- Entreprise : {$entreprise->nom}
- Item : {$item->nom_item}
- Type : {$type}
- Description : {$item->description}

DONNÉES SOUMISES :
{$dataJson}

MISSION :
Analyse ces données et détermine :
1. Si elles sont complètes et cohérentes
2. S'il y a des problèmes évidents
3. Des suggestions d'amélioration
4. Si la soumission peut être envoyée en l'état

FORMAT DE RÉPONSE JSON :
{
  "can_submit": true/false,
  "score_qualite": 0-100,
  "problemes": [
    {
      "severite": "critique|warning|info",
      "message": "Description du problème"
    }
  ],
  "warnings": ["Warning 1", "Warning 2"],
  "suggestions": ["Suggestion 1", "Suggestion 2"],
  "resume": "Résumé de l'analyse en 1-2 phrases"
}

IMPORTANT : Réponds UNIQUEMENT avec le JSON.
PROMPT;
    }

    /**
     * Suggestions de secours en cas d'erreur IA
     */
    private function getFallbackSuggestions(Item $item): array
    {
        $type = $item->type;

        $base = [
            'type' => $type,
            'conseils' => "Veuillez remplir ce champ avec précision en respectant les exigences de conformité."
        ];

        if ($type === 'texte') {
            $base['points_cles'] = [
                "Soyez précis et complet",
                "Utilisez un langage professionnel",
                "Vérifiez l'orthographe et la grammaire"
            ];
        } elseif ($type === 'liste' || $type === 'checkbox') {
            $base['conseils'] = "Sélectionnez les options qui correspondent à votre situation.";
        } elseif ($type === 'documents') {
            $base['formats_acceptes'] = ['PDF', 'DOCX', 'XLSX'];
            $base['taille_max_recommandee'] = '10 MB';
        }

        return $base;
    }
}
