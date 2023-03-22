<!DOCTYPE html>
<!--[if IE 9]> <html class="no-js lt-ie10" lang="en"> <![endif]-->
<!--[if gt IE 9]><!--> <html class="no-js" lang="en"> <!--<![endif]-->
    <head>
        <meta charset="utf-8">

        <title>Candellabra</title>

        <meta name="description" content="">
        <meta name="author" content="pixelcave">
        <meta name="robots" content="noindex, nofollow">
        <meta name="viewport" content="width=device-width,initial-scale=1.0,user-scalable=0">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <!-- Icons -->
        <link rel="shortcut icon" href="/img/favicons/favicon.ico">
        <link rel="apple-touch-icon" href="/img/favicons/favicon.png" sizes="192x192">
        <!-- END Icons -->

        <!-- Stylesheets -->
        <link rel="stylesheet" href="/css/bootstrap.min.css">
        <link rel="stylesheet" href="/css/plugins.css">
        <link rel="stylesheet" href="/css/main.css">
        <link rel="stylesheet" href="/css/themes.css">
        <link rel="stylesheet" href="/css/app.css">
        <link rel="stylesheet" href="/js/app.css">
        <!-- END Stylesheets -->

        <!-- import CSS -->
        <link rel="stylesheet" href="https://unpkg.com/element-ui/lib/theme-chalk/index.css">

        @yield('styles')
    </head>
    <body>
        <div id="app">


            <div id="page-wrapper">
                <!-- Preloader functionality (initialized in js/app.js) - pageLoading() -->
                <!-- Used only if page preloader is enabled from inc/config (PHP version) or the class 'page-loading' is added in #page-wrapper element (HTML version) -->
                <div class="preloader themed-background">
                    <h1 class="push-top-bottom text-light text-center"><strong>Pro</strong>UI</h1>
                    <div class="inner">
                        <h3 class="text-light visible-lt-ie9 visible-lt-ie10"><strong>Loading..</strong></h3>
                        <div class="preloader-spinner hidden-lt-ie9 hidden-lt-ie10"></div>
                    </div>
                </div>
                <!-- END Preloader -->

                <!-- Page Container -->
                <!-- In the PHP version you can set the following options from inc/config file -->
                <!--
                    Available #page-container classes:

                    '' (None)                                       for a full main and alternative sidebar hidden by default (> 991px)

                    'sidebar-visible-lg'                            for a full main sidebar visible by default (> 991px)
                    'sidebar-partial'                               for a partial main sidebar which opens on mouse hover, hidden by default (> 991px)
                    'sidebar-partial sidebar-visible-lg'            for a partial main sidebar which opens on mouse hover, visible by default (> 991px)
                    'sidebar-mini sidebar-visible-lg-mini'          for a mini main sidebar with a flyout menu, enabled by default (> 991px + Best with static layout)
                    'sidebar-mini sidebar-visible-lg'               for a mini main sidebar with a flyout menu, disabled by default (> 991px + Best with static layout)

                    'sidebar-alt-visible-lg'                        for a full alternative sidebar visible by default (> 991px)
                    'sidebar-alt-partial'                           for a partial alternative sidebar which opens on mouse hover, hidden by default (> 991px)
                    'sidebar-alt-partial sidebar-alt-visible-lg'    for a partial alternative sidebar which opens on mouse hover, visible by default (> 991px)

                    'sidebar-partial sidebar-alt-partial'           for both sidebars partial which open on mouse hover, hidden by default (> 991px)

                    'sidebar-no-animations'                         add this as extra for disabling sidebar animations on large screens (> 991px) - Better performance with heavy pages!

                    'style-alt'                                     for an alternative main style (without it: the default style)
                    'footer-fixed'                                  for a fixed footer (without it: a static footer)

                    'disable-menu-autoscroll'                       add this to disable the main menu auto scrolling when opening a submenu

                    'header-fixed-top'                              has to be added only if the class 'navbar-fixed-top' was added on header.navbar
                    'header-fixed-bottom'                           has to be added only if the class 'navbar-fixed-bottom' was added on header.navbar

                    'enable-cookies'                                enables cookies for remembering active color theme when changed from the sidebar links
                -->
                <div id="page-container" class="sidebar-partial sidebar-visible-lg sidebar-no-animations">

                    @include('layouts/sidebar')

                    <div id="main-container">

                        @include('layouts/navbar')

                        <div id="page-content">
                            @yield('content')

                            <loading :loading="loading">
                                <router-view :key="$route.path"></router-view>
                            </loading>
                        </div>

                        @include('layouts/footer')

                    </div>

                </div>
                <!-- END Page Container -->
            </div>
            <!-- END Page Wrapper -->

            <a href="#" id="to-top"><i class="fa fa-angle-double-up"></i></a>
        </div>

        <script>window.config = {!! getFrontConfig() !!}</script>
        <script src="/js/jquery.min.js"></script>
        <script src="/js/vendor/bootstrap.min.js"></script>
        <script src="/packages/ckeditor/ckeditor.js" defer></script>
        <script src="/js/plugins.js"></script>

        <script src="/js/manifest.js"></script>
        <script src="/js/vendor.js"></script>
        <script src="/js/app.js"></script>

        @yield('scripts')
    </body>
</html>