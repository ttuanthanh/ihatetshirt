<?php 
/* 
    Template Name: Default
*/
?>
<!doctype html>
<html class="no-js" lang="{{ config('app.locale') }}">

    @include('partials.frontend.head') 

    <body>

        @include('partials.frontend.header')

        @if( @$s->title == 'show' )
        <div class="page-title"><div class="container"><div class="row"><div class="col-md-12"><h1>
        {!! $info->post_title !!}
        </h1></div></div></div></div>
        @endif

        <div class="content bg-white">
            <div class="container">

            @if( @$s->tags == 'show' )
            <div class="tags">
                @if( $tags = $info->tags )
                    @foreach(explode(',', $tags) as $tag)
                        <a href="#">{{ $tag }}</a>
                    @endforeach
                @endif
            </div>
            @endif

            @yield('content')     
            </div>      
        </div>
   
		@if( @$s->subscription == 'show' )
		    @include('partials.frontend.subscribe')
		@endif
      
        @include('partials.frontend.footer') 

    </body>
</html>