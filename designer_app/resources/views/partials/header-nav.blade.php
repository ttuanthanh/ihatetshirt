<div class="page-header navbar navbar-fixed-top">
    <!-- BEGIN HEADER INNER -->
    <div class="page-header-inner ">
        
        <!-- BEGIN LOGO -->
        <div class="page-logo">

            @if( $logo = App\Setting::get_setting('logo') )
            <a href=""><img src="{{ has_image($logo) }}" alt="logo" class="logo-default" 
            style="height:30px;margin:10px 0 0 0;" /></a>
            @endif

            <div class="menu-toggler sidebar-toggler">
                <span></span>
            </div>
        </div>
        <!-- END LOGO -->

        <!-- BEGIN RESPONSIVE MENU TOGGLER -->
        <a href="javascript:;" class="menu-toggler responsive-toggler" data-toggle="collapse" data-target=".navbar-collapse">
            <span></span>
        </a>
        <!-- END RESPONSIVE MENU TOGGLER -->

        <!-- BEGIN TOP NAVIGATION MENU -->
        @include('partials.top-nav-menu')   
        <!-- END TOP NAVIGATION MENU -->
    </div>
    <!-- END HEADER INNER -->
</div>