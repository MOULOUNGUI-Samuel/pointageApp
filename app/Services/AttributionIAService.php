<?php

namespace App\Services;

use App\Models\Domaine;
use App\Models\CategorieDomaine;
use App\Models\Item;
use App\Models\Entreprise;
use Illuminate\Support\Facades\Http;

class AttributionIAService
{
    /**
     * Analyse l'activité de l'entreprise et suggère les domaines/catégories/items
     */
    public function suggererAttributionInitiale(Entreprise $entreprise, string $descriptionActivite)
    {
        // Récupérer tous les domaines disponibles
        $domaines = Domaine::with(['categories.items.options'])->where('statut', '1')->get();

        // Construire le contexte pour l'IA
        $contexteDomaines = $this->construireContexteDomaines($domaines);

        $prompt = $this->construirePromptInitial($entreprise, $descriptionActivite, $contexteDomaines);

        // Appeler l'API OpenAI
        $response = $this->appellerOpenAI($prompt);

        // Parser et structurer la réponse
        return $this->structurerReponse($response, $domaines);
    }

    /**
     * Suggère des domaines supplémentaires basés sur un nouveau besoin
     */
    public function suggererAttributionSupplementaire(Entreprise $entreprise, string $nouveauBesoin)
    {
        // ÉTAPE 1 : Récupérer les domaines déjà attribués
        $domainesAttribues = $entreprise->domaines()->pluck('domaine_id')->toArray();

        // ÉTAPE 2 : Récupérer les domaines non encore attribués
        $domainesNonAttribues = Domaine::with(['categories.items.options'])
            ->where('statut', '1')
            ->whereNotIn('id', $domainesAttribues)
            ->get();

        // Si des domaines sont disponibles, les proposer
        if ($domainesNonAttribues->isNotEmpty()) {
            $contexteDomaines = $this->construireContexteDomaines($domainesNonAttribues);
            $prompt = $this->construirePromptSupplementaire($entreprise, $nouveauBesoin, $contexteDomaines, 'domaines');
            $response = $this->appellerOpenAI($prompt);
            return $this->structurerReponse($response, $domainesNonAttribues);
        }

        // ÉTAPE 3 : Si tous les domaines sont attribués, chercher des catégories non attribuées
        // $categoriesAttribuees = $entreprise->categories()->pluck('categorie_domaine_id')->toArray();
        $categoriesAttribuees = $entreprise->categories()
    ->select('categorie_domaines.id')
    ->pluck('categorie_domaines.id')
    ->toArray();

        $categoriesNonAttribuees = CategorieDomaine::with(['items.options', 'domaine'])
            ->where('statut', '1')
            ->whereIn('domaine_id', $domainesAttribues) // Dans les domaines déjà attribués
            ->whereNotIn('id', $categoriesAttribuees) // Mais catégories pas encore attribuées
            ->get();

        // Si des catégories sont disponibles, les proposer
        if ($categoriesNonAttribuees->isNotEmpty()) {
            $contexteCategories = $this->construireContexteCategories($categoriesNonAttribuees);
            $prompt = $this->construirePromptSupplementaireCategories($entreprise, $nouveauBesoin, $contexteCategories);
            $response = $this->appellerOpenAI($prompt);
            return $this->structurerReponseCategories($response, $categoriesNonAttribuees);
        }

        // ÉTAPE 4 : Si toutes les catégories sont attribuées, chercher des items non attribués
        $itemsAttribues = $entreprise->items()->pluck('item_id')->toArray();

        $itemsNonAttribues = Item::with(['options', 'categorie.domaine'])
            ->where('statut', '1')
            ->whereHas('categorie', function ($query) use ($categoriesAttribuees) {
                $query->whereIn('id', $categoriesAttribuees); // Dans les catégories déjà attribuées
            })
            ->whereNotIn('id', $itemsAttribues) // Mais items pas encore attribués
            ->get();

        // Si des items sont disponibles, les proposer
        if ($itemsNonAttribues->isNotEmpty()) {
            $contexteItems = $this->construireContexteItems($itemsNonAttribues);
            $prompt = $this->construirePromptSupplementaireItems($entreprise, $nouveauBesoin, $contexteItems);
            $response = $this->appellerOpenAI($prompt);
            return $this->structurerReponseItems($response, $itemsNonAttribues);
        }

        // ÉTAPE 5 : Si tout est attribué
        return [
            'success' => false,
            'message' => 'Tous les domaines, catégories et items disponibles sont déjà attribués à cette entreprise.'
        ];
    }

