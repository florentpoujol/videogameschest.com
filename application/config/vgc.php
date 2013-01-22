<?php
return array(
    
    "site_languages" => array(
        "english",
        
    ),

    'language_files' => array('vgc', 'emails'),
    

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
        'types' => array('publishing'),
        'duration' => 7,
        'approval_threshold' => 20,
        'check_interval' => 60, // time in minutes between two review success check
    ),

    'privacy' => array('private', 'public'),

    'privacy_and_reviews' => array('private', 'public', 'publishing'),

    'form_attributes_to_clean' => array('csrf_token', 'password_confirmation', 'old_password', 'controller', 'captcha', 'city'),

    'dummie_password' => 'testtest', //r!&5éT[79m},D?4â+5w% temp password while user is in submission review

    'dev_feed_item_count' => 6,
    'game_feed_item_count' => 6,

    'automatic_email_from' => 'no-reply@videogameschest.com',
    'automatic_email_from_name' => 'The VideoGamesChest mailer',
    'admin_email' => 'contact@videogameschest.com',

    'smtp' => array(
        'server' => 'mail.gandi.net',
        'server_port' => 465,
        'username' => 'contact@videogameschest.com',
        'password' => 'r8(A/vH6r:#;f5G',
    ),

    'recaptcha_private_key' => '6LcRitsSAAAAAGw_aZAy3qlnC_3lAypwaJm4SgMs',
    'recaptcha_public_key' => '6LcRitsSAAAAAKlKTf79Z6KbSIrSz2Z0WptjvXzE',

    'crosspromotion_developer_allowed_fields' => array(
        'name', 'pitch', 'logo', 'website', 'blogfeed', 'presskit',
        'languages', 'technologies', 'operatingsystems', 'devices',
       'socialnetworks', 'stores',
    ),

    'crosspromotion_game_allowed_fields' => array(
        'name', 'devstate', 'pitch', 'cover', 'website', 'blogfeed', 'presskit',
        'publishername', 'publisherurl', 'soundtrackurl', 'languages', 'technologies', 'operatingsystems', 'devices',
        'genres', 'themes', 'viewpoints', 'nbplayers', 'tags', 'socialnetworks', 'stores', 'screenshots', 'videos', 'reviews'
    ),


    'profile_types' => array('developers', 'games'),
    'profile_types_singular' => array('developer', 'game'),








// form

    "languages" => array(
        "chinese",
        "english",
        "japonese",
        "russian",
        
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
        "slidedb",
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


    'looks' => array(
        "cartoon",
        'pixelart',
        'realist',
        'minimalist',
        'celshading',
    ),

    'periods' => array( 
        "mythology",
        "fantasy",
        "medieval",
        "futuristic",
        "modern",
        "scifi",
        "western",
        "twentycentury"
    ),

    "genres" => array(
        "action",
        "adventure",
        "arcade",
        "citybuilding",
        "fighting",
        "hackandslash",
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
        "2d",
        "firstperson",
        "isometric",
        "thirdperson",
    ),

    "tags" => array(
        "3d",
        "casual",
        "fun",
        "hardcore",
        "leveleditor",
        "physics",
        "sidescrolling",
        "turnbased",
        "topdown",
        
        "onegameamonth",
        'ludumdare',
        'globalgamejam',
        'fuckthisjam',
        "horror",
        "mafia",
        "steampunk",
    ),

    // ICONS


    'icons' => array(
        'facebook' => 'img/icons/facebook.png',
        'twitter' => 'img/icons/twitter.png',
        'rss' => 'img/icons/rss.png',
    ),

    'glyphicons' => array( 
        'glass',
        'music',
        'search',
        'envelope',
        'heart',
        'star',
        'star-empty',
        'user',
        'film',
        'th-large',
        'th',
        'th-list',
        'ok',
        'remove',
        'zoom-in',
        'zoom-out',
        'off',
        'signal',
        'cog',
        'trash',
        'home',
        'file',
        'time',
        'road',
        'download-alt',
        'download',
        'upload',
        'inbox',
        'play-circle',
        'repeat',
        'refresh',
        'list-alt',
        'lock',
        'flag',
        'headphones',
        'volume-off',
        'volume-down',
        'volume-up',
        'qrcode',
        'barcode',
        'tag',
        'tags',
        'book',
        'bookmark',
        'print',
        'camera',
        'font',
        'bold',
        'italic',
        'text-height',
        'text-width',
        'align-left',
        'align-center',
        'align-right',
        'align-justify',
        'list',
        'indent-left',
        'indent-right',
        'facetime-video',
        'picture',
        'pencil',
        'map-marker',
        'adjust',
        'tint',
        'edit',
        'share',
        'check',
        'move',
        'step-backward',
        'fast-backward',
        'backward',
        'play',
        'pause',
        'stop',
        'forward',
        'fast-forward',
        'step-forward',
        'eject',
        'chevron-left',
        'chevron-right',
        'plus-sign',
        'minus-sign',
        'remove-sign',
        'ok-sign',
        'question-sign',
        'info-sign',
        'screenshot',
        'remove-circle',
        'ok-circle',
        'ban-circle',
        'arrow-left',
        'arrow-right',
        'arrow-up',
        'arrow-down',
        'share-alt',
        'resize-full',
        'resize-small',
        'plus',
        'minus',
        'asterisk',
        'exclamation-sign',
        'gift',
        'leaf',
        'fire',
        'eye-open',
        'eye-close',
        'warning-sign',
        'plane',
        'calendar',
        'random',
        'comment',
        'magnet',
        'chevron-up',
        'chevron-down',
        'retweet',
        'shopping-cart',
        'folder-close',
        'folder-open',
        'resize-vertical',
        'resize-horizontal',
        'hdd',
        'bullhorn',
        'bell',
        'certificate',
        'thumbs-up',
        'thumbs-down',
        'hand-right',
        'hand-left',
        'hand-up',
        'hand-down',
        'circle-arrow-right',
        'circle-arrow-left',
        'circle-arrow-up',
        'circle-arrow-down',
        'globe',
        'wrench',
        'tasks',
        'filter',
        'briefcase',
        'fullscreen',

        // font-awesome :
        // 'twitter',
        // 'twitter-sign',
        // 'facebook',
        'cogs',
        'globe',
        'envelope-alt',
        'group',
        'flag',
        'caret-down',
        'folder-open',
        'briefcase',
        'wrench',
        'music',
        'money',

    ),
);
