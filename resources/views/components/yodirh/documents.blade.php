@extends('layouts.master2')
@section('content2')
    <!-- Mobile Menu end -->
    <!-- Main Menu area start-->
    <!-- Main Menu area End-->
    <!-- Start Status area -->

    <div class="file-manager-area mg-tb-15">
        <div class="container-fluid" style="margin-left: 15px;">

            <div class="row">
                <div class="col-md-3"></div>
                <div class="col-md-6" style="text-align: center;">
                    <form action="{{ route('html.import.owncloud') }}" method="POST">
                        @csrf
                        <label>Lien de partage OwnCloud :</label>
                        <input type="text" name="cloud_url" placeholder="Collez ici votre lien public OwnCloud" required
                            style="width: 100%;margin-bottom: 10px;">
                        <button type="submit" class="btn btn-primary">Importer depuis le cloud</button>
                    </form>
                </div>
                <div class="col-md-3">

                </div>
            </div>
            <div class="row">
                <div class="col-md-3"></div>
                @if (count($imported))
                    @foreach ($imported as $viewName)
                        <div class="col-md-3">
                            <div class="hpanel mt-b-30">
                                <div class="panel-body file-body file-cs-ctn">
                                    <i class="fa fa-file-pdf-o text-info"></i>
                                </div>
                                <div class="panel-footer">
                                    <a href="{{ route('imported.' . $viewName) }}" target="_blank">{{ $viewName }}</a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="col-md-3"></div>
                    <div class="col-md-6" style="text-align: center;">
                        <p style="color:brown">Aucun fichier importé pour le moment.</p>
                    </div>
                    <div class="col-md-3"></div>
                @endif
                {{-- <div class="col-md-3">
                        <div class="hpanel mt-b-30">
                            <div class="panel-body file-body file-cs-ctn">
                                <i class="fa fa-file-excel-o text-success"></i>
                            </div>
                            <div class="panel-footer">
                                <a href="#">Sheets_2016.doc</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="hpanel mt-b-30">
                            <div class="panel-body file-body file-cs-ctn">
                                <i class="fa fa-file-powerpoint-o text-danger"></i>
                            </div>
                            <div class="panel-footer">
                                <a href="#">Presentation_2016.doc</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="hpanel mt-b-30">
                            <div class="panel-body file-body file-cs-ctn">
                                <i class="fa fa-file-powerpoint-o text-danger"></i>
                            </div>
                            <div class="panel-footer">
                                <a href="#">Presentation_2016.doc</a>
                            </div>
                        </div>
                    </div> --}}
            </div>

        </div>
    </div>

    <!-- End Email Statistic area-->
@endsection
