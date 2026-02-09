<meta charset="utf-8">
<base href="{{ url('/') }}/">
<title>@yield('title', __('Dashboard')) | {{ config('app.name') }}</title>
<meta name="description" content="Page Description">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no, maximum-scale=5">
<meta name="csrf-token" content="{{ csrf_token() }}">

{{-- Standard favicon for browsers --}}
<link rel="icon" href="{{ asset('sa5/img/favicon-32x32.png') }}" type="image/png" sizes="32x32">
<link rel="apple-touch-icon" href="{{ asset('sa5/img/apple-touch-icon.png') }}" sizes="180x180">
{{-- Android/Chrome (Progressive Web App) --}}
<link rel="icon" href="{{ asset('sa5/img/favicon-192x192.png') }}" type="image/png" sizes="192x192">

{{-- Call App Mode on ios devices --}}
<meta name="mobile-web-app-capable" content="yes">
{{-- Remove Tap Highlight on Windows Phone IE --}}
<meta name="msapplication-tap-highlight" content="no">

{{-- Vendor CSS (cache-busting ?v=filemtime) --}}
<link rel="stylesheet" media="screen, print" href="{{ \App\Support\Asset::v('sa5/css/bootstrap.css') }}">
<link rel="stylesheet" media="screen, print" href="{{ \App\Support\Asset::v('sa5/css/waves.css') }}">

{{-- Base CSS --}}
<link rel="stylesheet" media="screen, print" href="{{ \App\Support\Asset::v('sa5/css/smartapp.css') }}">
<link rel="stylesheet" media="screen, print" href="{{ \App\Support\Asset::v('sa5/css/sa-icons.css') }}">

{{-- Theme Style --}}
<link id="theme-style" rel="stylesheet" media="screen, print">

{{-- Page specific CSS --}}
<link rel="stylesheet" media="screen, print" href="{{ \App\Support\Asset::v('sa5/css/iconsdemo.css') }}">
<link rel="stylesheet" media="screen, print" href="{{ \App\Support\Asset::v('sa5/css/fontawesome.css') }}">

{{-- Custom CSS (DataTables loaded via Vite in app.css; other plugins below) --}}
<link rel="stylesheet" media="screen, print" href="{{ asset('sa5/css/plugin/select2/select2.min.css') }}">
<link rel="stylesheet" media="screen, print" href="{{ asset('sa5/css/plugin/jquery-ui/jquery-ui.min.css') }}">

{{-- Custom CSS (overrides) --}}
<link rel="stylesheet" media="screen, print" href="{{ \App\Support\Asset::v('sa5/css/style.css') }}">

@stack('styles')
@foreach($custom_css ?? [] as $href)
    <link rel="stylesheet" href="{{ asset($href) }}">
@endforeach

<script src="{{ asset('sa5/js/custom/jquery.min.js') }}"></script>

<script>
    var BASE_URL = {!! json_encode(url('/')) !!};
    var BASE_URL_ADMIN = {!! json_encode(url('/admin')) !!};
    var THEME_CSS_BASE = {!! json_encode(rtrim(asset('sa5/css'), '/') . '/') !!};
    var NUM_LANG = {!! json_encode((int) ($numLang ?? 0)) !!};
    var DATATABLE_ELEMENTS_PER_PAGE = {!! json_encode((int) config('custom.datatable_per_page', 50)) !!};
</script>


