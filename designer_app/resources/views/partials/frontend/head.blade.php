<head>
    <meta charset="utf-8">
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    @include('partials.meta')

    <title>{{ App\Setting::get_setting('site_title') }}</title>
    <script defer="defer" src="https://use.fontawesome.com/releases/v5.0.8/js/all.js"></script>
    <link rel="stylesheet" href="{{ asset('frontend/styles/vendor.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend/styles/main.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend/styles/components.css') }}">
    <script src="{{ asset('frontend/scripts/vendor/modernizr.js') }}"></script>

    <link rel="apple-touch-icon" sizes="32x32" href="{{ asset('assets/uploads/ico.png') }}">
    <link rel="icon" type="image/png" href="{{ asset('assets/uploads/ico.png') }} " sizes="32x32">

    <style>
    .col-centered {margin: 0 auto;}                
    </style>

    @yield('style')

</head>