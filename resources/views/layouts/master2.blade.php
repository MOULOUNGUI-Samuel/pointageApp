@include('components/head2')

<body>
    @include('components/sidebar')
    <div class="all-content-wrapper">
        @include('components/header2')

        @yield('content2')
        {{-- @include('components/footer') --}}
    </div>
    @include('components.script2')
</body>

</html>
