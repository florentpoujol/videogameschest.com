@section('page_title')
    {{ lang('developer.add.title') }}
@endsection

<?php
$rules = array(
    'name' => 'required|min:5',
    'email' => 'min:5|email',
    'logo' => 'url',
    'website' => 'url',
    'blogfeed' => 'url',
    'presskit' => 'url',
    'teamsize' => 'min:1'
);

$old = Input::old();
if ( ! empty($old)) Former::populate($old);

if (is_admin()) {
    $users = User::get(array('id', 'username'));
    $privacy = array_set_values_as_keys(Config::get('vgc.privacy_and_reviews'));
}
?>
<div id="adddeveloper">
    <h2>{{ lang('developer.add.title') }}</h2>

    <hr>

    <div class="row">
        {{ Former::open_vertical(route('post_adddeveloper'))->rules($rules) }}
            {{ Form::token() }}

            <div class="span5">
                {{ Former::text('name', lang('common.name'))->help(lang('developer.name_help')) }}

                {{ Former::email('email', lang('developer.email')) }}

                @if (is_admin())
                    {{ Former::select('user_id', 'User')->fromQuery($users)  }}

                    {{ Former::select('privacy')->options($privacy) }}
                @endif
                
                {{ Former::textarea('pitch', lang('developer.pitch'))->placeholder(lang('common.bbcode_explanation')) }}

                {{ Former::url('logo', lang('common.logo'))->placeholder(lang('common.url')) }}
                {{ Former::url('website', lang('common.website'))->placeholder(lang('common.url')) }}
                {{ Former::url('blogfeed', lang('common.blogfeed'))->placeholder(lang('common.url')) }}
                {{ Former::url('presskit', lang('common.presskit'))->placeholder(lang('common.url')) }}

                {{ Former::number('teamsize', lang('common.teamsize'))->value(1) }}

                {{ Former::select('country', lang('common.country'))->options(get_array_lang(Config::get('vgc.countries'), 'countries.')) }}

            </div> <!-- /.span -->

            <div class="span7">
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
                            $options = array_merge(array('' => lang('common.select-first-option')), $options);

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
                
                <hr>

                <input type="submit" value="{{ lang('developer.add.submit') }}" class="btn btn-primary clearfix">
            </div> <!-- /.span -->
        </form>
    </div> <!-- /.row -->
</div><!-- /#adddeveloper --> 

@section('jQuery')
// from adddeveloper
$('#array_items_tabs a:first').tab('show');
@endsection
