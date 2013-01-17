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
    'publisherurl' => 'url',
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

$developer_name = $game->actual_developer_name;
?>

<div id="editgame">
    <h1>{{ lang('game.edit.title') }}</h1>

    <hr>

    <ul class="nav nav-tabs" id="edit_game_tabs">
        <li><a href="#edit_profile" data-toggle="tab">{{ lang('common.profile') }}</a></li>
        <li><a href="#edit_crosspromotion" data-toggle="tab">{{ lang('crosspromotion.title') }}</a></li>
    </ul>

    <div class="tab-content">
        <div class="tab-pane" id="edit_profile">
            {{ Former::open_vertical(route('post_editgame'))->rules($rules) }} 
                {{ Form::token() }}
                {{ Form::hidden('id', $profile_id) }}

                {{ Former::primary_submit(lang('game.edit.submit')) }}

                <a href="{{ route('get_game', array(name_to_url($game->name))) }}">{{ lang('common.view_profile_link') }}</a>

                <hr>
                
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
                                    $options = array_merge(array('' => lang('common.select_arrayitem_first_option')), $options);
                                    
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

                        <hr>

                        {{ Former::primary_submit(lang('game.edit.submit')) }}
                    </div> <!-- /.span -->
                </div> <!-- /.row -->

                <div class="row">
                    

                </div>
                
            </form>
        </div> <!-- /.tab-pane #edit_profile -->

        <div class="tab-pane" id="edit_crosspromotion">
            <h2>{{ lang('crosspromotion.title') }}</h2>

            <hr>

            {{ Former::open_vertical(route('post_crosspromotion_editgame')) }}
                {{ Form::token() }}
                {{ Former::hidden('id', $game->id)}}
                
                <p>
                    {{ lang('crosspromotion.editgame.select_text') }}
                </p>

                <div class="row-fluid">
                    <div class="span4">
                        <?php
                        $options = Dev::where_privacy('public');

                        $values = $game->crosspromotion_profiles['developers'];
                        
                        $size = count($options);
                        if ($size > 15) $size = 15;
                        ?>
                        {{ Former::multiselect('developers', lang('common.developers'))->fromQuery($options)->size($size)->value($values) }}
                    </div>

                    <div class="span4">
                        <?php
                        $options = Game::where_privacy('public');
                        
                        $values = $game->crosspromotion_profiles['games'];

                        $size = count($options);
                        if ($size > 15) $size = 15;
                        ?>
                        {{ Former::multiselect('games', lang('common.games'))->fromQuery($options)->size($size)->value($values) }}
                    </div>
                </div> <!-- /.row -->

                {{ Former::primary_submit(lang('common.update')) }}
            </form>

            <hr>

            <p>
                
                <?php
                $link = route('get_crosspromotion_from_game', array($game->id, $game->crosspromotion_key)); 
                ?>
                {{ lang('crosspromotion.editgame.link_text', array('url'=>$link)) }}
            </p>
        </div> <!-- /.tab-pane #edit_crosspromotion -->
    </div> <!-- /.tab-sontent -->
</div> <!-- /#editgame --> 

@section('jQuery')
// from editgame
$('#edit_game_tabs a:first').tab('show');
$('#array_items_tabs a:first').tab('show');
$('#nu_items_tabs a:first').tab('show');

@endsection
