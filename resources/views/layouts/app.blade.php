<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') · Eventos CE</title>
    <link href="{{ asset('assets/vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/vendor/bootstrap-icons/font/bootstrap-icons.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/vendor/fonts/inter/inter.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/app.css') }}" rel="stylesheet">
    @stack('styles')
</head>
<body>
<input type="checkbox" class="sb-check" id="sbToggle">
<div class="layout d-flex min-vh-100">

    @include('partials.sidebar')
    <label for="sbToggle" class="sb-backdrop position-fixed top-0 start-0 end-0 bottom-0"></label>

    <div class="main-wrap d-flex flex-column flex-grow-1">
        @include('partials.topbar')

        <main class="content flex-grow-1">
            @yield('content')
        </main>
    </div>
</div>
<script src="{{ asset('assets/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
@stack('scripts')
</body>
</html>
