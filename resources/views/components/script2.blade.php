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
<!-- jQuery -->
<script src="{{ asset('src2/js/vendor/jquery-1.11.3.min.js') }}"></script>

<!-- Bootstrap -->
<script src="{{ asset('src2/js/bootstrap.min.js') }}"></script>

<!-- Plugins principaux -->
<script src="{{ asset('src2/js/wow.min.js') }}"></script>
<script src="{{ asset('src2/js/jquery-price-slider.js') }}"></script>
<script src="{{ asset('src2/js/jquery.meanmenu.js') }}"></script>
<script src="{{ asset('src2/js/owl.carousel.min.js') }}"></script>
<script src="{{ asset('src2/js/jquery.sticky.js') }}"></script>
<script src="{{ asset('src2/js/jquery.scrollUp.min.js') }}"></script>

<!-- Custom Scrollbar -->
<script src="{{ asset('src2/js/scrollbar/jquery.mCustomScrollbar.concat.min.js') }}"></script>
<script src="{{ asset('src2/js/scrollbar/mCustomScrollbar-active.js') }}"></script>

<!-- MetisMenu -->
<script src="{{ asset('src2/js/metisMenu/metisMenu.min.js') }}"></script>
<script src="{{ asset('src2/js/metisMenu/metisMenu-active.js') }}"></script>

<!-- MorrisJS -->
<script src="{{ asset('src2/js/morrisjs/raphael-min.js') }}"></script>
<script src="{{ asset('src2/js/morrisjs/morris.js') }}"></script>
<script src="{{ asset('src2/js/morrisjs/morris-active.js') }}"></script>

<!-- Sparkline -->
<script src="{{ asset('src2/js/sparkline/jquery.sparkline.min.js') }}"></script>
<script src="{{ asset('src2/js/sparkline/jquery.charts-sparkline.js') }}"></script>

<!-- Calendar -->
<script src="{{ asset('src2/js/calendar/moment.min.js') }}"></script>
<script src="{{ asset('src2/js/calendar/fullcalendar.min.js') }}"></script>
<script src="{{ asset('src2/js/calendar/fullcalendar-active.js') }}"></script>

<!-- Touchspin -->
<script src="{{ asset('src2/js/touchspin/jquery.bootstrap-touchspin.min.js') }}"></script>
<script src="{{ asset('src2/js/touchspin/touchspin-active.js') }}"></script>

<!-- Colorpicker -->
<script src="{{ asset('src2/js/colorpicker/jquery.spectrum.min.js') }}"></script>
<script src="{{ asset('src2/js/colorpicker/color-picker-active.js') }}"></script>

<!-- Datepicker -->
<script src="{{ asset('src2/js/datapicker/bootstrap-datepicker.js') }}"></script>
<script src="{{ asset('src2/js/datapicker/datepicker-active.js') }}"></script>

<!-- Input Mask -->
<script src="{{ asset('src2/js/input-mask/jasny-bootstrap.min.js') }}"></script>

<!-- Chosen -->
<script src="{{ asset('src2/js/chosen/chosen.jquery.js') }}"></script>
<script src="{{ asset('src2/js/chosen/chosen-active.js') }}"></script>

<!-- Select2 -->
<script src="{{ asset('src2/js/select2/select2.full.min.js') }}"></script>
<script src="{{ asset('src2/js/select2/select2-active.js') }}"></script>

<!-- Ion Range Slider -->
<script src="{{ asset('src2/js/ionRangeSlider/ion.rangeSlider.min.js') }}"></script>
<script src="{{ asset('src2/js/ionRangeSlider/ion.rangeSlider.active.js') }}"></script>

<!-- Range Slider -->
<script src="{{ asset('src2/js/rangle-slider/jquery-ui-1.10.4.custom.min.js') }}"></script>
<script src="{{ asset('src2/js/rangle-slider/jquery-ui-touch-punch.min.js') }}"></script>
<script src="{{ asset('src2/js/rangle-slider/rangle-active.js') }}"></script>

<!-- Knob -->
<script src="{{ asset('src2/js/knob/jquery.knob.js') }}"></script>
<script src="{{ asset('src2/js/knob/knob-active.js') }}"></script>

<!-- Tabs -->
<script src="{{ asset('src2/js/tab.js') }}"></script>

<!-- Data Tables -->
<script src="{{ asset('src2/js/data-table/bootstrap-table.js') }}"></script>
<script src="{{ asset('src2/js/data-table/tableExport.js') }}"></script>
<script src="{{ asset('src2/js/data-table/data-table-active.js') }}"></script>
<script src="{{ asset('src2/js/data-table/bootstrap-table-editable.js') }}"></script>
<script src="{{ asset('src2/js/data-table/bootstrap-editable.js') }}"></script>
<script src="{{ asset('src2/js/data-table/bootstrap-table-resizable.js') }}"></script>
<script src="{{ asset('src2/js/data-table/colResizable-1.5.source.js') }}"></script>
<script src="{{ asset('src2/js/data-table/bootstrap-table-export.js') }}"></script>

<!-- Editable -->
<script src="{{ asset('src2/js/editable/jquery.mockjax.js') }}"></script>
<script src="{{ asset('src2/js/editable/mock-active.js') }}"></script>
<script src="{{ asset('src2/js/editable/select2.js') }}"></script>
<script src="{{ asset('src2/js/editable/moment.min.js') }}"></script>
<script src="{{ asset('src2/js/editable/bootstrap-datetimepicker.js') }}"></script>
<script src="{{ asset('src2/js/editable/bootstrap-editable.js') }}"></script>
<script src="{{ asset('src2/js/editable/xediable-active.js') }}"></script>

<!-- Charts -->
<script src="{{ asset('src2/js/chart/jquery.peity.min.js') }}"></script>
<script src="{{ asset('src2/js/peity/peity-active.js') }}"></script>

<!-- Plugins & Main -->
<script src="{{ asset('src2/js/plugins.js') }}"></script>
<script src="{{ asset('src2/js/main.js') }}"></script>
