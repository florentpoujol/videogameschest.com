@section('page_title')
    {{ lang('vgc.tool.edit.title') }}
@endsection
<?php
$profile_type = 'tool';
$profile = Tool::find($profile_id);
$profile->update_with_preview_data();

Former::populate($profile);

$old = Input::old();
if ( ! empty($old)) Former::populate($old);

if (is_admin()) {
    $privacy = array_set_values_as_keys(Config::get('vgc.privacy_and_reviews'));
}
?>
<div id="edittool" class="profile-form update-profile-form">
    <h1>{{ lang('vgc.tool.edit.title') }}</h1>

    <hr>

    <p class="pull-right">
        <a href="{{ route('get_profile_preview', array($profile_type, $profile->id)) }}">{{ lang('vgc.common.preview_profile_modifications') }}</a> | 
        <a href="{{ route('get_profile_view', array($profile_type, name_to_url($profile->name))) }}">{{ lang('vgc.common.view_profile_link') }}</a>
    </p>

    <?php
    $rules = Config::get('vgc.profiles_post_update_rules.tool', array())
    ?>
    {{ Former::open_vertical(route('post_profile_update', 'tool'))->rules($rules) }} 
        {{ Form::token() }}
        {{ Form::hidden('id', $profile->id) }}

        {{ Former::primary_submit(lang('vgc.common.edit_profile')) }}

        <hr>

        @if (is_admin())
            {{ Former::select('privacy')->options($privacy) }}

            <hr>
        @endif

        <div class="row">
            <div class="span4">
                {{ Former::text('name', lang('vgc.common.name')) }}

                {{ Former::url('logo', lang('vgc.common.logo'))->placeholder(lang('vgc.common.logo')) }}

                
            </div>

            <div class="span4">
                {{ Former::url('background', lang('vgc.common.profile_background'))->placeholder(lang('vgc.common.url'))->help(lang('vgc.common.profile_background_help')) }}
            </div>

            <div class="span4">
                {{ Former::textarea('meta_description', lang('vgc.profile.meta_description'))->id('meta-description') }}

                {{ Former::text('meta_keywords', lang('vgc.profile.meta_keywords'))->placeholder(lang('vgc.profile.meta_keywords_help')) }}
            </div>
        </div> <!-- /.row -->

        <hr>

        <div class="row">
            <div class="span4">
                {{ Former::url('website', lang('vgc.common.website'))->placeholder(lang('vgc.common.url')) }}

                {{ Former::url('blogfeed', lang('vgc.common.blogfeed'))->placeholder(lang('vgc.common.url'))->help(lang('vgc.common.blogfeed_help')) }}

                {{ Former::url('documentation', lang('vgc.common.documentation'))->placeholder(lang('vgc.common.url'))->help(lang('vgc.common.documentation_help')) }}
            </div>  

            <div class="span8">
                {{ Former::textarea('pitch', lang('vgc.tool.pitch'))->help(lang('vgc.common.markdown_help'))->class('span12') }}
            </div>
        </div> <!-- /.row -->

        <hr>

        <div class="row">
            <div class="span6">
                <!-- array items -->
                <div class="tabbable tabs-left">
                    <ul class="nav nav-tabs nav-stacked" id="array-fields-tabs">
                        @foreach (Tool::$array_fields as $field)
                            <li><a href="#{{ $field }}" data-toggle="tab">{{ lang('vgc.tool.profile_form_'.$field.'_pane_title') }}</a></li>
                        @endforeach
                    </ul>

                    <div class="tab-content">
                        <?php
                        foreach (Tool::$array_fields as $field):
                            if (isset($old[$field])) $values = $old[$field];
                            else $values = $profile->$field;
                        ?>
                            <div class="tab-pane" id="{{ $field }}">
                                <p>{{ lang($field.'.tool_help', '') }}</p>

                                @if ($field == 'tool_works_on_os')
                                    {{ array_to_checkboxes('operatingsystems', $values, 'tool_works_on_os[]') }}
                                @else
                                    {{ array_to_checkboxes($field, $values) }}
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div> <!-- /.tabbable -->
                <!-- /array items -->
            </div>

            <div class="span6">
                <!-- names urls items -->
                <ul class="nav nav-tabs" id="general-nu-text-tabs">
                    <li><a href="#screenshots" data-toggle="tab">{{ lang('vgc.common.screenshots') }}</a></li>
                    <li><a href="#videos" data-toggle="tab">{{ lang('vgc.common.videos') }}</a></li>
                    <li><a href="#press" data-toggle="tab">{{ lang('vgc.press.title') }}</a></li>
                    <li><a href="#socialnetworks" data-toggle="tab">{{ lang('vgc.common.socialnetworks') }}</a></li>
                </ul>

                <div class="tab-content">
                    <?php
                    // name url select
                    $nu_select = array('socialnetworks'); 
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
                                    <?php
                                    $name = isset($values['names'][$i]) ? $values['names'][$i] : '';
                                    $url = isset($values['urls'][$i]) ? $values['urls'][$i] : '';
                                    ?>
                                    {{ Former::select($field.'[names][]', '')->options($options)->value($name) }} 
                                    {{ Former::url($field.'[urls][]', '')->placeholder(lang('vgc.common.url'))->value($url) }}
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
                    $nu_text = array('screenshots', 'videos', 'press');
                    foreach ($nu_text as $field):
                    ?>
                        <div class="tab-pane" id="{{ $field }}">
                            <p>
                                @if ($field == 'press')
                                    {{ lang('vgc.profile.press_help', array('type' => 'tool')) }} <br>
                                @endif
                                {{ lang('vgc.common.text_url_delete_help') }}
                            </p>
                            <?php
                            if (isset($old[$field])) $values = clean_names_urls_array($old[$field]);
                            else $values = $profile->$field;

                            $length = count($values['names']);
                            for ($i = 0; $i < $length; $i++):
                            ?>
                                <div class="control-group-inline">
                                    <?php
                                    $name = isset($values['names'][$i]) ? $values['names'][$i] : '';
                                    $url = isset($values['urls'][$i]) ? $values['urls'][$i] : '';
                                    ?>
                                    {{ Former::text($field.'[names][]', '')->value($name)->placeholder(lang('vgc.common.title')) }} 
                                    {{ Former::url($field.'[urls][]', '')->value($url)->placeholder(lang('vgc.common.url')) }}
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

        {{ Former::primary_submit(lang('vgc.common.add_profile')) }}

    {{ Former::close() }}
</div> <!-- /#addtool --> 

@section('jQuery')
// from add tool
$('#array-fields-tabs a:first').tab('show');
$('#general-nu-text-tabs a:first').tab('show');
@endsection
