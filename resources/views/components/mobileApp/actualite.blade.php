<!DOCTYPE html>
<html lang="en">

<!-- Mirrored from pixner.net/html/readease/main-demo/search-page.html by HTTrack Website Copier/3.x [XR&CO'2014], Mon, 13 Oct 2025 09:25:56 GMT -->

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Nedcore</title>
    <script src="https://unpkg.com/@phosphor-icons/web@2.1.1"></script>
    <link rel="shortcut icon" href="{{ asset('assets/img/authentication/logo_nedcore.JPG') }}" type="image/x-icon" />
    <link href="{{ asset('asset/css/index.css') }}" rel="stylesheet">
</head>

<body class="">
    <main class="container py-8 min-h-dvh text-b300 bg-y50 dark:bg-darkY50 dark:text-white">
        <div class="px-6 flex justify-between items-center gap-4">
            <a href="{{ route('connexion') }}"
                class="size-12 bg-b300 text-white flex justify-center items-center rounded-full text-2xl dark:bg-darkB300">
                <i class="ph ph-list" style="font-size:24px;"></i>
            </a>
            <div
                class="flex justify-between items-center flex-1 border border-n40 rounded-full p-3 gap-2 dark:border-darkN40 dark:bg-darkN20 bg-white dark:bg-darkN40">
                <div class="">
                    <i class="ph ph-magnifying-glass text-n70"></i>
                </div>
                <input type="text" placeholder="Recherche..."
                    class="placeholder:text-n90 text-xs bg-transparent outline-none flex-1" />
                <i class="ph ph-microphone text-n70"></i>
            </div>
        </div>

        <div class="pt-8 px-1">
            <p class="text-xl font-semibold px-6">
                Actualités
            </p>
            <div class="container py-5">
                <div id="taggbox-loading" class="text-center py-5">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Chargement...</span>
                    </div>
                    <p class="mt-2">Chargement des actualités en cours...</p>
                </div>
                <div class="taggbox" style="width:100%;height:100%;overflow:auto;" data-widget-id="300959" data-website="1">
                </div>
                <script src="https://widget.taggbox.com/embed.min.js" type="text/javascript"></script>
                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        const loadingElement = document.getElementById('taggbox-loading');
                        const taggboxElement = document.querySelector('.taggbox');
        
                        // Afficher le contenu Taggbox après un délai pour s'assurer qu'il est chargé
                        setTimeout(() => {
                            if (loadingElement) loadingElement.style.display = 'none';
                            if (taggboxElement) taggboxElement.style.display = 'block';
                        }, 2000); // Délai de 2 secondes avant de masquer le chargement
                    });
                </script>
            </div>
        </div>
    </main>

    <!-- ======Javascript Dependencies -->
    <script src="{{ asset('asset/js/main.js') }}"></script>
    <script defer src="{{ asset('asset/js/index.js') }}"></script>
</body>

<!-- Mirrored from pixner.net/html/readease/main-demo/search-page.html by HTTrack Website Copier/3.x [XR&CO'2014], Mon, 13 Oct 2025 09:25:56 GMT -->

</html>
