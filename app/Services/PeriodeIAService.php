<?php

namespace App\Services;

use App\Models\Item;
use App\Models\Entreprise;
use App\Models\PeriodeItem;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;

class PeriodeIAService
{
    /**
     * Suggère des périodes de validité pour un item avec IA
     */
    public function suggererPeriodes(Item $item, Entreprise $entreprise, ?string $contexteSupplementaire = null)
    {
        $prompt = $this->construirePromptPeriodes($item, $entreprise, $contexteSupplementaire);
        
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . config('services.openai.key'),
            'Content-Type' => 'application/json',
        ])->timeout(60)->post('https://api.openai.com/v1/chat/completions', [
            'model' => 'gpt-4o',
            'messages' => [
                ['role' => 'system', 'content' => 'Tu es un expert en conformité et gestion de périodes de validité. Tu réponds UNIQUEMENT avec du JSON valide.'],
                ['role' => 'user', 'content' => $prompt],
            ],
            'temperature' => 0.7,
            'max_tokens' => 2000,
        ]);

        if ($response->failed()) {
            throw new \Exception('Erreur API OpenAI: ' . $response->body());
        }

        $content = $response->json()['choices'][0]['message']['content'];
        
        // Nettoyer le contenu
        $content = preg_replace('/```json\s*/', '', $content);
        $content = preg_replace('/```\s*$/', '', $content);
        $content = trim($content);
        
        $data = json_decode($content, true);
        
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new \Exception('Erreur de décodage JSON : ' . json_last_error_msg());
        }
        
        return $this->structurerSuggestionsPeriodes($data);
    }

    /**
     * Construit le prompt pour suggérer des périodes
     */
    private function construirePromptPeriodes(Item $item, Entreprise $entreprise, ?string $contexteSupplementaire)
    {
        $nomItem = $item->nom_item;
        $descriptionItem = $item->description ?? 'Non spécifiée';
        $typeItem = $item->type;
        $categorie = $item->categorie->nom_categorie ?? 'Non spécifiée';
        $domaine = $item->categorie->domaine->nom_domaine ?? 'Non spécifié';
        
        $nomEntreprise = $entreprise->nom;
        $secteurEntreprise = $entreprise->secteur ?? 'Non spécifié';
        
        $contexte = $contexteSupplementaire ? "\n\nCONTEXTE SUPPLÉMENTAIRE :\n{$contexteSupplementaire}" : '';
        
        return <<<PROMPT
Tu es un expert en conformité et gestion documentaire. Tu dois suggérer des périodes de validité pertinentes pour un document/critère de conformité.

ITEM À CONFIGURER :
- Nom : {$nomItem}
- Description : {$descriptionItem}
- Type : {$typeItem}
- Catégorie : {$categorie}
- Domaine : {$domaine}

ENTREPRISE CONCERNÉE :
- Nom : {$nomEntreprise}
- Secteur : {$secteurEntreprise}{$contexte}

MISSION :
Propose 3 options de périodes de validité pertinentes pour cet item. Considère :
1. Les obligations légales (si applicable)
2. Les bonnes pratiques du secteur
3. La fréquence de mise à jour typique
4. La durée de validité des documents similaires

RÈGLES :
- Les périodes doivent être réalistes et pratiques
- Préfère des durées standards (3 mois, 6 mois, 1 an, 2 ans)
- Justifie chaque suggestion
- La date de début sera toujours aujourd'hui

FORMAT DE RÉPONSE STRICTEMENT JSON :
{
  "suggestions": [
    {
      "duree_jours": 90,
      "duree_libelle": "3 mois",
      "raison": "Explication de pourquoi cette durée est pertinente",
      "recommandation": "court_terme|standard|long_terme"
    },
    {
      "duree_jours": 180,
      "duree_libelle": "6 mois",
      "raison": "Explication",
      "recommandation": "court_terme|standard|long_terme"
    },
    {
      "duree_jours": 365,
      "duree_libelle": "1 an",
      "raison": "Explication",
      "recommandation": "court_terme|standard|long_terme"
    }
  ],
  "recommandation_principale": "standard",
  "notes": "Notes ou recommandations supplémentaires"
}

IMPORTANT : Retourne UNIQUEMENT le JSON, sans texte avant ou après.
PROMPT;
    }

    /**
     * Structure les suggestions de périodes
     */
    private function structurerSuggestionsPeriodes($data)
    {
        if (!isset($data['suggestions']) || !is_array($data['suggestions'])) {
            throw new \Exception('Format de réponse IA invalide');
        }

        $dateDebut = now()->startOfDay();
        
        $suggestions = [];
        foreach ($data['suggestions'] as $suggestion) {
            $dateFin = $dateDebut->copy()->addDays($suggestion['duree_jours']);
            
            $suggestions[] = [
                'duree_jours' => $suggestion['duree_jours'],
                'duree_libelle' => $suggestion['duree_libelle'],
                'date_debut' => $dateDebut->format('Y-m-d'),
                'date_fin' => $dateFin->format('Y-m-d'),
                'raison' => $suggestion['raison'],
                'recommandation' => $suggestion['recommandation'] ?? 'standard',
                'est_recommande' => ($data['recommandation_principale'] ?? '') === $suggestion['recommandation']
            ];
        }

        return [
            'suggestions' => $suggestions,
            'notes' => $data['notes'] ?? '',
            'recommandation_principale' => $data['recommandation_principale'] ?? 'standard'
        ];
    }

    /**
     * Crée une période à partir d'une suggestion
     */
    public function creerPeriodeDepuisSuggestion(
        Item $item, 
        Entreprise $entreprise, 
        array $suggestion, 
        $userId
    ): PeriodeItem {
        return PeriodeItem::create([
            'item_id' => $item->id,
            'entreprise_id' => $entreprise->id,
            'debut_periode' => $suggestion['date_debut'],
            'fin_periode' => $suggestion['date_fin'],
            'statut' => '1',
            'user_add_id' => $userId,
        ]);
    }
}