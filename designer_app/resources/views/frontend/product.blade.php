@extends('templates.default')

@section('content')

<form action="{{ route('frontend.get-quote') }}" id="form-product" method="post">
{{ csrf_field() }}
<input type="hidden" name="pid" value="{{ $info->id }}">
<input type="hidden" name="type" value="">
<input type="hidden" name="image" value="">
<input type="hidden" name="color_index" value="">
<input type="hidden" name="color_hex" value="">
<input type="hidden" name="color_title" value="">

<div class="row" id="single-product">
	<div class="col-md-5">

	<h4>{{ $info->post_title }}</h4>

	<div class="row m-t-20">

		<div class="col-md-12">
			<h6>
		@if( $info->starting_price )

				Starting at <strong>{{ amount_formatted($info->starting_price) }}</strong>
	@endif

			</h6>			
		</div>
	


	</div>

	
	<div class="img-frame m-t-20">
		<img src="{{ has_image($info->image) }}" class="img-product">					
	</div>

	</div>
	<div class="col-md-7">



@if( $info->product_design )
<h6>Preview available colors : <b class="color-title"></b> </h6>

@if(  $colors = json_decode($info->product_design, true) )
	<p>{{ count($colors) }} Color{{ is_plural($colors) }}</p>
@endif


<div class="color-swatches m-t-20">
@foreach( json_decode($info->product_design) as $c_k => $color )

	<a href="javascript:void(0);" class="color-swatch" 
	data-toggle="tooltip" 
	data-index="{{ $c_k }}"
	data-placement="top" 
	style="background-color: {{ $color->color }};" 
	data-type="{{ $color->color == '#ffffff' ? 0 : 1 }}"
	data-hex="{{ $color->color }}"
	data-src="{{ $color->image[0]->url }}" 
	data-image="{{ has_image($color->image[0]->url) }}" 	
	data-title="{{ $color->color_title }}"></a>
@endforeach	
</div>
@else
<p class="alert alert-info">No available colors.</p>
@endif

<hr>

@if($info->size)
<h6>Enter Sizes to calculate your price : <small>( Minimum order is 6 pieces )</small></h6>

<div class="row m-t-20">
	@foreach(json_decode($info->size) as $s_k => $size)
	<div class="col-lg-2 col-md-3 col-sm-3 col-xs-6">
		<div class="form-group">
		{{ $size->name }}
		<input type="number" name="size[]" class="form-control numeric" 
		onchange="get_quote();" 
		onkeyup="get_quote();"
		min="0" 
		value="{{ $s_k==0 ? 6 : '' }}">		
			
		</div>
	</div>
	@endforeach	
</div>
<p class="msg-quantity text-danger error-msg"></p>
@else
<p class="alert alert-info">No available sizes.</p>
@endif

<hr>

<h6>Decoration : </h6>
<div class="row m-t-20">
	<div class="col-md-6">
		<label>Front Colors:</label>
		{{ Form::select('front_color', shirt_colors(), '', ['class' => 'form-control', 'onchange' => 'get_quote();']) }}		
	</div>
	<div class="col-md-6">
		<label>Back Colors:</label>
		{{ Form::select('back_color', shirt_colors(), '', ['class' => 'form-control', 'onchange' => 'get_quote();']) }}		
	</div>
</div>



<hr>

<div class="row">
	<div class="col-md-5">
		<div class="row">
			<div class="col-md-6">
				<label>Unit price:</label><h5> <span class="unit-price">{{ amount_formatted(0) }}</span></h5>
			</div>
			<div class="col-md-6">
				<label>Total price:</label><h5> <span class="total-price">{{ amount_formatted(0) }}</span></h5>
			</div>
		</div>
		
		
	</div>
	<div class="col-md-7">
		<small><p>Free Shipping by <strong>Tue, Mar 27</strong> Guaranteed<br>
		Need it sooner? Upgrade to rush shipping at Check out.</p></small>		

		<button type="submit" class="btn btn-primary btn-lg btn-block btn-start">Start Design Here</button>
	</div>
</div>






<ul class="nav nav-tabs" id="pills-tab" role="tablist">
  <li class="nav-item">
    <a class="nav-link active" id="pills-home-tab" data-toggle="pill" href="#pills-home" role="tab" aria-controls="pills-home" aria-selected="true">Product Details</a>
  </li>
  <li class="nav-item">
    <a class="nav-link" id="pills-profile-tab" data-toggle="pill" href="#pills-profile" role="tab" aria-controls="pills-profile" aria-selected="false">Size Chart</a>
  </li>
</ul>
<div class="tab-content" id="pills-tabContent">
  <div class="tab-pane fade show active" id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab">
	{!! $info->post_content !!}
  </div>
  <div class="tab-pane fade" id="pills-profile" role="tabpanel" aria-labelledby="pills-profile-tab">
	{!! $info->product_size_info !!}
  </div>
</div>



	</div>

</div>
</form>





@endsection

@section('style')
<style>
#single-product {
	color: #4f4f4f;
    font-family: Poppins,sans-serif;
}
.color-swatches a.color-swatch {
    border: 2px solid #fff;
    cursor: pointer;
    display: inline-block;
    height: 30px;
    margin: 4px;
    outline: 1px solid #ccc;
    padding: 2px;
    width: 30px;
}	
#pills-tab {	
    margin-top: 35px;
}
.tab-content>.tab-pane {
    border: 1px solid #d4d4d4;
    border-top: 0;
    padding: 20px 15px 10px;
}
.m-t-20 {
    margin-top: 20px;	
}
.color-swatch.actived {
    border-radius: 20px;
    box-shadow: 0px 0px 10px 0 #000;
    outline: 0 !important;
}
.img-frame {
	display:inline-block;
	border: 1px solid #ddd;	
	cursor: zoom-in;
	box-shadow: 0px 0px 10px 0 #00000091;
}
</style>

@stop

@section('plugin_script') 

@stop

@section('script')


<script src="{{ asset('assets/plugins/multizoom/jquery.zoom.min.js') }}"></script>
<script>
$(document).on('click', '.color-swatch', function() {
	var src = $(this).data('src'),
		image = $(this).data('image'), 
		title = $(this).data('title'), 
		hex = $(this).data('hex'), 
		type = $(this).data('type'), 
		hex = $(this).data('hex'), 
		title = $(this).data('title'), 
		index = $(this).data('index');

	$('.color-swatch').removeClass('actived');
	$(this).addClass('actived');

	$('.img-product').attr('src', image);
	$('.color-title').html(title);
	$('[name="type"]').val(type);
	$('[name="image"]').val(src);
	$('[name="color_index"]').val(index);
	$('[name="color_hex"]').val(hex);
	$('[name="color_title"]').val(title);

	$('.img-product')
		.parent()
		.zoom({'magnify':1.2, 'touch':true, 'url' : image});	

	get_quote();
});

$('.img-product')
	.parent()
	.zoom({'magnify':1.2, 'touch':true});	


function get_quote() {
    formElement = document.querySelector("#form-product");
    $.ajax({
        url: $('#form-product').attr('action'), 
        type: "POST",           
        data: new FormData( formElement ), 
        contentType: false,
        cache: false,             
        processData:false,     
        success: function(data) 
        { 
        	val = JSON.parse(data);
			$.each( val.msg, function( key, value ) {
			  $('.msg-'+key).html(value);
			});   

        	$('.unit-price').html(val.unit_price);
        	$('.total-price').html(val.total_price);

        }
    });
}

$('.color-swatch:first-child').click();

</script>
@stop
