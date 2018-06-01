<!doctype html>
<html class="no-js" lang="{{ config('app.locale') }}">

    @include('partials.frontend.head') 

    <body>

    @include('partials.frontend.header')

    <div class="page-title page-title--with-form">
        <div class="container">
            <div class="row">
                <div class="col-sm-4 col-md-6">
                    <h1>{{ $cat->post_title }}</h1>
                </div>
                <div class="col-sm-8 col-md-6">
                    <form class="form form-search" method="get" action="{{ route('frontend.results') }}">
                        <fieldset><input type="text" name="s" placeholder="Search our {{ strtolower($cat->post_title) }}" value="{{ Input::get('s') }}"> <button><i class="fa fa-search text-muted"></i></button></fieldset>
                    </form>
                </div>
            </div>
        </div>
    </div>


    <div class="content">
        <div class="container">
            <div class="row">

                    <div class="col-md-9">
                        <div class="blog-list">

                        @foreach($rows as $row)
                        <?php $postmeta = get_meta( $row->postMetas()->get() ); ?>
                            <div class="blog-card">
                                <div class="blog-card__photo"><img src="{{ $postmeta->image }}"></div>
                                <div class="blog-card__content">
                                    <div class="tags">
                                    @if( $tags = $postmeta->tags )
                                        @foreach(explode(',', $tags) as $tag)
                                            <a href="#">{{ $tag }}</a>
                                        @endforeach
                                    @endif
                                    </div>
                                    <h2><a href="{{ route('frontend.post', [$category, $row->post_name]) }}">{{ $row->post_title }}</a></h2>
                                    <div class="blog-card__meta">By <a href="#">{{ $row->authorName }}</a> • {{ date('F d, Y', strtotime($row->created_at)) }}</div>
                                    <p>{{ $postmeta->excerpt }}</p>
                                </div>
                            </div>
                        @endforeach

                        </div>
                        
                        {{ $rows->links() }}

                    </div>


                    <div class="col-md-3">
                    @include('partials.post-sidebar')
                    </div>

            </div>
        </div>
    </div>
    
    @include('partials.frontend.subscribe') 
  
    @include('partials.frontend.footer') 

    </body>
</html>


    