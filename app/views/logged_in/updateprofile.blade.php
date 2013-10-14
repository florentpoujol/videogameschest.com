@section('page_title')
    {{ lang('profile.edit.title') }}
@endsection
<?php

$profile = Profile::find($profile_id);
Former::populate($profile);

$old = Input::old();
if ( ! empty($old)) Former::populate($old);

$tags = Tag::all();
$tags_formatted = array();
foreach ($tags->toArray() as $key => $value) { 
    $tags_formatted[ $value['id'] ] = $value['name'];
}

$profile_tags = array();
foreach ($profile->tags->toArray() as $key => $value) { 
    $profile_tags[] = $value['id'];
}
?>

<div id="editgame" class="profile-form update-profile-form">
    <h1>{{ lang('profile.edit.title') }} <small>{{ xssSecure($profile->name) }} </small></h1>

    <hr>

    <p class="pull-right">
        <a href="{{ route('get_profile_view', array($profile->id)) }}">{{ lang('common.view_profile_link') }}</a>
    </p>

    <?php
    $rules = Config::get('profiles_post_update_rules', array());
    ?>
    {{ Former::open_vertical(route('post_profile_update'))->rules($rules) }} 
        {{ Form::token() }}
        {{ Form::hidden('id', $profile->id) }}

        {{ Former::primary_submit(lang('common.update')) }}

        <hr>

        TODO : Checkbox for privacy <br>

        <hr>
        
        <div class="row">
            <div class="span4">
                {{ Former::text('name', lang('common.name')) }}
            
                {{ Former::date('release_date', lang('profile.release_date'))->help(lang('profile.release_date_help')) }}
            </div>

            <div class="span8">
                {{ Former::textarea('description', lang('profile.pitch')) }}
            </div>
        </div>

        <hr>

        <div class="row">
            <div class="span6">
                {{ Former::select('tags[]', 'Tags')->options($tags_formatted, $profile_tags)->id("tags")->multiple()->size(12) }}
            </div>

            <div class="span6">
                <!-- names urls items -->
                <ul class="nav nav-tabs" id="medias-tabs">
                    <li><a href="#links" data-toggle="tab">{{ lang('common.links') }}</a></li>
                    <li><a href="#medias" data-toggle="tab">{{ lang('common.medias') }}</a></li>
                </ul>

                <div class="tab-content">
                    <?php
                    $nu_text = array('links', 'medias');
                    foreach ($nu_text as $field):
                    ?>
                        <div class="tab-pane" id="{{ $field }}">
                            <p>
                                {{ lang('common.text_url_delete_help') }}
                            </p>

                            <?php
                            if (isset($old[$field])) $values = clean_names_urls_array($old[$field]);
                            else $values = $profile->$field;
                            
                            $length = count($values);
                            for ($i = 0; $i < $length; $i++):
                            ?>

                                <div class="control-group-inline">
                                    {{ Former::text($field.'['.$i.'][name]', '')->value($values[$i]['name'])->placeholder(lang('common.title')) }} 
                                    {{ Former::url($field.'['.$i.'][url]', '')->value($values[$i]['url'])->placeholder(lang('common.url')) }}
                                </div>
                            @endfor

                            @for ($i = $length; $i < $length+4; $i++)
                                <div class="control-group-inline">
                                    {{ Former::text($field.'['.$i.'][name]', '')->placeholder(lang('common.title')) }} 
                                    {{ Former::url($field.'['.$i.'][url]', '')->placeholder(lang('common.url')) }}
                                </div>
                            @endfor
                        </div>
                    @endforeach
                </div> <!-- /.tab-content -->
            </div> <!-- /.span6 -->
        </div> <!-- /.row -->

        <hr>

        {{ Former::primary_submit(lang('common.update')) }}

    {{ Former::close() }}
</div> <!-- /#editgame --> 

@section('jQuery')
// from update profile
$('#medias-tabs a:first').tab('show');
@endsection
