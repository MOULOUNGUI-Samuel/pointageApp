<?php

namespace App\Livewire\Absences;

use App\Models\Absence;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use Illuminate\Validation\Rule;


class UniqueDemandChecker extends Component
{
    use WithPagination, WithFileUploads;

    protected $paginationTheme = 'bootstrap';

    public string $forUserId;
    public string $modalId;

    /** Formulaire (droite) */
    public bool $isEditing = false;
    public ?string $selectedId = null;
    public string $type = '';
    public ?string $start_datetime = null;
    public ?string $end_datetime = null;
    public ?string $reason = null;
    /** Upload (création/édition) */
    public $attachment; // Livewire TemporaryUploadedFile|null

    /** Filtres (gauche) */
    public string $search = '';
    public string $statusFilter = '';
    public ?string $dateFrom = null;  // 'Y-m-d'
    public ?string $dateTo   = null;  // 'Y-m-d'

    /** États UI (gauche) */
    public array $openAcc = [];           // [$id => bool]
    public array $confirmDelete = [];     // [$id => bool]
    public array $showReject = [];        // [$id => bool]
    public array $justif = [];            // [$id => string|null]

    /** Retour d’absence (gauche - exclusif) */
    public ?string $returnTargetId = null;
    public bool $returnOnTime = true;     // si false -> notes/doc obligatoires
    public ?string $returnNotes = null;
    public ?string $return_confirmed_at = null;
    public $returnAttachment;             // TemporaryUploadedFile|null

    /** Unicité */
    public bool $hasConflict = false;
    public array $conflicts = [];

    public ?string $code_demande = null;

    private function generateUniqueCode(): string
    {
        $societe = session('entreprise_nom') ?? 'ENT';
        $prefix = collect(explode(' ', $societe))
            ->filter()
            ->map(fn($w) => strtoupper(mb_substr($w, 0, 1)))
            ->implode('');

        do {
            $code = sprintf('%s-%s-%s', $prefix ?: 'ENT', Str::upper(Str::random(3)), now()->format('His'));
        } while (Absence::where('code_demande', $code)->exists());

        return $code;
    }

    public function mount(string $forUserId, string $modalId): void
    {
        $this->forUserId = $forUserId;
        $this->modalId   = $modalId;
        $this->code_demande = $this->generateUniqueCode(); // code prêt par défaut
    }
    
    // Pagination indépendante par user
    public function getPageName(): string
    {
        return 'absences_page_' . $this->forUserId;
    }

    protected function rules(): array
    {
        return [
            'type'           => 'required',
            'start_datetime' => 'required|date',
            'code_demande'   => [
                'required',
                'string',
                'max:40',
                Rule::unique('absences', 'code_demande')->ignore($this->selectedId),
            ],
            'end_datetime'   => 'required|date|after:start_datetime',
            'reason'         => 'nullable|string|max:5000',
            'attachment'     => 'nullable|file|max:5120|mimes:pdf,jpg,jpeg,png,doc,docx',
        ];
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }
    public function updatedStatusFilter()
    {
        $this->resetPage();
    }
    public function updatedDateFrom()
    {
        $this->resetPage();
    }
    public function updatedDateTo()
    {
        $this->resetPage();
    }

    /** Vérifie l’unicité (chevauchements) avant enregistrement */
    private function passesUniqueness(): bool
    {
        if (!$this->start_datetime || !$this->end_datetime) return false;

        $conflicts = Absence::where('user_id', $this->forUserId)
            ->when($this->selectedId, fn($q) => $q->where('id', '!=', $this->selectedId))
            ->whereIn('status', ['brouillon', 'soumis', 'approuvé'])
            ->where(function ($q) {
                $q->where('start_datetime', '<', $this->end_datetime)
                    ->where('end_datetime',   '>', $this->start_datetime);
            })
            ->get();

        if ($conflicts->isNotEmpty()) {
            $this->hasConflict = true;
            $this->conflicts = $conflicts->map(fn($a) => [
                'id'     => (string) $a->id,
                'type'   => $a->type,
                'start'  => optional($a->start_datetime)->format('d/m/Y H:i'),
                'end'    => optional($a->end_datetime)->format('d/m/Y H:i'),
                'status' => $a->status,
            ])->toArray();
            return false;
        }

        $this->hasConflict = false;
        $this->conflicts = [];
        return true;
    }

    /** Accordion piloté côté Livewire */
    public function toggleAccordion(string $id): void
    {
        $this->openAcc[$id] = !data_get($this->openAcc, $id, false);
    }

