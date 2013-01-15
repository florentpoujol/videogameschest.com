@section('page_title')
    {{ lang('crosspromotion.title') }}
@endsection

@include('partials.ad_menu')

<div id="crosspromotion">
    <h1>{{ lang('crosspromotion.title') }}</h1>

    <hr>

    <?php
    $games = user()->games;
    ?>

    @if ( ! empty($games))
        @if (user()->crosspromotion_active == 1)
            <p>
                {{ lang('crosspromotion.activated_text') }}
            </p>
        @else
            <p>
                {{ lang('crosspromotion.non_activated_text') }}
            </p>
        @endif

        {{ Former::open_vertical(route('post_crosspromotion')) }}

            <!-- <div class="control-group">
            <label for="crosspromotion_subscription_checkbox">
                <input type="checkbox" name="crosspromotion_subscription" id="crosspromotion_subscription_checkbox">
                {{ lang('crosspromotion.subsciption_checkbox_label') }}
            </label>
            </div> -->
            {{ Former::checkbox('checkme', '')->text(lang('crosspromotion.subsciption_checkbox_label'))->check((user()->crosspromotion_active == 1)) }}

            {{ Former::primary_submit(lang('common.update')) }}
        </form>
    @else
        <p class="muted"> 
            {{ lang('crosspromotion.no_game', array('add_game_link'=>URL::to_route('get_addgame'))) }}
        </p>
    @endif

    <hr>

    <p>
        {{ lang('crosspromotion.what_is_it') }}
    </p>

    <hr>

    <p>
        {{ lang('crosspromotion.how_it_works') }}
    </p>
</div>
