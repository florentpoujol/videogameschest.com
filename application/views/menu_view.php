<?php 
//the $page variable is the name of the current page -->
?>
<header id="menu">
	<a href="<?php echo site_url('featured'); ?>" <?php echo menu_selected('featured');?>>Featured</a> | 
	<a href="<?php echo site_url('search'); ?>" >Search</a> | 
	<a href="<?php echo site_url('about'); ?>" >About</a> | 

<?php if(userdata('isloggedin')): ?>
	<a href="<?php echo site_url('admin'); ?>" <?php echo menu_selected('admin');?>>Admin</a> | 
	
	<a href="<?php echo site_url('admin/logout'); ?>">Log Out</a>
<?php else: ?>
	<a href="<?php echo site_url('admin/login'); ?>" <?php echo menu_selected('login');?>>Log In</a>
<?php endif; ?> <br>
</header>
