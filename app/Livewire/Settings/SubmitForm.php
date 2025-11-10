<?php

namespace App\Livewire\Settings;

use App\Models\Item;
use App\Models\PeriodeItem;
use App\Models\ConformitySubmission;
use App\Models\ConformityAnswer;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Validation\ValidationException;

class SubmitForm extends Component
{
    use WithFileUploads;

    public string $entrepriseId;
    public string $itemId;
    public ?string $submissionId = null;
    public bool $isEditing = false;

    public string $itemLabel = '';
    /** Type normalisé: texte|file|liste|checkbox */
    public string $type = 'texte';
    public ?string $periodeId = null;

    /** @var ?Item */
    public ?Item $item = null;
    /** @var Collection<int, mixed> */
    public Collection $options;

    public ?string $text_value = null;

    /** @var array<int, \Livewire\Features\SupportFileUploads\TemporaryUploadedFile> */
    public array $docs = [];

    /** @var array<int,string>  valeurs sélectionnées pour “liste” (multi-choix) */
    public array $list_values = [];

    /** @var array<int, string>  valeurs pour checkbox */
    public array $checkbox_values = [];

    /** @var array<int, string>  chemins des pièces déjà stockées */
    public array $existingDocs = [];

    public function mount(string $itemId, ?string $submissionId = null): void
    {
        $this->entrepriseId = (string) session('entreprise_id');

        $this->item      = Item::with('options')->findOrFail($itemId);
        $this->itemId    = $this->item->id;
        $this->itemLabel = $this->item->nom_item;

        $this->type    = $this->normalizeType($this->item->type);
        $this->options = $this->item->options ?? collect();

        // Période ACTIVE (statut=1 ET today ∈ [début, fin]) obligatoire pour ouvrir le formulaire
        $p = PeriodeItem::query()
            ->where('item_id', $this->item->id)
            ->where('entreprise_id', $this->entrepriseId)
            ->active() // <- nécessite le scopeActive() sur PeriodeItem
            ->latest('debut_periode')
            ->first();

        if (!$p) {
            abort(403, 'Aucune période active pour cet item.');
        }

        $this->periodeId = $p->id;

        // Édition d'une soumission en attente
        if ($submissionId) {
            $s = ConformitySubmission::with('answers')
                ->where('entreprise_id', $this->entrepriseId)
                ->where('item_id', $this->itemId)
                ->where('status', 'soumis')
                ->findOrFail($submissionId);

            $this->submissionId = $s->id;
            $this->isEditing = true;

            if ($this->type === 'texte') {
                $answer = $s->answers->firstWhere('kind', 'texte');
                $this->text_value = $answer?->value_text;
            } elseif ($this->type === 'liste') {
                $answer = $s->answers->firstWhere('kind', 'liste');
                $this->list_values = (array) data_get($answer?->value_json, 'selected', []);
            } elseif ($this->type === 'checkbox') {
                $answer = $s->answers->firstWhere('kind', 'checkbox');
                $this->checkbox_values = (array) data_get($answer?->value_json, 'selected', []);
            } elseif ($this->type === 'file') {
                $this->existingDocs = $s->answers
                    ->where('kind', 'documents')
                    ->pluck('file_path')
                    ->filter()
                    ->values()
                    ->all();
            }
        }
    }

    private function normalizeType(string $raw): string
    {
        return match (strtolower($raw)) {
            'file', 'document', 'documents' => 'file',
            'text', 'texte'                 => 'texte',
            'list', 'liste'                 => 'liste',
            'checkbox', 'checks'            => 'checkbox',
            default                         => $raw,
        };
    }

    /** S’assure qu’on a TOUJOURS une période active au moment d’enregistrer */
    private function assertPeriodeStillActive(): void
    {
        if (!$this->periodeId) {
            throw ValidationException::withMessages([
                'periode' => 'Période introuvable.',
            ]);
        }

        $still = PeriodeItem::query()
            ->where('id', $this->periodeId)
            ->active()
            ->exists();

        if (!$still) {
            throw ValidationException::withMessages([
                'periode' => 'La période n’est plus active. Veuillez recharger la page.',
            ]);
        }
    }

    private function rulesFor(string $mode): array
    {
        $mode = strtolower($mode);
        if (!in_array($mode, ['create', 'update'], true)) {
            throw ValidationException::withMessages(['_mode' => 'Mode invalide.']);
        }

        // Fichiers en MAJ : fichiers optionnels
        if ($this->type === 'file' && $mode === 'update') {
            return ['docs.*' => 'nullable|file|max:10240|mimes:pdf,jpg,jpeg,png,doc,docx'];
        }

        // Règles selon le type
        return match ($this->type) {
            'texte'    => ['text_value'      => 'required|string|min:2'],
            'file'     => ['docs.*'          => 'required|file|max:10240|mimes:pdf,jpg,jpeg,png,doc,docx'],
            'liste'    => ['list_values'     => 'required|array|min:1', 'list_values.*' => 'string'],
            'checkbox' => ['checkbox_values' => 'required|array|min:1'],
            default    => [], // pas de __noop
        };
    }

