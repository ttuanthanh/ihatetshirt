<!doctype html>
<html class="no-js" lang="{{ config('app.locale') }}">

    @include('partials.frontend.head') 

    <style>
    .card-photo {
        height: 200px;
        background-repeat: no-repeat;
        background-position: center;
        background-color: #fff;
        background-size: 100%;
    }
    </style>
    <body>

    @include('partials.frontend.header')

    <div class="page-title page-title--with-form">
        <div class="container">
            <div class="row">
                <div class="col-sm-4 col-md-6">
                    <h1>Search Results</h1>
                </div>
                <div class="col-sm-8 col-md-6">
                    <form class="form form-search" method="get" action="">
                        <fieldset><input type="text" name="s" placeholder="Search ..." value="{{ Input::get('s') }}"> <button><i class="fa fa-search text-muted"></i></button></fieldset>
                    </form>
                </div>
            </div>
        </div>
    </div>


    <div class="content">
        <div class="container">
            <div class="row">

                    <div class="col-md-12">
                        
                        <p class="text-muted">{{ $count = count($rows) }} result{{ is_plural($count) }}</p>

                        <div class="blog-list">

                        @foreach($rows as $row)
                        <?php 
                            $postmeta = get_meta( $row->postMetas()->get() ); 
                            
                            $link = route('frontend.post', ['product', $row->post_name]);

                            if($row->post_type == 'page') {
                                $link = route('frontend.post', $row->post_name);
                            } elseif($row->post_type == 'post') { 
                                $link = route('frontend.post', [strtolower($row->categoryList), $row->post_name]);

                            }


                        ?>

                            <div class="blog-card">

                                <div class="row">

                                    <div class="col-md-4">
                                        <a href="{{ $link }}">
                                            <div class="card-photo" style="background-image: url({{ $postmeta->image }});"></div>
                                        </a>
                                            
                                    </div>
                                    <div class="col-md-8">


         
                                    <div class="tags">
                                    @if( $tags = $postmeta->tags )
                                        @foreach(explode(',', $tags) as $tag)
                                            <a href="#">{{ $tag }}</a>
                                        @endforeach
                                    @endif
                                    </div>
                                    <h2><a href="{{ $link }}">{{ $row->post_title }}</a></h2>
                                    <div class="blog-card__meta">By <a href="#">{{ $row->authorName }}</a> • {{ date('F d, Y', strtotime($row->created_at)) }}</div>
                                    <p>{{ $postmeta->excerpt }}</p>
                                                                

                                    </div>

                                </div>

                       

                            </div>
                        @endforeach

                        </div>
                        
                        {{ $rows->links() }}

                        @if( ! $count )
                            No results found for <strong>{{ Input::get('s') }}</strong>!
                        @endif

                    </div>



            </div>
        </div>
    </div>
    
    @include('partials.frontend.subscribe') 
  
    @include('partials.frontend.footer') 

    </body>
</html>


    