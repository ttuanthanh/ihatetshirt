<!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8 no-js"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9 no-js"> <![endif]-->
<!--[if !IE]><!-->
<html lang="{{ config('app.locale') }}">
    <!--<![endif]-->
    <!-- BEGIN HEAD -->

<head>
    <meta charset="utf-8" />
    <title>{{ App\Setting::get_setting('site_title') }}</title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta content="width=device-width, initial-scale=1" name="viewport" />
    <meta name="csrf-token" content="{{ csrf_token() }}">

    @include('partials.meta')

    <meta name="app-url" content="{{ asset('/') }}">
    <link href="https://fonts.googleapis.com/css?family=Lato" rel="stylesheet">
    <meta name="fonts" content="{{ str_replace([' ', ','], ['+','|'], App\Setting::get_setting('fonts')) }}">

    <!-- BEGIN GLOBAL MANDATORY STYLES -->
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&subset=all" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/global/plugins/font-awesome/css/font-awesome.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/global/plugins/simple-line-icons/simple-line-icons.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/global/css/glyphicons.css') }}" rel="stylesheet" type="text/css" />

    <link href="{{ asset('assets/global/plugins/bootstrap/css/bootstrap.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/global/plugins/bootstrap-switch/css/bootstrap-switch.min.css') }}" rel="stylesheet" type="text/css" />
    <!-- END GLOBAL MANDATORY STYLES -->
    <!-- BEGIN THEME GLOBAL STYLES -->
    <link href="{{ asset('assets/global/css/components-md.min.css') }}" rel="stylesheet" id="style_components" type="text/css" />
    <link href="{{ asset('assets/global/css/plugins-md.min.css') }}" rel="stylesheet" type="text/css" />
    <!-- END THEME GLOBAL STYLES -->
    <!-- BEGIN THEME LAYOUT STYLES -->
    <link href="{{ asset('assets/layouts/layout/css/layout.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/layouts/layout/css/themes/darkblue.min.css') }}" rel="stylesheet" type="text/css" id="style_color" />
    <link href="{{ asset('assets/layouts/layout/css/custom.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/global/plugins/bootstrap-colorpicker/css/colorpicker.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/global/plugins/jquery-minicolors/jquery.minicolors.css') }}" rel="stylesheet" type="text/css" />
    <!-- FAVICON -->
    <link rel="apple-touch-icon" sizes="32x32" href="{{ asset('assets/uploads/ico.png') }}">
    <link rel="icon" type="image/png" href="{{ asset('assets/uploads/ico.png') }} " sizes="32x32">

    <link rel="manifest" href="images/favicons/manifest.json">
    <link rel="mask-icon" href="images/favicons/safari-pinned-tab.svg">
    <meta name="msapplication-TileColor">
    <meta name="theme-color">

    <link href='https://fonts.googleapis.com/css?family={{ str_replace([' ', ','], ['+','|'], App\Setting::get_setting('fonts')) }}' rel='stylesheet' type='text/css'>

    
    
    <!-- CSS Start -->
    <link rel="stylesheet" type="text/css" href="{{ asset('designer-tool/css/font-awesome.min.css') }}" >
    <link rel="stylesheet" type="text/css" href="{{ asset('designer-tool/css/normalize.css') }}" >
    <link rel="stylesheet" type="text/css" href="{{ asset('designer-tool/css/bootstrap.min.css') }}" >
    <link rel="stylesheet" type="text/css" href="{{ asset('designer-tool/css/ng-scrollbar.min.css') }}" >
    <link rel="stylesheet" type="text/css" href="{{ asset('designer-tool/css/style.css') }}" >
    <link rel="stylesheet" type="text/css" href="{{ asset('designer-tool/css/custom.css') }}" >
    <link rel="stylesheet" type="text/css" href="{{ asset('designer-tool/css/fonts.css') }}" >
    <link rel="stylesheet" type="text/css" href="{{ asset('designer-tool/css/bootstrap-colorpicker.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('designer-tool/css/angular-material.css') }}"> 
    <!-- CSS End -->

    <!-- CUSTOM STYLES -->
    <link href="{{ asset('css/designer-studio.css') }}" rel="stylesheet" type="text/css" />


    @yield('style')

    <body class="page-sidebar-closed-hide-logo page-content-white page-full-width page-md">
        <div class="page-wrapper">
            <!-- BEGIN HEADER -->
            <div class="page-header navbar navbar-static-top">
                <!-- BEGIN HEADER INNER -->
                <div class="page-header-inner ">
                    <!-- BEGIN LOGO -->
                    <div class="page-logo">
                        <a href="{{ url('/') }}">
                            <img src="{{ has_image(App\Setting::get_setting('logo')) }}" alt="logo" class="logo-default" /> </a>
                    </div>
                    <!-- END LOGO -->

                    <!-- BEGIN TOP NAVIGATION MENU -->
                    <div class="top-menu">
                        <ul class="nav navbar-nav pull-right">
                            <!-- BEGIN INBOX DROPDOWN -->
                            <?php $carts = Session::get('cart'); ?>
                            <li class="dropdown dropdown-extended dropdown-inbox mini-cart" id="header_inbox_bar">
                                <a href="javascript:;" class="dropdown-toggle">
                                    <i class="fa fa-shopping-cart"></i>
                                    <span class="badge badge-danger cart-total"> {{ $carts ? $carts['quantity'] : 0 }} </span>
                                </a>
                                <ul class="dropdown-menu">
                                    @if( $carts )
                                    <li class="external">
                                        <h3>You have <span class="bold cart-total">{{ $carts['quantity'] }} items</span> in your cart</h3>
                                        <a href="{{ route('frontend.checkout') }}">view all</a>
                                    </li>
                                    <li>
                                        <ul class="dropdown-menu-list" style="height: 275px;" data-handle-color="#637283">
                                        @foreach($carts['orders'] as $cart)
                                        <li>
                                            <!--<a href="{{ route('frontend.designer.index', ['reload' => @$cart['token']]) }}">-->
                                            <a href="{{ route('frontend.checkout') }}">
                                                <span class="photo"><img src="{{ has_image($cart['image']) }}" alt=""> </span>
                                                <span class="subject">
                                                    <span class="from"> {{ $cart['name'] }} </span>
                                                </span>
                                                <span class="message"> {{ $cart['quantity'] }} x {{ amount_formatted($cart['unit_price']) }} </span>
                                                    <div class="text-right sbold">{{ amount_formatted($cart['total_price']) }} </div>

                                            </a>
                                        </li>
                                        @endforeach
                                        </ul>
                                    </li>
                                    @else
                                    <li class="external text-center">Your cart is empty!</li>
                                    @endif
                                <li><a href="{{ route('frontend.checkout') }}" class="btn-checkout">Checkout</a></li>
                                </ul>
                            </li>
                            <!-- END INBOX DROPDOWN -->


                            <!-- DOC: Apply "dropdown-dark" class after below "dropdown-extended" to change the dropdown styte -->
                            <li class="dropdown dropdown-user">
                                <a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-close-others="true">
                                    <i class="fa fa-user"></i>
                                    <i class="fa fa-angle-down"></i>
                                </a>
                                <ul class="dropdown-menu dropdown-menu-default">

                                @if( Auth::check() )
                                    <li><a href="{{ route('frontend.account.profile') }}"><i class="icon-user"></i> My Profile</a></li> 
                                    <li><a href="{{ route('frontend.account.change-password') }}"><i class="icon-lock-open"></i> Change Password</a></li> 
                                    <li><a href="{{ route('frontend.account.logout') }}"><i class="icon-logout"></i> Log Out</a></li> 
                                @else
                                    <li><a href="{{ route('frontend.account.login') }}"><i class="icon-login"></i> Log In</a></li> 
                                    <li><a href="{{ route('frontend.account.register') }}"><i class="icon-user-follow"></i> Register</a></li>
                                    <li><a href="{{ route('frontend.account.forgot-password') }}"><i class="icon-login"></i> Forgot Password</a></li>
                                @endif

                                </ul>
                            </li>
                            <!-- END USER LOGIN DROPDOWN -->
                        </ul>
                    </div>
                    <!-- END TOP NAVIGATION MENU -->
                </div>
                <!-- END HEADER INNER -->
            </div>
            <!-- END HEADER -->
            <!-- BEGIN HEADER & CONTENT DIVIDER -->
            <div class="clearfix"> </div>
            <!-- END HEADER & CONTENT DIVIDER -->
            <!-- BEGIN CONTAINER -->
            <div class="page-container">
                <!-- BEGIN SIDEBAR -->


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
            <div class="page-footer">
                <div class="page-footer-inner margin-top-20 margin-bottom-20"> {{ date('Y') }} Teevision Printing LLC, All right reserved</div>
                <div class="scroll-to-top">
                    <i class="icon-arrow-up"></i>
                </div>
            </div>
            <!-- END FOOTER -->
        </div>

        <!--[if lt IE 9]>
        <script src="{{ asset('assets/global/plugins/respond.min.js') }}"></script>
        <script src="{{ asset('assets/global/plugins/excanvas.min.js') }}"></script> 
        <script src="{{ asset('assets/global/plugins/ie8.fix.min.js') }}"></script> 
        <![endif]-->
        <!-- BEGIN CORE PLUGINS -->
        <script src="{{ asset('assets/global/plugins/jquery.min.js') }}" type="text/javascript"></script>
        <script src="{{ asset('assets/global/plugins/bootstrap/js/bootstrap.min.js') }}" type="text/javascript"></script>
        <script src="{{ asset('assets/global/plugins/js.cookie.min.js') }}" type="text/javascript"></script>
        <script src="{{ asset('assets/global/plugins/jquery-slimscroll/jquery.slimscroll.min.js') }}" type="text/javascript"></script>
        <script src="{{ asset('assets/global/plugins/jquery.blockui.min.js') }}" type="text/javascript"></script>
        <script src="{{ asset('assets/global/plugins/bootstrap-switch/js/bootstrap-switch.min.js') }}" type="text/javascript"></script>
        <!-- END CORE PLUGINS -->
        <!-- BEGIN THEME GLOBAL SCRIPTS -->
        <script src="{{ asset('assets/global/scripts/app.min.js') }}" type="text/javascript"></script>
        <!-- END THEME GLOBAL SCRIPTS -->
        <!-- BEGIN THEME LAYOUT SCRIPTS -->
        <script src="{{ asset('assets/layouts/layout/scripts/layout.min.js') }}" type="text/javascript"></script>
        <script src="{{ asset('assets/layouts/layout/scripts/demo.min.js') }}" type="text/javascript"></script>
        <script src="{{ asset('assets/layouts/global/scripts/quick-sidebar.min.js') }}" type="text/javascript"></script>
        <script src="{{ asset('assets/layouts/global/scripts/quick-nav.min.js') }}" type="text/javascript"></script>
        <!-- END THEME LAYOUT SCRIPTS -->

        <link href="{{ asset('assets/global/plugins/select2/css/select2.min.css') }}" rel="stylesheet" type="text/css" />
        <link href="{{ asset('assets/global/plugins/select2/css/select2-bootstrap.min.css') }}" rel="stylesheet" type="text/css" />
        <script src="{{ asset('assets/global/plugins/bootstrap-colorpicker/js/bootstrap-colorpicker.js') }}" type="text/javascript"></script>
        <script src="{{ asset('assets/global/plugins/jquery-minicolors/jquery.minicolors.min.js') }}" type="text/javascript"></script>
        <script src="{{ asset('assets/global/plugins/select2/js/select2.full.min.js') }}" type="text/javascript"></script>

        @yield('plugin_script')

        @yield('script')

        <script>
            $('.select2').select2({'width': '100%'});


            $('.colorpickr').each(function() {
                $(this).minicolors({
                    change: function(hex, opacity) {
                    },
                    theme: 'bootstrap',
                    position: 'top left',
                });
            });

        </script>

    </body>

</html>