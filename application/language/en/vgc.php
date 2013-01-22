<?php
/*
You may not have dots inside the keys


*/
return array(

// COMMON

    'common' => array(
        'add_names_help' => 'One or several (comma separated) profile\'s names or ids.',
        'add_profile' => 'Add this profile',

        'bbcode_explanation' => 'You may use the following BBCode tags : h1, h2, b and i.',
        // 'You may use HTML in the textarea above (only when editing a profile).'

        'blogfeed' => 'Blog RSS feed',
        'blogfeed_help' => 'No Atom feed, only RSS.',

        'country' => 'Country',

        'date' => 'Date',
        'delete' => 'Delete',
        'developer' => 'Developer',
        'developer_name' => 'Developer\'s name',
        'developer_url' => 'Developer\'s website',
        'developers' => 'Developers',
        'devices' => 'Devices',

        'email' => 'Email',
        'edit_profile' => 'Edit this profile',

        'game' => 'Game',
        'games' => 'Games',
        'general' => 'General',
        'genres' => 'Genres',

        'insert_captcha' => 'Insert captcha',

        'languages' => 'Languages',
        'logo' => 'Logo',

        'medias' => 'Medias',

        'name' => 'Name',
        'nbplayers' => 'Number of players',

        'operatingsystems' => 'Operating systems',

        'pitch' => 'Pitch',
        'presskit' => 'Press Kit',
        'profile' => 'Profile',
        'profile_background' => 'Background image',
        'profile_background_help' => 'Background image for the profile\'s header. Max-height : 250px. Ideal min-width : 1000px.',
        'profile_blogfeed' => 'Latest articles',
        'profile_list_empty' => 'No profile to display',
        'publisher' => 'Publisher',
        'publisher_name' => 'Publisher\'s name',
        'publisher_url' => 'Publisher\'s website',

        'report_profile_link' => 'Report this profile',
        'reviews' => 'Reviews',

        'screenshots' => 'Screenshots',
        'select_first_option' => 'Select below',
        'select_arrayitem_first_option' => 'Select below or select this option to delete the item',
        //'site_slogan' => 'The participative and searchable video games database',
        'site_slogan' => 'The place to hunt down for the treasures of video games',
        'socialnetworks' => 'Social networks',
        'soundtrack' => 'Soundtrack',
        'stores' => 'Stores',
        'submit_profile' => 'Submit this profile',
        'subscription' => 'Subscription',

        'tags' => 'Tags',
        'teamsize' => 'Teamsize',
        'technologies' => 'Technologies',
        'text_url_delete_help' => 'Leave a title blanck to delete an item.',
        'themes' => 'Themes',
        'title' => 'Title',

        'update' => 'Update',
        'url' => 'URL',
        'username' => 'User name',

        'videos' => 'Videos',
        'viewpoints' => 'Point of view',
        'view_profile_link' => 'View this profile',

        'website' => 'Website',




        'msg' => array( 
            'edit_other_users_profile_not_allowed' => 'You are not allowed to edit profiles which does not belong to you.',
            'access_not_allowed' => "You are not allowed to access the page ':page'",
            'page_not_found' => 'Sorry, the page you were looking for was not found.'
        ),
    ),

// HOME

    'home' => array(
        'title' => 'Home',

        'herounit_text' => '
        Video Games Chest tackle the problem of the discoverability of games by providing inovatives and effectives ways for the player to find games they are really interested to play
         as well as for the developers to pitch their games only to the audience really interested in it.

        ',

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
        
        'addgame_success' => 'The game profile with name \":name\" has successfully been submitted.',

        'editdev_nametaken' => 'Can\'t rename the developer profile with name \":name\" (id : :id) because the name \":newname\" is already taken.',
        'editgame_nametaken' => 'Can\'t rename the game profile with name \":name\" (id : :id) because the name \":newname" is already taken.',
        
        
        

        'logged_in_only' => "You must be logged in to access this page.",
        'admin_only' => "You must be an administrator to access this page.",
        'admin_and_logged_in' => "You must be logged in and an administrator to access this page.",

        'can_not_edit_others_games' => "You are not allowed to edit other developer's games.",
        'game_profile_not_found' => "Can't find the game profile with id ':profile_id'.",

        'user_not_trusted' => 'You can\'t acces this page because you are not a trusted user.',
       
    ),  

    'errors' => array(
        

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
        'find' => 'Find',
        'advertising' => 'Be finded',
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

        'register' => 'Register'
    ),

// REGISTER
    
    'register' => array(
        'title' => 'Create a user account',
        'submit' => 'Register !',
        'username' => 'Choose your user name',
        'password' => 'Choose your password',
        'password_confirmation' => 'Verify password',

        'msg_register_success' => 'Thank you :username, your user account has been created. 
        An email has been sent to your email adress with a link to activate your account. 
        You will be able to login as soon as to activated your account.',

        'msg_confirmation_error' => "Unable to activate the user account because none with id ':id' and url key ':url_key' has been found, or the user account is already activated.",
        'msg_confirmation_success' => 'Thank you :username, you have successfully activated your user account, you may now log in.',

    ),

// LOGIN

    'login' => array(
        'name_label' => 'Your user name or email',
        'password_label' => 'Your password',
        'keep_logged_in_label' => 'Keep me logged in',
        'title' => 'Log In',
        'submit' => 'Log In',
        'lost_password' => 'I lost my password',
        'lostpassword_help' => 'If you lost your password, just fill the field below with your username, email or user id and click the button to get a new temporary password by email.',
    

        'msg' => array(
            'wrong_password' => "The password provided for user :field ':username' is incorrect.",
            'not_activated' => "The user account with the :field ':username' is not activated yet. You will be able to login once you will have clicked the activation link that can be found in the email sent to you upon registration.",
            'user_not_found' => "No user with the :field ':username' has been found.",

            'login_success' => 'Welcome :username, you successfully logged in.',
            'logout_success' => 'You successfully logged out. See you soon !',
        ),
    ),

// LOSTPASSWORD

    'lostpassword' => array(
        'msg' => array(
            'confirmation_email_sent' => 'An email with a confirmation link has been sent to your email adress. Click the link in that email to generate a new temporary password.',
            'confirmation_error' => 'Unable to generate a new password because no user account with id ":id" and url key ":url_key" has been found, or the user account is not activated.',
            'new_password_success' => 'A new password has been successfully generated and sent to your email adress.',

        ),
    ),

// USER

    'user' => array(
        'edit_title' => 'Edit your user account',
        'edit_password' => 'Edit your password',
        
        'id' => 'User Id',

        'subscription_title' => 'Subscriptions',

        'url_key_help' => 'This random string is used to access various things related to your user account via URL. You may update it but keep it secret.',
        'old_password_help' => 'In order to update your password, enter your old password here.',

        'msg' => array(
            'wrong_old_password' => 'The old password does not match your current password.',
            'update_success' => 'Your user account has successfully been updated.',
            'edituser_nametaken' => "Can't rename the user ':username' (id : :id) because the name ':newname' is already taken.",
            'user_not_found' => "Sorry the user with id ':id' has not been found."
        ),

    ),

// DEVELOPER 

    'developer' => array(
        
        'name_help' => 'Company name, if applicable.',
        'email' => 'Contact email',

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
            'select_profile_placeholder' => 'Name or id',
            'select_profile_help' => 'Select the developer to edit',
        ),

        'profile' => array(
            'title' => 'Developer',
            'website' => 'Go to the developer\'s website',
            'no_game' => 'This developer has no game yet',
        ),

        'msg' => array(
            'adddev_success' => "The developer profile with name ':name' (id : :id) has successfully been created. It must be approved by an administrator before it becomes visible by everyone. You will get an email when that happens.",
            'editdev_success' => "The developer profile with name ':name' (id : :id) has successfully been updated.",

            'select_editdev_id_not_found' => "No developer with id ':id' was found.",
            'select_editdev_name_not_found' => "No developer with name ':name' was found.",

            'profile_not_found' => "No developer profile with id ':id' was found.",

            'editdev_nametaken' => "Can't rename the developer profile with name ':name' (id : :id) because the name ':newname' is already taken.",
        ),

    ),

