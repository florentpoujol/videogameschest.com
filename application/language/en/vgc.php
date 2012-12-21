<?php
/*
You may not have dots inside the keys


*/
return array(
'language_key_not_found' => '[language key \':key\' not found in language \':language\']',

// COMMON

    'common' => array(
        'teamsize' => 'Teamsize',
        'country' => 'Country',
    ),

// MESSAGES (errors, success)

    'messages' => array(
        'adddev_success' => 'The developer profile with name \":name\" has successfully been submitted.',
        'addgame_success' => 'The game profile with name \":name\" has successfully been submitted.',

        'editdev_nametaken' => 'Can\'t rename the developer profile with name \":name\" (id : :id) because the name \":newname\" is already taken.',
        'editgame_nametaken' => 'Can\'t rename the game profile with name \":name\" (id : :id) because the name \":newname" is already taken.',
        
        'editdev_success' => 'The developer profile with name \":name\" (id : :id) has successfully been updated.',
        'editgame_success' => 'The game profile with name \":name\" (id : :id) has successfully been updated.',

        'logged_in_only' => "You must be logged in to access this page.",
        'admin_only' => "You must be an administrator to access this page.",
        'admin_and_logged_in' => "You must be logged in and an administrator to access this page.",

        'can_not_edit_others_games' => "You are not allowed to edit other developer's games.",
        'game_profile_not_found' => "Can't find the game profile with id ':profile_id'.",

        'user_not_trusted' => 'You can\'t acces this page because you are not a trusted user.',
       
    ),  

    'errors' => array(
        'access_not_allowed' => "You are not allowed to access the page : ':page'",

        'developer_profile_name_not_found' => "Can't find the developper profile with name ':name'.",
        'developer_profile_id_not_found' => "Can't find the developper profile with id ':id'.",

        'game_profile_name_not_found' => "Can't find the game profile with name ':name'.",
        'game_profile_id_not_found' => "Can't find the game profile with id ':id'.",
    ),


// MENU

    'menu' => array(
        'home' => 'Home',
        'featured' => 'Featured',
        'search' => 'Search',
        'about' => 'About',
        'adddeveloper' => 'Add a developer',
        'addgame' => 'Add a game',
        'logout' => 'Log Out',
        'languages' => 'Languages',

        'login' => array(
            'name_label' => 'Your user name or email',
            'password_label' => 'Your password',
            'keep_logged_in_label' => 'Keep me logged in',
            'title' => 'Log In',
            'submit' => 'Log In',
            'lost_password' => 'I lost my password',
            'lostpassword_help' => 'If you lost your password, just fill the field below with your username, email or user id and click the button to get a new temporary password by email.',
        ),
    ),


// DEVELOPER 
    'developer' => array(
        'fields' => array(
            'name' => 'Name',
            'name_help' => 'Company name, if applicable.',

            'email' => 'Email',
            'pitch' => 'Explain about the developer\'s phylosophy, goals...',
            'logo' => 'Logo URL',
            'website' => 'Website URL',

            'blogfeed' => 'Blog RSS/Atom feed',
            'profile_blogfeed' => 'Lastest articles from the blog',

            'country' => 'Country',
            'teamsize' => 'Teamsize',

            'socialnetworks' => 'Social networks',
            'socialnetworks_name' => 'Name',
            'socialnetworks_url' => 'url',
            'socialnetworks_url_placeholder' => 'Full profile URL',

            'technologies' => 'Technologies',
            'technologies_help' => 'The technologies the developer works with',

            'operatingsystems' => 'Operating systems',
            'operatingsystems_help' => 'The operating systems the developer\'s games run on (not applicable for most consoles)',

            'devices' => 'Devices',
            'devices_help' => 'The devices the developer\'s games are playable on',

            'stores' => 'Stores',
            'stores_help' => 'The stores the developer sells their games on',
        ), // end fields
        
        'add' => array(
            'title' => 'Add a developer profile',

            'required_field' => 'The developer\'s name and email are required fields.',
            'submit' => 'Add this developer profile',

            'success' => 'Thanks you, the developer has successfully been added, an email will be send to let them now. \n 
          They must first check the data then make their account public before you can add one of their game.',
        ),

        'edit' => array(
            'title' => 'Edit a developer profile',
            'submit' => 'Edit a developer profile',
        ),

        'profile' => array(
            'title' => 'Developer',
            'website' => 'Go to the developer\'s website',
        ),
    ),


// GAME 
    'game' => array(
        'fields' => array(
            'name' => 'Game title',

            'developer' => 'Developer\'s name',
            'pitch' => 'Game story, features...',

            'devstate_title' => 'Developement state',

            'cover' => 'Box cover URL',
            'website' => 'Website\'s page URL',
            'blogfeed' => 'Blog RSS/Atom feed',
            'publishername' => 'Publisher name',
            'publisherurl' => 'Publisher\'s website URL',
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
            'viewpoints_help' => 'Not applicable for 2D games',

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
        ),

        'edit' => array(
            'title' => 'Edit a game profile',
            'submit' => 'Edit this game profile',            
        ),

        'profile' => array(
            'title' => 'Game',
            'website' => 'Go to the game\'s website',
            'blogfeed' => 'Lastest articles from the blog',
        ),

    ), // end of game



// REPORTS

    'reports' => array(
        'title' => 'Reports',
        'form_title' => 'Report this profile',

        'help' => 'You may report a profile to the developer in case of typos or broken links. <br>
        But you may only report a profile to the administrators in case of offensive/inappropriate content.',

        'message' => 'Your message',
        'submit_dev' => 'Send to the developer',
        'submit_admin' => 'Send to the administrators',

        'dev_title' => 'Developer reports',
        'admin_title' => 'Admin reports',

        'table' => array(
            'profile' => 'Profile',
            'message' => 'Message',
            'delete' => 'Delete',
        ),

        'msg' => array(
            'create_success' => 'Thank you, the report has been issued successfully.',
            'delete_success' => 'The reports have succsessfully been deleted',
        ),
    ),


// REVIEW

    'reviews' => array(
        'title' => 'Peer Reviews',
        'submission_title' => 'Submission Review',
        'publishing_title' => 'Publishing Review',
        'game_title' => 'Games',
        'developer_title' => 'Developers',

        'no_review' => 'No profile in :review review.',

        'table' => array(
            'approve' => 'Approve',
            'profile' => 'Profile',
            'delete' => '',
        ),

        'msg' => array(
            'profiles_approved' => 'Thanks you for approving :num profile(s).'
        ),
    ),


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
        'title' => 'Countries',

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
        'title' => 'OS',

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
        'title' => 'Devices',

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
        'title' => 'Techonologies',

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
        'title' => 'Stores',

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
        'title' => 'Social Networks',

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

    'developmentstates' => array(
        'concept' => '1 - Concept/Design phase',
        'prototype' => '2 - Prototype phase',
        'earlyproduction' => '3 - Early production',
        'firstgameplay' => '4 - First gameplay',
        'publicplayablebuilt' => '5 - Public playable built',
        'released' => '6 - Released',
        'canceled' => 'Canceled',
    ),


// THEMES

    'themes' => array(
        'title' => 'Themes',

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
        'celshading' => 'Cel Shading',
        'fun' => 'Fun',
        'hardcore' => 'Hardcore',
        'leveleditor' => 'Level Editor',
        'physics' => 'Physics',
        'sidescrolling' => 'Side-Scrolling',
        'turnbased' => 'Turn-Based',
    ),



    // EMAILS

    'emails' => array(
        'developer_passed_submission_review' =>
'Hi :name <br>
<br>
You receive this email because someone created a developer profile on <a href="http://videogameschest.com" title="">VideoGamesChest.com</a> <br>
<br>
Log in to your account with your name, email or id (:id) and your temporary password : :password. Don\'t forget to edit your user account to change the password.<br>
<br>
A developer profile is linked to your user account. The profile is now private and you may review it and <a href="http://videogameschest.com/admin/editdeveloper/:id">edit it</a>. <br>
Once you are satisfied with the informations it contains, you may send your profile in the Publishing review. <br>
<br>
<br>
Thanks,<br>
The VideoGamesChest.com team
',
        
        'developer_passed_publishing_review' => 'developer_passed_publishing_review',
        'developer_failed_publishing_review' => 'developer_failed_publishing_review',

        'game_passed_submission_review' => 'game_passed_submission_review',
        'game_passed_publishing_review' => 'game_passed_publishing_review',
        'game_failed_publishing_review' => 'game_failed_publishing_review',
    ),

);
