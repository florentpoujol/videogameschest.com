@section('page_title')
    {{ lang('game.edit.title') }}
@endsection
<?php
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
    <h1>{{ lang('game.edit.title') }} <small>{{ xssSecure($game->name) }} </small></h1>

    <hr>

    <p class="pull-right">
        <a href="{{ route('get_game', array(name_to_url($game->name))) }}">{{ icon('eye-open') }}{{ lang('common.view_profile_link') }}</a>
    </p>

    <ul class="nav nav-tabs" id="main-tabs">
        <li><a href="#general-pane" data-toggle="tab">{{ lang('common.general') }}</a></li>
        <li><a href="#medias-pane" data-toggle="tab">{{ lang('common.medias') }}</a></li>
        <li><a href="#crosspromotion-pane" data-toggle="tab">{{ lang('crosspromotion.title') }}</a></li>
    </ul>

    <div class="tab-content">
        <div class="tab-pane" id="general-pane">
            <?php
            $rules = array(
                'name' => 'required|no_slashes|min:5',
                'developer_name' => 'required|no_slashes|min:5',
                'developer_url' => 'url',
                'publisher_name' => 'min:2',
                'publisher_url' => 'url',
                'website' => 'url',
                'blogfeed' => 'url',
                'presskit' => 'url',
            );
            ?>
            {{ Former::open_vertical(route('post_editgame'))->rules($rules) }} 
                {{ Form::token() }}
                {{ Form::hidden('id', $profile_id) }}

                {{ Former::primary_submit(lang('common.edit_profile')) }}

                <hr>

                <div class="row">
                    <div class="span4">
                        {{ Former::text('name', lang('common.name')) }}

                        {{ Former::select('devstate', lang('game.devstate'))->options(get_array_lang(Config::get('vgc.developmentstates'), 'developmentstates.'))->value('released') }}

                        @if (is_admin())
                            {{ Former::select('privacy')->options($privacy) }}
                        @endif
                    </div>

                    <div class="span4">
                        {{ Former::text('developer_name', lang('common.developer_name'))->useDatalist($devs, 'name')->value($developer_name)->help(lang('game.developer_name_help')) }}

                        {{ Former::url('developer_url', lang('common.developer_url'))->placeholder(lang('common.url')) }}
                    </div>

                    <div class="span4">
                        {{ Former::text('publisher_name', lang('common.publisher_name')) }}
                        
                        {{ Former::url('publisher_url', lang('common.publisher_url'))->placeholder(lang('common.url')) }}
                    </div>
                </div> <!-- /.row -->

                <hr>

                <div class="row">
                    <div class="span4">
                        {{ Former::url('website', lang('common.website'))->placeholder(lang('common.url')) }}
                        {{ Former::url('blogfeed', lang('common.blogfeed'))->placeholder(lang('common.url'))->help(lang('common.blogfeed_help')) }}
                        {{ Former::url('presskit', lang('common.presskit'))->placeholder(lang('common.url')) }}
                    </div>

                    <div class="span8">
                        {{ Former::textarea('pitch', lang('game.pitch'))->help(lang('common.bbcode_explanation'))->class('span8') }}
                    </div>
                </div> <!-- /.row -->

                <hr>

                <div class="row">
                    <div class="span6">
                        <!-- array items -->
                        <div class="tabbable tabs-left">
                            <ul class="nav nav-tabs nav-stacked" id="array-fields-tabs">
                                @foreach (Game::$array_fields as $field)
                                    <li><a href="#{{ $field }}" data-toggle="tab">{{ lang($field.'.title') }}</a></li>
                                @endforeach
                            </ul>

                            <div class="tab-content">
                                <?php
                                foreach (Game::$array_fields as $field):
                                    /*$fields = Config::get('vgc.'.$field);
                                    $options = get_array_lang($fields, $field.'.');

                                    if (isset($old[$field])) $values = $old[$field];
                                    else $values = $game->$field;
                                    // why do I need this ?
                                    // using populate will not work because it doesn't work with multiselect

                                    $size = count($fields);
                                    if ($size > 10 ) $size = 10;*/
                                    if (isset($old[$field])) $values = $old[$field];
                                    else $values = $game->$field;
                                ?>
                                <div class="tab-pane" id="{{ $field }}">
                                    <p>{{ lang($field.'.help', '') }}</p>
                                    {{-- Former::multiselect($field, '')->options($options)->forceValue($values)->size($size)}}
                                    {{ array_to_checkboxes($field, $values) }}
                                </div>
                                @endforeach
                            </div>
                        </div> <!-- /.tabbable -->
                        <!-- /array items -->
                    </div>

                    <div class="span6">
                        <!-- names urls items -->
                        <ul class="nav nav-tabs" id="general-nu-text-tabs">
                            <li><a href="#stores" data-toggle="tab">{{ lang('common.stores') }}</a></li>
                            <li><a href="#socialnetworks" data-toggle="tab">{{ lang('common.socialnetworks') }}</a></li>
                            <li><a href="#reviews" data-toggle="tab">{{ lang('common.reviews') }}</a></li>
                        </ul>

                        <div class="tab-content">
                            <?php
                            // name url select
                            $nu_select = array('socialnetworks', 'stores'); 
                            foreach ($nu_select as $fields):
                            ?>
                                <div class="tab-pane" id="{{ $fields }}">
                                    <?php
                                    $options = get_array_lang(Config::get('vgc.'.$fields), $fields.'.');
                                    $options = array_merge(array('' => lang('common.select_arrayitem_first_option')), $options);
                                    
                                    if (isset($old[$fields])) $values = clean_names_urls_array($old[$fields]);
                                    else $values = $game->$fields;

                                    $length = count($values['names']);
                                    for ($i = 0; $i < $length; $i++):
                                    ?>
                                        <div class="control-group-inline">
                                            {{ Former::select($fields.'[names][]', '')->options($options)->value($values['names'][$i]) }} 
                                            {{ Former::url($fields.'[urls][]', '')->value($values['urls'][$i])->placeholder(lang('common.url')) }}
                                        </div>
                                    @endfor

                                    @for ($i = 0; $i < 4; $i++)
                                        <div class="control-group-inline">
                                            {{ Former::select($fields.'[names][]', '')->options($options) }} 
                                            {{ Former::url($fields.'[urls][]', '')->placeholder(lang('common.url')) }}
                                        </div>
                                    @endfor
                                </div>
                            @endforeach

                            <?php
                            $nu_text = array('reviews');
                            foreach ($nu_text as $fields):
                            ?>
                                <div class="tab-pane" id="{{ $fields }}">
                                    <p>
                                        {{ lang('common.text-url-delete-help') }}
                                    </p>

                                    <?php
                                    if (isset($old[$fields])) $values = clean_names_urls_array($old[$fields]);
                                    else $values = $game->$fields;

                                    $length = count($values['names']);
                                    for ($i = 0; $i < $length; $i++):
                                    ?>
                                        <div class="control-group-inline">
                                            {{ Former::text($fields.'[names][]', '')->value($values['names'][$i])->placeholder(lang('common.title')) }} 
                                            {{ Former::url($fields.'[urls][]', '')->value($values['urls'][$i])->placeholder(lang('common.url')) }}
                                        </div>
                                    @endfor

                                    @for ($i = 0; $i < 4; $i++)
                                        <div class="control-group-inline">
                                            {{ Former::text($fields.'[names][]', '')->placeholder(lang('common.title')) }} 
                                            {{ Former::url($fields.'[urls][]', '')->placeholder(lang('common.url')) }}
                                        </div>
                                    @endfor
                                </div>
                            @endforeach
                        </div> <!-- /.tab-content -->
                    </div> <!-- /.span7 -->
                </div> <!-- /.row -->

                <hr>

                {{ Former::primary_submit(lang('common.edit_profile')) }}

            {{ Former::close() }}
        </div> <!-- /#general-pane .tab-pane -->

        <div class="tab-pane" id="medias-pane">
            <?php
            $rules = array(
                'profile_background' => 'url',
                'cover' => 'url',
                'soundtrack' => 'url',
            );
            ?>
            {{ Former::open_vertical(route('post_editgame'))->rules($rules) }} 
                {{ Form::token() }}
                {{ Form::hidden('id', $profile_id) }}

                {{ Former::primary_submit(lang('common.edit_profile')) }}

                <hr>

                <div class="row">
                    <div class="span4">
                        {{ Former::url('profile_background', lang('common.profile_background'))->placeholder(lang('common.url'))->help(lang('common.profile_background_help')) }}
                    
                        {{ Former::url('cover', lang('game.cover'))->placeholder(lang('common.url')) }}
                    
                        {{ Former::url('soundtrack', lang('game.soundtrackurl'))->placeholder(lang('common.url')) }}
                    </div>
                
                    <div class="span8">
                        <!-- names urls items -->
                        <ul class="nav nav-tabs" id="medias-tabs">
                            <li><a href="#screenshots" data-toggle="tab">{{ lang('common.screenshots') }}</a></li>
                            <li><a href="#videos" data-toggle="tab">{{ lang('common.videos') }}</a></li>
                        </ul>

                        <div class="tab-content">
                            <?php
                            $nu_text = array('screenshots', 'videos');
                            foreach ($nu_text as $fields):
                            ?>
                                <div class="tab-pane" id="{{ $fields }}">
                                    <p>
                                        {{ lang('common.text_url_delete_help') }}
                                    </p>

                                    <?php
                                    if (isset($old[$fields])) $values = clean_names_urls_array($old[$fields]);
                                    else $values = $game->$fields;

                                    $length = count($values['names']);
                                    for ($i = 0; $i < $length; $i++):
                                    ?>
                                        <div class="control-group-inline">
                                            {{ Former::text($fields.'[names][]', '')->value($values['names'][$i])->placeholder(lang('common.title')) }} 
                                            {{ Former::url($fields.'[urls][]', '')->value($values['urls'][$i])->placeholder(lang('common.url')) }}
                                        </div>
                                    @endfor

                                    @for ($i = 0; $i < 4; $i++)
                                        <div class="control-group-inline">
                                            {{ Former::text($fields.'[names][]', '')->placeholder(lang('common.title')) }} 
                                            {{ Former::url($fields.'[urls][]', '')->placeholder(lang('common.url')) }}
                                        </div>
                                    @endfor
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div> <!-- /.row -->

                <hr>

                {{ Former::primary_submit(lang('common.edit_profile')) }}

            {{ Former::close() }}
        </div> <!-- /#medias-pane .tab-pane -->

        <div class="tab-pane" id="crosspromotion-pane">
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

            {{ Former::close() }}

            <hr>

            <p>
                <?php
                $link = route('get_crosspromotion_from_game', array($game->id, $game->crosspromotion_key)); 
                ?>
                {{ lang('crosspromotion.editgame.link_text', array('url'=>$link)) }}
            </p>
        </div> <!-- /#crosspromotion-pane .tab-pane  -->
    </div> <!-- /.tab-sontent -->
</div> <!-- /#editgame --> 

@section('jQuery')
// from editgame
$('#main-tabs a:first').tab('show');
$('#array-fields-tabs a:first').tab('show');
$('#general-nu-text-tabs a:first').tab('show');
$('#medias-tabs a:first').tab('show');
@endsection