    /**
     * Construit le contexte des catégories pour l'IA
     */
    private function construireContexteCategories($categories)
    {
        $context = [];

        foreach ($categories as $categorie) {
            $context[] = [
                'id' => $categorie->id,
                'nom_categorie' => $categorie->nom_categorie,
                'code_categorie' => $categorie->code_categorie,
                'description' => $categorie->description,
                'domaine' => $categorie->domaine->nom_domaine,
                'nombre_items' => $categorie->items->count()
            ];
        }

        return json_encode($context, JSON_PRETTY_PRINT);
    }

    /**
     * Construit le contexte des items pour l'IA
     */
    private function construireContexteItems($items)
    {
        $context = [];

        foreach ($items as $item) {
            $context[] = [
                'id' => $item->id,
                'nom_item' => $item->nom_item,
                'description' => $item->description,
                'type' => $item->type,
                'categorie' => $item->categorie->nom_categorie,
                'domaine' => $item->categorie->domaine->nom_domaine,
                'a_options' => $item->options->isNotEmpty()
            ];
        }

        return json_encode($context, JSON_PRETTY_PRINT);
    }

    /**
     * Prompt pour catégories supplémentaires
     */
    private function construirePromptSupplementaireCategories(Entreprise $entreprise, string $nouveauBesoin, string $contexteCategories)
    {
        $nomEntreprise = $entreprise->nom;
        $secteurEntreprise = $entreprise->secteur ? $entreprise->secteur : 'Non spécifié';

        return <<<PROMPT
Tu es un expert en conformité et gestion d'entreprise.

CONTEXTE DE L'ENTREPRISE :
- Nom : {$nomEntreprise}
- Secteur : {$secteurEntreprise}

SITUATION : Cette entreprise a déjà des domaines attribués, mais pas toutes les catégories.

NOUVEAU BESOIN EXPRIMÉ :
{$nouveauBesoin}

CATÉGORIES DISPONIBLES (non encore attribuées) :
{$contexteCategories}

MISSION :
Identifie quelles catégories supplémentaires répondent à ce nouveau besoin.

FORMAT DE RÉPONSE STRICTEMENT JSON :
{
  "categories_selectionnees": [
    {
      "categorie_id": "uuid-de-la-categorie",
      "nom_categorie": "Nom de la catégorie",
      "raison_selection": "Explication de la pertinence",
      "inclure_tous_items": true
    }
  ],
  "resume": "Résumé de comment ces catégories répondent au besoin"
}

IMPORTANT : Retourne UNIQUEMENT le JSON, sans texte avant ou après.
PROMPT;
    }

    /**
     * Prompt pour items supplémentaires
     */
    private function construirePromptSupplementaireItems(Entreprise $entreprise, string $nouveauBesoin, string $contexteItems)
    {
        $nomEntreprise = $entreprise->nom;
        $secteurEntreprise = $entreprise->secteur ? $entreprise->secteur : 'Non spécifié';

        return <<<PROMPT
Tu es un expert en conformité et gestion d'entreprise.

CONTEXTE DE L'ENTREPRISE :
- Nom : {$nomEntreprise}
- Secteur : {$secteurEntreprise}

SITUATION : Cette entreprise a déjà des domaines et catégories attribués, mais pas tous les items.

NOUVEAU BESOIN EXPRIMÉ :
{$nouveauBesoin}

ITEMS DISPONIBLES (non encore attribués) :
{$contexteItems}

MISSION :
Identifie quels items supplémentaires répondent à ce nouveau besoin.

FORMAT DE RÉPONSE STRICTEMENT JSON :
{
  "items_selectionnes": [
    {
      "item_id": "uuid-de-l-item",
      "nom_item": "Nom de l'item",
      "raison_selection": "Explication de la pertinence"
    }
  ],
  "resume": "Résumé de comment ces items répondent au besoin"
}

IMPORTANT : Retourne UNIQUEMENT le JSON, sans texte avant ou après.
PROMPT;
    }

