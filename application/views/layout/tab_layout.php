<!DOCTYPE html>
<html xml:lang="fr" > 
	<head>
		<title><?php echo $pageTitle; ?> - Florent Poujol</title>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		
		<link rel="stylesheet" type="text/css" media="screen" href="<?php echo cssLink( 'style1' ); ?>" />
<?php foreach( $cssUrls as $url ): ?>
		<link rel="stylesheet" type="text/css" media="screen" href="<?php echo $url; ?>" />
<?php endforeach; ?>
	</head>
	
	<body>
<!-- header (logo & main menu) -->
<?php echo $header; ?>

<!-- tab menu (sub menu with tabs for that page) -->
<?php echo $tabMenu; ?>
		
<!-- tab container / body content (main content of the page) -->
		<div id="tab-container">
<?php echo $bodyContent; ?>
		</div>
		
		<br/>
		<hr/>
		
<!-- javascript -->
<?php foreach( $jsUrls as $url ): ?>
		<script type="text/javascript" src="<?php echo $url; ?>"></script> 
<?php endforeach; ?>

<!-- google analytics code -->
<?php echo $visitTrackingCode; ?>
	</body>
</html>