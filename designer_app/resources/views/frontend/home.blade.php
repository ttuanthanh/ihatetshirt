@extends('templates.fullwidth')

@section('content')

<div class="fold">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <h1>Customized<br>Shirt Designs<br><strong>Fast &amp; Easy<br>Printing</strong></h1>
                    <h4>Call Us Now</h4>
                    <p>{{ App\Setting::get_setting('telephone') }}</p>
                    <div class="btn-group">
                        <a href="{{ route('frontend.designer.index') }}" class="btn btn--sh">start here</a> 
                        <a href="{{ route('frontend.designer.index') }}" class="btn btn--raq">request a quote</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="section preferred-option">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <h2>Select Your Prefered Option</h2>

                    <div class="preferred-option__banners">
                        <div class="card-image"><a href="{{ route('frontend.products') }}"><img src="{{  asset('frontend/images/home-pricing-1.png') }}"></a></div>
                        <div class="card-blue" style="background-image:url({{  asset('frontend/images/home-pricing-2.png') }})">
                            <h3>low price</h3>
                            <h2>$5-10<span>/pc</span></h2>
                            <h4>20 pcs above</h4>
                            <a href="{{ route('frontend.products', 'low-price') }}" class="card-btn">inquire now</a>
                        </div>
                        <div class="card-green" style="background-image:url({{  asset('frontend/images/home-pricing-3.png') }})">
                            <h3>mid price</h3>
                            <h2>$7-12<span>/pc</span></h2>
                            <h4>15 pcs above</h4>
                            <a href="{{ route('frontend.products', 'mid-price') }}" class="card-btn">inquire now</a>
                        </div>
                        <div class="card-yellow" style="background-image:url({{  asset('frontend/images/home-pricing-4.png') }}">
                            <h3>best price</h3>
                            <h2>$8-15<span>/pc</span></h2>
                            <h4>10 pcs above</h4>
                            <a href="{{ route('frontend.products', 'best-price') }}" class="card-btn">inquire now</a>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
    <div class="section need-shirt">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-6 col-md-12">
                    <div id="carousel-ns" class="owl-carousel carousel carousel--home">
                        <div>
                            <div class="carousel__item" style="background-image:url({{ asset('frontend/images/carousel.jpg') }})"><img src="{{ asset('frontend/images/carousel.jpg') }}"></div>
                        </div>
                        <div>
                            <div class="carousel__item" style="background-image:url({{ asset('frontend/images/carousel.jpg') }})"><img src="{{ asset('frontend/images/carousel.jpg') }}"></div>
                        </div>
                        <div>
                            <div class="carousel__item" style="background-image:url({{ asset('frontend/images/carousel.jpg') }}"><img src="{{ asset('frontend/images/carousel.jpg') }}"></div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 col-md-12">
                    <div class="teaser">
                        <h2>Need custom T-shirts?</h2>
                        <p>Welcome to Tee Vision Printing, a trusted source of custom T-shirts in Philadelphia and beyond.</p>
                        <p>Everyone loves wearing tees, as they fit snugly and are extremely comfortable to wear. But, most tees we find in shops are either too plain or have generic designs. At Tee Vision Printing, we provide high-quality screen printing at an affordable price. Using our Design Studio, you can create your own designs on a range of shirt styles, sizes, and colors.</p>
                        <a href="#" class="btn">read more</a>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class="section featured-designs">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <h2><strong>Featured Customer</strong> Shirt Designs</h2>
                    <div class="products">

                        @foreach($features as $feature)
                        <?php $content = json_decode($feature->post_content); ?>
                        <div class="product">
                            <figure><img src="{{ asset(@$content->image) }}"></figure>
                        </div>
                        @endforeach

                    </div>
                    <a href="#" class="btn btn--lm" style="display:none;">load more</a>
                </div>
            </div>
        </div>
    </div>


    <div class="section design-shirt">
        <div>
            <h1>Design your own shirt.</h1>
            <p>Start creating your customized shirt using our <span>design studio</span>.</p>
            <a href="{{ route('frontend.designer.index') }}" class="btn btn--sh">start here</a>
        </div>
    </div>

    @include('partials.frontend.subscribe')

@endsection

@section('style')
<style>
    .product { background: #fff; }
</style>
@stop

@section('plugin_script') 
@stop

@section('script')
@stop
