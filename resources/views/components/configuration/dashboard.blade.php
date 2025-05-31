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
    <div class="section-admin container-fluid">
        <div class="row admin text-center">
            <div class="col-md-12">
                <div class="row">
                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                        <div class="admin-content analysis-progrebar-ctn res-mg-t-15">
                            <div class="row">
                                <div class="col-md-2 d-flex align-items-center justify-content-center">
                                    <i class="fa fa-building fa-3x" aria-hidden="true" style="font-size: 45px"></i>
                                </div>
                                <div class="col-md-10">
                                    <h4 class="text-left text-uppercase"><b>Entreprises</b></h4>
                                    <div class="row vertical-center-box vertical-center-box-tablet">
                                        <div class="col-xs-12 cus-gh-hd-pro">
                                            <h2 class="text-right no-margin">{{ count($entreprises) }}</h2>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12" style="margin-bottom:1px;">
                        <div class="admin-content analysis-progrebar-ctn res-mg-t-30">
                            <div class="row">
                                <div class="col-md-2 d-flex align-items-center justify-content-center">
                                    <i class="fa fa-th-large fa-3x" aria-hidden="true" style="font-size: 45px"></i>
                                </div>
                                <div class="col-md-10">
                                    <h4 class="text-left text-uppercase"><b>Modules</b></h4>
                                    <div class="row vertical-center-box vertical-center-box-tablet">
                                        <div class="col-xs-12 cus-gh-hd-pro">
                                            <h2 class="text-right no-margin">{{ count($modules) }}</h2>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                        <div class="admin-content analysis-progrebar-ctn res-mg-t-30">
                            <div class="row">
                                <div class="col-md-2 d-flex align-items-center justify-content-center">
                                    <i class="fa fa-cogs fa-3x" aria-hidden="true" style="font-size: 45px"></i>
                                </div>
                                <div class="col-md-10">
                                    <h4 class="text-left text-uppercase"><b>Services</b></h4>
                                    <div class="row vertical-center-box vertical-center-box-tablet">
                                        <div class="col-xs-12 cus-gh-hd-pro">
                                            <h2 class="text-right no-margin">{{ count($services) }}</h2>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                        <div class="admin-content analysis-progrebar-ctn res-mg-t-30">
                            <div class="row">
                                <div class="col-md-2 d-flex align-items-center justify-content-center">
                                    <i class="fa fa-list-alt fa-3x" aria-hidden="true" style="font-size: 45px"></i>
                                </div>
                                <div class="col-md-10">
                                    <h4 class="text-left text-uppercase"><b>Catégories</b></h4>
                                    <div class="row vertical-center-box vertical-center-box-tablet">
                                        <div class="col-xs-12 cus-gh-hd-pro">
                                            <h2 class="text-right no-margin">{{ count($categories) }}</h2>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End Email Statistic area-->
@endsection
