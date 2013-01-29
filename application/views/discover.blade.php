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
        <li><a href="#feed-pane" data-toggle="tab">{{ lang('promote.feed.title') }}</a></li>
        <li><a href="#email-pane" data-toggle="tab">{{ lang('promote.email.title') }}</a></li>
    </ul>

    <div class="tab-content">
        <div class="tab-pane" id="feed-pane">
            <p>
                {{ lang('discover.form.feed_help')}}
            </p>

            <hr>
            
            @include('forms/discover_feed')
        </div> <!-- /#feed-pane .tab-pane -->

        <div class="tab-pane" id="email-pane">
            <p>
                {{ lang('discover.form.email_help')}}
            </p>

            <hr>

            @include('forms/discover_email')
        </div> <!-- /#email-pane .tab-pane -->
    </div> <!-- /.tab-content -->
</div>

@section('jQuery')
// from discover
$('#main-tabs a:first').tab('show');
@endsection
