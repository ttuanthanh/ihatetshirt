<footer class="footer">
    <div class="container">
        <div class="row">
            <div class="col-md-6 col-lg-3">
                <h3>contact us</h3>
                <ul class="list-contact">
                    @if( $telephone  = App\Setting::get_setting('telephone') )
                    <li class="tel"><span>{{ $telephone }}</span></li>
                    @endif

                    @if( $email = App\Setting::get_setting('admin_email') )
                    <li class="email"><a href="mailto:{{ $email }}">{{ $email }}</a></li>
                    @endif

                    @if( $address = App\Setting::get_setting('address') )                    
                    <li class="address">{!! App\Setting::get_setting('address') !!}</li>
                    @endif
                </ul>
            </div>
            <div class="col-md-6 col-lg-3 d-connect-one">
                <h3>connect</h3>
                <ul class="list-social-icons">
                    <li><a href="#" class="ico-fb"><i class="fab fa-facebook-square"></i></a></li>
                    <li><a href="#" class="ico-yt"><i class="fab fa-youtube"></i></a></li>
                    <li><a href="#" class="ico-ig"><i class="fab fa-instagram"></i></a></li>
                    <li><a href="#" class="ico-gp"><i class="fab fa-google-plus-square"></i></a></li>
                </ul>
                <img src="{{ asset('frontend/images/logo-footer.png') }}">
            </div>
            <div class="col-md-6 col-lg-3">
                <h3>navigate</h3>
                <ul>
                    <li><a href="{{ route('frontend.home') }}">Home</a></li>
                    <li><a href="{{ route('frontend.post', ['services']) }}">Our services</a></li>
                    <li><a href="{{ route('frontend.post', ['organizations']) }}">Organizations</a></li>
                    <li><a href="{{ route('frontend.post', ['blog']) }}">Blog</a></li>
                </ul>
            </div>
            <div class="col-md-6 col-lg-3">
                <h3 class="d-none d-md-block">&nbsp;</h3>
                <ul>
                    <li><a href="#">Request A Quote</a></li>
                    <li><a href="{{ route('frontend.designer.index') }}">Custom T-shirts</a></li>
                    <li><a href="{{ route('frontend.post', ['site-map']) }}">Site Map</a></li>
                </ul>
            </div>
            <div class="col-md-6 col-lg-3 d-connect-two">
                <h3>connect</h3>
                <ul class="list-social-icons">
                    <li><a href="https://www.facebook.com/teevisionprinting" class="ico-fb"><i class="fab fa-facebook-square"></i></a></li>
                    <li><a href="#" class="ico-yt"><i class="fab fa-youtube"></i></a></li>
                    <li><a href="https://www.instagram.com/teevisionprinting" class="ico-ig"><i class="fab fa-instagram"></i></a></li>
                    <li><a href="#" class="ico-gp"><i class="fab fa-google-plus-square"></i></a></li>
                </ul>
                <img src="{{ asset(App\Setting::get_setting('logo')) }}" style="opacity: 0.5;">
            </div>
        </div>
    </div>
    <div class="container-fluid footer--bottom">
        <div class="row">
            <div class="col-md-12">
                <nav class="menu">
                    <ul>
                        <li>{{ App\Setting::get_setting('copy_right') }}</li>
                        <li><a href="{{ route('frontend.post', ['user-agreement']) }}">User Agreement</a></li>
                        <li><a href="{{ route('frontend.post', ['privacy-policy']) }}">Privacy Policy</a></li>
                        <li><a href="{{ route('frontend.post', ['copyright']) }}">Copyright</a></li>
                        <li><a href="{{ route('frontend.post', ['terms-and-condition']) }}">Terms &amp; Conditions</a></li>
                        <li>
                            @if( Auth::check() )
                            <a href="{{ route('frontend.account.logout') }}">Log Out</a>
                            @else
                            <a href="{{ route('frontend.account.login') }}">Log In</a>
                            @endif
                        </li>
                    </ul>
                </nav>
            </div>
        </div>
    </div>
</footer>
<script src="{{ asset('frontend/scripts/vendor.js') }}"></script>
<script src="{{ asset('frontend/scripts/main.js') }}"></script>

@yield('plugin_script')     

@yield('script')    

<script>
$(document).on('click', '.mini-cart .dropdown-toggle', function(e) {
    $('.mini-cart .dropdown-menu').slideToggle();
});

$('#form-subscribe').on('submit', function(e) {
    e.preventDefault();
    $('.msg-email').removeClass('text-success text-danger');
    formElement = document.querySelector("#form-subscribe");
    $.ajax({
        url: $('#form-subscribe').attr('action'), 
        type: "POST",           
        data: new FormData( formElement ), 
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        contentType: false,
        cache: false,             
        processData:false,     
        success: function(data) 
        { 
            val = JSON.parse(data);
            console.log(val.msg.email);
            if(val.error) {
                $.each(val.msg, function(k, v){
                    $('.msg-'+k).html(v).addClass('text-danger');
                });
            } else {
                $('.msg-email').html(val.msg).addClass('text-success');            
            }

        }
    });

});

</script>