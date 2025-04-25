@include('components/head')
<body style="background: linear-gradient(rgba(0, 0, 0, 0.795), rgba(0, 0, 0, 0.836)),
url('{{ asset('src/images/login.webp') }}') no-repeat center center;
background-size: cover;
background-attachment: fixed;
color: #fff;">
    
    {{-- @include('components/pre-loader') --}}

    @include('components/header')

            @yield('content')
    {{-- @include('components/footer') --}}

@include('components.script')
</body>
</html>