    /**
     * Structure la réponse pour les catégories
     */
    private function structurerReponseCategories($reponseIA, $categories)
    {
        if (!$reponseIA || !isset($reponseIA['categories_selectionnees'])) {
            throw new \Exception('Réponse IA invalide pour catégories');
        }

        $resultat = [
            'resume' => $reponseIA['resume'] ?? '',
            'type' => 'categories',
            'domaines' => []
        ];

        // Grouper par domaine pour l'affichage
        $categoriesParDomaine = [];

        foreach ($reponseIA['categories_selectionnees'] as $catIA) {
            $categorie = $categories->firstWhere('id', $catIA['categorie_id']);

            if (!$categorie) continue;

            $domaineId = $categorie->domaine_id;

            if (!isset($categoriesParDomaine[$domaineId])) {
                $categoriesParDomaine[$domaineId] = [
                    'id' => $categorie->domaine->id,
                    'nom_domaine' => $categorie->domaine->nom_domaine,
                    'description' => $categorie->domaine->description,
                    'raison_selection' => 'Catégories supplémentaires pour ce domaine déjà attribué',
                    'categories' => []
                ];
            }

            $categoriesParDomaine[$domaineId]['categories'][] = [
                'id' => $categorie->id,
                'nom_categorie' => $categorie->nom_categorie,
                'code_categorie' => $categorie->code_categorie,
                'description' => $categorie->description,
                'raison_selection' => $catIA['raison_selection'] ?? '',
                'items' => $categorie->items->map(function ($item) {
                    return [
                        'id' => $item->id,
                        'nom_item' => $item->nom_item,
                        'description' => $item->description,
                        'type' => $item->type,
                        'options' => $item->options->map(function ($opt) {
                            return [
                                'id' => $opt->id,
                                'label' => $opt->label,
                                'value' => $opt->value
                            ];
                        })->toArray()
                    ];
                })->toArray()
            ];
        }

        $resultat['domaines'] = array_values($categoriesParDomaine);

        return $resultat;
    }

    /**
     * Structure la réponse pour les items
     */
    private function structurerReponseItems($reponseIA, $items)
    {
        if (!$reponseIA || !isset($reponseIA['items_selectionnes'])) {
            throw new \Exception('Réponse IA invalide pour items');
        }

        $resultat = [
            'resume' => $reponseIA['resume'] ?? '',
            'type' => 'items',
            'domaines' => []
        ];

        // Grouper par domaine et catégorie pour l'affichage
        $structure = [];

        foreach ($reponseIA['items_selectionnes'] as $itemIA) {
            $item = $items->firstWhere('id', $itemIA['item_id']);

            if (!$item) continue;

            $domaineId = $item->categorie->domaine_id;
            $categorieId = $item->categorie->id;

            if (!isset($structure[$domaineId])) {
                $structure[$domaineId] = [
                    'id' => $item->categorie->domaine->id,
                    'nom_domaine' => $item->categorie->domaine->nom_domaine,
                    'description' => $item->categorie->domaine->description,
                    'raison_selection' => 'Items supplémentaires pour ce domaine déjà attribué',
                    'categories' => []
                ];
            }

            if (!isset($structure[$domaineId]['categories'][$categorieId])) {
                $structure[$domaineId]['categories'][$categorieId] = [
                    'id' => $item->categorie->id,
                    'nom_categorie' => $item->categorie->nom_categorie,
                    'code_categorie' => $item->categorie->code_categorie,
                    'description' => $item->categorie->description,
                    'raison_selection' => 'Items supplémentaires pour cette catégorie',
                    'items' => []
                ];
            }

            $structure[$domaineId]['categories'][$categorieId]['items'][] = [
                'id' => $item->id,
                'nom_item' => $item->nom_item,
                'description' => $item->description,
                'type' => $item->type,
                'raison_selection' => $itemIA['raison_selection'] ?? '',
                'options' => $item->options->map(function ($opt) {
                    return [
                        'id' => $opt->id,
                        'label' => $opt->label,
                        'value' => $opt->value
                    ];
                })->toArray()
            ];
        }

        // Convertir en tableau indexé
        foreach ($structure as $domaine) {
            $domaine['categories'] = array_values($domaine['categories']);
            $resultat['domaines'][] = $domaine;
        }

        return $resultat;
    }