    /** Supprimer (bloc exclusif) */
    public function showDelete(string $id): void
    {
        $this->openAcc[$id] = true;
        $this->showReject = [];
        $this->confirmDelete = [];
        $this->returnTargetId = null;
        $this->confirmDelete[$id] = true;
    }
    public function cancelDelete(string $id): void
    {
        $this->confirmDelete[$id] = false;
    }

    /** Rejeter (bloc exclusif) */
    public function showRejectBox(string $id): void
    {
        $this->openAcc[$id] = true;
        $this->confirmDelete = [];
        $this->showReject = [];
        $this->returnTargetId = null;
        $this->showReject[$id] = true;
    }
    public function cancelReject(string $id): void
    {
        $this->showReject[$id] = false;
        unset($this->justif[$id]);
    }

    /** Retour d’absence (bloc exclusif) */
    public function showReturnBox(string $id): void
    {
        $a = Absence::where('user_id', $this->forUserId)->findOrFail($id);

        if ($a->status !== 'approuvé') {
            session()->flash('error', "Le retour ne peut être confirmé que pour une demande approuvée.");
            return;
        }
        if ($a->return_confirmed_at) {
            session()->flash('error', "Le retour est déjà confirmé pour cette demande.");
            return;
        }

        $this->openAcc[$id] = true;
        $this->confirmDelete = [];
        $this->showReject = [];
        $this->returnTargetId = $id;
        $this->returnNotes = null;
        $this->return_confirmed_at = null;
        $this->returnAttachment = null;
    }

    public function cancelReturn(): void
    {
        $this->returnTargetId = null;
        $this->returnOnTime = true;
        $this->returnNotes = null;
        $this->return_confirmed_at = null;
        $this->returnAttachment = null;
    }
    public function confirmReturn(): void
    {
        if (!$this->returnTargetId) {
            session()->flash('error', "Aucune demande sélectionnée pour le retour.");
            return;
        }

        $a = Absence::where('user_id', $this->forUserId)->findOrFail($this->returnTargetId);

        if ($a->status !== 'approuvé') {
            session()->flash('error', "Le retour ne peut être confirmé que pour une demande approuvée.");
            return;
        }

        // Auto: à l’heure si maintenant <= end_datetime
        $onTime = now()->lte($a->end_datetime);

        // Validation (retard => description requise; pièce jointe facultative)
        $this->validate([
            'returnNotes'      => $onTime ? 'nullable|string|max:5000' : 'required|string|min:5|max:5000',
            'return_confirmed_at' => 'required|date',
            'returnAttachment' => 'nullable|file|max:5120|mimes:pdf,jpg,jpeg,png,doc,docx',
        ], [], ['returnNotes' => 'description (retour)']);

        $returnPath = null;
        if ($this->returnAttachment) {
            $returnPath = $this->returnAttachment->store('absences/returns', 'public');
        }

        $a->update([
            'return_confirmed_at'    => $this->return_confirmed_at,
            'returned_on_time'       => $onTime,
            'return_notes'           => $this->returnNotes,
            'return_attachment_path' => $returnPath,
        ]);

        $this->cancelReturn();
        session()->flash('success', "Retour d'absence confirmé.");
        $this->dispatch('absencesUpdated'); // Livewire v3
        $this->resetPage();
    }


    /** Formulaire (droite) */
    public function openForm($id = null): void
    {
        $this->resetValidation();
        $this->isEditing = false;
        $this->selectedId = null;
        $this->type = 'congé_payé';
        $this->start_datetime = $this->end_datetime = $this->reason = null;
        $this->attachment = null;

        if ($id) {
            $a = Absence::where('user_id', $this->forUserId)->findOrFail($id);
            $this->isEditing = true;
            $this->selectedId = (string) $a->id;
            $this->type = $a->type;
            $this->code_demande = $a->code_demande; // garder le code existant
            $this->start_datetime = optional($a->start_datetime)->format('Y-m-d\TH:i');
            $this->end_datetime   = optional($a->end_datetime)->format('Y-m-d\TH:i');
            $this->reason = $a->reason;
        } else {
            $this->code_demande = $this->generateUniqueCode(); // nouvelle demande
        }
    }

