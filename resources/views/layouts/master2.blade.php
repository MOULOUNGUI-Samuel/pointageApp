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
        // 🔌 GESTION CONNEXION PERDUE
        function showOfflinePopup() {
            const existingPopup = document.getElementById('offline-popup');
            if (existingPopup) return;
    
            const popup = document.createElement('div');
            popup.id = 'offline-popup';
            popup.innerHTML = `
                <div class="alert alert-danger text-center position-fixed bottom-0 start-0 end-0 m-3 shadow" role="alert" style="z-index: 9999;">
                    <span class="me-3">📡 Connexion perdue.</span>
                </div>
            `;
            document.body.appendChild(popup);
        }
    
        function removeOfflinePopup() {
            const popup = document.getElementById('offline-popup');
            if (popup) popup.remove();
        }
    
        window.addEventListener('offline', showOfflinePopup);
        window.addEventListener('online', removeOfflinePopup);
    
        if (!navigator.onLine) {
            showOfflinePopup();
        }
    
        // ⏱️ GESTION SESSION EXPIRÉE
        function showSessionExpiredPopup() {
            const existingPopup = document.getElementById('session-popup');
            if (existingPopup) return;
    
            const popup = document.createElement('div');
            popup.id = 'session-popup';
            popup.innerHTML = `
                <div class="alert alert-warning text-center position-fixed bottom-0 start-0 end-0 m-3 shadow" role="alert" style="z-index: 9999;">
                    ⌛ Session expirée. Redirection en cours...
                </div>
            `;
            document.body.appendChild(popup);
    
            setTimeout(() => {
                window.location.href = "/liste_modules";
            }, 1000);
        }
    
        function checkSessionExpired() {
            fetch(window.location.href, {
                method: 'HEAD',
                cache: 'no-store'
            })
            .then(response => {
                if (response.status === 419 || response.status === 401) {
                    showSessionExpiredPopup();
                }
            })
            .catch(() => {
                // Si requête échoue, considérer comme hors ligne
                showOfflinePopup();
            });
        }
    
        setInterval(checkSessionExpired, 60000); // Vérifie toutes les 60s
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
