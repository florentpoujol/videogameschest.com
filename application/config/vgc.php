<?php
return array(
    
    'admin_email' => 'contact@videogameschest.com',
    'automatic_email_from' => 'noreply@videogameschest.com',
    'automatic_email_from_name' => 'The VideoGamesChest mailer',

    "date_formats" => array(
        "date_sql" => "Y-m-d",
        "datetime_sql" => "Y-m-d H:i:s",
        "english" => "d M Y - g:ia",
        "nonenglish" => "d M Y - G\\hi",
        "blog" => "d M Y",
        "blog_sidebar" => "d M",
        'rss' => 'r', // 'D, d M Y H:i:s O',
    ),

    'environment' => 'common',

    'form_attributes_to_clean' => array('csrf_token', 'password_confirmation',
     'old_password', 'controller', 'captcha', 'recaptcha_challenge_field', 'recaptcha_response_field',
      'city'),


    'language_files' => array('vgc', 'emails'),

  
    // field that needs an admin review before their update is approved
    'profile_fields_to_review' => array(
        'common' => array('name', 'price', 'release_date', 'links', 'screenshots', 'videos', ),
    ),



    'profiles_post_create_rules' => array(
        'game' => array(
            'name' => 'required|alpha_dash_extended|min:2|unique:games',
            'developer_name' => 'required|alpha_dash_extended|min:2',
            'developer_url' => 'url',
            'publisher_name' => 'min:2',
            'publisher_url' => 'url',
            'website' => 'url',
            'blogfeed' => 'url',
            'presskit' => 'url',

            'profile_background' => 'url',
            'cover' => 'url',
            'soundtrack' => 'url',
        ),
    ),


    'profiles_post_update_rules' => array(
        'game' => array(
            'name' => 'required|alpha_dash_extended|min:2',
            'developer_name' => 'required|alpha_dash_extended|min:2',
            'developer_url' => 'url',
            'publisher_name' => 'min:2',
            'publisher_url' => 'url',
            'website' => 'url',
            'blogfeed' => 'url',
            'presskit' => 'url',

            'profile_background' => 'url',
            'cover' => 'url',
            'soundtrack' => 'url',
        ),
    ),


    'profile_types' => array('game'),

    'privacy' => array('private', 'public'),

    'recaptcha_private_key' => '6LeL59wSAAAAAPHo33Qt8iyf71Mf0U-QGET3IlhE',
    'recaptcha_public_key' =>  '6LeL59wSAAAAAPd08fVY9Fq1loW04p0kldVFqsWS',

    "site_languages" => array(
        "english",
    ),

    'social' => array(
        'twitter_url' => 'https://twitter.com/videogameschest',
        'facebook_url' => 'http://www.facebook.com/Videogameschest',
        'google+_url' => 'https://plus.google.com/104684794332555704876',
    ),

    'user_types' => array('user', 'admin'),

 

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
        'gamestick',
        "ipod",
        "iphone",
        "ipad",
        "mac",
        "ouya",
        "pc",
        "ps3",
        'ps4',
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
        'love',
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
        "xna",
        'yna',
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

    "nbplayers" => array(
        "singleplayer",
        "coop",
        "mmo",
        "multiplayer"
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
        "modern",
        "futuristic",
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
        'facebook' => 'img/icons/facebook-512.png',
        'twitter' => 'img/icons/twitter-512.png',
        'rss' => 'img/icons/rss-128.png',
        'google+' => 'img/icons/google+-301.png',
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
        'comments',
        'comments-alt',
        'caret-right',
        'double-angle-right',

    ),
);
