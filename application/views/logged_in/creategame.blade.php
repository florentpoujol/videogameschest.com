@section('page_title')
    {{ lang('game.add.title') }}
@endsection
<?php
$old = Input::old();
if ( ! empty($old)) Former::populate($old);

if (is_admin()) {
    $users = User::get(array('id', 'username'));
    $privacy = array_set_values_as_keys(Config::get('vgc.privacy_and_reviews'));
} 

$devs = Dev::get(array('id', 'name'));

?>
<div id="addgame">
    <h1>{{ lang('game.add.title') }}</h1>

    <hr>

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
    {{ Former::open_vertical(route('post_game_create'))->rules($rules) }} 
        {{ Form::token() }}

        {{ Former::primary_submit(lang('common.add_profile')) }}

        <hr>

        @if (is_admin())
            {{ Former::select('privacy')->options($privacy) }}
        @endif

        <div class="row">
            <div class="span4">
                {{ Former::text('name', lang('common.name')) }}

                {{ Former::select('devstate', lang('game.devstate'))->options(get_array_lang(Config::get('vgc.developmentstates'), 'developmentstates.'))->value('released') }}
            </div>

            <div class="span4">
                {{ Former::text('developer_name', lang('common.developer_name'))->useDatalist($devs, 'name')->help(lang('game.developer_name_help')) }}

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
                            if (isset($old[$field])) $values = $old[$field];
                            else $values = array();
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
                        $options = get_array_lang(Config::get('vgc.'.$fields), $fields.'.');
                        $options = array_merge(array('' => lang('common.select_arrayitem_first_option')), $options);

                        if (isset($old[$fields])) $values = clean_names_urls_array($old[$fields]);
                        else $values = array();
                    ?>
                        <div class="tab-pane" id="{{ $fields }}">
                            @for ($i = 0; $i < 4; $i++)
                                <div class="control-group-inline">
                                    <?php
                                    $name = isset($values['names'][$i]) ? $values['names'][$i] : '';
                                    $url = isset($values['urls'][$i]) ? $values['urls'][$i] : '';
                                    ?>
                                    {{ Former::select($fields.'[names][]', '')->options($options)->value($name) }} 
                                    {{ Former::url($fields.'[urls][]', '')->placeholder(lang('common.url'))->value($url) }}
                                </div>
                            @endfor
                        </div>
                    @endforeach

                    <?php
                    $nu_text = array('press');
                    foreach ($nu_text as $fields):
                        if (isset($old[$fields])) $values = clean_names_urls_array($old[$fields]);
                        else $values = array();
                    ?>
                        <div class="tab-pane" id="{{ $fields }}">
                            <p>
                                {{ lang('game.press_help') }} <br>
                                {{ lang('common.text_url_delete_help') }}
                            </p>

                            @for ($i = 0; $i < 4; $i++)
                                <div class="control-group-inline">
                                    <?php
                                    $name = isset($values['names'][$i]) ? $values['names'][$i] : '';
                                    $url = isset($values['urls'][$i]) ? $values['urls'][$i] : '';
                                    ?>
                                    {{ Former::text($fields.'[names][]', '')->value($name)->placeholder(lang('common.title')) }} 
                                    {{ Former::url($fields.'[urls][]', '')->value($url)->placeholder(lang('common.url')) }}
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
                        if (isset($old[$fields])) $values = clean_names_urls_array($old[$fields]);
                        else $values = array();
                    ?>
                        <div class="tab-pane" id="{{ $fields }}">
                            <p>
                                {{ lang('common.text_url_delete_help') }}
                            </p>

                            @for ($i = 0; $i < 4; $i++)
                                <div class="control-group-inline">
                                    <?php
                                    $name = isset($values['names'][$i]) ? $values['names'][$i] : '';
                                    $url = isset($values['urls'][$i]) ? $values['urls'][$i] : '';
                                    ?>
                                    {{ Former::text($fields.'[names][]', '')->value($name)->placeholder(lang('common.title')) }} 
                                    {{ Former::url($fields.'[urls][]', '')->value($url)->placeholder(lang('common.url')) }}
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
                </div>
            </div>
        </div> <!-- /.row -->

        <hr>

        {{ Former::primary_submit(lang('common.add_profile')) }}

    {{ Former::close() }}
</div> <!-- /#addgame --> 

@section('jQuery')
// from addgame
$('#array-fields-tabs a:first').tab('show');
$('#general-nu-text-tabs a:first').tab('show');
$('#medias-tabs a:first').tab('show');
@endsection
