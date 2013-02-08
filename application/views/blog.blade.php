<div id="blog">
    <h1>{{ lang('blog.title') }}</h1>

    <hr>

    <?php
    $sidebar_posts = BlogPost::order_by('created_at', 'desc')->get();

    if ( ! isset($display_posts)) $display_posts = $sidebar_posts;
    ?>

    <div class="row">
        <div class="span9">
            @foreach ($display_posts as $post)
                <article>
                    <h2>{{ $post->title }} <small>{{ $post->date }}</small></h2>


                    {{ $post->content }}

                </article>

                <hr>
            @endforeach
        </div>

        <!-- sidebar -->
        <div class="span3">
            <h3><a href="{{ route('get_blog_feed') }}" title="Blog feed">{{ icon('rss', null, 20) }}</a>  {{ lang('blog.latest_articles') }}</h3>

            <ul>
                @foreach ($sidebar_posts as $post)
                    <li><a href="{{ route('get_blog_post', array($post->title_url)) }}">{{ $post->title }}</a> <span class="muted">{{ $post->sidebar_date }}</span></li>    
                @endforeach
            </ul>
        </div>
    </div>
</div>