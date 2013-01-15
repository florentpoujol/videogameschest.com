<div id="advertising-menu">
<?php
$tabs = array(array(
    'url' => route('get_crosspromotion'),
    'label' => lang('crosspromotion.title'),
));

?>

{{ Navigation::tabs($tabs) }}
</div>