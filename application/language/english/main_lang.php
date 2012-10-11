<?php




// MENU

$lang['menu_featured'] = 'Featured';
$lang['menu_search'] = 'Search';
$lang['menu_about'] = 'About';
$lang['menu_adddeveloper'] = 'Add a developer';
$lang['menu_addgame'] = 'Add a game';
$lang['menu_login'] = 'Log In';
$lang['menu_logout'] = 'Log Out';



// DEVELOPER FORM, page "adddeveloper"


$lang['adddeveloper_form_title'] = 'Create a developer profile';

$lang['adddeveloper_required_field'] = 'The developer\'s name and email are required fields.';

$lang['adddeveloper_name'] = 'Name (company name, if applicable)';
$lang['adddeveloper_placeholder_name'] = 'Name (company name)';
$lang['adddeveloper_email'] = 'Email';
$lang['adddeveloper_pitch'] = 'Explain about the developer\'s phylosophy, goals...';
$lang['adddeveloper_logo'] = 'Logo URL';
$lang['adddeveloper_website'] = 'Website URL';
$lang['adddeveloper_blogfeed'] = 'Blog RSS/Atom feed';
$lang['adddeveloper_country'] = 'Country';
$lang['adddeveloper_teamsize'] = 'Size of the team';

$lang['adddeveloper_socialnetworks'] = 'Social networks profiles';
$lang['adddeveloper_technologies'] = 'The technologies the developer works with :';
$lang['adddeveloper_operatingsystems'] = 'The operating systems the developer\'s games run on (not applicable for most consoles) : ';
$lang['adddeveloper_devices'] = 'The devices the developer\'s games are playable on : ';
$lang['adddeveloper_stores'] = 'The stores the developer sells their games on : ';

$lang['adddeveloper_submit'] = 'Create this developer profile';

$lang['adddeveloper_form_success'] = 'Thanks you, the developer has successfully been added, an email will be send to let them now. \n 
They must first check the data then make their account public before you can add one of their game.';



// GAME FORM (mostly page "addgame")


$lang['addgame_form_title'] = 'Create a game profile';

$lang['addgame_required_field'] = 'The developer\'s name and email are required fields.';

$lang['addgame_name'] = 'Game title';
$lang['addgame_developer'] = 'Developer';
$lang['addgame_pitch'] = 'Game story, features...';

$lang['addgame_developementstates_legend'] = "Developement state";
$lang['addgame_developementstates_concept'] = "Concept/Design phase";
$lang['addgame_developementstates_prototype'] = "Prototype phase";
$lang['addgame_developementstates_earlyproduction'] = "Early production";
$lang['addgame_developementstates_internalplayablebuilt'] = "Internal playable built";
$lang['addgame_developementstates_publicplayablebuilt'] = "Public playable built";
$lang['addgame_developementstates_released'] = "Released";
$lang['addgame_developementstates_canceled'] = "Canceled";

$lang['addgame_logo'] = 'Logo URL';
$lang['addgame_website'] = 'Website URL';
$lang['addgame_blogfeed'] = 'Blog RSS/Atom feed';
$lang['addgame_publishername'] = 'Publisher name';
$lang['addgame_publisherurl'] = "Publisher's website url";
$lang['addgame_soundtrack'] = 'Soundtrack URL';
$lang['addgame_price'] = 'Price (this is a text field, write whatever is appropriate)';
$lang['addgame_releasedate'] = 'Release date';

$lang['addgame_country'] = 'Country';
$lang['addgame_socialnetworks'] = 'Social networks profiles : ';
$lang['addgame_languages'] = 'The languages the game is playable in : ';
$lang['addgame_stores'] = 'The stores the game is purchable from : ';
$lang['addgame_technologies'] = 'The technologies the game is made with : ';
$lang['addgame_operatingsystems'] = 'The operating systems the game runs on (not applicable for most console games) : ';
$lang['addgame_devices'] = 'The devices the game is playable on : ';
$lang['addgame_nbplayers'] = 'How many players can play the game at the same time :';
$lang['addgame_themes'] = 'Theme :';
$lang['addgame_genres'] = 'Genre : ';
$lang['addgame_tags'] = 'Tags : ';
$lang['addgame_viewpoints'] = 'The player\'s point of vue : ';

$lang['addgame_screenshots'] = 'Screenshots : ';
$lang['addgame_screenshots_name'] = 'Title';
$lang['addgame_screenshots_url'] = 'URL';
$lang['addgame_videos'] = 'Video and trailers : ';
$lang['addgame_videos_name'] = 'Title';
$lang['addgame_videos_url'] = 'URL';

$lang['addgame_submit'] = 'Create this game profile';

$lang['addgame_form_success'] = 'Thanks you, the developer has successfully been added, an email will be send to let them now. \n 
They must first check the data then make their account public before you can add one of their game.';


// DEVELOPER PROFILE (page "developer")

$lang['developer_page_title'] = "Developer";
$lang['developer_website_title'] = "Go to the developer's website";
$lang['developer_teamsize'] = "Size of the team";
$lang['developer_country'] = "Country";
$lang['developer_blogfeed'] = "Lastest articles from the blog";

$lang['developer_socialnetworks'] = "Social networks";
$lang['developer_technologies'] = "Technologies";
$lang['developer_operatingsystems'] = "Operating systems";
$lang['developer_devices'] = "Devices";
$lang['developer_stores'] = "Stores";


// GAME PROFILE (page "game")

