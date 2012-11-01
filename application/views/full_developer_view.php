<?php
$site_data = get_static_data('site');

//$db_dev["data"]["socialnetworks"] = json_decode( $db_dev["socialnetworks"], true );
?>
		<section id="full_developer_profile">
			<h1>{{ lang('developer_page_title') }}</h1>

			<header>
				<span>{{ db_dev.name }}</span> 
				<span><a href="{{ db_dev.data.website }}" title="{{ lang('developer_website_title') }}">{{ db_dev.name }}</a></span>
				<span>{{ lang('developer_teamsize') }} : {{ db_dev.data.teamsize }}</span> 
				<span>{{ lang('developer_country') }} : {{ lang("countries_".$db_dev["data"]["country"]) }}</span>
			</header>

			<section>
				<div id="profile_pitch">
					<img src="{{ db_dev.data.logo }}" alt="{{ db_dev.name }} logo" id="profile_logo" maxwidth="100px"/>
					{{ parse_bbcode($db_dev["data"]["pitch"]) }} 
				</div>

				<div id="profile_blogfeed">
					{{ lang('game_blogfeed') }}
					<ul>
						<?php foreach( $db_dev["feed_items"] as $item ): ?>
						<li><a href="{{ item.link }}">{{ item.title }}</a></li>
						{% endfor %}
					 </ul>
				</div>

				<div id="profile_socialnetworks">
					{{ lang('developer_socialnetworks') }} : 
					
					<?php
					$count = count($db_dev["data"]['socialnetworks']['names']);

					for( $i = 0; $i < $count; $i++ ) : ?>
					<a href="{{ db_dev.data.socialnetworks.urls.$i }}">{{ lang("socialnetworks_".$db_dev["data"]['socialnetworks']['names'][$i]) }}</a>
					<?php endfor; ?> 
				</div>

				<?php 
				$categories = array('technologies', 'operatingsystems', 'devices', 'stores');

				foreach( $categories as $category ):
					if( count( $db_dev["data"][$category] ) == 0 )
						continue;
				?> 
				<div id="profile_{{ category }}">
					{{ lang('developer_'.$category) }} : 
					
					<?php
					$text = '';
					foreach( $db_dev["data"][$category] as $item ) {
						$text .= lang($category."_$item")." ,";
					}
					?> 
					{{ rtrim($text, ', ') }}
				</div>
				{% endfor %} 
			</section>
		</section> <!-- /#full_developer_profile -->
