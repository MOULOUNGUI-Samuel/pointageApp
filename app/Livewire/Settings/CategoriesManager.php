<?php

namespace App\Livewire\Settings;

use App\Models\CategorieDomaine;
use Illuminate\Support\Facades\Auth;
use App\Models\Domaine;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Str;
use Livewire\Attributes\On;   // <= important en v3

class CategoriesManager extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    // Form
    public ?string $selectedId = null;
    public string  $nom_categorie = '';
    public string  $code_categorie = '';
    public ?string $description = null;
    public string  $statut = '1';
    public ?string $domaine_id = null;     // ✅ parent
    public bool    $isEditing = false;
    // Entreprise
    public string $entrepriseId;
    // UI
    public string $search = '';
    public ?string $confirmingDeleteId = null;  // ✅ confirmation suppression

    // pour savoir si les champs clés ont changé
    protected ?string $original_domaine_id = null;
    protected ?string $original_nom_categorie = null;

    // Sélecteurs
    public array $domaines = [];
    /** Fabrique les initiales d'une chaîne (ex: "Ressources Humaines" => "RH") */
    // private function initials(string $label): string
    // {
    //     // retire tout sauf lettres / espaces, split, prend la 1ère lettre de chaque mot
    //     $words = preg_split('/\s+/u', trim(preg_replace('/[^[:alpha:]\s]/u', ' ', $label)));
    //     $letters = array_map(fn($w) => Str::upper(Str::substr($w, 0, 1)), array_filter($words));
    //     return implode('', $letters);
    // }

    // /** Calcule l'index suivant (01, 02, ...) pour un préfixe "RH-CD" */
    // private function nextIndexForPrefix(string $prefix): string
    // {
    //     // on cherche le max des suffixes existants pour ce préfixe
    //     $last = CategorieDomaine::where('code_categorie', 'like', $prefix . '-%')
    //         ->orderByRaw("LPAD(REGEXP_REPLACE(code_categorie, '.*-', ''), 4, '0') DESC")
    //         ->value('code_categorie');

    //     if (!$last) {
    //         return '01';
    //     }

    //     // récupère la partie après le dernier tiret
    //     $num = (int) Str::afterLast($last, '-');
    //     return str_pad((string)($num + 1), 2, '0', STR_PAD_LEFT);
    // }
    private function initials(string $label): string
    {
        $label = preg_replace('/[^[:alpha:]\s]/u', ' ', $label ?? '');
        $words = preg_split('/\s+/u', trim($label));
        $letters = array_map(fn($w) => Str::upper(Str::substr($w, 0, 1)), array_filter($words));
        return implode('', $letters);
    }

    private function nextIndexForPrefix(string $prefix, ?string $excludeId = null): string
    {
        $q = CategorieDomaine::where('code_categorie', 'like', $prefix . '-%');
        if ($excludeId) {
            $q->where('id', '!=', $excludeId);
        }

        $last = $q->orderByRaw(
            "LPAD(REGEXP_REPLACE(code_categorie, '.*-', ''), 4, '0') DESC"
        )->value('code_categorie');

        if (!$last) return '01';
        $num = (int) Str::afterLast($last, '-');
        return str_pad((string)($num + 1), 2, '0', STR_PAD_LEFT);
    }

    /** Recalcule le code quand domaine OU nom de catégorie change */
    private function recalcCode(): void
    {
        if (!$this->domaine_id || !$this->nom_categorie) {
            $this->code_categorie = '';
            return;
        }

        $dom = Domaine::find($this->domaine_id);
        if (!$dom) {
            $this->code_categorie = '';
            return;
        }

        $domPrefix = $this->initials($dom->nom_domaine);
        $catPrefix = $this->initials($this->nom_categorie);
        $prefix    = $domPrefix . '-' . $catPrefix;

        // exclure l'enregistrement en cours (pour ne pas “se compter soi-même”)
        $index = $this->nextIndexForPrefix($prefix, $this->selectedId);
        $this->code_categorie = $prefix . '-' . $index;
    }


    public function updatedDomaineId(): void
    {
        $this->recalcCode();
    }

    public function updatedNomCategorie(): void
    {
        $this->recalcCode();
    }


    protected function rules(): array
    {
        return [
            'nom_categorie'  => 'required|string|max:190',
            'code_categorie' => [
                'required',
                'string',
                'max:50',
                Rule::unique('categorie_domaines', 'code_categorie')->ignore($this->selectedId),
            ],
            'description'    => 'nullable|string|max:1000',
            'statut'         => 'required|in:0,1',
            'domaine_id'   => [
                'nullable',
                'uuid',
                'exists:domaines,id',
                // empêche de se définir soi-même comme parent
                Rule::notIn([$this->selectedId]),
            ],
        ];
    }

    public function mount(): void
    {
        $this->entrepriseId = (string) session('entreprise_id');
        $this->reloadDomaines(); // charge $domaines pour le <select>
        $this->code_categorie = ''; // pas de code tant que domaine/catégorie pas saisis
    }

    /** Recharge la liste des domaines */
    public function reloadDomaines(): void
    {
        $this->domaines = Domaine::query()
            ->orderBy('nom_domaine')
            ->get(['id', 'nom_domaine'])
            ->map(fn($d) => ['id' => $d->id, 'label' => $d->nom_domaine])
            ->all();
    }

    /** Écoute l’événement émis par DomainesManager */
    #[On('domaines-updated')]
    public function onDomainesUpdated(): void
    {
        $this->reloadDomaines();
    }

    #[On('domaines-deleted')]
    public function onDomainesDeleted(string $id): void
    {
        // Recharge la liste
        $this->reloadDomaines();
        // Si le domaine actuellement sélectionné vient d’être supprimé, on le vide
        if ($this->domaine_id === $id) {
            $this->domaine_id = null;
        }
    }

    public function openForm(?string $id = null): void
    {
        $this->resetValidation();
        $this->isEditing = false;
        $this->selectedId = null;

        $this->nom_categorie  = '';
        $this->domaine_id     = null;
        $this->description    = null;
        $this->statut         = '1';
        $this->code_categorie = '';

        $this->original_domaine_id   = null;
        $this->original_nom_categorie = null;

        if ($id) {
            $c = CategorieDomaine::findOrFail($id);
            $this->isEditing      = true;
            $this->selectedId     = $c->id;
            $this->nom_categorie  = $c->nom_categorie;
            $this->domaine_id     = $c->domaine_id;
            $this->description    = $c->description;
            $this->statut         = (string) $c->statut;
            $this->code_categorie = $c->code_categorie;

            // garde les originaux pour savoir si on doit recalculer au moment du save
            $this->original_domaine_id    = $c->domaine_id;
            $this->original_nom_categorie = $c->nom_categorie;
        }
    }



    public function save(): void
    {
        $this->validate();

        if ($this->isEditing && $this->selectedId) {
            // détecte un changement impactant le code
            $keysChanged = (
                $this->domaine_id !== $this->original_domaine_id ||
                $this->nom_categorie !== $this->original_nom_categorie
            );

            if ($keysChanged) {
                $this->recalcCode();
                // revalider l'unicité avec ignore($this->selectedId)
                $this->validate([
                    'code_categorie' => [
                        'required',
                        'string',
                        'max:50',
                        Rule::unique('categorie_domaines', 'code_categorie')->ignore($this->selectedId),
                    ],
                ]);
            }

            $c = CategorieDomaine::findOrFail($this->selectedId);
            $c->update([
                'nom_categorie'  => $this->nom_categorie,
                'code_categorie' => $this->code_categorie,
                'description'    => $this->description,
                'statut'         => $this->statut,
                'domaine_id'     => $this->domaine_id,
                'user_update_id' => Auth::id(),
            ]);

            // maj des originaux après save
            $this->original_domaine_id    = $this->domaine_id;
            $this->original_nom_categorie = $this->nom_categorie;

            session()->flash('success', 'Catégorie mise à jour.');
        } else {
            // création : double sécurité → recalc + revalide l’unicité
            $this->recalcCode();
            $this->validate([
                'code_categorie' => [
                    'required',
                    'string',
                    'max:50',
                    Rule::unique('categorie_domaines', 'code_categorie'),
                ],
            ]);

            CategorieDomaine::create([
                'nom_categorie'  => $this->nom_categorie,
                'code_categorie' => $this->code_categorie,
                'description'    => $this->description,
                'statut'         => $this->statut,
                'domaine_id'     => $this->domaine_id,
                'user_add_id'    => Auth::id(),
            ]);

            session()->flash('success', 'Catégorie créée.');
        }

        $this->openForm();
        $this->resetPage();
        $this->dispatch('categories-updated');
    }


    // ✅ flux de confirmation de suppression
    public function confirmDelete(string $id): void
    {
        $this->confirmingDeleteId = $id;
    }
    public function cancelDelete(): void
    {
        $this->confirmingDeleteId = null;
    }
    public function deleteConfirmed(): void
    {
        if (!$this->confirmingDeleteId) return;

        CategorieDomaine::findOrFail($this->confirmingDeleteId)->delete();
        $deletedId = $this->confirmingDeleteId;
        $this->confirmingDeleteId = null;

        session()->flash('success', 'Catégorie supprimée.');
        $this->resetPage();
        if ($this->selectedId === $deletedId) $this->openForm();
        // après suppression
        $this->dispatch('categories-updated');
        $this->dispatch('categories-deleted', id: $deletedId); // passe l'id supprimé
    }

    public function render()
    {
        $items = CategorieDomaine::query()
            ->with('domaine:id,nom_domaine')
            ->when(
                $this->search,
                fn($q) =>
                $q->where('nom_categorie', 'like', "%{$this->search}%")
                    ->orWhere('code_categorie', 'like', "%{$this->search}%")
            )
            ->orderBy('nom_categorie')
            ->paginate(8, pageName: 'categories_p');

        return view('livewire.settings.categories-manager', compact('items'));
    }
}
