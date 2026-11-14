<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Uhalisia wa Tanzania Safaris and Tours')</title>

    <!-- Local Bootstrap 5.3.3 CSS -->
    <link rel="stylesheet" href="{{ asset('vendor/bootstrap/css/bootstrap.min.css') }}">

    <!-- Local Custom Brand Styling (includes fonts & icons) -->
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">

    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">

    @stack('styles')
</head>
<body class="@yield('body_class')">
    @yield('content')

    <!-- Local Bootstrap 5.3.3 JS Bundle -->
    <script src="{{ asset('vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>

    <!-- Local Alpine.js for lightweight reactivity -->
    <script defer src="{{ asset('vendor/alpine/alpine.min.js') }}"></script>

    @stack('scripts')
</body>
</html>
