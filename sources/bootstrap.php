<?php #sources/bootstrap.php

use Silex\Provider;
use PommProject\Foundation\Pomm;

// This script sets up the application DI with services.

if (!defined('PROJECT_DIR'))
{
    define('PROJECT_DIR', dirname(__DIR__));
}

require PROJECT_DIR.'/sources/config/environment.php';

// autoloader
$loader = require PROJECT_DIR.'/vendor/autoload.php';
$loader->add(false, PROJECT_DIR.'/sources/lib');

$app = new Silex\Application();

// configuration parameters
if (!file_exists(PROJECT_DIR.'/sources/config/config.php')) {
    throw new \RunTimeException("No config.php found in config.");
}

require PROJECT_DIR.'/sources/config/config.php';

// extensions registration

$app->register(new Provider\UrlGeneratorServiceProvider());
$app->register(new Provider\SessionServiceProvider());
$app->register(new Provider\TwigServiceProvider(), array(
    'twig.path' => array(PROJECT_DIR.'/sources/twig'),
));
$app['pomm'] = $app->share(function() use ($app) {
    return new Pomm($app['config.pomm.dsn'][ENV]);
});

// Service container customization.
$app['loader'] = $loader;
// set DEBUG mode or not
if (preg_match('/^dev/', ENV))
{
    $app['debug'] = true;
    $app->register(new Provider\MonologServiceProvider(), array(
        'monolog.logfile' => PROJECT_DIR.'/log/app.log'
        ));
    $app['pomm'] = $app->share($app->extend(
        'pomm',
        function($pomm, $app) { $pomm->setLogger($app['monolog']); return $pomm;}
        ));
}

return $app;
