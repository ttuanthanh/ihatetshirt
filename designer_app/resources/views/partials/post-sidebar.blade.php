
    <div class="sidebar">
        <div class="widget widget-like-us">
            <h3>Like us for tshirt design tips, promos, and the discounts.</h3>            
            
            <div id="fb-root"></div>
            <script>(function(d, s, id) {
            var js, fjs = d.getElementsByTagName(s)[0];
            if (d.getElementById(id)) return;
            js = d.createElement(s); js.id = id;
            js.src = 'https://connect.facebook.net/en_US/sdk.js#xfbml=1&version=v2.12';
            fjs.parentNode.insertBefore(js, fjs);
            }(document, 'script', 'facebook-jssdk'));</script>

            <div class="fb-like" data-href="https://www.facebook.com/teevisionprinting" data-layout="button_count" data-action="like" data-size="large" data-show-faces="true" data-share="true"></div>
        </div>

        <?php         
            $recents = App\Post::select('posts.*', 'm1.meta_value as category')
                                ->from('posts')
                                ->join('postmeta AS m1', function ($join) use ($cat) {
                                    $join->on('posts.id', '=', 'm1.post_id')
                                        ->where('m1.meta_key', '=', 'category')
                                        ->where('meta_value', $cat->id);
                                    })
                                ->where('post_type', 'post')
                                ->where('post_status', 'published')
                                ->orderBy('id', 'DESC')
                                ->limit(5)
                                ->get();
        ?>
        @if( count($recents) )
        <div class="widget">
            <h3 class="widget__title">Recent Posts</h3>
            <ul>
                @foreach($recents as $recent)
                    <?php $postmeta = get_meta($recent->postMetas()->get() ); ?>
                    <li><a href="{{ route('frontend.post', ['blog', $recent->post_name]) }}">
                        {{ $postmeta->excerpt ? str_limit($postmeta->excerpt, 60) : str_limit(strip_tags($recent->post_content, 60)) }}
                    </a></li>
                @endforeach
            </ul>
        </div>
        @endif

        <?php $tags = App\PostMeta::where('meta_key', 'tags')->where('meta_value', '!=', '')->get()->pluck('meta_value')->toArray(); ?>

        @if($tags)
        <?php $post_tags = array_unique(explode(',', str_replace(['[',']', '"'], '', json_encode($tags)))); ?>
        <div class="widget">
            <h3 class="widget__title">Posts Tags</h3>
            <div class="tags">
                @foreach($post_tags as $tag)
                <a href="#">{{ $tag }}</a>
                @endforeach
            </div>
        </div>
        @endif

        <?php         
            $trendings = App\Post::select('posts.*', 'm1.meta_value as category', 'm2.meta_value as view')
                                ->from('posts')
                                ->join('postmeta AS m1', function ($join) use ($cat) {
                                    $join->on('posts.id', '=', 'm1.post_id')
                                        ->where('m1.meta_key', '=', 'category')
                                        ->where('meta_value', $cat->id);
                                })
                                ->join('postmeta AS m2', function ($join) use ($cat) {
                                    $join->on('posts.id', '=', 'm2.post_id')
                                        ->where('m2.meta_key', '=', 'view');
                                })
                                ->where('post_type', 'post')
                                ->where('post_status', 'published')
                                ->orderBy('view', 'DESC')
                                ->limit(5)
                                ->get();
        ?>
        @if( count($trendings) )
        <div class="widget">
            <h3 class="widget__title">Trending Posts</h3>
            <ul>
                @foreach($trendings as $trending)
                    <?php $postmeta = get_meta($trending->postMetas()->get() ); ?>
                    <li><a href="{{ route('frontend.post', ['blog', $trending->post_name]) }}">
                        {{ $postmeta->excerpt ? str_limit($postmeta->excerpt, 60) : str_limit(strip_tags($trending->post_content, 60)) }}
                    </a></li>
                @endforeach
            </ul>
        </div>
        @endif

    </div>
