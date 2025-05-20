@extends('layouts.master2')
@section('content')
    @php
        $moduleNom = strtolower($module_nom);
    @endphp
    @switch($moduleNom)
        @case('smi')
            @include('components/smi/dashboard')
        @break

        @case('rh')
            @include('components/yodirh/dashboard')
        @break
        @case('documents owncloud')
            @include('components/cloudDoc/dashboard')
        @break

        @default
            @include('components/yodirh/dashboard')
    @endswitch

@endsection
