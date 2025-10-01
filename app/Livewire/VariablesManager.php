<?php

namespace App\Livewire;

use App\Models\Variable;
use App\Models\Categorie;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Livewire\Attributes\On;

class VariablesManager extends Component
{
    public array $categoriesList = [];
    public array $variablesList  = [];
    public string  $mode = 'create';
    public ?string $currentId = null;
    public ?int    $numeroVariable = null;
    public string  $nom_variable = '';
    public string  $type = '';
    public ?string $categorie_id = null;
    public ?string $statutMode = null;
    public ?string $tauxVariable = null;
    public ?string $tauxVariableEntreprise = null;
    public bool    $variableImposable = false;
    public array   $selectedVariables = [];
    public $categories = [];
    public $variables = [];

    public function mount(): void
    {
        $this->hydrateLists();
        $this->loadData();
    }
    public function loadData()
    {
        $this->categories = Categorie::all()->toArray();
        $this->variables = Variable::all()->toArray();
    }
    private function hydrateLists(): void
    {
        $this->categoriesList = Categorie::query()
            ->orderBy('nom_categorie')
            ->get(['id', 'nom_categorie'])
            ->map(fn($c) => ['id' => $c->id, 'label' => $c->nom_categorie])
            ->toArray();

        $this->variablesList = Variable::query()
            ->with('categorie:id,nom_categorie')
            ->orderBy('nom_variable')
            ->get()
            ->map(fn($v) => [
                'id'   => $v->id,
                'name' => $v->nom_variable,
                'type' => $v->type,
                'categoryId'   => $v->categorie_id,
                'categoryName' => optional($v->categorie)->nom_categorie ?? '(Sans catégorie)',
            ])
            ->toArray();
    }

    public function render()
    {
        return view('livewire.variables-manager', [
            'categoriesList' => $this->categoriesList,
            'variablesList'  => $this->variablesList,
            'type'           => $this->type,
            'statutMode'     => $this->statutMode,
            'currentId'      => $this->currentId,
        ]);
    }

    private function nextNumero(): int
    {
        return DB::transaction(function () {
            $row = DB::table('variables')->selectRaw('MAX(numeroVariable) as maxnum')->lockForUpdate()->first();
            $max = $row?->maxnum ? (int)$row->maxnum : null;
            return $max ? $max + 10 : 100;
        });
    }

    private function rules(): array
    {
        $rules = [
            'nom_variable'  => ['required', 'string', 'max:150'],
            'type'          => ['required', Rule::in(['gain', 'deduction'])],
            'categorie_id'  => ['required', 'uuid', 'exists:categories,id'],
            'selectedVariables'   => ['array'],
            'selectedVariables.*' => ['uuid', 'exists:variables,id'],
        ];

        if ($this->type === 'deduction') {
            $rules['statutMode'] = ['required', Rule::in(['cotisation', 'sans_cotisation'])];
            if ($this->statutMode === 'cotisation') {
                $rules['tauxVariable']           = ['required', 'numeric', 'gte:0', 'lte:100'];
                $rules['tauxVariableEntreprise'] = ['required', 'numeric', 'gte:0', 'lte:100'];
            } else {
                $rules['tauxVariable'] = ['required', 'numeric', 'gte:0', 'lte:100'];
            }
        }
        return $rules;
    }

    #[On('open-create')]
    public function openCreate(?string $categorieId = null): void
    {
        $this->reset([
            'mode',
            'currentId',
            'numeroVariable',
            'nom_variable',
            'type',
            'categorie_id',
            'statutMode',
            'tauxVariable',
            'tauxVariableEntreprise',
            'variableImposable',
            'selectedVariables'
        ]);
        $this->mode = 'create';
        $this->categorie_id   = $categorieId;
        $this->numeroVariable = $this->nextNumero();
        $this->dispatch('show-variable-modal');
    }

