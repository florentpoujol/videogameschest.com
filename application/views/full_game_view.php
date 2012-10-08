<?php
$site_data = get_site_data();
?>
		<section id="full_profile">
			<h1><?php echo lang('game_page_title');?></h1>

			<header>
				<span><?php echo $db_game->name;?></span> 
				<?php echo '<span><a href="'.$db_game->data['website'].'" title="'.lang('game_website_title').'">'.$db_game->name.'</a></span>'; ?> 
			</header>

			<article>
				<div id="profile_pitch">
					<?php echo parse_bbcode($db_game->data['pitch']); ?> 
				</div>

				<div id="profile_blogfeed">
					<?php echo lang('game_blogfeed'); ?>
					<ul>
						<?php 
						foreach( $db_game->feed_items as $item ) {
						 	echo '<li><a href="'.$item['link'].'">'.$item['title'].'</a></li>';
						}
						?>
					 </ul>
				</div>

				<div id="profile_screenshots">
					<?php echo lang('game_screenshots').' <br>'; ?> 
					
					<div id="screenshots_display">
					<?php
					$count = count($db_game->data['screenshots']['names']);
					for( $i = 0; $i < $count; $i++ ) {
						$name = $db_game->data['screenshots']['names'][$i];
						$url = $db_game->data['screenshots']['urls'][$i];

						echo '<a href="'.$url.'" title="'.$name.'" rel="color_screenshots_group"><img src="'.$url.'" alt="'.$name.'" class="profile_screenshot" /></a> ';
					}
					?> 
				</div>


				<div id="profile_socialnetworks">
					<?php echo lang('game_socialnetworks').' : '; ?> 
					
					<?php
					$count = count($db_game->data['socialnetworks']['names']);
					for( $i = 0; $i < $count; $i++ )
						echo '<a href="'.$db_game->data['socialnetworks']['urls'][$i].'">'.$site_data->socialnetworks[$db_game->data['socialnetworks']['names'][$i]].'</a> ';
					?> 
				</div>
				<div id="profile_stores">
					<?php echo lang('game_stores').' : '; ?> 
					
					<?php
					$count = count($db_game->data['stores']['names']);
					for( $i = 0; $i < $count; $i++ )
						echo '<a href="'.$db_game->data['stores']['urls'][$i].'">'.$site_data->stores[$db_game->data['stores']['names'][$i]].'</a> ';
					?> 
				</div>

				<?php 
				$categories = array('technologies', 'operatingsystems', 'devices',
 				'genres', 'themes', 'viewpoints', 'nbplayers',  'tags' );

				foreach( $categories as $category ):
				?> 
				<div id="profile_<?php echo $category;?>">
					<?php echo lang('game_'.$category).' : '; ?> 
					
					<?php
					$text = '';
					foreach( $db_game->data[$category] as $item ) {
						$text .= $site_data->{$category}[$item].', ';
					}

					echo rtrim( $text, ', ' );
					?> 
				</div>
				<?php
				endforeach;
				?> 
			</article>
		</section> <!-- /#full_game_profile -->

