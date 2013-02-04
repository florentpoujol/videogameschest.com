<?php
$old = Input::old();
if (isset($old)) {
 var_dump($old);
 Former::populate($old);
}

$rules = array();

if ( ! isset($files)) echo 'file test';
if ( ! isset($contents)) echo 'content test';


?>



