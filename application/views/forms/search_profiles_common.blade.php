<?php
if ( ! isset($search_data)) $search_data = array();
if ( ! empty($search_data)) {
    if (isset($search_data['profile_type']) && $search_data['profile_type'] == $profile_type) {
        Former::populate($search_data);
    }
}
?>
{{ Former::open_vertical(route('post_search')) }}
    {{ Form::token() }}
    
    {{ Former::primary_submit(lang('vgc.search.submit_category')) }}
    <hr>
    
    <div class="row">
        {{ Former::hidden('profile_type', $profile_type) }}

        <div class="span2">
            <i class="icon-quote-left icon-2x pull-left icon-muted"></i>

            {{ lang('vgc.search.i_am_looking_for', array('profile_type' => $profile_type)) }}
        </div>
        
        <div class="span2">
            <?php 
            $name = array(
                'value' => 'name',
                'id' => $profile_type.'_dev_name',
            );

            if (isset($search_data['search_words_in']) && in_array('name', $search_data['search_words_in'])) {
                $name['checked'] = 'checked';
            }

            $pitch = array(
                'value' => 'pitch', 
                'id' => $profile_type.'_dev_pitch'
            );

            if (isset($search_data['search_words_in']) && in_array('pitch', $search_data['search_words_in'])) {
                $pitch['checked'] = 'checked';
            }

            if ( ! isset($name['checked']) && ! isset($pitch['checked'])) $name['checked'] = 'checked';

            echo Former::checkboxes('search_words_in[]', lang('vgc.search.name_or_pitch_help'))->checkboxes(array(
                lang('common.name') => $name,
                lang('common.pitch') => $pitch
            ));
            ?>
        </div>

        <div class="span2">
            <?php 
            $all = array(
                'value' => 'all',
                'name' => 'search_words_mode',
                'id' => $profile_type.'words_all',
            );

            $any = array(
                'value' => 'any',
                'name' => 'search_words_mode',
                'id' => $profile_type.'words_any',
            );

            if (isset($search_data['search_words_mode'])) {
                ${$search_data['search_words_mode']}['checked'] = 'checked';
            } else $any['checked'] = 'checked';

            echo Former::radios('', lang('vgc.search.words_contains'))->radios(array(
                lang('vgc.search.words_contains_all') => $all,
                lang('vgc.search.words_contains_any') => $any,
            ));
            ?>
        </div>

        <div class="span2">
            {{ Former::text('words_list', lang('vgc.search.words_list')) }}
        </div>
    </div>

    <hr>

    @include('forms.search_'.$profile_type)

    <hr>
    
    {{ Former::primary_submit(lang('vgc.search.submit_category')) }}
{{ Former::close() }}
