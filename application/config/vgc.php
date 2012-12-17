<?php
return array(
	
	"site_languages" => array(
		"english",
		"french"
	),

	"date_formats" => array(
		"date_sql" => "Y-m-d",
		"datetime_sql" => "Y-m-d H:i:s",
		"english" => "d M Y - g:ia",
		"nonenglish" => "d M Y - G\\hi"
	),

	"feed" => array(
		"pitch_extract_length" => 1000,
		"item_count" => 20
	),


	'review' => array(
		'types' => array('submission', 'publishing'),
		'duration' => 7,
		'approval_threshold' => 20,
		'check_interval' => 60, // time in minutes between two review success check
	),

	'privacy' => array('private', 'public'),

	'pivacy_and_reviews' => array('private', 'public', 'submission', 'publishing'),

	'form_attributes_to_clean' => array('csrf_token', 'password_confirmation', 'old_password', 'controller'),


	// form

	"languages" => array(
		"chinese",
		"english",
		"japonese",
		"russian",
		'french',
	),

	"countries" => array(
		"usa",
		"australia",
		"austria",
		"belgium",
		"canada",
 		"france",
 		"germany",
		"greece",
		"holland",
		"ireland",
		"portugal",
		"russia",
		"spain",
		"switzerland",
		"uk"
	),

	"operatingsystems" => array(
		"android",
		"blackberry",
		"ios",
		"linux",
		"mac",
		"windowsdesktop",
		"windows8metro",
		"windowsphone"
	),
	
	"devices" => array(
		"3ds",
		"3dsxl",
		"androidtablet",
		"androidsmartphone",
		"blackberrysmartphone",
		"browser",
		"ds",
		"ipod",
		"iphone",
		"ipad",
		"mac",
		"ouya",
		"pc",
		"ps3",
		"psp",
		"psvita",
		"wii",
		"wiiu",
		"windowsphonetablet",
		"windowsphonesmartphone",
		"xbox360",
		"xperiaplay",
		"xperiasmartphone"
	),

	"technologies" => array(
		"adventuregamestudio",
		"air",
		"blender",
		"craftstudio",
		"cryengine",
		"custom",
		"flash",
		"flixel",
		"gamemaker",
		"html5",
		"impactjs",
		"java",
		"ogre3d",
		"python",
		"rpgmaker",
		"shiva3d",
		"source",
		"stencyl",
		"torque",
		"udk",
		"unity3d",
		"unrealengine",
		"xna"
	),

	"stores" => array(
		"amazonmarket",
		"androidmarket",
		"applestore",
		"armorgames",
		"desura",
		"gameolith",
		"gamersgate",
		"googleplay",
		"greenmangaming",
		"impulse",
		"indiecity",
		"indievania",
		"kongregate",
		"newsground",
		"steam",
		"website",
		"windowsstore",
		"xbla",
		"xblig"
	),

	"socialnetworks" => array(
		"desura",
		"facebook",
		"googleplus",
		"indiedb",
		"linkedin",
		"moddb",
		"pinterest",
		"reddit",
		"steam",
		"twitter"
 	),

	"nbplayers" => array(
		"singleplayer",
 		"coop",
 		"mmo",
 		"multiplayer"
 	),

	"developmentstates" => array(
		"concept",
		"prototype",
		"earlyproduction",
		"firstgameplay",
		"publicplayablebuilt",
		"released",
 		"canceled"
 	),

	"themes" => array(
		"fantasy",
		"medieval",
 		"comic",
 		"futuristic",
 		"horror",
 		"mafia",
 		"modern",
 		"scifi",
 		"steampunk",
 		"western"
 	),

 	"genres" => array(
 		"action",
 		"adventure",
 		"arcade",
 		"citybuilding",
 		"fighting",
 		"platformer",
 		"pointandclick",
 		"puzzle",
 		"racing",
 		"resources",
 		"roguelike",
 		"roleplaying",
 		"rts",
 		"shootemup",
 		"shooter",
 		"simulation",
 		"sport",
 		"strategy",
 		"towerdefense"
 	),

 	"viewpoints" => array(
 		"firstperson",
 		"isometric",
 		"thirdperson",
 		"topdown"
 	),

	"tags" => array(
		"25d",
		"2d",
		"3d",
 		"casual",
 		"celshading",
 		"fun",
 		"hardcore",
 		"leveleditor",
 		"physics",
 		"sidescrolling",
 		"turnbased"
 	),
);
