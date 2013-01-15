@section('page_title')
    {{ lang('game.add.title') }}
@endsection
<?php
$rules = array(
    'name' => 'required|min:5',
    'developer_name' => 'required|min:5',
    'cover' => 'url',
    'website' => 'url',
    'blogfeed' => 'url',
    'soundtrackurl' => 'url',
    'publishername' => 'min:2|alpa',
    'publisherurl' => 'url|required_with:publishername',

);

$old = Input::old();
if ( ! empty($old)) Former::populate($old);


if (is_admin()) {
    $devs = Dev::get(array('id', 'name'));
    $privacy = array_set_values_as_keys(Config::get('vgc.privacy_and_reviews'));
}
else $devs = Dev::where_privacy('public')->get(array('id', 'name'));

/*$old_devs = $devs;
$devs = array(lang('common.select_first_option'));
foreach ($old_devs as $dev) {
    $devs[$dev->id] = $dev;
}*/
?>
<div id="addgame">
    <h1>{{ lang('game.add.title') }}</h1>

    <hr>

    {{ Former::open_vertical(route('post_addgame'))->rules($rules) }} 
        {{ Form::token() }}

        <div class="row">
            <div class="span5">
                {{ Former::text('name', lang('common.name')) }}

                {{ Former::text('developer_name', lang('common.developer'))->useDatalist($devs, 'name') }}
                
                @if (is_admin())
                    {{ Former::select('privacy')->options($privacy) }}
                @endif

                {{ Former::select('devstate', lang('game.devstate'))->options(get_array_lang(Config::get('vgc.developmentstates'), 'developmentstates.'))->value('released') }}

                {{ Former::textarea('pitch', lang('game.pitch'))->placeholder(lang('common.bbcode_explanation')) }}

                {{ Former::url('cover', lang('game.cover'))->placeholder(lang('common.url')) }}
                {{ Former::url('website', lang('common.website'))->placeholder(lang('common.url')) }}
                {{ Former::url('blogfeed', lang('common.blogfeed'))->placeholder(lang('common.url')) }}
                {{ Former::url('presskit', lang('common.presskit'))->placeholder(lang('common.url')) }}
                {{ Former::url('soundtrackurl', lang('game.soundtrackurl'))->placeholder(lang('common.url')) }}

                <div class="control-group-inline">
                    {{ Former::text('publishername', lang('game.publishername')) }}
                    {{ Former::url('publisherurl', lang('game.publisherurl'))->placeholder(lang('common.url')) }}
                </div>  
            </div> <!-- /.span5 -->

            <div class="span7">
                <!-- array items -->
                <div class="tabbable tabs-left">
                    <ul class="nav nav-tabs nav-stacked" id="array_items_tabs">
                        @foreach (Game::$array_fields as $item)
                        <li><a href="#{{ $item }}" data-toggle="tab">{{ lang($item.'.title') }}</a></li>
                        @endforeach
                    </ul>

                    <div class="tab-content">
                        @foreach (Game::$array_fields as $item)
                            <?php
                             $values = array();
                            if (isset($old[$item])) $values = $old[$item];
                            ?>
                            <div class="tab-pane" id="{{ $item }}">
                                <p>{{ lang('game.'.$item.'_help') }}</p>

                                {{ array_to_checkboxes($item, $values) }}
                            </div>
                        @endforeach
                    </div>
                </div> <!-- /.tabbable -->
                <!-- /array items -->

                <hr>

                <!-- names urls items -->
                <ul class="nav nav-tabs" id="nu_items_tabs">
                    @foreach (Game::$names_urls_fields as $item)
                    <li><a href="#{{ $item }}" data-toggle="tab">{{ lang('common.'.$item) }}</a></li>
                    @endforeach
                </ul>

                <div class="tab-content">
                    <?php
                    $nu_select = array('socialnetworks', 'stores'); // name url select
                    foreach ($nu_select as $item):
                        $options = get_array_lang(Config::get('vgc.'.$item), $item.'.');
                        $options = array_merge(array('' => lang('common.select_first_option')), $options);

                        $values = array();
                        if (isset($old[$item])) $values = clean_names_urls_array($old[$item]);
                    ?>
                        <div class="tab-pane" id="{{ $item }}">
                            @for ($i = 0; $i < 4; $i++)
                                <?php
                                $name = isset($values['names'][$i]) ? $values['names'][$i] : '';
                                $url = isset($values['urls'][$i]) ? $values['urls'][$i] : '';
                                ?>
                                <div class="control-group-inline">
                                    {{ Former::select($item.'[names][]', '')->options($options)->value($name) }} 
                                    {{ Former::url($item.'[urls][]', '')->value($url)->placeholder(lang('common.url'))  }}
                                </div>
                            @endfor
                        </div>
                    @endforeach

                    <?php
                    $nu_text = array('screenshots', 'videos', 'reviews');
                    foreach ($nu_text as $item):
                        $values = array();
                        if (isset($old[$item])) $values = clean_names_urls_array($old[$item]);
                    ?>
                        <div class="tab-pane" id="{{ $item }}">
                            @for ($i = 0; $i < 4; $i++)
                                <?php
                                $name = isset($values['names'][$i]) ? $values['names'][$i] : '';
                                $url = isset($values['urls'][$i]) ? $values['urls'][$i] : '';
                                ?>
                                <div class="control-group-inline">
                                    {{ Former::text($item.'[names][]', '')->value($name)->placeholder(lang('common.title')) }} 
                                    {{ Former::url($item.'[urls][]', '')->value($url)->placeholder(lang('common.url')) }}
                                </div>
                            @endfor
                        </div>
                    @endforeach
                </div> <!-- /.tab-content -->
                <!-- /names url items -->
                <hr>

                <input type="submit" value="{{ lang('game.add.submit') }}" class="btn btn-primary">
            </div> <!-- /.span -->
        </div> <!-- /.row -->
    </form>
</div> <!-- /#addgame --> 

@section('jQuery')
// from addgame

$('#array_items_tabs a:first').tab('show');


$('#nu_items_tabs a:first').tab('show');
// from addgame
@endsection
