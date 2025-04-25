@extends('layouts.master')
@section('content')
    <style>
        .container-custom {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border-radius: 15px;
            padding: 20px;
            text-align: left;
            max-width: 100%;
            width: 100%;
            color: white;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.2);
        }

        .date-header {
            border: 1px solid rgba(245, 247, 249, 0.8);
            padding: 6px 12px;
            border-radius: 6px;
            display: inline-block;
            margin-bottom: 15px;
        }

        .date-header1 {
            border: 1px solid white;
            padding: 6px 12px;
            border-radius: 6px;
            display: inline-block;
        }

        .time-block {
            border-bottom: 1px solid rgba(255, 255, 255, 0.2);
            padding-bottom: 10px;
            margin-bottom: 10px;
        }


        .btn-gradient {
            background: linear-gradient(135deg, #3f81b3d8, #d0d7db);
            border: none;
            border-radius: 10px;
            padding: 10px 24px;
            color: white;
            font-size: 16px;
            font-weight: bold;
            transition: all 0.3s ease-in-out;
            /* box-shadow: 0px 0px 20px rgba(255, 255, 255, 0.4); Grand shadow blanc */
        }


        .btn-gradient:hover {
            opacity: 0.8;
            transform: scale(1.05);
        }
    </style>
    <div class="data-table-area">
        <div class="container">
            <div class="mx-4" id="l-login">
                <div class="row mx-3 " style="margin-bottom: 10px">
                    <div class="col-md-5 mt-2">
                        <div class="basic-tb-hd">
                            <h2 style="color: #d0d7db">Profil : {{ $user->nom }} {{ $user->prenom }}</h2>
                        </div>
                    </div>
                    <div class="col-md-1"></div>
                    <div class="col-md-3 mb-2">
                        <div class="d-flex align-items-center">
                            <label for="filtre-date" class="me-2 mb-0">Date début :</label>
                            <input type="date" id="filtre-date" name="date_debut" class="form-control">
                        </div>
                    </div>

                    <div class="col-md-3 mb-2">
                        <div class="d-flex align-items-center">
                            <label for="filtre-date1" class="me-2 mb-0">Date fin :</label>
                            <input type="date" id="filtre-date1" name="date_fin" class="form-control">
                        </div>
                    </div>

                    {{-- <div class="col-md-3 mt-2">
                        <select class="form-select form-control  shadow-sm" id="filterStatus">
                            <option value="">Tous les statuts</option>
                            <option value="a_heure">Entrées</option>
                            <option value="en_retard">Sorties</option>
                        </select>
                    </div> --}}
                </div>

                <!-- Champ visible (sélecteur de date) -->
                <div class="container-custom">
                    <div class="row">
                        @foreach ($Pointages as $Pointage)
                            <div class="col-md-4">
                                <div class="pointage-item{{ $Pointage->id }}" data-status="{{ $Pointage->date_arriver }}">
                                    <div class="date-header shadow-sm text-capitalize" style="font-size: 19px">
                                        {{ \App\Helpers\DateHelper::convertirDateEnTexte(App\Helpers\DateHelper::convertirDateFormat($Pointage->date_arriver)) }}
                                    </div>

                                    <div class="time-block">
                                        <div class="mb-2">
                                            <div class="d-flex justify-content-between">
                                                <span class="text-success" style="font-size: 19px">
                                                    <i class="icon-enter text-success me-2"></i> Entrée :
                                                    {{ $Pointage->heure_arriver }}
                                                </span>
                                                @if ($Pointage->heure_arriver > $Pointage->user->entreprise->heure_ouverture)
                                                    <span class="text-danger" style="font-size: 20px">(en retard)</span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="mb-2" style="font-size: 19px;margin-top:5px">
                                            <i class="icon-exit me-2" style="color:darkorange"></i>
                                            <span style="color:darkorange"> Sortie :
                                                {{ $Pointage->heure_sortie ?? '-- : -- : --' }}
                                            </span>
                                        </div>
                                    </div>



                                    @if (!empty($cause_sorties[$Pointage->id]))
                                        <div class="shadow-sm mb-3">
                                            <button type="button" class="btn btn-primary" data-toggle="modal"
                                                data-target="#floatingLabelsModal{{ $Pointage->id }}">
                                                Sorties intermédiaires
                                            </button>
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <script>
                                function filtrerParPeriode() {
                                    const dateDebut = document.getElementById('filtre-date').value;
                                    const dateFin = document.getElementById('filtre-date1').value;
                                    const items = document.querySelectorAll('.pointage-item{{ $Pointage->id }}');
                                    let matchCount = 0;

                                    items.forEach(item => {
                                        const itemDate = item.getAttribute('data-status'); // format YYYY-MM-DD

                                        if (
                                            (!dateDebut || itemDate >= dateDebut) &&
                                            (!dateFin || itemDate <= dateFin)
                                        ) {
                                            item.style.display = 'block';
                                            matchCount++;
                                        } else {
                                            item.style.display = 'none';
                                        }
                                    });


                                }

                                // ⚡ Déclenchement automatique du filtrage
                                document.getElementById('filtre-date').addEventListener('change', filtrerParPeriode);
                                document.getElementById('filtre-date1').addEventListener('change', filtrerParPeriode);
                            </script>
                        @endforeach
                    </div>

                    <div id="aucun-resultat" class="alert alert-warning text-center" style="display: none;">
                        Aucun résultat trouvé pour cette date.
                    </div>
                </div>
                @foreach ($Pointages as $Pointage)
                    @if (!empty($cause_sorties[$Pointage->id]))
                        <div class="modal fade" id="floatingLabelsModal{{ $Pointage->id }}" tabindex="-1" role="dialog"
                            aria-labelledby="floatingLabelsModalLabel" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header bg-primary">
                                        <h4 class="modal-title text-white" id="floatingLabelsModal{{ $Pointage->id }}">
                                            <i class="bi bi-calendar-event"></i>
                                            <div class="date-header1 shadow-sm text-capitalize">
                                                {{ \App\Helpers\DateHelper::convertirDateEnTexte(App\Helpers\DateHelper::convertirDateFormat($Pointage->date_arriver)) }}
                                            </div>
                                        </h4>
                                    </div>
                                    <div class="modal-body">
                                        <div class="row" style="margin-left: 5px">
                                            @foreach ($cause_sorties[$Pointage->id] as $sortie)
                                                <div class="col-md-6">
                                                    <div class="time-block">
                                                        <div class="mb-2" style="font-size: 20px">
                                                            <i class="icon-exit me-2" style="color:darkorange"></i>
                                                            <span style="color:darkorange">Sortie :
                                                                {{ $sortie['pointage_intermediaire']->heure_sortie }}</span>
                                                        </div>
                                                        <div class="mb-2" style="font-size: 20px">
                                                            <i class="icon-enter text-success me-2"></i>
                                                            <span class="text-success">Entrée :
                                                                {{ $sortie['pointage_intermediaire']->heure_entrer ?? '-- : -- : --' }}</span>
                                                        </div>

                                                        @if (!empty($sortie['descriptions']))
                                                            <div class="mb-2" style="margin-bottom: 5px;margin-top:5px">
                                                                <small style="color:rgb(255, 255, 255); cursor: pointer;font-size: 15px;padding:5px; background:rgb(169, 169, 169);border-radius:30px"
                                                                    data-toggle="collapse"
                                                                    data-target="#motifSortie{{ $loop->index }}">
                                                                    Motif de sortie :
                                                                </small>
                                                            </div>

                                                            <div id="motifSortie{{ $loop->index }}" class="collapse mb-2"
                                                                style="background-color: transparent; border-radius:5px; border:1px solid #0d0d0efa">
                                                                <ul class="list-unstyled"
                                                                    style="padding-left: 15px; margin: 0;">
                                                                    @foreach ($sortie['descriptions'] as $description)
                                                                        <li
                                                                            style="margin-bottom: 5px; color: black; font-size: 15px">
                                                                            ◉
                                                                            <small>{{ $description->description }}</small>
                                                                        </li>
                                                                    @endforeach
                                                                </ul>
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-primary" data-dismiss="modal">Fermer</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                @endforeach
            </div>
        </div>
    </div>
@endsection