<script>
    //hint: place this right after 'body' tag for instantaneous loading
    'use strict';
    var htmlRoot = document.getElementsByTagName('HTML')[0],
        //save states
        savePanelStateEnabled = true,
        //mobile operator on
        mobileOperator = function()
        {
            // Check user agent
            const userAgent = navigator.userAgent.toLowerCase();
            const isMobileUserAgent = /iphone|ipad|ipod|android|blackberry|mini|windows\sce|palm/i.test(userAgent);
            // Check for touch support
            const isTouchDevice = 'ontouchstart' in window || navigator.maxTouchPoints > 0;
            // Check screen size (optional)
            const isSmallScreen = window.innerWidth <= 992; // Adjust the breakpoint as needed
            // Return true if any of the conditions are met
            return isMobileUserAgent || isTouchDevice || isSmallScreen;
        },
        //filter
        filterClass = function(t, e)
        {
            return String(t).split(/[^\w-]+/).filter(function(t)
            {
                return e.test(t)
            }).join(' ')
        },
        //load
        loadSettings = function()
        {
            var t = localStorage.getItem('layoutSettings') || '',
                e = t ? JSON.parse(t) :
                    {};
            // Load theme setting
            var savedTheme = e.theme || 'light';
            htmlRoot.setAttribute('data-bs-theme', savedTheme);
            // Load theme style CSS file only if one was saved
            var themeStyle = e.themeStyle || '';
            if (themeStyle)
            {
                loadThemeStyle(themeStyle);
            }
            return Object.assign(
                {
                    htmlRoot: '',
                    theme: savedTheme,
                    themeStyle: themeStyle
                }, e)
        },
        //save
        saveSettings = function()
        {
            layoutSettings.htmlRoot = filterClass(htmlRoot.className, /^(set)-/i);
            layoutSettings.theme = htmlRoot.getAttribute('data-bs-theme') || 'light';
            // Save theme style CSS path
            var themeStyleElement = document.getElementById('theme-style');
            if (themeStyleElement)
            {
                layoutSettings.themeStyle = themeStyleElement.getAttribute('href');
            }
            else
            {
                layoutSettings.themeStyle = '';
            }
            localStorage.setItem("layoutSettings", JSON.stringify(layoutSettings));
            savingIndicator();
        },
        //reset
        resetSettings = function()
        {
            localStorage.setItem("layoutSettings", "");
            // reset data-bs-theme
            htmlRoot.setAttribute('data-bs-theme', 'light');
            // reset theme style element if it exists
            document.getElementById('theme-style').setAttribute('href', '');
            // refresh page
            window.location.reload();
        },
        //load theme style
        loadThemeStyle = function(themeStyle)
        {
            // Get existing theme style if it exists
            var existingThemeStyle = document.getElementById('theme-style');
            if (existingThemeStyle)
            {
                // Update existing theme style's href
                existingThemeStyle.href = themeStyle || '';
            }
            else if (themeStyle)
            {
                // Create new theme style element if none exists and themeStyle is provided
                var linkElement = document.createElement('link');
                linkElement.id = 'theme-style';
                linkElement.rel = 'stylesheet';
                linkElement.href = themeStyle;
                document.head.appendChild(linkElement);
            }
        },
        //get page id
        getPageIdentifier = function()
        {
            return window.location.pathname.split('/').pop() || 'index.html';
        },
        //save panel state
        savePanelState = function()
        {
            if (!savePanelStateEnabled) return;
            var state = [];
            var columns = document.querySelectorAll('.main-content > .row > [class^="col-"]');
            columns.forEach(function(column, columnIndex)
            {
                var panels = column.querySelectorAll('.panel');
                panels.forEach(function(panel, position)
                {
                    var panelHeader = panel.querySelector('.panel-hdr');
                    // Save panel classes excluding 'panel' and 'panel-fullscreen'
                    var panelClasses = panel.className.split(' ').filter(function(cls)
                    {
                        return cls !== 'panel' && cls !== 'panel-fullscreen';
                    }).join(' ');
                    // Save header classes excluding 'panel-hdr'
                    var headerClasses = panelHeader ? panelHeader.className.split(' ').filter(function(cls)
                    {
                        return cls !== 'panel-hdr';
                    }).join(' ') : '';
                    state.push(
                        {
                            id: panel.id,
                            column: columnIndex,
                            position: position, // Save position within column
                            classes: panelClasses,
                            headerClasses: headerClasses
                        });
                });
            });
            var pageId = getPageIdentifier();
            var allStates = JSON.parse(localStorage.getItem('allPanelStates') || '{}');
            allStates[pageId] = state;
            localStorage.setItem('allPanelStates', JSON.stringify(allStates));
            savingIndicator();
        },
        loadPanelState = function()
        {
            var pageId = getPageIdentifier();
            var allStates = JSON.parse(localStorage.getItem('allPanelStates') || '{}');
            var savedState = allStates[pageId];
            if (!savedState) return;
            // Use same selector as save function
            var columns = Array.from(document.querySelectorAll('.main-content > .row > [class^="col-"]'));
            // Store all existing panels in a map before removing them
            var panelMap = {};
            columns.forEach(function(column)
            {
                var existingPanels = Array.from(column.querySelectorAll('.panel'));
                existingPanels.forEach(function(panel)
                {
                    panelMap[panel.id] = panel;
                    panel.remove();
                });
            });
            // Sort state by column and position
            savedState.sort(function(a, b)
            {
                if (a.column === b.column)
                {
                    return a.position - b.position;
                }
                return a.column - b.column;
            });
            // Reinsert panels in correct order
            savedState.forEach(function(item)
            {
                var panel = panelMap[item.id];
                if (panel && columns[item.column])
                {
                    // Update panel classes
                    panel.className = 'panel ' + (item.classes || '');
                    // Update header classes
                    var panelHeader = panel.querySelector('.panel-hdr');
                    if (panelHeader && item.headerClasses)
                    {
                        panelHeader.className = 'panel-hdr ' + item.headerClasses;
                    }
                    // Append to correct column
                    columns[item.column].appendChild(panel);
                }
            });
        },
        //reset panel state
        resetPanelState = function()
        {
            var pageId = getPageIdentifier();
            var allStates = JSON.parse(localStorage.getItem('allPanelStates') || '{}');
            delete allStates[pageId];
            localStorage.setItem('allPanelStates', JSON.stringify(allStates));
            //refresh page
            window.location.reload();
        },
        savingIndicator = function()
        {
            // Create or get the indicator element
            let indicator = document.getElementById('saving-indicator');
            if (!indicator)
            {
                indicator = document.createElement('div');
                indicator.id = 'saving-indicator';
                document.body.appendChild(indicator);
            }
            // Show saving animation
            //indicator.textContent = '';
            indicator.className = 'saving-indicator spinner-border show';
            // After a brief delay, show success and hide
            setTimeout(() =>
            {
                //indicator.textContent = '';
                indicator.className = 'saving-indicator spinner-border show success';
                setTimeout(() =>
                {
                    indicator.className = 'saving-indicator spinner-border success';
                }, 500);
            }, 300);
        },
        //load page layout settings
        layoutSettings = loadSettings();
        // Aplica clasele salvate pe html (inclusiv cand sunt goale – ex. Dark Nav debifat)
        if (localStorage.getItem('layoutSettings')) {
            htmlRoot.className = layoutSettings.htmlRoot || '';
        }
    //load panel settings is triggered just before <script> tag
</script>
