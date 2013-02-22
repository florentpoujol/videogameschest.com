@section('page_title')
    {{ lang('developer.edit.title') }}
@endsection

<?php
$profile_type = 'developer';
$profile = Dev::find($profile_id);
$profile->update_with_preview_data();

Former::populate($profile);

$old = Input::old();
if ( ! empty($old)) Former::populate($old);

if (is_admin()) {
    $users = User::get(array('id', 'username'));
    $privacy = array_set_values_as_keys(Config::get('vgc.privacy_and_reviews'));
}
?>
<div id="editdeveloper" class="profile-form update-profile-form">
    <h1>{{ lang('developer.edit.title') }} <small>{{ $profile->name }}</small></h1>
    
    <hr>

    <ul class="nav nav-tabs" id="main-tabs">
        <li><a href="#general-pane" data-toggle="tab">{{ lang('common.general') }}</a></li>
        <li><a href="#promote-pane" data-toggle="tab">{{ lang('promote.title') }}</a></li>
    </ul>

    <div class="tab-content">
        <div class="tab-pane" id="general-pane">
            <p class="pull-right">
                <a href="{{ route('get_profile_preview', array($profile_type, $profile->id)) }}">{{ lang('common.preview_profile_modifications') }}</a> | 
                <a href="{{ route('get_profile_view', array($profile_type, name_to_url($profile->name))) }}">{{ lang('common.view_profile_link') }}</a>
            </p>

            <?php
            $rules = Config::get('vgc.profiles_post_update_rules.developer', array());
            ?>
            {{ Former::open_vertical(route('post_profile_update', $profile_type))->rules($rules) }} 
                {{ Form::token() }}
                {{ Form::hidden('id', $profile_id) }}

                {{ Former::primary_submit(lang('common.update')) }} 

                <br>
                <br>
                <div class="alert alert-info">
                    {{ lang('vgc.profile.update_help') }}
                </div>

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
                        {{ Former::textarea('pitch', lang('developer.pitch'))->help(lang('common.markdown_help'))->class('span8') }}
                    </div>
                </div>

                <hr>

                <div class="row">
                    <div class="span4">
                        {{ Former::url('website', lang('common.website'))->placeholder(lang('common.url')) }}

                        {{ Former::url('blogfeed', lang('common.blogfeed'))->placeholder(lang('common.url'))->help(lang('common.blogfeed_help')) }}
                    </div>

                    <div class="span4">
                        {{ Former::email('email', lang('common.email')) }}

                        {{ Former::number('teamsize', lang('common.teamsize')) }}
                    </div>

                    <div class="span4">
                        {{ Former::url('presskit', lang('common.presskit'))->placeholder(lang('common.url')) }}

                        {{ Former::select('country')->options(get_array_lang(Config::get('vgc.countries'), 'countries.')) }}
                    </div>
                </div>

                <hr>

                <div class="row">
                    <div class="span12">
                        <!-- arrayitems + socialnetworks -->
                        <div class="tabbable tabs-left">
                            <ul class="nav nav-tabs nav-stacked" id="array_items_tabs">
                                @foreach (Dev::$array_fields as $field)
                                <li><a href="#{{ $field }}" data-toggle="tab">{{ lang($field.'.title') }}</a></li>
                                @endforeach

                                <li><a href="#socialnetworks" data-toggle="tab">{{ lang('socialnetworks.title') }}</a></li>
                            </ul>

                            <div class="tab-content">
                                <?php
                                foreach (Dev::$array_fields as $field):
                                    if (isset($old[$field])) $values = $old[$field];
                                    else $values = $profile->$field;
                                ?>
                                <div class="tab-pane" id="{{ $field }}">
                                    <p>{{ lang('developer.'.$field.'_help') }}</p>
                                    {{ array_to_checkboxes($field, $values) }}
                                </div>
                                @endforeach

                                <div class="tab-pane" id="socialnetworks">
                                    <?php
                                    $options = get_array_lang(Config::get('vgc.socialnetworks'), 'socialnetworks.');
                                    $options = array_merge(array('' => lang('common.select_arrayitem_first_option')), $options);

                                    if (isset($old['socialnetworks'])) $socialnetworks = clean_names_urls_array($old['socialnetworks']);
                                    else $socialnetworks = $profile->socialnetworks;

                                    $length = count($socialnetworks['names']);
                                    for ($i = 0; $i < $length; $i++):
                                    ?>
                                        <div class="control-group-inline">
                                            {{ Former::select('socialnetworks[names][]', '')->options($options)->forceValue($socialnetworks['names'][$i]) }} 
                                            {{ Former::url('socialnetworks[urls][]', '')->forceValue($socialnetworks['urls'][$i])->placeholder(lang('common.url')) }}
                                        </div>
                                    @endfor

                                    @for ($i = 0; $i < 4; $i++)
                                        <div class="control-group-inline">
                                            {{ Former::select('socialnetworks[names][]', '')->options($options) }} 
                                            {{ Former::url('socialnetworks[urls][]', '')->placeholder(lang('common.url')) }}
                                        </div>
                                    @endfor
                                </div>
                            </div>
                        </div>    <!-- /.tabable -->
                        <!-- array items + socialnetworks -->
                    </div> <!-- /.span -->
                </div> <!-- /.row -->

                <hr>

                {{ Former::primary_submit(lang('common.update')) }}

            {{ Former::close() }}
        </div> <!-- /#general-pane .tab-pane -->

        <div class="tab-pane" id="promote-pane">
            @if (is_standard_user())
                <p class="alert alert-info">
                    {{ lang('user.not_a_developer') }}
                </p>
            @elseif (is_admin() or is_developer())
                <h3>{{ lang('discover.feed_title') }}/{{ lang('discover.email_title') }}</h3>

                <?php
                //$profile = $dev;
                ?>
                @include('forms/promote_update_profile_subscription')
                
                <hr>
            @endif
        </div> <!-- /#promote-pane .tab-pane  -->
    </div> <!-- /.tab-content -->
</div><!-- /#editdeveloper --> 

@section('jQuery')
// from update developer
$('#main-tabs a:first').tab('show');
$('#array_items_tabs a:first').tab('show');
@endsection
