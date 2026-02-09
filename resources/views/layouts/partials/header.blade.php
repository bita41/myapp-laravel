<header class="app-header">
    <!-- Collapse icon -->
    <div class="d-flex flex-grow-1 w-100 me-auto align-items-center">
        <!-- App logo -->
        <div class="app-logo flex-shrink-0" data-prefix="v5.1.0">
            <svg class="custom-logo">
                <use href="{{ asset('sa5/img/app-logo-new.svg?v=5#custom-logo') }}"></use>
            </svg>
        </div>
        <!-- Mobile menu -->
        <div class="mobile-menu-icon me-2 d-flex d-sm-flex d-md-flex d-lg-none flex-shrink-0" data-action="toggle-swap" data-toggleclass="app-mobile-menu-open" aria-label="{{ __('Toggle Mobile Menu') }}">
            <svg class="sa-icon">
                <use href="{{ asset('sa5/img/sprite.svg#menu') }}"></use>
            </svg>
        </div>
        <!-- Collapse icon -->
        <button type="button" class="collapse-icon me-3 d-none d-lg-inline-flex d-xl-inline-flex d-xxl-inline-flex" data-action="toggle" data-class="set-nav-minified" aria-label="{{ __('Toggle Navigation Size') }}">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 5 8">
                <polygon fill="#878787" points="4.5,1 3.8,0.2 0,4 3.8,7.8 4.5,7 1.5,4" />
            </svg>
        </button>
    </div>
    <!-- Settings -->
    <button type="button" class="btn btn-system hidden-mobile" data-action="toggle-swap" data-toggleclass="open" data-target="aside.js-drawer-settings" aria-label="{{ __('Open Settings') }}">
        <svg class="sa-icon sa-icon-2x">
            <use href="{{ asset('sa5/img/sprite.svg#settings') }}"></use>
        </svg>
    </button>
    <!-- Theme modes -->
    <button type="button" class="btn btn-system" data-action="toggle-theme" aria-label="{{ __('Toggle Dark Mode') }}" aria-pressed="false">
        <svg class="sa-icon sa-icon-2x">
            <use href="{{ asset('sa5/img/sprite.svg#circle') }}"></use>
        </svg>
    </button>

    @auth
    <!-- Profile -->
    @php
        $user = auth()->user();
        $userImage = $user->img ?? null;
        $userImageUrl = $userImage
            ? (str_starts_with($userImage, 'http') ? $userImage : asset($userImage))
            : ('https://ui-avatars.com/api/?name=' . rawurlencode($user->displayname ?? $user->email ?? 'U') . '&size=80&background=dee2e6');
    @endphp
    <button type="button" data-bs-toggle="dropdown" title="{{ $user->email ?? '' }}" class="btn-system bg-transparent d-flex flex-shrink-0 align-items-center justify-content-center" aria-label="{{ $user->displayname ?? $user->email }}">
        <img src="{{ $userImageUrl }}" class="profile-image profile-image-md rounded-circle" alt="{{ $user->displayname ?? 'User' }}">
    </button>
    <!-- Profile dropdown -->
    <div class="dropdown-menu dropdown-menu-animated">
        <div class="notification-header rounded-top mb-2">
            <div class="d-flex flex-row align-items-center mt-1 mb-1 color-white">
                <span class="status status-success d-inline-block me-2">
                    <img src="{{ $userImageUrl }}" class="profile-image rounded-circle" alt="{{ $user->displayname ?? '' }}">
                </span>
                <div class="info-card-text">
                    <div class="fs-lg text-truncate text-truncate-lg">{{ $user->displayname ?? $user->email }}</div>
                    <span class="text-truncate text-truncate-md opacity-80 fs-sm">{{ $user->email }}</span>
                </div>
            </div>
        </div>
        <div class="dropdown-divider m-0"></div>
        <a href="#" class="dropdown-item" data-action="app-reset">
            <span data-i18n="drpdwn.reset_layout">{{ __('Reset Layout') }}</span>
        </a>
        <a href="#" class="dropdown-item" data-action="toggle-swap" data-toggleclass="open" data-target="aside.js-drawer-settings">
            <span data-i18n="drpdwn.settings">{{ __('Settings') }}</span>
        </a>

        <div class="dropdown-divider m-0"></div>
        <a href="#"
           class="dropdown-item d-flex justify-content-between align-items-center btn-modal-remote"
           data-remote="{{ url('settings/users/edit/' . $user->user_id) }}"
           data-target="#modal-remote"
           data-toggle="modal"
           data-bs-target="#modal-remote"
           data-bs-toggle="modal"
           data-modal_title="{{ l('a-user-profile') }}"
           data-reload_internal_page="1">
            <span data-i18n="drpdwn.fullscreen">{{ l('a-user-profile') }}</span>
        </a>

        <div class="dropdown-divider m-0"></div>
        <form method="POST" action="{{ route('logout') }}" class="d-inline">
            @csrf
            <a class="dropdown-item py-3 fw-500 d-flex justify-content-between" href="{{ route('logout') }}"
               onclick="event.preventDefault(); this.closest('form').submit();">
                <span class="text-danger" data-i18n="drpdwn.page-logout">{{ l('a-logout') }}</span>
            </a>
        </form>
    </div>
    @else
    <a href="{{ route('login') }}" class="btn btn-system">{{ __('Login') }}</a>
    @endauth
</header>
