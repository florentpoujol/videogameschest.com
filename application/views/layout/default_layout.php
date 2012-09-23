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
		<link rel="stylesheet" type="text/css" media="screen" href="<?php echo cssLink( 'vgdb' ); ?>" />
<?php foreach( $css as $url ): ?>
		<link rel="stylesheet" type="text/css" media="screen" href="<?php echo $url; ?>" />
<?php endforeach; ?>
		<!-- /CSS -->

		<!-- HeadEnd hook -->
<?php echo $headEnd; ?>
		<!-- /HeadEnd hook -->
	</head>
	
	<body>
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