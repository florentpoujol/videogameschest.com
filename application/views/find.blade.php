@section('page_title')
    {{ lang('find.title') }}
@endsection

<div id="find">
    <h1>{{ lang('find.title') }} <small>{{ lang('find.subtitle') }}</small></h1>

    <hr>

    <div class="pull-right" id="find-icon">
        {{ icon('eye-open') }}
    </div>

    <!-- <p>
        {{-- lang('find.explanation', array('search_link'=>route('get_search'))) }}
    </p> -->

    @include('partials/find_explanation_'.get_language())

    <hr>

    <ul class="nav nav-tabs" id="main-tabs">
        <li><a href="#feed-pane" data-toggle="tab">{{ lang('advertising.feed.title') }}</a></li>
        <li><a href="#email-pane" data-toggle="tab">{{ lang('advertising.email.title') }}</a></li>
    </ul>

    <div class="tab-content">
        <div class="tab-pane" id="feed-pane">
            @include('find/find_feed')
        </div> <!-- /#feed-pane .tab-pane -->

        <div class="tab-pane" id="email-pane">
            @include('find/find_email')
        </div> <!-- /#email-pane .tab-pane -->
    </div> <!-- /.tab-content -->
</div>

@section('jQuery')
// from find
$('#main-tabs a:first').tab('show');
@endsection
