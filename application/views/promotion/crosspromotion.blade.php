@section('page_title')
    {{ lang('crosspromotion.title') }}
@endsection


<div id="crosspromotion">
    <h2>{{ lang('crosspromotion.title') }}</h2>

    <hr>

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

            {{ Former::open_vertical(route('post_crosspromotion')) }}
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
                {{ lang('crosspromotion.no_game', array('add_game_link'=>URL::to_route('get_addgame'))) }}
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
                {{ lang('crosspromotion.how_it_works') }}
            </p>
        </div>
    </div>
    
</div> <!-- /#crosspromotion -->

