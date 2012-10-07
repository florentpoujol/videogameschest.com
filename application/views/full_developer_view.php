<?php
$site_data = get_site_data();

$db_dev->socialnetworks = json_decode( $db_dev->socialnetworks, true );
?>
		<section id="full_developer_profile">
			<h1><?php echo lang('developer_page_title');?></h1>

			<header>
				<span><?php echo $db_dev->name;?></span> 
				<?php echo '<span><a href="'.$db_dev->website.'" title="'.lang('developer_website_title').'">'.$db_dev->name.'</a></span>'; ?> 
				<span><?php echo lang('developer_teamsize').' : '.$db_dev->teamsize;?></span> 
				<span><?php echo lang('developer_country').' : '.$site_data->countries[$db_dev->country];?></span>
			</header>

			<article>
				<div id="developer_pitch">
					<?php echo '<img src="'.$db_dev->logo.'" alt="'.$db_dev->name.' logo" id="developer_logo" maxwidth="100px"/>';
					echo parse_bbcode($db_dev->pitch); ?> 
				</div>

				<div id="developer_blogfeed">
					blogfeed
				</div>

				<div id="developer_socialnetworks">
					<?php echo lang('developer_socialnetworks').' : '; ?> 
					
					<?php
					$count = count($db_dev->socialnetworks['names']);
					for( $i = 0; $i < $count; $i++ )
						echo '<a href="'.$db_dev->socialnetworks['urls'][$i].'">'.$site_data->socialnetworks[$db_dev->socialnetworks['names'][$i]].'</a> ';
					?> 
				</div>
				<?php 
				$categories = array('technologies', 'operatingsystems', 'devices', 'stores');

				foreach( $categories as $category ):
				?> 
				<div id="developer_<?php echo $category;?>">
					<?php echo lang('developer_'.$category).' : '; ?> 
					
					<?php
					$items = explode( ',', $db_dev->$category );
					$text = '';
					foreach( $items as $item ) {
						$array = $site_data->$category;
						$text .= $array[$item].', ';
					}

					echo rtrim( $text, ', ' );
					?> 
				</div>
				<?php
				endforeach;
				?> 
			</article>
		</section> <!-- /#full_developer_profile -->
