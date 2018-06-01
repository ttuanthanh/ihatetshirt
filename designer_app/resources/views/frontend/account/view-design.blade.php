@extends('templates.default')

@section('content')
<div class="row">
    <div class="col-md-3">
        @include('frontend.account.sidebar')
    </div>
    <div class="col-md-9">
		<div class="row">
		@foreach( $content->object as $index => $row)
			@if( $row )
			<div class="col-md-6">
				<img src="{{ asset('assets/uploads/designs/'.$info->post_name.'-'.$index.'.png') }}" class="fullwidth img-thumbnail">
			</div>
			@endif
		@endforeach

		</div>
		@if( $comment )

		<hr>

		<div class="portlet light bordered">
			<strong>Instruction :</strong>
			<p style="white-space: pre-wrap;" class="text-justify">{{ $comment }}</p>
		</div>
		@endif
    </div>        
</div>
   
@endsection

@section('style')
@stop

@section('plugin_script') 
@stop

@section('script')
@stop
