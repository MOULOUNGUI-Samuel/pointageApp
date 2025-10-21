<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Domaine;
use App\Models\CategorieDomaine;
use App\Models\CategorieDommaine;
use App\Models\Item;
use App\Models\ItemOption;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class GenerateurConformite extends Component
{
    public $nom_domaine = '';
    public $description_domaine = '';
    public $generatedData = null;
    public $isGenerating = false;
    public $isValidated = false;
    public $errorMessage = '';
    
    // Données éditables
    public $editableData = [];

    protected $rules = [
        'nom_domaine' => 'required|string|min:3',
        'description_domaine' => 'nullable|string',
    ];

    public function generate()
    {
        $this->validate();
        
        $this->isGenerating = true;
        $this->errorMessage = '';
        $this->generatedData = null;
        $this->editableData = [];

        try {
            $prompt = $this->buildPrompt();
            
            // Utiliser HTTP client au lieu du package OpenAI
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . config('services.openai.key'),
                'Content-Type' => 'application/json',
            ])->timeout(60)->post('https://api.openai.com/v1/chat/completions', [
                'model' => 'gpt-4o',
                'messages' => [
                    ['role' => 'system', 'content' => 'Vous êtes un expert en conformité et modélisation de données. Vous générez uniquement du JSON valide sans aucun texte additionnel.'],
                    ['role' => 'user', 'content' => $prompt],
                ],
                'temperature' => 0.7,
                'max_tokens' => 4000,
            ]);

            if ($response->failed()) {
                throw new \Exception('Erreur API OpenAI: ' . $response->body());
            }

            $content = $response->json()['choices'][0]['message']['content'];
            
            // Nettoyer le contenu (enlever les balises markdown si présentes)
            $content = preg_replace('/```json\s*/', '', $content);
            $content = preg_replace('/```\s*$/', '', $content);
            $content = trim($content);
            
            $this->generatedData = json_decode($content, true);
            
            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new \Exception('Erreur de décodage JSON : ' . json_last_error_msg());
            }
            
            // Copier dans les données éditables
            $this->editableData = $this->generatedData;
            
        } catch (\Exception $e) {
            $this->errorMessage = 'Erreur lors de la génération : ' . $e->getMessage();
        } finally {
            $this->isGenerating = false;
        }
    }

    private function buildPrompt()
    {
        return <<<PROMPT
AGISSEZ comme un architecte de conformité et un expert en modélisation de données JSON. Votre objectif est de générer un fichier JSON complet et auto-suffisant représentant un catalogue de conformité pour un domaine métier donné.

DOMAINE À MODÉLISER : {$this->nom_domaine}
DESCRIPTION DU DOMAINE : {$this->description_domaine}

EXIGENCES STRUCTURELLES DU LIVRABLE (Format JSON strict) :

1. Structure racinaire :
   - Le JSON doit contenir un objet unique avec les clés suivantes :
     - nom_domaine : libellé du domaine
     - description : description du domaine
     - categories : tableau d'objets représentant les catégories de conformité

2. Catégories (minimum 3) :
   - Chaque objet dans le tableau categories doit contenir :
     - nom_categorie : libellé de la catégorie
     - code_categorie : identifiant de la catégorie (constitué de la première lettre de chaque mot du nom de la catégorie en majuscules)
     - description : description de la catégorie
     - items : tableau d'objets représentant les exigences de conformité

3. Items (minimum 5 par catégorie) :
   - Chaque objet dans le tableau items doit inclure les champs suivants :
     - nom_item : libellé de l'exigence
     - description : description de l'exigence
     - type : type de l'item ('liste', 'checkbox', ou 'texte')
     - options : si le type est 'liste' ou 'checkbox', proposer un tableau d'objets avec les clés 'label' et 'value'
     - statut : toujours "1"

CONTRAINTES DE SORTIE :
- Le fichier JSON doit être complet, auto-suffisant et conforme à la structure ci-dessus.
- Aucun texte explicatif, commentaire ou introduction ne doit précéder ou suivre le bloc JSON.
- Générez uniquement le JSON brut.

EXEMPLE DE STRUCTURE ATTENDUE :
{
  "nom_domaine": "Ressources Humaines",
  "description": "Gestion de la conformité RH",
  "categories": [
    {
      "nom_categorie": "Contrats de Travail",
      "code_categorie": "CDT",
      "description": "Gestion des contrats",
      "items": [
        {
          "nom_item": "Type de contrat",
          "description": "Vérifier le type de contrat",
          "type": "liste",
          "options": [
            {"label": "CDI", "value": "cdi"},
            {"label": "CDD", "value": "cdd"}
          ],
          "statut": "1"
        }
      ]
    }
  ]
}
PROMPT;
    }

    public function updateField($path, $value)
    {
        // Mettre à jour un champ dans editableData
        data_set($this->editableData, $path, $value);
    }

    public function removeCategory($index)
    {
        unset($this->editableData['categories'][$index]);
        $this->editableData['categories'] = array_values($this->editableData['categories']);
    }

    public function removeItem($categoryIndex, $itemIndex)
    {
        unset($this->editableData['categories'][$categoryIndex]['items'][$itemIndex]);
        $this->editableData['categories'][$categoryIndex]['items'] = 
            array_values($this->editableData['categories'][$categoryIndex]['items']);
    }

    public function validate_and_save()
    {
        if (!$this->editableData) {
            $this->errorMessage = 'Aucune donnée à enregistrer';
            return;
        }

        DB::beginTransaction();
        
        try {
            // Créer le domaine
            $domaine = Domaine::create([
                'id' => Str::uuid(),
                'nom_domaine' => $this->editableData['nom_domaine'],
                'description' => $this->editableData['description'] ?? null,
                'user_add_id' => auth()->id(),
                'statut' => '1',
            ]);

            // Créer les catégories et items
            foreach ($this->editableData['categories'] as $catData) {
                $categorie = CategorieDommaine::create([
                    'id' => Str::uuid(),
                    'domaine_id' => $domaine->id,
                    'nom_categorie' => $catData['nom_categorie'],
                    'code_categorie' => $catData['code_categorie'],
                    'description' => $catData['description'] ?? null,
                    'user_add_id' => auth()->id(),
                    'statut' => '1',
                ]);

                foreach ($catData['items'] as $itemData) {
                    $item = Item::create([
                        'id' => Str::uuid(),
                        'categorie_domaine_id' => $categorie->id,
                        'nom_item' => $itemData['nom_item'],
                        'description' => $itemData['description'] ?? null,
                        'type' => $itemData['type'] ?? 'texte',
                        'user_add_id' => auth()->id(),
                        'statut' => '1',
                    ]);

                    // Créer les options si type liste ou checkbox
                    if (in_array($itemData['type'], ['liste', 'checkbox']) && isset($itemData['options'])) {
                        foreach ($itemData['options'] as $position => $option) {
                            ItemOption::create([
                                'id' => Str::uuid(),
                                'item_id' => $item->id,
                                'kind' => $itemData['type'],
                                'label' => $option['label'],
                                'value' => $option['value'] ?? $option['label'],
                                'position' => $position + 1,
                                'statut' => '1',
                            ]);
                        }
                    }
                }
            }

            DB::commit();
            
            $this->isValidated = true;
            session()->flash('success', 'Données enregistrées avec succès !');
            
            // Émettre un événement pour fermer le modal
            $this->dispatch('conformite-saved');
            
            // Réinitialiser le formulaire
            $this->reset(['nom_domaine', 'description_domaine', 'generatedData', 'editableData']);
            
        } catch (\Exception $e) {
            DB::rollBack();
            $this->errorMessage = 'Erreur lors de l\'enregistrement : ' . $e->getMessage();
        }
    }

    public function regenerate()
    {
        $this->reset(['generatedData', 'editableData', 'errorMessage', 'isValidated']);
    }

    public function render()
    {
        return view('livewire.generateur-conformite');
    }
}