<?php defined('SYSPATH') or die('No direct script access.');

// -- Environment setup --------------------------------------------------------

// Load the core Kohana class
require SYSPATH.'classes/Kohana/Core'.EXT;

if (is_file(APPPATH.'classes/Kohana'.EXT))
{
	// Application extends the core
	require APPPATH.'classes/Kohana'.EXT;
}
else
{
	// Load empty core extension
	require SYSPATH.'classes/Kohana'.EXT;
}

/**
 * Set the default time zone.
 *
 * @link http://kohanaframework.org/guide/using.configuration
 * @link http://www.php.net/manual/timezones
 */
date_default_timezone_set('Asia/Shanghai');

/**
 * Set the default locale.
 *
 * @link http://kohanaframework.org/guide/using.configuration
 * @link http://www.php.net/manual/function.setlocale
 */
setlocale(LC_ALL, 'en_US.utf-8');

/**
 * Enable the Kohana auto-loader.
 *
 * @link http://kohanaframework.org/guide/using.autoloading
 * @link http://www.php.net/manual/function.spl-autoload-register
 */
spl_autoload_register(array('Kohana', 'auto_load'));

/**
 * Optionally, you can enable a compatibility auto-loader for use with
 * older modules that have not been updated for PSR-0.
 *
 * It is recommended to not enable this unless absolutely necessary.
 */
//spl_autoload_register(array('Kohana', 'auto_load_lowercase'));

/**
 * Enable the Kohana auto-loader for unserialization.
 *
 * @link http://www.php.net/manual/function.spl-autoload-call
 * @link http://www.php.net/manual/var.configuration#unserialize-callback-func
 */
ini_set('unserialize_callback_func', 'spl_autoload_call');

/**
 * Set the mb_substitute_character to "none"
 *
 * @link http://www.php.net/manual/function.mb-substitute-character.php
 */
mb_substitute_character('none');

// -- Configuration and initialization -----------------------------------------

/**
 * Set the default language
 */
I18n::lang('en-us');

if (isset($_SERVER['SERVER_PROTOCOL']))
{
	// Replace the default protocol.
	HTTP::$protocol = $_SERVER['SERVER_PROTOCOL'];
}

/**
 * Set Kohana::$environment if a 'KOHANA_ENV' environment variable has been supplied.
 *
 * Note: If you supply an invalid environment name, a PHP warning will be thrown
 * saying "Couldn't find constant Kohana::<INVALID_ENV_NAME>"
 */
if (isset($_SERVER['KOHANA_ENV']))
{
	Kohana::$environment = constant('Kohana::'.strtoupper($_SERVER['KOHANA_ENV']));
}

/**
 * Initialize Kohana, setting the default options.
 *
 * The following options are available:
 *
 * - string   base_url    path, and optionally domain, of your application   NULL
 * - string   index_file  name of your index file, usually "index.php"       index.php
 * - string   charset     internal character set used for input and output   utf-8
 * - string   cache_dir   set the internal cache directory                   APPPATH/cache
 * - integer  cache_life  lifetime, in seconds, of items cached              60
 * - boolean  errors      enable or disable error handling                   TRUE
 * - boolean  profile     enable or disable internal profiling               TRUE
 * - boolean  caching     enable or disable internal caching                 FALSE
 * - boolean  expose      set the X-Powered-By header                        FALSE
 */
Kohana::init(array(
	'base_url'   => '/kohana/',
));

/**
 * Attach the file write to logging. Multiple writers are supported.
 */
Kohana::$log->attach(new Log_File(APPPATH.'logs'));

/**
 * Attach a file reader to config. Multiple readers are supported.
 */
// Kohana::$config->attach(new Config_File);
if(defined('APP_ENV') && APP_ENV == Kohana::PRODUCTION){
    Kohana::$config->attach(new Config_File('config.production'));
}else{
    Kohana::$config->attach(new Config_File('config.development'));
}
/**
 * Enable modules. Modules are referenced by a relative or absolute path.
 */
Kohana::modules(array(
	'auth'       => MODPATH.'auth',       // Basic authentication
	'cache'      => MODPATH.'cache',      // Caching with multiple backends
	'codebench'  => MODPATH.'codebench',  // Benchmarking tool
	'database'   => MODPATH.'database',   // Database access
	'redis'      => MODPATH.'redis',   // redis access
	'image'      => MODPATH.'image',      // Image manipulation
	'minion'     => MODPATH.'minion',     // CLI Tasks
	'orm'        => MODPATH.'orm',        // Object Relationship Mapping
	'unittest'   => MODPATH.'unittest',   // Unit testing
	'userguide'  => MODPATH.'userguide',  // User guide and API documentation
    'mongodb' 	 => MODPATH.'mongodb',
	));

/**
 * Cookie Salt
 * @see  http://kohanaframework.org/3.3/guide/kohana/cookies
 *
 * If you have not defined a cookie salt in your Cookie class then
 * uncomment the line below and define a preferrably long salt.
 */
// Cookie::$salt = NULL;
Cookie::$salt = 'hdW3Z5ato9ssjyz';


/**
 * v1 版本1.0
 */
Route::set('v1', 'v1(/<controller>(/<action>))')->defaults(array(
    'directory'  => 'Ver1',
    'controller' => 'Index',
    'action'     => 'Index',
));

Route::set('Notify', 'Notify(/<controller>(/<action>))')->defaults(array(
    'directory'  => 'Notify',
    'controller' => 'Index',
    'action'     => 'Index',
));

Route::set('APIPay_Payment', 'APIPay_Payment_UNSPay(/<controller>(/<action>))')->defaults(array(
    'directory'  => 'APIPay_Payment_UNSPay',
    'controller' => 'Notify',
    'action'     => 'Index',
));

Route::set('APIPay_Deduct', 'APIPay_Deduct_UNSPay(/<controller>(/<action>))')->defaults(array(
    'directory'  => 'APIPay_Deduct_UNSPay',
    'controller' => 'Notify',
    'action'     => 'Index',
));

$_directoryView = "Ver1/H5/APP";

Route::set('v1h5app', 'v1/v(/<controller>(/<action>))')->defaults(array(

    'directory'  => $_directoryView,
    'controller' => 'Index',
    'action'     => 'Index',
));


Route::set('API', 'API(/<controller>(/<action>))')->defaults(array(
    'directory'  => 'API',
    'controller' => 'Index',
    'action'     => 'Index',
));


Route::set('v1h5wx', 'v1/w(/<controller>(/<action>))')->defaults(array(
    'directory'  => 'Ver1/H5/WX',
    'controller' => 'Index',
    'action'     => 'Index',
));





Route::set('v2', 'v2(/<controller>(/<action>))')->defaults(array(
    'directory'  => 'Ver2',
    'controller' => 'Index',
    'action'     => 'Index',
));





/**
 * Set the routes. Each route must have a minimum of a name, a URI and a set of
 * defaults for the URI.
 */
Route::set('default', '(<controller>(/<action>(/<id>)))')
    ->defaults(array(
        'controller' => 'Index',
        'action'     => 'Index',
    ));
