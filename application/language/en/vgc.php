<?php
/*
You may not have dots inside the keys


 */
return array(
'language_key_not_found' => '[language key \':key\' not found in language \':language\']',


// MESSAGES (errors, success)

    'messages' => array(
        'adddev_success' => 'The developer profile with name \":name\" has successfully been created.',
        'addgame_success' => 'The game profile with name \":name\" has successfully been created.',

        'editdev_nametaken' => 'Can\'t rename the developer profile with name \":name\" (id : :id) because the name \":newname" is already taken.',
        'editgame_nametaken' => 'Can\'t rename the game profile with name \":name\" (id : :id) because the name \":newname" is already taken.',
        
        'editdev_success' => 'The developer profile with name \":name\" (id : :id) has successfully been updated.',
        'editgame_success' => 'The game profile with name \":name\" (id : :id) has successfully been updated.',
    ),  

// MENU

    'menu' => array(
        'home' => 'Home',
        'featured' => 'Featured',
        'search' => 'Search',
        'about' => 'About',
        'adddeveloper' => 'Add a developer',
        'addgame' => 'Add a game',
        'login' => 'Log In',
        'logout' => 'Log Out',
        'languages' => 'Languages',
    ),


// LOGIN

'login_name_label' => 'Your user name, email or id',
'login_password_label' => 'Your password',
'login_submit' => 'Log In',
'login_lost_password' => 'I lost my password',


// HELP

'help_required_field' => 'Required field',



// DEVELOPER 
    
    // GENERIC DEVELOPER STRINGS

    'developer_name' => 'Name',
    'help_developer_name' => 'Company name, if applicable.',

    'developer_email' => 'Email',
    'developer_pitch' => 'Explain about the developer\'s phylosophy, goals...',
    'developer_logo' => 'Logo URL',
    'developer_website' => 'Website URL',

    'developer_blogfeed' => 'Blog RSS/Atom feed',
    'developer_profile_blogfeed' => 'Lastest articles from the blog',

    'developer_country' => 'Country',
    'developer_teamsize' => 'Teamsize',

    'developer_socialnetworks' => 'Social networks',
    'developer_socialnetworks_name' => 'Name',
    'developer_socialnetworks_url' => 'url',
    'developer_socialnetworks_url_placeholder' => 'Full profile URL',

    'developer_technologies' => 'Technologies',
    'help_developer_technologies' => 'The technologies the developer works with',

    'developer_operatingsystems' => 'Operating systems',
    'help_developer_operatingsystems' => 'The operating systems the developer\'s games run on (not applicable for most consoles)',

    'developer_devices' => 'Devices',
    'help_developer_devices' => 'The devices the developer\'s games are playable on',

    'developer_stores' => 'Stores',
    'help_developer_stores' => 'The stores the developer sells their games on',


    // ADD DEVELOPER SPECIFIC TERMS

    'adddeveloper_title' => 'Add a developer profile',

    'adddeveloper_required_field' => 'The developer\'s name and email are required fields.',
    'adddeveloper_submit' => 'Add this developer profile',

    'adddeveloper_success' => 'Thanks you, the developer has successfully been added, an email will be send to let them now. \n 
    They must first check the data then make their account public before you can add one of their game.',


    // EDITDEVELOPER
    'editdeveloper_title' => 'Edit a developer profile',
    'editdeveloper_submit' => 'Edit a developer profile',


    // DEVELOPER PROFILE SPECIFIC TERMS

    'developer_profile_title' => 'Developer',
    'developer_website_title' => 'Go to the developer\'s website',




// GAME 
    'game' => array(
        // GENERIC GAME STRINGS
        'fields' => array(
            'name' => 'Game title',

            'developer' => 'Developer\'s name',
            'pitch' => 'Game story, features...',

            /*'developementstates' => array(
                'legend' => 'Developement state',
                'concept' => 'Concept/Design phase',
                'prototype' => 'Prototype phase',
                'earlyproduction' => 'Early production',
                'internalplayablebuilt' => 'Internal playable built',
                'publicplayablebuilt' => 'Public playable built',
                'released' => 'Released',
                'canceled' => 'Canceled',
            ),*/

            'logo' => 'Logo URL',
            'website' => 'Website URL',
            'blogfeed' => 'Blog RSS/Atom feed',
            'publishername' => 'Publisher name',
            'publisherurl' => 'Publisher\'s website url',
            'soundtrackurl' => 'Soundtrack URL',

            'price' => 'Price',
            'price_help' => 'This is a text field, write whatever is appropriate.',

            'releasedate' => 'Release date',
            'country' => 'Country',

            

            'languages' => 'Languages',
            'languages_help' => 'The languages the game is playable in',

            
            

            'technologies' => 'Technologies',
            'technologies_help' => 'The technologies the game is made with',

            'operatingsystems' => 'Operating systems',
            'operatingsystems_help' => 'The operating systems the game runs on (not applicable for most console games)',

            'devices' => 'Devices',
            'devices_help' => 'The devices the game is playable on',

            'nbplayers' => 'Number of players',
            'nbplayers_help' => 'How many players can play the game at the same time :',

            'themes' => 'Themes',
            'themes_help' => ' ',

            'genres' => 'Genres',
            'genres_help' => ' ',

            'tags' => 'Tags',
            'tags_help' => ' ',

            'viewpoints' => 'Point of view',
            'viewpoints_help' => ' ',


            'socialnetworks_title' => 'Social networks',
            'socialnetworks_help' => '',
            'socialnetworks_name' => 'Name',
            'socialnetworks_url' => 'URL',

            'stores_title' => 'Stores',
            'stores_help' => 'The stores the game is purchasable from.',
            'stores_name' => 'Name',
            'stores_url' => 'URL',

            'screenshots_title' => 'Screenshots',
            'screenshots_name' => 'Title',
            'screenshots_url' => 'URL',
            
            'videos_title' => 'Video and trailers',
            'videos_name' => 'Title',
            'videos_url' => 'URL',
        ),

        'add' => array(
            'title' => 'Add a game profile',
            'submit' => 'Submit this game profile',
            'success' => 'Thanks you, the game has successfully been added. The developers must first check the data then make the profile public before he shows up in the search.',
        ),

        'profile' => array(
            'title' => 'Game',
            'website' => 'Go to the game\'s website',
            'blogfeed' => 'Lastest articles from the blog',
        ),

    ), // end of game

    // ADD GAME SPECIFIC TERMS
    
    

    // GAME PROFILE SPECIFIC TERMS

    



// LANGUAGES

    'languages' => array(
        'french' => 'French',
        'english' => 'English',
        'german' => 'German',
        'spanish' => 'Spanish',
        'italian' => 'Italian',
        'russian' => 'Russian',
        'japonese' => 'Japonese',
        'chinese' => 'Chinese',
    ),


// COUNTRIES

    'countries' => array(
        'australia' => 'Australia',
        'austria' => 'Austria',
        'belgium' => 'Belgium',
        'canada' => 'Canada',
        'france' => 'France',
        'germany' => 'Germany',
        'greece' => 'Greece',
        'holland' => 'Holland',
        'ireland' => 'Ireland',
        'portugal' => 'Portugal',
        'russia' => 'Russia',
        'spain' => 'Spain',
        'switzerland' => 'Switzerland',
        'uk' => 'United Kingdom',
        'usa' => 'United States of America',
    ),

// OPERATING SYSTEMS

    'operatingsystems' => array(
        'android' => 'Android',
        'blackberry' => 'BlackBerry',
        'ios' => 'iOS',
        'linux' => 'Linux',
        'mac' => 'Mac OS',
        'windowsdesktop' => 'Windows desktop',
        'windows8metro' => 'Windows 8 Metro',
        'windowsphone' => 'Windows Phone',
    ),

// DEVICES

    'devices' => array(
        '3ds' => '3DS',
        '3dsxl' => '3DS XL',
        'androidtablet' => 'Android Tablet',
        'androidsmartphone' => 'Android Smartphone',
        'blackberrysmartphone' => 'BlackBerry Smartphone',
        'browser' => 'Browser',
        'ds' => 'DS',
        'ipod' => 'iPod',
        'iphone' => 'iPhone',
        'ipad' => 'iPad',
        'mac' => 'Mac',
        'ouya' => 'Ouya',
        'pc' => 'PC',
        'ps3' => 'PS3',
        'psp' => 'PSP',
        'psvita' => 'PS Vita',
        'wii' => 'Wii',
        'wiiu' => 'Wii U',
        'windowsphonetablet' => 'Windows Phone Tablet',
        'windowsphonesmartphone' => 'Windows Phone Smartphone',
        'xbox360' => 'xBox 360',
        'xperiaplay' => 'Xperia Play',
        'xperiasmartphone' => 'Xperia Smartphone',
    ),

// TECHOLOGIES

    'technologies' => array(
        'blender' => 'Blender Game Engine',
        'flash' => 'Flash',
        'flixel' => 'Flixel',
        'java' => 'Java',
        'python' => 'Python',
        'stencyl' => 'Stencyl',
        'adventuregamestudio' => 'Adventure Games Studio',
        'air' => 'Adobe AIR',
        'craftstudio' => 'CraftStudio',
        'cryengine' => 'Cry Engine',
        'custom' => 'Custom-built/In-house',
        'gamemaker' => 'GameMaker',
        'html5' => 'HTML5 / JS',
        'impactjs' => 'Impact JS',
        'ogre3d' => 'Ogre 3D',
        'rpgmaker' => 'RPG Maker',
        'shiva3d' => 'Shiva 3D',
        'source' => 'Source Engine',
        'torque' => 'Torque Engine',
        'udk' => 'Unreal Developement Kit',
        'unity3d' => 'Unity 3D',
        'unrealengine' => 'Unreal Engine',
        'xna' => 'XNA',
    ),

// STORES

    'stores' => array(
        'desura' => 'Desura',
        'gameolith' => 'Gameolith',
        'gamersgate' => 'Gamersgate',
        'impulse' => 'Impulse',
        'indiecity' => 'Indiecity',
        'indievania' => 'Indievania',
        'kongregate' => 'Kongregate',
        'newsground' => 'Newsground',
        'steam' => 'Steam',
        'amazonmarket' => 'Amazon Marketplace',
        'androidmarket' => 'Android Marketplace',
        'applestore' => 'Apple Store',
        'armorgames' => 'Armor Games',
        'googleplay' => 'Google Play',
        'greenmangaming' => 'GreenManGaming',
        'website' => 'Company/Game Website',
        'windowsstore' => 'Windows 8 Store',
        'xbla' => 'xBox Live Arcade',
        'xblig' => 'xBox Live Indie Games',
    ),

// SOCIAL NETWORKS

    'socialnetworks' => array(
        'desura' => 'Desura',
        'facebook' => 'Facebook',
        'pinterest' => 'Pinterest',
        'reddit' => 'Reddit',
        'steam' => 'Steam',
        'twitter' => 'Twitter',
        'googleplus' => 'Google+',
        'indiedb' => 'Indie DB',
        'linkedin' => 'Linked In',
        'moddb' => 'Mod DB',
    ),

// Nb PLAYERS

    'nbplayers' => array(
        'singleplayer' => 'Single player',
        'coop' => 'Co-op',
        'multiplayer' => 'Multiplayer',
        'mmo' => 'MMO (Massively Multiplayer Online)',
    ),

// DEVELOPEMENT STATES

    'developementstates' => array(
        'concept' => 'Concept/Design phase',
        'prototype' => 'Prototype phase',
        'earlyproduction' => 'Early production',
        'firstgameplay' => 'First gameplay',
        'publicplayablebuilt' => 'Public playable built',
        'released' => 'Released',
        'canceled' => 'Canceled',
    ),

// THEMES

    'themes' => array(
        'fantasy' => 'Fantasy',
        'medieval' => 'Medieval',
        'comic' => 'Comic',
        'futuristic' => 'Futuristic',
        'horror' => 'Horror',
        'mafia' => 'Mafia',
        'modern' => 'Modern',
        'scifi' => 'Sci-fi',
        'steampunk' => 'Steampunk',
        'western' => 'Western',
    ),

// GENRES

    'genres' => array(
        'action' => 'Action',
        'adventure' => 'Adventure',
        'arcade' => 'Arcade',
        'fighting' => 'Fighting',
        'platformer' => 'Platformer',
        'puzzle' => 'Puzzle',
        'racing' => 'Racing',
        'shooter' => 'Shooter',
        'simulation' => 'Simulation',
        'sport' => 'Sport',
        'strategy' => 'Strategy',
        'citybuilding' => 'City Building',
        'pointandclick' => 'Point-and-Click',
        'resources' => 'Resources Management',
        'roguelike' => 'Rogue-like',
        'roleplaying' => 'Role playing',
        'rts' => 'Real Time Strategy',
        'shootemup' => 'Shoot\'Em\'Up',
        'towerdefense' => 'Tower Defense',
    ),

// POINT OF VIEW

    'viewpoints' => array(
        'firstperson' => 'First Person',
        'isometric' => 'Isometric',
        'thirdperson' => 'Third Person',
        'topdown' => 'Top-Down',
    ),

// TAGS

    'tags' => array(
        '25d' => '2.5D',
        '2d' => '2D',
        '3d' => '3D',
        'casual' => 'Casual',
        'cellshading' => 'Cell Shading',
        'fun' => 'Fun',
        'hardcore' => 'Hardcore',
        'leveleditor' => 'Level Editor',
        'physics' => 'Physics',
        'sidescrolling' => 'Side-Scrolling',
        'turnbased' => 'Turn-Based',
    ),

// REPORTS

    'report' => array(
        'title' => 'Report this profile',
        'description' => 'Description',
        'description_placeholder' => '10 characters minimum',
        'recipient' => 'Report recipient',
        'developer' => 'Developer and Admins',
        'admin' => 'Admins only',
        'submit' => 'Submit this report',
        'gobacktoprofile' => 'Go back to the profile',
        'form_success' => 'Thanks, you. The report has been saved.',
    ),
);
