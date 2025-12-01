<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Domaine;
use App\Models\CategorieDomaine;
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
    public $warningMessage = '';

    // Données éditables
    public $editableData = [];

    protected $rules = [
        'nom_domaine' => 'required|string|min:3',
        'description_domaine' => 'nullable|string',
    ];

    public function updatedNomDomaine()
    {
        if (strlen($this->nom_domaine) >= 3) {
            $domaineExistant = Domaine::where('nom_domaine', $this->nom_domaine)->first();

            if ($domaineExistant) {
                $nbCategories = $domaineExistant->categories()->count();
                $nbItems = Item::whereHas('categorie', function ($query) use ($domaineExistant) {
                    $query->where('domaine_id', $domaineExistant->id);
                })->count();

                $this->warningMessage = "ℹ️ Ce domaine existe déjà ({$nbCategories} catégories, {$nbItems} items). Les nouvelles données seront fusionnées.";
            } else {
                $this->warningMessage = '';
            }
        }
    }

    public function generate()
    {
        $this->validate();

        // Vérifier si le domaine existe déjà
        $domaineExistant = Domaine::where('nom_domaine', $this->nom_domaine)->first();

        if ($domaineExistant) {
            $nbCategories = $domaineExistant->categories()->count();
            $nbItems = Item::whereHas('categorie', function ($query) use ($domaineExistant) {
                $query->where('domaine_id', $domaineExistant->id);
            })->count();

            $this->warningMessage = "⚠️ Le domaine '{$this->nom_domaine}' existe déjà avec {$nbCategories} catégorie(s) et {$nbItems} item(s). ";
            $this->warningMessage .= "Les nouvelles données générées seront fusionnées avec l'existant. Les catégories/items avec les mêmes noms seront mis à jour.";
        } else {
            $this->warningMessage = '';
        }

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
    public $varier_types = false;
    private function buildPrompt()
    {
        $varier_types = $this->varier_types 
        ? "Variez les types d'items (file, liste, checkbox, texte) de manière équilibrée selon les besoins."
        : "Par défaut, PRIORISEZ le type 'file' pour au moins 90% des items";
        return <<<PROMPT
VOUS ÊTES un expert en conformité, audit interne, contrôle de gestion, gouvernance administrative et droit du travail, spécialisé en :

- LÉGISLATION GABONAISE : Code du travail, CNSS, CNAMGS, fiscalité, sécurité, environnement, obligations administratives
- RÉFÉRENTIEL OHADA : Acte uniforme sur le droit comptable (SYSCOHADA), Acte uniforme sur les sociétés commerciales, Acte uniforme sur les sûretés, Acte uniforme sur les procédures collectives, obligations de gestion d'entreprise et tenue documentaire

DOMAINE À MODÉLISER : {$this->nom_domaine}
DESCRIPTION DU DOMAINE : {$this->description_domaine}

OBJECTIF : Générer un fichier JSON complet et auto-suffisant représentant un catalogue de conformité pour le domaine donné, en identifiant toutes les catégories réglementaires pertinentes selon la législation gabonaise, les actes uniformes OHADA et les pratiques administratives obligatoires.

EXIGENCES STRUCTURELLES DU LIVRABLE (Format JSON strict) :

1. Structure racinaire :
   - Le JSON doit contenir un objet unique avec les clés suivantes :
     - nom_domaine : libellé du domaine
     - description : description du domaine
     - categories : tableau d'objets représentant les catégories de conformité

2. Catégories (minimum 4) :
   - Chaque objet dans le tableau categories doit contenir :
     - nom_categorie : libellé de la catégorie (opérationnel et exploitable par un auditeur)
     - code_categorie : identifiant de la catégorie (constitué de la première lettre de chaque mot du nom de la catégorie en majuscules)
     - description : description de la catégorie couvrant le champ réglementaire applicable
     - items : tableau d'objets représentant les exigences de conformité

3. Items (minimum 6 par catégorie) :
   - Chaque objet dans le tableau items doit inclure les champs suivants :
     - nom_item : formulé comme une question claire, brève et explicite
     - description : explication synthétique avec la référence au texte ou principe juridique (gabonais et/ou OHADA)
     - type : type de l'item ('file', 'liste', 'checkbox', ou 'texte')
     - options : si le type est 'liste' ou 'checkbox', proposer un tableau d'objets avec les clés 'label' et 'value'
     - statut : toujours "1"

RÈGLE IMPORTANTE SUR LES TYPES :
 {$varier_types}
- Le type "file" doit être utilisé pour tous les items nécessitant une preuve documentaire (contrats, attestations, déclarations, certificats, rapports, factures, etc.)
- N'utilisez les types "liste", "checkbox" ou "texte" QUE lorsque le contexte l'exige absolument (choix multiples, état binaire, commentaire libre)
- Si aucune instruction contraire n'est donnée, générez majoritairement des items de type "file"

CONTRAINTES DE SORTIE :
- Le fichier JSON doit être complet, auto-suffisant et conforme à la structure ci-dessus
- Aucun texte explicatif, commentaire ou introduction ne doit précéder ou suivre le bloc JSON
- Générez uniquement le JSON brut
- Les catégories doivent couvrir l'ensemble du champ réglementaire du domaine
- Les observations doivent être concises avec le cadre légal applicable

EXEMPLE DE STRUCTURE ATTENDUE :
{
  "nom_domaine": "Ressources Humaines",
  "description": "Gestion de la conformité RH selon la législation gabonaise et OHADA",
  "categories": [
    {
      "nom_categorie": "Dossiers du Personnel",
      "code_categorie": "DDP",
      "description": "Gestion documentaire des salariés conformément au Code du travail gabonais",
      "items": [
        {
          "nom_item": "Le dossier de chaque salarié contient-il un contrat de travail signé ?",
          "description": "Conformément au Code du travail gabonais, tout salarié doit disposer d'un contrat de travail écrit signé par les deux parties.",
          "type": "file",
          "statut": "1"
        },
        {
          "nom_item": "Les fiches de paie sont-elles archivées pour chaque salarié ?",
          "description": "Selon le Code du travail, l'employeur doit conserver les bulletins de paie pendant 5 ans minimum.",
          "type": "file",
          "statut": "1"
        },
        {
          "nom_item": "Les attestations de visite médicale d'embauche sont-elles disponibles ?",
          "description": "Le Code du travail gabonais impose une visite médicale avant toute embauche.",
          "type": "file",
          "statut": "1"
        },
        {
          "nom_item": "Les déclarations d'embauche ont-elles été transmises à l'inspection du travail ?",
          "description": "Toute embauche doit être déclarée à l'inspection du travail dans les 8 jours.",
          "type": "file",
          "statut": "1"
        },
        {
          "nom_item": "Le registre du personnel est-il à jour et disponible ?",
          "description": "Selon le Code du travail, tout employeur doit tenir un registre du personnel actualisé.",
          "type": "file",
          "statut": "1"
        },
        {
          "nom_item": "Les dossiers contiennent-ils les pièces d'identité des salariés ?",
          "description": "Les pièces d'identité sont obligatoires pour constituer un dossier administratif complet.",
          "type": "file",
          "statut": "1"
        }
      ]
    },
    {
      "nom_categorie": "Obligations Fiscales et Sociales",
      "code_categorie": "OFS",
      "description": "Respect des obligations fiscales selon la législation gabonaise",
      "items": [
        {
          "nom_item": "Les déclarations de retenues à la source (ITS) sont-elles disponibles ?",
          "description": "Le Code Général des Impôts gabonais impose à l'employeur de déclarer et reverser mensuellement l'impôt sur traitement et salaire.",
          "type": "file",
          "statut": "1"
        },
        {
          "nom_item": "Les bordereaux CNSS sont-ils transmis et archivés ?",
          "description": "Selon le Code de la Sécurité Sociale, les déclarations mensuelles CNSS sont obligatoires.",
          "type": "file",
          "statut": "1"
        },
        {
          "nom_item": "Les attestations de paiement CNAMGS sont-elles disponibles ?",
          "description": "La CNAMGS exige des attestations de cotisation pour la couverture maladie obligatoire.",
          "type": "file",
          "statut": "1"
        }
      ]
    }
  ]
}

GÉNÉREZ MAINTENANT LE JSON COMPLET SELON CES SPÉCIFICATIONS.
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
            // Vérifier si le domaine existe déjà
            $domaineExistant = Domaine::where('nom_domaine', $this->editableData['nom_domaine'])->first();

            if ($domaineExistant) {
                $domaine = $domaineExistant;
                // Mettre à jour la description si le domaine existe
                $domaine->update([
                    'description' => $this->editableData['description'] ?? $domaine->description,
                    'user_update_id' => auth()->id(),
                ]);
            } else {
                // Créer le domaine s'il n'existe pas
                $domaine = Domaine::create([
                    'id' => Str::uuid(),
                    'nom_domaine' => $this->editableData['nom_domaine'],
                    'description' => $this->editableData['description'] ?? null,
                    'user_add_id' => auth()->id(),
                    'statut' => '1',
                ]);
            }

            $statsCreation = [
                'domaine_existe' => $domaineExistant ? true : false,
                'categories_existantes' => 0,
                'categories_creees' => 0,
                'items_existants' => 0,
                'items_crees' => 0,
                'options_creees' => 0,
            ];

            // Créer les catégories et items
            foreach ($this->editableData['categories'] as $catData) {
                // Vérifier si la catégorie existe déjà dans ce domaine
                $categorieExistante = CategorieDomaine::where('domaine_id', $domaine->id)
                    ->where('code_categorie', $catData['code_categorie'])
                    ->first();

                if ($categorieExistante) {
                    // Mettre à jour la catégorie existante
                    $categorieExistante->update([
                        'nom_categorie' => $catData['nom_categorie'],
                        'description' => $catData['description'] ?? $categorieExistante->description,
                        'user_update_id' => auth()->id(),
                    ]);
                    $categorie = $categorieExistante;
                    $statsCreation['categories_existantes']++;
                } else {
                    // Créer la catégorie si elle n'existe pas
                    $categorie = CategorieDomaine::create([
                        'id' => Str::uuid(),
                        'domaine_id' => $domaine->id,
                        'nom_categorie' => $catData['nom_categorie'],
                        'code_categorie' => $catData['code_categorie'],
                        'description' => $catData['description'] ?? null,
                        'user_add_id' => auth()->id(),
                        'statut' => '1',
                    ]);
                    $statsCreation['categories_creees']++;
                }

                foreach ($catData['items'] as $itemData) {
                    // Vérifier si l'item existe déjà dans cette catégorie
                    $itemExistant = Item::where('categorie_domaine_id', $categorie->id)
                        ->where('nom_item', $itemData['nom_item'])
                        ->first();

                    if ($itemExistant) {
                        // Mettre à jour l'item existant
                        $itemExistant->update([
                            'description' => $itemData['description'] ?? $itemExistant->description,
                            'type' => $itemData['type'] ?? $itemExistant->type,
                            'user_update_id' => auth()->id(),
                        ]);
                        $item = $itemExistant;
                        $statsCreation['items_existants']++;
                    } else {
                        // Créer l'item s'il n'existe pas
                        $item = Item::create([
                            'id' => Str::uuid(),
                            'categorie_domaine_id' => $categorie->id,
                            'nom_item' => $itemData['nom_item'],
                            'description' => $itemData['description'] ?? null,
                            'type' => $itemData['type'] ?? 'texte',
                            'user_add_id' => auth()->id(),
                            'statut' => '1',
                        ]);
                        $statsCreation['items_crees']++;
                    }

                    // Gérer les options (supprimer les anciennes et créer les nouvelles)
                    if (in_array($itemData['type'], ['liste', 'checkbox']) && isset($itemData['options'])) {
                        // Supprimer les anciennes options
                        ItemOption::where('item_id', $item->id)->delete();

                        // Créer les nouvelles options
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
                            $statsCreation['options_creees']++;
                        }
                    } elseif (!in_array($itemData['type'], ['liste', 'checkbox'])) {
                        // Supprimer les options si le type n'est plus liste ou checkbox
                        ItemOption::where('item_id', $item->id)->delete();
                    }
                }
            }

            DB::commit();

            $this->isValidated = true;

            // Message personnalisé selon les statistiques
            $message = 'Données enregistrées avec succès ! ';
            if ($statsCreation['categories_creees'] > 0 || $statsCreation['items_crees'] > 0) {
                $details = [];
                if ($statsCreation['categories_creees'] > 0) {
                    $details[] = $statsCreation['categories_creees'] . ' nouvelle(s) catégorie(s)';
                }
                if ($statsCreation['items_crees'] > 0) {
                    $details[] = $statsCreation['items_crees'] . ' nouveau(x) item(s)';
                }
                $message .= implode(' et ', $details) . ' créé(s).';
            }
            if ($statsCreation['categories_existantes'] > 0 || $statsCreation['items_existants'] > 0) {
                $details = [];
                if ($statsCreation['categories_existantes'] > 0) {
                    $details[] = $statsCreation['categories_existantes'] . ' catégorie(s)';
                }
                if ($statsCreation['items_existants'] > 0) {
                    $details[] = $statsCreation['items_existants'] . ' item(s)';
                }
                $message .= ' ' . implode(' et ', $details) . ' mis à jour.';
            }

            session()->flash('success', $message);

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
        $this->reset(['generatedData', 'editableData', 'errorMessage', 'isValidated', 'warningMessage']);
    }

    public function render()
    {
        return view('livewire.generateur-conformite');
    }
}
