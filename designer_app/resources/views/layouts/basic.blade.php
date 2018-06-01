<!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8 no-js"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9 no-js"> <![endif]-->
<!--[if !IE]><!-->
<html lang="{{ config('app.locale') }}">
    <!--<![endif]-->
    
    <!-- BEGIN HEAD -->
    @include('partials.header')
    <!-- END HEAD -->

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

        <!-- BEGIN SCRIPT -->
        @include('partials.script')
        <!-- END SCRIPT -->

    </body>
</html>