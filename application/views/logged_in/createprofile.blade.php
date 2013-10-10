@section('page_title')
    {{ lang('profile.add.title') }}
@endsection
<?php
$old = Input::old();
if ( ! empty($old)) Former::populate($old);

?>
<div id="addprofile" class="profile-form create-profile-form">
    <h1>{{ lang('profile.add.title') }}</h1>

    <hr>

    <?php
    $rules = Config::get('profiles_post_create_rules', array());
    ?>
    {{ Former::open_vertical(route('post_profile_create'))->rules($rules) }} 
        {{ Form::token() }}

        {{ Former::primary_submit(lang('common.add_profile')) }}

        <hr>

        <div class="row">
            <div class="span4">
                {{ Former::text('name', lang('common.name')) }}
            </div> 
        </div>

        <hr>

        <div class="row">
            <div class="span4">
                {{ Former::textarea('description', lang('profile.pitch')) }}
            </div>

            <div class="span4">
                {{ Former::date('release_date', lang('profile.release_date'))->help(lang('profile.release_date_help')) }}
            </div>
        </div> <!-- /.row -->

        <hr>

        <div class="row">
            <div class="span6">
                Tags <br>
            </div>
            
            <div class="span6">
                <ul class="nav nav-tabs" id="medias-tabs">
                    <li><a href="#links" data-toggle="tab">{{ lang('common.links') }}</a></li>
                    <li><a href="#medias" data-toggle="tab">{{ lang('common.medias') }}</a></li>
                </ul>

                <div class="tab-content">
                    <?php
                    $nu_text = array('links', 'medias');
                    foreach ($nu_text as $field):
                        if (isset($old[$field])) $values = clean_names_urls_array($old[$field]);
                        else $values = array();
                    ?>
                        <div class="tab-pane" id="{{ $field }}">
                            <p>
                                @if ($field == 'links')
                                    {{ lang('links.form_help') }} <br> <br>
                                @endif
                                {{ lang('common.text_url_delete_help') }}

                            </p>

                            @for ($i = 0; $i < 4; $i++)
                                <div class="control-group-inline">
                                    <?php
                                    $name = isset($values[$i]) ? $values[$i]['name'] : '';
                                    $url = isset($values[$i]) ? $values[$i]['url'] : '';
                                    ?>
                                    {{ Former::text($field.'['.$i.'][name]', '')->value($name)->placeholder(lang('common.title')) }} 
                                    {{ Former::url($field.'['.$i.'][url]', '')->value($url)->placeholder(lang('common.url')) }}
                                </div>
                            @endfor
                        </div> <!-- /.tab-pane -->
                    @endforeach
                </div> <!-- /.tab-content -->
            </div>
        </div> <!-- /.row -->

        <hr>

        {{ Former::primary_submit(lang('common.add_profile')) }}

    {{ Former::close() }}
</div> <!-- /#addgame --> 

@section('jQuery')
// from addprofile
$('#medias-tabs a:first').tab('show');
@endsection
