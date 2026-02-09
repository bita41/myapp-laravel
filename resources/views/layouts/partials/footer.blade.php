<footer class="app-footer">
    <div class="app-footer-content flex-grow-1 d-flex align-items-center">
        {{ config('app.name') }} &copy; {{ date('Y') }}. All rights reserved
        <a href="#top" class="ms-auto hidden-mobile" aria-label="{{ __('Back to top') }}">
            <svg class="sa-icon sa-thick sa-icon-primary">
                <use href="{{ asset('sa5/img/sprite.svg#arrow-up') }}"></use>
            </svg>
        </a>
    </div>
</footer>
