<?php 
//the $page variable is the name of the current page -->
?>
<header id="menu">
	<ul>
<?php
$items = array('featured', 'search', 'about', 'adddeveloper', 'addgame');

foreach( $items as $item )
	echo '		<li><a href="'.site_url($item).'" '.menu_selected($item).'>'.lang('menu_'.$item).'</a></li>
';
?>

<?php if( userdata( 'is_logged_in' ) ): ?>
		<li><a href="<?php echo site_url('admin'); ?>" <?php echo menu_selected('admin');?>>Admin</a></li>
		<li><a href="<?php echo site_url('admin/logout'); ?>">Log Out</a></li>
<?php else: ?>
		<li><a href="<?php echo site_url('admin/login'); ?>" <?php echo menu_selected('login');?>>Log In</a></li>
<?php endif; ?>
	</ul>
	<ul>
<?php
$class = '';
foreach( get_site_data()->sitelanguages as $lang ) {
	if( userdata( 'language' ) == $lang )
		$class = 'class="selected"';
	else
		$class ='';

	echo '		<li><a href="'.site_url( 'admin/setlanguage/'.$lang ).'" title="'.$lang.'" '.$class.'>'.$lang.'</a></li>
	';
}
?>
	</ul>
</header>
