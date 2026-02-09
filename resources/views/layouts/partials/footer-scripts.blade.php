@include('layouts.partials.remote-modals')

<div class="backdrop" data-action="toggle-swap" data-toggleclass="app-mobile-menu-open"></div>

{{-- Core scripts (CI4 order, cache-busting ?v=filemtime) --}}
<script src="{{ \App\Support\Asset::v('sa5/scripts/smartApp.js') }}"></script>
<script src="{{ \App\Support\Asset::v('sa5/scripts/smartNavigation.js') }}"></script>
<script src="{{ \App\Support\Asset::v('sa5/scripts/smartFilter.js') }}"></script>
<script src="{{ \App\Support\Asset::v('sa5/scripts/thirdparty/bootstrap/bootstrap.bundle.js') }}"></script>
<script src="{{ \App\Support\Asset::v('sa5/scripts/thirdparty/sortable/sortable.js') }}"></script>
<script src="{{ \App\Support\Asset::v('sa5/scripts/smartSlimscroll.js') }}"></script>
<script src="{{ \App\Support\Asset::v('sa5/scripts/thirdparty/wavejs/waves.js') }}"></script>
<script src="{{ \App\Support\Asset::v('sa5/scripts/iconography.js') }}"></script>

{{-- DataTables loaded via Vite (app.js). Bootbox below. --}}
<script src="{{ \App\Support\Asset::v('sa5/js/plugin/bootbox/bootbox.min.js') }}"></script>

{{-- Plugins --}}
<script src="{{ asset('sa5/js/plugin/jquery-ui/jquery-ui.min.js') }}"></script>
<script src="{{ asset('sa5/js/plugin/select2/select2.full.min.js') }}"></script>
<script src="{{ asset('sa5/js/plugin/ckeditor/ckeditor.js') }}"></script>
<script src="{{ asset('sa5/js/plugin/slugify/slugify.js') }}"></script>

{{-- Custom (modalHelper + app with AppUtils.dataTableDom, remote modals, form submit) --}}
<script src="{{ \App\Support\Asset::v('sa5/js/custom/modalHelper.js') }}"></script>
<script src="{{ \App\Support\Asset::v('sa5/js/custom/app.js') }}"></script>

<script>
    /* Navigation : smartNavigation.js */
    var nav;
    var navElement = document.querySelector('#js-primary-nav');
    if (navElement && typeof Navigation !== 'undefined') {
        nav = new Navigation(navElement, {
            accordion: true,
            slideUpSpeed: 350,
            slideDownSpeed: 470,
            closedSign: '<i class="sa sa-chevron-down"></i>',
            openedSign: '<i class="sa sa-chevron-up"></i>',
            initClass: 'js-nav-built',
            debug: false,
            instanceId: 'nav-' + Date.now(),
            maxDepth: 5,
            sanitize: true,
            animationTiming: 'easeOutExpo',
            debounceTime: 0,
            onError: function(err) { console.error('Navigation error:', err); }
        });
    }
    /* Waves */
    if (window.Waves) {
        Waves.attach('.btn:not(.js-waves-off):not(.btn-switch):not(.btn-panel):not(.btn-system):not([data-action="playsound"]), .js-waves-on', ['waves-themed']);
        Waves.init();
    }
    document.addEventListener('DOMContentLoaded', function() {
        if (typeof appDOM !== 'undefined') {
            appDOM.checkActiveStyles().debug(false);
        }
        var mainContent = document.querySelector('.main-content');
        if (mainContent) {
            var columns = document.querySelectorAll('.main-content:not(.sortable-off) > .row:not(.sortable-off) > [class^="col-"]');
            if (columns.length > 0 && typeof Sortable !== 'undefined' && typeof mobileOperator === 'function' && !mobileOperator()) {
                [].forEach.call(columns, function(column) {
                    Sortable.create(column, {
                        animation: 150,
                        ghostClass: 'panel-selected',
                        handle: '.panel-hdr > h2',
                        filter: '.panel-locked',
                        draggable: '.panel:not(.panel-locked):not(.panel-fullscreen)',
                        group: 'sapanels',
                        onEnd: function() {
                            if (typeof savePanelState === 'function') savePanelState();
                        }
                    });
                });
                mainContent.classList.add('sortable-active');
            } else {
                mainContent.classList.add('sortable-inactive');
            }
        }
        if (typeof smartSlimScroll !== 'undefined' && typeof mobileOperator === 'function' && !mobileOperator()) {
            var scrollEl = document.querySelector('.custom-scroll');
            if (scrollEl) {
                new smartSlimScroll('.custom-scroll', {
                    height: '100%',
                    size: '4px',
                    position: 'right',
                    color: '#000',
                    alwaysVisible: false,
                    railVisible: true,
                    railColor: '#222',
                    railOpacity: 0,
                    wheelStep: 10,
                    offsetX: '6px',
                    offsetY: '8px'
                });
            }
        } else if (typeof mobileOperator === 'function' && mobileOperator()) {
            document.body.classList.add('no-slimscroll');
        }
    });
    /* Bootstrap tooltips + popovers */
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.forEach(function(el) { new bootstrap.Tooltip(el); });
    var popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'));
    popoverTriggerList.forEach(function(el) { new bootstrap.Popover(el); });
    if (typeof bootstrap !== 'undefined' && bootstrap.Dropdown && bootstrap.Dropdown.Default) {
        bootstrap.Dropdown.Default.autoClose = 'outside';
    }
    /* FontAwesome icon set */
    if (typeof loadIconSet === 'function') {
        loadIconSet('fal');
    }
</script>

@foreach($custom_js ?? [] as $src)
    <script src="{{ asset($src) }}"></script>
@endforeach

@stack('scripts')
