<div id="advertising">
    <h1>{{ lang('advertising.title') }} <small>{{ lang('advertising.subtitle') }}</small></h1>

    <hr>

    <div class="pull-right" id="advertising-icon">
        {{ icon('comments-alt') }}
    </div>

    {{ lang('advertising.explanation') }}

    <hr>

    <!-- <div id="menu">
        <?php
        $tabs = array(
            array(
                'url' => route('get_crosspromotion'),
                'label' => lang('crosspromotion.title'),
            ),

            array(
                'url' => route('get_advertising_feed'),
                'label' => lang('advertising.feed.title'),
            ),

            array(
                'url' => route('get_advertising_email'),
                'label' => lang('advertising.email.title'),
            ),
        );
        ?>

        {{ Navigation::tabs($tabs) }}
    </div> -->

    <ul class="nav nav-tabs" id="main-tabs">
        <li><a href="#feed-pane" data-toggle="tab">{{ lang('advertising.feed.title') }}</a></li>
        <li><a href="#email-pane" data-toggle="tab">{{ lang('advertising.email.title') }}</a></li>
        <li><a href="#crosspromotion-pane" data-toggle="tab">{{ lang('crosspromotion.title') }}</a></li>
    </ul>

    <div class="tab-content">
        <div class="tab-pane" id="crosspromotion-pane">
            @include('advertising/crosspromotion')
        </div> <!-- /#crosspromotion-pane .tab-pane -->

        <div class="tab-pane" id="feed-pane">

        </div> <!-- /#crosspromotion-pane .tab-pane -->

        <div class="tab-pane" id="email-pane">

        </div> <!-- /#crosspromotion-pane .tab-pane -->
    </div> <!-- /.tab-content -->
</div>

@section('jQuery')
// from advertising
$('#main-tabs a:first').tab('show');
@endsection
