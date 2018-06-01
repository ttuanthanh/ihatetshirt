<?php 
/* 
    Template Name: Services
*/
?>
<!doctype html>
<html class="no-js" lang="{{ config('app.locale') }}">

    @include('partials.frontend.head') 

    <body>

        @include('partials.frontend.header')

		<div class="content">
		    <div class="container">
		        <div class="row">
		            <div class="col-md-3">
		                <div class="sidebar">
		                    <div class="widget">
		                        <h3 class="widget__title">Services List</h3>
		                        <ul>
		                        	@foreach($rows as $row)
		                            <li><a href="{{ route('frontend.post', [$category, $row->post_name]) }}">{{ $row->post_title }}</a></li>
		                            @endforeach
		                        </ul>
		                    </div>
		                </div>
		            </div>
		            <div class="col-md-9">
		                <div class="article">
		                    <h2 class="article__title">{{ $info->post_title }}</h2>               
		                    <div class="article__desc">
		                    	@yield('content')     
		                    </div>
		                </div>
		            </div>
		        </div>
		    </div>
		</div>
	    <?php 
        $product_chunks = App\Post::where('post_type', 'product')
                    ->where('post_status', 'published')	                    
                    ->get()
                    ->chunk(3);
	    ?>
	   <div class="carousel-block">
	    <h2>Select The <strong>Right</strong> Shirt For You</h2>
	    <div id="carousel-rsfy" class="owl-carousel carousel--rsfy">

	    	@foreach($product_chunks as $product_chunk)
		    	@foreach( $product_chunk as $product )
		    	<?php $postmeta = get_meta($product->postMetas()->get()); ?>
		        <div>
		            <div class="carousel-rsfy__item-image">
		            <a href="#"><span><img src="{{ asset($postmeta->image) }}"></span><span><strong>
		            	{{ $product->post_title }}</strong><br>{{ $product->categoryList }}</span>
		            </a>
		            </div>
		        </div>
		        @endforeach
		        <div>
		            <div class="carousel-rsfy__item-card">
		                <img src="{{ asset('frontend/images/product-1.jpg') }}"> 
		                <span>
		                    <span>
		                        <h2>Cant find what you’re looking for?</h2>
		                        <h3>Choose from our hundreds of design and syles.</h3>
		                        <a href="{{ route('frontend.products') }}">Browse Our Catalog</a>
		                    </span>
		                </span>
		            </div>
		        </div>
	        @endforeach

	    </div>
	</div>
	<div class="custom-block custom-block--1">
	    <div class="container">
	        <div class="row">
	            <div class="col-md-12">
	                <h2>Get your <strong><u>creative juice</u></strong> flowing!</h2>
	                <h3>Create your own <strong>custom designed shirt.</strong></h3>
	                <div class="btn-group">
	                	<a href="{{ route('frontend.designer.index') }}" class="btn btn--dn">design now</a> 
	                	<a href="{{ route('frontend.designer.index') }}" class="btn btn--gaq">get a quote</a></div>
	            </div>
	        </div>
	    </div>
	</div>

	@include('partials.frontend.subscribe') 

	@include('partials.frontend.footer') 

	</body>
</html>

