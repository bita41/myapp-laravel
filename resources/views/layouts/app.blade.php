<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-bs-theme="light" class="set-nav-dark">
<head>
    @include('layouts.partials.head')
    @vite(['resources/css/app.css', 'resources/js/app.ts'])
    {{-- Debug: HOT file (sterge dupa ce confirmi DEV mode) --}}
    <!-- HOT FILE EXISTS: {{ is_file(public_path('hot')) ? 'YES' : 'NO' }} -->
    <!-- HOT FILE CONTENT: {{ is_file(public_path('hot')) ? trim(file_get_contents(public_path('hot'))) : '' }} -->
</head>

@php
    $pageModules = $pageModules ?? [];
@endphp

<body class="content-has-right" data-page-modules='@json($pageModules)'>
    @if(!empty($langKeys))
        <script src="{{ route('admin.lang.js', ['keys' => $langKeys]) }}"></script>
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
