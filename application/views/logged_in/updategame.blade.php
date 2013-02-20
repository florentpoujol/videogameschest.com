@section('page_title')
    {{ lang('game.edit.title') }}
@endsection
<?php
$profile_type = 'game';
$profile = Game::find($profile_id);
$profile->update_with_preview_data();

Former::populate($profile);

$old = Input::old();
if ( ! empty($old)) Former::populate($old);

if (is_admin()) {
    $devs = Dev::get(array('id', 'name'));
    $privacy = array_set_values_as_keys(Config::get('vgc.privacy_and_reviews'));
}
else $devs = Dev::where_privacy('public')->get(array('id', 'name'));

$developer_name = $profile->actual_developer_name;
?>

<div id="editgame" class="profile-form update-profile-form">
    <h1>{{ lang('game.edit.title') }} <small>{{ xssSecure($profile->name) }} </small></h1>

    <hr>

    

    <ul class="nav nav-tabs" id="main-tabs">
        <li><a href="#general-pane" data-toggle="tab">{{ lang('common.general') }}</a></li>
        <li><a href="#promote-pane" data-toggle="tab">{{ lang('promote.title') }}</a></li>
        <li><a href="#crosspromotion-pane" data-toggle="tab">{{ lang('crosspromotion.title') }}</a></li>
    </ul>

    <div class="tab-content">
        <div class="tab-pane" id="general-pane">
            <p class="pull-right">
                <a href="{{ route('get_profile_preview', array($profile_type, $profile->id)) }}">{{ lang('common.preview_profile_modifications') }}</a> | 
                <a href="{{ route('get_profile_view', array($profile_type, name_to_url($profile->name))) }}">{{ lang('common.view_profile_link') }}</a>
            </p>

            <?php
            $rules = array(
                'name' => 'required|alpha_dash_extended|min:2',
                'developer_name' => 'required|alpha_dash_extended|min:2',
                'developer_url' => 'url',
                'publisher_name' => 'min:2',
                'publisher_url' => 'url',
                'website' => 'url',
                'blogfeed' => 'url',
                'presskit' => 'url',

                'profile_background' => 'url',
                'cover' => 'url',
                'soundtrack' => 'url',
            );
            ?>
            {{ Former::open_vertical(route('post_profile_update', $profile_type))->rules($rules) }} 
                {{ Form::token() }}
                {{ Form::hidden('id', $profile->id) }}

                {{ Former::primary_submit(lang('common.update')) }}
                
                <br>
                <br>
                <div class="alert alert-info">
                    {{ lang('vgc.profile.update_help') }}
                </div>

                <hr>

                @if (is_admin())
                    {{ Former::select('privacy')->options($privacy) }}

                    <hr>
                @endif

                <div class="row">
                    <div class="span4">
                        {{ Former::text('name', lang('common.name')) }}

                        {{ Former::select('devstate', lang('game.devstate'))->options(get_array_lang(Config::get('vgc.developmentstates'), 'developmentstates.'))->value('released') }}

                        {{ Former::textarea('meta_description', lang('vgc.profile.meta_description'))->id('meta-description') }}
                    </div>

                    <div class="span4">
                        {{ Former::text('developer_name', lang('common.developer_name'))->useDatalist($devs, 'name')->value($developer_name)->help(lang('game.developer_name_help')) }}

                        {{ Former::url('developer_url', lang('common.developer_url'))->placeholder(lang('common.url')) }}
                    </div>

                    <div class="span4">
                        {{ Former::text('publisher_name', lang('common.publisher_name')) }}
                        
                        {{ Former::url('publisher_url', lang('common.publisher_url'))->placeholder(lang('common.url')) }}

                        {{ Former::text('meta_keywords', lang('vgc.profile.meta_keywords'))->help(lang('vgc.profile.meta_keywords_help')) }}
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
                        {{ Former::textarea('pitch', lang('game.pitch'))->help(lang('common.markdown_help'))->class('span8') }}
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
                                    if (isset($old[$field])) $values = $old[$field];
                                    else $values = $profile->$field;
                                ?>
                                <div class="tab-pane" id="{{ $field }}">
                                    <p>{{ lang($field.'.help', '') }}</p>
                                    
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
                            <li><a href="#press" data-toggle="tab">{{ lang('press.title') }}</a></li>
                            <li><a href="#socialnetworks" data-toggle="tab">{{ lang('common.socialnetworks') }}</a></li>
                            
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
                                    else $values = $profile->$fields;

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
                            $nu_text = array('press');
                            foreach ($nu_text as $fields):
                            ?>
                                <div class="tab-pane" id="{{ $fields }}">
                                    <p>
                                        {{ lang('game.press_help') }} <br>
                                        {{ lang('common.text_url_delete_help') }}
                                    </p>

                                    <?php
                                    if (isset($old[$fields])) $values = clean_names_urls_array($old[$fields]);
                                    else $values = $profile->$fields;

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

                <div class="row">
                    <div class="span4">
                        {{ Former::url('profile_background', lang('common.profile_background'))->placeholder(lang('common.url'))->help(lang('common.profile_background_help')) }}
                    
                        {{ Former::url('cover', lang('game.cover'))->placeholder(lang('common.url')) }}
                     </div>
                
                    <div class="span8">
                        <!-- names urls items -->
                        <ul class="nav nav-tabs" id="medias-tabs">
                            <li><a href="#screenshots" data-toggle="tab">{{ lang('common.screenshots') }}</a></li>
                            <li><a href="#videos" data-toggle="tab">{{ lang('common.videos') }}</a></li>
                            <li><a href="#soundtrack" data-toggle="tab">{{ lang('common.soundtrack') }}</a></li>
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
                                    else $values = $profile->$fields;

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

                            <div class="tab-pane" id="soundtrack">
                                <p>
                                    {{ lang('game.soundtrack_help') }}
                                </p>

                                <div class="alert alert-error">
                                    {{ lang('game.soundtrack_alert') }}
                                </div>

                                {{ Former::xlarge_url('soundtrack', lang('game.soundtrackurl'))->placeholder(lang('common.url')) }}
                            </div> 
                        </div> <!-- /.tab-content -->
                    </div>
                </div> <!-- /.row -->

                <hr>

                {{ Former::primary_submit(lang('common.update')) }}

            {{ Former::close() }}
        </div> <!-- /#general-pane .tab-pane -->

        {{--=============================================================================================================}}

        <div class="tab-pane" id="promote-pane">
            @if (is_standard_user())
                <p class="alert alert-info">
                    {{ lang('user.not_a_developer') }}
                </p>
            @elseif (is_admin() or is_developer())
                <h3>{{ lang('discover.feed_title') }}/{{ lang('discover.email_title') }}</h3>

                <?php
                $profile = $profile;

                ?>
                @include('forms/promote_update_profile_subscription')
                
                <hr>
            @endif
        </div> <!-- /#promote-pane .tab-pane  -->

        {{--=============================================================================================================}}

        <div class="tab-pane" id="crosspromotion-pane">
            {{ Former::open_vertical(route('post_crosspromotion_game_update')) }}
                {{ Form::token() }}
                {{ Former::hidden('id', $profile->id)}}
                
                <p>
                    {{ lang('crosspromotion.editgame.select_text') }}
                </p>

                <div class="row-fluid">
                    <div class="span4">
                        <?php
                        $options = Dev::where_privacy('public');

                        $values = $profile->crosspromotion_profiles['developers'];
                        
                        $size = count($options);
                        if ($size > 15) $size = 15;
                        ?>
                        {{ Former::multiselect('developers', lang('common.developers'))->fromQuery($options)->size($size)->value($values) }}
                    </div>

                    <div class="span4">
                        <?php
                        $options = Game::where_privacy('public');
                        
                        $values = $profile->crosspromotion_profiles['games'];

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
                $link = route('get_crosspromotion_from_game', array($profile->id, $profile->crosspromotion_key)); 
                ?>
                {{ lang('crosspromotion.editgame.link_text', array('url'=>$link)) }}
            </p>
        </div> <!-- /#crosspromotion-pane .tab-pane  -->
    </div> <!-- /.tab-sontent -->
</div> <!-- /#editgame --> 

@section('jQuery')
// from update game
$('#main-tabs a:first').tab('show');
$('#array-fields-tabs a:first').tab('show');
$('#general-nu-text-tabs a:first').tab('show');
$('#medias-tabs a:first').tab('show');
@endsection
