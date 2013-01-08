<div class="row">
    <div class="span2">
        <?php 
        echo Former::checkboxes('', lang('search.name_or_pitch_help'))->checkboxes(array(
        lang('common.name') => array('value' => 'name', 'name'=>'search_in_name', 'id'=>'dev_name', 'checked'=>'checked'),
        lang('common.pitch') => array('value' => 'pitch', 'name'=>"search_in_pitch", 'id'=>'dev_pitch'),
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

    <div class="span3">
        <?php 
        /*echo Former::radios('words_search_mode', )->radios(array(
        lang('search.words_search_mode_whole') => array('value' => 'whole', 'checked'=>'checked'),
        lang('search.words_search_mode_part') => array('value' => 'part', ),
        ));*/?>

        <?php
        $options = array(
            'whole' => lang('search.words_search_mode_whole'),
            'part' => lang('search.words_search_mode_part'),
        );
        ?>

        {{-- Former::select('words_search_mode', lang('search.words_search_mode'))->options($options) --}}
    </div>
</div>
