<?php
$site_data = get_static_data('site');

//$db_dev->data["socialnetworks"] = json_decode( $db_dev->socialnetworks, true );
?>
		<section id="full_developer_profile">
			<h1><?php echo lang('developer_page_title');?></h1>

			<header>
				<span><?php echo $db_dev->name;?></span> 
				<?php echo '<span><a href="'.$db_dev->data["website"].'" title="'.lang('developer_website_title').'">'.$db_dev->name.'</a></span>'; ?> 
				<span><?php echo lang('developer_teamsize').' : '.$db_dev->data["teamsize"];?></span> 
				<span><?php echo lang('developer_country').' : '.lang("countries_".$db_dev->data["country"]);?></span>
			</header>

			<article>
				<div id="profile_pitch">
					<?php echo '<img src="'.$db_dev->data["logo"].'" alt="'.$db_dev->name.' logo" id="profile_logo" maxwidth="100px"/>';
					echo parse_bbcode($db_dev->data["pitch"]); ?> 
				</div>

				<div id="profile_blogfeed">
					<?php echo lang('game_blogfeed'); ?>
					<ul>
						<?php 
						foreach( $db_dev->feed_items as $item ) {
						 	echo '<li><a href="'.$item['link'].'">'.$item['title'].'</a></li>';
						}
						?>
					 </ul>
				</div>

				<div id="profile_socialnetworks">
					<?php echo lang('developer_socialnetworks').' : '; ?> 
					
					<?php
					$count = count($db_dev->data['socialnetworks']['names']);
					for( $i = 0; $i < $count; $i++ )
						echo '<a href="'.$db_dev->data['socialnetworks']['urls'][$i].'">'.lang("socialnetworks_".$db_dev->data['socialnetworks']['names'][$i]).'</a> ';
					?> 
				</div>

				<?php 
				$categories = array('technologies', 'operatingsystems', 'devices', 'stores');

				foreach( $categories as $category ):
					if( count( $db_dev->data[$category] ) == 0 )
						continue;
				?> 
				<div id="profile_<?php echo $category;?>">
					<?php echo lang('developer_'.$category).' : '; ?> 
					
					<?php
					$text = '';
					foreach( $db_dev->data[$category] as $item ) {
						$text .= lang($category."_$item")." ,";
					}

					echo rtrim( $text, ', ' );
					?> 
				</div>
				<?php
				endforeach;
				?> 
			</article>
		</section> <!-- /#full_developer_profile -->
