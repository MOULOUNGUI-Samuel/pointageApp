{{-- Modale de Soumission --}}
<div class="modal fade" id="submitModal2" tabindex="-1" aria-labelledby="submitModalLabel2"
     aria-hidden="true" wire:ignore.self data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header border-bottom bg-light">
                <h5 class="modal-title" id="submitModalLabel2">
                    <i class="ti ti-send me-2"></i>
                    Soumission de Conformité
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                @livewire('settings.submit-wizard')
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('livewire:initialized', () => {
        // Écouter l'événement pour fermer la modale
        Livewire.on('close-submit-modal2', () => {
            const modal = bootstrap.Modal.getInstance(document.getElementById('submitModal2'));
            if (modal) {
                modal.hide();
            }
        });

        // Gérer l'ouverture de la modale via l'événement
        Livewire.on('open-submit-modal2', (event) => {
            // Fermer toutes les autres modales ouvertes
            document.querySelectorAll('.modal.show').forEach(openModal => {
                const modalInstance = bootstrap.Modal.getInstance(openModal);
                if (modalInstance && openModal.id !== 'submitModal2') {
                    modalInstance.hide();
                }
            });

            // Ouvrir cette modale après un court délai
            setTimeout(() => {
                const modal = new bootstrap.Modal(document.getElementById('submitModal2'));
                modal.show();
            }, 300);
        });
    });
</script>