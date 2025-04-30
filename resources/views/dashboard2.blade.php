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
                                    <i class="fa fa-users fa-3x" aria-hidden="true" style="font-size: 45px"></i>
                                </div>
                                <div class="col-md-10">
                                    <h4 class="text-left text-uppercase"><b>Nombre d'employés</b></h4>
                                    <div class="row vertical-center-box vertical-center-box-tablet">
                                        <div class="col-xs-12 cus-gh-hd-pro">
                                            <h2 class="text-right no-margin">10,000</h2>
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
                                    <i class="fa fa-user fa-3x" aria-hidden="true" style="font-size: 45px"></i>
                                </div>
                                <div class="col-md-10">
                                    <h4 class="text-left text-uppercase"><b>Employés présents</b></h4>
                                    <div class="row vertical-center-box vertical-center-box-tablet">
                                        <div class="col-xs-12 cus-gh-hd-pro">
                                            <h2 class="text-right no-margin">5,000</h2>
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
                                    <i class="fa fa-sign-out fa-3x" aria-hidden="true" style="font-size: 45px"></i>
                                </div>
                                <div class="col-md-10">
                                    <h4 class="text-left text-uppercase"><b>Sorties intermediaires</b></h4>
                                    <div class="row vertical-center-box vertical-center-box-tablet">
                                        <div class="col-xs-12 cus-gh-hd-pro">
                                            <h2 class="text-right no-margin">70,000</h2>
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
                                    <i class="fa fa-user-times fa-3x" aria-hidden="true" style="font-size: 45px"></i>
                                </div>
                                <div class="col-md-10">
                                    <h4 class="text-left text-uppercase"><b>Employés absences</b></h4>
                                    <div class="row vertical-center-box vertical-center-box-tablet">
                                        <div class="col-xs-12 cus-gh-hd-pro">
                                            <h2 class="text-right no-margin">100,000</h2>
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
    <div class="product-sales-area mg-tb-30">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-3 col-md-2 col-sm-12 col-xs-12">
                    <div class="white-box analytics-info-cs mg-b-10 res-mg-t-30">
                        <h4 class="box-title badge" style="font-size: 15px;background-color:#05436b">Statut des employés</h4>
                        <div class="">
                            <div class="row">
                                <div class="col-md-8 text-left">
                                    <span>Actifs</span>
                                </div>
                                <div class="col-md-4 text-right">
                                    <span>60%</span>
                                </div>
                            </div>
                            <div class="progress progress-mini">
                                <div style="width: 60%;" class="progress-bar bg-green"></div>
                            </div>
                            <div class="row">
                                <div class="col-md-8 text-left">
                                    <span>En congé</span>
                                </div>
                                <div class="col-md-4 text-right">
                                    <span>30%</span>
                                </div>
                            </div>
                            <div class="progress progress-mini">
                                <div style="width: 30%;" class="progress-bar bg-orange" style="background-color:orange"></div>
                            </div>
                            <div class="row">
                                <div class="col-md-8 text-left">
                                    <span>Inactifs</span>
                                </div>
                                <div class="col-md-4 text-right">
                                    <span>10%</span>
                                </div>
                            </div>
                            <div class="progress progress-mini">
                                <div style="width: 10%;" class="progress-bar bg-red"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-2 col-sm-12 col-xs-12">
                    <div class="white-box analytics-info-cs mg-b-10 res-mg-t-30">
                        <h4 class="box-title badge" style="font-size: 15px;background-color:#05436b">Types de contrats</h4>
                        <div class="">
                            <div class="row">
                                <div class="col-md-8 text-left">
                                    <span>Actifs</span>
                                </div>
                                <div class="col-md-4 text-right">
                                    <span>60%</span>
                                </div>
                            </div>
                            <div class="progress progress-mini">
                                <div style="width: 60%;" class="progress-bar bg-green"></div>
                            </div>
                            <div class="row">
                                <div class="col-md-8 text-left">
                                    <span>En congé</span>
                                </div>
                                <div class="col-md-4 text-right">
                                    <span>30%</span>
                                </div>
                            </div>
                            <div class="progress progress-mini">
                                <div style="width: 30%;" class="progress-bar bg-orange" style="background-color:orange"></div>
                            </div>
                            <div class="row">
                                <div class="col-md-8 text-left">
                                    <span>Inactifs</span>
                                </div>
                                <div class="col-md-4 text-right">
                                    <span>10%</span>
                                </div>
                            </div>
                            <div class="progress progress-mini">
                                <div style="width: 10%;" class="progress-bar bg-red"></div>
                            </div>
                        </div>
                    </div>
                    <div class="white-box analytics-info-cs mg-b-10 res-mg-t-30">
                        <h4 class="box-title badge" style="font-size: 15px;background-color:#f8ac59">Contrats à renouveler</h4>
                        <div class="">
                            <div class="row">
                                <div class="col-md-8 text-left">
                                    <span>CDD-Assistante...</span>
                                </div>
                                <div class="col-md-4 text-right">
                                    <span class="text-danger">3 jrs</span>
                                </div>
                            </div>
                            <div>
                                <h5>Sophie Bernard</h5>
                            </div>
                        </div>
                        <div class="">
                            <div class="row">
                                <div class="col-md-8 text-left">
                                    <span>CDD-Développe...</span>
                                </div>
                                <div class="col-md-4 text-right">
                                    <span class="text-warning">15 jrs</span>
                                </div>
                            </div>
                            <div>
                                <h5>Thomas Petit</h5>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-2 col-sm-12 col-xs-12">
                    <div class="white-box analytics-info-cs mg-b-10 res-mg-t-30">
                        <h4 class="box-title badge" style="font-size: 15px;background-color:#05436b">Congés par service</h4>
                        <div class="">
                            <div class="row">
                                <div class="col-md-8 text-left">
                                    <span>Informatique</span>
                                </div>
                                <div class="col-md-4 text-right">
                                    <span>12 jours</span>
                                </div>
                            </div>
                            <div class="progress progress-mini">
                                <div style="width: 30%;" class="progress-bar bg-green"></div>
                            </div>
                            <div class="row">
                                <div class="col-md-8 text-left">
                                    <span>RH</span>
                                </div>
                                <div class="col-md-4 text-right">
                                    <span>8 jours</span>
                                </div>
                            </div>
                            <div class="progress progress-mini">
                                <div style="width: 90%;" class="progress-bar bg-orange" style="background-color:orange"></div>
                            </div>
                            <div class="row">
                                <div class="col-md-8 text-left">
                                    <span>Finance</span>
                                </div>
                                <div class="col-md-4 text-right">
                                    <span>15 jours</span>
                                </div>
                            </div>
                            <div class="progress progress-mini">
                                <div style="width: 50%;" class="progress-bar bg-red"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- End Email Statistic area-->
@endsection
