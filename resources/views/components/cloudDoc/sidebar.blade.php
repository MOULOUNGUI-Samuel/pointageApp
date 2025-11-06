@php
    $dossier_info = \App\Helpers\DateHelper::dossier_info();
@endphp
<div class="sidebar" id="sidebar">
    <div class="sidebar-inner slimscroll">
        <div id="sidebar-menu" class="sidebar-menu">

            @php
                $currentDossier = request()->segment(count(request()->segments()));
            @endphp
            <ul>
                <li class="clinicdropdown">
                    <a href="profile.html">
                        <img src="{{ asset('assets/img/user.jpg') }}" class="img-fluid" alt="Profile" />
                        <div class="user-names">
                            <h5>{{ Auth::user()->nom ?? 'Utilisateur' }}</h5>
                            <h6>{{ Auth::user()->fonction ?? 'fonction' }}</h6>
                        </div>
                    </a>
                </li>
            </ul>
            <ul>
                <li>
                    <h6 class="submenu-hdr">Menu NedCore</h6>
                    <ul>
                        <li>
                            <a href="{{ route('dashboard', $module_id) }}"
                                class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">
                                <i class="fa fa-home"></i><span>Tableau de bord </span>
                            </a>
                        </li>
                        <div id="loadingIndicator" class="text-center my-3" style="display: none;">
                            <div class="spinner-border text-primary" role="status">
                                <span class="visually-hidden text-info" id="loadingText"
                                    style="font-size: 20px">Chargement<span id="dot">.</span>
                            </div>
                            <div class="mt-2">Chargement en cours.</span></div>
                        </div>
                        @foreach ($dossier_info['lienDocuments'] as $index => $lienDocument)
                            <li>
                                <a href="#"
                                    class="{{ $lienDocument->nom_lien == $currentDossier ? 'active' : '' }}"
                                    onclick="event.preventDefault(); showLoader(); document.getElementById('owncloudProcedure{{ $index }}').submit();">
                                    <i class="fa fa-folder text-warning me-2"></i>
                                    {{ preg_replace('/-\\d{8}(-\\d+)*$/', '', $lienDocument->nom_lien) }}
                                </a>

                                {{-- <a href="{{route('dashboard_doc', ['nom_lien' => $lienDocument->nom_lien])}}"
                                class="{{ $lienDocument->nom_lien == $currentDossier ? 'bg-primary2' : '' }}">
                                <i class="fa fa-folder text-warning me-2"></i>
                                {{  preg_replace('/-\\d{8}(-\\d+)*$/', '', $lienDocument->nom_lien) }}
                            </a> --}}

                                <form id="owncloudProcedure{{ $index }}"
                                    action="{{ route('html.import.owncloudProcedure') }}" method="POST"
                                    style="display: none;">
                                    @csrf
                                    <input type="hidden" name="user_id" value="{{ Auth::user()->id }}">
                                    <input type="hidden" name="module_id" value="{{ $module_id }}">
                                    <input type="hidden" name="cloud_url" value="{{ $lienDocument->lien }}">
                                    <input type="hidden" name="nom_lien_existant"
                                        value="{{ $lienDocument->nom_lien }}">
                                </form>
                            </li>
                        @endforeach
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</div>
