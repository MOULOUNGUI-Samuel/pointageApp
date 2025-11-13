<?php

namespace App\Livewire\Settings;

use App\Models\Item;
use App\Models\ConformitySubmission;
use App\Models\ConformityAnswer;
use App\Models\PeriodeItem;
use App\Services\SubmissionIAService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Http\UploadedFile;
use Livewire\Attributes\On;
use Smalot\PdfParser\Parser; // ⬅️ ajouter

class SubmitWizard extends Component
{
    use WithFileUploads;

    public string $traceId;

    // IDs
    public ?string $itemId       = null;
    public ?string $submissionId = null;   // Pour modification
    public bool    $isEditing    = false;  // ⬅️ nouveau

    // Données du formulaire
    public string $textValue      = '';
    public array  $selectedOptions = [];
    public        $uploadedFile   = null;

    // États IA
    public bool  $showAiSuggestions   = false;
    public bool  $loadingAiSuggestions = false;
    public array $aiSuggestions       = [];

    public bool  $showPreSubmitAnalysis = false;
    public bool  $loadingAnalysis       = false;
    public array $analysisResults       = [];

    // États UI
    public bool   $showConfirmation = false;
    public string $errorMessage     = '';
    public string $successMessage   = '';

    // Données chargées
    public ?Item                $item       = null;
    public ?PeriodeItem         $periode    = null;
    public ?ConformitySubmission $submission = null;

    protected $listeners = [
        'open-submit-modal2' => 'initializeForSubmit',
    ];

    public function mount(): void
    {
        $this->traceId = (string) Str::uuid();
        Log::info('[SubmitWizard] mount()', ['trace_id' => $this->traceId]);
    }

