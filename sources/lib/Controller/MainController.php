<?php
namespace Controller;

use Silex\Application;
use Silex\ControllerProviderInterface;

use \Symfony\Component\HttpFoundation\Request;
use \Symfony\Component\HttpFoundation\Response;

class MainController implements ControllerProviderInterface
{
    protected $app;

    public function connect(Application $app)
    {
        $this->app = $app;
        $controller_collection = $app['controllers_factory'];
        $controller_collection->get('/', array($this, 'index'))->bind('main_index');
        $controller_collection->get('/navbar', array($this, 'navbar'))->bind('main_navbar');

        return $controller_collection;
    }

    public function index()
    {
        return $this->app['twig']->render('index.html.twig');
    }

    public function navbar()
    {
        return $this->app['twig']->render('_navbar.html.twig');
    }
}

