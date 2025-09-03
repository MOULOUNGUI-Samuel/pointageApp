{{-- resources/views/absences/index.blade.php --}}
@extends('layouts.master2')
@section('content2')
    <div class="d-flex align-items-center justify-content-between mb-3">
        <h4 class="mb-0">Gestion des demandes d'absences</h4>

        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#absenceModal" wire:click="openForm2()">
            + Nouvelle demande
        </button>

    </div>
    @livewire('absences.dashboard')
    @livewire('absences.manager')
    <script>
  document.addEventListener('livewire:init', () => {
    const el = document.getElementById('absenceModal');

    Livewire.on('showAbsenceModal', () => {
      const m = bootstrap.Modal.getOrCreateInstance(el);
      m.show();
    });

    Livewire.on('hideAbsenceModal', () => {
      const inst = bootstrap.Modal.getInstance(el);
      if (inst) inst.hide();
    });
  });
</script>

@endsection