    public function save(): void
    {
        $this->validate();
        if (!$this->passesUniqueness()) {
            session()->flash('error', 'Conflit détecté : chevauchement avec une autre demande.');
            return;
        }

        $path = null;
        if ($this->attachment) {
            $path = $this->attachment->store('absences/attachments', 'public');
        }

        if ($this->isEditing && $this->selectedId) {
            $a = Absence::where('user_id', $this->forUserId)->findOrFail($this->selectedId);
            $data = [
                'type'           => $this->type,
                'start_datetime' => $this->start_datetime,
                'end_datetime'   => $this->end_datetime,
                'reason'         => $this->reason,
            ];
            if ($path) {
                // (optionnel) supprime l’ancien
                if ($a->attachment_path) Storage::disk('public')->delete($a->attachment_path);
                $data['attachment_path'] = $path;
            }
            $a->update($data);
            session()->flash('success', 'Demande mise à jour.');
            $this->dispatch('absencesUpdated'); // Livewire v3
        } else {
            Absence::create([
                'user_id'        => $this->forUserId,
                'type'           => $this->type,
                'start_datetime' => $this->start_datetime,
                'end_datetime'   => $this->end_datetime,
                'reason'         => $this->reason,
                'code_demande'   => $this->code_demande, // <-- N'OUBLIE PAS
                'attachment_path' => $path,
                'status'         => 'brouillon',
            ]);
            session()->flash('success', 'Demande créée.');
            $this->dispatch('absencesUpdated'); // Livewire v3
        }

        $this->openForm();  // reset form
        $this->resetPage();
    }

    /** Actions liste */
    public function submit(string $id): void
    {
        $a = Absence::where('user_id', $this->forUserId)->findOrFail($id);
        if ($a->status === 'brouillon' || $a->status === 'rejeté') {
            $a->update(['status' => 'soumis']);
            session()->flash('success', 'Demande soumise.');
            $this->dispatch('absencesUpdated'); // Livewire v3
            $this->resetPage();
        }
    }

    public function delete(string $id): void
    {
        $a = Absence::where('user_id', $this->forUserId)->findOrFail($id);
        if ($a->status === 'approuvé') {
            session()->flash('error', 'Impossible de supprimer une demande approuvée.');
            return;
        }
        // (optionnel) supprime pièces jointes
        if ($a->attachment_path) Storage::disk('public')->delete($a->attachment_path);
        if ($a->return_attachment_path) Storage::disk('public')->delete($a->return_attachment_path);

        $a->delete();
        $this->confirmDelete[$id] = false;
        session()->flash('success', 'Demande supprimée.');
        $this->dispatch('absencesUpdated'); // Livewire v3
        $this->resetPage();
        if ($this->selectedId === $id) $this->openForm();
    }

    public function approve(string $id): void
    {
        $a = Absence::findOrFail($id);
        if ($a->status !== 'soumis') {
            session()->flash('error', 'Seules les demandes soumises peuvent être approuvées.');
            return;
        }
        $a->update([
            'status'      => 'approuvé',
            'approved_by' => auth()->id(),
            'approved_at' => now(),
        ]);
        session()->flash('success', 'Demande approuvée.');
        $this->dispatch('absencesUpdated'); // Livewire v3
        $this->resetPage();
    }

    public function confirmReject(string $id): void
    {
        $this->validate([
            "justif.$id" => 'required|string|min:5|max:8000'
        ], [], [
            "justif.$id" => 'justification'
        ]);

        $a = Absence::findOrFail($id);
        if ($a->status !== 'soumis') {
            session()->flash('error', 'Seules les demandes soumises peuvent être rejetées.');
            return;
        }

        $a->update([
            'status'        => 'rejeté',
            'approved_by'   => auth()->id(),
            'approved_at'   => now(),
            'justification' => $this->justif[$id] ?? null,
        ]);

        $this->showReject[$id] = false;
        unset($this->justif[$id]);

        session()->flash('success', 'Demande rejetée.');
        $this->dispatch('absencesUpdated'); // Livewire v3
        $this->resetPage();
    }

    public function render()
    {
        $items = Absence::where('user_id', $this->forUserId)
            ->when($this->statusFilter, fn($q) => $q->where('status', $this->statusFilter))
            ->when($this->search, function ($q) {
                $s = "%{$this->search}%";
                $q->where(function ($qq) use ($s) {
                    $qq->where('type', 'like', $s)
                        ->orWhere('reason', 'like', $s)
                        ->orWhere('justification', 'like', $s)
                        ->orWhere('return_notes', 'like', $s);
                });
            })
            ->when($this->dateFrom, fn($q) => $q->whereDate('start_datetime', '>=', $this->dateFrom))
            ->when($this->dateTo,   fn($q) => $q->whereDate('end_datetime',   '<=', $this->dateTo))
            ->orderByDesc('start_datetime')
            ->paginate(10, pageName: $this->getPageName());

        return view('livewire.absences.unique-demand-checker', compact('items'));
    }
}
