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
        $controller_collection->get('/about', array($this, 'about'))->bind('main_about');
        $controller_collection->get('/navbar', array($this, 'navbar'))->bind('main_navbar');
        $controller_collection->get('/manual-{version}', array($this, 'documentation'))->bind('documentation');

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

    public function about()
    {
        return $this->app['twig']->render('about.html.twig');
    }

    public function documentation($version)
    {
        return $this->app['twig']->render('documentation.html.twig', array('pomm_version' => $version));
    }
}
