<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-bs-theme="light" class="set-nav-dark">
<head>
    @include('layouts.partials.head')
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

@php
    $pageModules = $pageModules ?? [];
@endphp

<body class="content-has-right" data-page-modules='@json($pageModules)'>
    {{-- daca ai echivalentul $langs din CI4, aici il pui ca <script src="..."></script> --}}
    @if(!empty($langs))
        {!! $langs !!}
    @endif

    <div class="app-wrap">
        {{-- header (topright) e inclus din left.blade.php, ca in CI4 --}}
        @include('layouts.partials.left')

        @yield('content')

        {{-- Drawer Settings (butonul din header cauta aside.js-drawer-settings) --}}
        @include('layouts.partials.app-settings-drawer')
    </div>

    <script>loadPanelState();</script>
    @include('layouts.partials.footer-scripts')
</body>
</html>
