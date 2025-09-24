<div class="space-y-3">
    @if (session('success'))
      <div class="alert alert-success rounded-3 d-flex align-items-center">
        <i class="ti ti-check me-2"></i><span>{{ session('success') }}</span>
      </div>
    @endif
  
    <div class="p-3 border rounded-3">
      <div class="d-flex flex-wrap align-items-center justify-content-between gap-2">
        <div class="d-flex align-items-center gap-2">
          <h5 class="mb-0">{{ $itemLabel }}</h5>
          <span class="badge bg-secondary text-uppercase">{{ $item->type }}</span>
          @if($isEditing)
            <span class="badge bg-warning-subtle text-warning border">En attente — modification</span>
          @endif
        </div>
  
        <div class="text-end">
          @if($periodeId)
            <span class="badge bg-success-subtle text-success border">
              <i class="ti ti-calendar-check me-1"></i>Période active
            </span>
          @else
            <span class="badge bg-warning-subtle text-warning border">
              <i class="ti ti-alert-triangle me-1"></i>Période non active
            </span>
          @endif
        </div>
      </div>
  
      @if(!empty($item->description))
        <div class="text-muted small mt-2">{{ $item->description }}</div>
      @endif
    </div>
  
    {{-- Champs selon type --}}
    @if($item->type === 'texte')
      <div class="mb-2">
        <label class="form-label fw-semibold">Valeur attendue</label>
        <textarea rows="4" class="form-control @error('text_value') is-invalid @enderror"
                  placeholder="Saisir la valeur…" wire:model.defer="text_value"></textarea>
        @error('text_value') <div class="invalid-feedback">{{ $message }}</div> @enderror
      </div>
  
    @elseif($item->type === 'documents')
      {{-- fichiers existants en édition --}}
      @if($isEditing && $existingDocs)
        <div class="mb-2">
          <label class="form-label fw-semibold">Documents déjà fournis</label>
          <ul class="list-unstyled mb-0">
            @foreach($existingDocs as $path)
              <li class="d-flex align-items-center gap-2">
                <i class="ti ti-paperclip"></i>
                <a target="_blank" href="{{ Storage::disk('public')->url($path) }}">
                  {{ basename($path) }}
                </a>
              </li>
            @endforeach
          </ul>
          <div class="form-text">Téléversez des fichiers pour remplacer l’ensemble.</div>
        </div>
      @endif
  
      <div class="mb-2">
        <label class="form-label fw-semibold">Ajouter des pièces jointes</label>
        <input type="file" class="form-control" multiple wire:model="docs"
               accept=".pdf,.jpg,.jpeg,.png,.doc,.docx">
        <div class="d-flex align-items-center gap-2 small mt-1 text-muted">
          <i class="ti ti-info-circle"></i>
          <span>Formats: pdf, jpg, png, doc, docx — 10 Mo max / fichier</span>
        </div>
        <div class="mt-2">
          <div wire:loading wire:target="docs" class="small text-muted">
            <span class="spinner-border spinner-border-sm me-2"></span>Upload en cours…
          </div>
          @error('docs') <div class="text-danger small">{{ $message }}</div> @enderror
          @error('docs.*') <div class="text-danger small">{{ $message }}</div> @enderror
        </div>
      </div>
  
    @elseif($item->type === 'liste')
      <div class="mb-2">
        <label class="form-label fw-semibold">Choix</label>
        <select class="form-select @error('list_value') is-invalid @enderror" wire:model.defer="list_value">
          <option value="">— Sélectionner —</option>
          @foreach($options as $opt)
            <option value="{{ $opt->value ?: $opt->label }}">{{ $opt->label }}</option>
          @endforeach
        </select>
        @error('list_value') <div class="invalid-feedback">{{ $message }}</div> @enderror
      </div>
  
    @elseif($item->type === 'checkbox')
      <div class="mb-2">
        <label class="form-label fw-semibold">Cases à cocher</label>
        <div class="d-flex flex-wrap gap-3">
          @foreach($options as $opt)
            @php $val = $opt->value ?: $opt->label; @endphp
            <div class="form-check">
              <input type="checkbox" id="cb-{{ $opt->id }}" class="form-check-input"
                     value="{{ $val }}" wire:model="checkbox_values">
              <label class="form-check-label" for="cb-{{ $opt->id }}">{{ $opt->label }}</label>
            </div>
          @endforeach
        </div>
        @error('checkbox_values') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
      </div>
    @endif
  
    {{-- Actions --}}
    <div class="d-flex justify-content-end gap-2 pt-2">
      <button class="btn btn-primary" wire:click="save" wire:loading.attr="disabled">
        <span class="spinner-border spinner-border-sm me-2" wire:loading></span>
        <span wire:loading.remove>{{ $isEditing ? 'Enregistrer' : 'Soumettre' }}</span>
        <span wire:loading>Traitement…</span>
      </button>
    </div>
  </div>
  