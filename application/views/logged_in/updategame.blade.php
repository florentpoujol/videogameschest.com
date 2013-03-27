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
    $privacy = array_set_values_as_keys(Config::get('vgc.privacy'));
}
?>

<div id="editgame" class="profile-form update-profile-form">
    <h1>{{ lang('vgc.game.edit.title') }} <small>{{ xssSecure($profile->name) }} </small></h1>

    <hr>

    <p class="pull-right">
        <a href="{{ route('get_profile_preview', array($profile_type, $profile->id)) }}">{{ lang('vgc.common.preview_profile_modifications') }}</a> | 
        <a href="{{ route('get_profile_view', array($profile_type, name_to_url($profile->name))) }}">{{ lang('vgc.common.view_profile_link') }}</a>
    </p>

    <?php
    $rules = Config::get('vgc.profiles_post_update_rules.game', array());
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
            </div>
        </div>

        <div class="row">
            <div class="span4">
                {{ Former::text('developer_name', lang('vgc.common.developer_name')) }}
            </div>

            <div class="span4">
                {{ Former::url('developer_url', lang('vgc.common.developer_url'))->placeholder(lang('vgc.common.url')) }}
            </div>

            <div class="span4">
            <?php
            $countries = array_merge(
                array('' => lang('vgc.common.select_first_option')),
                get_array_lang(Config::get('vgc.countries'), 'countries.')
            );
            ?>
            {{ Former::select('country', lang('vgc.game.country'))->options($countries) }}
            </div>
        </div> <!-- /.row -->

        <hr>

        <div class="row">
            <div class="span4">
                {{ Former::textarea('pitch', lang('vgc.game.pitch')) }}
            </div>

            <div class="span4">
                {{ Former::text('price', lang('vgc.common.price'))->help(lang('vgc.game.price_help')) }}
            </div>

            <div class="span4">
                {{ Former::date('release_date', lang('vgc.game.release_date'))->help(lang('vgc.game.release_date_help')) }}
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
                <ul class="nav nav-tabs" id="medias-tabs">
                    <li><a href="#links" data-toggle="tab">{{ lang('vgc.common.links') }}</a></li>
                    <li><a href="#screenshots" data-toggle="tab">{{ lang('vgc.common.screenshots') }}</a></li>
                    <li><a href="#videos" data-toggle="tab">{{ lang('vgc.common.videos') }}</a></li>
                </ul>

                <div class="tab-content">
                    <?php
                    $nu_text = array('links', 'screenshots', 'videos');
                    foreach ($nu_text as $field):
                    ?>
                        <div class="tab-pane" id="{{ $field }}">
                            <p>
                                @if ($field == 'links')
                                    {{ lang('vgc.links.form_help') }} <br> <br>
                                @endif
                                {{ lang('vgc.common.text_url_delete_help') }}
                            </p>

                            <?php
                            if (isset($old[$field])) $values = clean_names_urls_array($old[$field]);
                            else $values = $profile->$field;
                            
                            $length = count($values);
                            for ($i = 0; $i < $length; $i++):
                            ?>

                                <div class="control-group-inline">
                                    {{ Former::text($field.'['.$i.'][name]', '')->value($values[$i]['name'])->placeholder(lang('vgc.common.title')) }} 
                                    {{ Former::url($field.'['.$i.'][url]', '')->value($values[$i]['url'])->placeholder(lang('vgc.common.url')) }}
                                </div>
                            @endfor

                            @for ($i = $length; $i < $length+4; $i++)
                                <div class="control-group-inline">
                                    {{ Former::text($field.'['.$i.'][name]', '')->placeholder(lang('vgc.common.title')) }} 
                                    {{ Former::url($field.'['.$i.'][url]', '')->placeholder(lang('vgc.common.url')) }}
                                </div>
                            @endfor
                        </div>
                    @endforeach
                </div> <!-- /.tab-content -->
            </div> <!-- /.span6 -->
        </div> <!-- /.row -->

        <hr>

        {{ Former::primary_submit(lang('vgc.common.update')) }}

    {{ Former::close() }}
</div> <!-- /#editgame --> 

@section('jQuery')
// from update game
$('#main-tabs a:first').tab('show');
$('#array-fields-tabs a:first').tab('show');
$('#general-nu-text-tabs a:first').tab('show');
$('#medias-tabs a:first').tab('show');
@endsection
