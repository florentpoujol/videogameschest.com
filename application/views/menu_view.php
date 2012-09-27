<?php 
//the $page variable is the name of the current page -->
?>
<header id="menu">
	<ul>
		<li><a href="<?php echo site_url('featured'); ?>" <?php echo menu_selected('featured');?>>Featured</a></li> 
		<li><a href="<?php echo site_url('search'); ?>" >Search</a></li> 
		<li><a href="<?php echo site_url('about'); ?>" >About</a></li>

<?php if( userdata( 'isloggedin' ) ): ?>
		<li><a href="<?php echo site_url('admin'); ?>" <?php echo menu_selected('admin');?>>Admin</a></li>
		<li><a href="<?php echo site_url('admin/logout'); ?>">Log Out</a></li>
<?php else: ?>
		<li><a href="<?php echo site_url('admin/login'); ?>" <?php echo menu_selected('login');?>>Log In</a></li>
<?php endif; ?>
	</ul>
	<ul>
<?php
$site_data = get_site_data();
$class = '';
foreach( $site_data->languages as $lang ) {
	if( userdata( 'language' ) === $lang )
		$class = 'class="selected"';
	else
		$class ='';

	echo '<li><a href="'.site_url( 'admin/setlanguage/'.$lang ).'" title="'.$lang.'" '.$class.'>'.$lang.'</a></li>';
}
echo lang( 'main_test' );
?>
	</ul>
</header>
