@extends('layouts.master2')
@section('content')
    @php
        $moduleNom = strtolower($module_nom);
    @endphp
    @switch($moduleNom)
        @case('audit de conformité')
            @include('conformite/index')
        @break

        @case('rh')
            @include('components/yodirh/dashboard')
        @break
        @case('documents owncloud')
            @include('components/cloudDoc/dashboard')
        @break
        @case('configurations')
            @include('components/configuration/dashboard')
        @break

        @default
            @include('components/yodirh/dashboard')
    @endswitch

@endsection
