@section('page_title')
    {{ lang('discover.title') }}
@endsection

<div id="discover">
    <h1>{{ lang('discover.title') }} <small>{{ lang('discover.subtitle') }}</small></h1>

    <hr>

    <div class="pull-right" id="discover-icon">
        {{ icon('eye-open') }}
    </div>

    @include('partials/discover_explanation_'.get_language())

    <hr>

    <ul class="nav nav-tabs" id="main-tabs">
        <li><a href="#feed-pane" data-toggle="tab">{{ lang('advertising.feed.title') }}</a></li>
        <li><a href="#email-pane" data-toggle="tab">{{ lang('advertising.email.title') }}</a></li>
    </ul>

    <div class="tab-content">
        <div class="tab-pane" id="feed-pane">
            <p>
                {{ lang('discover.form.help')}}
            </p>

            <hr>
            
            @include('forms/discover_feed')
        </div> <!-- /#feed-pane .tab-pane -->

        <div class="tab-pane" id="email-pane">
            
        </div> <!-- /#email-pane .tab-pane -->
    </div> <!-- /.tab-content -->
</div>

@section('jQuery')
// from discover
$('#main-tabs a:first').tab('show');
@endsection
