  <div class="p-3" style="width: 400px; max-height: 85vh; overflow-y: auto;">


      <div class="row row-cols-2 g-1">
          <div class="col text-center card-hover-zoom">
              <!-- Le lien pointe maintenant vers la nouvelle route 'openproject.redirect' -->
              <a href="https://tache.groupenedco.com/" class="text-decoration-none text-dark d-block" target="_blank">
                  <div class="d-flex align-items-center justify-content-center mx-auto mb-2 shadow bg-white"
                      style="width: 170px;height: 70px; transition: transform 0.3s;border-radius: 5px;">
                      <img src="{{ asset('assets/img/OpenProject-1.jpg') }}" alt="OpenProject" class="img-fluid rounded"
                          style="width: 160px;height: 60px; object-fit: contain;border-radius: 5px;">
                  </div>
                  <small class="fw-medium d-block text-truncate" title="Gestion de Projets"
                      style="font-size: 13px">Gestion de projets</small>
              </a>
          </div>
          {{-- <div class="col text-center  card-hover-zoom">
                                    <a @if (Auth::user()->openproject_api_token) href="{{ route('openproject.redirect') }}" 
                                            target="_blank" 
                                    @else
                                            href="#"
                                            data-bs-toggle="offcanvas" 
                                            data-bs-target="#offcanvasWithBackdrop3" 
                                            aria-controls="offcanvasWithBackdrop3" @endif
                                        data-bs-toggle="modal" data-bs-target="#generateTasksModal"
                                        class="text-decoration-none text-dark d-block">
                                        <div class="d-flex align-items-center justify-content-center mx-auto mb-2 shadow"
                                            style="width: 170px;height: 70px; transition: transform 0.3s;border-radius: 5px;">
                                            <img src="{{ asset('assets/img/tache.png') }}" alt="OpenProject"
                                                class="img-fluid rounded"
                                                style="width: 170px;height: 70px; object-fit: contain;border-radius: 5px;">
                                        </div>
                                        <small class="fw-medium d-block text-truncate" title="Gestion de Projets"
                                            style="font-size: 13px">Créer des tâches avec IA</small>
                                    </a>
                                </div> --}}
          <div class="col text-center  card-hover-zoom">
              <a href="#" onclick="ModaleAnnuaire(event)" class="text-decoration-none text-dark d-block">
                  <div class="d-flex align-items-center justify-content-center mx-auto mb-2 shadow bg-white"
                      style="width: 170px;height: 70px; transition: transform 0.3s;border-radius: 5px;">
                      <img src="{{ asset('assets/img/annuaires.png') }}" alt="OpenProject"
                          class="img-fluid rounded py-1"
                          style="width: 170px;height: 70px; object-fit: contain;border-radius: 5px;">
                  </div>
                  <small class="fw-medium d-block text-truncate" title="Gestion de Projets"
                      style="font-size: 13px">Annuaire Nedcore</small>
              </a>
          </div>
          <style>
              .card-hover-zoom {
                  transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
              }

              .card-hover-zoom:hover {
                  transform: scale(1.09);
                  z-index: 2;
              }
          </style>
          {{-- Script pour le popup mobile money --}}
         
          <script>
              function ModaleAnnuaire(event) {
                  event.preventDefault(); // Empêche le lien de naviguer

                  const url = "{{ route('annuaire') }}";
                  const width = 1450;
                  const height = 700;

                  // Calcul de la position centrée
                  const left = (window.screen.width / 2) - (width / 2);
                  const top = (window.screen.height / 2) - (height / 2);

                  // Ouverture de la popup centrée
                  window.open(
                      url,
                      'annuaire',
                      `width=${width},height=${height},top=${top},left=${left},scrollbars=yes,resizable=no`
                  );
              }
          </script>
          <div class="col text-center  card-hover-zoom">
              <a href="#" data-bs-toggle="offcanvas" data-bs-target="#offcanvasScrolling"
                  aria-controls="offcanvasScrolling" class="text-decoration-none text-dark d-block">
                  <div class="d-flex align-items-center justify-content-center mx-auto mb-2 shadow bg-white"
                      style="width: 170px;height: 70px; transition: transform 0.3s;border-radius: 5px;">
                      <img src="{{ asset('assets/img/simulation.png') }}" alt="OpenProject"
                          class="img-fluid rounded py-1"
                          style="width: 170px;height: 70px; object-fit: contain;border-radius: 5px;">
                  </div>
                  <small class="fw-medium d-block text-truncate" title="Gestion de Projets"
                      style="font-size: 13px">Simulateurs</small>
              </a>
          </div>
          <div class="col text-center  card-hover-zoom">
              <a href="#" data-bs-toggle="modal" data-bs-target="#partageLabelsModal"
                  class="text-decoration-none text-dark d-block">
                  <div class="d-flex align-items-center justify-content-center mx-auto mb-2 shadow bg-white"
                      style="width: 170px;height: 70px; transition: transform 0.3s;border-radius: 5px;">
                      <img src="{{ asset('assets/img/assistance.png') }}" alt="OpenProject"
                          class="img-fluid rounded py-1"
                          style="width: 170px;height: 70px; object-fit: contain;border-radius: 5px;">
                  </div>
                  <small class="fw-medium d-block text-truncate" title="Gestion de Projets"
                      style="font-size: 13px">Demande d'assistance</small>
              </a>
          </div>
      </div>

  </div>