// GAME 
    'game' => array(
        'developer_name_help' => 'OR if the developer profile does not yet exist on VGC, you can just',
        'dev_not_in_list_link' => 'enter its name and website URL below :',

        'cover' => 'Box cover or icon',
        'devstate' => 'Developement state',

        'pitch' => 'Game pitch, story, features...',

        'publishername' => 'Publisher name',
        'publisherurl' => 'Publisher\'s website',

        'soundtrackurl' => 'Soundtrack',
        
        'releasedate' => 'Release date',

        'developer_name_help' => 'If the developer has a profile on VGC, its name should appear below the field as you type it. 
        If that\'s the case, a link to the developer\'s profile will be done from the game\'s profile instead of using the URL below.',


        'add' => array(
            'title' => 'Add a game profile',
            'submit' => 'Submit this game profile',
        ),

        'edit' => array(
            'title' => 'Edit a game profile',
            'submit' => 'Edit this game profile',
            'select_profile_placeholder' => 'Name or id',
            'select_profile_help' => 'Select the game to edit',
        ),

        'profile' => array(
            'title' => 'Game',
            'website' => 'Go to the game\'s website',
            'blogfeed' => 'Lastest articles from the blog',
            'soundtrack' => 'Get the soundtrack',
            'screenshots_help' => 'Click on them to view in full size'
        ),


        'msg' => array(
            'addgame_success' => "The game profile with name ':name' (id : :id) has successfully been created. It must be approved by an administrator before it becomes visible by everyone. You will get an email when that happens.",
            'editgame_success' => "The game profile with name ':name' (id : :id) has successfully been updated.",

            'select_editgame_id_not_found' => "No game profile with id ':id' was found.",
            'select_editgame_name_not_found' => "No game profile with name ':name' was found.",

            'profile_not_found' => "No game profile with id ':id' was found.",

            'editgame_nametaken' => "Can't rename the game profile with name ':name' (id : :id) because the name ':newname' is already taken.",
        ),

    ), // end of game

