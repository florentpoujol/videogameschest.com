<!DOCTYPE html>
<html> 
	<head>
		<title><?php echo $pageTitle; ?></title>

		<!-- Meta -->
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<?php if (CONTROLLER == "admin"): ?>
		<meta name='robots' content='noindex,nofollow' />
<?php else: ?>
		<meta name='robots' content='index,follow' />
<?php endif; ?>
		<meta name='robots' content='noindex,nofollow' />
		
<?php foreach ($metas as $meta): ?>
		<meta name='<?php echo $meta['name']; ?>' content='<?php echo $meta['content']; ?>' />
<?php endforeach; ?>
		<!-- /Meta -->

		<!-- CSS -->		
		<!--<link rel="stylesheet" type="text/css" media="screen" href="<?php echo css_link( 'main' ); ?>" />-->
		<link rel="stylesheet/less" type="text/css" media="screen" href="<?php echo css_link('main', '.less');?>" />
		<!--<link rel="stylesheet" type="text/css" href="<?php echo css_link('tooltipster');?>" />-->
<?php
/*try {
    $this->lessphp->instance->ccompile(base_url().'assets/css/main.less', base_url().'assets/css/mainCompiled.css');
}
catch (exception $ex) {
     exit('lessc fatal error:
     '.$ex->getMessage());
}*/
?>
		
<?php foreach ($css as $url): ?>
		<link rel="stylesheet" type="text/css" media="screen" href="<?php echo $url; ?>" />
<?php endforeach; ?>		
		<!-- /CSS -->
	</head>
	
	<body>
		<header>
			<nav id="menu">
				<ul>
<?php
$items = array('featured', 'search', 'adddeveloper', 'addgame', 'about');

foreach ($items as $item): ?>
					<?php echo '<li><a href="'.site_url($item).'" '.menu_selected($item).'>'.lang('menu_'.$item).'</a></li>'; ?>

<?php
endforeach;

if (IS_LOGGED_IN): ?>
					<li><a href="<?php echo site_url('admin'); ?>" <?php echo menu_selected('admin');?>>Admin</a></li>
					<li><a href="<?php echo site_url('admin/logout'); ?>"><?php echo lang('menu_logout');?></a></li>
<?php else: ?>
					<li><a href="<?php echo site_url('admin/login'); ?>" <?php echo menu_selected('login');?>><?php echo lang('menu_login');?></a></li>
<?php endif; ?>
				</ul>
			</nav> 
			<!-- /#menu --> 

			<nav id="lang_menu">
				<ul>
<?php
$class = '';
foreach (get_static_data('site')->languages as $lang) {
	if (LANGUAGE == $lang)
		$class = 'class="selected"';
	else
		$class ='';

	$current_url_escaped = str_replace("/", ":", uri_string()); // replace / by @ in the current url
	$lang_url = site_url("admin/setlanguage/$lang:$current_url_escaped");
?>
					<?php echo '<li><a href="'.$lang_url.'" title="'.$lang.'" '.$class.'>'.lang('languages_'.$lang).'</a></li>'; ?>

<?php	
} // end foreach
?>
				</ul>
			</nav> 
			<!-- /#lang_menu --> 

<?php if( userdata( 'is_logged_in' ) ): ?>
			<nav id="admin_menu">
				<ul>
					<li><a href="<?php echo site_url('admin'); ?>" <?php echo admin_menu_selected('index');?>>Admin hub</a></li>
	<?php if( userdata( 'is_admin' ) ): // admin only ?>
					<li><a href="<?php echo site_url('admin/editadmin'); ?>" <?php echo admin_menu_selected('editadmin');?>>Edit your admin account</a></li>
					<li><a href="<?php echo site_url('admin/adddeveloper'); ?>" <?php echo admin_menu_selected('adddeveloper');?>>Add a developer</a></li>
					<li><a href="<?php echo site_url('admin/editdeveloper'); ?>" <?php echo admin_menu_selected('editdeveloper');?>>Edit a developer</a></li>
					<li><a href="<?php echo site_url('admin/reports'); ?>" <?php echo admin_menu_selected('reports');?>>Reports</a></li>
	<?php elseif( userdata( 'is_developer' ) ): ?>
					<li><a href="<?php echo site_url('admin/editdeveloper/'.userdata('user_id')); ?>" <?php echo admin_menu_selected('editdeveloper');?>>Edit your developer account</a></li>
	<?php endif; ?>		
					<li><a href="<?php echo site_url('admin/addgame'); ?>" <?php echo admin_menu_selected('addgame');?>>Add a game</a></li>
					<li><a href="<?php echo site_url('admin/editgame'); ?>" <?php echo admin_menu_selected('editgame');?>>Edit a game</a></li>
					<li><a href="<?php echo site_url('admin/gamequeue'); ?>" <?php echo admin_menu_selected('gamequeue');?>>Game queue</a></li>
					<li><a href="<?php echo site_url('admin/messages'); ?>" <?php echo admin_menu_selected('messages');?>>Messages</a></li>
				<ul>
			</nav> <!-- /#admin_menu -->
<?php endif; // end if( userdata( 'is_logged_in' ) ):?>
		</header>

		<!-- Body hook -->
<?php echo $body_views; ?>
		<!-- /Body hook -->

		<!-- JavaScript -->
		<script src="http://lesscss.googlecode.com/files/less-1.3.0.min.js" type="text/javascript"></script>
		
		<script src="http://code.jquery.com/jquery-1.8.2.min.js" type="text/javascript"></script>
		<!--<script src="<?php echo js_link('jquery.tooltipster.min');?>" type="text/javascript"></script>-->

<?php foreach( $js as $url ): ?>
		<script type="text/javascript" src="<?php echo $url; ?>"></script> 
<?php endforeach; ?>

		<script type="text/javascript">
			/*$(document).ready(function() {
				$('.tooltip').tooltipster();
			});*/
		</script>
		<!-- /JavaScript -->
	</body>
</html>