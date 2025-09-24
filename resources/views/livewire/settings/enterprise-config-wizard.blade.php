<div>
    {{-- Bandeau feedback --}}
    @if (session('success'))
        <div class="alert alert-success rounded-pill alert-dismissible fade show">
            <strong><i class="fas fa-check me-2"></i>{{ session('success') }}</strong>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Stepper header --}}
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div class="fw-bold {{ $step===1 ? 'text-primary' : 'text-muted' }}">1. Domaines</div>
        <div class="fw-bold {{ $step===2 ? 'text-primary' : 'text-muted' }}">2. Catégories</div>
        <div class="fw-bold {{ $step===3 ? 'text-primary' : 'text-muted' }}">3. Items</div>
    </div>
    <hr>

    {{-- ÉTAPE 1 : Domaines --}}
    @if($step===1)
        <div class="mb-2">
            <input type="text" class="form-control" placeholder="Rechercher un domaine…" wire:model.debounce.300ms="searchDomaines">
        </div>
        <div class="row row-cols-1 row-cols-md-4">
            @forelse ($domaines as $d)
                <div class="col">
                    <input type="checkbox" class="btn-check"  id="dom-{{ $d['id'] }}"
                    wire:click="toggleDomaine('{{ $d['id'] }}')"
                    @checked(in_array($d['id'], $selectedDomainIds, true))>
                    <label class="btn rounded shadow" for="dom-{{ $d['id'] }}"> {{ $d['label'] }}</label>
                </div>
            @empty
                <div class="col"><em class="text-muted">Aucun domaine.</em></div>
            @endforelse
        </div>

        <div class="d-flex justify-content-end mt-3">
            <button class="btn btn-primary"
                    wire:click="nextFromDomains"
                    @disabled(empty($selectedDomainIds))
                    wire:loading.attr="disabled"
                    wire:target="nextFromDomains,toggleDomaine,searchDomaines">
                Continuer
                <span class="spinner-border spinner-border-sm ms-2" wire:loading wire:target="nextFromDomains"></span>
            </button>
        </div>
    @endif

    {{-- ÉTAPE 2 : Catégories --}}
    @if($step===2)
        <div class="d-flex justify-content-between mb-2">
            <button class="btn btn-light" wire:click="backToDomains">
                <i class="fas fa-arrow-left me-1"></i> Domaines
            </button>
            <input type="text" class="form-control w-auto" style="min-width:280px"
                   placeholder="Rechercher une catégorie…" wire:model.debounce.300ms="searchCategories">
        </div>

        <div class="row row-cols-1 row-cols-md-2 g-2">
            @forelse ($categories as $c)
                <div class="col">
                    <input type="checkbox" class="btn-check"   id="cat-{{ $c['id'] }}"
                    wire:click="toggleCategorie('{{ $c['id'] }}')"
                    @checked(in_array($c['id'], $selectedCategoryIds, true))>
                    <label class="btn rounded shadow" for="cat-{{ $c['id'] }}"> {{ $c['label'] }}</label>
                </div>
            @empty
                <div class="col"><em class="text-muted">Aucune catégorie pour les domaines sélectionnés.</em></div>
            @endforelse
        </div>

        <div class="d-flex justify-content-end mt-3 gap-2">
            <button class="btn btn-outline-secondary" wire:click="backToDomains">
                <i class="fas fa-arrow-left me-1"></i> Retour
            </button>
            <button class="btn btn-primary"
                    wire:click="nextFromCategories"
                    @disabled(empty($selectedCategoryIds))
                    wire:loading.attr="disabled"
                    wire:target="nextFromCategories,toggleCategorie,searchCategories">
                Continuer
                <span class="spinner-border spinner-border-sm ms-2" wire:loading wire:target="nextFromCategories"></span>
            </button>
        </div>
    @endif

    {{-- ÉTAPE 3 : Items --}}
    @if($step===3)
        <div class="d-flex justify-content-between mb-2">
            <button class="btn btn-light" wire:click="backToCategories">
                <i class="fas fa-arrow-left me-1"></i> Catégories
            </button>
            <input type="text" class="form-control w-auto" style="min-width:280px"
                   placeholder="Rechercher un item…" wire:model.debounce.300ms="searchItems">
        </div>

        <div class="row row-cols-1 row-cols-md-2 g-2">
            @forelse ($items as $it)
                <div class="col">
                    <input type="checkbox" class="btn-check"   id="it-{{ $it['id'] }}"
                    wire:click="toggleItem('{{ $it['id'] }}')"
                    @checked(in_array($it['id'], $selectedItemIds, true))>
                    <label class="btn rounded shadow" for="it-{{ $it['id'] }}">
                        {{ $it['label'] }}</label>
                </div>
            @empty
                <div class="col"><em class="text-muted">Aucun item pour les catégories sélectionnées.</em></div>
            @endforelse
        </div>

        <div class="d-flex justify-content-end mt-3 gap-2">
            <button class="btn btn-outline-secondary" wire:click="backToCategories">
                <i class="fas fa-arrow-left me-1"></i> Retour
            </button>
            <button class="btn btn-success"
                    wire:click="finish"
                    @disabled(empty($selectedItemIds))
                    wire:loading.attr="disabled"
                    wire:target="finish,toggleItem,searchItems">
                Terminer
                <span class="spinner-border spinner-border-sm ms-2" wire:loading wire:target="finish"></span>
            </button>
        </div>
    @endif
</div>
