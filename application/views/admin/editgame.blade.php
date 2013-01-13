@section('page_title')
    {{ lang('game.edit.title') }}
@endsection
<?php
$rules = array(
    'name' => 'required|min:5',
    'cover' => 'url',
    'website' => 'url',
    'blogfeed' => 'url',
    'presskit' => 'url',
    'soundtrackurl' => 'url',
    'publishername' => 'min:2|aplha',
    'publisherurl' => 'url|required_with:publishername',
);

$game = Game::find($profile_id);
Former::populate($game);

$old = Input::old();
if ( ! empty($old)) Former::populate($old);

if (is_admin()) {
    $devs = Dev::get(array('id', 'name'));
    $privacy = array_set_values_as_keys(Config::get('vgc.privacy_and_reviews'));
}
else $devs = Dev::where_privacy('public')->get(array('id', 'name'));

if ($game->developer_id != 0) $developer_name = $game->dev->name;
else $developer_name = $game->developer_name;
?>

<div id="editgame">
    <h1>{{ lang('game.edit.title') }}</h1>

    <hr>

    {{ Former::open_vertical(route('post_editgame'))->rules($rules) }} 
        {{ Form::token() }}
        {{ Form::hidden('id', $profile_id) }}
        
        <div class="row">
            <div class="span5">
                {{ Former::text('name', lang('common.name')) }}

                {{ Former::text('developer_name', lang('common.developer'))->useDatalist($devs, 'name')->value($developer_name) }}
                
                @if (is_admin())
                    {{ Former::select('privacy')->options($privacy) }}
                @endif

                {{ Former::select('devstate', lang('game.devstate'))->options(get_array_lang(Config::get('vgc.developmentstates'), 'developmentstates.'))->value('released') }}

                {{ Former::textarea('pitch', lang('game.pitch'))->help(lang('common.bbcode_explanation')) }}

                {{ Former::url('cover', lang('game.cover'))->placeholder(lang('common.url')) }}
                {{ Former::url('website', lang('common.website'))->placeholder(lang('common.url')) }}
                {{ Former::url('blogfeed', lang('common.blogfeed'))->placeholder(lang('common.url')) }}
                {{ Former::url('presskit', lang('common.presskit'))->placeholder(lang('common.url')) }}
                {{ Former::url('soundtrackurl', lang('game.soundtrackurl'))->placeholder(lang('common.url')) }}

                <div class="control-group-inline">
                    {{ Former::text('publishername', lang('game.publishername')) }}
                    {{ Former::url('publisherurl', lang('game.publisherurl'))->placeholder(lang('common.url')) }}
                </div>
                <div class="clearfix"></div>

            </div> <!-- /.span5 -->

            <div class="span7">
                <!-- array items -->
                <div class="tabbable tabs-left">
                    <ul class="nav nav-tabs nav-stacked" id="array_items_tabs">
                        @foreach (Game::$array_fields as $item)
                        <li><a href="#{{ $item }}" data-toggle="tab">{{ lang($item.'.title') }}</a></li>
                        @endforeach
                    </ul>

                    <div class="tab-content">
                        <?php
                        foreach (Game::$array_fields as $item):
                            /*$items = Config::get('vgc.'.$item);
                            $options = get_array_lang($items, $item.'.');

                            if (isset($old[$item])) $values = $old[$item];
                            else $values = $game->$item;
                            // why do I need this ?
                            // using populate will not work because it doesn't work with multiselect

                            $size = count($items);
                            if ($size > 10 ) $size = 10;*/
                            if (isset($old[$item])) $values = $old[$item];
                            else $values = $game->$item;
                        ?>
                        <div class="tab-pane" id="{{ $item }}">
                            <p>{{ lang('game.'.$item.'_help') }}</p>
                            {{-- Former::multiselect($item, '')->options($options)->forceValue($values)->size($size)}}
                            {{ array_to_checkboxes($item, $values) }}
                        </div>
                        @endforeach
                    </div>
                </div> <!-- /.tabbable -->
                <!-- /array items -->
                
                <hr>

                <!-- names urls items -->
                <ul class="nav nav-tabs" id="nu_items_tabs">
                    @foreach (Game::$names_urls_fields as $item)
                    <li><a href="#{{ $item }}" data-toggle="tab">{{ lang('common.'.$item) }}</a></li>
                    @endforeach
                </ul>

                <div class="tab-content">
                    <?php
                    // name url select
                    $nu_select = array('socialnetworks', 'stores'); 
                    foreach ($nu_select as $item):
                    ?>
                        <div class="tab-pane" id="{{ $item }}">
                            <?php
                            $options = get_array_lang(Config::get('vgc.'.$item), $item.'.');
                            $options = array_merge(array('' => lang('common.select-arrayitem-first-option')), $options);
                            
                            if (isset($old[$item])) $values = clean_names_urls_array($old[$item]);
                            else $values = $game->$item;

                            $length = count($values['names']);
                            for ($i = 0; $i < $length; $i++):
                            ?>
                                <div class="control-group-inline">
                                    {{ Former::select($item.'[names][]', '')->options($options)->value($values['names'][$i]) }} 
                                    {{ Former::url($item.'[urls][]', '')->value($values['urls'][$i])->placeholder(lang('common.url')) }}
                                </div>
                            @endfor

                            @for ($i = 0; $i < 4; $i++)
                                <div class="control-group-inline">
                                    {{ Former::select($item.'[names][]', '')->options($options) }} 
                                    {{ Former::url($item.'[urls][]', '')->placeholder(lang('common.url')) }}
                                </div>
                            @endfor
                        </div>
                    @endforeach

                    <?php
                    $nu_text = array('screenshots', 'videos', 'reviews');
                    foreach ($nu_text as $item):
                    ?>
                        <div class="tab-pane" id="{{ $item }}">
                            <p>
                                {{ lang('common.text-url-delete-help') }}
                            </p>

                            <?php
                            if (isset($old[$item])) $values = clean_names_urls_array($old[$item]);
                            else $values = $game->$item;

                            $length = count($values['names']);
                            for ($i = 0; $i < $length; $i++):
                            ?>
                                <div class="control-group-inline">
                                    {{ Former::text($item.'[names][]', '')->value($values['names'][$i])->placeholder(lang('common.title')) }} 
                                    {{ Former::url($item.'[urls][]', '')->value($values['urls'][$i])->placeholder(lang('common.url')) }}
                                </div>
                            @endfor

                            @for ($i = 0; $i < 4; $i++)
                                <div class="control-group-inline">
                                    {{ Former::text($item.'[names][]', '')->placeholder(lang('common.title')) }} 
                                    {{ Former::url($item.'[urls][]', '')->placeholder(lang('common.url')) }}
                                </div>
                            @endfor
                        </div>
                    @endforeach
                </div> <!-- /.tab-content -->
                <!-- /names url items -->
            </div> <!-- /.span -->
        </div> <!-- /.row -->

        {{ Former::primary_submit(lang('game.edit.submit')) }}  
    </form>
</div> <!-- /#editgame --> 

@section('jQuery')
// from editgame
$('#array_items_tabs a:first').tab('show');
$('#nu_items_tabs a:first').tab('show');
@endsection
