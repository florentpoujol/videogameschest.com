<header id="admin_menu">
	<ul>
		<li><a href="<?php echo site_url('admin'); ?>" <?php echo admin_menu_selected('hub');?>>Hub</a></li>

<?php if(userdata('isadmin')): ?> <!-- admins only -->
		<li><a href="<?php echo site_url('admin/adddeveloper'); ?>" <?php echo admin_menu_selected('adddeveloper');?>>Add a developer</a></li>
		<li><a href="<?php echo site_url('admin/reports'); ?>" <?php echo admin_menu_selected('reports');?>>Reports</a></li>
<?php endif; ?>

		<li><a href="<?php echo site_url('admin/editdeveloper'); ?>" <?php echo admin_menu_selected('editdeveloper');?>>Edit a developer</a></li>
		<li><a href="<?php echo site_url('admin/addgame'); ?>" <?php echo admin_menu_selected('addgame');?>>Add a game</a></li>
		<li><a href="<?php echo site_url('admin/editgame'); ?>" <?php echo admin_menu_selected('editgame');?>>Edit a game</a></li>
		<li><a href="<?php echo site_url('admin/gamequeue'); ?>" <?php echo admin_menu_selected('gamequeue');?>>Game queue</a></li>
		<li><a href="<?php echo site_url('admin/messages'); ?>" <?php echo admin_menu_selected('messages');?>>Messages</a></li>
	<ul>
<?php echo 'admin page : '.get_admin_page();?>
</header>