<?php

/*
|--------------------------------------------------------------------------
| Register The Laravel Class Loader
|--------------------------------------------------------------------------
|
| In addition to using Composer, you may use the Laravel class loader to
| load your controllers and models. This is useful for keeping all of
| your classes in the "global" namespace without Composer updating.
|
*/

ClassLoader::addDirectories(array(

	app_path().'/commands',
	app_path().'/controllers',
	app_path().'/models',
	app_path().'/database/seeds',

    app_path().'/libraries', // added for VGC
));

/*
|--------------------------------------------------------------------------
| Application Error Logger
|--------------------------------------------------------------------------
|
| Here we will configure the error logger setup for the application which
| is built on top of the wonderful Monolog library. By default we will
| build a rotating log file setup which creates a new file each day.
|
*/

$logFile = 'log-'.php_sapi_name().'.txt';

Log::useDailyFiles(storage_path().'/logs/'.$logFile);

/*
|--------------------------------------------------------------------------
| Application Error Handler
|--------------------------------------------------------------------------
|
| Here you may handle any errors that occur in your application, including
| logging them or displaying custom views for specific errors. You may
| even register several error handlers to handle different types of
| exceptions. If nothing is returned, the default error view is
| shown, which includes a detailed stack trace during debug.
|
*/

App::error(function(Exception $exception, $code)
{
	Log::error($exception);
});

/*
|--------------------------------------------------------------------------
| Maintenance Mode Handler
|--------------------------------------------------------------------------
|
| The "down" Artisan command gives you the ability to put an application
| into maintenance mode. Here, you will define what is displayed back
| to the user if maintenace mode is in effect for this application.
|
*/

App::down(function()
{
	return Response::make("Be right back!", 503);
});

/*
|--------------------------------------------------------------------------
| Require The Filters File
|--------------------------------------------------------------------------
|
| Next we will load the filters file for the application. This gives us
| a nice separate location to store our route and application filter
| definitions instead of putting them all in the main routes file.
|
*/

require app_path().'/filters.php';


/* below this point the file has been edited for VideoGamesChest.com */


include_once('helpers.php');
include_once('macros.php');
// include_once('libraries/simple_html_dom.php');


// Asset::container('nivo-slider')->add('main-css', 'css/nivo-slider/nivo-slider.css');
// Asset::container('nivo-slider')->add('default-theme', 'css/nivo-slider/themes/default/default.css');
// Asset::container('nivo-slider')->add('js-pack', 'js/nivo-slider/jquery.nivo.slider.pack.js');
// Asset::container('nivo-slider')->add('bar-theme', 'css/nivo-slider/themes/bar/bar.css');
// Asset::container('nivo-slider')->add('dark-theme', 'css/nivo-slider/themes/dark/dark.css');
// Asset::container('nivo-slider')->add('light-theme', 'css/nivo-slider/themes/light/light.css');

// Asset::container('colorbox')->add('colorbox-css', 'css/colorbox/colorbox.css');
// Asset::container('colorbox')->add('colorbox-js', 'js/colorbox/jquery.colorbox-min.js');

// Asset::container('slidesjs')->add('slidesjs-css', 'css/slidesjs/global.css');
// Asset::container('slidesjs')->add('slidesjs-js', 'js/slidesjs/slides.min.jquery.js');

// Asset::container('coda-slider')->add('css', 'css/coda-slider.css');
// Asset::container('coda-slider')->add('jquery-ui', 'js/jquery-ui-1.10.0.custom.min.js');
// Asset::container('coda-slider')->add('js', 'js/jquery.coda-slider-3.0.min.js');


// new validation rule
/*Laravel\Validator::register('no_slashes', function($attribute, $value, $parameters)
{
    if (strpos($value, '/') === false && strpos($value, "\\") === false) return true;
    else return false;
});*/

Laravel\Validator::register('alpha_dash_extended', function($attribute, $value, $parameters)
{
    $alphabet = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789_- ";

    for ($i = 0; $i < strlen($value); $i++) {
        if (strpos($alphabet, $value[$i]) === false) { // current carac not found in aphabet
            return false;
        }
    }

    return true;
});


Laravel\Validator::register('honeypot', function($attribute, $value, $parameters)
{
    if (strlen($value) > 0) {
        Log::write('error honeypot bot', 'The honeypot was filled with value "'.$value.'". IP : '.Request::ip());
        return Response::error('500');
    } else return true;
});
