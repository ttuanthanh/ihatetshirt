@if (count($errors->all()) > 0)
<div class="alert alert-danger alert-dismissable" style="margin-bottom:10px;">
	<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
	Please check the form below for errors
</div>
@endif

@if ($message = Session::get('success'))
<div class="alert alert-success" style="margin-bottom:10px;">
	{!! $message !!}
</div>
@endif

@if ($message = Session::get('error'))
<div class="alert alert-danger alert-dismissable" style="margin-bottom:10px;">
	<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
	{!! $message !!}
</div>
<!-- <p class="bg-danger">
	<h4>Error</h4>
	{!! $message !!}
</p> -->
@endif

@if ($message = Session::get('warning'))
<div class="alert alert-warning alert-dismissable" style="margin-bottom:10px;">
	<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
	{!! $message !!}
</div>
@endif

@if ($message = Session::get('info'))
<div class="alert alert-info alert-dismissable">
	<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
	{!! $message !!}
</div>
@endif