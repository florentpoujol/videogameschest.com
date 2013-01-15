@section('page_title')
    {{ lang('developer.edit.title') }}
@endsection

<?php
$rules = array(
    'name' => 'required|min:5',
    'logo' => 'url',
    'website' => 'url',
    'blogfeed' => 'url',
    'presskit' => 'url',
    'teamsize' => 'min:1'
);

$dev = Dev::find($profile_id);
Former::populate($dev);

$old = Input::old();
if ( ! empty($old)) Former::populate($old);
?>
<div id="editdeveloper">
    <h1>{{ lang('developer.edit.title') }}</h1>
    
    <hr>

    <div class="row">
        {{ Former::open_vertical(route('post_editdeveloper'))->rules($rules) }} 
            {{ Form::token() }}
            {{ Form::hidden('id', $profile_id) }}

            <div class="span5">
                {{ Former::text('name', lang('common.name'))->help(lang('developer.name_help')) }}
                {{ Former::text('email', lang('common.email')) }}

                {{ Former::textarea('pitch', lang('developer.pitch'))->help(lang('common.bbcode_explanation')) }}

                {{ Former::url('logo', lang('common.logo'))->placeholder(lang('common.url')) }}
                {{ Former::url('website', lang('common.website'))->placeholder(lang('common.url')) }}
                {{ Former::url('blogfeed', lang('common.blogfeed'))->placeholder(lang('common.url')) }}
                {{ Former::url('presskit', lang('common.presskit'))->placeholder(lang('common.url')) }}

                {{ Former::number('teamsize', lang('common.teamsize')) }}

                {{ Former::select('country')->options(get_array_lang(Config::get('vgc.countries'), 'countries.')) }}

            </div> <!-- /.span -->

            <div class="span7">
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

                <hr>

                <input type="submit" value="{{ lang('developer.edit.submit') }}" class="btn btn-primary">
            </div> <!-- /.span -->
        </form>
    </div> <!-- /.row -->
</div><!-- /#editdeveloper --> 

@section('jQuery')
// from editdeveloper
$('#array_items_tabs a:first').tab('show');
@endsection
