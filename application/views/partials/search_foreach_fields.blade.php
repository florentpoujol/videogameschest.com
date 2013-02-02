@foreach ($fields as $field)
    <?php
    $values = array();
    if (isset($old['arrayitems'][$field])) $values = $old['arrayitems'][$field];
    if (isset($search_data['arrayitems'][$field])) $values = $search_data['arrayitems'][$field];
    ?>
    <div class="span6">
        <!-- <p>{{ lang('search.'.$field.'_help') }}</p> -->

        <?php 
        echo Former::radios('', lang('search.'.$field.'_help'))->radios(array(
            lang('common.array_field_where_all') => array(
                'value' => 'all',
                'name' => $field.'_where',
                'id' => $profile_type.'array_field_all',
             ),

            lang('common.array_field_where_any') => array(
                'value' => 'any',
                'name' => $field.'_where',
                'id' => $profile_type.'array_field_any',
                'checked' => 'checked'
            ),
        ));
        ?>

        {{ array_to_checkboxes($field, $values, 'arrayitems['.$field.'][]') }}
    </div>
@endforeach
