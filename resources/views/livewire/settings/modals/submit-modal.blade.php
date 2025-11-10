{{-- resources/views/livewire/settings/modals/submit-modal.blade.php --}}
<div wire:ignore.self class="modal fade" id="submitModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
      <div class="modal-content">
        <div class="modal-header bg-primary bg-gradient">
          <h5 class="modal-title text-white">
            <i class="ti ti-send me-2"></i>Soumettre une Déclaration
          </h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
        </div>
  
        <div class="modal-body">
          @if ($selectedItemForSubmit)
            @livewire(
              'settings.submit-form',
              [
                'itemId'       => $selectedItemForSubmit,
                'submissionId' => $selectedSubmissionForSubmit,   
              ],
              key('submit-' . $selectedItemForSubmit . '-' . ($selectedSubmissionForSubmit ?? 'new')) 
            )
          @else
            <div class="text-center text-muted py-5">
              <i class="ti ti-file-off fs-1 mb-3 d-block"></i>
              <p>Sélectionnez un item pour soumettre une déclaration</p>
            </div>
          @endif
        </div>
      </div>
    </div>
  
    <script>
      document.addEventListener('livewire:init', () => {
        Livewire.on('open-submit-modal', () => {
          const el = document.getElementById('submitModal');
          if (!el) return;
          (bootstrap.Modal.getInstance(el) || new bootstrap.Modal(el)).show();
        });
  
        Livewire.on('settings-submitted', () => {
          const el = document.getElementById('submitModal');
          if (!el) return;
          const bs = bootstrap.Modal.getInstance(el);
          if (bs) bs.hide();
        });
      });
    </script>
  </div>
  