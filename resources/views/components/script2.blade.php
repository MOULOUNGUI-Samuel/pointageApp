@livewireScripts
<script>
    const beamsClient = new PusherPushNotifications.Client({
        // instanceId: 'b06db11a-f8ee-4eed-9313-ee95b6de6c85',
          instanceId: '6546287e-5b26-46fe-86d4-7c3aac85c0c0',
    });

    beamsClient.start()
        .then(() => beamsClient.addDeviceInterest('hello'))
        .then(() => console.log('Successfully registered and subscribed!'))
        .catch(console.error);
</script>
<script src="https://js.pusher.com/beams/2.1.0/push-notifications-cdn.js"></script>
<script>
    let dotCount = 1;
    let dotInterval;

    function startDotsAnimation() {
        const dot = document.getElementById('dot');
        dotCount = 1;

        dotInterval = setInterval(() => {
            dot.textContent = '.'.repeat(dotCount);
            dotCount = dotCount >= 3 ? 1 : dotCount + 1;
        }, 500); // vitesse de l'animation
    }

    function stopDotsAnimation() {
        clearInterval(dotInterval);
        document.getElementById('dot').textContent = '';
    }

    // Exemple : démarrer quand le loader s'affiche
    function showLoader() {
        document.getElementById('loadingIndicator').style.display = 'block';
        startDotsAnimation();
    }

    function hideLoader() {
        document.getElementById('loadingIndicator').style.display = 'none';
        stopDotsAnimation();
    }
</script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Cible tous les boutons ayant l'attribut data-loader-target
        document.querySelectorAll('[data-loader-target]').forEach(function(btn) { // Simplification du sélecteur
            btn.addEventListener('click', function(event) {
                const targetId = btn.getAttribute('data-loader-target');
                const loaderBtn = document.getElementById(targetId);

                if (btn.type === 'submit') {
                    const form = btn.closest('form');
                    if (form && !form.checkValidity()) {
                        // Si le formulaire n'est pas valide, empêche l'action par défaut
                        event.preventDefault();
                        event.stopPropagation();
                        form.classList.add(
                            'was-validated'
                            ); // Ajoute la classe Bootstrap pour afficher les erreurs
                        return;
                    }
                }

                if (loaderBtn) {
                    btn.style.display = 'none';
                    loaderBtn.style.display = 'inline-block';
                }
            });
        });
    });
</script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Fonction de recherche générique pour tous les couples searchX / absentTableX
        function setupSearchFilter(searchIdPrefix, tableIdPrefix) {
            let i = 1;
            while (true) {
                const searchInput = document.getElementById(`${searchIdPrefix}${i}`);
                const absentContainer = document.getElementById(`${tableIdPrefix}${i}`);
                if (!searchInput || !absentContainer) break;

                searchInput.addEventListener('input', function() {
                    const query = this.value.toLowerCase().trim();
                    const absentItems = absentContainer.querySelectorAll('.absent-item');

                    absentItems.forEach(item => {
                        const nameElement = item.querySelector('h6 a');
                        const functionElement = item.querySelector('.text-info');
                        const name = nameElement ? nameElement.textContent.toLowerCase() : '';
                        const func = functionElement ? functionElement.textContent
                            .toLowerCase() : '';
                        const searchableText = name + ' ' + func;
                        item.style.display = searchableText.includes(query) ? '' : 'none';
                    });
                });

                i++;
            }
        }

        // Appel pour tous les search/absentTable indexés (search1, search2, ...)
        setupSearchFilter('search', 'absentTable');
    });
</script>
<!-- jQuery -->
<!-- jQuery -->
<script src="{{ asset('assets/js/jquery-3.7.1.min.js') }}" type="text/javascript"></script>

<!-- Feather Icon JS -->
<script src="{{ asset('assets/js/feather.min.js') }}" type="text/javascript"></script>

<!-- Owl Carousel -->
<script src="{{ asset('assets/js/owl.carousel.min.js') }}" type="text/javascript"></script>

<!-- Slimscroll JS -->
<script src="{{ asset('assets/js/jquery.slimscroll.min.js') }}" type="text/javascript"></script>

