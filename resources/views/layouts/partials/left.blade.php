@php
    $uri = url()->current();
@endphp
{{-- topright (header) ca in CI4 load_header -> left --}}
@include('layouts.partials.header')

<aside class="app-sidebar d-flex flex-column">
    <nav id="js-primary-nav" class="primary-nav flex-grow-1 custom-scroll">
        <ul id="js-nav-menu" class="nav-menu">
            <li class="nav-title"><span>{{ __('Main') }}</span></li>
            <li class="nav-item {{ request()->routeIs('dashboard.index') ? 'active' : '' }}">
                <a href="{{ route('dashboard.index') }}">
                    <svg class="sa-icon">
                        <use href="{{ asset('sa5/img/sprite.svg#home') }}"></use>
                    </svg>
                    <span class="nav-link-text">{{ __('Home') }}</span>
                </a>
            </li>
            <li class="nav-item {{ request()->routeIs('customers.*') ? 'active' : '' }}">
                <a href="{{ route('customers.index') }}">
                    <svg class="sa-icon">
                        <use href="{{ asset('sa5/img/sprite.svg#users') }}"></use>
                    </svg>
                    <span class="nav-link-text">{{ __('Customers') }}</span>
                </a>
            </li>
            <li class="nav-item {{ request()->routeIs('projects.*') ? 'active' : '' }}">
                <a href="{{ route('projects.index') }}">
                    <svg class="sa-icon">
                        <use href="{{ asset('sa5/img/sprite.svg#briefcase') }}"></use>
                    </svg>
                    <span class="nav-link-text">{{ __('Projects') }}</span>
                </a>
            </li>
            <li class="nav-item {{ request()->routeIs('tasks.*') ? 'active' : '' }}">
                <a href="{{ route('tasks.index') }}">
                    <svg class="sa-icon">
                        <use href="{{ asset('sa5/img/sprite.svg#clipboard') }}"></use>
                    </svg>
                    <span class="nav-link-text">{{ __('Tasks') }}</span>
                </a>
            </li>
            @php
                $settingsOpen = request()->routeIs('settings.*');
            @endphp
            <li class="nav-item {{ $settingsOpen ? 'active open' : '' }}">
                <a href="#" aria-expanded="{{ $settingsOpen ? 'true' : 'false' }}">
                    <svg class="sa-icon">
                        <use href="{{ asset('sa5/img/sprite.svg#settings') }}"></use>
                    </svg>
                    <span class="nav-link-text">{{ __('Settings') }}</span>
                </a>
                <ul>
                    <li class="nav-item {{ request()->routeIs('settings.dictionaries.*') ? 'active' : '' }}">
                        <a href="{{ route('settings.dictionaries.index') }}">
                            <span class="nav-link-text">{{ __('Dictionaries') }}</span>
                        </a>
                    </li>
                    <li class="nav-item {{ request()->routeIs('settings.users.*') ? 'active' : '' }}">
                        <a href="{{ route('settings.users.index') }}">
                            <span class="nav-link-text">{{ __('Users') }}</span>
                        </a>
                    </li>
                    <li class="nav-item {{ request()->routeIs('settings.roles.*') ? 'active' : '' }}">
                        <a href="{{ route('settings.roles.index') }}">
                            <span class="nav-link-text">{{ __('Roles') }}</span>
                        </a>
                    </li>
                    <li class="nav-item {{ request()->routeIs('settings.index') ? 'active' : '' }}">
                        <a href="{{ route('settings.index') }}">
                            <span class="nav-link-text">{{ __('Settings') }}</span>
                        </a>
                    </li>
                </ul>
            </li>
        </ul>
    </nav>
</aside>
