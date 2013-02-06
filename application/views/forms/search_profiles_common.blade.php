{{ Former::open_vertical(route('post_search')) }}
    {{ Form::token() }}
    
    {{ Former::primary_submit(lang('search.submit')) }}

    <hr>
    
    <div class="row">
        {{ Former::hidden('profile_type', $profile_type) }}

        <div class="span2">

        </div>
        
        <div class="span2">
            <?php 
            echo Former::checkboxes('search_words_in[]', lang('search.name_or_pitch_help'))->checkboxes(array(
                lang('common.name') => array(
                    'value' => 'name',
                    // 'name' => 'search_in[]_name',
                    'id' => $profile_type.'dev_name',
                    'checked' => 'checked'
                ),

                lang('common.pitch') => array(
                    'value' => 'pitch', 
                    // 'name' => 'search_in_pitch',
                    'id' => $profile_type.'dev_pitch'
                ),
            ));
            ?>
        </div>

        <div class="span2">
            <?php 
            echo Former::radios('', lang('search.words_contains'))->radios(array(
                lang('search.words_contains_all') => array(
                    'value' => 'all',
                    'name' => 'search_words_mode',
                    'id' => $profile_type.'words_all',
                 ),

                lang('search.words_contains_any') => array(
                    'value' => 'any',
                    'name' => 'search_words_mode',
                    'id' => $profile_type.'words_any',
                    'checked' => 'checked'
                ),
            ));
            ?>
        </div>

        <div class="span2">
            {{ Former::text('words_list', lang('search.words_list')) }}
        </div>
    </div>

    <hr>

    @include('forms.search_'.$profile_type)

    <hr>
    
    {{ Former::primary_submit(lang('search.submit')) }}
{{ Former::close() }}
