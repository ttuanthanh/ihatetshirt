@extends('layouts.admin')

@section('content')
<!-- BEGIN PAGE TITLE-->
<h1 class="page-title"> {{ $single }} 
    <a href="{{ URL::route($view.'.index') }}" class="btn pull-right"><i class="fa fa-long-arrow-left"></i> All {{ $label }}</a>
</h1>
<!-- END PAGE TITLE-->

<label class="mt-checkbox mt-checkbox-outline">
    <input type="checkbox" data-id="{{ $info->id }}" class="set-featured" data-url="{{ route('admin.customers.designs.index') }}" {{ checked(@$info->featured, 1) }}>
    <span></span> Set as featured design
</label>      

@foreach( $content->object as $index => $row)

	@if( $row )
	<div class="row">
	<div class="col-md-7">
		<div class="text-center">
		<img src="{{ asset('assets/uploads/designs/'.$info->post_name.'-'.$index.'.png') }}?{{ json_decode($content->png_filetime)[$index] }}" class="fullwidth img-thumbnail">
		
		<p class="sbold">Download design file</p>
		<p>
			<a href="{{ asset('assets/uploads/designs/'.$info->post_name.'-'.$index.'.svg') }}" class="btn" download>SVG</a> 
			<a href="{{ asset('assets/uploads/designs/'.$info->post_name.'-'.$index.'.png') }}" class="btn" download>PNG</a>
		</p>
		</div>

	@if( $index == 1 )
	<hr>
	<h4>Name & Number</h4>
	<table class="table table-striped">
		<thead>
			<tr>
				<th>Name</th>
				<th>Number</th>
				<th>Size</th>
			</tr>
		</thead>
		<tbody>
			@foreach($content->team as $team)
			@if( $team->name || $team->number )
			<tr>
				<td class="text-left">{{ $team->name }}</td>
				<td class="text-left">{{ $team->number }}</td>
				<td class="text-left">{{ $team->size }}</td>
			</tr>
			@endif
			@endforeach
		</tbody>
	</table>
	@endif
	</div>


	<div class="col-md-5"> 

		@foreach($row->objects as $obj)
			@if( @$obj->type != 'rect' )

				<div class="portlet light bordered">
				<table class="table table-bordered">	
				<tr>
					@if( @$obj->type == 'text' )
					<td><h4 class="sbold">Add Text</h4></td>
					<td><h4>{{ @$obj->text }}</h4></td>
					@endif

					@if( @$obj->type == 'path-group' )
					<td><h4 class="sbold">Add Art</h4></td>
					<td><h4>{{ @$obj->text }}</h4></td>
					@endif
				</tr>
				@foreach($obj as $k => $v)
					@if( !in_array($k, ['originX', 'originY', 'strokeDashArray', 'strokeLineCap', 'strokeLineJoin', 'strokeMiterLimit', 'scaleX', 'scaleY', 'angle', 'flipX', 'flipY', 'visible', 'smallFont', 'largeFont', 'range', 'globalCompositeOperation', 'fillRule', 'strokeWidth', 'text', 'type', 'textDecoration', 'paths', 'uid', 'inkindex']) && $v)
					<tr>
						<td width="110">{{ $k }}</td>
						<td>
							@if( $k == 'fontFamily' ) 
							<a href="http://www.google.com/fonts/specimen/{{ str_replace(' ', '+', ucwords($obj->fontFamily)) }}" target="_blank" style="font-family: {{ $obj->fontFamily }};font-size:20px;">{{ $obj->fontFamily }}</a>
							@elseif( $k == 'fill' ) 
							<span class="fill" style="background-color: {{ $obj->fill }};"></span> {{ $obj->fill }}
							@elseif( $k == 'src' ) 

								<?php 
								$src = $obj->src;
								if( substr($src, 0, strpos($src, "-paint")) ) {
									$src = substr($src, 0, strpos($src, "-paint")).'.png';
								}
								?>

								<img src="{{ $src }}" class="img-thumbnail fullwidth">
								<a href="{{ $src }}" class="btn btn-block btn-xs margin-top-10" download>Download</a>
							@elseif( $k == 'inkcolors' ) 
								<?php $v = array_unique($v); ?>
								@foreach($v as $color)
								<span class="fill" style="background-color: {{ $color }};"></span> {{ $color }}<br><br>
								@endforeach
							@else
							{{ is_array($v) ? '' : $v }}
							@endif
							</td>
					</tr>
					@endif
				@endforeach
			</table>
			</div>
			
			@endif

		@endforeach


	</div>

</div>

	<div class="borderline"></div>

	@endif
@endforeach

@if( $comment )
<div class="portlet light bordered">
	<h4 class="sbold">Instruction :</h4>
	<p style="white-space: pre-wrap;" class="text-justify">{{ $comment }}</p>
</div>
@endif


@endsection

@section('style')
<link href='https://fonts.googleapis.com/css?family={{ str_replace([' ', ','], ['+','|'], App\Setting::get_setting('fonts')) }}' rel='stylesheet' type='text/css'>
<style>
.fill {
	height: 26px;
	width: 26px;
	margin: -3px 5px -8px -4px;
	display: inline-block;	
    border-radius: 30px;
    border: 1px solid #adadad;
}
.borderline {
    border-top: 1px dashed #9E9E9E;
    width: 100%;
    margin: 0 0 20px 0;	
}	
</style>
@stop

@section('plugin_script') 
@stop

@section('script')
<script>
$(document).on('click', '.set-featured', function(){
    var id = $(this).data('id'),
    url = $(this).data('url'), 
    token = $('[name="csrf-token"]').attr('content');
    $.post(url, { '_token' : token, 'id' : id, val :  $(this).is(':checked') }, function(res){
        console.log( res );
    });
});	
</script>
@stop
