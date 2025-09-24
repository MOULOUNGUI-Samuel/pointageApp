<div class="space-y-3">
    @if (session('success'))
      <div class="alert alert-success rounded-3 d-flex align-items-center">
        <i class="ti ti-check me-2"></i><span>{{ session('success') }}</span>
      </div>
    @endif
  
    {{-- En-tête --}}
    <div class="p-3 border rounded-3">
      <div class="d-flex align-items-start justify-content-between gap-2 flex-wrap">
        <div>
          <div class="d-flex align-items-center gap-2">
            <h5 class="mb-0">{{ $submission->item->nom_item }}</h5>
            <span class="badge bg-secondary text-uppercase">{{ $submission->item->type }}</span>
            @if($submission->periode)
              <span class="badge bg-light border text-muted">
                {{ $submission->periode->debut_periode?->format('d/m/Y') }}
                — {{ $submission->periode->fin_periode?->format('d/m/Y') }}
              </span>
            @endif
          </div>
          <div class="small text-muted mt-1">
            <i class="ti ti-upload me-1"></i>
            Soumis le {{ $submission->submitted_at?->format('d/m/Y H:i') }}
            @if($submission->submitter) par {{ $submission->submitter->nom }} {{ $submission->submitter->prenom }} @endif
          </div>
        </div>
  
        <div>
          @php $st = $submission->status; @endphp
          @if($st === 'approuvé')
            <span class="badge bg-success-subtle text-success border"><i class="ti ti-circle-check me-1"></i>Approuvé</span>
          @elseif($st === 'rejeté')
            <span class="badge bg-danger-subtle text-danger border"><i class="ti ti-circle-x me-1"></i>Rejeté</span>
          @else
            <span class="badge bg-warning-subtle text-warning border"><i class="ti ti-hourglass-high me-1"></i>En attente</span>
          @endif
        </div>
      </div>
  
      @if(in_array($submission->status,['approuvé','rejeté']))
        <div class="mt-3 p-2 bg-light rounded-3">
          <div class="small">
            <i class="ti ti-user-check me-1"></i>
            Validé par {{ $submission->reviewer->name ?? '—' }}
            @if($submission->reviewed_at)
              le {{ $submission->reviewed_at->format('d/m/Y H:i') }}
            @endif
          </div>
          @if($submission->reviewer_notes)
            <div class="small text-muted mt-1">
              <span class="fw-semibold">Notes :</span> {{ $submission->reviewer_notes }}
            </div>
          @endif
        </div>
      @endif
    </div>
  
    {{-- Données --}}
    <div class="p-3 border rounded-3">
      <div class="d-flex align-items-center justify-content-between mb-2">
        <h6 class="mb-0">Données soumises</h6>
        <span class="text-muted small">#{{ $submission->id }}</span>
      </div>
  
      @forelse($submission->answers as $a)
        @switch($a->kind)
          @case('texte')
            <div class="border rounded-3 p-2 mb-2">
              <div class="small text-muted mb-1"><i class="ti ti-text-size me-1"></i>Texte</div>
              <div>{{ $a->value_text }}</div>
            </div>
            @break
  
          @case('documents')
            <div class="border rounded-3 p-2 mb-2">
              <div class="small text-muted mb-1"><i class="ti ti-paperclip me-1"></i>Document</div>
              <a class="text-decoration-underline" target="_blank"
                 href="{{ Storage::disk('public')->url($a->file_path) }}">Télécharger</a>
            </div>
            @break
  
          @case('liste')
            <div class="border rounded-3 p-2 mb-2">
              <div class="small text-muted mb-1"><i class="ti ti-list-check me-1"></i>Choix</div>
              <div class="fw-semibold">{{ $a->selectedLabel() ?? $a->selectedList() }}</div>
            </div>
            @break
  
          @case('checkbox')
            <div class="border rounded-3 p-2 mb-2">
              <div class="small text-muted mb-1"><i class="ti ti-checkbox me-1"></i>Cases cochées</div>
              @php $labels = $a->selectedLabels(); $vals = $a->selectedMany(); @endphp
              <div class="d-flex flex-wrap gap-2">
                @forelse($labels ?: $vals as $lbl)
                  <span class="badge bg-light text-dark border">{{ $lbl }}</span>
                @empty
                  <span class="text-muted">—</span>
                @endforelse
              </div>
            </div>
            @break
        @endswitch
      @empty
        <div class="text-muted">Aucune donnée soumise.</div>
      @endforelse
    </div>
  
    {{-- Décision --}}
    <div class="p-3 border rounded-3">
      <label class="form-label fw-semibold">Notes du validateur</label>
      <textarea rows="4" class="form-control @error('notes') is-invalid @enderror"
                placeholder="Commentaires, réserves, références…"
                wire:model.defer="notes"></textarea>
      @error('notes') <div class="invalid-feedback">{{ $message }}</div> @enderror
  
      <div class="d-flex justify-content-end gap-2 mt-3">
        <button class="btn btn-success" wire:click="approve" wire:loading.attr="disabled">
          <span class="spinner-border spinner-border-sm me-2" wire:loading></span>Approuver
        </button>
        <button class="btn btn-danger" wire:click="reject" wire:loading.attr="disabled">
          <span class="spinner-border spinner-border-sm me-2" wire:loading></span>Rejeter
        </button>
      </div>
    </div>
  
    {{-- Journal --}}
    <div class="p-3 border rounded-3 bg-light">
      <div class="small fw-semibold mb-2">Journal</div>
      <ul class="list-unstyled small mb-0">
        <li class="mb-1">
          <i class="ti ti-upload me-1"></i>
          Soumis le {{ $submission->submitted_at?->format('d/m/Y H:i') }}
          @if($submission->submitter) par {{ $submission->submitter->nom }} {{ $submission->submitter->prenom }}@endif
        </li>
        @if($submission->isFinal())
          <li>
            <i class="ti ti-clipboard-check me-1"></i>
            Décision « {{ ucfirst($submission->status) }} »
            le {{ $submission->reviewed_at?->format('d/m/Y H:i') }}
            @if($submission->reviewer) par  {{ $submission->reviewer->nom }} {{ $submission->reviewer->prenom }} @endif
          </li>
        @endif
      </ul>
    </div>
  </div>
  <script>
    document.addEventListener('livewire:init', () => {
      Livewire.on('close-review-modal', ({ submissionId }) => {
        const id = 'modalReviewCompliance-' + submissionId;
        const el = document.getElementById(id);
        if (!el) return;
    
        // Récupère l'instance Bootstrap existante, sinon en crée une
        let modal = bootstrap.Modal.getInstance(el);
        if (!modal) modal = new bootstrap.Modal(el);
    
        modal.hide();
    
        // (optionnel) forcer le cleanup du backdrop si resté affiché
        document.querySelectorAll('.modal-backdrop.show').forEach(b => b.remove());
        document.body.classList.remove('modal-open');
        document.body.style.removeProperty('overflow');
        document.body.style.removeProperty('padding-right');
      });
    });
    </script>
    