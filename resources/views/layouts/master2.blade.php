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
        window.addEventListener('offline', function () {
            window.location.href = "/connexion-error.html"; // fichier statique local
        });
    
        if (!navigator.onLine) {
            window.location.href = "/connexion-error.html";
        }
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
