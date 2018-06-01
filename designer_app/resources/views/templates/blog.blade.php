<?php 
/* 
    Template Name: Blog Post
*/
?>
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
                        <div class="blog-card blog-card--full">
                            
				            @if( @$s->tags == 'show' )
				            <div class="tags">
				                @if( $tags = $info->tags )
				                    @foreach(explode(',', $tags) as $tag)
				                        <a href="#">{{ $tag }}</a>
				                    @endforeach
				                @endif
							</div>                
				            @endif
							
							@if( @$s->title == 'show' )
							<h2>{{ $info->post_title }}</h2>
							@endif

                            <div class="blog-card__meta">By <a href="#">{{ $info->authorName }}</a> • {{ date('F d, Y', strtotime($info->created_at)) }}</div>      
                            <div class="blog-card__content">
                                @yield('content')   
                            </div>
                        </div>

						<?php         
						$previous = App\Post::select('posts.*', 'm1.meta_value as category')
						                    ->from('posts')
						                    ->join('postmeta AS m1', function ($join) use ($cat) {
						                        $join->on('posts.id', '=', 'm1.post_id')
						                            ->where('m1.meta_key', '=', 'category')
						                            ->where('meta_value', $cat->id);
						                        })
						                    ->where('post_type', 'post')
						                    ->where('post_status', 'published')
						                    ->where('posts.id', '<', $info->id)
				                            ->first();

						$next =	 App\Post::select('posts.*', 'm1.meta_value as category')
										->from('posts')
										->join('postmeta AS m1', function ($join) use ($cat) {
										    $join->on('posts.id', '=', 'm1.post_id')
										        ->where('m1.meta_key', '=', 'category')
										        ->where('meta_value', $cat->id);
										    })
										->where('post_type', 'post')
										->where('post_status', 'published')
										->where('posts.id', '>', $info->id)
										->first();
					          
						?>

                        <div class="pagi-card">
                        	@if( $previous )
                        	<a href="{{ route('frontend.post', ['blog', $previous->post_name]) }}" class="page-c pagi-card__prev">
                        		<strong>&lt; prev</strong><br><span>{{ $previous->post_title }}</span>
                        	</a> 
                        	@else
                        	<a></a>
                        	@endif

                        	@if( $next )
                    		<a href="{{ route('frontend.post', ['blog', $next->post_name]) }}" class="page-c pagi-card__next">
                    			<strong>next &gt;</strong><br><span>{{ $next->post_title }}</span>
                    		</a>
                    		@else
                    		<a></a>
                    		@endif
                        </div>
                    </div>
                  
					<div class="col-md-3">
					@include('partials.post-sidebar')
					</div>
                </div>
            </div>
        </div>
        <div class="container related-posts">
            <div class="row">
                <div class="col-md-12">
                    <h2>Related Posts</h2>
                    <div class="related-posts__list">

						<?php         
						$relateds = App\Post::select('posts.*', 'm1.meta_value as category')
						                    ->from('posts')
						                    ->join('postmeta AS m1', function ($join) use ($cat) {
						                        $join->on('posts.id', '=', 'm1.post_id')
						                            ->where('m1.meta_key', '=', 'category')
						                            ->where('meta_value', $cat->id);
						                        })
						                    ->where('post_type', 'post')
						                    ->where('post_status', 'published')
						                    ->limit(3)
						                    ->get();
						?>
						@foreach($relateds as $related)
                  		<?php $relatedmeta = get_meta($related->postMetas()->get());  ?>
                        <div class="related-posts__card">
                        	@if( $relatedmeta->image )
                            <a href="#"><img src="{{ str_replace('large', 'thumb', $relatedmeta->image) }}" style="height:240px"></a>
                            @endif

                            <div>
                                <h3><a href="{{ route('frontend.post', ['blog', $related->post_name]) }}">{{ $related->post_title }}</a></h3>
                                <p>By <a href="{{ route('frontend.post', ['blog', $related->post_name]) }}">{{ $related->authorName }}</a> • {{ date('F d, Y', strtotime($related->created_at)) }}</p>
                            </div>
                        </div>
                        @endforeach

                    </div>
                </div>
            </div>
        </div>


		@if( @$s->subscription == 'show' )
		    @include('partials.frontend.subscribe')
		@endif
    
        <?php 
            // count page view for this post
            $page_views = Session::get('page_view') ? Session::get('page_view') : array(); 
            if( ! in_array($info->id, $page_views) ) {
                $view = App\PostMeta::get_meta($info->id, 'view');
                App\PostMeta::update_meta($info->id, 'view', $view + 1);
                $page_view = array_prepend($page_views, $info->id);
                Session::put('page_view', $page_view);                
            }
        ?>      

	@include('partials.frontend.footer') 

	</body>
</html>