// ADMIN

    'admin' => array(
        


        'home' => array(
            'title' => 'Admin home',
            'hello' => 'Hello',
        ),


        'menu' => array(
            'add_developer' => 'Add a developer',
            'edit_developer' => 'Edit a developer',
            'add_game' => 'Add a game',
            'edit_game' => 'Edit a game',
            'edit_user_account' => 'Edit your user account',
        ),
    ),

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

        'no_report' => 'No report to display',

        'rss_feed' => 'RSS feed for your reports.',

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

// REVIEWS

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

 

        'non_activated_text' => 'To activate the cross-promotion service, just check the box below and click on the Update button.',
        'activated_text' => 'To deactivate the cross-promotion service, just uncheck the box below and click on the Update button.',

        'subsciption_checkbox_label' => 'Activate the cross promotion service for my games.',

        'no_game' => 'Using the cross promotion service starts by <a href=":add_game_link" title="Go the the \'Add game profile\' form">creating a game profile</a>.', 


        'what_is_it_title' => 'What\'s cross-promotion ?',
        'what_is_it' => 
        '<a href="http://en.wikipedia.org/wiki/Cross-promotion" title="Go the Cross-promotion Wikipedia page">Cross-promotion</a> is a kind of advertising where a product is promoted from within another product. <br>
        Its two main benefits are that it\'s free since you don\'t have to rent advertising space inside you own game and it\'s <em>a priori</em> well targetted since we can imagine that players of one of your game might be interrested in your other games as well.',

        /*
        <br>
        Classic online advertising cost so much that most small to medium sized game studios just can\'t afford it. <br>
        Cross-promotion is a cheaper alternative that can prove effective if you promote not only your other games but also other developer\'s games. <br>
        Creating a network of cross-promotion

        if you promote a game made by another developer, tell them, they want to do the same with your game ! 
         */

        'how_it_works_title' => 'How does it works ?',
        'how_it_works' => 
        'You can select profiles to promote for each of your games under the tab "Cross-promotion" in your game profile edit page. <br>
        Then from within your game, you just have to query an URL to get the list of the profiles you selected in the first step. <br>
        Once you properly setted up the system in your game you can just update the promoted games from your game profile on VGC without the need to update/patch your actual game.',

        


        'editgame' => array(
            'non_subscribers_msg' => 'To easily promote other profiles from within this game <a href=":link">you need to activate</a> the cross-promotion service first.',
            
            'select_text' => 'Select below the profiles you want to promote from this game :',

            'link_text' => 'To get the profiles you selected above from your game, you need to query the following url : <br>
            <a href=":url">:url</a> <br>
            <br>
            It returns a JSON object with two top-level keys ("developers" and "games") which value is an array of objects. Each of those objects represent a profile. <br>
            In case of errors, the object wil just contain one top-level key "errors" which contains an array of strings.
            ',
        ),



        'msg' => array(
            'activation_success' => 'You successfully activated the cross-promotion for your games.',
            'deactivation_success' => 'You successfully deactivated the cross-promotion for your games.',
            'update_profiles_success' => "The promoted profiles for your game ':game_name' have successfully been updated.",
        ),
    ),

