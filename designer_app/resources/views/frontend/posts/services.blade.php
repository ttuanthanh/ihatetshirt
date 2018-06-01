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


    <div class="content">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <h2 class="content__title">Let's find the service thats right for you</h2>
                    <div class="services-list">

                        @foreach($rows as $row)
                        <?php $postmeta = get_meta( $row->postMetas()->get() ); ?>
                        <div class="service">
                            <a href="{{ route('frontend.post', [$category, $row->post_name]) }}">
                                <span><img src="{{ $postmeta->image }}"></span>
                                <span>
                                    <h3>{{ str_limit($row->post_title, 50) }}</h3>
                                </span>
                            </a>
                        </div>
                        @endforeach

                    </div>
                </div>
            </div>
        </div>
    </div>
    
    @include('partials.frontend.subscribe') 
  
    @include('partials.frontend.footer') 

    </body>
</html>


    