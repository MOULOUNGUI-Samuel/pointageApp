 <div class="p-3" style="width: 400px; max-height: 85vh; overflow-y: auto;">
     <div class="row row-cols-2 g-1">
         <div class="col text-center card-hover-zoom">
             <!-- Le lien pointe maintenant vers la nouvelle route 'openproject.redirect' -->
             {{-- Blade --}}
             <a href="#" onclick="Consolider(event)" class="text-decoration-none text-dark d-block">
                 <div class="d-flex align-items-center justify-content-center mx-auto mb-2 shadow bg-white"
                     style="width:170px;height:70px;transition:transform .3s;border-radius:5px;">
                     <img src="{{ asset('src/images/consolider.png') }}" alt="OpenProject" class="img-fluid rounded py-1"
                         style="width:170px;height:70px;object-fit:contain;border-radius:5px;">
                 </div>
                 <small class="fw-medium d-block text-truncate" title="Gestion de Projets"
                     style="font-size:13px">Consolider</small>
             </a>


             <script>
                 function Consolider(event) {
                     event.preventDefault(); // Empêche le lien de naviguer

                     const url = @json(route('index-consolider'));
                     const width = 1450;
                     const height = 700;

                     // Calcul de la position centrée
                     const left = (window.screen.width / 2) - (width / 2);
                     const top = (window.screen.height / 2) - (height / 2);

                     // Ouverture de la popup centrée
                     window.open(
                         url,
                         'index-consolider',
                         `width=${width},height=${height},top=${top},left=${left},scrollbars=yes,resizable=no`
                     );
                 }
             </script>
         </div>
         @foreach ($mesModules['modules'] as $module)
             @if ($module->nom_module === 'Caisses')
                 @if (Auth::user()->statut_vue_caisse === 1)
                     <div class="col text-center card-hover-zoom">
                         <!-- Le lien pointe maintenant vers la nouvelle route 'openproject.redirect' -->
                         <a href="https://caisse.nedcore.net/authenticate/{{ Auth::user()->id }}"
                             class="text-decoration-none text-dark d-block">
                             <div class="d-flex align-items-center justify-content-center mx-auto mb-2 shadow bg-white"
                                 style="width: 170px;height: 70px; transition: transform 0.3s;border-radius: 5px;">
                                 <img src="{{ asset('storage/' . $module->logo) }}" alt="{{ $module->nom_module }}"
                                     class="img-fluid rounded"
                                     style="width: 160px;height: 60px; object-fit: contain;border-radius: 5px;">
                             </div>
                             <small class="fw-medium d-block text-truncate" style="font-size: 13px"
                                 title="{{ $module->nom_module }}">{{ $module->nom_module }}</small>
                         </a>
                     </div>
                 @endif
             @elseif($module->nom_module === 'GED')
                 <div class="col text-center card-hover-zoom">
                     <!-- Le lien pointe maintenant vers la nouvelle route 'openproject.redirect' -->
                     <a href="https://ged.nedcore.net/ged/authenticate/{{ Auth::user()->id }}"
                         class="text-decoration-none text-dark d-block">
                         <div class="d-flex align-items-center justify-content-center mx-auto mb-2 shadow bg-white"
                             style="width: 170px;height: 70px; transition: transform 0.3s;border-radius: 5px;">
                             <img src="{{ asset('storage/' . $module->logo) }}" alt="{{ $module->nom_module }}"
                                 class="img-fluid rounded"
                                 style="width: 160px;height: 60px; object-fit: contain;border-radius: 5px;">
                         </div>
                         <small class="fw-medium d-block text-truncate" style="font-size: 13px"
                             title="{{ $module->nom_module }}">{{ $module->nom_module }}</small>
                     </a>
                 </div>
             @elseif($module->nom_module === 'Configurations')
                 @if (Auth::user()->super_admin === 1)
                     <div class="col text-center  card-hover-zoom">
                         <a href="{{ route('dashboard', $module->id) }}"
                             class="text-decoration-none text-dark d-block">
                             <div class="d-flex align-items-center justify-content-center mx-auto mb-2 shadow bg-white"
                                 style="width: 170px;height: 70px; transition: transform 0.3s;border-radius: 5px;">
                                 <img src="{{ asset('storage/' . $module->logo) }}" alt="{{ $module->nom_module }}"
                                     class="img-fluid rounded"
                                     style="width: 160px;height: 60px; object-fit: contain;border-radius: 5px;">
                             </div>
                             <small class="fw-medium d-block text-truncate" style="font-size: 13px"
                                 title="{{ $module->nom_module }}">{{ $module->nom_module }}</small>
                         </a>
                     </div>
                 @endif
             @elseif($module->nom_module === 'Agenda')
                 <div class="col text-center  card-hover-zoom">
                     <a href="https://agenda.groupenedco.com/authenticate/{{ Auth::user()->id }}"
                         class="text-decoration-none text-dark d-block">
                         <div class="d-flex align-items-center justify-content-center mx-auto mb-2 shadow bg-white"
                             style="width: 170px;height: 70px; transition: transform 0.3s;border-radius: 5px;">
                             <img src="{{ asset('storage/' . $module->logo) }}" alt="{{ $module->nom_module }}"
                                 class="img-fluid rounded"
                                 style="width: 160px;height: 60px; object-fit: contain;border-radius: 5px;">
                         </div>
                         <small class="fw-medium d-block text-truncate" style="font-size: 13px"
                             title="{{ $module->nom_module }}">{{ $module->nom_module }}</small>
                     </a>
                 </div>
             @else
                 <div class="col text-center  card-hover-zoom">
                     <a href="{{ route('dashboard', $module->id) }}" class="text-decoration-none text-dark d-block">
                         <div class="d-flex align-items-center justify-content-center mx-auto mb-2 shadow bg-white"
                             style="width: 170px;height: 70px; transition: transform 0.3s;border-radius: 5px;">
                             <img src="{{ asset('storage/' . $module->logo) }}" alt="{{ $module->nom_module }}"
                                 class="img-fluid rounded"
                                 style="width: 160px;height: 60px; object-fit: contain;border-radius: 5px;">
                         </div>
                         <small class="fw-medium d-block text-truncate" style="font-size: 13px"
                             title="{{ $module->nom_module }}">{{ $module->nom_module }}</small>
                     </a>
                 </div>
             @endif
         @endforeach
     </div>

 </div>
