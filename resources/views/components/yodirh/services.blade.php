@extends('layouts.master2')
@section('content2')
    <style>
        .upload-container {
            text-align: center;
            margin: 10px;
        }

        .upload-box {
            border: 2px dashed #007bff;
            background-color: #f8f9fa;
            padding: 20px;
            border-radius: 10px;
            display: inline-block;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .upload-box:hover {
            background-color: #e9ecef;
            border-color: #0056b3;
        }

        .upload-box i {
            font-size: 40px;
            color: #007bff;
        }

        .upload-box p {
            margin: 5px 0 0;
            font-size: 14px;
            color: #333;
        }

        .preview-container {
            margin-top: 15px;
        }

        .preview-container img {
            max-width: 100%;
            height: auto;
            border-radius: 10px;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
        }
    </style>
    <div class="section-admin container-fluid" style="margin-left: 40px; margin-right: 40px;">

        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="data-table-list">
                    <div class="row mx-3" style="margin-top: 20px;">
                        <div class="col-md-3 mt-2">
                            <div class="basic-tb-hd">
                                <h2 class="card-title text-primary">Liste des services</h2>
                            </div>
                        </div>
                        <div class="col-md-7">
                            @if (session('success'))
                                <div class="alert alert-success">{{ session('success') }}</div>
                            @endif

                            @if ($errors->any())
                                <div class="alert alert-danger">
                                    <ul>
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                        </div>
                        <div class="col-md-2 mt-2">
                            <!-- Button trigger modal -->
                            <button type="button" class="btn btn-primary" data-toggle="modal"
                                data-target="#floatingLabelsModal">
                                Ajouter un service
                            </button>
                        </div>

                        <!-- Modal -->
                        <div class="modal fade" id="floatingLabelsModal" tabindex="-1" role="dialog"
                            aria-labelledby="floatingLabelsModalLabel" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header bg-primary">
                                        <h4 class="modal-title text-white" id="floatingLabelsModal">
                                            <i class="bi bi-calendar-event"></i> Formulaire d'enregistrement d'un service
                                        </h4>
                                    </div>
                                    <form action="{{ route('Ajoutservices') }}" method="POST" enctype="multipart/form-data"
                                        style="display:inline-block;">
                                        @csrf
                                        <div class="modal-body">
                                            <div class="row">
                                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                    <div class="form-group ic-cmp-int float-lb floating-lb">
                                                        <div class="form-ic-cmp">
                                                            <i class="icon-library"></i>
                                                        </div>
                                                        <div class="nk-int-st">
                                                            <input type="text" class="form-control" name="nom_service"
                                                                placeholder="Nom du service"
                                                                value="{{ old('nom_service') }}">
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-12">
                                                    <div class="form-group ic-cmp-int float-lb floating-lb">
                                                        <div class="form-ic-cmp">
                                                            <i class="icon-info"></i>
                                                        </div>
                                                        <div class="nk-int-st">
                                                            <textarea class="form-control" name="description" placeholder="Description du service">{{ old('description') }}</textarea>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary"
                                                        data-dismiss="modal">Fermer</button>
                                                    <button type="submit" class="btn btn-primary">Enregistrer</button>
                                                </div>
                                            </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @foreach ($services as $service)
                <!-- Bloc Entrée -->
                <div class="col-lg-3 col-sm-12" style="margin-top: 20px;">
                    <div class="card-body shadow-lg px-2"
                        style="background-color: white; border-radius: 10px;text-align: center;padding-top: 20px;padding-bottom: 20px;">

                        <h3 class="text-success fw-bold">{{ $service->nom_service ?? 'YODIRH' }}</h3>
                        @if (!empty($service->description))
                            <p class="text-muted">{{ $service->description }}</p>
                        @endif
                        <div class="dropdown-trig-sgn" style="margin-bottom: 10px;">
                            <button class="btn triger-bounceIn btn-primary" data-toggle="dropdown">Actions</button>
                            <ul class="dropdown-menu triger-bounceIn-dp">
                                <li><a href="#" data-toggle="modal"
                                        data-target="#editserviceModal-{{ $service->id }}">Modifier</a></li>
                                <li class="divider"></li>
                                <li><a href="#">Supprimer</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="modal fade" id="editserviceModal-{{ $service->id }}" tabindex="-1" role="dialog"
                    aria-labelledby="editserviceModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header bg-primary">
                                <h4 class="modal-title text-white" id="editserviceModalLabel">
                                    <i class="bi bi-pencil-square"></i> Modifier le service
                                </h4>
                            </div>
                            <form action="{{ route('modifier_service', $service->id) }}" method="POST"
                                enctype="multipart/form-data" style="display:inline-block;">
                                @csrf
                                @method('PUT')
                                <div class="modal-body">
                                    <div class="row">
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                            <div class="form-group ic-cmp-int float-lb floating-lb">
                                                <div class="form-ic-cmp">
                                                    <i class="icon-library"></i>
                                                </div>
                                                <div class="nk-int-st">
                                                    <input type="text" class="form-control" name="nom_service"
                                                        placeholder="Nom du service"
                                                        value="{{ old('nom_service', $service->nom_service) }}">
                                                </div>
                                            </div>
                                        </div>


                                        <div class="col-md-12">
                                            <div class="form-group ic-cmp-int float-lb floating-lb">
                                                <div class="form-ic-cmp">
                                                    <i class="icon-info"></i>
                                                </div>
                                                <div class="nk-int-st">
                                                    <textarea class="form-control" name="description" placeholder="Description du service">{{ old('description', $service->description) }}</textarea>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal"
                                            style="color: black">Fermer</button>
                                        <button type="submit" class="btn btn-primary">Modifier</button>
                                    </div>
                                </div>
                            </form>

                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

@endsection
