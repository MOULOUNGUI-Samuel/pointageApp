@extends('layouts.master2')
@section('content2')
    <!-- Mobile Menu end -->
    <!-- Main Menu area start-->
    <!-- Main Menu area End-->
    <!-- Start Status area -->
    <style>
        @media (min-width: 1024px) {
            .section-admin {
                margin-left: 90px;
                margin-right: 90px;
            }
        }

        @media (min-width: 1024px) {
            .product-sales-area {
                margin-left: 90px;
                margin-right: 90px;
            }
        }
    </style>
    <div class="welcome-wrap mb-4 bg-primary shadow">
        <div class="d-flex align-items-center justify-content-between flex-wrap">
            <div class="d-flex">
                <div class="d-flex align-items-center justify-content-center me-3">
                    <img src="{{ asset('storage/' . $module_logo) }}" alt="Profile"
                        style="width: 90px; height: 90px; object-fit: cover; border-radius: 12px; box-shadow: 0 5px 8px rgba(243, 239, 239, 0.508); background: #fff; border: 1px solid #e5e7eb;" />
                </div>
                <div class="">
                    <h2 class="mb-1 text-white">Bienvenue, {{ Auth::user()->prenom ?? '' }}</h2>
                    <p class="text-light">Prêt à découvrir vos options de paramétrage&nbsp;?</p>
                </div>
            </div>
            <div class="d-flex align-items-center flex-wrap mb-1">
                <div class="d-flex align-items-center flex-wrap mb-1">
                    <a href="#" class="btn btn-dark btn-md me-2 mb-2">Entreprises</a>
                    <a href="#" class="btn btn-light btn-md mb-2">Modules</a>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-3 col-md-6 d-flex">
            <div class="card flex-fill shadow">
                <div class="card-body d-flex align-items-center justify-content-between">
                    <div class="d-flex align-items-center overflow-hidden">
                        <div>
                            <h4 class="fw-medium mb-1 text-truncate">Entreprises</h4>
                            <h4>{{ count($entreprises) }}</h4>
                        </div>
                    </div>
                    <div>
                        <span class="avatar avatar-lg bg-primary flex-shrink-0">
                            <i class="fa fa-building fa-3x" aria-hidden="true" style="font-size: 25px"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 d-flex">
            <div class="card flex-fill shadow">
            <div class="card-body d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center overflow-hidden">
                <div>
                    <h4 class="fw-medium mb-1 text-truncate">Modules</h4>
                    <h4>{{ count($modules) }}</h4>
                </div>
                </div>
                <div>
                <span class="avatar avatar-lg bg-info flex-shrink-0">
                    <i class="fa fa-th-large fa-3x" aria-hidden="true" style="font-size: 25px"></i>
                </span>
                </div>
            </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 d-flex">
            <div class="card flex-fill shadow">
            <div class="card-body d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center overflow-hidden">
                <div>
                    <h4 class="fw-medium mb-1 text-truncate">Services</h4>
                    <h4>{{ count($services) }}</h4>
                </div>
                </div>
                <div>
                <span class="avatar avatar-lg bg-success flex-shrink-0">
                    <i class="fa fa-cogs fa-3x" aria-hidden="true" style="font-size: 25px"></i>
                </span>
                </div>
            </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 d-flex">
            <div class="card flex-fill shadow">
            <div class="card-body d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center overflow-hidden">
                <div>
                    <h4 class="fw-medium mb-1 text-truncate">Catégories</h4>
                    <h4>{{ count($categories) }}</h4>
                </div>
                </div>
                <div>
                <span class="avatar avatar-lg bg-warning flex-shrink-0">
                    <i class="fa fa-list-alt fa-3x" aria-hidden="true" style="font-size: 25px"></i>
                </span>
                </div>
            </div>
            </div>
        </div>
    </div>
    <!-- End Email Statistic area-->
@endsection
