@section('page_title')
    {{ lang('discover.title') }}
@endsection

<div id="discover">
    <h1>{{ lang('discover.title') }} <small>{{ lang('discover.subtitle') }}</small></h1>

    <hr>

    <div class="pull-right" id="discover-icon">
        {{ icon('eye-open') }}
    </div>

    <p>
        {{ lang('discover.home_text') }} <br>
        <br>
        <a class="accordion-toggle" data-toggle="collapse" href="#collapse-learn-more">
            {{ icon('circle-arrow-down') }} {{ lang('common.learn_more') }} 
        </a>
    </p>

    <div id="collapse-learn-more" class="collapse">
        {{--@include('partials/discover_explanation_'.get_language()) --}}
    </div>

    <hr>

    <ul class="nav nav-tabs" id="main-tabs">
        <li><a href="#feed-pane" data-toggle="tab">{{ lang('promotion.feed.title') }}</a></li>
        <li><a href="#email-pane" data-toggle="tab">{{ lang('promotion.email.title') }}</a></li>
    </ul>

    <div class="tab-content">
        <div class="tab-pane" id="feed-pane"> 
            @if (is_logged_in())
                <?php
                $feed = PromotionFeed::where_user_id(user_id())->first();
                ?>

                @if ( ! is_null($feed))
                    <?php
                    $url = route('get_promotion_feed_data', array($feed->id));
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
            @include('forms/discover_email')
        </div> <!-- /#email-pane .tab-pane -->
    </div> <!-- /.tab-content -->
</div>

@section('jQuery')
// from discover
$('#main-tabs a:first').tab('show');
@endsection
