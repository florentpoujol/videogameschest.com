<?php
$rules = array(
    'name' => 'required|min:5',
    'developer_id' => 'required|exists:developers,id',
    'cover' => 'url',
    'website' => 'url',
    'blogfeed' => 'url',
    'soundtrackurl' => 'url',
    'publishername' => 'min:2|required_with:publisherurl',
    'publisherurl' => 'url|required_with:publishername',
    'price' => 'min:0'
);

$old = Input::old();
if ( ! empty($old)) Former::populate($old);

if (IS_ADMIN) $devs = Dev::get(array('id', 'name'));
else $devs = Dev::where('privacy', '=', 'public')->get(array('id', 'name'));

if (CONTROLLER == 'admin' && IS_ADMIN) {
    $privacy = array_set_values_as_keys(Config::get('vgc.privacy'));
}
?>

<div id="addgame_form">
    {{ Former::open_vertical('admin/addgame')->rules($rules) }} 
        <legend>{{ lang('game.add.title') }}</legend>
        
        {{ Form::token() }}
        {{ Form::hidden('controller', CONTROLLER) }}
        
        {{ Former::text('name', lang('game.fields.name')) }}

        @if (Auth::guest() || IS_ADMIN)
            {{ Former::select('developer_id', lang('game.fields.developer'))->fromQuery($devs) }}
        @else
            You are the developer of this game. <br>
            {{ Former::hidden('developer_id', DEV_PROFILE_ID) }}
        @endif

        @if (CONTROLLER == 'admin')
            @if (IS_ADMIN)
                {{ Former::select('privacy')->options($privacy) }}
            @else
                {{ Former::hidden('privacy', 'private') }}
            @endif
        @elseif (CONTROLLER == 'addgame')
            {{ Former::hidden('privacy', 'submission') }}
        @endif

        {{ Former::select('devstate', lang('game.fields.devstate_title'))->options(get_array_lang(Config::get('vgc.developmentstates'), 'developmentstates.'))->value('released') }}

        {{ Former::textarea('pitch', lang('game.fields.pitch')) }}

        {{ Former::url('cover', lang('game.fields.cover')) }}
        {{ Former::url('website', lang('game.fields.website')) }}
        {{ Former::url('blogfeed', lang('game.fields.blogfeed')) }}
        {{ Former::url('soundtrackurl', lang('game.fields.soundtrackurl')) }}

        {{ Former::text('publishername', lang('game.fields.publishername')) }}
        {{ Former::url('publisherurl', lang('game.fields.publisherurl')) }}

        {{ Former::number('price', lang('game.fields.price')) }}

        <hr>

        <!-- array items -->
        <div class="tabbable tabs-left">
            <ul class="nav nav-tabs nav-stacked" id="array_items_tabs">
                @foreach (Game::$array_items as $item)
                <li><a href="#{{ $item }}" data-toggle="tab">{{ lang('game.fields.'.$item) }}</a></li>
                @endforeach
            </ul>

            <div class="tab-content">
                @foreach (Game::$array_items as $item)
                    <?php
                    $items = Config::get('vgc.'.$item);
                    $options = get_array_lang($items, $item.'.');
                    
                    $values = array();
                    if (isset($old[$item])) $values = $old[$item];
                    
                    $size = count($items);
                    if ($size > 15) $size = 15;
                    ?>
                    <div class="tab-pane" id="{{ $item }}">
                        {{ Former::multiselect($item.'[]', lang('game.fields.'.$item))->options($options)->value($values)->size($size)->help(lang('game.fields.'.$item.'_help')) }}
                    </div>
                @endforeach
            </div>
        </div>
        <!-- /array items -->

        <hr>

        <!-- names urls items -->
        <ul class="nav nav-tabs" id="nu_items_tabs">
            @foreach (Game::$names_urls_items as $item)
            <li><a href="#{{ $item }}" data-toggle="tab">{{ lang('game.fields.'.$item.'_title') }}</a></li>
            @endforeach
        </ul>

        <div class="tab-content">
            <?php
            $nu_select = array('socialnetworks', 'stores'); // name url select
            foreach ($nu_select as $item):
                $options = get_array_lang(Config::get('vgc.'.$item), $item.'.');
                $values = array();
                if (isset($old[$item])) $values = clean_names_urls_array($old[$item]);
            ?>
                <div class="tab-pane" id="{{ $item }}">
                    <!-- <legend>{{ lang('game.fields.'.$item.'_title') }}</legend> -->

                    @for ($i = 0; $i < 4; $i++)
                        <?php
                        $name = isset($values['names'][$i]) ? $values['names'][$i] : '';
                        $url = isset($values['urls'][$i]) ? $values['urls'][$i] : '';
                        ?>
                        {{ Former::select($item.'[names][]', lang('game.fields.'.$item.'_name'))->options($options)->value($name) }} 
                        {{ Former::url($item.'[urls][]', lang('game.fields.'.$item.'_url'))->value($url) }}
                    @endfor
                </div>
            @endforeach

            <?php
            $nu_text = array('screenshots', 'videos');
            foreach ($nu_text as $item):
                $values = array();
                if (isset($old[$item])) $values = clean_names_urls_array($old[$item]);
            ?>
                <div class="tab-pane" id="{{ $item }}">
                    <!-- <legend>{{ lang('game.fields.'.$item.'_title') }}</legend> -->

                    @for ($i = 0; $i < 4; $i++)
                        <?php
                        $name = isset($values['names'][$i]) ? $values['names'][$i] : '';
                        $url = isset($values['urls'][$i]) ? $values['urls'][$i] : '';
                        ?>
                        {{ Former::text($item.'[names][]', lang('game.fields.'.$item.'_name'))->value($name) }} 
                        {{ Former::url($item.'[urls][]', lang('game.fields.'.$item.'_url'))->value($url) }}
                    @endfor
                </div>
            @endforeach
        </div>

        <input type="submit" value="{{ lang('game.add.submit') }}" class="btn btn-primary">
    </form>
</div>
<!-- /#addgame_form --> 

@section('jQuery')
// from addgame
$('#array_items_tabs a').click(function (e) {
  e.preventDefault();
  $(this).tab('show');
})
$('#array_items_tabs a:first').tab('show');

$('#nu_items_tabs a').click(function (e) {
  e.preventDefault();
  $(this).tab('show');
})
$('#nu_items_tabs a:first').tab('show');
// from addgame
@endsection
