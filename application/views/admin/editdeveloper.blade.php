@section('page_title')
    {{ lang('developer.edit.title') }}
@endsection

<?php
$rules = array(
    'name' => 'required|no_slashes|min:2',
    'email' => 'email',
    'logo' => 'url',
    'website' => 'url',
    'blogfeed' => 'url',
    'presskit' => 'url',
    'teamsize' => 'integer|min:1'
);

$dev = Dev::find($profile_id);
Former::populate($dev);

$old = Input::old();
if ( ! empty($old)) Former::populate($old);

if (is_admin()) {
    $users = User::get(array('id', 'username'));
    $privacy = array_set_values_as_keys(Config::get('vgc.privacy_and_reviews'));
}
?>
<div id="editdeveloper">
    <h1>{{ lang('developer.edit.title') }} <small>{{ $dev->name }}</small></h1>
    
    <hr>

    <p class="pull-right">
        <a href="{{ route('get_developer', array(name_to_url($dev->name))) }}">{{ icon('eye-open') }} {{ lang('common.view_profile_link') }}</a>
    </p>


    {{ Former::open_vertical(route('post_editdeveloper'))->rules($rules) }} 
        {{ Form::token() }}
        {{ Form::hidden('id', $profile_id) }}

        {{ Former::primary_submit(lang('common.edit_profile')) }} 

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
            </div>

            <div class="span8">
                {{ Former::textarea('pitch', lang('developer.pitch'))->help(lang('common.bbcode_explanation'))->class('span8') }}
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
                            else $values = $dev->$field;
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
                            else $socialnetworks = $dev->socialnetworks;

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

        {{ Former::primary_submit(lang('common.edit_profile')) }}

    {{ Former::close() }}
</div><!-- /#editdeveloper --> 

@section('jQuery')
// from editdeveloper
$('#array_items_tabs a:first').tab('show');
@endsection
