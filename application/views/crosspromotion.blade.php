<hr>

        <h3>{{ lang('crosspromotion.title') }}</h3>

        @if (user()->crosspromotion_subscription == 1 || is_admin())
            <?php 
            $url = route('get_crosspromotion', array($game->id, user()->secret_key));
            ?>
            <p>{{ lang('crosspromotion.edit_game_subscribers_help', array('url' => $url)) }}</p>

            <?php 
            $games = Game::all();
            $options = array();

            foreach ($games as $temp_game) {
                if ($temp_game->id != $game->id)
                    $options[$temp_game->id] = $temp_game->name;
            }

            if (isset($old['promoted_games'])) $values = $old['promoted_games'];
            else $values = $game->promoted_games;
            ?>
            {{ Former::multiselect('promoted_games', '')->options($options)->forceValue($values) }}
        @else
            <p>{{ lang('crosspromotion.edit_game_nonsubscribers_help') }}</p>
        @endif

        <hr>