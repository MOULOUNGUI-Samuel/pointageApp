<?php

namespace App\Livewire\Settings;

use App\Models\Item;
use App\Models\ConformitySubmission;
use App\Models\ConformityAnswer;
use App\Models\PeriodeItem;
use App\Services\SubmissionIAService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\Attributes\On;

class SubmitWizard extends Component
{
    use WithFileUploads;

    public string $traceId;

    // IDs
    public ?string $itemId = null;
    public ?string $submissionId = null; // Pour modification

    // Données du formulaire
    public string $textValue = '';
    public array $selectedOptions = [];
    public $uploadedFile = null;

    // États IA
    public bool $showAiSuggestions = false;
    public bool $loadingAiSuggestions = false;
    public array $aiSuggestions = [];
    
    public bool $showPreSubmitAnalysis = false;
    public bool $loadingAnalysis = false;
    public array $analysisResults = [];

    // États UI
    public bool $showConfirmation = false;
    public string $errorMessage = '';
    public string $successMessage = '';

    // Données chargées
    public ?Item $item = null;
    public ?PeriodeItem $periode = null;
    public ?ConformitySubmission $submission = null;

    protected $listeners = [
        'open-submit-modal2' => 'initializeForSubmit'
    ];

    public function mount(): void
    {
        $this->traceId = (string) Str::uuid();
        Log::info('[SubmitWizard] mount()', ['trace_id' => $this->traceId]);
    }

    #[On('open-submit-modal2')]
    public function initializeForSubmit(string $itemId, ?string $submissionId = null): void
    {
        $this->reset([
            'textValue',
            'selectedOptions',
            'uploadedFile',
            'showAiSuggestions',
            'aiSuggestions',
            'showPreSubmitAnalysis',
            'analysisResults',
            'showConfirmation',
            'errorMessage',
            'successMessage'
        ]);

        $this->itemId = $itemId;
        $this->submissionId = $submissionId;

        Log::info('[SubmitWizard] initializeForSubmit()', [
            'trace_id' => $this->traceId,
            'item_id' => $itemId,
            'submission_id' => $submissionId
        ]);

        $this->loadData();
    }

    private function loadData(): void
    {
        $entrepriseId = session('entreprise_id');

        // Charger l'item
        $this->item = Item::with(['CategorieDomaine.Domaine', 'options'])
            ->findOrFail($this->itemId);

        // Charger la période active
        $today = now()->toDateString();
        $this->periode = PeriodeItem::where('item_id', $this->itemId)
            ->where('entreprise_id', $entrepriseId)
            ->where('statut', '1')
            ->whereDate('debut_periode', '<=', $today)
            ->whereDate('fin_periode', '>=', $today)
            ->first();

        // Si modification, charger la soumission existante
        if ($this->submissionId) {
            $this->submission = ConformitySubmission::with('answers.itemOption')
                ->findOrFail($this->submissionId);

            $this->loadExistingData();
        }
    }

    private function loadExistingData(): void
    {
        if (!$this->submission) return;

        foreach ($this->submission->answers as $answer) {
            if ($answer->kind === 'texte') {
                $this->textValue = $answer->value_text ?? '';
            } elseif ($answer->kind === 'liste' || $answer->kind === 'checkbox') {
                $selected = $answer->selectedMany();
                $this->selectedOptions = array_combine($selected, array_fill(0, count($selected), true));
            } elseif ($answer->kind === 'documents') {
                // On ne peut pas pré-charger un fichier, mais on peut afficher l'existant
                // Le fichier existant sera affiché dans la vue
            }
        }
    }

    /**
     * Demander des suggestions IA
     */
    public function requestAiSuggestions(): void
    {
        $this->loadingAiSuggestions = true;
        $this->showAiSuggestions = false;
        $this->errorMessage = '';

        try {
            $service = app(SubmissionIAService::class);
            $entreprise = auth()->user()->Entreprise;

            $result = $service->suggererContenu(
                $this->item,
                $entreprise,
                $this->periode
            );

            if ($result['success']) {
                $this->aiSuggestions = $result['suggestions'];
                $this->showAiSuggestions = true;
            } else {
                $this->errorMessage = "Erreur lors de la génération des suggestions : " . ($result['error'] ?? 'Erreur inconnue');
            }

        } catch (\Exception $e) {
            Log::error('[SubmitWizard] Erreur suggestions IA', [
                'trace_id' => $this->traceId,
                'error' => $e->getMessage()
            ]);
            $this->errorMessage = "Impossible de générer les suggestions. Veuillez réessayer.";
        } finally {
            $this->loadingAiSuggestions = false;
        }
    }

    /**
     * Appliquer une suggestion IA (pour texte)
     */
    public function applySuggestion(): void
    {
        if ($this->item->type === 'texte' && isset($this->aiSuggestions['exemple_texte'])) {
            $this->textValue = $this->aiSuggestions['exemple_texte'];
            $this->successMessage = "Suggestion appliquée ! Vous pouvez la modifier selon vos besoins.";
        }
    }

