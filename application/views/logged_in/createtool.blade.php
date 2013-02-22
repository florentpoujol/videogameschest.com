@section('page_title')
    {{ lang('vgc.tool.add_title') }}
@endsection
<?php
$old = Input::old();
if ( ! empty($old)) Former::populate($old);

if (is_admin()) {
    $privacy = array_set_values_as_keys(Config::get('vgc.privacy_and_reviews'));
} 
?>
<div id="addtool" class="profile-form create-profile-form">
    <h1>{{ lang('vgc.tool.add_title') }}</h1>

    <hr>

    <?php
    $rules = Config::get('vgc.profiles_post_create_rules.tool', array());
    ?>
    {{ Former::open_vertical(route('post_profile_create', 'tool'))->rules($rules) }} 
        {{ Form::token() }}

        {{ Former::primary_submit(lang('vgc.common.add_profile')) }}

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
                            else $values = array();
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
                    
                    <li><a href="#socialnetworks" data-toggle="tab">{{ lang('vgc.common.socialnetworks') }}</a></li>
                </ul>

                <div class="tab-content">
                    <?php
                    // name url select
                    $nu_select = array('socialnetworks'); 
                    foreach ($nu_select as $fields):
                        $options = get_array_lang(Config::get('vgc.'.$fields), $fields.'.');
                        $options = array_merge(array('' => lang('vgc.common.select_arrayitem_first_option')), $options);

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
                                    {{ Former::url($fields.'[urls][]', '')->placeholder(lang('vgc.common.url'))->value($url) }}
                                </div>
                            @endfor
                        </div>
                    @endforeach

                    <?php
                    $nu_text = array('screenshots', 'videos');
                    foreach ($nu_text as $field):
                        if (isset($old[$field])) $values = clean_names_urls_array($old[$field]);
                        else $values = array();
                    ?>
                        <div class="tab-pane" id="{{ $field }}">
                            <p>
                                @if ($field == 'press')
                                    {{ lang('vgc.profile.press_help', array('type' => 'tool')) }} <br>
                                @endif
                                {{ lang('vgc.common.text_url_delete_help') }}
                            </p>

                            @for ($i = 0; $i < 4; $i++)
                                <div class="control-group-inline">
                                    <?php
                                    $name = isset($values['names'][$i]) ? $values['names'][$i] : '';
                                    $url = isset($values['urls'][$i]) ? $values['urls'][$i] : '';
                                    ?>
                                    {{ Former::text($field.'[names][]', '')->value($name)->placeholder(lang('vgc.common.title')) }} 
                                    {{ Former::url($field.'[urls][]', '')->value($url)->placeholder(lang('vgc.common.url')) }}
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
