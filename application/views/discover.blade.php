<div id="discover">
    <div class="pull-right muted" id="discover-icon">
        {{ icon('eye-open') }}
    </div>
    
    <h1>{{ lang('discover.title') }}<!--  <small>{{ lang('discover.subtitle') }}</small> --></h1>

    <hr>

    <p>
        {{ lang('discover.page_text') }} <br>
        <br>
        <a class="accordion-toggle" data-toggle="collapse" href="#collapse-learn-more">
            {{ icon('circle-arrow-down') }} {{ lang('common.learn_more') }} 
        </a>
    </p> 

    <div id="collapse-learn-more" class="collapse">
        {{ lang('discover.page_learn_more_text', array('search_url' => route('get_search_page'))) }}
    </div>

    <hr>

    <ul class="nav nav-tabs" id="main-tabs">
        <li><a href="#feed-pane" data-toggle="tab">{{ lang('discover.feed_title') }}</a></li>
        <li><a href="#email-pane" data-toggle="tab">{{ lang('discover.email_title') }}</a></li>
    </ul>

    <div class="tab-content">
        <div class="tab-pane" id="feed-pane"> 
            @if (is_logged_in())
                <?php
                $feed = PromotionFeed::where_user_id(user_id())->first();
                ?>

                @if ( ! is_null($feed))
                    <?php
                    $url = route('get_discover_feed_data', array($feed->id));
                    ?>
            
                    <p>
                        {{ lang('discover.form.feed.help_url', array('feed_url'=>$url, 'username'=>user()->name )) }}
                    </p>

                    <hr>
                @endif
            @endif

            @include('forms/discover_feed')
        </div> <!-- /#feed-pane .tab-pane -->

        <div class="tab-pane" id="email-pane">
            <?php
            $newsletter = null;

            if (is_logged_in()) {
                $newsletter = user()->PromotionNewsletter;
            } elseif (isset($newsletter_id) && isset($url_key)) {
                $newsletter = PromotionNewsletter::where_id($newsletter_id)->where_url_key($url_key)->first();

                if (is_null($newsletter)) {
                    HTML::set_error(lang('discover.msg.email_id_key_no_match'));
                }
            }
            ?>

            {{ HTML::get_errors() }}

            @if ( ! is_null($newsletter))
                @include('forms/discover_update_email')
            @else
                @include('forms/discover_create_email')
            @endif
        </div> <!-- /#email-pane .tab-pane -->
    </div> <!-- /.tab-content -->
</div>

@section('jQuery')
// from discover
    @if ( ! isset($current_tab)) 
        $('#main-tabs a:first').tab('show');
    @else
        $('#main-tabs a[href="{{ $current_tab }}"]').tab('show');
    @endif
@endsection
