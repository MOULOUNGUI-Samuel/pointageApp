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

class SubmitForm extends Component
{
    use WithFileUploads;

    public string $entrepriseId;
    public string $itemId;
    public ?string $submissionId = null;   // <-- NEW
    public bool $isEditing = false;        // <-- NEW

    public string $itemLabel = '';
    public string $type = 'texte';         // texte|documents|liste|checkbox
    public ?string $periodeId = null;

    /** Mémos */
    public ?Item $item = null;
    public Collection $options;

    // Champs
    public ?string $text_value = null;
    public array   $docs = [];             // nouveaux fichiers
    public ?string $list_value = null;
    public array   $checkbox_values = [];

    // Pour affichage des docs existants en édition
    public array $existingDocs = [];       // <-- NEW (file_path[])

    public function mount(string $itemId, ?string $submissionId = null): void
    {
        $this->entrepriseId = (string) session('entreprise_id');

        $this->item      = Item::with('options')->findOrFail($itemId);
        $this->itemId    = $this->item->id;
        $this->itemLabel = $this->item->nom_item;
        $this->type      = $this->item->type;
        $this->options   = $this->item->options ?? collect();

        // Période active
        $p = PeriodeItem::where('item_id', $this->item->id)
            ->where('entreprise_id', $this->entrepriseId)
            ->where('statut', '1')
            ->latest('fin_periode')
            ->first();
        $this->periodeId = $p?->id;

        // Si une soumission pending est fournie -> mode édition
        if ($submissionId) {
            $s = ConformitySubmission::with('answers')
                ->where('entreprise_id', $this->entrepriseId)
                ->where('item_id', $this->itemId)
                ->where('status', 'soumis')
                ->findOrFail($submissionId);

            $this->submissionId = $s->id;
            $this->isEditing = true;

            // Préremplir les champs selon le type
            $answer = $s->answers->firstWhere('kind', $this->type);

            if ($this->type === 'texte') {
                $this->text_value = $answer?->value_text;
            } elseif ($this->type === 'liste') {
                $this->list_value = data_get($answer?->value_json, 'selected');
            } elseif ($this->type === 'checkbox') {
                $this->checkbox_values = (array) data_get($answer?->value_json, 'selected', []);
            } elseif ($this->type === 'documents') {
                // On liste les docs existants, l’utilisateur peut garder tel quel
                $this->existingDocs = $s->answers
                    ->where('kind', 'documents')
                    ->pluck('file_path')
                    ->filter()
                    ->values()
                    ->all();
            }
        }
    }

    protected function rules(): array
    {
        // Si édition "documents", on rend l'upload optionnel (nullable)
        if ($this->type === 'documents' && $this->isEditing) {
            return ['docs.*' => 'nullable|file|max:10240|mimes:pdf,jpg,jpeg,png,doc,docx'];
        }

        return match ($this->type) {
            'texte'     => ['text_value' => 'required|string|min:2'],
            'documents' => ['docs.*'     => 'file|max:10240|mimes:pdf,jpg,jpeg,png,doc,docx'],
            'liste'     => ['list_value' => 'required|string'],
            'checkbox'  => ['checkbox_values' => 'array|min:1'],
            default     => [],
        };
    }

    public function save(): void
    {
        $this->validate();

        // ----- MODE ÉDITION (soumission pending existante) -----
        if ($this->isEditing && $this->submissionId) {
            $s = ConformitySubmission::with('answers')
                ->where('entreprise_id', $this->entrepriseId)
                ->where('item_id', $this->itemId)
                ->where('status', 'soumis')
                ->findOrFail($this->submissionId);

            // MàJ des réponses selon le type
            if ($this->type === 'texte') {
                $a = $s->answers()->firstOrCreate(['kind' => 'texte'], ['position' => 1]);
                $a->update(['value_text' => $this->text_value]);
            } elseif ($this->type === 'liste') {
                $opt = $this->options->first(fn($o) => ($o->value ?: $o->label) === $this->list_value);
                $a = $s->answers()->firstOrCreate(['kind' => 'liste'], ['position' => 1]);
                $a->update([
                    'value_json' => ['selected' => $this->list_value, 'label' => $opt?->label],
                ]);
            } elseif ($this->type === 'checkbox') {
                $labels = $this->options
                    ->filter(fn($o) => in_array(($o->value ?: $o->label), $this->checkbox_values, true))
                    ->pluck('label')->values()->all();
                $a = $s->answers()->firstOrCreate(['kind' => 'checkbox'], ['position' => 1]);
                $a->update([
                    'value_json' => ['selected' => array_values($this->checkbox_values), 'labels' => $labels],
                ]);
            } elseif ($this->type === 'documents') {
                // Si l’utilisateur a ajouté de nouveaux fichiers, on remplace les anciens
                if (!empty($this->docs)) {
                    // Supprimer les anciens fichiers (optionnel)
                    foreach ($s->answers()->where('kind', 'documents')->get() as $old) {
                        if ($old->file_path && Storage::disk('public')->exists($old->file_path)) {
                            Storage::disk('public')->delete($old->file_path);
                        }
                        $old->delete();
                    }
                    foreach ($this->docs as $i => $file) {
                        $path = $file->store('conformity/docs', 'public');
                        $s->answers()->create([
                            'kind'      => 'documents',
                            'file_path' => $path,
                            'position'  => $i + 1,
                        ]);
                    }
                }
                // sinon, on garde les fichiers existants
            }

            session()->flash('success', 'Soumission mise à jour.');
            $this->dispatch('settings-submitted', id: $s->id);
            $this->dispatch('wizard-config-reload');
            return;
        }

        // ----- MODE CRÉATION (pas de pending) -----
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
        } elseif ($this->type === 'documents') {
            // si aucun fichier fourni en création, on renvoie une erreur UX douce
            if (empty($this->docs)) {
                $this->addError('docs', 'Veuillez joindre au moins un document.');
                return;
            }
            foreach ($this->docs as $i => $file) {
                $path = $file->store('conformity/docs', 'public');
                ConformityAnswer::create([
                    'submission_id' => $submission->id,
                    'kind'          => 'documents',
                    'file_path'     => $path,
                    'position'      => $i + 1,
                ]);
            }
        } elseif ($this->type === 'liste') {
            $opt = $this->options->first(fn($o) => ($o->value ?: $o->label) === $this->list_value);
            ConformityAnswer::create([
                'submission_id' => $submission->id,
                'kind'          => 'liste',
                'value_json'    => ['selected' => $this->list_value, 'label' => $opt?->label],
                'position'      => 1,
            ]);
        } elseif ($this->type === 'checkbox') {
            $labels = $this->options
                ->filter(fn($o) => in_array(($o->value ?: $o->label), $this->checkbox_values, true))
                ->pluck('label')->values()->all();
            ConformityAnswer::create([
                'submission_id' => $submission->id,
                'kind'          => 'checkbox',
                'value_json'    => ['selected' => array_values($this->checkbox_values), 'labels' => $labels],
                'position'      => 1,
            ]);
        }

        session()->flash('success', 'Déclaration envoyée pour validation.');
        $this->dispatch('settings-submitted', id: $submission->id);
        $this->dispatch('wizard-config-reload');

        $this->reset(['text_value', 'docs', 'list_value', 'checkbox_values']);
    }

    public function render()
    {
        return view('livewire.Settings.submit-form', [
            'item'          => $this->item,
            'options'       => $this->options,
            'existingDocs'  => $this->existingDocs,
            'isEditing'     => $this->isEditing,
        ]);
    }
}
