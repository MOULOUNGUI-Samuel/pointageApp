@extends('layouts.master2')
@section('content2')
  
    <div class="card p-3 shadow mb-2">
        <div class="d-flex-justify-content-between">
            <h2 class="card-title text-primary">Liste des catégories professionnelles</h2>
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

                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#floatingLabelsModal">
                    <i class="ti ti-plus me-2" style="font-size: 16px;"></i> Catégorie professionnelle
                </button>
            </div>
            <div class="modal fade" id="floatingLabelsModal" tabindex="-1" role="dialog"
                aria-labelledby="floatingLabelsModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header bg-primary">
                            <h4 class="modal-title text-white" id="floatingLabelsModal">
                                <i class="bi bi-calendar-event"></i> Enregistrement d'une catégorie
                                professionnelle
                            </h4>
                        </div>
                        <form action="{{ route('Ajoutcategorieprofessionels') }}" method="POST"
                            enctype="multipart/form-data" style="display:inline-block;">
                            @csrf
                            <div class="modal-body">
                                <div class="row">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <div class="form-group ic-cmp-int float-lb floating-lb">
                                            <div class="form-ic-cmp">
                                                <i class="icon-library"></i>
                                            </div>
                                            <div class="nk-int-st">
                                                <input type="text" class="form-control"
                                                    name="nom_categorie_professionnelle"
                                                    placeholder="Nom de la catégorie professionnelle"
                                                    value="{{ old('nom_categorie_professionnelle') }}">
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
    </div>

    <div class="row" style="background: transparent">
        @foreach ($categorieprofessionels as $categorieprofessionel)
            <!-- Bloc Entrée -->
            <div class="col-lg-4 col-md-6 col-sm-12 mb-4">
                <div class="card shadow">
                    <h5 class="card-header">
                        {{ $categorieprofessionel->nom_categorie_professionnelle ?? 'YODIRH' }}
                    </h5>
                    <div class="card-body">
                        <div class="collapse" id="collapseExample-{{ $categorieprofessionel->id }}"
                            style="max-height: 80px; overflow-y: auto;">
                            <p class="card-text mb-3">{!! nl2br(e($categorieprofessionel->description)) !!}</p>
                        </div>
                        @if (isset($categorieprofessionel->description))
                            <button class="btn btn-sm btn-info" type="button" data-bs-toggle="collapse"
                                data-bs-target="#collapseExample-{{ $categorieprofessionel->id }}" aria-expanded="false"
                                aria-controls="collapseExample-{{ $categorieprofessionel->id }}">
                                <span class="more">Détails</span>
                            </button>
                        @endif
                        <button class="btn btn-sm btn-primary" data-bs-toggle="modal"
                            data-bs-target="#editserviceModal-{{ $categorieprofessionel->id }}">Modifier</button>
                        <button class="btn btn-sm btn-danger" data-bs-toggle="modal"
                            data-bs-target="#supprserviceModal-{{ $categorieprofessionel->id }}">Supprimer</button>
                        <button class="btn btn-sm btn-dark" data-bs-toggle="modal"
                            data-bs-target="#assignServiceModal-{{ $categorieprofessionel->id }}">Affecter à une
                            entreprise</button>
                    </div>
                </div>
            </div>

            <div class="modal fade" id="supprserviceModal-{{ $categorieprofessionel->id }}" tabindex="-1" role="dialog"
                aria-labelledby="supprserviceModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header bg-danger">
                            <h4 class="modal-title text-white" id="supprserviceModalLabel">
                                <i class="bi bi-trash-fill"></i> Suppression de la catégorie professionnelle :

                            </h4>
                        </div>
                        <form action="{{ route('supprimer_categorieprofessionel', $categorieprofessionel->id) }}"
                            method="POST" style="display:inline-block;">
                            @csrf
                            @method('DELETE')
                            <div class="modal-body">
                                <div class="row">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <div class="text-center">
                                            <h3>{{ $categorieprofessionel->nom_categorie_professionnelle }}</h3>
                                            <p> Etes-vous sûr de vouloir supprimer cette catégorie professionnelle ?
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                                <button type="submit" class="btn btn-danger">Oui, supprimer</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="modal fade" id="editserviceModal-{{ $categorieprofessionel->id }}" tabindex="-1"
                role="dialog" aria-labelledby="editserviceModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header bg-primary">
                            <h4 class="modal-title text-white" id="editserviceModalLabel">
                                <i class="bi bi-pencil-square"></i> Modifier la catégorie professionnelle :
                                {{ $categorieprofessionel->nom_categorie_professionnelle }}
                            </h4>
                        </div>
                        <form action="{{ route('modifier_categorieprofessionel', $categorieprofessionel->id) }}"
                            method="POST" enctype="multipart/form-data" style="display:inline-block;">
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
                                                <input type="text" class="form-control"
                                                    name="nom_categorie_professionnelle"
                                                    placeholder="Nom de la catégorie professionnelle"
                                                    value="{{ old('nom_categorie_professionnelle', $categorieprofessionel->nom_categorie_professionnelle) }}">
                                            </div>
                                        </div>
                                    </div>


                                    <div class="col-md-12 mt-3">
                                        <div class="form-group ic-cmp-int float-lb floating-lb">
                                            <div class="form-ic-cmp">
                                                <i class="icon-info"></i>
                                            </div>
                                            <div class="nk-int-st">
                                                <textarea class="form-control" name="description" placeholder="Description de la categorie">{{ old('description', $categorieprofessionel->description) }}</textarea>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"
                                        style="color: black">Fermer</button>
                                    <button type="submit" class="btn btn-primary">Modifier</button>
                                </div>
                            </div>
                        </form>

                    </div>
                </div>
            </div>

            <!-- Modal : Intégrer dans d'autres entreprises -->
            <div class="modal fade" id="assignServiceModal-{{ $categorieprofessionel->id }}" tabindex="-1"
                role="dialog" aria-labelledby="assignServiceModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header bg-dark">
                            <h4 class="modal-title text-white" id="assignServiceModalLabel">
                                <i class="bi bi-building"></i> Intégrer la catégorie à une entreprise
                            </h4>
                        </div>
                        <form action="{{ route('affecter_categorie') }}" method="POST">
                            @csrf
                            <input type="hidden" name="categorie_id" value="{{ $categorieprofessionel->id }}">
                            <div class="modal-body">
                                <div class="form-group">
                                    <label>Choisir une entreprise</label>
                                    <select class="form-control select2" name="entreprise_id" required>
                                        <option value="">Sélectionnez une entreprise</option>
                                        @foreach ($entreprisesSansCategorie[$categorieprofessionel->id] ?? [] as $entreprise)
                                            <option value="{{ $entreprise->id }}">{{ $entreprise->nom_entreprise }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                                <button type="submit" class="btn btn-dark">Intégrer</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <div class="row">
        @if ($categorieprofessionels->isEmpty())
            <div class="col-md-3"></div>

            <div class="col-lg-6 text-center" style="margin-top: 20px;">
                <div class="alert alert-info" style="font-size: 20px">
                    Aucun élément trouvé dans les catégories professionnelles.
                </div>
            </div>
            <div class="col-md-3"></div>
        @endif
    </div>

@endsection
