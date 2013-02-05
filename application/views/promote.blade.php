<div id="promotion">
    <h1>{{ lang('promote.title') }} <small>{{ lang('promote.subtitle') }}</small></h1>

    <hr>

    <div class="pull-right" id="promotion-icon">
        {{ icon('comments-alt') }}
    </div>

    {{ lang('promote.explanation') }}

    <hr>

    <ul class="nav nav-tabs" id="main-tabs">
        <li><a href="#feed-pane" data-toggle="tab">{{ lang('promote.feed.title') }}</a></li>
        <li><a href="#email-pane" data-toggle="tab">{{ lang('promote.email.title') }}</a></li>
        <li><a href="#crosspromotion-pane" data-toggle="tab">{{ lang('crosspromotion.title') }}</a></li>
    </ul>

    <div class="tab-content">
        <div class="tab-pane" id="crosspromotion-pane">
            @include('promotion/crosspromotion')
        </div> <!-- /#crosspromotion-pane .tab-pane -->

        <div class="tab-pane" id="feed-pane">

        </div> <!-- /#crosspromotion-pane .tab-pane -->

        <div class="tab-pane" id="email-pane">

        </div> <!-- /#crosspromotion-pane .tab-pane -->
    </div> <!-- /.tab-content -->
</div>

@section('jQuery')
// from promotion
$('#main-tabs a:first').tab('show');
@endsection
