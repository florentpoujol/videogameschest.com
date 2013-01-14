<?php
$old = Input::old();
if (isset($old)) {
 var_dump($old);
 Former::populate($old);
}

$rules = array();

$email = 'florent.poujol@gmail.com';
$subject = 'Test sendMail 2';
$msg = '<strong>test</strong> <br> send mail';

sendMail($email, $subject, $msg);

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