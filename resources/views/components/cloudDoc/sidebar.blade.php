@php
    $dossier_info = \App\Helpers\DateHelper::dossier_info();
@endphp
<div class="left-sidebar-pro">
    <nav id="sidebar" class="">
        <div class="sidebar-header">
            <a href="{{ route('dashboard', $module_id) }}"><img class="main-logo rounded"
                    src="{{ asset('storage/' . $module_logo) }}" alt="" width="100" style="height: 90px" /></a>
            <strong><img src="{{ asset('storage/' . $module_logo) }}" alt="" /></strong>
            <div style="margin-left: 8px;margin-right:8px;margin-top:8px">
                <a class="btn btn-primary btn-block" style="margin-bottom: 10px;color:white"
                    onclick="document.getElementById('afficheAJoutLien').style.display = 'block';">
                    AJOUTER UN LIEN CLOUD
                </a>

                <div class="text-center">
                    <h3 style="color:black">Gestion des documents</h3>
                    <p class="text-muted" style="font-size:15px">Gérez vos fichiers et dossiers</p>
                </div>
            </div>
        </div>
        @php
            $currentDossier = request()->segment(count(request()->segments()));
        @endphp
        <div class="left-custom-menu-adp-wrap comment-scrollbar">
            <nav class="sidebar-nav left-sidebar-menu-pro">
                {{-- Loader d'écran --}}
                <ul class="metismenu" id="menu1">

                    <div id="loadingIndicator" class="text-center my-3" style="display: none;">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden text-info" id="loadingText" style="font-size: 20px">Chargement<span id="dot">.</span>
                        </div>
                        <div class="mt-2" >Chargement en cours.</span></div>
                    </div>

                    @foreach ($dossier_info['lienDocuments'] as $index => $lienDocument)
                        <li class="active">
                            <a href="#"
                                class="{{ $lienDocument->nom_lien == $currentDossier ? 'bg-primary2' : '' }}"
                                onclick="event.preventDefault(); showLoader(); document.getElementById('owncloudProcedure{{ $index }}').submit();">
                                <i class="fa fa-folder text-warning me-2"></i>
                                {{ $lienDocument->nom_lien }}
                            </a>

                            <form id="owncloudProcedure{{ $index }}"
                                action="{{ route('html.import.owncloudProcedure') }}" method="POST"
                                style="display: none;">
                                @csrf
                                <input type="hidden" name="user_id" value="{{ Auth::user()->id }}">
                                <input type="hidden" name="module_id" value="{{ $module_id }}">
                                <input type="hidden" name="cloud_url" value="{{ $lienDocument->lien }}">
                                <input type="hidden" name="nom_lien_existant" value="{{ $lienDocument->nom_lien }}">
                            </form>
                        </li>
                    @endforeach
                </ul>
            </nav>
            

        </div>
    </nav>
</div>
