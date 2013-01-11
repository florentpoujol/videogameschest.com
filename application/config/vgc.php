<?php
return array(
    
    "site_languages" => array(
        "english",
        "french"
    ),

    'lang_files' => array('vgc', 'email'),
    

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

    'form_attributes_to_clean' => array('csrf_token', 'password_confirmation', 'old_password', 'controller'),

    'dummie_password' => 'testtest', //r!&5éT[79m},D?4â+5w% temp password while user is in submission review

     



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

    "themes" => array(
        "fantasy",
        "medieval",
        "cartoon",
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
        "2d",
        "firstperson",
        "isometric",
        "thirdperson",
    ),

    "tags" => array(
        "2d",
        "25d",
        "3d",
        "casual",
        "celshading",
        "fun",
        "hardcore",
        "leveleditor",
        "physics",
        "sidescrolling",
        "turnbased",
        "topdown",
    ),

    // ICONS


    'icons' => array(
        'facebook' => 'img/icons/facebook.png',
        'twitter' => 'img/icons/twitter.png',
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
    ),
);
