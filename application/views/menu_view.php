<?php 
//the $page variable is the name of the current page -->
?>
<header id="menu">
	<a href="<?php echo site_url('featured'); ?>" <?php echo menu_selected('featured');?>>Featured</a> | 
	<a href="<?php echo site_url('search'); ?>" <?php if($page=='search')echo'class="menu_selected"';?>>Search</a> | 
	<a href="<?php echo site_url('about'); ?>" <?php if($page=='about')echo'class="menu_selected"';?>>About</a> | 

<?php if(userdata('isloggedin')): ?>
	<a href="<?php echo site_url('admin'); ?>" <?php echo menu_selected('admin');?>>Admin</a> | 
	<?php if($page=='')echo'class="menu_selected"';?>
	<a href="<?php echo site_url('admin/logout'); ?>">Log Out</a>
<?php else: ?>
	<a href="<?php echo site_url('admin/login'); ?>">Log In</a>
<?php endif; ?>
</header>

<?php if($page=='')echo'class="menu_selected"';?>