    /** create|update */
    public function save(string $mode): void
    {
        $mode  = strtolower($mode);

        // Vérifie que la période est TOUJOURS active
        $this->assertPeriodeStillActive();

        $rules = $this->rulesFor($mode);
        if (!empty($rules)) {
            $this->validate($rules);
        }

        if ($mode === 'update') {
            $this->persistUpdate();
        } else {
            $this->persistCreate();
        }
    }

    private function persistCreate(): void
    {
        $submission = ConformitySubmission::create([
            'entreprise_id'   => $this->entrepriseId,
            'item_id'         => $this->itemId,
            'periode_item_id' => $this->periodeId,
            'submitted_by'    => Auth::id(),
            'status'          => 'soumis',
            'submitted_at'    => now(),
        ]);

        if ($this->type === 'texte') {
            ConformityAnswer::create([
                'submission_id' => $submission->id,
                'kind'          => 'texte',
                'value_text'    => $this->text_value,
                'position'      => 1,
            ]);
        } elseif ($this->type === 'file') {
            foreach ($this->docs as $i => $file) {
                $path = $file->store('conformity/docs', 'public');
                ConformityAnswer::create([
                    'submission_id' => $submission->id,
                    'kind'          => 'documents', // canonique
                    'file_path'     => $path,
                    'position'      => $i + 1,
                ]);
            }
        } elseif ($this->type === 'liste') {
            $labels = $this->options
                ->filter(fn($o) => in_array(($o->value ?: $o->label), $this->list_values, true))
                ->pluck('label')->values()->all();

            ConformityAnswer::create([
                'submission_id' => $submission->id,
                'kind'          => 'liste',
                'value_json'    => [
                    'selected' => array_values($this->list_values),
                    'labels'   => $labels,
                ],
                'position'      => 1,
            ]);
        } elseif ($this->type === 'checkbox') {
            $labels = $this->options
                ->filter(fn($o) => in_array(($o->value ?: $o->label), $this->checkbox_values, true))
                ->pluck('label')->values()->all();

            ConformityAnswer::create([
                'submission_id' => $submission->id,
                'kind'          => 'checkbox',
                'value_json'    => [
                    'selected' => array_values($this->checkbox_values),
                    'labels'   => $labels
                ],
                'position'      => 1,
            ]);
        }

        $this->dispatch('notify', type: 'success', message: 'Déclaration envoyée pour validation.');
        $this->dispatch('settings-submitted', id: $submission->id);
        $this->dispatch('wizard-config-reload');

        // reset cohérent (plus de list_value)
        $this->reset(['text_value', 'docs', 'list_values', 'checkbox_values']);
    }

    private function persistUpdate(): void
    {
        if (!$this->submissionId) {
            $this->dispatch('notify', type: 'error', message: 'Soumission inexistante pour mise à jour.');
            throw ValidationException::withMessages(['_submission' => 'Soumission inexistante pour mise à jour.']);
        }

        $s = ConformitySubmission::with('answers')
            ->where('entreprise_id', $this->entrepriseId)
            ->where('item_id', $this->itemId)
            ->where('status', 'soumis')
            ->findOrFail($this->submissionId);

        if ($this->type === 'texte') {
            $a = $s->answers()->firstOrCreate(['kind' => 'texte'], ['position' => 1]);
            $a->update(['value_text' => $this->text_value]);
        } elseif ($this->type === 'liste') {
            $labels = $this->options
                ->filter(fn($o) => in_array(($o->value ?: $o->label), $this->list_values, true))
                ->pluck('label')->values()->all();

            $a = $s->answers()->firstOrCreate(['kind' => 'liste'], ['position' => 1]);
            $a->update([
                'value_json' => [
                    'selected' => array_values($this->list_values),
                    'labels'   => $labels,
                ],
            ]);
        } elseif ($this->type === 'checkbox') {
            $labels = $this->options
                ->filter(fn($o) => in_array(($o->value ?: $o->label), $this->checkbox_values, true))
                ->pluck('label')->values()->all();

            $a = $s->answers()->firstOrCreate(['kind' => 'checkbox'], ['position' => 1]);
            $a->update([
                'value_json' => [
                    'selected' => array_values($this->checkbox_values),
                    'labels'   => $labels
                ],
            ]);
        } elseif ($this->type === 'file') {
            if (!empty($this->docs)) {
                // supprimer les anciens documents
                foreach ($s->answers()->where('kind', 'documents')->get() as $old) {
                    if ($old->file_path && Storage::disk('public')->exists($old->file_path)) {
                        Storage::disk('public')->delete($old->file_path);
                    }
                    $old->delete();
                }
                // ajouter les nouveaux
                foreach ($this->docs as $i => $file) {
                    $path = $file->store('conformity/docs', 'public');
                    $s->answers()->create([
                        'kind'      => 'documents',
                        'file_path' => $path,
                        'position'  => $i + 1,
                    ]);
                }
            }
        }

        $this->dispatch('notify', type: 'success', message: 'Soumission mise à jour.');
        $this->dispatch('settings-submitted', id: $s->id);
        $this->dispatch('wizard-config-reload');
    }

    public function render()
    {
        return view('livewire.settings.submit-form', [
            'item'         => $this->item,
            'options'      => $this->options,
            'existingDocs' => $this->existingDocs,
            'isEditing'    => $this->isEditing,
            'type'         => $this->type,
        ]);
    }
}
