@extends('layouts.master2')
@section('content2')
    <div class="section-admin container-fluid">

        <div class="card p-3 shadow mb-2">
            <div class="d-flex-justify-content-between">
                <h2 class="card-title text-primary">Liste des Liste des services</h2>
                </h2>
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
                <div class="d-flex-justify-content-between">

                    <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                        data-bs-target="#floatingLabelsModal">
                        <i class="ti ti-plus me-2" style="font-size: 16px;"></i> Catégorie professionnelle
                    </button>
                </div>
                <!-- Modal -->
            </div>
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
                                                    placeholder="Nom du service" value="{{ old('nom_service') }}">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-12 mt-3">
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
                                            data-bs-dismiss="modal">Fermer</button>
                                        <button type="submit" class="btn btn-primary">Enregistrer</button>
                                    </div>
                                </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            @foreach ($services as $service)
                <!-- Bloc Entrée -->
                <div class="col-lg-4 col-md-6 col-sm-12 mb-4">
                    <div class="card shadow">
                        <h5 class="card-header">
                            {{ $service->nom_service ?? 'YODIRH' }}
                        </h5>
                        <div class="card-body">
                            <div class="collapse" id="collapseExample-{{ $service->id }}"
                                style="max-height: 80px; overflow-y: auto;">
                                <p class="card-text mb-3">{!! nl2br(e($service->description)) !!}</p>
                            </div>
                            @if ($service->description)
                                <button class="btn btn-sm btn-info" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#collapseExample-{{ $service->id }}" aria-expanded="false"
                                    aria-controls="collapseExample-{{ $service->id }}">
                                    <span class="more">Détails</span>
                                </button>
                            @endif
                            <button class="btn btn-sm btn-primary" data-bs-toggle="modal"
                                data-bs-target="#editServiceModal-{{ $service->id }}">Modifier</button>
                            <button class="btn btn-sm btn-danger" data-bs-toggle="modal"
                                data-bs-target="#deleteServiceModal-{{ $service->id }}">Supprimer</button>
                            @if (isset($entreprisesSansCeService[$service->id]) && count($entreprisesSansCeService[$service->id]) > 0)
                                <button class="btn btn-sm btn-dark" data-bs-toggle="modal"
                                    data-bs-target="#assignServiceModal-{{ $service->id }}">Intégrer dans d'autres
                                    entreprises</button>
                            @endif

                        </div>
                    </div>
                </div>

                <!-- Modal : Supprimer le service -->
                <div class="modal fade" id="deleteServiceModal-{{ $service->id }}" tabindex="-1" role="dialog"
                    aria-labelledby="deleteServiceModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header bg-danger">
                                <h4 class="modal-title text-white" id="deleteServiceModalLabel">
                                    <i class="bi bi-trash-fill"></i> Suppression du service :
                                </h4>
                            </div>
                            <form action="{{ route('supprimer_service', $service->id) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <div class="modal-body text-center">
                                    <h3>{{ $service->nom_service }}</h3>
                                    <p>Êtes-vous sûr de vouloir supprimer ce service ?</p>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary"
                                        data-bs-dismiss="modal">Annuler</button>
                                    <button type="submit" class="btn btn-danger">Oui, supprimer</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Modal : Modifier le service -->
                <div class="modal fade" id="editServiceModal-{{ $service->id }}" tabindex="-1" role="dialog"
                    aria-labelledby="editServiceModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header bg-primary">
                                <h4 class="modal-title text-white" id="editServiceModalLabel">
                                    <i class="bi bi-pencil-square"></i> Modifier le service
                                </h4>
                            </div>
                            <form action="{{ route('modifier_service', $service->id) }}" method="POST"
                                enctype="multipart/form-data">
                                @csrf
                                @method('PUT')
                                <div class="modal-body">
                                    <div class="form-group">
                                        <label>Nom du service</label>
                                        <input type="text" class="form-control" name="nom_service"
                                            value="{{ old('nom_service', $service->nom_service) }}"
                                            placeholder="Nom du service">
                                    </div>
                                    <div class="form-group mt-3">
                                        <label>Description</label>
                                        <textarea class="form-control" name="description" placeholder="Description du service">{{ old('description', $service->description) }}</textarea>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary"
                                        data-bs-dismiss="modal">Fermer</button>
                                    <button type="submit" class="btn btn-primary">Modifier</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Modal : Intégrer dans d'autres entreprises -->
                <div class="modal fade" id="assignServiceModal-{{ $service->id }}" tabindex="-1" role="dialog"
                    aria-labelledby="assignServiceModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header bg-dark">
                                <h4 class="modal-title text-white" id="assignServiceModalLabel">
                                    <i class="bi bi-building"></i> Intégrer le service à une entreprise
                                </h4>
                            </div>
                            <form action="{{ route('affecter_service') }}" method="POST">
                                @csrf
                                <input type="hidden" name="service_id" value="{{ $service->id }}">
                                <div class="modal-body">
                                    <div class="form-group">
                                        <label>Choisir une entreprise</label>
                                        <select class="form-control select2" name="entreprise_id" required>
                                            <option value="">Sélectionnez une entreprise</option>
                                            @foreach ($entreprisesSansCeService[$service->id] ?? [] as $entreprise)
                                                <option value="{{ $entreprise->id }}">{{ $entreprise->nom_entreprise }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary"
                                        data-bs-dismiss="modal">Fermer</button>
                                    <button type="submit" class="btn btn-dark">Intégrer</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach

            @if ($services->isEmpty())
                <div class="col-md-3"></div>
                <div class="col-lg-6 text-center" style="margin-top: 20px;">
                    <div class="alert alert-info" style="font-size: 20px">
                        Aucun service n'est disponible pour le moment.
                    </div>
                </div>
                <div class="col-md-3"></div>
            @endif
        </div>
    </div>

@endsection
