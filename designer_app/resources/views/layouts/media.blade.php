<!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8 no-js"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9 no-js"> <![endif]-->
<!--[if !IE]><!-->
<html lang="{{ config('app.locale') }}">
    <!--<![endif]-->
    
<head>
    <meta charset="utf-8" />
    <title>{{ App\Setting::get_setting('site_title') }}</title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta content="width=device-width, initial-scale=1" name="viewport" />
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <script src="{{ asset('assets/global/plugins/pace/pace.min.js') }}" type="text/javascript"></script>
    <link href="{{ asset('assets/global/plugins/pace/themes/pace-theme-flash.css') }}" rel="stylesheet" type="text/css" />

    <!-- BEGIN GLOBAL MANDATORY STYLES -->
    <link href="{{ asset('assets/customs/fonts.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/global/plugins/font-awesome/css/font-awesome.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/global/plugins/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css" />
    <!-- END GLOBAL MANDATORY STYLES -->

    <!-- BEGIN PAGE LEVEL PLUGINS -->
    <link href="{{ asset('assets/global/plugins/dropzone/dropzone.min.css') }}" rel="stylesheet" type="text/css" />
    <!-- END PAGE LEVEL PLUGINS -->


    <!-- BEGIN THEME LAYOUT STYLES -->
    <link href="{{ asset('assets/layouts/layout/css/layout.min.css') }}" rel="stylesheet" type="text/css" />
    <!-- END THEME LAYOUT STYLES -->

    <!-- BEGIN CUSTOM STYLES -->
    <link href="{{ asset('assets/customs/backend.css') }}" rel="stylesheet" type="text/css" />
    @yield('style')
    <!-- END CUSTOM STYLES -->

    <link rel="shortcut icon" href="{{ asset('favicon.png') }}" /> 

    <script src="{{ asset('assets/global/plugins/jquery.min.js') }}" type="text/javascript"></script>  

</head>

    <body class="page-header-fixed page-sidebar-closed-hide-logo page-content-white page-full-width page-md">
        <div class="page-wrapper">

            <!-- BEGIN CONTENT -->
            <div class="page-content-wrapper">
                <!-- BEGIN CONTENT BODY -->
                <div class="page-content">

                    <!-- BEIGN CONTENT BODY -->
                    @yield('content')     
                    <!-- END CONTENT BODY -->

                </div>
                <!-- END CONTENT BODY -->
            </div>
            <!-- END CONTENT -->

        </div>

        <!--[if lt IE 9]>
        <script src="{{ asset('assets/global/plugins/respond.min.js') }}"></script>
        <script src="{{ asset('assets/global/plugins/excanvas.min.js') }}"></script> 
        <script src="{{ asset('assets/global/plugins/ie8.fix.min.js') }}"></script> 
        <![endif]-->

        <script src="{{ asset('assets/global/plugins/bootstrap/js/bootstrap.min.js') }}"" type="text/javascript"></script>
        <script src="{{ asset('assets/global/plugins/jquery-ui/jquery-ui.min.js') }}" type="text/javascript"></script>
        <script src="{{ asset('assets/global/scripts/app.min.js') }}" type="text/javascript"></script>
        <script src="{{ asset('assets/layouts/layout/scripts/layout.min.js') }}" type="text/javascript"></script>


        @yield('plugin_script')

        @yield('script')

        @yield('filter_script')



    </body>
</html>