<!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8 no-js"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9 no-js"> <![endif]-->
<!--[if !IE]><!-->
<html lang="{{ config('app.locale') }}">
    <!--<![endif]-->
    
    <!-- BEGIN HEAD -->
    @include('partials.header')
    <!-- END HEAD -->

    <body class="page-header-fixed page-sidebar-closed-hide-logo page-container-bg-solid page-md">
        <div class="page-wrapper">

            <!-- BEGIN HEADER -->
            @include('partials.header-nav')    
            <!-- END HEADER -->

            <!-- BEGIN HEADER & CONTENT DIVIDER -->
            <div class="clearfix"></div>
            <!-- END HEADER & CONTENT DIVIDER -->

            <!-- BEGIN CONTAINER -->
            <div class="page-container">
                
                <!-- BEGIN SIDEBAR -->
                <div class="page-sidebar-wrapper">
                    <!-- BEGIN SIDEBAR -->
                    @include('partials.sidebar')  
                    <!-- END SIDEBAR -->
                </div>
                <!-- END SIDEBAR -->

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
            <!-- END CONTAINER -->

            <!-- BEGIN FOOTER -->
            @include('partials.footer')
            <!-- END FOOTER -->

            <!-- BEGIN POPUP MODAL -->
            @include('partials.popup-modal')
            <!-- END POPUP MODAL -->

        </div>

        <!-- BEGIN SCRIPT -->
        @include('partials.script')
        <!-- END SCRIPT -->

    </body>
</html>