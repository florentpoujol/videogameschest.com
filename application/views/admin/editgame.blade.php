<?php
$rules = array(
    'name' => 'required|min:5',
    'developer_id' => 'required|exists:developers,id',
    'cover' => 'url',
    'website' => 'url',
    'blogfeed' => 'url',
    'soundtrackurl' => 'url',
    'publishername' => 'min:2',
    'publisherurl' => 'url|required_with:publishername',
    'price' => 'min:0',
);

$game = Game::find($profile_id);
Former::populate($game);

$old = Input::old();
if ( ! empty($old)) Former::populate($old);
?>

<div id="editgame">
    <h2>{{ lang('game.edit.title') }}</h2>

    {{ Former::open_vertical('admin/editgame')->rules($rules) }} 
        {{ Form::token() }}
        {{ Form::hidden('id', $profile_id) }}
        
        {{ Former::text('name', lang('common.name')) }}

        @if (IS_ADMIN)
            {{ Former::select('developer_id', lang('common.developer'))->fromQuery(Dev::all()) }}
        @else
            You are the developer of this game. <br>
            {{ Former::hidden('developer_id', DEV_PROFILE_ID) }}
        @endif

        {{ Former::select('devstate', lang('game.devstate'))->options(get_array_lang(Config::get('vgc.developmentstates'), 'developmentstates.'))->value('released') }}

        {{ Former::textarea('pitch', lang('game.pitch')) }}

        {{ Former::url('cover', lang('game.cover')) }}
        {{ Former::url('website', lang('common.website')) }}
        {{ Former::url('blogfeed', lang('common.blogfeed')) }}
        {{ Former::url('soundtrackurl', lang('game.soundtrackurl')) }}

        <div class="control-group-inline">
            {{ Former::text('publishername', lang('game.publishername')) }}
            {{ Former::url('publisherurl', lang('game.publisherurl')) }}
        </div>
        <div class="clearfix"></div>

        <hr>

        <!-- array items -->
        <div class="tabbable tabs-left">
            <ul class="nav nav-tabs nav-stacked" id="array_items_tabs">
                @foreach (Game::$array_items as $item)
                <li><a href="#{{ $item }}" data-toggle="tab">{{ lang($item.'.title') }}</a></li>
                @endforeach
            </ul>

            <div class="tab-content">
                <?php
                foreach (Game::$array_items as $item):
                    $items = Config::get('vgc.'.$item);
                    $options = get_array_lang($items, $item.'.');

                    if (isset($old[$item])) $values = $old[$item];
                    else $values = $game->$item;
                    // why do I need this ?
                    // using populate will not work because it doesn't work with multiselect

                    $size = count($items);
                    if ($size > 10 ) $size = 10;
                ?>
                <div class="tab-pane" id="{{ $item }}">
                    <p>{{ lang('game.'.$item.'_help') }}</p>
                    {{ Former::multiselect($item.'[]', '')->options($options)->value($values)->size($size)}}
                </div>
                @endforeach
            </div>
        </div> <!-- /.tabbable -->
        <!-- /array items -->
        
        <hr>

        <!-- names urls items -->
        <ul class="nav nav-tabs" id="nu_items_tabs">
            @foreach (Game::$names_urls_items as $item)
            <li><a href="#{{ $item }}" data-toggle="tab">{{ lang('common.'.$item) }}</a></li>
            @endforeach
        </ul>

        <div class="tab-content">
            <?php
            // name url select
            $nu_select = array('socialnetworks', 'stores'); 
            foreach ($nu_select as $item):
            ?>
                <div class="tab-pane" id="{{ $item }}">
                    <?php
                    $options = get_array_lang(Config::get('vgc.'.$item), $item.'.');
                    $options = array_merge(array('' => lang('common.select-first-option')), $options);
                    
                    if (isset($old[$item])) $values = clean_names_urls_array($old[$item]);
                    else $values = $game->$item;

                    $length = count($values['names']);
                    for ($i = 0; $i < $length; $i++):
                    ?>
                        <div class="control-group-inline">
                            {{ Former::select($item.'[names][]', '')->options($options)->value($values['names'][$i]) }} 
                            {{ Former::url($item.'[urls][]', '')->value($values['urls'][$i])->placeholder(lang('common.url')) }}
                        </div>
                    @endfor

                    @for ($i = 0; $i < 4; $i++)
                        <div class="control-group-inline">
                            {{ Former::select($item.'[names][]', '')->options($options) }} 
                            {{ Former::url($item.'[urls][]', '')->placeholder(lang('common.url')) }}
                        </div>
                    @endfor
                </div>
            @endforeach

            <?php
            $nu_text = array('screenshots', 'videos');
            foreach ($nu_text as $item):
            ?>
                <div class="tab-pane" id="{{ $item }}">
                    <p>
                        {{ lang('common.text-url-delete-help') }}
                    </p>

                    <?php
                    if (isset($old[$item])) $values = clean_names_urls_array($old[$item]);
                    else $values = $game->$item;

                    $length = count($values['names']);
                    for ($i = 0; $i < $length; $i++):
                    ?>
                        <div class="control-group-inline">
                            {{ Former::text($item.'[names][]', '')->value($values['names'][$i])->placeholder(lang('common.title')) }} 
                            {{ Former::url($item.'[urls][]', '')->value($values['urls'][$i])->placeholder(lang('common.url')) }}
                        </div>
                    @endfor

                    @for ($i = 0; $i < 4; $i++)
                        <div class="control-group-inline">
                            {{ Former::text($item.'[names][]', '')->placeholder(lang('common.title')) }} 
                            {{ Former::url($item.'[urls][]', '')->placeholder(lang('common.url')) }}
                        </div>
                    @endfor
                </div>
            @endforeach
        </div> <!-- /.tab-content -->
        <!-- /names url items -->

        <hr>

        <input type="submit" value="{{ lang('game.edit.submit') }}" class="btn btn-primary">
    </form>
</div> <!-- /#editgame --> 

@section('jQuery')
// from addgame
$('#array_items_tabs a').click(function (e) {
  e.preventDefault();
  $(this).tab('show');
})
$('#array_items_tabs a:first').tab('show');

$('#nu_items_tabs a').click(function (e) {
  e.preventDefault();
  $(this).tab('show');
})
$('#nu_items_tabs a:first').tab('show');
// from addgame
@endsection
