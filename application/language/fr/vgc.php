<?php
/*
You may not have dots inside the keys


*/
return array(

// MESSAGES (errors, success)

    'messages' => array(
        'adddev_success' => 'The developer profile with name \":name\" has successfully been submitted.',
        'addgame_success' => 'The game profile with name \":name\" has successfully been submitted.',

        'editdev_nametaken' => 'Can\'t rename the developer profile with name ":name" (id : :id) because the name ":newname" is already taken.',
        'editgame_nametaken' => 'Can\'t rename the game profile with name \":name\" (id : :id) because the name \":newname" is already taken.',
        
        'editdev_success' => 'The developer profile with name \":name\" (id : :id) has successfully been updated.',
        'editgame_success' => 'The game profile with name \":name\" (id : :id) has successfully been updated.',

        'logged_in_only' => "You must be logged in to access this page !",
        'admin_only' => "You must be an administrator to access this page !",
        'admin_and_logged_in' => "You must be logged in and an administrator to access this page !",

        'can_not_edit_others_games' => "You are not allowed to edit other developer's games !",
        'game_profile_not_found' => "Can't find the game profile with id ':profile_id' !",
    ),  

// MENU

    'menu' => array(
        'home' => 'Accueil',
        'search' => 'Recherche',
        'about' => 'À propos',
        'adddeveloper' => 'Ajouter un developpeur',
        'addgame' => 'Ajouter un jeu',

        'logout' => 'Se déconnecter',
        'languages' => 'Langues',

        'login' => array(
            'name_label' => 'Votre nom d\'utilisateur, courriel ou id',
            'password_label' => 'Votre mot de passe',
            'title' => 'Se connecter',
            'submit' => 'Se connecter',
            'lost_password' => 'J\'ai perdu mon mot de passe',
            'lostpassword_help' => 'Si vous avez perdu votre mot de passe, remplissez le champs ci dessous avec votre nom d\'utilisateur, votre courriel ou votre id afin de recevoir un mot de passe temporaire.',
        ),
    ),


// HELP

'help_required_field' => 'Required field',



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

            'logo' => 'Logo URL',
            'website' => 'Website URL',
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

    // ADD GAME SPECIFIC TERMS
    
    

    // GAME PROFILE SPECIFIC TERMS

    



// LANGUAGES

    'languages' => array(
        'french' => 'Français',
        'english' => 'Anglais',
        'german' => 'Allemand',
        'spanish' => 'Espagnol',
        'italian' => 'Italien',
        'russian' => 'Russe',
        'japonese' => 'Japonais',
        'chinese' => 'Chinois',
    ),


// COUNTRIES

    'countries' => array(
        'australia' => 'Australie',
        'austria' => 'Autriche',
        'belgium' => 'Belgique',
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

// DEVICES

    'devices' => array(
        'androidtablet' => 'Tablette Android',
        'androidsmartphone' => 'Smartphone Android',
        'blackberrysmartphone' => 'Smartphone BlackBerry ',
        'browser' => 'Navigateur internet',
        'windowsphonetablet' => 'Tablette Windows Phone',
        'windowsphonesmartphone' => 'SmartphoneWindows Phone',
        'xperiasmartphone' => 'Smartphone Xperia',
    ),

// TECHOLOGIES

    'technologies' => array(
        'custom' => 'Custom-built/Fait maison',
    ),

// STORES

    'stores' => array(
        'website' => 'Site web du studio/du jeu',
        'windowsstore' => 'Boutique Windows 8',
    ),

// SOCIAL NETWORKS

// Nb PLAYERS

    'nbplayers' => array(
        'singleplayer' => 'Simple joueur',
        'multiplayer' => 'Multi joueurs',
    ),

// DEVELOPEMENT STATES

    'developmentstates' => array(
        'concept' => '1 - Concept/Design',
        'prototype' => '2 - Prototype',
        'earlyproduction' => '3 - Début de la production',
        'firstgameplay' => '4 - Premier gameplay',
        'publicplayablebuilt' => '5 - Version jouable publique',
        'released' => '6 - Sortit',
        'canceled' => 'Annulé',
    ),


// THEMES

    'themes' => array(
        'medieval' => 'Médiéval',
        'futuristic' => 'Futuriste',
        'horror' => 'Horreur',
        'modern' => 'Moderne',
        'scifi' => 'Science fiction',
    ),

// GENRES

    'genres' => array(
        'adventure' => 'Aventure',
        'fighting' => 'Combat',
        'platformer' => 'Plateforme',
        'racing' => 'Course',
        'shooter' => 'Jeu de tir',
        'strategy' => 'Stratégie',
        'citybuilding' => 'Construction de cité',
        'resources' => 'Gestion de ressources',
        'roleplaying' => 'Jeu de rôle',
        'rts' => 'Strategie en temps réel',
    ),

// POINT OF VIEW

    'viewpoints' => array(
        'firstperson' => 'À la première personne',
        'isometric' => 'Isométrique',
        'thirdperson' => 'À la troisième personne',
        'topdown' => 'Vue du dessus',
    ),

// TAGS

    'tags' => array(

        'leveleditor' => 'Editeur de niveau',
        'physics' => 'Physique',
        'sidescrolling' => 'Side-Scrolling',
        'turnbased' => 'Au tour par tour',
    ),
 
// REPORTS

    'report' => array(
        'title' => 'Rapporter ce profil',
        'description' => 'Description',
        'description_placeholder' => '10 caractères minimum',
        'recipient' => 'Destinataire du rapport',
        'developer' => 'Développeur et Admins',
        'admin' => 'Admins seulement',
        'submit' => 'Envoyer ce rapport',
        'gobacktoprofile' => 'Revenir au profil',
        'form_success' => 'Merci, le rapport a bien été envoyé.',
    ),
);
