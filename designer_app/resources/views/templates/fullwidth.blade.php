<?php 
/* 
    Template Name: Fullwidth
*/
?>
<!doctype html>
<html class="no-js" lang="{{ config('app.locale') }}">

    @include('partials.frontend.head') 

    <body>

        @include('partials.frontend.header')

        @yield('content')        
      
        @include('partials.frontend.footer') 

    </body>
</html>