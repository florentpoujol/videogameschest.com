@section('page_title')
    {{ lang('developer.add.title') }}
@endsection

<?php
$old = Input::old();
if ( ! empty($old)) Former::populate($old);

if (is_admin()) {
    $users = User::get(array('id', 'username'));
    $privacy = array_set_values_as_keys(Config::get('vgc.privacy_and_reviews'));
}
?>
<div id="adddeveloper" class="create-profile-form profile-form">
    <h1>{{ lang('developer.add.title') }}</h1>

    <hr>

    <?php
    $rules = Config::get('vgc.profiles_post_create_rules.developer', array());
    ?>
    {{ Former::open_vertical(route('post_profile_create'), 'developer')->rules($rules) }}
        {{ Form::token() }}

        {{ Former::primary_submit(lang('common.add_profile')) }}

        <hr>

        @if (is_admin())
            {{ Former::select('user_id', 'User')->fromQuery($users)  }}

            {{ Former::select('privacy')->options($privacy) }}

            <hr>
        @endif

        <div class="row">
            <div class="span4">
                {{ Former::text('name', lang('common.name'))->help(lang('developer.name_help')) }}

                {{ Former::url('logo', lang('common.logo'))->placeholder(lang('common.url')) }}

                {{ Former::textarea('meta_description', lang('vgc.profile.meta_description'))->id('meta-description') }}

                {{ Former::text('meta_keywords', lang('vgc.profile.meta_keywords'))->placeholder(lang('vgc.profile.meta_keywords_help')) }}
            </div>
            
            <div class="span8">
                {{ Former::textarea('pitch', lang('developer.pitch'))->class('span8')->help(lang('common.markdown_help')) }}
            </div>
        </div>

        <hr>

        <div class="row">
            <div class="span4">
                {{ Former::url('website', lang('common.website'))->placeholder(lang('common.url')) }}

                {{ Former::url('blogfeed', lang('common.blogfeed'))->placeholder(lang('common.url'))->help(lang('common.blogfeed_help')) }}
            </div>

            <div class="span4">
                {{ Former::email('email', lang('developer.email')) }}

                {{ Former::number('teamsize', lang('common.teamsize'))->value(1) }}
            </div>

            <div class="span4">
                {{ Former::url('presskit', lang('common.presskit'))->placeholder(lang('common.url')) }}

                {{ Former::select('country', lang('common.country'))->options(get_array_lang(Config::get('vgc.countries'), 'countries.')) }}
            </div>
        </div>

        <hr>

        <div class="row">
            <div class="span12">
                <!-- array items + socialnetworks -->
                <div class="tabbable tabs-left">
                    <ul class="nav nav-tabs nav-stacked" id="array_items_tabs">
                        @foreach (Dev::$array_fields as $item)
                        <li><a href="#{{ $item }}" data-toggle="tab">{{ lang($item.'.title') }}</a></li>
                        @endforeach

                        <li><a href="#socialnetworks" data-toggle="tab">{{ lang('socialnetworks.title') }}</a></li>
                    </ul>

                    <div class="tab-content">
                        <?php
                        foreach (Dev::$array_fields as $field):
                            $values = array();
                            if (isset($old[$field])) $values = $old[$field];
                        ?>
                        <div class="tab-pane" id="{{ $field }}">
                            <p>{{ lang('developer.'.$field.'_help') }}</p>
                            {{ array_to_checkboxes($field, $values) }}
                        </div>
                        @endforeach

                        <div class="tab-pane" id="socialnetworks">
                            <?php
                            $options = get_array_lang(Config::get('vgc.socialnetworks'), 'socialnetworks.');
                            $options = array_merge(array('' => lang('common.select_first_option')), $options);

                            $values = array();
                            if (isset($old['socialnetworks'])) $values = clean_names_urls_array($old['socialnetworks']);
                            ?>
                            @for ($i = 0; $i < 4; $i++)
                                <?php
                                $name = isset($values['names'][$i]) ? $values['names'][$i] : '';
                                $url = isset($values['urls'][$i]) ? $values['urls'][$i] : '';
                                ?>
                                <div class="control-group-inline">
                                    {{ Former::select('socialnetworks[names][]', '')->options($options)->value($name) }}  
                                    {{ Former::url('socialnetworks[urls][]', '')->placeholder(lang('common.url'))->value($url) }}
                                </div>
                            @endfor

                        </div>
                    </div>
                </div><!-- /.tabable -->
                <!-- array items + socialnetworks -->
            </div> <!-- /.span -->
        </div> <!-- /.row -->

        <hr>

        {{ Former::primary_submit(lang('common.add_profile')) }}

    {{ Former::close() }}
</div><!-- /#adddeveloper --> 

@section('jQuery')
// from adddeveloper
$('#array_items_tabs a:first').tab('show');
@endsection
