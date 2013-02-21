@section('page_title')
    {{ lang('vgc.game.edit.title') }}
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
    <h1>{{ lang('vgc.game.edit.title') }} <small>{{ xssSecure($profile->name) }} </small></h1>

    <hr>

    

    <ul class="nav nav-tabs" id="main-tabs">
        <li><a href="#general-pane" data-toggle="tab">{{ lang('vgc.common.general') }}</a></li>
        <li><a href="#promote-pane" data-toggle="tab">{{ lang('vgc.promote.title') }}</a></li>
        <li><a href="#crosspromotion-pane" data-toggle="tab">{{ lang('vgc.crosspromotion.title') }}</a></li>
    </ul>

    <div class="tab-content">
        <div class="tab-pane" id="general-pane">
            <p class="pull-right">
                <a href="{{ route('get_profile_preview', array($profile_type, $profile->id)) }}">{{ lang('vgc.common.preview_profile_modifications') }}</a> | 
                <a href="{{ route('get_profile_view', array($profile_type, name_to_url($profile->name))) }}">{{ lang('vgc.common.view_profile_link') }}</a>
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

                {{ Former::primary_submit(lang('vgc.common.update')) }}
                
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
                        {{ Former::text('name', lang('vgc.common.name')) }}

                        {{ Former::select('devstate', lang('vgc.game.devstate'))->options(get_array_lang(Config::get('vgc.developmentstates'), 'developmentstates.'))->value('released') }}

                        {{ Former::textarea('meta_description', lang('vgc.profile.meta_description'))->id('meta-description') }}
                    </div>

                    <div class="span4">
                        {{ Former::text('developer_name', lang('vgc.common.developer_name'))->useDatalist($devs, 'name')->value($developer_name)->help(lang('vgc.game.developer_name_help')) }}

                        {{ Former::url('developer_url', lang('vgc.common.developer_url'))->placeholder(lang('vgc.common.url')) }}
                    </div>

                    <div class="span4">
                        {{ Former::text('publisher_name', lang('vgc.common.publisher_name')) }}
                        
                        {{ Former::url('publisher_url', lang('vgc.common.publisher_url'))->placeholder(lang('vgc.common.url')) }}

                        {{ Former::text('meta_keywords', lang('vgc.profile.meta_keywords'))->help(lang('vgc.profile.meta_keywords_help')) }}
                    </div>
                </div> <!-- /.row -->

                <hr>

                <div class="row">
                    <div class="span4">
                        {{ Former::url('website', lang('vgc.common.website'))->placeholder(lang('vgc.common.url')) }}
                        {{ Former::url('blogfeed', lang('vgc.common.blogfeed'))->placeholder(lang('vgc.common.url'))->help(lang('vgc.common.blogfeed_help')) }}
                        {{ Former::url('presskit', lang('vgc.common.presskit'))->placeholder(lang('vgc.common.url')) }}
                    </div>

                    <div class="span8">
                        {{ Former::textarea('pitch', lang('vgc.game.pitch'))->help(lang('vgc.common.markdown_help'))->class('span8') }}
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
                            <li><a href="#stores" data-toggle="tab">{{ lang('vgc.common.stores') }}</a></li>
                            <li><a href="#press" data-toggle="tab">{{ lang('vgc.press.title') }}</a></li>
                            <li><a href="#socialnetworks" data-toggle="tab">{{ lang('vgc.common.socialnetworks') }}</a></li>
                            
                        </ul>

                        <div class="tab-content">
                            <?php
                            // name url select
                            $nu_select = array('socialnetworks', 'stores'); 
                            foreach ($nu_select as $field):
                            ?>
                                <div class="tab-pane" id="{{ $field }}">
                                    <?php
                                    $options = get_array_lang(Config::get('vgc.'.$field), $field.'.');
                                    $options = array_merge(array('' => lang('vgc.common.select_arrayitem_first_option')), $options);
                                    
                                    if (isset($old[$field])) $values = clean_names_urls_array($old[$field]);
                                    else $values = $profile->$field;

                                    $length = count($values['names']);
                                    for ($i = 0; $i < $length; $i++):
                                    ?>
                                        <div class="control-group-inline">
                                            {{ Former::select($field.'[names][]', '')->options($options)->value($values['names'][$i]) }} 
                                            {{ Former::url($field.'[urls][]', '')->value($values['urls'][$i])->placeholder(lang('vgc.common.url')) }}
                                        </div>
                                    @endfor

                                    @for ($i = 0; $i < 4; $i++)
                                        <div class="control-group-inline">
                                            {{ Former::select($field.'[names][]', '')->options($options) }} 
                                            {{ Former::url($field.'[urls][]', '')->placeholder(lang('vgc.common.url')) }}
                                        </div>
                                    @endfor
                                </div>
                            @endforeach

                            <?php
                            $nu_text = array('press');
                            foreach ($nu_text as $field):
                            ?>
                                <div class="tab-pane" id="{{ $field }}">
                                    <p>
                                        {{ lang('vgc.game.press_help') }} <br>
                                        {{ lang('vgc.common.text_url_delete_help') }}
                                    </p>

                                    <?php
                                    if (isset($old[$field])) $values = clean_names_urls_array($old[$field]);
                                    else $values = $profile->$field;

                                    $length = count($values['names']);
                                    for ($i = 0; $i < $length; $i++):
                                    ?>
                                        <div class="control-group-inline">
                                            {{ Former::text($field.'[names][]', '')->value($values['names'][$i])->placeholder(lang('vgc.common.title')) }} 
                                            {{ Former::url($field.'[urls][]', '')->value($values['urls'][$i])->placeholder(lang('vgc.common.url')) }}
                                        </div>
                                    @endfor

                                    @for ($i = 0; $i < 4; $i++)
                                        <div class="control-group-inline">
                                            {{ Former::text($field.'[names][]', '')->placeholder(lang('vgc.common.title')) }} 
                                            {{ Former::url($field.'[urls][]', '')->placeholder(lang('vgc.common.url')) }}
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
                        {{ Former::url('profile_background', lang('vgc.common.profile_background'))->placeholder(lang('vgc.common.url'))->help(lang('vgc.common.profile_background_help')) }}
                    
                        {{ Former::url('cover', lang('vgc.game.cover'))->placeholder(lang('vgc.common.url')) }}
                     </div>
                
                    <div class="span8">
                        <!-- names urls items -->
                        <ul class="nav nav-tabs" id="medias-tabs">
                            <li><a href="#screenshots" data-toggle="tab">{{ lang('vgc.common.screenshots') }}</a></li>
                            <li><a href="#videos" data-toggle="tab">{{ lang('vgc.common.videos') }}</a></li>
                            <li><a href="#soundtrack" data-toggle="tab">{{ lang('vgc.common.soundtrack') }}</a></li>
                        </ul>

                        <div class="tab-content">
                            <?php
                            $nu_text = array('screenshots', 'videos');
                            foreach ($nu_text as $fields):
                            ?>
                                <div class="tab-pane" id="{{ $fields }}">
                                    <p>
                                        {{ lang('vgc.common.text_url_delete_help') }}
                                    </p>

                                    <?php
                                    if (isset($old[$fields])) $values = clean_names_urls_array($old[$fields]);
                                    else $values = $profile->$fields;

                                    $length = count($values['names']);
                                    for ($i = 0; $i < $length; $i++):
                                    ?>
                                        <div class="control-group-inline">
                                            {{ Former::text($fields.'[names][]', '')->value($values['names'][$i])->placeholder(lang('vgc.common.title')) }} 
                                            {{ Former::url($fields.'[urls][]', '')->value($values['urls'][$i])->placeholder(lang('vgc.common.url')) }}
                                        </div>
                                    @endfor

                                    @for ($i = 0; $i < 4; $i++)
                                        <div class="control-group-inline">
                                            {{ Former::text($fields.'[names][]', '')->placeholder(lang('vgc.common.title')) }} 
                                            {{ Former::url($fields.'[urls][]', '')->placeholder(lang('vgc.common.url')) }}
                                        </div>
                                    @endfor
                                </div>
                            @endforeach

                            <div class="tab-pane" id="soundtrack">
                                <p>
                                    {{ lang('vgc.game.soundtrack_help') }}
                                </p>

                                <div class="alert alert-error">
                                    {{ lang('vgc.game.soundtrack_alert') }}
                                </div>

                                {{ Former::xlarge_url('soundtrack', lang('vgc.game.soundtrackurl'))->placeholder(lang('vgc.common.url')) }}
                            </div> 
                        </div> <!-- /.tab-content -->
                    </div>
                </div> <!-- /.row -->

                <hr>

                {{ Former::primary_submit(lang('vgc.common.update')) }}

            {{ Former::close() }}
        </div> <!-- /#general-pane .tab-pane -->

        {{--=============================================================================================================}}

        <div class="tab-pane" id="promote-pane">
            @if (is_standard_user())
                <p class="alert alert-info">
                    {{ lang('vgc.user.not_a_developer') }}
                </p>
            @elseif (is_admin() or is_developer())
                <h3>{{ lang('vgc.discover.feed_title') }}/{{ lang('vgc.discover.email_title') }}</h3>

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
                    {{ lang('vgc.crosspromotion.editgame.select_text') }}
                </p>

                <div class="row-fluid">
                    <div class="span4">
                        <?php
                        $options = Dev::where_privacy('public');

                        $values = $profile->crosspromotion_profiles['developers'];
                        
                        $size = count($options);
                        if ($size > 15) $size = 15;
                        ?>
                        {{ Former::multiselect('developers', lang('vgc.common.developers'))->fromQuery($options)->size($size)->value($values) }}
                    </div>

                    <div class="span4">
                        <?php
                        $options = Game::where_privacy('public');
                        
                        $values = $profile->crosspromotion_profiles['games'];

                        $size = count($options);
                        if ($size > 15) $size = 15;
                        ?>
                        {{ Former::multiselect('games', lang('vgc.common.games'))->fromQuery($options)->size($size)->value($values) }}
                    </div>
                </div> <!-- /.row -->

                {{ Former::primary_submit(lang('vgc.common.update')) }}

            {{ Former::close() }}

            <hr>

            <p>
                <?php
                $link = route('get_crosspromotion_from_game', array($profile->id, $profile->crosspromotion_key)); 
                ?>
                {{ lang('vgc.crosspromotion.editgame.link_text', array('url'=>$link)) }}
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
