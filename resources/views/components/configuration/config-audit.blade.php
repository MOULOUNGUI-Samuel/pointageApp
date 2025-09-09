@extends('layouts.master2')
@section('content2')
    <div class="welcome-wrap mb-4 bg-primary shadow">
        <div class="d-flex align-items-center justify-content-between flex-wrap">
            <div class="d-flex">
                <div class="d-flex align-items-center justify-content-center me-3">
                    <img src="{{ asset('storage/' . $module_logo) }}" alt="Profile"
                        style="width: 90px; height: 90px; object-fit: cover; border-radius: 12px; box-shadow: 0 5px 8px rgba(243, 239, 239, 0.508); background: #fff; border: 1px solid #e5e7eb;" />
                </div>
                <div class="">
                    <h2 class="mb-1 text-white">Bienvenue, </h2>
                    <p class="text-light">Prêt à découvrir vos options de paramétrage&nbsp;?</p>
                </div>
            </div>
            {{-- <div class="d-flex align-items-center flex-wrap mb-1">
                <div class="d-flex align-items-center flex-wrap mb-1">
                    <a href="#" class="btn btn-dark btn-md me-2 mb-2">Entreprises</a>
                    <a href="#" class="btn btn-light btn-md mb-2">Modules</a>
                </div>
            </div> --}}
        </div>
    </div>

    @livewire('settings.dashboard-counters')

    {{-- Domaines --}}
    @livewire('settings.domaines-manager')

    {{-- Catégories de domaines --}}
    @livewire('settings.categories-manager')

    {{-- Types d’items --}}
    @livewire('settings.type-items-manager')

    {{-- Items --}}
    @livewire('settings.items-manager')
    <div class="modal fade" id="periodesModal" tabindex="-1" aria-labelledby="periodesModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-scrollable">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title">
                  Périodes de validité — <span class="text-muted" wire:ignore.self>
                  </span>
              </h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
            </div>
            <div class="modal-body">
              <livewire:settings.periodes-manager />
            </div>
          </div>
        </div>
      </div>
      
      
      
    <script>
        document.querySelectorAll(".searchInput").forEach(input => {
            input.addEventListener("keyup", function() {
                let filter = this.value.toLowerCase();

                document.querySelectorAll(".dataTable").forEach(tbody => {
                    tbody.querySelectorAll("tr").forEach(row => {
                        let text = row.textContent.toLowerCase();
                        row.style.display = text.includes(filter) ? "" : "none";
                    });
                });
            });
        });
    </script>
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
    <!-- End Email Statistic area-->
@endsection
