@extends('templates.fullwidth')

@section('content')

<div class="section featured-designs">
<div class="container">
<div class="row">
	<div class="col-md-3">


		<div class="category-list"> 

			<div class="sidebar">
			    <div class="widget">
			        <h3 class="widget__title">Categories</h3>
						{!! link_ordered_menu($categories, 0, $category) !!}		
			    </div>
			</div>

		</div>
	</div>
	<div class="col-md-9">
			<div class="row">
				<div class="col-md-6 col-sm-6 col-xs-6">
	
			        <h3 class="widget__title">{{ $category ? ucwords(@$cat->post_title) : 'All Categories' }} 
			        	<small> - {{ $count = count($rows) }} Item{{ is_plural($count) }}</small>
			        </h3>					
				</div>
				<div class="col-md-6 col-sm-6 col-xs-6 text-right">
					<div class="row">
						<div class="col-md-6">Sort by :</div>
						<div class="col-md-6">

							<form method="get" action="{{ route('frontend.products') }}">
						{{ Form::select('sort', [
							'post_name-asc'   => 'Name: Ascending',
							'post_name-desc'  => 'Name: Descending', 
							'created_at-desc' => 'Date: Oldest',
							'created_at-asc'  => 'Date: Newest', 
							'price-asc' 	  => 'Price: Cheapest',
							'price-desc' 	  => 'Price: Most Expensive'
							], Input::get('sort'), ['class' => 'form-control form-control-sm sort']) }}							

							</form>

						</div>
					</div>



				</div>
			</div>

		<div class="row">
		@foreach($rows as $row)
		<?php $postmeta = get_meta( $row->postMetas()->get() ); ?>
		<div class="col-lg-3 col-md-6 col-sm-6">
			<a href="{{ route('frontend.product', $row->post_name) }}">
			<figure class="figure">
			  <img src="{{ has_image($postmeta->image) }}" class="figure-img img-fluid rounded" alt="{{ $row->post_title }}">
			  <div class="product-title">{{ str_limit($row->post_title, 60) }}</div>
			</figure>
			</a>
		</div>
		@endforeach
		</div>

		@if( ! count($rows) )
			<div class="alert alert-info">No products found!</div>
		@endif



		{{ $rows->appends(Input::all())->links() }}

	</div>
</div>
</div>
</div>

@endsection

@section('style')
<style>
.ordered-menu:first-child {
	padding: 0 0 0 15px;
}
.ordered-menu a.active {
	color: #014c8c;
	text-decoration: underline;
}
.ordered-menu li:last-child {
	border: 0;
}
.ordered-menu li {
	list-style: none;
}
.ordered-menu .ordered-menu li {
	border-bottom: 0;
	padding: 5px 0;
}
.ordered-menu .ordered-menu {
    padding: 0 20px;
}
.category-list {
	background: #fff;
    padding: 15px 20px 5px;
    margin-bottom: 20px;
	box-shadow: 0 2px 4px 0 rgba(0,0,0,.25);
}
.figure { 
	background-color: #fff; 
    padding: 15px 15px 10px;
}
.figure:hover {
    box-shadow: 0 2px 4px 0 rgba(0,0,0,.25);	
}	
.product-title {
	height: 60px;
    font-size: .8em;
    line-height: 18px;
    color: #343434;
    text-align: center;
}
</style>
@stop

@section('plugin_script') 
@stop

@section('script')
<script>
$(document).on('change', '.sort', function(){
    $(this).closest('form').submit();
});	
</script>
@stop