    /**
     * Construit le contexte des domaines pour l'IA
     */
    private function construireContexteDomaines($domaines)
    {
        $context = [];

        foreach ($domaines as $domaine) {
            $context[] = [
                'id' => $domaine->id,
                'nom_domaine' => $domaine->nom_domaine,
                'description' => $domaine->description,
                'categories' => $domaine->categories->map(function ($cat) {
                    return [
                        'id' => $cat->id,
                        'nom_categorie' => $cat->nom_categorie,
                        'code_categorie' => $cat->code_categorie,
                        'description' => $cat->description,
                        'nombre_items' => $cat->items->count()
                    ];
                })->toArray()
            ];
        }

        return json_encode($context, JSON_PRETTY_PRINT);
    }

    /**
     * Construit le prompt pour l'attribution initiale
     */
    private function construirePromptInitial(Entreprise $entreprise, string $descriptionActivite, string $contexteDomaines)
    {
        $nomEntreprise = $entreprise->nom;
        $secteurEntreprise = $entreprise->secteur ? $entreprise->secteur : 'Non spécifié';

        return <<<PROMPT
Tu es un expert en conformité et gestion d'entreprise. 

CONTEXTE DE L'ENTREPRISE :
- Nom : {$nomEntreprise}
- Secteur : {$secteurEntreprise}
- Description de l'activité : {$descriptionActivite}

DOMAINES DISPONIBLES EN BASE DE DONNÉES :
{$contexteDomaines}

MISSION :
Analyse l'activité de cette entreprise et identifie quels domaines, catégories et items de conformité sont pertinents pour elle.

CRITÈRES DE SÉLECTION :
1. Pertinence directe avec l'activité décrite
2. Obligations légales potentielles du secteur
3. Meilleures pratiques du domaine d'activité
4. Ne sélectionne QUE ce qui est vraiment nécessaire (qualité > quantité)

FORMAT DE RÉPONSE STRICTEMENT JSON :
{
  "domaines_selectionnes": [
    {
      "domaine_id": "uuid-du-domaine",
      "nom_domaine": "Nom du domaine",
      "raison_selection": "Explication courte de pourquoi ce domaine est pertinent",
      "categories_selectionnees": [
        {
          "categorie_id": "uuid-de-la-categorie",
          "nom_categorie": "Nom de la catégorie",
          "inclure_tous_items": true
        }
      ]
    }
  ],
  "resume": "Résumé en 2-3 phrases des principaux domaines de conformité identifiés"
}

IMPORTANT : Retourne UNIQUEMENT le JSON, sans texte avant ou après.
PROMPT;
    }

