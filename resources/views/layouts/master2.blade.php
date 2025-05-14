@include('components/head2')

<style>
    .all-content-wrapper {
        display: none;
    }

    .preloader-single {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: white;
        display: flex;
        justify-content: center;
        align-items: center;
        z-index: 9999;
    }
</style>

<body>

    <!-- Preloader -->
    <div id="preloader" class="preloader-single">
        <div class="ts_preloading_box">
            <div id="ts-preloader-absolute16">
                <div class="tsperloader16" id="tsperloader16_one"></div>
                <div class="tsperloader16" id="tsperloader16_two"></div>
                <div class="tsperloader16" id="tsperloader16_three"></div>
                <div class="tsperloader16" id="tsperloader16_four"></div>
                <div class="tsperloader16" id="tsperloader16_big"></div>
            </div>
        </div>
    </div>
    @php
        $moduleNom = strtolower($module_nom);
    @endphp
    @switch($moduleNom)
        @case('smi')
            @include('components/smi/sidebar')
        @break

        @case('rh')
            @include('components/yodirh/sidebar')
        @break

        @default
            @include('components/yodirh/sidebar')
    @endswitch

    <!-- Ajout de l'ID requis pour le script -->
    <div id="main-content" class="all-content-wrapper">
        @include('components/header2')
        @yield('content2')
        {{-- @include('components/footer') --}}
    </div>

    @include('components/script2')
    <script>
        function showOfflineMessage(redirect = false) {
            const existingPopup = document.getElementById('offline-popup');
            if (existingPopup) return; // Ne pas dupliquer

            const popup = document.createElement('div');
            popup.id = 'offline-popup';
            popup.innerHTML = `
              <div class="alert alert-danger text-center position-fixed bottom-0 start-0 end-0 m-3 shadow" role="alert" style="z-index: 9999;">
                  <span class="me-3">📡 Connexion perdue ou session expirée.</span>
              </div>
          `;
            document.body.appendChild(popup);

            if (redirect) {
                // Redirection automatique vers une route définie
                setTimeout(() => {
                    window.location.href = "/liste_modules";
                }, 3000);
            }
        }

        function removeOfflineMessage() {
            const popup = document.getElementById('offline-popup');
            if (popup) popup.remove();
        }

        function retryAutoReload() {
            const btns = document.querySelectorAll('#offline-popup button');
            btns.forEach(btn => btn.disabled = true);

            const interval = setInterval(() => {
                if (navigator.onLine) {
                    clearInterval(interval);
                    location.reload();
                }
            }, 1000); // tente toutes les 3 secondes
        }

        function checkSessionExpired() {
            fetch(window.location.href, {
                    method: 'HEAD',
                    cache: 'no-store'
                })
                .then(response => {
                    if (response.status === 419 || response.status === 401) {
                        showOfflineMessage(true); // redirige vers /components/liste_module
                    }
                })
                .catch(() => {
                    // Si l’appel échoue complètement, probablement hors ligne
                    showOfflineMessage();
                });
        }

        window.addEventListener('offline', showOfflineMessage);
        window.addEventListener('online', removeOfflineMessage);

        // Vérifie l'état initial au chargement
        if (!navigator.onLine) {
            showOfflineMessage();
        }

        // Vérifie périodiquement si la session a expiré
        setInterval(checkSessionExpired, 60000); // toutes les 60 secondes
    </script>
    
    <script>
        // Nombre de tâches de chargement en cours
        let pendingTasks = 0;

        function showPreloader() {
            document.getElementById('preloader').style.display = 'flex';
            document.getElementById('main-content').style.display = 'none';
        }

        function hidePreloader() {
            document.getElementById('preloader').style.display = 'none';
            document.getElementById('main-content').style.display = 'block';
        }

        function addTask() {
            pendingTasks++;
            showPreloader();
        }

        function completeTask() {
            pendingTasks = Math.max(0, pendingTasks - 1);
            if (pendingTasks === 0) {
                hidePreloader();
            }
        }

        // Charger la page = 1 tâche
        addTask();

        window.addEventListener('load', function() {
            completeTask(); // Fin du chargement initial
        });

        // Exemple d'utilisation :
        // addTask();
        // fetch('/api/data')
        //     .then(response => response.json())
        //     .then(data => {
        //         // traitement
        //     })
        //     .finally(() => completeTask());
    </script>
</body>

</html>
