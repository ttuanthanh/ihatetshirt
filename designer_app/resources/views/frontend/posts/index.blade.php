@extends('templates.fullwidth')

@section('content')

<div class="content bg-white">
	<div class="container">

	@foreach($rows as $row)
	<?php $postmeta = get_meta( $row->postMetas()->get() ); ?>

		<div class="row margin-top-20">
			<div class="col-md-2">
				<img src="{{ str_replace('large', 'thumb', $postmeta->image) }}" class="img-thumbnail fullwidth">
			</div>
			<div class="col-md-10">	
				<h4 class="sbold"><a href="{{ route('frontend.post', [$category, $row->post_name]) }}">{{ $row->post_title }}</a></h4>

				@if( $postmeta->excerpt )
				<p class="text-justify">{{ str_limit($postmeta->excerpt, 400) }}</p>		
				<a href="{{ URL::route('frontend.post', [$category, $row->post_name]) }}" class="btn">Read More ...</a>
				@endif

			</div>
		</div>
	@endforeach	
		
	</div>
</div>

@endsection

@section('style')
@stop

@section('plugin_script') 
@stop

@section('script')
@stop
