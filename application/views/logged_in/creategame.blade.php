@section('page_title')
    {{ lang('vgc.game.add.title') }}
@endsection
<?php
$old = Input::old();
if ( ! empty($old)) Former::populate($old);

if (is_admin()) {
    $privacy = array_set_values_as_keys(Config::get('vgc.privacy'));
}
?>
<div id="addgame" class="profile-form create-profile-form">
    <h1>{{ lang('vgc.game.add.title') }}</h1>

    <hr>

    <?php
    $rules = Config::get('vgc.profiles_post_create_rules.game', array());
    ?>
    {{ Former::open_vertical(route('post_profile_create', array('game')))->rules($rules) }} 
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
                {{ Former::number('price', lang('vgc.game.price')) }}
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
                <ul class="nav nav-tabs" id="medias-tabs">
                    <li><a href="#links" data-toggle="tab">{{ lang('vgc.common.links') }}</a></li>
                    <li><a href="#screenshots" data-toggle="tab">{{ lang('vgc.common.screenshots') }}</a></li>
                    <li><a href="#videos" data-toggle="tab">{{ lang('vgc.common.videos') }}</a></li>
                </ul>

                <div class="tab-content">
                    <?php
                    $nu_text = array('links', 'screenshots', 'videos');
                    foreach ($nu_text as $fields):
                        if (isset($old[$fields])) $values = clean_names_urls_array($old[$fields]);
                        else $values = array();
                    ?>
                        <div class="tab-pane" id="{{ $fields }}">
                            <p>
                                @if ($fields == 'links')
                                    {{ lang('vgc.links.form_help') }} <br>
                                @endif
                                {{ lang('vgc.common.text_url_delete_help') }}

                            </p>

                            @for ($i = 0; $i < 4; $i++)
                                <div class="control-group-inline">
                                    <?php
                                    $name = isset($values['names'][$i]) ? $values['names'][$i] : '';
                                    $url = isset($values['urls'][$i]) ? $values['urls'][$i] : '';
                                    ?>
                                    {{ Former::text($fields.'[names][]', '')->value($name)->placeholder(lang('vgc.common.title')) }} 
                                    {{ Former::url($fields.'[urls][]', '')->value($url)->placeholder(lang('vgc.common.url')) }}
                                </div>
                            @endfor
                        </div> <!-- /.tab-pane -->
                    @endforeach
                </div> <!-- /.tab-content -->
            </div>
        </div> <!-- /.row -->

        <hr>

        {{ Former::primary_submit(lang('vgc.common.add_profile')) }}

    {{ Former::close() }}
</div> <!-- /#addgame --> 

@section('jQuery')
// from addgame
$('#array-fields-tabs a:first').tab('show');
$('#medias-tabs a:first').tab('show');
@endsection