    /**
     * Construit le prompt pour l'attribution supplémentaire
     */
    private function construirePromptSupplementaire(Entreprise $entreprise, string $nouveauBesoin, string $contexteDomaines, string $type = 'domaines')
    {
        $nomEntreprise = $entreprise->nom;
        $secteurEntreprise = $entreprise->secteur ? $entreprise->secteur : 'Non spécifié';

        return <<<PROMPT
Tu es un expert en conformité et gestion d'entreprise.

CONTEXTE DE L'ENTREPRISE :
- Nom : {$nomEntreprise}
- Secteur : {$secteurEntreprise}

NOUVEAU BESOIN EXPRIMÉ :
{$nouveauBesoin}

DOMAINES DISPONIBLES (non encore attribués) :
{$contexteDomaines}

MISSION :
Identifie quels domaines, catégories et items supplémentaires répondent à ce nouveau besoin.

FORMAT DE RÉPONSE STRICTEMENT JSON :
{
  "domaines_selectionnes": [
    {
      "domaine_id": "uuid-du-domaine",
      "nom_domaine": "Nom du domaine",
      "raison_selection": "Explication de la pertinence par rapport au besoin",
      "categories_selectionnees": [
        {
          "categorie_id": "uuid-de-la-categorie",
          "nom_categorie": "Nom de la catégorie",
          "inclure_tous_items": true
        }
      ]
    }
  ],
  "resume": "Résumé de comment ces domaines répondent au besoin"
}

IMPORTANT : Retourne UNIQUEMENT le JSON, sans texte avant ou après.
PROMPT;
    }

    /**
     * Appelle l'API OpenAI
     */
    private function appellerOpenAI(string $prompt)
    {
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . config('services.openai.key'),
            'Content-Type' => 'application/json',
        ])->timeout(60)->post('https://api.openai.com/v1/chat/completions', [
            'model' => 'gpt-4o',
            'messages' => [
                ['role' => 'system', 'content' => 'Tu es un expert en conformité. Tu réponds UNIQUEMENT avec du JSON valide.'],
                ['role' => 'user', 'content' => $prompt],
            ],
            'temperature' => 0.7,
            'max_tokens' => 3000,
        ]);

        if ($response->failed()) {
            throw new \Exception('Erreur API OpenAI: ' . $response->body());
        }

        $content = $response->json()['choices'][0]['message']['content'];

        // Nettoyer le contenu
        $content = preg_replace('/```json\s*/', '', $content);
        $content = preg_replace('/```\s*$/', '', $content);
        $content = trim($content);

        return json_decode($content, true);
    }

    /**
     * Structure la réponse avec les données complètes depuis la base
     */
    private function structurerReponse($reponseIA, $domaines)
    {
        if (!$reponseIA || !isset($reponseIA['domaines_selectionnes'])) {
            throw new \Exception('Réponse IA invalide');
        }

        $resultat = [
            'resume' => $reponseIA['resume'] ?? '',
            'domaines' => []
        ];

        foreach ($reponseIA['domaines_selectionnes'] as $domaineIA) {
            $domaine = $domaines->firstWhere('id', $domaineIA['domaine_id']);

            if (!$domaine) continue;

            $categoriesSelectionnees = [];

            foreach ($domaineIA['categories_selectionnees'] as $catIA) {
                $categorie = $domaine->categories->firstWhere('id', $catIA['categorie_id']);

                if (!$categorie) continue;

                $categoriesSelectionnees[] = [
                    'id' => $categorie->id,
                    'nom_categorie' => $categorie->nom_categorie,
                    'code_categorie' => $categorie->code_categorie,
                    'description' => $categorie->description,
                    'items' => $categorie->items->map(function ($item) {
                        return [
                            'id' => $item->id,
                            'nom_item' => $item->nom_item,
                            'description' => $item->description,
                            'type' => $item->type,
                            'options' => $item->options->map(function ($opt) {
                                return [
                                    'id' => $opt->id,
                                    'label' => $opt->label,
                                    'value' => $opt->value
                                ];
                            })->toArray()
                        ];
                    })->toArray()
                ];
            }

            $resultat['domaines'][] = [
                'id' => $domaine->id,
                'nom_domaine' => $domaine->nom_domaine,
                'description' => $domaine->description,
                'raison_selection' => $domaineIA['raison_selection'] ?? '',
                'categories' => $categoriesSelectionnees
            ];
        }

        return $resultat;
    }
}
