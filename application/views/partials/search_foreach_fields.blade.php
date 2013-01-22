@foreach ($fields as $field)
    <?php
    $values = array();
    if (isset($old['arrayitems'][$field])) $values = $old['arrayitems'][$field];
    if (isset($search_data['arrayitems'][$field])) $values = $search_data['arrayitems'][$field];
    ?>
    <div class="span2">
        <p>{{ lang('search.'.$field.'_help') }}</p>

        {{ array_to_checkboxes($field, $values, 'arrayitems['.$field.'][]') }}
    </div>
@endforeach
