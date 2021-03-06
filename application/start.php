<?php

/*
|--------------------------------------------------------------------------
| PHP Display Errors Configuration
|--------------------------------------------------------------------------
|
| Since Laravel intercepts and displays all errors with a detailed stack
| trace, we can turn off the display_errors ini directive. However, you
| may want to enable this option if you ever run into a dreaded white
| screen of death, as it can provide some clues.
|
*/

ini_set('display_errors', 'On');

/*
|--------------------------------------------------------------------------
| Laravel Configuration Loader
|--------------------------------------------------------------------------
|
| The Laravel configuration loader is responsible for returning an array
| of configuration options for a given bundle and file. By default, we
| use the files provided with Laravel; however, you are free to use
| your own storage mechanism for configuration arrays.
|
*/

Laravel\Event::listen(Laravel\Config::loader, function($bundle, $file)
{
    return Laravel\Config::file($bundle, $file);
});

/*
|--------------------------------------------------------------------------
| Register Class Aliases
|--------------------------------------------------------------------------
|
| Aliases allow you to use classes without always specifying their fully
| namespaced path. This is convenient for working with any library that
| makes a heavy use of namespace for class organization. Here we will
| simply register the configured class aliases.
|
*/

$aliases = Laravel\Config::get('application.aliases');

Laravel\Autoloader::$aliases = $aliases;

/*
|--------------------------------------------------------------------------
| Auto-Loader Mappings
|--------------------------------------------------------------------------
|
| Registering a mapping couldn't be easier. Just pass an array of class
| to path maps into the "map" function of Autoloader. Then, when you
| want to use that class, just use it. It's simple!
|
*/

Autoloader::map(array(
    'Base_Controller' => path('app').'controllers/base.php',
));

/*
|--------------------------------------------------------------------------
| Auto-Loader Directories
|--------------------------------------------------------------------------
|
| The Laravel auto-loader can search directories for files using the PSR-0
| naming convention. This convention basically organizes classes by using
| the class namespace to indicate the directory structure.
|
*/

Autoloader::directories(array(
    path('app').'models',
    path('app').'libraries',
));

/*
|--------------------------------------------------------------------------
| Laravel View Loader
|--------------------------------------------------------------------------
|
| The Laravel view loader is responsible for returning the full file path
| for the given bundle and view. Of course, a default implementation is
| provided to load views according to typical Laravel conventions but
| you may change this to customize how your views are organized.
|
*/

Event::listen(View::loader, function($bundle, $view)
{
    return View::file($bundle, $view, Bundle::path($bundle).'views');
});

/*
|--------------------------------------------------------------------------
| Laravel Language Loader
|--------------------------------------------------------------------------
|
| The Laravel language loader is responsible for returning the array of
| language lines for a given bundle, language, and "file". A default
| implementation has been provided which uses the default language
| directories included with Laravel.
|
*/

Event::listen(Lang::loader, function($bundle, $language, $file)
{
    return Lang::file($bundle, $language, $file);
});

/*
|--------------------------------------------------------------------------
| Attach The Laravel Profiler
|--------------------------------------------------------------------------
|
| If the profiler is enabled, we will attach it to the Laravel events
| for both queries and logs. This allows the profiler to intercept
| any of the queries or logs performed by the application.
|
*/

if (Config::get('application.profiler'))
{
    Profiler::attach();
}

/*
|--------------------------------------------------------------------------
| Enable The Blade View Engine
|--------------------------------------------------------------------------
|
| The Blade view engine provides a clean, beautiful templating language
| for your application, including syntax for echoing data and all of
| the typical PHP control structures. We'll simply enable it here.
|
*/

Blade::sharpen();

/*
|--------------------------------------------------------------------------
| Set The Default Timezone
|--------------------------------------------------------------------------
|
| We need to set the default timezone for the application. This controls
| the timezone that will be used by any of the date methods and classes
| utilized by Laravel or your application. The timezone may be set in
| your application configuration file.
|
*/

date_default_timezone_set(Config::get('application.timezone'));

/*
|--------------------------------------------------------------------------
| Start / Load The User Session
|--------------------------------------------------------------------------
|
| Sessions allow the web, which is stateless, to simulate state. In other
| words, sessions allow you to store information about the current user
| and state of your application. Here we'll just fire up the session
| if a session driver has been configured.
|
*/

if ( ! Request::cli() and Config::get('session.driver') !== '')
{
    Session::load();
}


/* below this point the file has been edited for VideoGamesChest.com */

include_once('helpers.php');
include_once('macros.php');


Asset::container('nivo-slider')->add('main-css', 'css/nivo-slider/nivo-slider.css');
Asset::container('nivo-slider')->add('default-theme', 'css/nivo-slider/themes/default/default.css');
Asset::container('nivo-slider')->add('js-pack', 'js/nivo-slider/jquery.nivo.slider.pack.js');
Asset::container('nivo-slider')->add('bar-theme', 'css/nivo-slider/themes/bar/bar.css');
Asset::container('nivo-slider')->add('dark-theme', 'css/nivo-slider/themes/dark/dark.css');
Asset::container('nivo-slider')->add('light-theme', 'css/nivo-slider/themes/light/light.css');

Asset::container('colorbox')->add('colorbox-css', 'css/colorbox/colorbox.css');
Asset::container('colorbox')->add('colorbox-js', 'js/colorbox/jquery.colorbox-min.js');

Asset::container('slidesjs')->add('slidesjs-css', 'css/slidesjs/global.css');
Asset::container('slidesjs')->add('slidesjs-js', 'js/slidesjs/slides.min.jquery.js');

Asset::container('coda-slider')->add('css', 'css/coda-slider.css');
Asset::container('coda-slider')->add('jquery-ui', 'js/jquery-ui-1.10.0.custom.min.js');
Asset::container('coda-slider')->add('js', 'js/jquery.coda-slider-3.0.min.js');


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
