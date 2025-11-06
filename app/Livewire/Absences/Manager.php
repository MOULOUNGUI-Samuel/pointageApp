<?php

// app/Livewire/Absences/Manager.php
namespace App\Livewire\Absences;

use App\Models\Absence;
use App\Models\User;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use Illuminate\Validation\Rule;
use App\Services\Beams; // Ensure this is the correct namespace for the Beams class
use App\Notifications\NewAlert; // Import the NewAlert class
use Pusher\PushNotifications\PushNotifications; // Import the PushNotifications class
use Illuminate\Support\Facades\Notification; // Import the Notification facade
use App\Events\ServiceCreated; // Import the ServiceCreated event

class Manager extends Component
{
    use WithPagination, WithFileUploads;

    protected $paginationTheme = 'bootstrap';

    /** Portée Entreprise */
    public string $forCompanyId;
    protected string $companyKey = 'entreprise_id'; // nom de la colonne dans `users`

    /** Formulaire (droite) */
    public bool $isEditing = false;
    public ?string $selectedId = null;
    public ?string $form_user_id = null;   // ✅ utilisateur choisi dans le formulaire
    public string $type = '';
    public ?string $start_datetime = null;
    public ?string $end_datetime = null;
    public ?string $reason = null;
    public $attachment; // TemporaryUploadedFile|null

    /** Filtres Livewire */
    public string $search = '';
    public string $statusFilter = '';
    public ?string $dateFrom = null;
    public ?string $dateTo   = null;

    /** États UI (gauche) */
    public array $openAcc = [];
    public array $confirmDelete = [];
    public array $showReject = [];
    public array $justif = [];

    /** Retour d’absence */
    public ?string $returnTargetId = null;
    public ?string $returnNotes = null;
    public ?string $return_confirmed_at = null;
    public $returnAttachment;

    /** Unicité */
    public bool $hasConflict = false;
    public array $conflicts = [];

    /** Pour le <select> des employés de l’entreprise */
    public array $companyUsers = [];
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

    public function mount(): void
    {
        $this->forCompanyId = session('entreprise_id');
        $this->code_demande = $this->generateUniqueCode(); // code prêt par défaut
        // Charge la liste des employés de l’entreprise
        $this->companyUsers = User::query()
            ->where($this->companyKey, $this->forCompanyId)
            ->orderBy('nom')
            ->where('statu_user', 1)
            ->get(['id', 'nom', 'prenom'])
            ->map(fn($u) => ['id' => (string) $u->id, 'label' => $u->nom . ' ' . $u->prenom])
            ->all();
    }

    public function openForm2($id = null): void
    {
        $this->resetValidation();
        $this->isEditing = false;
        $this->selectedId = null;
        $this->form_user_id = null;
        $this->type = 'congé_payé';
        $this->start_datetime = $this->end_datetime = $this->reason = null;
        $this->attachment = null;

        if ($id) {
            // ⛔️ plus de $this->forUserId
            $a = $this->findInCompany($id);

            $this->isEditing = true;
            $this->selectedId = (string) $a->id;
            $this->form_user_id = (string) $a->user_id;   // ✅ important pour le <select>
            $this->type = $a->type;
            $this->code_demande = $a->code_demande;       // on garde le code existant
            $this->start_datetime = optional($a->start_datetime)->format('Y-m-d\TH:i');
            $this->end_datetime   = optional($a->end_datetime)->format('Y-m-d\TH:i');
            $this->reason = $a->reason;
        } else {
            // nouvelle demande => générer un code
            $this->code_demande = $this->generateUniqueCode();
        }

        // pas nécessaire si tu utilises data-bs-toggle/data-bs-target
        // $this->dispatch('showAbsenceModal');
    }

    /** Pagination indépendante par entreprise */
    public function getPageName(): string
    {
        return 'absences_page_company_' . $this->forCompanyId;
    }

