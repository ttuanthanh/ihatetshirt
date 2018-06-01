<!--[if IE]>
<p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
<![endif]-->
<header class="header">
    <div class="container">
        <div class="row">
            <div class="col-2 col-sm-2 col-md-2">
                <div class="logo"><a href="{{ url('/') }}"><img src="{{ has_image(App\Setting::get_setting('logo')) }}"></a></div>
            </div>
            <div class="col-10 col-sm-10 col-md-10 text-right">
                <nav class="menu menu-center d-none d-lg-inline-block">
                    <ul>
                    @foreach(site_header_menu() as $menu)
                    <li>
                        <a href="{{ $menu['url'] }}">
                             {{ $menu['title'] }}
                             @if( $menu['sub_menu'] )
                             <span class="arrow"></span>
                            @endif
                        </a>

                        @if( $menu['sub_menu'] )
                        <ul class="sub-menu">
                            @foreach($menu['sub_menu'] as $sub_menu)
                            <li>
                                <a href="{{ $sub_menu['url'] }}">
                                   {{ $sub_menu['title'] }}
                                </a>
                            </li>
                            @endforeach
                        </ul>
                        @endif

                    </li>
                    @endforeach
                    </ul>
                </nav>
                <nav class="menu menu-right d-lg-inline-block">
                    <ul>
                        <li class="mini-cart">
                             <?php $carts = Session::get('cart'); ?>
                            <a href="#" class="cart-link dropdown-toggle" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><img src="{{ asset('frontend/images/ico-cart.png') }}">
                                @if( @$carts['quantity'] )
                                <span>{{ $carts['quantity'] }}</span>
                                @endif
                            </a>
  
                                <ul class="dropdown-menu">
                                    @if( $carts )
                                    <li class="external">
                                        <h3>You have <b class="cart-total">{{ $carts['quantity'] }} items</b> in your cart</h3>
                                        <a href="{{ route('frontend.checkout') }}">view all</a>
                                    </li>
                                    <li>
                                        <ul class="dropdown-menu-list" style="height: 275px;overflow: auto;">
                                        @foreach($carts['orders'] as $cart)
                                        <li>
                                            <a href="{{ route('frontend.checkout') }}">
                                                <span class="photo"><img src="{{ has_image($cart['image']) }}" alt=""> </span>
                                                <span class="subject">
                                                    <span class="from"> {{ $cart['name'] }} </span>
                                                </span>
                                                <span class="message"> {{ $cart['quantity'] }} x {{ amount_formatted($cart['unit_price']) }} </span>
                                                <span class="total">{{ amount_formatted($cart['total_price']) }} </span>

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
                        <li>
                            <a href="#" class="dropdown-toggle" id="dropdownMenuLink2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><img src="{{ asset('frontend/images/ico-person.png') }}"></a>
                            <div class="dropdown-menu" aria-labelledby="dropdownMenuLink2">
                            @if( Auth::check() )
                                <a class="dropdown-item" href="{{ route('frontend.account.profile') }}">My Profile</a> 
                                <a class="dropdown-item" href="{{ route('frontend.account.change-password') }}">Change Password</a> 
                                <a class="dropdown-item" href="{{ route('frontend.account.logout') }}">Log Out</a> 
                            @else
                                <a class="dropdown-item" href="{{ route('frontend.account.login') }}">Log In</a> 
                                <a class="dropdown-item" href="{{ route('frontend.account.register') }}">Register</a> 
                                <a class="dropdown-item" href="{{ route('frontend.account.forgot-password') }}">Forgot Password</a>
                            @endif
                            </div>
                        </li>
                        <li class="d-lg-none"><a href="#" class="mobile-btn"><i class="fas fa-bars"></i></a></li>
                    </ul>
                </nav>
            </div>
        </div>
    </div>
    <div class="mobile-menu">
        <nav>
            <ul>
            @foreach(site_header_menu() as $menu)
            <li>
                <a href="{{ $menu['url'] }}">
                     {{ $menu['title'] }}
                     @if( $menu['sub_menu'] )
                     <span class="arrow"></span>
                    @endif
                </a>

                @if( $menu['sub_menu'] )
                <ul class="sub-menu">
                    @foreach($menu['sub_menu'] as $sub_menu)
                    <li>
                        <a href="{{ $sub_menu['url'] }}">
                           {{ $sub_menu['title'] }}
                        </a>
                    </li>
                    @endforeach
                </ul>
                @endif

            </li>
            @endforeach
            </ul>
        </nav>
    </div>
</header>


<style>
.mini-cart li {
    padding: 0 !important;
}
.mini-cart .dropdown-menu {
    width: 275px;
    box-shadow: 0 3px 6px rgba(0,0,0,.16),0 3px 6px rgba(0,0,0,.22)!important;
}
.mini-cart .external {
    padding: 13px 15px 8px !important;
    background: #eaedf2;
    width: 100%;
}    
.mini-cart .external h3 {
    float: left;
    color: #62878f;
    font-size: 14px;  
}
.mini-cart .external a {
    float: right;
    color: #62878f;
    font-size: 12px;
    text-transform: initial;
    font-weight: 500;
}
.mini-cart .photo {
    float: left;
    width: 40px;    
}
.mini-cart .dropdown-menu-list li { 
    background: #fff;
}
.mini-cart .dropdown-menu-list a { 
    font-size: 13px !important;
    text-transform: inherit;
    font-weight: 500;
    padding: 16px 15px 18px;
    text-decoration: none;
    border-bottom: 1px solid #e6e6e6;
}
.dropdown-menu-list .total {  
    display: block;
    text-align: right;
}
.dropdown-menu-list .from {  
    font-weight: 600;
    color: #5b9bd1;
    line-height: 1.5;
}    
.dropdown-menu-list .subject {    
    display: block;
    margin-left: 46px;
}
.dropdown-menu-list .message {    
    margin-left: 46px;
    color: #888;
}
.mini-cart .dropdown-menu-list a:hover { 
    background: #f8f9fa;
}    
.mini-cart .dropdown-menu {
    display: none;
    padding-top: 5px;
}
.mini-cart .dropdown-menu:before {
    position: absolute;
    top: -7px;
    right: 9px;
    display: inline-block!important;
    border-right: 7px solid transparent;
    border-bottom: 7px solid #eee;
    border-left: 7px solid transparent;
    border-bottom-color: rgba(0,0,0,.2);
    content: '';
}
.mini-cart ul li:last-child {
    width: 100%;
}
.btn-checkout {
    box-shadow: 0 1px 3px rgba(0,0,0,.1), 0 1px 2px rgba(0,0,0,.18);
    font-size: 12px !important;
    font-weight: 600;
    width: 100%;    
    color: #555 !important;
    text-align: center;
    padding: 8px 14px 7px;
}
</style>