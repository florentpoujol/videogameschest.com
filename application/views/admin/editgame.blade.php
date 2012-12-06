<?php
$rules = array(
    'name' => 'required|min:5',
    'developer_id' => 'required|exists:developers,id',
    'logo' => 'url|active_url',
    'website' => 'url|active_url',
    'blogfeed' => 'url|active_url',
    'soundtrackurl' => 'url|active_url',
    'publishername' => 'min:2',
    'publisherurl' => 'url|active_url|required_with:publishername',
    'price' => 'min:0',
);

$game = Game::find($profile_id);
Former::populate($game);

$old = Input::old();
if ( ! empty($old)) {
    Former::populate($old);
}
var_dump($old);
?>

<div id="editgame_form">
    {{ Former::open_vertical('admin/editgame')->rules($rules) }} 
        <legend>{{ lang('game.edit.title') }}</legend>
        
        {{ Form::token() }}
        {{ Form::hidden('id', $profile_id) }}
        
        {{ Former::text('name', lang('game.fields.name')) }}

        @if (IS_ADMIN)
            {{ Former::select('developer_id', lang('game.fields.developer'))->fromQuery(Dev::all()) }}
        @else
            You are the developer of this game. <br>
            {{ Former::hidden('developer_id', DEV_PROFILE_ID) }}
        @endif

        {{ Former::select('devstate', lang('game.fields.devstate_title'))->options(get_array_lang(Config::get('vgc.developmentstates'), 'developmentstates.'))->value('released') }}

        {{ Former::textarea('pitch', lang('game.fields.pitch')) }}

        {{ Former::url('logo', lang('game.fields.logo')) }}
        {{ Former::url('website', lang('game.fields.website')) }}
        {{ Former::url('blogfeed', lang('game.fields.blogfeed')) }}
        {{ Former::url('soundtrackurl', lang('game.fields.soundtrackurl')) }}

        {{ Former::text('publishername', lang('game.fields.publishername')) }}
        {{ Former::url('publisherurl', lang('game.fields.publisherurl')) }}

        {{ Former::number('price', lang('game.fields.price')) }}

        
        <?php
        // array items technologies, languages, devices, ...
        foreach (Game::$array_items as $item):
            $items = Config::get('vgc.'.$item);
            $options = get_array_lang($items, $item.'.');

            $values = $game->json_to_array($item);
            if (isset($old[$item])) $values = $old[$item];
            // why do I need this ?
            // using populate will not work because the data is stored in json in the db
            // so I need forceValue() here to fill the select
            // populate() would work with $old since the data is in array, but forceValue would overrite it with the db data

            $size = count($items);
            if ($size > 10 ) $size = 10;
        ?>
            {{ Former::multiselect($item.'[]', lang('game.fields.'.$item))->options($options)->forceValue($values)->size($size)->help(lang('game.fields.'.$item.'_help')) }}
        @endforeach
        
        <?php
        // name url select
        $nu_select = array('socialnetworks', 'stores'); 
        foreach ($nu_select as $item):
        ?>
            <fieldset>
                <legend>{{ lang('game.fields.'.$item.'_title') }}</legend>

                <?php
                $options = get_array_lang(Config::get('vgc.'.$item), $item.'.');
                
                if (isset($old[$item])) $values = clean_names_urls_array($old[$item]);
                else $values = $game->json_to_array($item);

                $length = count($values['names']);
                for ($i = 0; $i < $length; $i++):
                    
                ?>
                    {{ Former::select($item.'[names][]', lang('game.fields.'.$item.'_name'))->options($options)->value($values['names'][$i]) }} 
                    {{ Former::url($item.'[urls][]', lang('game.fields.'.$item.'_url'))->value($values['urls'][$i]) }}
                @endfor

                @for ($i = $length; $i < $length+4; $i++)       
                    {{ Former::select($item.'[names][]', lang('game.fields.'.$item.'_name'))->options($options) }} 
                    {{ Former::url($item.'[urls][]', lang('game.fields.'.$item.'_url')) }}
                @endfor
            </fieldset>
        @endforeach

        <?php
        $nu_text = array('screenshots', 'videos');
        foreach ($nu_text as $item):
        ?>
            <fieldset>
                <legend>{{ lang('game.fields.'.$item.'_title') }}</legend>

                <?php
                if (isset($old[$item])) $values = clean_names_urls_array($old[$item]);
                else $values = $game->json_to_array($item);

                $length = count($values['names']);
                for ($i = 0; $i < $length; $i++):
                ?>
                    {{ Former::text($item.'[names][]', lang('game.fields.'.$item.'_name'))->value($values['names'][$i]) }} 
                    {{ Former::url($item.'[urls][]', lang('game.fields.'.$item.'_url'))->value($values['urls'][$i]) }}
                @endfor

                @for ($i = 1; $i < 5; $i++)     
                    {{ Former::text($item.'[names][]', lang('game.fields.'.$item.'_name')) }} 
                    {{ Former::url($item.'[urls][]', lang('game.fields.'.$item.'_url')) }}
                @endfor
            </fieldset>
        @endforeach

        <input type="submit" value="{{ lang('game.edit.submit') }}" class="btn btn-primary">
    </form>
</div>
<!-- /#user_form --> 