// SEARCH

    'search' => array(
        'title' => 'Search profiles',

        'hide_show_link' => 'Show/hide the search form',
        'game_accordion_link' => 'Show more criteria for games',

        'looking_for' => 'I am looking for',
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

        'submit' => 'Search',

        'devices_help' => 'are playable on,',
        'operatingsystems_help' => 'and runs on,',
        'genres_help' => 'and genre is,',
        'themes_help' => 'and theme is,',
        'viewpoints_help' => 'and point of view is,',
        'nbplayers_help' => 'and player mode is,',
        'tags_help' => 'and are tagged,',
        'languages_help' => 'and languages is,',
        'technologies_help' => 'and are made with,',
        'stores_help' => 'and are sold on,',
        'looks_help' => 'and looks,',
        'periods_help' => 'and take place during,',
        
        


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

// BLACKLIST

    'blacklist' => array(
        'title' => 'Blacklist',

        'msg' => array(
            'delete_success' => ':num :type profiles have successfully been deleted from your blacklist.',
            'add_success' => ':num :type profiles have successfully been added to your blacklist.',
        ),
    ),


// LANGUAGES

    'languages' => array(
        'title' => 'Languages',
        'help' => 'The languages the game is playable in.',

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
        'help' => 'The operating systems the game runs on (not applicable for most console games).',

        'android' => 'Android',
        'blackberry' => 'BlackBerry',
        'ios' => 'iOS',
        'linux' => 'Linux',
        'mac' => 'Mac OS',
        'windowsdesktop' => 'Windows desktop',
        'windowsdesktop_help' => 'Any Windows but Windows 8 Metro and Windows Phone',
        'windows8metro' => 'Windows 8 Metro',
        'windowsphone' => 'Windows Phone',
    ),

// DEVICES

    'devices' => array(
        'title' => 'Devices',
        'help' => 'The devices the game is playable on.',

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
        'help' => 'The technologies the game is made with.',

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
        'help' => 'The stores the game is purchasable from.',

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
        'help' => 'How many players can play the game at the same time.',

        'singleplayer' => 'Single player',
        'coop' => 'Co-op',
        'coop_help' => 'Usually 1 to 4 humans vs the computer.',
        'multiplayer' => 'Multiplayer',
        'multiplayer_help' => 'Human(s) vs human(s), often limited to 64 (sometime a couple hundreds) players on the same server.',
        'mmo' => 'MMO',
        'mmo_help' => 'Massively Multiplayer Online',
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

// LOOKS

    'looks' => array(
        'title' => 'Look',
        'help' => 'How the game looks',

        'cartoon' => 'Cartoon',
        'pixelart' => 'Pixel Art',
        'realist' => 'Realist',
        'minimalist' => 'Minimalist',
        'celshading' => 'Cel Shading',
    ),

// PERIODS

    'periods' => array(
        'title' => 'Period',
        'help' => 'When is the action supposed to take palce. No need to check any boxes if the game is timeless.',

        'mythology' => 'Mythology',
        'fantasy' => 'Fantasy',
        'medieval' => 'Medieval',
        'cartoon' => 'Cartoon',
        'futuristic' => 'Futuristic',
        'futuristic_help' => 'Up until a hundred year or so from now. After that, Sci-fi is more appropriate.',
        'modern' => 'Modern',
        'scifi' => 'Sci-fi',
        'western' => 'Western',
        'twentycentury' => '20th century',
        'twentycentury_help' => 'Include the Wold War I and WWII',
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
        'shootemup' => 'Shoot\'Em Up',
        'towerdefense' => 'Tower Defense',
        'hackandslash' => 'Hack and slash',
    ),

// POINT OF VIEW

    'viewpoints' => array(
        'title' => 'Point of view',
        'help' => '',

        '2d' => '2D',
        'firstperson' => 'First Person',
        'isometric' => 'Isometric',
        'isometric_help' => 'Isometric = 2.5D = most management/strategy game, whether they are in 2D or actual 3D.',
        'thirdperson' => 'Third Person',
        'topdown' => 'Top-Down',
    ),

// TAGS

    'tags' => array(
        'title' => 'Tags',

        '3d' => '3D',
        'casual' => 'Casual',
        'fun' => 'Fun',
        'hardcore' => 'Hardcore',
        'leveleditor' => 'Level Editor',
        'physics' => 'Physics',
        'sidescrolling' => 'Side-Scrolling',
        'turnbased' => 'Turn-Based',
        'topdown' => 'Top-Down',
        'crowdfunded' => 'Crowdfunded',
        "onegameamonth" => 'One Game A Month',
        'ludumdare' => 'Ludum Dare',
        'globalgamejam' => 'Global Game Jam',
        'fuckthisjam' => 'Fuck this Jam',
        'horror' => 'Horror',
        'mafia' => 'Mafia',
        'steampunk' => 'Steampunk',
    ),
);
