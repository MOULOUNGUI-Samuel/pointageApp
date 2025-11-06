@extends('layouts.master2')
@section('content2')
  <div class="content">

    {{-- Header / bouton assistant --}}
    {{-- ... ton header inchangé ... --}}

    {{-- Modale assistant (ton wizard existant) --}}
    <div class="modal fade" id="wizardConfigModal" tabindex="-1" aria-hidden="true" wire:ignore.self>
      <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content">
          <div class="modal-header bg-primary text-white">
            <h5 class="modal-title">Assistant de configuration</h5>
            <button type="button" class="btn-close bg-light" data-bs-dismiss="modal"></button>
          </div>
          <div class="modal-body">
            @livewire('settings.enterprise-config-wizard')
          </div>
        </div>
      </div>
    </div>

    {{-- Board dynamique --}}
    @livewire('settings.enterprise-config-board')
    <div class="modal fade" id="periodesModal" tabindex="-1" aria-labelledby="periodesModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-scrollable">
          <div class="modal-content">
            <div class="modal-header bg-primary">
              <h5 class="modal-title text-white">
                  Périodes de validité — <span class="text-muted" wire:ignore.self></span>
              </h5>
              <button type="button" class="btn-close bg-light p-1" data-bs-dismiss="modal" aria-label="Fermer"></button>
            </div>
            <div class="modal-body">
              <livewire:settings.periodes-manager />
            </div>
          </div>
        </div>
      </div>
  </div>
  @push('scripts')
  <script>
      // si tu veux piloter l’ouverture depuis Livewire:
      window.Livewire?.on('showPeriodeModal', () => {
          const el = document.getElementById('periodeModal');
          if (!el) return;
          const modal = bootstrap.Modal.getOrCreateInstance(el);
          modal.show();
      });
  </script>
  @endpush
@endsection
