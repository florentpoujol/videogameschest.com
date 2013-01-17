{{ Former::open_vertical(route('post_search')) }}
    {{ Form::token() }}
    
    {{ Former::primary_submit(lang('search.submit')) }}

    <hr>
    
    <div class="row">
        <div class="span3">
            <?php
            $options = array(
                'developer' => lang('common.developers'),
                'game' => lang('common.games'),
            );
            ?>
            {{ Former::select('class', lang('search.looking_for'))->options($options) }}
        </div>

        <div class="span2">
            <?php 
            echo Former::checkboxes('', lang('search.name_or_pitch_help'))->checkboxes(array(
            lang('common.name') => array('value' => 'name', 'name'=> 'search_in_name', 'id'=>'dev_name', 'checked'=>'checked'),
            lang('common.pitch') => array('value' => 'pitch', 'name'=> 'search_in_pitch', 'id'=>'dev_pitch'),
            ));?>
        </div>

        <div class="span3">
            <?php
            $options = array(
                'all' => lang('search.words_contains_all'),
                'any' => lang('search.words_contains_any'),
            );
            ?>

            {{ Former::select('words_contains', lang('search.words_contains'))->options($options)->width('0.5em') }}
        </div>

        <div class="span2">
            {{ Former::text('words_list', lang('search.words_list')) }}
        </div>
    </div>

    <hr>

    <div class="row">
        <div class="span12">
        And whose games / And which ... <br> <br>
        </div>
    </div>

    {{-- dev fields :  }}
    <?php
    $fields = array('devices', 'operatingsystems','technologies', 'stores'  );
    ?>
    <div class="row">
        
        @include('partials.search_foreach_fields')
    </div>

    <div class="accordion" id="search-array-items-accordion">
        <div class="accordion-group">
            <div class="accordion-heading">
                <a class="accordion-toggle" data-toggle="collapse" data-parent="search-array-items-accordion" href="#collapse-game">
                    {{ lang('search.game_accordion_link') }}
                </a>
            </div>

            <div id="collapse-game" class="accordion-body collapse out">
                <div class="accordion-inner">
                    <?php
                    $fields = array('genres', 'themes', 'tags', 'viewpoints', 'nbplayers', 'languages', );
                    ?>
                    <div class="row">
                        
                        @include('partials.search_foreach_fields')
                    </div>

                    
                </div>
            </div> <!-- /.accordion-body -->
        </div> <!-- /.accordion-group -->
    </div> <!-- /.accordion #search-array-items-accordion -->
    
    {{ Former::primary_submit(lang('search.submit')) }}
</form> 