<header id="admin_menu">
	<ul>
		<li><a href="<?php echo site_url('admin'); ?>" <?php echo menu_selected('hub', 'admin');?>>Hub</a></li>

<?php if(userdata('isadmin')): ?> <!-- admins only -->
		<li><a href="<?php echo site_url('admin/adddeveloper'); ?>">Add a developer</a></li>
		<li><a href="<?php echo site_url('admin/reports'); ?>">Reports</a></li>
<?php endif; ?>

		<li><a href="<?php echo site_url('admin/editdeveloper'); ?>">Edit a developer</a></li>
		<li><a href="<?php echo site_url('admin/addgame'); ?>">Add a game</a></li>
		<li><a href="<?php echo site_url('admin/editgame'); ?>">Edit a game</a></li>
		<li><a href="<?php echo site_url('admin/gamequeue'); ?>">Game queue</a></li>
		<li><a href="<?php echo site_url('admin/messages'); ?>">Messages</a></li>
	<ul>
</header>