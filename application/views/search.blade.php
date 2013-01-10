@section('page_title')
    {{ lang('search.title') }}
@endsection

<?php 
$old = Input::old();
if ( ! empty($old)) Former::populate($old);

if ( ! isset($search_data)) $search_data = array();
if ( ! empty($search_data)) Former::populate($search_data);

var_dump($search_data);

$default_tab = 'developer';
if (isset($old['class'])) $default_tab = $old['class'];
if (isset($search_data['class'])) $default_tab = $search_data['class'];
?> 

<div id="search-form">
    <h1>{{ lang('search.title') }}</h1>
    
    <hr>

    {{ Former::open_vertical(route('post_search')) }}
        {{ Form::token() }}
        <?php
        $options = array(
            'developer' => lang('common.developer'),
            'game' => lang('common.game'),
        );
        ?>
        {{ Former::select('class', 'I am looking for a :')->options($options) }}

        
        @include('partials.search_words')

        <hr>

        <div class="row">
            <div class="row span12">
                And whose games...<br><br>
            </div>
            
            @foreach (Dev::$array_fields as $item)
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

        {{ Former::primary_submit(lang('search.game.submit')) }}
    </form> 
<!-- 
    <ul class="nav nav-tabs" id="search_tabs">
        <li><a href="#developer-tab" data-toggle="tab">developer</a></li>
        <li><a href="#game-tab" data-toggle="tab">game</a></li>
    </ul>

    <div class="tab-content">
        <div class="tab-pane" id="developer-tab">
            {{ Former::open_vertical(route('post_search')) }}
                {{ Form::token() }}
                {{ Former::hidden('class', 'developer')->forceValue('developer') }}
                <?php $class = 'developer'; ?>

                @include('partials.search_words')

                <hr>

                <div class="row">
                    <div class="row span12">
                        And whose games...<br><br>
                    </div>
                    
                    @foreach (Dev::$array_fields as $field)
                        <?php
                        $items = Config::get('vgc.'.$field);
                        $options = get_array_lang($items, $field.'.');
                        
                        $values = array();
                        if (isset($old['arrayitems']) &&
                            isset($old['arrayitems'][$field])
                        ) {
                            $values = $old[$field];
                        }

                        if (isset($search_data['arrayitems']) && 
                            isset($search_data['arrayitems'][$field])
                        ) {
                            $values = $search_data['arrayitems'][$field];
                        }
                        
                        $size = count($items);
                        if ($size > 15) $size = 15;
                        ?>
                        <div class="span3">
                            <p>{{ lang('search.dev.'.$field.'_help') }}</p>
                            {{ Former::multiselect($class.'_arrayitems['.$field.']', '')->options($options)->value($values)->size($size) }}
                            {{-- array_to_checkboxes($field, $values) }}
                        </div>
                    @endforeach
                </div>

                {{ Former::primary_submit(lang('search.dev.submit')) }}
            </form>
        </div> <!-- /.tab-pane #developer-tab --

        <div class="tab-pane" id="game-tab">
            {{ Former::open_vertical(route('post_search')) }}
                {{ Form::token() }}
                {{ Former::hidden('class', 'game')->forceValue('game') }}

                <?php $class = 'game'; ?>
                @include('partials.search_words')

                <hr>

                <div class="row">
                    <div class="row span12">
                        And whose games...<br><br>
                    </div>
                    
                    @foreach (Dev::$array_fields as $item)
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
                            {{ Former::multiselect($class.'_arrayitems['.$item.']', '')->options($options)->value($values)->size($size) }}
                        </div>
                    @endforeach
                </div>

                {{ Former::primary_submit(lang('search.game.submit')) }}
            </form> 
        </div> <!-- /.tab-pane #game-tab --
    </div> <!-- /.tab-content -- -->
</div> <!-- /#search-form -->

@if (isset($profiles))
    <hr>

    @include('profile_list')
@endif 
{{-- if (isset($profiles)) --}}


@section('jQuery')
$('#search_tabs a[href="#{{ $default_tab }}-tab"]').tab('show');
$('#array_items_dev_tabs a:first').tab('show');
@endsection