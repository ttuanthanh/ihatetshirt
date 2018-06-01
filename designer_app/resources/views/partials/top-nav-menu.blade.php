<?php $auth = Auth::User(); ?>

<div class="top-menu">
    <ul class="nav navbar-nav pull-right">

        <!-- BEGIN USER LOGIN DROPDOWN -->
        <li class="dropdown dropdown-user">
            <a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-close-others="true">
            <img alt="" class="img-circle" src="{{ has_photo($auth->get_meta($auth->id, 'profile_picture')) }}" width="30" />
                <span class="username username-hide-on-mobile"> Waszup </span>
                <i class="fa fa-angle-down"></i>
            </a>
            <ul class="dropdown-menu dropdown-menu-default">
                @foreach(top_nav_menu() as $menu)
                <li class="nav-item">
                    <a href="{{ $menu['url'] }}" class="nav-link nav-toggle">
                        <i class="icon-{{ $menu['icon'] }}"></i>
                        <span class="title">{{ $menu['title'] }}</span>
                         <span class="selected"></span>
                         @if( $menu['sub_menu'] )
                         <span class="arrow"></span>
                        @endif
                    </a>

                    @if( $menu['sub_menu'] )
                    <ul class="sub-menu">
                        @foreach($menu['sub_menu'] as $sub_menu)
                        <li class="nav-item">
                            <a href="{{ $sub_menu['url'] }}" class="nav-link ">
                                <span class="title">{{ $sub_menu['title'] }}</span>
                                <span class="selected"></span>
                            </a>
                        </li>
                        @endforeach
                    </ul>
                    @endif

                </li>
                @endforeach

                <li class="divider"></li>

                <li>
                    <a href="{{ URL::route('auth.logout') }}">
                        <i class="icon-logout"></i> Log Out </a>
                </li>
            </ul>
        </li>
        <!-- END USER LOGIN DROPDOWN -->

    </ul>
</div>