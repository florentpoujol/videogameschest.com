<!DOCTYPE html>
<html> 
	<head>
		<!-- HeadStart hook -->
<?php echo $headStart; ?>
		<!-- /HeadStart hook -->

		<title><?php echo $pageTitle; ?></title>

		<!-- Meta -->
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<meta name='robots' content='index,follow' />
<?php foreach( $metas as $meta ): ?>
		<meta name='<?php echo $meta['name']; ?>' content='<?php echo $meta['content']; ?>' />
<?php endforeach; ?>
		<!-- /Meta -->

		<!-- CSS -->		
		<link rel="stylesheet" type="text/css" media="screen" href="<?php echo css_link( 'main' ); ?>" />
<?php foreach( $css as $url ): ?>
		<link rel="stylesheet" type="text/css" media="screen" href="<?php echo $url; ?>" />
<?php endforeach; ?>
		<!-- /CSS -->

		<!-- HeadEnd hook -->
<?php echo $headEnd; ?>
		<!-- /HeadEnd hook -->
	</head>
	
	<body>
		<header id="menu">
			<ul>
<?php
$items = array('featured', 'search', 'adddeveloper', 'addgame', 'about');

foreach( $items as $item )
	echo '<li><a href="'.site_url($item).'" '.menu_selected($item).'>'.lang('menu_'.$item).'</a></li>
';
?>

<?php if( userdata( 'is_logged_in' ) ): ?>
				<li><a href="<?php echo site_url('admin'); ?>" <?php echo menu_selected('admin');?>>Admin</a></li>
				<li><a href="<?php echo site_url('admin/logout'); ?>"><?php echo lang('menu_logout');?></a></li>
<?php else: ?>
				<li><a href="<?php echo site_url('admin/login'); ?>" <?php echo menu_selected('login');?>><?php echo lang('menu_login');?></a></li>
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

	echo '<li><a href="'.site_url( 'admin/setlanguage/'.$lang ).'" title="'.$lang.'" '.$class.'>'.lang('language_'.$lang).'</a></li>
	';
}
?>
			</ul>
		</header>

		<!-- BodyStart hook -->
<?php echo $bodyStart; ?>
		<!-- /BodyStart hook -->

		<!-- JavaScript -->
<?php foreach( $js as $url ): ?>
		<script type="text/javascript" src="<?php echo $url; ?>"></script> 
<?php endforeach; ?>
		<!-- /JavaScript -->

		<!-- BodyEnd hook -->
<?php echo $bodyEnd; ?>
		<!-- /BodyEnd hook -->
	</body>
</html>