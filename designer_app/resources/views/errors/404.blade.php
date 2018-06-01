@extends('templates.default')

@section('content')
<style>
.error { font-size: 6em; }	
</style>

<div class="alert text-center" style="margin: 0 auto;max-width: 600px;">

	@if( $logo = App\Setting::get_setting('logo') )
	<a href="//{{ $_SERVER['HTTP_HOST'] }}"><img src="{{ has_image($logo) }}" alt="logo" class="logo-default" 
	style="height:30px;margin:10px 0 0 0;" /></a>
	@endif

	<h1 class="error sbold text-danger">404</h1>	
	<h3><b class="text-muted">Oops!</b> You are stuck at 404</h3>

	<p class="text-muted">
	Unfortunately the page you were looking for could not be found.<br> 
	It may be temporarily unavailable, moved or no longer exist.<br> 
	Check the URL you entered for any mistakes and try again. </p>	
	</p>

	<hr>
	
	<p><a href="//{{ $_SERVER['HTTP_HOST'] }}">Go Back to Home Page</a><br>
	or <br>
	Search for whatever is missing, or take a look around the rest of our site.</p>

	<form method="get" action="{{ route('frontend.results') }}">

		<div class="form-group">
			<div class="input-group">
				<input type="text" name="s" class="form-control" placeholder="Enter your search here ...">
				<span class="input-group-btn">	
					<button type="submit" class="btn btn-primary button-search ">Search</button>
				</span>
			</div>

		</div>
	</form>

	
</div>


@stop


