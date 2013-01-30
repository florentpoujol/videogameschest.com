<div id="promotion">
    <h1>{{ lang('promotion.title') }} <small>{{ lang('promotion.subtitle') }}</small></h1>

    <hr>

    <div class="pull-right" id="promotion-icon">
        {{ icon('comments-alt') }}
    </div>

    {{ lang('promotion.explanation') }}

    <hr>

    <!-- <div id="menu">
        <?php
        $tabs = array(
            array(
                'url' => route('get_crosspromotion'),
                'label' => lang('crosspromotion.title'),
            ),

            array(
                'url' => route('get_promotion_feed'),
                'label' => lang('promotion.feed.title'),
            ),

            array(
                'url' => route('get_promotion_email'),
                'label' => lang('promotion.email.title'),
            ),
        );
        ?>

        {{ Navigation::tabs($tabs) }}
    </div> -->

    <ul class="nav nav-tabs" id="main-tabs">
        <li><a href="#feed-pane" data-toggle="tab">{{ lang('promotion.feed.title') }}</a></li>
        <li><a href="#email-pane" data-toggle="tab">{{ lang('promotion.email.title') }}</a></li>
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