    #[On('open-edit')]
    public function openEdit(string $id): void
    {
        $v = Variable::with('categorie')->findOrFail($id);

        $this->mode              = 'edit';
        $this->currentId         = $v->id;
        $this->numeroVariable    = $v->numeroVariable ?? $this->nextNumero();
        $this->nom_variable      = $v->nom_variable;
        $this->type              = $v->type ?? ''; // peut être vide
        $this->categorie_id      = $v->categorie_id;
        $this->variableImposable = (bool) $v->variableImposable;

        // 👉 Important : on positionne le mode d'après la DB,
        //    sans conditionner à $this->type === 'deduction'
        if ((bool) $v->statutVariable === true) {
            // Variable de cotisation
            $this->statutMode              = 'cotisation';
            $this->tauxVariable            = $v->tauxVariable !== null ? (string) $v->tauxVariable : null;
            $this->tauxVariableEntreprise  = $v->tauxVariableEntreprise !== null ? (string) $v->tauxVariableEntreprise : null;
        } else {
            // Variable sans cotisation (avec taux possible)
            $this->statutMode              = $v->tauxVariable==true ?'sans_cotisation':null;
            $this->tauxVariable            = $v->tauxVariable !== null ? (string) $v->tauxVariable : null;
            $this->tauxVariableEntreprise  = null; // pas de patronal en "sans"
        }

        // Associations déjà choisies
        $this->selectedVariables = DB::table('variable_associers')
            ->where('variableBase_id', $v->id)
            ->pluck('variableAssocier_id')
            ->toArray();

        $this->loadData();
        $this->dispatch('show-variable-modal');
    }


    public function save(): void
    {
        $this->validate($this->rules());
        $isCotisation = $this->type === 'deduction' && $this->statutMode === 'cotisation';

        $variable = DB::transaction(function () use ($isCotisation) {
            if ($this->mode === 'create') {
                $v = Variable::create([
                    'categorie_id'           => $this->categorie_id,
                    'nom_variable'           => $this->nom_variable,
                    'type'                   => $this->type,
                    'statutVariable'         => $isCotisation ? 1 : 0,
                    'variableImposable'      => $this->variableImposable,
                    'tauxVariable'           => $this->tauxVariable,
                    'tauxVariableEntreprise' => $isCotisation ? $this->tauxVariableEntreprise : null,
                    'numeroVariable'         => $this->nextNumero(),
                    'statut'                 => true,
                ]);
                $this->currentId = $v->id;
            } else {
                $v = Variable::whereKey($this->currentId)->lockForUpdate()->firstOrFail();
                $numero = $v->numeroVariable ?? $this->nextNumero();
                $v->update([
                    'categorie_id'           => $this->categorie_id,
                    'nom_variable'           => $this->nom_variable,
                    'type'                   => $this->type,
                    'statutVariable'         => $isCotisation,
                    'variableImposable'      => $this->variableImposable,
                    'tauxVariable'           => $this->tauxVariable,
                    'tauxVariableEntreprise' => $isCotisation ? $this->tauxVariableEntreprise : null,
                    'numeroVariable'         => $numero,
                ]);
            }

            DB::table('variable_associers')->where('variableBase_id', $this->currentId)->delete();
            if ($this->selectedVariables) {
                $rows = [];
                $now = now();
                foreach (array_unique($this->selectedVariables) as $assocId) {
                    if ($assocId === $this->currentId) continue;
                    $rows[] = [
                        'variableBase_id'     => $this->currentId,
                        'variableAssocier_id' => $assocId,
                        'statut'              => '1',
                        'created_at'          => $now,
                        'updated_at'          => $now,
                    ];
                }
                if ($rows) {
                    DB::table('variable_associers')->upsert(
                        $rows,
                        ['variableBase_id', 'variableAssocier_id'],
                        ['statut', 'updated_at']
                    );
                }
            }

            return Variable::with('categorie')->find($this->currentId);
        });

        $this->hydrateLists();

        $payload = [
            'id'   => $variable->id,
            'name' => $variable->nom_variable,
            'statutVariable'         => (bool)$variable->statutVariable,
            'variableImposable'      => (bool)$variable->variableImposable,
            'tauxVariable'           => $variable->tauxVariable,
            'tauxVariableEntreprise' => $variable->tauxVariableEntreprise,
            'type' => $variable->type,
            'categorie' => [
                'id' => $variable->categorie?->id,
                'nom_categorie' => $variable->categorie?->nom_categorie,
            ],
            'numeroVariable' => $variable->numeroVariable,
        ];
        $this->loadData();
        $this->dispatch('hide-variable-modal');
        // Ajout d'un délai de 100ms
        usleep(100000); // 100 millisecondes
        $this->dispatch('variable-upserted', payload: $payload);
        $this->dispatch('notify', ['type' => 'success', 'msg' => 'Enregistré']);
        $this->mode = 'create';
    }

    #[On('delete-variable')]
    public function delete(string $id): void
    {
        DB::transaction(function () use ($id) {
            DB::table('variable_associers')->where('variableBase_id', $id)->orWhere('variableAssocier_id', $id)->delete();
            Variable::whereKey($id)->delete();
        });

        // IMPORTANT: dispatch the event with the ID
        $this->dispatch('variable-deleted', id: $id);
        $this->dispatch('notify', ['type' => 'success', 'msg' => 'Supprimée']);
        $this->dispatch('hide-variable-modal');
    }
}