$lang['game_page_title'] = "Game";
$lang['game_website_title'] = "Go to the game's website";
$lang['game_blogfeed'] = "Lastest articles from the blog";

$lang['game_socialnetworks'] = "Social networks";
$lang['game_technologies'] = "Technologies";
$lang['game_operatingsystems'] = "Operating systems";
$lang['game_devices'] = "Devices";
$lang['game_stores'] = "Stores";
$lang['game_genres'] = "Genres";
$lang['game_themes'] = "Stores";
$lang['game_viewpoints'] = "Stores";
$lang['game_nbplayers'] = "Stores";
$lang['game_tags'] = "tags";



// SEARCH (page "search")



// TOOLTIPS      use \n to insert  

$lang['tooltip_developer_name'] = "This is a <br> useful \n tooltip";




// LANGUAGES

$languages = array(
"french",
"english",
"german",
"spanish",
"italian",
"russian",
"japonese",
"chinese"
);
foreach ($languages as $language)
	$lang['languages_'.$language] = ucfirst($language);


// COUNTRIES

$countries = array( 
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
"uk",
"usa"
);
foreach ($countries as $country)
	$lang['countries_'.$country] = ucfirst($country);

$lang['countries_uk'] = "United Kingdom";
$lang['countries_usa'] = "United States of America";


// OPERATING SYSTEMS

$oss = array(
"android",
"blackberry",
"linux",
"mac",
"windows",
"windowsphone7"
);
foreach ($oss as $os)
	$lang['operatingsystems_'.$os] = ucfirst($os);

$lang['operatingsystems_ios'] = "iOS";


// DEVICES

$oss = array(
"browser",
"mac",
"ouya",
"wii"
);
foreach ($oss as $os)
	$lang['devices_'.$os] = ucfirst($os);


$lang['devices_3ds'] = "3DS";
$lang['devices_3dsxl'] = "3DS XL";
$lang['devices_androidtablet'] = "Android Tablet";
$lang['devices_androidsmartphone'] = "Android Smartphone";
$lang['devices_blackberrysmartphone'] = "BlackBerry Smartphone";
$lang['devices_ds'] = "DS";
$lang['devices_ipod'] = "iPod";
$lang['devices_iphone'] = "iPhone";
$lang['devices_ipad'] = "iPad";
$lang['devices_pc'] = "PC";
$lang['devices_ps3'] = "PS3";
$lang['devices_psp'] = "PSP";
$lang['devices_psvita'] = "PS Vita";
$lang['devices_wiiu'] = "Wii U";
$lang['devices_windowsphonetablet'] = "Windows Phone Tablet";
$lang['devices_windowsphonesmartphone'] = "Windows Phone Smartphone";
$lang['devices_xbox360'] = "xBox 360";
$lang['devices_xperiaplay'] = "Xperia Play";
$lang['devices_xperiasmartphone'] = "Xperia Smartphone";


// DEVICES

$technologies = array(
"blender",
"flash",
"flixel",
"java",
"python",
"stencyl"
);
foreach ($technologies as $techno)
	$lang['technologies_'.$techno] = ucfirst($techno);


$lang['technologies_adventuregamestudio'] = "Adventure Games Studio";
$lang['technologies_air'] = "Adobe AIR";
$lang['technologies_craftstudio'] = "CraftStudio";
$lang['technologies_cryengine'] = "Cry Engine";
$lang['technologies_custom'] = "Custom built";
$lang['technologies_gamemaker'] = "GameMaker";
$lang['technologies_html5'] = "HTML5 / JS";
$lang['technologies_impactjs'] = "Impact JS";
$lang['technologies_ogre3d'] = "Ogre 3D";
$lang['technologies_rpgmaker'] = "RPG Maker";
$lang['technologies_shiva3d'] = "Shiva 3D";
$lang['technologies_source'] = "Source Engine";
$lang['technologies_torque'] = "Torque Engine";
$lang['technologies_udk'] = "Unreal Developement Kit";
$lang['technologies_unity3d'] = "Unity 3D";
$lang['technologies_unrealengine'] = "Unreal Engine";
$lang['technologies_xna'] = "XNA";


// STORES

$stores = array(
"desura",
"gameolith",
"gamersgate",
"impulse",
"indiecity",
"indievania",
"kongregate",
"newsground",
"steam",
);
foreach ($stores as $store)
	$lang['stores_'.$store] = ucfirst($store);


$lang['stores_amazonmarket'] = "Amazon Marketplace";
$lang['stores_androidmarket'] = "Android Marketplace";
$lang['stores_applestore'] = "Apple Store";
$lang['stores_armorgames'] = "Armor Games";
$lang['stores_googleplay'] = "Google Play";
$lang['stores_greenmangaming'] = "GreenManGaming";
$lang['stores_website'] = "Company/Game Website";
$lang['stores_xbla'] = "xBox Live Arcade";
$lang['stores_xblig'] = "xBox Live Indie Games";


// SOCIAL NETWORKS

$socialnetworks = array(
"desura",
"facebook",
"pinterest",
"reddit",
"steam",
"twitter"
);
foreach ($socialnetworks as $site)
	$lang['socialnetworks_'.$site] = ucfirst($site);


$lang['socialnetworks_googleplus'] = "Google+";
$lang['socialnetworks_indiedb'] = "IndieDB";
$lang['socialnetworks_linkedin'] = "Linked In";
$lang['socialnetworks_moddb'] = "ModDB";