    /**
     * Analyser avant soumission
     */
    public function analyzeBeforeSubmit(): void
    {
        $this->validate($this->getRules());

        $this->loadingAnalysis = true;
        $this->showPreSubmitAnalysis = false;
        $this->errorMessage = '';

        try {
            $service = app(SubmissionIAService::class);
            $entreprise = auth()->user()->Entreprise;

            $submissionData = $this->prepareSubmissionData();

            $result = $service->analyserAvantSoumission(
                $this->item,
                $submissionData,
                $entreprise
            );

            if ($result['success']) {
                $this->analysisResults = $result['analyse'];
                $this->showPreSubmitAnalysis = true;

                if (!$result['can_submit']) {
                    $this->errorMessage = "Des problèmes ont été détectés. Veuillez les corriger avant de soumettre.";
                }
            } else {
                $this->errorMessage = "Erreur lors de l'analyse : " . ($result['error'] ?? 'Erreur inconnue');
            }

        } catch (\Exception $e) {
            Log::error('[SubmitWizard] Erreur analyse', [
                'trace_id' => $this->traceId,
                'error' => $e->getMessage()
            ]);
            $this->errorMessage = "Impossible d'analyser les données.";
        } finally {
            $this->loadingAnalysis = false;
        }
    }

    /**
     * Préparer les données de soumission pour l'analyse
     */
    private function prepareSubmissionData(): array
    {
        $type = $this->item->type;

        if ($type === 'texte') {
            return ['texte' => $this->textValue];
        } elseif ($type === 'liste' || $type === 'checkbox') {
            return ['options_selectionnees' => array_keys(array_filter($this->selectedOptions))];
        } elseif ($type === 'documents') {
            return ['fichier' => $this->uploadedFile ? $this->uploadedFile->getClientOriginalName() : 'Aucun fichier'];
        }

        return [];
    }

    /**
     * Préparer la soumission (afficher confirmation)
     */
    public function prepareSubmit(): void
    {
        $this->validate($this->getRules());
        $this->showConfirmation = true;
        $this->errorMessage = '';
    }

    /**
     * Annuler la confirmation
     */
    public function cancelSubmit(): void
    {
        $this->showConfirmation = false;
    }

    /**
     * Soumettre définitivement
     */
    public function confirmSubmit(): void
    {
        $this->validate($this->getRules());

        DB::beginTransaction();

        try {
            $entrepriseId = session('entreprise_id');
            $userId = auth()->id();

            // Créer ou mettre à jour la soumission
            if ($this->submissionId) {
                // Modification d'une soumission existante
                $submission = ConformitySubmission::findOrFail($this->submissionId);
                
                // Supprimer les anciennes réponses
                $submission->answers()->delete();
                
                // Mettre à jour la soumission
                $submission->update([
                    'status' => 'soumis',
                    'submitted_at' => now(),
                    'submitted_by' => $userId,
                ]);
                
                $action = 'modifiée';
            } else {
                // Nouvelle soumission
                $submission = ConformitySubmission::create([
                    'item_id' => $this->itemId,
                    'entreprise_id' => $entrepriseId,
                    'periode_id' => $this->periode?->id,
                    'status' => 'soumis',
                    'submitted_at' => now(),
                    'submitted_by' => $userId,
                ]);
                
                $action = 'créée';
            }

            // Créer les réponses selon le type
            $this->createAnswers($submission);

            DB::commit();

            Log::info('[SubmitWizard] Soumission enregistrée', [
                'trace_id' => $this->traceId,
                'submission_id' => $submission->id,
                'action' => $action
            ]);

            // Émettre événement pour rafraîchir le board
            $this->dispatch('settings-submitted');

            // Fermer la modale
            $this->dispatch('close-submit-modal');
            $this->dispatch('close-submit-modal2');

            session()->flash('success', "Soumission {$action} avec succès !");

        } catch (\Exception $e) {
            DB::rollBack();
            
            Log::error('[SubmitWizard] Erreur soumission', [
                'trace_id' => $this->traceId,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            $this->errorMessage = "Erreur lors de la soumission : " . $e->getMessage();
            $this->showConfirmation = false;
        }
    }

    /**
     * Créer les réponses selon le type d'item
     */
    private function createAnswers(ConformitySubmission $submission): void
    {
        $type = $this->item->type;

        if ($type === 'texte') {
            ConformityAnswer::create([
                'submission_id' => $submission->id,
                'kind' => 'texte',
                'value_text' => $this->textValue,
                'position' => 1
            ]);

        } elseif ($type === 'file') {
            if ($this->uploadedFile) {
                $path = $this->uploadedFile->store('conformity-documents', 'public');
                
                ConformityAnswer::create([
                    'submission_id' => $submission->id,
                    'kind' => 'documents',
                    'file_path' => $path,
                    'position' => 1
                ]);
            }

        } elseif ($type === 'liste' || $type === 'checkbox') {
            $selected = array_keys(array_filter($this->selectedOptions));
            
            // Récupérer les labels correspondants
            $labels = $this->item->options()
                ->whereIn('value', $selected)
                ->pluck('label', 'value')
                ->toArray();

            ConformityAnswer::create([
                'submission_id' => $submission->id,
                'kind' => $type,
                'value_json' => [
                    'selected' => $selected,
                    'labels' => array_values($labels)
                ],
                'position' => 1
            ]);
        }
    }

    /**
     * Règles de validation selon le type
     */
    public function getRules(): array
    {
        $type = $this->item->type;

        if ($type === 'texte') {
            return [
                'textValue' => 'required|string|min:10'
            ];
        } elseif ($type === 'documents') {
            return [
                'uploadedFile' => $this->submissionId ? 'nullable|file|max:10240' : 'required|file|max:10240'
            ];
        } elseif ($type === 'liste' || $type === 'checkbox') {
            return [
                'selectedOptions' => 'required|array|min:1'
            ];
        }

        return [];
    }

    public function render()
    {
        return view('livewire.settings.submit-wizard');
    }
}