<?php
$all = array(
    'value' => 'all',
    'name' => 'array_fields_where['.$field.']',
    'id' => $profile_type.'_'.$field.'_array_field_all',
);

$any = array(
    'value' => 'any',
    'name' => 'array_fields_where['.$field.']',
    'id' => $profile_type.'_'.$field.'_array_field_any',
);

if (isset($search_data['array_fields_where']) && isset($search_data['array_fields_where'][$field])) {
    ${$search_data['array_fields_where'][$field]}['checked'] = 'checked';
} else $any['checked'] = 'checked';


echo Former::radios('', lang('search.'.$field.'_help'))->radios(array(
    lang('common.array_field_where_all') => $all,
    lang('common.array_field_where_any') => $any,
));

if (isset($search_data['array_fields'][$field])) $values = $search_data['array_fields'][$field];
else $values = array();
?>

@if ($field == 'tool_works_on_os')
    {{ array_to_checkboxes('operatingsystems', $values, 'array_fields['.$field.'][]') }}
@else
    {{ array_to_checkboxes($field, $values, 'array_fields['.$field.'][]') }}
@endif
