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

    <?php
        $tabs = array(
            array(
                'url' => route('get_discover_feed_page'),
                'label' => lang('promotion.email.title'),
            ),

            array(
                'url' => route('get_discover_email_page'),
                'label' => lang('promotion.feed.title'),
            ),
        );
        ?>

    {{ Navigation::tabs($tabs) }}
