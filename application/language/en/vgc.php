<?php
/*
You may not have dots inside the keys


*/
return array(
'language_key_not_found' => '[language key \':key\' not found in language \':language\']',

// COMMON

    'common' => array(
        
        'blogfeed' => 'Blog RSS/Atom feed',

        'country' => 'Country',

        'developer' => 'Developer',
        'devices' => 'Devices',

        'email' => 'Email',

        'game' => 'Game',
        'genres' => 'Genres',

        'languages' => 'Languages',
        'logo' => 'Logo URL',

        'name' => 'Name',
        'nbplayers' => 'Number of players',

        'operatingsystems' => 'Operating systems',

        'pitch' => 'Pitch',
        'presskit' => 'Press Kit',

        'reviews' => 'Reviews',

        'screenshots' => 'Screenshots',
        'select-first-option' => 'Select below or select this option to delete the item',
        'site_slogan' => 'The participative and searchable video games database',
        'socialnetworks' => 'Social networks',
        'stores' => 'Stores',
        'subscription' => 'Subscription',

        'tags' => 'Tags',
        'teamsize' => 'Teamsize',
        'technologies' => 'Technologies',
        'text-url-delete-help' => 'Leave a title blanck to delete an item.',
        'themes' => 'Themes',
        'title' => 'Title',

        'url' => 'URL',

        'videos' => 'Videos',
        'viewpoints' => 'Point of view',

        'website' => 'Website URL',
    ),


// HOME

    'home' => array(
        'title' => 'Home',

        'participative' => array(
            'title' => 'Participative',
            'text' => 'It would be presumptuous to pretend that we can fill hundreds of thousand of extensive developers and games profiles in the database ourself. That\'s where <strong>you</strong> come in ! <br>
            <br>
            VGC allow anyone to submit a developer or game profile but ensure that the developer is in control and has reviewed the profile before it\'s published. <br>
            <br>
            You liked a game that is not on VGC yet ? <br>
            The best way to show your support to the developer after bying their games is to add their profile yourself so that they gets to know VGC and everyone on VGC gets to know them. <br> 
            Plus, they may be interested in the services we provide !',
            
            'submission_explanation_link' => 'Learn more about the public submission process',
            'submission_explanation_text' => 'When a new profile is submitted via the public forms, it goes in a first peer review. It\'s a matter of checking if the email belongs to the developer and if there is no offensive or inappropriate content. <br>
            <br>
            Once the profile passed the review, an email is sent to the developer to let him know that someone created a profile for them. The profile is still private and the developer must review and update/complete it before sending it to a second peer review. <br>
            <br>
            The profile become public and is visible and searchable by everyone only when it has passed that second review. <br>
            <br>
            The "trusted" users (who have a public developer profile and at least one public game profile) have access to the peer reviews. They can consult the profile and accept it or issue a report if something is wrong. <br>
            <br>
            Profiles pass reviews when they have a minimum number of approval and no open reports.',

            'adddev_link' => 'Add a developer',
            'addgame_link' => 'Add a game',
        ),

        'searchable_title' => 'Searchable',
        'searchable_text' => 'Any piece of information featured on the profiles can be used as a filter criterion.
        <br>
        That means that you can built complex searchs that return only <strong>the handfull of games you are really interested on</strong>.',
        'searchable_link' => 'Search for a profile',


        'services_title' => 'Services',
        'services_text' => 'VGC also provide some cool services to game developers :',

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
        
        'name_help' => 'Company name, if applicable.',
        'email_help' => 'This should be the main contact email to be publiquely displayed on the profile. It may not be different than your user email.',

        'pitch' => 'Explain about the developer\'s phylosophy, goals...',

        'technologies_help' => 'The technologies the developer works with :',
        'operatingsystems_help' => 'The operating systems the developer\'s games run on (not applicable for most consoles) :',
        'devices_help' => 'The devices the developer\'s games are playable on :',
        'stores_help' => 'The stores the developer sells their games on :',
       
        'add' => array(
            'title' => 'Add a developer profile',

            'required_field' => 'The developer\'s name and email are required fields.',
            'submit' => 'Add this developer profile',

            'success' => 'Thanks you, the developer has successfully been added, an email will be send to let them now. \n 
          They must first check the data then make their account public before you can add one of their game.',
        ),

        'edit' => array(
            'title' => 'Edit a developer profile',
            'submit' => 'Edit this developer profile',
        ),

        'profile' => array(
            'title' => 'Developer',
            'website' => 'Go to the developer\'s website',
        ),
    ),


// GAME 
    'game' => array(
        'cover' => 'Box cover URL',
        'devstate' => 'Developement state',

        'pitch' => 'Game story, features...',

        'publishername' => 'Publisher name',
        'publisherurl' => 'Publisher\'s website URL',

        'soundtrackurl' => 'Soundtrack URL',
        
        'releasedate' => 'Release date',


        'languages_help' => 'The languages the game is playable in :',
        'technologies_help' => 'The technologies the game is made with :',
        'operatingsystems_help' => 'The operating systems the game runs on (not applicable for most console games) :',
        'devices_help' => 'The devices the game is playable on :',
        'nbplayers_help' => 'How many players can play the game at the same time. <br>
        Co-op is usually 1 to 4 humans vs the computer. <br>
        Multiplayer is human(s) vs human(s), often limited to 64 (sometime a couple hundreds) players on the same server.',
        'themes_help' => 'Futuristic = up until a hundred year or so from now. After that, Sci-fi is more appropriate.',
        'genres_help' => '',
        'tags_help' => '',
        'viewpoints_help' => 'Isometric = 2.5D = most management/strategy game, whether they are in 2D or actual 3D. <a href="http://en.wikipedia.org/wiki/Category:Video_games_with_isometric_graphics" title="Go to the Wikipedia page \'Video games with isometric graphics\'">Example of isometric games</a>.',
        'stores_help' => 'The stores the game is purchasable from :',

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
            'soundtrack' => 'Get the soudtrack',
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


// ADMIN

    'admin' => array(
        'bbcode_explanation' => 'You may use HTML in the textarea above (only when editing a profile).',
        // 'You may use the following BBCode tags : b, i and url=. Full urls are automatically parsed in links.'
        'user' => array(
            'edit_title' => 'Edit a user account',
            'id' => 'User Id',

            'subscription_title' => 'Subscriptions',

            'secret_key_help' => 
            'This string is used to access various things linked to your user account like your report RSS feed or the cross-promotion service.<br>
            This is not a hash of something, this is just a random and unique string stored as-is in the database. You may update it.',
            'old_password_help' => 'In order to update your password, enter your old password here.',

        ),
    ),


// CROSS PROMOTION

    'crosspromotion' => array(
        'title' => 'Cross-promotion',

        'home_text' => 
        'VGC provides <strong>an evolutive, easy to setup and easy to use cross-promotion solution</strong> for your games. <br>
        Setup once in your game then update the promoted content  whenever you want online : <br>
        <br>
        Make sure the games you want to promote have a profile on VGC, then select them for cross-promotion in your game\'s profile. <br>
        From your game, just query VGC to get the profiles you selected for cross-promotion in that game. <br>',

        'edit_game_subscribers_help' => 
        'Select the games you want to promote from this game. <br>
        Then from the game query the following URL : :url',

        'edit_game_non_subscribers_msg' => 'To easily promote other games from within this game <a href=":edituser_link">you can subscribe to the cross-promotion service</a> from your user account.',

        'edit_user_subscription_text' => 
        'To be able to cross-promote in your game with VGC, you must subscribe to the service first. <br>
        The subscription cost 5€ per month. <br>
        You can subscribe by clicking the button below, with a Paypal account or various credit cards. You will be able to unsubscribe at any time via a similar button or via the Paypal dashboard.<br>
        <br>
        Once subscribed, the cross-promotion service must be activated manually by an administrator, which can take up to a few hours.',

        'edit_user_unsubscription_text' => 
        'You have subscribed to the cross-promotion service, <strong>thanks you</strong> ! <br>
        [insert explanation here on how to use it] <br>
        <br>
        If you want to unsubscribe, click on the button below. It will send you to a Paypal page which will prompt you for confirmation. <br>
        Once you unsubscribed, the cross-promotion will be deactivated by an administrator without a few hours.',

        'what_is_it' => 
        'Cross-promotin is a kind of advertising where a product is promoted from within another product. <br>
        It has two main benefits : it\'s free since you don\'t have to rent advertising space inside you own game and it\'s <em>a priori</em> well targetted since we can imagine that players of one of your game might be interrested in your other games as well. <br>
        <br>
        Classic online advertising cost so much that most small to medium sized game studios just can\'t afford it. <br>
        Cross-promotion is a cheaper alternative that can prove effective if you promote not only your other games but also other developer\'s games. <br>
        Creating a network of cross-promotion

        if you promote a game made by another developer, tell them, they want to do the same with your game ! 
        ',

        'how_it_works' => 
        'Once you will have subscribed to the service, you will have acces to a new section on your games profile\'s where you can select games to promote. <br>
        <br>
        From within your game, you just have to query an URL with your game id to get the list of the profiles you selected in the first step. <br>
        And that\'it.<br>
        <br>
        Once you properly setted up the system in your game you can just update the promoted games from your game profile on VGC without the need to update/patch your actual game.',

    ),


// SEARCH

    'search' => array(
        'title' => 'Search profiles',

        'name_or_pitch_help' => 'Whose',
        
        'words_contains' => 'Contains',
        'words_contains_all' => 'All',
        'words_contains_any' => 'Any of',

        'words_list' => 'the following words :',

        'words_search_mode' => 'Search mode :',
        'words_search_mode_whole' => 'As entire words',
        'words_search_mode_part' => 'As part of words',

        'no_profile_found' => 'Sorry, no profiles where found with these criteria.',
        'profiles_found' => ':num profiles were found :',


        'dev' => array(
            


            'stores_help' => 'are sold on,',
            'devices_help' => 'are playable on,',
            'operatingsystems_help' => 'runs on,',
            'technologies_help' => 'and are made with,',

            'submit' => 'Search for developers',
        ),

        'game' => array(
            'stores_help' => 'are sold on,',
            'devices_help' => 'are playable with,',
            'operatingsystems_help' => 'runs on,',
            'technologies_help' => 'and are made with :',

            'submit' => 'Search for games',
        ),


        'msg' => array(
            'id_not_found' => 'The search with id [:id] has not been found !',
        ),

        
    ),

// LANGUAGES

    'languages' => array(
        'title' => 'Languages',

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
        'title' => 'Technologies',

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
        'slidedb' => 'Slide DB',
        'linkedin' => 'Linked In',
        'moddb' => 'Mod DB',
    ),

// Nb PLAYERS

    'nbplayers' => array(
        'title' => 'Players',

        'singleplayer' => 'Single player',
        'coop' => 'Co-op',
        'multiplayer' => 'Multiplayer',
        'mmo' => 'MMO (Massively Multiplayer Online)',
    ),

// DEVELOPEMENT STATES

    'developmentstates' => array(
        'title' => 'Developement state',

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
        'cartoon' => 'Cartoon',
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
        'title' => 'Genres',

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
        'title' => 'Point of view',

        '2d' => '2D',
        'firstperson' => 'First Person',
        'isometric' => 'Isometric',
        'thirdperson' => 'Third Person',
        'topdown' => 'Top-Down',
    ),

// TAGS

    'tags' => array(
        'title' => 'Tags',

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
        'topdown' => 'Top-Down',
    ),
);
