<?php
$old = Input::old();
if (isset($old)) {
 var_dump($old);
 Former::populate($old);
}

$rules = array();

$search = new Search;
$search->data = json_encode(

array(
  'class' =>  'developer' ,
  'search_in' =>  'name' ,
  'words_search_mode' =>  'or_' ,
  'words' =>  'Developer' ,
  ));
$search->save();

?>
{{ Former::open_vertical('test')->rules($rules) }} 
	
<?php
$item = 'tags';
$items = Config::get('vgc.tags');
$options = get_array_lang($items, $item.'.');

$values = array();
if (isset($old[$item])) $values = $old[$item];

$size = count($items);
if ($size > 10) $size = 10;
?>

{{ Former::multiselect($item, '')->options($options)->forceValue($values)->size($size) }}

<br>


{{ array_to_checkboxes('stores', $old) }}
<?php



?>

{{ Former::submit() }}
</form>