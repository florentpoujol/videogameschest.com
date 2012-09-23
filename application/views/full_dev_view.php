
name of the game : <?php echo $DBInfos->name; ?> <br>
name of the developper : <?php echo GetInfo( 'developers', 'name', 'id', $DBInfos->developer_id ); ?> <br>
<br>
url of the webiste : <?php echo $gameData->websiteurl; ?>