    #[On('open-submit-modal2')]
    public function initializeForSubmit(string $itemId, ?string $submissionId = null): void
    {
        // Reset du formulaire, mais on garde traceId
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
            'successMessage',
        ]);

        $this->itemId       = $itemId;
        $this->submissionId = $submissionId;
        $this->isEditing    = !is_null($submissionId);   // ⬅️ flag create / update

        Log::info('[SubmitWizard] initializeForSubmit()', [
            'trace_id'      => $this->traceId,
            'item_id'       => $itemId,
            'submission_id' => $submissionId,
            'is_editing'    => $this->isEditing,
        ]);

        $this->loadData();
    }
    /**
     * Extraction de texte à partir d'un fichier uploadé
     */
    private function extractFileText(UploadedFile $file, int $maxLength = 20000): ?string
    {
        try {
            $mimeType  = $file->getMimeType();
            $extension = strtolower($file->getClientOriginalExtension());
            $size      = $file->getSize();
            $maxSize   = 200000; // 200 KB max pour lecture brute

            $content = null;

            // 1) Fichiers purement texte
            $textExtensions = ['txt', 'csv', 'json', 'xml', 'md', 'log'];
            if (in_array($extension, $textExtensions) && $size <= $maxSize) {
                $content = file_get_contents($file->getRealPath());
            }
            // 2) PDF
            elseif ($extension === 'pdf') {
                $parser = new Parser();
                $pdf    = $parser->parseFile($file->getRealPath());
                $text   = trim($pdf->getText());

                if ($text !== '') {
                    $content = $text;
                } else {
                    $content = "PDF détecté mais aucun texte exploitable (probablement un scan ou un PDF protégé).";
                }
            }
            // 3) Autres formats (docx/xlsx/etc.) -> à traiter plus tard si tu veux
            else {
                $content = "Fichier ($mimeType) non pris en charge pour l'extraction automatique du texte.";
            }

            if (! $content) {
                return null;
            }

            // Limiter la taille stockée pour éviter de faire exploser la BDD
            if (mb_strlen($content) > $maxLength) {
                return mb_substr($content, 0, $maxLength) . "\n... (contenu tronqué)";
            }

            return $content;
        } catch (\Exception $e) {
            Log::warning('[SubmitWizard] Erreur lors de l’extraction de texte du fichier', [
                'trace_id' => $this->traceId,
                'error'    => $e->getMessage(),
            ]);

            return null;
        }
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
                // On laisse le fichier déjà uploadé affiché dans la vue
            }
        }
    }

    /**
     * Demander des suggestions IA
     */
    public function requestAiSuggestions(): void
    {
        $this->loadingAiSuggestions = true;
        $this->showAiSuggestions    = false;
        $this->errorMessage         = '';

        try {
            $service    = app(SubmissionIAService::class);
            $entreprise = auth()->user()->Entreprise;

            $result = $service->suggererContenu(
                $this->item,
                $entreprise,
                $this->periode
            );

            if ($result['success']) {
                $this->aiSuggestions    = $result['suggestions'];
                $this->showAiSuggestions = true;
            } else {
                $this->errorMessage = "Erreur lors de la génération des suggestions : " . ($result['error'] ?? 'Erreur inconnue');
            }
        } catch (\Exception $e) {
            Log::error('[SubmitWizard] Erreur suggestions IA', [
                'trace_id' => $this->traceId,
                'error'    => $e->getMessage(),
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
            $this->textValue      = $this->aiSuggestions['exemple_texte'];
            $this->successMessage = "Suggestion appliquée ! Vous pouvez la modifier selon vos besoins.";
        }
    }

    /**
     * Analyser avant soumission
     */
    public function analyzeBeforeSubmit(): void
    {
        $this->validate($this->getRules());

        $this->loadingAnalysis      = true;
        $this->showPreSubmitAnalysis = false;
        $this->errorMessage         = '';

        try {
            $service    = app(SubmissionIAService::class);
            $entreprise = auth()->user()->Entreprise;

            $submissionData = $this->prepareSubmissionData();

            $result = $service->analyserAvantSoumission(
                $this->item,
                $submissionData,
                $entreprise
            );

            if ($result['success']) {
                $this->analysisResults      = $result['analyse'];
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
                'error'    => $e->getMessage(),
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
        } elseif ($type === 'documents' || $type === 'file') {
            if ($this->uploadedFile) {
                $file      = $this->uploadedFile;
                $fileName  = $file->getClientOriginalName();
                $mimeType  = $file->getMimeType();
                $fileSize  = $file->getSize();
                $extension = strtolower($file->getClientOriginalExtension());
        
                $content = $this->extractFileText($file);
        
                return [
                    'fichier' => [
                        'nom'                => $fileName,
                        'type'               => $mimeType,
                        'taille'             => round($fileSize / 1024, 2) . ' KB',
                        'extension'          => $extension,
                        'contenu_document'   => $content,
                        'contenu_disponible' => ! is_null($content),
                    ],
                ];
            }
        
            return [
                'fichier' => [
                    'nom'                => null,
                    'contenu_document'   => null,
                    'contenu_disponible' => false,
                    'message'            => 'Aucun fichier uploadé',
                ],
            ];

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
        $this->errorMessage     = '';
    }

    /**
     * Annuler la confirmation
     */
    public function cancelSubmit(): void
    {
        $this->showConfirmation = false;
    }

    /**
     * Soumettre définitivement (create / update)
     */
    public function confirmSubmit(): void
    {
        $this->validate($this->getRules());

        DB::beginTransaction();

        try {
            $entrepriseId = session('entreprise_id');
            $userId       = auth()->id();

            if ($this->isEditing && $this->submissionId) {
                // 🔁 UPDATE
                $submission = ConformitySubmission::findOrFail($this->submissionId);

                // On efface les anciennes réponses
                $submission->answers()->delete();

                $submission->update([
                    'status'       => 'soumis',
                    'submitted_at' => now(),
                    'submitted_by' => $userId,
                ]);

                $action = 'modifiée';
            } else {
                // 🆕 CREATE
                $submission = ConformitySubmission::create([
                    'item_id'       => $this->itemId,
                    'entreprise_id' => $entrepriseId,
                    'periode_id'    => $this->periode?->id,
                    'status'        => 'soumis',
                    'submitted_at'  => now(),
                    'submitted_by'  => $userId,
                ]);

                $this->submissionId = $submission->id;
                $this->isEditing    = true;
                $action             = 'créée';
            }

            // Réponses
            $this->createAnswers($submission);

            DB::commit();

            Log::info('[SubmitWizard] Soumission enregistrée', [
                'trace_id'      => $this->traceId,
                'submission_id' => $submission->id,
                'action'        => $action,
            ]);

            // Event pour rafraîchir le board parent
            $this->dispatch('settings-submitted', id: $submission->id);
            $this->dispatch('wizard-config-reload');

            // Fermer la modale côté JS (Bootstrap)
            $this->dispatch('close-submit-modal2');

            session()->flash('success', "Soumission {$action} avec succès !");
        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('[SubmitWizard] Erreur soumission', [
                'trace_id' => $this->traceId,
                'error'    => $e->getMessage(),
                'trace'    => $e->getTraceAsString(),
            ]);

            $this->errorMessage    = "Erreur lors de la soumission : " . $e->getMessage();
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
                'kind'          => 'texte',
                'value_text'    => $this->textValue,
                'position'      => 1,
            ]);
        } elseif ($type === 'documents' || $type === 'file') {   // ⬅️ cohérent avec getRules()
            if ($this->uploadedFile) {
                $path    = $this->uploadedFile->store('conformity-documents', 'public');
                $content = $this->extractFileText($this->uploadedFile);
        
                ConformityAnswer::create([
                    'submission_id'  => $submission->id,
                    'kind'           => 'documents',
                    'file_path'      => $path,
                    'extracted_text' => $content,   // ⬅️ on stocke ici
                    'position'       => 1,
                ]);
            }
        } elseif ($type === 'liste' || $type === 'checkbox') {
            $selected = array_keys(array_filter($this->selectedOptions));

            $labels = $this->item->options()
                ->whereIn('value', $selected)
                ->pluck('label', 'value')
                ->toArray();

            ConformityAnswer::create([
                'submission_id' => $submission->id,
                'kind'          => $type,
                'value_json'    => [
                    'selected' => $selected,
                    'labels'   => array_values($labels),
                ],
                'position' => 1,
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
                'textValue' => 'required|string|min:10',
            ];
        } elseif ($type === 'file') {
            return [
                'uploadedFile' => $this->submissionId
                    ? 'nullable|file|max:10240'
                    : 'required|file|max:10240',
            ];
        } elseif ($type === 'liste' || $type === 'checkbox') {
            return [
                'selectedOptions' => 'required|array|min:1',
            ];
        }

        return [];
    }

    public function render()
    {
       
        return view('livewire.settings.submit-wizard');
    }
}
