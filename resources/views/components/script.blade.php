<script>
    $(document).ready(function() {
        $('.selectpicker').selectpicker();
    });
</script>
<script>
    function updateDateTime() {
        let now = new Date();
        let dateTimeString = now.toLocaleString("fr-FR", {
            weekday: "long",
            year: "numeric",
            month: "long",
            day: "numeric"
        });
        document.getElementById("currentDateTime").innerText = dateTimeString;
    }
    setInterval(updateDateTime, 1000); // Met à jour chaque seconde
    updateDateTime();
</script>
<script>
    function updateTime() {
        let now = new Date();
        let hours = now.getHours().toString().padStart(2, '0');
        let minutes = now.getMinutes().toString().padStart(2, '0');
        let seconds = now.getSeconds().toString().padStart(2, '0');
        document.getElementById("currentTime").innerText = hours + ":" + minutes + ":" + seconds;
    }

    setInterval(updateTime, 1000); // Met à jour l'heure chaque seconde
    updateTime(); // Exécute immédiatement au chargement
</script>
<!-- jquery
    ============================================ -->
<script src="{{ asset('src/js/vendor/jquery-1.12.4.min.js') }}"></script>
<!-- bootstrap JS
    ============================================ -->
<script src="{{ asset('src/js/bootstrap.min.js') }}"></script>
<!-- wow JS
    ============================================ -->
<script src="{{ asset('src/js/wow.min.js') }}"></script>
<!-- price-slider JS
    ============================================ -->
<script src="{{ asset('src/js/jquery-price-slider.js') }}"></script>
<!-- owl.carousel JS
    ============================================ -->
<script src="{{ asset('src/js/owl.carousel.min.js') }}"></script>
<!-- scrollUp JS
    ============================================ -->
<script src="{{ asset('src/js/jquery.scrollUp.min.js') }}"></script>
<!-- meanmenu JS
    ============================================ -->
<script src="{{ asset('src/js/meanmenu/jquery.meanmenu.js') }}"></script>
<!-- counterup JS
    ============================================ -->
<script src="{{ asset('src/js/counterup/jquery.counterup.min.js') }}"></script>
<script src="{{ asset('src/js/counterup/waypoints.min.js') }}"></script>
<script src="{{ asset('src/js/counterup/counterup-active.js') }}"></script>
<!-- mCustomScrollbar JS
    ============================================ -->
<script src="{{ asset('src/js/scrollbar/jquery.mCustomScrollbar.concat.min.js') }}"></script>
<!-- jvectormap JS
    ============================================ -->
<script src="{{ asset('src/js/jvectormap/jquery-jvectormap-2.0.2.min.js') }}"></script>
<script src="{{ asset('src/js/jvectormap/jquery-jvectormap-world-mill-en.js') }}"></script>
<script src="{{ asset('src/js/jvectormap/jvectormap-active.js') }}"></script>
<!-- sparkline JS
    ============================================ -->
<script src="{{ asset('src/js/sparkline/jquery.sparkline.min.js') }}"></script>
<script src="{{ asset('src/js/sparkline/sparkline-active.js') }}"></script>
<!-- flot JS
    ============================================ -->
<script src="{{ asset('src/js/flot/jquery.flot.js') }}"></script>
<script src="{{ asset('src/js/flot/jquery.flot.resize.js') }}"></script>
<script src="{{ asset('src/js/flot/curvedLines.js') }}"></script>
<script src="{{ asset('src/js/flot/flot-active.js') }}"></script>
<!-- knob JS
    ============================================ -->
<script src="{{ asset('src/js/knob/jquery.knob.js') }}"></script>
<script src="{{ asset('src/js/knob/jquery.appear.js') }}"></script>
<script src="{{ asset('src/js/knob/knob-active.js') }}"></script>
<!-- wave JS
    ============================================ -->
<script src="{{ asset('src/js/wave/waves.min.js') }}"></script>
<script src="{{ asset('src/js/wave/wave-active.js') }}"></script>
<!-- todo JS
    ============================================ -->
<script src="{{ asset('src/js/todo/jquery.todo.js') }}"></script>
<!-- plugins JS
    ============================================ -->
<script src="{{ asset('src/js/plugins.js') }}"></script>
<!-- chat JS
    ============================================ -->
<script src="{{ asset('src/js/chat/moment.min.js') }}"></script>
<script src="{{ asset('src/js/chat/jquery.chat.js') }}"></script>
<!-- main JS
    ============================================ -->
<script src="{{ asset('src/js/data-table/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('src/js/data-table/data-table-act.js') }}"></script>
<script src="{{ asset('src/js/main.js') }}"></script>
<!-- datapicker JS
    ============================================ -->
<script src="{{ asset('src/js/datapicker/bootstrap-datepicker.js') }}"></script>
<script src="{{ asset('src/js/datapicker/datepicker-active.js') }}"></script>
<!-- Input Mask JS
    ============================================ -->
<script src="{{ asset('src/js/jasny-bootstrap.min.js') }}"></script>
<!-- bootstrap select JS
    ============================================ -->
<script src="{{ asset('src/js/bootstrap-select/bootstrap-select.js') }}"></script>
<!-- icheck JS
    ============================================ -->
<script src="{{ asset('src/js/icheck/icheck.min.js') }}"></script>
<script src="{{ asset('src/js/icheck/icheck-active.js') }}"></script>
<!-- rangle-slider JS
    ============================================ -->
<script src="{{ asset('src/js/rangle-slider/jquery-ui-1.10.4.custom.min.js') }}"></script>
<script src="{{ asset('src/js/rangle-slider/jquery-ui-touch-punch.min.js') }}"></script>
<script src="{{ asset('src/js/rangle-slider/rangle-active.js') }}"></script>
<!-- color-picker JS
    ============================================ -->
<script src="{{ asset('src/js/color-picker/farbtastic.min.js') }}"></script>
<script src="{{ asset('src/js/color-picker/color-picker.js') }}"></script>
<!-- notification JS
    ============================================ -->
<script src="{{ asset('src/js/notification/bootstrap-growl.min.js') }}"></script>
<script src="{{ asset('src/js/notification/notification-active.js') }}"></script>
<!-- summernote JS
    ============================================ -->
<script src="{{ asset('src/js/summernote/summernote-updated.min.js') }}"></script>
<script src="{{ asset('src/js/summernote/summernote-active.js') }}"></script>
<!-- dropzone JS
    ============================================ -->
<script src="{{ asset('src/js/dropzone/dropzone.js') }}"></script>
<!-- chosen JS
    ============================================ -->
<script src="{{ asset('src/js/chosen/chosen.jquery.js') }}"></script>

<!-- tawk chat JS
    ============================================ -->
{{-- <script src="{{ asset('src/js/tawk-chat.js') }}"></script> --}}
