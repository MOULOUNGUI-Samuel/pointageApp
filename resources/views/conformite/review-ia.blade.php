@extends('layouts.master2')

@section('content2')
<div class="container py-4">
  <div class="d-flex align-items-center justify-content-between mb-3">
    <h5 class="mb-0">
      <i class="ti ti-clipboard-check me-2"></i> Révision de la soumission avec IA
    </h5>
    <a href="{{ route('dashboard',['id'=>$module_id]) }}" class="btn btn-outline-secondary">
      <i class="ti ti-arrow-left"></i> Retour
    </a>
  </div>

  {{-- Monte le composant Livewire en pleine page --}}
  @livewire('settings.review-wizard', ['submissionId' => $submission->id], key('review-'.$submission->id))
</div>
@endsection
