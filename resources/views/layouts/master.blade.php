@include('components/head')
<body>
    {{-- @include('components/pre-loader') --}}

    @include('components/header')

            @yield('content')
    @include('components/footer')

@include('components.script')
</body>
</html>
