<!doctype html>
<html lang="fr">

<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>NedCore</title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
  
    <!-- Apple Touch Icon -->
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('assets/img/apple-touch-icon.png') }}">
  
    <!-- Favicon -->
    <link rel="icon" href="{{ asset('assets/img/authentication/logo_nedcore.JPG') }}" type="image/x-icon">
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('assets/img/authentication/logo_nedcore.JPG') }}">
  
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  
    <!-- Tabler Icons -->
    <link rel="stylesheet" href="{{ asset('assets/plugins/tabler-icons/tabler-icons.css') }}">
  
    <!-- Fontawesome -->
    <link rel="stylesheet" href="{{ asset('assets/plugins/fontawesome/css/fontawesome.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/plugins/fontawesome/css/all.min.css') }}">
  
    <!-- Datetimepicker CSS (pour .datetimepicker) -->
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap-datetimepicker.min.css') }}">
  
    <!-- Animations -->
    <link rel="stylesheet" href="{{ asset('assets/css/animate.css') }}">
  
    <!-- Select2 -->
    <link rel="stylesheet" href="{{ asset('assets/plugins/select2/css/select2.min.css') }}">
  
    <!-- Owl Carousel -->
    <link rel="stylesheet" href="{{ asset('assets/css/owl.carousel.min.css') }}">
  
    <!-- Color Picker -->
    <link rel="stylesheet" href="{{ asset('assets/plugins/flatpickr/flatpickr.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/plugins/@simonwep/pickr/themes/nano.min.css') }}">
  
    <!-- Datatable -->
    <link rel="stylesheet" href="{{ asset('assets/css/dataTables.bootstrap5.min.css') }}">
  
    <!-- Main CSS -->
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
  
    <!-- Preloader -->
    <link rel="stylesheet" href="{{ asset('src2/css/preloader/preloader-style.css') }}">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    
    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
  
    @if (request()->routeIs('paie'))
      <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
      <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.4.0/css/all.min.css">
      <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    @endif
      {{-- <!-- Daterangepikcer CSS -->
	<link rel="stylesheet" href="{{asset('assets/plugins/daterangepicker/daterangepicker.css')}}">

  <!-- Choices CSS -->
  <link rel="stylesheet" href="{{asset('assets/plugins/choices.js/public/assets/styles/choices.min.css')}}">

  <!-- Tabler Icon CSS -->
  <link rel="stylesheet" href="{{asset('assets/plugins/tabler-icons/tabler-icons.min.css')}}">

  <!-- Simplebar CSS -->
  <link rel="stylesheet" href="{{asset('assets/plugins/simplebar/simplebar.min.css')}}"> --}}

    <!-- Metas nécessaires -->
    <script src="https://js.pusher.com/beams/2.1.0/push-notifications-cdn.js"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="sw-beams" content="{{ asset('service-worker.js') }}">
    <meta name="user-id" content="{{ auth()->id() }}">
    <meta name="entreprise-id" content="{{ session('entreprise_id') }}">
    @vite(['resources/css/app.css','resources/js/app.js'])

  
    @livewireStyles
  </head>
  
  <style>
    @import url('https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700&family=Open+Sans:wght@400;600&display=swap');
    body { font-family: 'Inter', sans-serif; }
    .text-primary { color: #05436b !important; }
    .bg-primary { background-color: #05436b !important; color: #fff !important; }
    .bg-primary2 { background-color: #05426bc6 !important; color: #fff !important; }
    .shadow { box-shadow: 0 4px 8px rgba(0,0,0,.2); transition: .3s; border-radius: 5px; }
    .d-flex-justify-content-between { display:flex; justify-content:space-between; align-items:center; }
  </style>
  