    /** Trouve une absence bornée à l’entreprise */
    private function findInCompany(string $id): Absence
    {
        return Absence::where('id', $id)
            ->whereHas('user', function ($q) {
                $q->where($this->companyKey, $this->forCompanyId);
            })
            ->firstOrFail();
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

    /** Vérifie chevauchement pour l’utilisateur choisi */
    private function passesUniqueness(): bool
    {
        if (!$this->form_user_id || !$this->start_datetime || !$this->end_datetime) {
            return false;
        }

        $conflicts = Absence::where('user_id', $this->form_user_id)
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

    /** Accordéon piloté Livewire */
    public function toggleAccordion(string $id): void
    {
        $this->openAcc[$id] = !data_get($this->openAcc, $id, false);
    }

    /** Suppression (exclusive) */
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

    /** Rejet (exclusive) */
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

    /** Retour (exclusive) */
    public function showReturnBox(string $id): void
    {
        $a = $this->findInCompany($id);

        if ($a->status !== 'approuvé') {
            session()->flash('error', "Retour possible uniquement pour une demande approuvée.");
            return;
        }
        if ($a->return_confirmed_at) {
            session()->flash('error', "Retour déjà confirmé.");
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
        $this->returnNotes = null;
        $this->return_confirmed_at = null;
        $this->returnAttachment = null;
    }

    public function confirmReturn(): void
    {
        if (!$this->returnTargetId) {
            session()->flash('error', "Aucune demande sélectionnée.");
            return;
        }

        $a = $this->findInCompany($this->returnTargetId);
        if ($a->status !== 'approuvé') {
            session()->flash('error', "Retour possible uniquement pour une demande approuvée.");
            return;
        }

        $onTime = now()->lte($a->end_datetime); // ✅ auto "à l'heure" vs "en retard"

        // ✅ Validation adaptée selon le statut
        $this->validate([
            'returnNotes'         => $onTime ? 'nullable|string|max:5000' : 'required|string|min:5|max:5000',
            'return_confirmed_at' => $onTime ? 'required|date' : 'nullable|date', // ✅ Facultatif si en retard
            'returnAttachment'    => 'nullable|file|max:5120|mimes:pdf,jpg,jpeg,png,doc,docx',
        ], [], ['returnNotes' => 'description (retour)']);

        $returnPath = $this->returnAttachment
            ? $this->returnAttachment->store('absences/returns', 'public')
            : null;

        // ✅ Utiliser la date actuelle si en retard (pas de champ saisi)
        $returnConfirmedAt = $onTime
            ? $this->return_confirmed_at
            : ($this->return_confirmed_at ?: now());

        $a->update([
            'return_confirmed_at'    => $returnConfirmedAt,
            'returned_on_time'       => $onTime,
            'return_notes'           => $this->returnNotes,
            'return_attachment_path' => $returnPath,
        ]);

        $this->cancelReturn();
        session()->flash('success', "Retour confirmé.");
        $this->dispatch('absencesUpdated'); // Livewire v3

        $this->resetPage();
    }

    /** Formulaire (droite) */
    public function openForm($id = null): void
    {
        $this->openForm2($id);
    }

    public function save(): void
    {
        $this->validate();

        // Vérifie que l’utilisateur choisi appartient à l’entreprise
        $belongs = User::where('id', $this->form_user_id)
            ->where($this->companyKey, $this->forCompanyId)
            ->exists();

        if (!$belongs) {
            session()->flash('error', "L'utilisateur choisi n'appartient pas à cette entreprise.");
            return;
        }

        if (!$this->passesUniqueness()) {
            session()->flash('error', 'Chevauchement détecté avec une autre demande de cet utilisateur.');
            return;
        }

        $path = $this->attachment
            ? $this->attachment->store('absences/attachments', 'public')
            : null;

        if ($this->isEditing && $this->selectedId) {
            $a = $this->findInCompany($this->selectedId);
            $data = [
                'user_id'        => $this->form_user_id, // tu peux autoriser le changement d’employé
                'type'           => $this->type,
                'start_datetime' => $this->start_datetime,
                'end_datetime'   => $this->end_datetime,
                'reason'         => $this->reason,
            ];
            if ($path) {
                if ($a->attachment_path) Storage::disk('public')->delete($a->attachment_path);
                $data['attachment_path'] = $path;
            }
            if ($a->status === 'approuvé') {
                session()->flash('error', "Impossible de modifier une demande approuvée.");
                return;
            }
            $a->update($data);
            // ---- Contenu commun
            $title = 'Demande mise à jour';
            $entreprise_id = $this->forCompanyId;
            $body = "La demande de type : {$this->type}, a été mise à jour pour : "
                . ($this->form_user_id ? User::find($this->form_user_id)->nom . ' ' . User::find($this->form_user_id)->prenom : 'un utilisateur')
                . ", par " . (Auth::user() ? Auth::user()->nom . ' ' . Auth::user()->prenom : 'un administrateur') . ".";
            $url  = url('/services');

            // IDs des destinataires = tous les users de l'entreprise SAUF l'auteur
            $uids = User::where('entreprise_id', $entreprise_id)
                ->where('id', '!=', Auth::id())
                ->pluck('id')
                ->map(fn($id) => (string) $id)   // Beams exige des strings
                ->all();

            // Beams : publier vers les users (par batch si beaucoup d’IDs)
            foreach (array_chunk($uids, 1000) as $batch) {          // 1000 = limite confortable
                app(Beams::class)->publishToUsers(
                    $batch,
                    [
                        'title' => $title,
                        'body'  => $body,
                        'icon'  => asset('assets/img/authentication/logo_notif.JPG'),
                        'requireInteraction' => true,
                        'data'  => [
                            'url' => $url,
                            'type' => 'request_updated',
                            'entreprise' => $entreprise_id,
                        ],
                    ],
                    $url // deep_link
                );
            }
            // B) TEMPS RÉEL IN-APP + HISTORIQUE
            $recipients = User::where('entreprise_id', $entreprise_id)
                ->where('id', '!=', Auth::id())
                ->get();
            Notification::send($recipients, new NewAlert($title, $body, $url));
            // Option : historiser aussi chez l’auteur
            $author = request()->user();
            $author?->notify(new NewAlert($title, $body, $url));
            $beams = new PushNotifications([
                'instanceId' => env('BEAMS_INSTANCE_ID'),
                'secretKey'  => env('BEAMS_SECRET_KEY'),
            ]);
            $res = $beams->publishToInterests(
                ['hello'], // intérêt sur lequel ton navigateur est abonné
                [
                    'web' => [
                        'notification' => [
                            'title' => $title,
                            'body'  => $body,
                            'icon'  => asset('assets/img/authentication/logo_notif.JPG'),
                            'deep_link' => url('/notifications'), // optionnel
                        ],
                    ],
                ]
            );

            // C) (Optionnel) événement broadcast "entreprise.{id}" si tu l’utilises pour d'autres listeners
            broadcast(new ServiceCreated($this->type, $entreprise_id));


            session()->flash('success', 'Demande mise à jour.');
            $this->dispatch('absencesUpdated'); // Livewire v3
        } else {
            Absence::create([
                'user_id'         => $this->form_user_id,
                'type'            => $this->type,
                'start_datetime'  => $this->start_datetime,
                'end_datetime'    => $this->end_datetime,
                'reason'          => $this->reason,
                'code_demande'   => $this->code_demande, // <-- N'OUBLIE PAS
                'attachment_path' => $path,
                'status'          => 'brouillon',
            ]);

            // ---- Contenu commun
            $title = 'Demande créée';
            $entreprise_id = $this->forCompanyId;
            $body = "Une demande de type : {$this->type}, a été créée pour : "
                . ($this->form_user_id ? User::find($this->form_user_id)->nom . ' ' . User::find($this->form_user_id)->prenom : 'un utilisateur')
                . ", par " . (Auth::user() ? Auth::user()->nom . ' ' . Auth::user()->prenom : 'un administrateur') . ".";
            $url  = url('/notifications');

            // IDs des destinataires = tous les users de l'entreprise SAUF l'auteur
            $uids = User::where('entreprise_id', $entreprise_id)
                ->where('id', '!=', Auth::id())
                ->pluck('id')
                ->map(fn($id) => (string) $id)   // Beams exige des strings
                ->all();

            // Beams : publier vers les users (par batch si beaucoup d’IDs)
            foreach (array_chunk($uids, 1000) as $batch) {          // 1000 = limite confortable
                app(Beams::class)->publishToUsers(
                    $batch,
                    [
                        'title' => $title,
                        'body'  => $body,
                        'icon'  => asset('assets/img/authentication/logo_notif.JPG'),
                        'requireInteraction' => true,
                        'data'  => [
                            'url' => $url,
                            'type' => 'request_created',
                            'entreprise' => $entreprise_id,
                        ],
                    ],
                    $url // deep_link
                );
            }

            // B) TEMPS RÉEL IN-APP + HISTORIQUE
            $recipients = User::where('entreprise_id', $entreprise_id)
                ->where('id', '!=', Auth::id())
                ->get();

            Notification::send($recipients, new NewAlert($title, $body, $url));

            // Option : historiser aussi chez l’auteur
            $author = request()->user();
            $author?->notify(new NewAlert($title, $body, $url));

            $beams = new PushNotifications([
                'instanceId' => env('BEAMS_INSTANCE_ID'),
                'secretKey'  => env('BEAMS_SECRET_KEY'),
            ]);

            $res = $beams->publishToInterests(
                ['hello'], // intérêt sur lequel ton navigateur est abonné
                [
                    'web' => [
                        'notification' => [
                            'title' => $title,
                            'body'  => $body,
                            'icon'  => asset('assets/img/authentication/logo_notif.JPG'),
                            'deep_link' => url('/notifications'), // optionnel
                        ],
                    ],
                ]
            );

            // C) (Optionnel) événement broadcast "entreprise.{id}" si tu l’utilises pour d'autres listeners
            broadcast(new ServiceCreated($this->type, $entreprise_id));


            session()->flash('success', 'Demande créée.');
            $this->dispatch('absencesUpdated'); // Livewire v3
        }

        $this->openForm();  // reset form
        $this->resetPage();
    }

    /** Actions liste */
    public function submit(string $id): void
    {
        $a = $this->findInCompany($id);
        if (in_array($a->status, ['brouillon', 'rejeté'])) {
            $a->update(['status' => 'soumis']);

            session()->flash('success', 'Demande soumise.');
            $this->dispatch('absencesUpdated'); // Livewire v3
            $this->resetPage();
        }
    }

    public function delete(string $id): void
    {
        $a = $this->findInCompany($id);
        if ($a->status === 'approuvé') {
            session()->flash('error', 'Impossible de supprimer une demande approuvée.');
            return;
        }
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
        $a = $this->findInCompany($id);
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
        ], [], ["justif.$id" => 'justification']);

        $a = $this->findInCompany($id);
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


    protected function rules(): array
    {
        return [
            // 'required|uuid'  ⛔️
            'form_user_id'   => ['required', 'exists:users,id'],  // ✅
            'type'           => 'required',
            'code_demande'   => ['required', 'string', 'max:40', Rule::unique('absences', 'code_demande')->ignore($this->selectedId)],
            'start_datetime' => 'required|date',
            'end_datetime'   => 'required|date|after:start_datetime',
            'reason'         => 'nullable|string|max:5000',
            'attachment'     => 'nullable|file|max:5120|mimes:pdf,jpg,jpeg,png,doc,docx',
        ];
    }

    public function render()
    {
        $items = Absence::query()
            ->with(['user:id,nom,prenom,fonction,' . $this->companyKey]) // récupérer les infos nécessaires
            ->whereHas('user', function ($uq) {
                $uq->where($this->companyKey, $this->forCompanyId);
            })
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

        return view('livewire.absences.manager', compact('items'));
    }
}
