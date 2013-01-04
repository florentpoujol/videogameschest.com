@section('page_title')
    {{ lang('search.title') }}
@endsection

<?php 
/*Former::radios('words_search_mode', '')->radios(array(
'all or ' => array('value' => 'all'),
' any  ' => array('value' => 'any', 'checked'=>'checked'),
));*/


$old = Input::old();
if ( ! empty($old)) Former::populate($old);
?> 

<div id="search-form">
    <h2>{{ lang('search.title') }}</h2>
    
    <p>
        I am looking for a :
    </p>

    <ul class="nav nav-tabs" id="search_tabs">
        <li><a href="#developer-tab" data-toggle="tab">developer</a></li>
        <li><a href="#game-tab" data-toggle="tab">game</a></li>
    </ul>

    <div class="tab-content">
        <div class="tab-pane" id="developer-tab">
            {{ Former::open_vertical(route('post_search')) }}
                {{ Form::token() }}
                {{ Former::hidden('class', 'developer') }}

                <div class="row">
                    <div class="span2 offset1">
                        <?php 
                        echo Former::checkboxes('search_in', lang('search.name_or_pitch_help'))->checkboxes(array(
                        lang('common.name') => array('value' => 'name', 'name'=>'search_in', 'id'=>'dev_name'),
                        lang('common.pitch') => array('value' => 'pitch', 'name'=>"search_in", 'id'=>'dev_pitch'),
                        ));?>
                    </div>

                    <div class="span2">
                        <?php 
                        echo Former::radios('words_search_mode', lang('search.words_search_mode_help'))->radios(array(
                        lang('search.search_mode_all') => array('value' => ''),
                        lang('search.search_mode_any') => array('value' => 'or_', 'checked'=>'checked'),
                        ));?>
                    </div>

                    <div class="span2">
                        {{ Former::xlarge_text('words', lang('search.words_help')) }}
                    </div>
                </div>

                <hr>

                <div class="row">
                    <div class="row span12">
                        And whose games...<br><br>
                    </div>
                    
                    @foreach (Dev::$array_items as $item)
                        <?php
                        $items = Config::get('vgc.'.$item);
                        $options = get_array_lang($items, $item.'.');
                        
                        $values = array();
                        if (isset($old[$item])) $values = $old[$item];
                        
                        $size = count($items);
                        if ($size > 15) $size = 15;
                        ?>
                        <div class="span3">
                            <p>{{ lang('search.dev.'.$item.'_help') }}</p>
                            {{ Former::multiselect('arrayitems['.$item.']', '')->options($options)->value($values)->size($size) }}
                        </div>
                    @endforeach
                </div>

                <hr>

                {{ Former::submit(lang('search.dev.submit')) }}
            </form> 
        </div> <!-- /.tab-pane #developer-tab -->

        <div class="tab-pane" id="game-tab">
            {{ Former::open_vertical(route('post_search')) }}
                {{ Form::token() }}
                {{ Former::hidden('class', 'game') }}

            <!--
                <!-- array items 
                <div class="tabbable tabs-left">
                    <ul class="nav nav-tabs nav-stacked" id="array_items_dev_tabs">
                        <?php
                        $items = Dev::$array_items;
                        ?>
                        @foreach ($items as $item)
                        <li><a href="#{{ $item }}" data-toggle="tab">{{ lang($item.'.title') }}</a></li>
                        @endforeach
                    </ul>

                    <div class="tab-content">
                        @foreach (Dev::$array_items as $item)
                            <?php
                            $items = Config::get('vgc.'.$item);
                            $options = get_array_lang($items, $item.'.');
                            
                            $values = array();
                            if (isset($old[$item])) $values = $old[$item];
                            
                            $size = count($items);
                            if ($size > 15) $size = 15;
                            ?>
                            <div class="tab-pane" id="{{ $item }}">
                                <p>{{ lang('game.'.$item.'_help') }}</p>
                                {{ Former::multiselect('arrayitems['.$item.']', '')->options($options)->value($values)->size($size) }}
                            </div>
                        @endforeach
                    </div>
                </div> <!-- /.tabbable -->
                <!-- /array items -->

                <hr>

                {{ Former::checkbox('has_soundtrackurl', '')->text('Has a soundtrack url') }}

                <hr>

                {{ Former::submit(lang('search.dev.submit')) }}
        </div> <!-- /.tab-pane #game-tab -->
    </div> <!-- /.tab-content -->
</div> <!-- /.search-form -->

@section('jQuery')
$('#search_tabs a:first').tab('show');
$('#array_items_dev_tabs a:first').tab('show');

@endsection