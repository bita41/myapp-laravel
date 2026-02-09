<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="{{ config('app.name') }}">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', __('Login')) - {{ config('app.name') }}</title>

    {{-- Smart Admin 5.0.3: Bootstrap 5 + Smart App + Authentication --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="{{ asset('sa5/css/smartapp.css') }}" rel="stylesheet">
    <link href="{{ asset('sa5/css/authentication.css') }}" rel="stylesheet">

    <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">

    @stack('styles')
</head>
<body>

    {{-- Main --}}
    @yield('content')

    {{-- Scripts: Bootstrap, Smart App --}}
    <script src="{{ asset('sa5/scripts/smartApp.js') }}" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" defer></script>
    <script>
        window.APP_URL = @json(config('app.url'));
    </script>
    @stack('scripts')
</body>
</html>
