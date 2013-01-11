@foreach ($fields as $field)
    <?php
    $fields = Config::get('vgc.'.$field);
    $options = get_array_lang($fields, $field.'.');
    
    $values = array();
    if (isset($old['arrayitems'][$field])) $values = $old['arrayitems'][$field];
    if (isset($search_data['arrayitems'][$field])) $values = $search_data['arrayitems'][$field];
    
    $size = count($fields);
    if ($size > 15) $size = 15;
    ?>
    <div class="span2">
        <p>{{ lang('search.'.$field.'_help') }}</p>
        {{ array_to_checkboxes($field, $values, 'arrayitems['.$field.'][]') }}
        {{-- Former::multiselect('arrayitems['.$field.']', '')->options($options)->value($values)->size($size) }}
    </div>
@endforeach
