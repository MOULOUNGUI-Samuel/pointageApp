@extends('layouts.master2')
@section('content2')
@php
    $mesModules = \App\Helpers\DateHelper::dossier_info();
@endphp
    <!-- Export Datatable start -->
    <div class="section-admin container-fluid mx-5" style="margin-left: 20px">
        <div class="card-box mb-30">
            <div class="card-header rounded shadow mb-2">
                <div class="d-flex-justify-content-between mb-3 mt-3"
                    style="background-color: white;padding: 10px;margin-top: 10px;border-radius: 10px;">
                    <h2 class="card-title text-primary">Gestion des paramètres</h2>
                    <h5>
                        @if (session('success'))
                            <div class="alert alert-success">
                                <i class="icon-copy fa fa-check-circle" aria-hidden="true"></i>
                                {{ session('success') }}
                                <button type="button" class="close ml-2" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        @endif

                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li><i class="icon-copy fa fa-exclamation-triangle" aria-hidden="true"></i>
                                            {{ $error }}
                                            <button type="button" class="close ml-2" data-dismiss="alert"
                                                aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                    </h5>
                    <button class="btn btn-primary btn-round border border-2 border-white shadow" data-toggle="modal"
                        data-target="#bd-example-modal-lg" type="button">
                        <i class="fa fa-plus"></i>
                        Ajouter un goupe de permission
                    </button>
                </div>
            </div>
            <div class="pb-20">

                <div class="tab-content">
                    <form method="POST" action="{{ route('permission') }}" class="mt-3">
                        @csrf
                            <div class="pd-20">
                                <div class="row mb-3">
                                    @foreach ($Tab_permissions as $group)
                                        <div class="col-md-4">
                                            <div class="row" >
                                                <div class="col-md-12">
                                                    <div class="card1" style="margin-top: 10px;margin-left: 10px;">

                                                        <div class="card-header1 bg-primary text-white">
                                                            <a href="#"
                                                                class="btn btn-block btn-round border border-2 border-white"
                                                                data-toggle="collapse"
                                                                data-target="#group-{{ $group['GroupePermission']->id }}"> 
                                                                <span
                                                                    class="text-white"  style="color: white;font-size:20px">{{ $group['GroupePermission']->nom }}</span>
                                                            </a>
                                                        </div>
                                                        <style>
                                                            .card-header1 {
                                                                padding: 10px;
                                                                border-radius: 10px 10px 0 0;
                                                                cursor: pointer;
                                                            }

                                                        </style>
                                                        <div id="group-{{ $group['GroupePermission']->id }}"
                                                            class="collapse show"> 
                                                            <div class="card-body">
                                                                <div class="col-md-12 col-sm-12 mb-2" style="margin-top: 12px;margin-bottom: 12px;">
                                                                    <div class="row mb-2">
                                                                        <div class="col-md-2">
                                                                            <div class="text-left">
                                                                                <button
                                                                                    class="btn btn-danger btn-round border border-2 border-white"
                                                                                    data-toggle="modal"
                                                                                    data-target="#supprimer{{ $group['GroupePermission']->id }}"
                                                                                    type="button">
                                                                                    <i class="fa fa-trash"></i>
                                                                                </button>

                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-2">
                                                                            <div class="text-left">
                                                                                <button
                                                                                    class="btn btn-info btn-round border border-2 border-white"
                                                                                    data-toggle="modal"
                                                                                    data-target="#modifier{{ $group['GroupePermission']->id }}"
                                                                                    type="button">
                                                                                    <i class="fa fa-edit"></i>
                                                                                </button>

                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-8">
                                                                            <div class="text-right">
                                                                                <button
                                                                                    class="btn btn-primary btn-round border border-2 border-white"
                                                                                    data-toggle="modal"
                                                                                    data-target="#bd-example-modal-lg{{ $group['GroupePermission']->id }}"
                                                                                    type="button">
                                                                                    <i class="fa fa-plus"></i>
                                                                                    Permission
                                                                                </button>
                                                                            </div>
                                                                        </div>

                                                                    </div>
                                                                </div>
                                                                @foreach ($group['Permissions'] as $permission)
                                                                    <div class="col-md-12 col-sm-12 mb-2" style="margin-bottom: 12px;">
                                                                        <div class="row">
                                                                            <div class="col-md-10">
                                                                                <label for="permission{{ $permission->id }}"
                                                                                    style="font-size: 17px;cursor: pointer;">
                                                                                    {{ $permission->libelle }}
                                                                                </label>
                                                                            </div>
                                                                            <div class="col-md-1" style="cursor: pointer">
                                                                                <i class="fa fa-trash text-danger"
                                                                                    data-toggle="modal"
                                                                                    data-target="#supprimerP{{ $permission->id }}"></i>
                                                                            </div>
                                                                            <div class="col-md-1" style="cursor: pointer">
                                                                                <i class="fa fa-edit text-info"
                                                                                    data-toggle="modal"
                                                                                    data-target="#modifierP{{ $permission->id }}"></i>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="modal fade"
                                                                        id="supprimerP{{ $permission->id }}" tabindex="-1"
                                                                        role="dialog" aria-hidden="true">
                                                                        <div class="modal-dialog modal-dialog-top"
                                                                            role="document">
                                                                            <div class="modal-content">
                                                                                <div class="modal-body text-center font-18">
                                                                                    <h5
                                                                                        class="padding-top-30 text-danger mb-3 weight-500">
                                                                                        Voulez-vous
                                                                                        vraiment
                                                                                        supprimer cette permission ?</h5>
                                                                                    <h4 class="mb-30 weight-500">
                                                                                        <strong>{{ $permission->libelle }}</strong>
                                                                                    </h4>
                                                                                    <div class="padding-bottom-30 row"
                                                                                        style="max-width: 170px; margin: 0 auto;">

                                                                                        <div class="col-6">
                                                                                            <form action=""
                                                                                                method="POST"
                                                                                                style="display:inline-block;">
                                                                                                <button type="button"
                                                                                                    class="btn btn-secondary border-radius-100 btn-block confirmation-btn"
                                                                                                    data-dismiss="modal"><i
                                                                                                        class="fa fa-times"></i></button>
                                                                                                NON
                                                                                            </form>
                                                                                        </div>
                                                                                        <div class="col-6">
                                                                                            <form
                                                                                                action="{{ route('supprimer_permission', $permission->id) }}"
                                                                                                method="POST"
                                                                                                style="display:inline-block;">
                                                                                                @csrf
                                                                                                @method('DELETE')
                                                                                                <button type="submit"
                                                                                                    class="btn btn-danger border-radius-100 btn-block confirmation-btn"><i
                                                                                                        class="fa fa-check"></i></button>
                                                                                                OUI
                                                                                            </form>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="modal fade bs-example-modal-lg"
                                                                        id="modifierP{{ $permission->id }}"
                                                                        tabindex="-1" role="dialog"
                                                                        aria-labelledby="myLargeModalLabel"
                                                                        aria-hidden="true">
                                                                        <div
                                                                            class="modal-dialog modal-md modal-dialog-top">
                                                                            <div class="modal-content">
                                                                                <div
                                                                                    class="modal-header box-shadow bg-success text-white">
                                                                                    <h4 class="modal-title text-white"
                                                                                        id="myLargeModalLabel">
                                                                                        Modifier le groupe</h4>
                                                                                    <button type="button" class="close"
                                                                                        data-dismiss="modal"
                                                                                        aria-hidden="true"><span
                                                                                            class=" bg-white text-black px-3 py-2"
                                                                                            style="border-radius: 100%">x</span>
                                                                                    </button>
                                                                                </div>
                                                                                <div
                                                                                    class="register-box bg-white box-shadow border-radius-10">
                                                                                    <div class="wizard-content">
                                                                                        <div class="wizard-content">
                                                                                            <div class="pd-20">
                                                                                                <form
                                                                                                    action="{{ route('modif_permissions', $permission->id) }}"
                                                                                                    method="post"
                                                                                                    enctype="multipart/form-data">
                                                                                                    @csrf
                                                                                                    @method('PUT')
                                                                                                    <h5 class="mb-2">
                                                                                                        Permission :
                                                                                                        {{ $permission->libelle }}
                                                                                                    </h5>
                                                                                                    <div class="row mb-3">
                                                                                                        <div class="col-12"
                                                                                                            id="container-champs{{ $permission->id }}">
                                                                                                            <div
                                                                                                                class="champ-input{{ $permission->id }} input-group custom shadow d-flex mb-2">
                                                                                                                <input
                                                                                                                    type="text"
                                                                                                                    name="libelle"
                                                                                                                    class="form-control form-control-lg mr-2"
                                                                                                                    placeholder="Nom de la permission"
                                                                                                                    value="{{ $permission->libelle }}"
                                                                                                                    required>
                                                                                                            </div>
                                                                                                        </div>
                                                                                                    </div>
                                                                                                    <div
                                                                                                        class="d-flex align-items-center justify-content-between text-center mx-5">
                                                                                                        <button
                                                                                                            type="button"
                                                                                                            class="btn btn-secondary"
                                                                                                            data-dismiss="modal">Annuler</button>
                                                                                                        <button
                                                                                                            type="submit"
                                                                                                            class="btn btn-success">Modifier</button>
                                                                                                    </div>
                                                                                                </form>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                @endforeach
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal fade" id="supprimer{{ $group['GroupePermission']->id }}"
                                            tabindex="-1" role="dialog" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-top" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-body text-center font-18">
                                                        <h5 class="padding-top-30 text-danger mb-3 weight-500">Voulez-vous
                                                            vraiment
                                                            supprimer ce groupe ?</h5>
                                                        <h4 class="mb-30 weight-500">
                                                            <strong>{{ $group['GroupePermission']->nom }}</strong>
                                                        </h4>
                                                        <div class="padding-bottom-30 row"
                                                            style="max-width: 170px; margin: 0 auto;">

                                                            <div class="col-6">
                                                                <form action="" method="POST"
                                                                    style="display:inline-block;">
                                                                    <button type="button"
                                                                        class="btn btn-secondary border-radius-100 btn-block confirmation-btn"
                                                                        data-dismiss="modal"><i
                                                                            class="fa fa-times"></i></button>
                                                                    NON
                                                                </form>
                                                            </div>
                                                            <div class="col-6">
                                                                <form
                                                                    action="{{ route('supprimer_groupe', $group['GroupePermission']->id) }}"
                                                                    method="POST" style="display:inline-block;">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                    <button type="submit"
                                                                        class="btn btn-danger border-radius-100 btn-block confirmation-btn"><i
                                                                            class="fa fa-check"></i></button>
                                                                    OUI
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal fade bs-example-modal-lg"
                                            id="modifier{{ $group['GroupePermission']->id }}" tabindex="-1"
                                            role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
                                            <div class="modal-dialog modal-md modal-dialog-top">
                                                <div class="modal-content">
                                                    <div class="modal-header box-shadow bg-success text-white">
                                                        <h4 class="modal-title text-white" id="myLargeModalLabel">
                                                            Modifier le groupe</h4>
                                                        <button type="button" class="close" data-dismiss="modal"
                                                            aria-hidden="true"><span
                                                                class=" bg-white text-black px-3 py-2"
                                                                style="border-radius: 100%">x</span>
                                                        </button>
                                                    </div>
                                                    <div class="register-box bg-white box-shadow border-radius-10">
                                                        <div class="wizard-content">
                                                            <div class="wizard-content">
                                                                <div class="pd-20">
                                                                    <form
                                                                        action="{{ route('groupe_permissions', $group['GroupePermission']->id) }}"
                                                                        method="post" enctype="multipart/form-data">
                                                                        @csrf
                                                                        @method('PUT')
                                                                        <h5 class="mb-2">Groupe:
                                                                            {{ $group['GroupePermission']->nom }}</h5>
                                                                        <div class="row mb-3">
                                                                            <div class="col-12"
                                                                                id="container-champs{{ $group['GroupePermission']->id }}">
                                                                                <div
                                                                                    class="champ-input{{ $group['GroupePermission']->id }} input-group custom shadow d-flex mb-2">
                                                                                    <input type="text" name="nom"
                                                                                        class="form-control form-control-lg mr-2"
                                                                                        placeholder="Nom de la permission"
                                                                                        value="{{ $group['GroupePermission']->nom }}"
                                                                                        required>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div
                                                                            class="d-flex align-items-center justify-content-between text-center mx-5">
                                                                            <button type="button"
                                                                                class="btn btn-secondary"
                                                                                data-dismiss="modal">Annuler</button>
                                                                            <button type="submit"
                                                                                class="btn btn-success">Modifier</button>
                                                                        </div>
                                                                    </form>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal fade bs-example-modal-lg"
                                            id="bd-example-modal-lg{{ $group['GroupePermission']->id }}" tabindex="-1"
                                            role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
                                            <div class="modal-dialog modal-md modal-dialog-top">
                                                <div class="modal-content">
                                                    <div class="modal-header box-shadow bg-success text-white">
                                                        <h4 class="modal-title text-white" id="myLargeModalLabel">
                                                            Ajouter des permissions</h4>
                                                        <button type="button" class="close" data-dismiss="modal"
                                                            aria-hidden="true"><span
                                                                class=" bg-white text-black px-3 py-2"
                                                                style="border-radius: 100%">x</span>
                                                        </button>
                                                    </div>
                                                    <div class="register-box bg-white box-shadow border-radius-10">
                                                        <div class="wizard-content">
                                                            <div class="wizard-content">
                                                                <div class="pd-20">
                                                                    <form action="{{ route('enregistre_permissions') }}"
                                                                        method="post" enctype="multipart/form-data">
                                                                        @csrf
                                                                        <h5 class="mb-2">Groupe:
                                                                            {{ $group['GroupePermission']->nom }}</h5>
                                                                        <input type="hidden" name="id_hidden"
                                                                            value="{{ $group['GroupePermission']->id }}">
                                                                        <div class="row mb-3">
                                                                            <div
                                                                                class="col-md-5 d-flex align-items-center justify-content-center">
                                                                                <div class="text-left">
                                                                                    <h6 class="mb-2">Liste des
                                                                                        permissions</h6>
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-md-7">
                                                                                <div class="text-right">
                                                                                    <button type="button"
                                                                                        id="add-champ{{ $group['GroupePermission']->id }}"
                                                                                        class="btn btn-primary mt-2">Ajouter
                                                                                        une permission</button>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="row mb-3">
                                                                            <div class="col-12"
                                                                                id="container-champs{{ $group['GroupePermission']->id }}">
                                                                                <div
                                                                                    class="champ-input{{ $group['GroupePermission']->id }} input-group custom shadow d-flex mb-2">
                                                                                    <input type="text"
                                                                                        name="libelles[]"
                                                                                        class="form-control form-control-lg mr-2"
                                                                                        placeholder="Nom de la permission"
                                                                                        required>
                                                                                    <button type="button"
                                                                                        class="btn btn-danger btn-sm remove-champ{{ $group['GroupePermission']->id }}">X</button>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div
                                                                            class="d-flex align-items-center justify-content-between text-center mx-5">
                                                                            <button type="button"
                                                                                class="btn btn-secondary"
                                                                                data-dismiss="modal">Annuler</button>
                                                                            <button type="submit"
                                                                                class="btn btn-success">Enregistrer</button>
                                                                        </div>
                                                                    </form>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <script>
                                            document.addEventListener("DOMContentLoaded", function() {
                                                const container = document.getElementById("container-champs{{ $group['GroupePermission']->id }}");
                                                const addBtn = document.getElementById("add-champ{{ $group['GroupePermission']->id }}");

                                                if (addBtn && container) {
                                                    addBtn.addEventListener("click", function() {
                                                        const newChamp = document.createElement("div");
                                                        newChamp.classList.add("champ-input", "d-flex", "mb-2");

                                                        newChamp.innerHTML = `
                                                    <input type="text" name="libelles[]" class="form-control form-control-lg mr-2" placeholder="Autre permission" required>
                                                    <button type="button" class="btn btn-danger btn-sm remove-champ">X</button>
                                                `;

                                                        container.appendChild(newChamp);
                                                    });

                                                    container.addEventListener("click", function(e) {
                                                        if (e.target && e.target.classList.contains("remove-champ")) {
                                                            e.target.parentElement.remove();
                                                        }
                                                    });
                                                }
                                            });
                                        </script>

                                        <!-- Export Datatable End -->
                                    @endforeach
                                </div>
                            </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <style>
        #container-champs .input-group input {
            max-width: 90%;
        }

        .remove-champ {
            min-width: 40px;
        }
    </style>
    <div class="modal fade bs-example-modal-lg" id="bd-example-modal-lg" tabindex="-1" role="dialog"
        aria-labelledby="myLargeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-md modal-dialog-top">
            <div class="modal-content">
                <div class="modal-header box-shadow text-white" style="background-color: #05436b;color: white;">
                    <h4 class="modal-title" id="myLargeModalLabel">Enregistrement du groupe de permissions</h4>
                </div>
                <div class="register-box bg-white box-shadow border-radius-10">

                    <div class="p-3 mx-4" style="margin-right: 20px; margin-left: 20px;">
                        <form action="{{ route('enregistre_permissions') }}" method="post"
                            enctype="multipart/form-data">
                            @csrf
                            <div class="fw-bold text-primary mb-3" style="margin-bottom: 13px;margin-top: 10px;">
                                <label for="module">Module</label>
                                <select name="module_id" id="module_id" class="form-control shadow" required>
                                    <option value="" disabled selected>Choisir un module</option>
                                    @foreach ($mesModules['modules'] as $module)
                                        <option value="{{ $module->id }}">{{ $module->nom_module }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <!-- Groupe : Nom et Description -->
                            <div class="fw-bold text-primary mb-3" style="margin-bottom: 13px;margin-top: 10px;">
                                <label for="">Nom du groupe de permission</label>
                                <input autocomplete="off" type="text" class="form-control shadow" name="nom"
                                    placeholder="Nom du groupe" value="{{ old('nom') }}" required>
                            </div>
                            <div class="mb-4" style="margin-bottom: 13pxpx;">
                                <textarea class="form-control shadow" name="description" placeholder="Description" rows="3"
                                    style="resize: vertical;">{{ old('description') }}</textarea>
                            </div>
                            <!-- Titre + bouton d'ajout -->
                            <div class="d-flex-justify-content-between mb-2"
                                style="margin-bottom: 10px;margin-top: 10px;">
                                <label class="text-primary">Liste des permissions</label>
                                <button type="button" id="add-champ" class="btn btn-primary btn-sm"><i
                                        class="fa fa-plus"></i> Ajouter une
                                    permission</button>
                            </div>

                            <!-- Liste des permissions -->
                            <div class="mb-4" id="container-champs" style="margin-bottom: 10px;margin-top: 10px;">
                                <div class="d-flex-justify-content-between champ-input input-group shadow-sm mb-2">
                                    <input type="text" name="libelles[]" class="form-control"
                                        placeholder="Nom de la permission" required>
                                    <button type="button" class="btn btn-danger remove-champ">X</button>
                                </div>
                            </div>

                            <!-- Boutons -->
                            <div class="d-flex-justify-content-between gap-2">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
                                <button type="submit" class="btn btn-primary">Enregistrer le groupe et les permissions</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Export Datatable End -->
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const container = document.getElementById("container-champs");
            const addBtn = document.getElementById("add-champ");

            addBtn.addEventListener("click", function() {
                const newChamp = document.createElement("div");
                newChamp.classList.add("champ-input", "d-flex-justify-content-between", "mb-2");
                newChamp.style.margin = "10px 0"; // <== style ici
                newChamp.innerHTML = `
                <input type="text" name="libelles[]" class="form-control form-control-lg mr-2" placeholder="Autre permission" required>
                <button type="button" class="btn btn-danger btn-sm remove-champ" style="margin-left:20px">X</button>
            `;
                container.appendChild(newChamp);
            });

            container.addEventListener("click", function(e) {
                if (e.target && e.target.classList.contains("remove-champ")) {
                    e.target.parentElement.remove();
                }
            });
        });
    </script>
@endsection
