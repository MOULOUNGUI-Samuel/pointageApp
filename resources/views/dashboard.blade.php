@extends('layouts.master')
@section('content')
    <!-- Mobile Menu end -->
    <!-- Main Menu area start-->
    <!-- Main Menu area End-->
    <!-- Start Status area -->
    <div class="notika-status-area">
        <div class="container">
            <div class="row">
                <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
                    <div class="wb-traffic-inner notika-shadow sm-res-mg-t-30 tb-res-mg-t-30">
                        <div class="website-traffic-ctn">
                            <h2><span class="counter">10</span></h2>
                            <p>Nombre d'employés</p>
                        </div>
                        <i class="icon-users" style="font-size: 40px; margin-left: auto;"></i>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
                    <div class="wb-traffic-inner notika-shadow sm-res-mg-t-30 tb-res-mg-t-30">
                        <div class="website-traffic-ctn">
                            <h2><span class="counter">7</span></h2>
                            <p>Employés présents</p>
                        </div>
                        <i class="icon-user-check" style="font-size: 40px; margin-left: auto;"></i>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
                    <div class="wb-traffic-inner notika-shadow sm-res-mg-t-30 tb-res-mg-t-30 dk-res-mg-t-30">
                        <div class="website-traffic-ctn">
                            <h2><span class="counter">1</span></h2>
                            <p>Sorties intermediaires</p>
                        </div>
                        <i class="icon-hour-glass" style="font-size: 35px; margin-left: auto;"></i>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
                    <div class="wb-traffic-inner notika-shadow sm-res-mg-t-30 tb-res-mg-t-30 dk-res-mg-t-30">
                        <div class="website-traffic-ctn">
                            <h2><span class="counter">2</span></h2>
                            <p>Employés absences</p>
                        </div>
                        <i class="icon-user-minus" style="font-size: 40px; margin-left: auto;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End Status area-->

    <!-- Start Email Statistic area-->
    <div class="notika-email-post-area mg-t-30">
        <div class="container">
            <div class="row">
                <div class="col-lg-4 col-md-6 col-sm-6 col-xs-12">
                    <div class="email-statis-inner notika-shadow">
                        <div class="email-ctn-round">
                            <div class="email-rdn-hd">
                                <h2>Évolution des pointages</h2>
                            </div>
                            <div class="email-statis-wrap">
                                <div class="email-round-nock">
                                    <input type="text" class="knob" value="0" data-rel="55" data-linecap="round"
                                        data-width="110" data-bgcolor="#E4E4E4" data-fgcolor="#0384ca" data-thickness=".10"
                                        data-readonly="true">
                                </div>
                                <div class="email-ctn-nock">
                                    <p>Taux d'absence <strong>de la semaine</strong></p>
                                </div>
                            </div>
                            <div class="email-round-gp">
                                <div class="email-round-pro">
                                    <div class="email-signle-gp">
                                        <input type="text" class="knob" value="0" data-rel="75"
                                            data-linecap="round" data-width="90" data-bgcolor="#E4E4E4"
                                            data-fgcolor="#0384ca" data-thickness=".10" data-readonly="true" disabled>
                                    </div>
                                    <div class="email-ctn-nock">
                                        <p>Taux d'absence <strong>du mois</strong></p>
                                    </div>
                                </div>
                                <div class="email-round-pro">
                                    <div class="email-signle-gp">
                                        <input type="text" class="knob" value="0" data-rel="55"
                                            data-linecap="round" data-width="90" data-bgcolor="#E4E4E4"
                                            data-fgcolor="#0384ca" data-thickness=".10" data-readonly="true" disabled>
                                    </div>
                                    <div class="email-ctn-nock">
                                        <p>Taux de congé <strong>de la semaine</strong></p>
                                    </div>
                                </div>
                                <div class="email-round-pro sm-res-ds-n lg-res-mg-bl">
                                    <div class="email-signle-gp">
                                        <input type="text" class="knob" value="0" data-rel="45"
                                            data-linecap="round" data-width="90" data-bgcolor="#E4E4E4"
                                            data-fgcolor="#0384ca" data-thickness=".10" data-readonly="true" disabled>
                                    </div>
                                    <div class="email-ctn-nock">
                                        <p>Taux de congé <strong>du mois</strong></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 col-sm-6 col-xs-12">
                    <div class="recent-items-wp notika-shadow sm-res-mg-t-30">
                        <div class="rc-it-ltd">
                            <div class="recent-items-ctn">
                                <div class="recent-items-title">
                                    <h2 style="color:rgba(196, 12, 12, 0.877)">Employés sans trace de présence</h2>
                                </div>
                            </div>
                            <div class="recent-items-inn">
                                <table class="table table-inner table-vmiddle">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Nom(s) et prenoms(s)</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td class="f-500 c-cyan"><img src="{{ asset('src/images/user.jpg') }}" alt="" width="30" style="border: 1px solid rgba(196, 12, 12, 0.877);border-radius:50px"/></td>
                                            <td>Samsung Galaxy Mega</td>
                                        </tr>
                                    </tbody>
                                </table>
                                <div class="recent-post-signle">
                                    <a href="#">
                                        <div class="recent-post-flex rc-ps-vw">
                                            <div class="recent-post-line rct-pt-mg">
                                                <p  style="color:rgba(196, 12, 12, 0.877)">Voir plus</p>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 col-sm-6 col-xs-12">
                    <div class="recent-items-wp notika-shadow sm-res-mg-t-30">
                        <div class="rc-it-ltd">
                            <div class="recent-items-ctn">
                                <div class="recent-items-title">
                                    <h2  style="color:green">Employés déjà presents</h2>
                                </div>
                            </div>
                            <div class="recent-items-inn">
                                <table class="table table-inner table-vmiddle">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Nom(s) et prenoms(s)</th>
                                            <th style="width: 60px">Heure</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td class="f-500 c-cyan"><img src="{{ asset('src/images/user.jpg') }}" alt="" width="30" style="border: 1px solid green;border-radius:50px"/></td>
                                            <td>Samsung Galaxy Mega</td>
                                            <td class="f-500 c-cyan">07H30</td>
                                        </tr>
                                        <tr>
                                            <td class="f-500 c-cyan"><img src="{{ asset('src/images/user.jpg') }}" alt="" width="30" style="border: 1px solid green;border-radius:50px"/></td>
                                            <td>Samsung Galaxy Mega</td>
                                            <td class="f-500 c-cyan">07H30</td>
                                        </tr>
                                        <tr>
                                            <td class="f-500 c-cyan"><img src="{{ asset('src/images/user.jpg') }}" alt="" width="30" style="border: 1px solid green;border-radius:50px"/></td>
                                            <td>Samsung Galaxy Mega</td>
                                            <td class="f-500 c-cyan">07H30</td>
                                        </tr>
                                        <tr>
                                            <td class="f-500 c-cyan"><img src="{{ asset('src/images/user.jpg') }}" alt="" width="30" style="border: 1px solid green;border-radius:50px"/></td>
                                            <td>Samsung Galaxy Mega</td>
                                            <td class="f-500 c-cyan">07H30</td>
                                        </tr>
                                        <tr>
                                            <td class="f-500 c-cyan"><img src="{{ asset('src/images/user.jpg') }}" alt="" width="30" style="border: 1px solid green;border-radius:50px"/></td>
                                            <td>Samsung Galaxy Mega</td>
                                            <td class="f-500 c-cyan">07H30</td>
                                        </tr>
                                    </tbody>
                                </table>
                                <div class="recent-post-signle">
                                    <a href="#">
                                        <div class="recent-post-flex rc-ps-vw">
                                            <div class="recent-post-line rct-pt-mg">
                                                <p style="color:green">Voir plus</p>
                                            </div>
                                        </div>
                                    </a>
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