<!-- Bootstrap Core JS -->
<script src="{{ asset('assets/js/bootstrap.bundle.min.js') }}" type="text/javascript"></script>

<!-- Datatable JS -->
<script src="{{ asset('assets/js/jquery.dataTables.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/js/dataTables.bootstrap5.min.js') }}" type="text/javascript"></script>

<!-- Daterangepicker JS -->
<script src="{{ asset('assets/js/moment.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/plugins/daterangepicker/daterangepicker.js') }}" type="text/javascript"></script>

<!-- Datetimepicker JS -->
<script src="{{ asset('assets/js/bootstrap-datetimepicker.min.js') }}" type="text/javascript"></script>

<!-- Bootstrap Tagsinput JS -->
<script src="{{ asset('assets/plugins/bootstrap-tagsinput/bootstrap-tagsinput.js') }}" type="text/javascript"></script>

<!-- Apexchart JS -->
<script src="{{ asset('assets/plugins/apexchart/apexcharts.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/plugins/apexchart/chart-data.js') }}" type="text/javascript"></script>

<!-- Chart JS -->
<script src="{{ asset('assets/plugins/peity/jquery.peity.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/plugins/peity/chart-data.js') }}" type="text/javascript"></script>

<!-- Select2 JS -->
<script src="{{ asset('assets/plugins/select2/js/select2.min.js') }}" type="text/javascript"></script>

<!-- Sticky-sidebar -->
<script src="{{ asset('assets/plugins/theia-sticky-sidebar/ResizeSensor.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/plugins/theia-sticky-sidebar/theia-sticky-sidebar.js') }}" type="text/javascript">
</script>

<!-- Masked Input JS -->
<script src="{{ asset('assets/js/jquery.maskedinput.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/js/mask.js') }}" type="text/javascript"></script>

<!-- Color Picker JS -->
<script src="{{ asset('assets/plugins/@simonwep/pickr/pickr.es5.min.js') }}" type="text/javascript"></script>

<!-- Custom Json Js -->
<script src="{{ asset('assets/js/jsonscript.js') }}" type="text/javascript"></script>

<!-- Custom JS -->
<script src="{{ asset('assets/js/theme-colorpicker.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/js/script.js') }}" type="text/javascript"></script>
<script src="{{ asset('cdn-cgi/scripts/7d0fa10a/cloudflare-static/rocket-loader.min.js') }}"
    data-cf-settings="ca6489927051845bc7a9daaa-|49" defer></script>
<script defer src="https://static.cloudflareinsights.com/beacon.min.js/vcd15cbe7772f49c399c6a5babf22c1241717689176015"
    integrity="sha512-ZpsOmlRQV6y907TI0dKBHq9Md29nnaEIPlkf84rnaERnq6zvWvPUqr2ft8M1aS28oN72PdrCzSjY4U6VaAw1EQ=="
    data-cf-beacon='{"rayId":"97e90219ca614193","version":"2025.8.0","serverTiming":{"name":{"cfExtPri":true,"cfEdge":true,"cfOrigin":true,"cfL4":true,"cfSpeedBrain":true,"cfCacheStatus":true}},"token":"3ca157e612a14eccbb30cf6db6691c29","b":1}'
    crossorigin="anonymous"></script>

{{-- <!-- Choices Js -->	
<script src="{{asset('assets/plugins/choices.js/public/assets/scripts/choices.min.js')}}" type="d2dd5d147621af618fbbb249-text/javascript"></script>

<!-- Simplebar JS -->
<script src="{{asset('assets/plugins/simplebar/simplebar.min.js')}}" type="d2dd5d147621af618fbbb249-text/javascript"></script>

<!-- Flatpickr JS -->
<script src="{{asset('assets/plugins/flatpickr/flatpickr.min.js')}}" type="d2dd5d147621af618fbbb249-text/javascript"></script>
 --}}

@env('production')
    <script src="{{ asset('assets/scripts/7d0fa10a/cloudflare-static/rocket-loader.min.js') }}" defer></script>
    <script defer src="https://static.cloudflareinsights.com/beacon.min.js" crossorigin="anonymous"></script>
@endenv
