<div id="promote">
    <div class="pull-right muted" id="promote-icon">
        {{ icon('volume-up') }}
    </div>

    <h1>{{ lang('promote.title') }}<!--  <small>{{ lang('promote.subtitle') }}</small> --></h1>

    <hr>


    {{ lang('promote.explanation') }}

    <hr>

    <ul class="nav nav-tabs" id="main-tabs">
        <li><a href="#feed-pane" data-toggle="tab">{{ lang('discover.feed_title') }}/{{ lang('discover.email_title') }}</a></li>
        <li><a href="#crosspromotion-pane" data-toggle="tab">{{ lang('crosspromotion.title') }}</a></li>
    </ul>

    <div class="tab-content">
        <div class="tab-pane" id="feed-pane">

        </div> <!-- /#feed-pane .tab-pane -->

        <div class="tab-pane" id="crosspromotion-pane">
            <div id="crosspromotion">
                @if (false && is_logged_in())
                    <?php
                    $has_subscribed = (user()->crosspromotion_active == 1);
                    ?>

                    @if ( ! empty(user()->games))
                        <p>
                            @if ($has_subscribed)
                                {{ lang('crosspromotion.activated_text') }}
                            @else
                                {{ lang('crosspromotion.non_activated_text') }}
                            @endif
                        </p>

                        {{ Former::open_vertical(route('post_crosspromotion_update')) }}
                            {{ Form::token() }}

                            {{ Former::checkbox('crosspromotion_active', '')->text(lang('crosspromotion.subsciption_checkbox_label'))->check($has_subscribed) }}

                            @if ($has_subscribed)
                                {{ Former::danger_submit(lang('common.update')) }}
                            @else
                                {{ Former::success_submit(lang('common.update')) }}
                            @endif
                        </form>
                    @else
                        <p class="muted"> 
                            {{ lang('crosspromotion.no_game', array('add_game_link'=>route('get_game_create'))) }}
                        </p>
                    @endif

                <hr>
                @endif

                <div class="row">
                    <div class="span5">
                        <h3>{{ lang('crosspromotion.what_is_it_title') }}</h3>

                        <p>
                            {{ lang('crosspromotion.what_is_it') }}
                        </p>
                    </div>

                    <div class="span5 offset1">
                        <h3>{{ lang('crosspromotion.how_it_works_title') }}</h3>

                        <p>
                            {{ lang('crosspromotion.how_it_works', array('update_game_link' => route('get_game_update'))) }}
                        </p>
                    </div>
                </div>
            </div> <!-- /#crosspromotion -->
        </div> <!-- /#crosspromotion-pane .tab-pane -->
    </div> <!-- /.tab-content -->
</div> <!-- /#promote -->

@section('jQuery')
// from promotion
$('#main-tabs a:first').tab('show');
@endsection
