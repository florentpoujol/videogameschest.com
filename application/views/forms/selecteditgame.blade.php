<?php
if (is_admin()) $games = Game::preview_version()->get('id', 'name');
else $games = user()->games;
?>
<div id="selecteditgame">
    {{ Former::open_vertical(route('post_selecteditgame'))->rules(array('game_name' => 'required')) }} 
        {{ Form::token() }}

        {{ Former::text('game_name', lang('game.edit.select_profile_help'))->useDatalist($games, 'name')->placeholder(lang('game.edit.select_profile_placeholder')) }}
        {{-- Former::select('game_id', 'Name')->fromQuery($games) --}}

        {{ Former::primary_submit(lang('game.edit.submit')) }}
    </form>
</div> <!-- /#selecteditgame-form --> 
