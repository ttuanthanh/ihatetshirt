<div style="padding:10px;">
	<a href="" class="pull-right btn btn-change-product"><i class="fa fa-angle-left"></i> Change Product</a>
	<h5 class="sbold">Choose Product Color</h5>

	<ul class="colors">
		<?php $c=1; ?>
	    @foreach($colors as $color)
	    <li><span class="bg-color tooltips" 
	    	title="{{ $color['color_title'] }}"
		    data-chosen-color="{{ $color['color_title'] }}"
		    style="background-color: {{ $color['color'] }};" 
	     	onclick="angular.element('#productApp').scope().loadProduct('{{ str_replace(['"', "'"], '', $product->post_title) }}', '{{ has_image($color['image'][0]['url']) }}', {{ $product->id }}, 0, 'USD', null, '{{ $color['image'][0]['attr'] }}', {{ $c }});"></span>
	 	</li>
	 	<?php $c++; ?>
	    @endforeach
	</ul>


	<hr>
	<h4>{{ $product->post_title }}</h4>
	<h5 class="chosen-color sbold"></h5>
	<p>{!! @$product->get_meta($product->id, 'excerpt') !!}</p>

</div>

