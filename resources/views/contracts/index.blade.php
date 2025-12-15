@extends('layouts.master2')
@section('content2')
<div class="section-admin container-fluid">
    <div class="row" style="margin-left: 20px; margin-right: 20px;">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="d-flex justify-content-between mb-3 mt-3">
                <h2 class="card-title text-primary">Gestion des contrats</h2>
            </div>

            @if (session('success'))
                <div class="alert alert-success rounded-pill alert-dismissible fade show">
                    <strong class="me-5"><i class="fas fa-check me-2"></i> {{ session('success') }}</strong>
                    <button type="button" class="btn-close custom-close" data-bs-dismiss="alert" aria-label="Close">
                        <i class="fas fa-xmark"></i>
                    </button>
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger rounded-pill alert-dismissible fade show">
                    <strong class="me-5"><i class="fas fa-exclamation-circle me-2"></i> {{ session('error') }}</strong>
                    <button type="button" class="btn-close custom-close" data-bs-dismiss="alert" aria-label="Close">
                        <i class="fas fa-xmark"></i>
                    </button>
                </div>
            @endif

            @if (session('info'))
                <div class="alert alert-info rounded-pill alert-dismissible fade show">
                    <strong class="me-5"><i class="fas fa-info-circle me-2"></i> {{ session('info') }}</strong>
                    <button type="button" class="btn-close custom-close" data-bs-dismiss="alert" aria-label="Close">
                        <i class="fas fa-xmark"></i>
                    </button>
                </div>
            @endif

            <!-- Statistiques -->
            @livewire('contract-stats')

            @livewire('contract-list')

            <!-- Modal Component -->
            @livewire('contract-modal')
        </div>
    </div>
</div>
@endsection