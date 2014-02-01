<?php // sources/application.php

use \Symfony\Component\HttpFoundation\Request;
use \Symfony\Component\HttpFoundation\Response;

$app = require "bootstrap.php";
$app->mount('/', new \Controller\MainController());
$app->mount('/news', new \Controller\BlogController());

$app->error(function(Exception $e, $code) use ($app) {

    if ($code == 404)
    {
        return $app['twig']->render('error404.html.twig');
    }

    if ($app['debug'])
    {
        // trigger log if enabled

        return;
    }

    return new Response($app['twig']->render('error.html.twig', array('message' => 'An error occured.')), $code);
});
return $app;
