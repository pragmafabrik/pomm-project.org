<?php
namespace Controller;

use Silex\Application;
use Silex\ControllerProviderInterface;

use \Symfony\Component\HttpFoundation\Request;
use \Symfony\Component\HttpFoundation\Response;

class MainController implements ControllerProviderInterface
{
    protected $app;
    protected $releases;

    public function connect(Application $app)
    {
        $this->app = $app;
        $this->releases = array('1.2' => '1.2.2', '1.3' => '1.3.0-RC1', '2.0' => '2.0-beta.1');

        $controller_collection = $app['controllers_factory'];
        $controller_collection->get('/', array($this, 'index'))->bind('main_index');
        $controller_collection->get('/about', array($this, 'about'))->bind('main_about');
        $controller_collection->get('/navbar', array($this, 'navbar'))->bind('main_navbar');
        $controller_collection->get('/download', array($this, 'download'))->bind('main_download');
        $controller_collection->get('/howto/install', array($this, 'install'))->bind('main_install');
        $controller_collection->get('/documentation/examples', array($this, 'examples'))->bind('main_examples');
        $controller_collection->get('/documentation/sandbox', array($this, 'sandbox'))->bind('main_sandbox');
        $controller_collection->get('/documentation/sandbox2', array($this, 'sandbox2'))->bind('main_sandbox2');
        $controller_collection->get('/documentation/manual-{version}', array($this, 'documentation'))->bind('documentation');

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

    public function download()
    {
        return $this->app['twig']->render('download.html.twig', array('latest' => $this->releases));
    }

    public function examples()
    {
        return $this->app['twig']->render('examples.html.twig');
    }

    public function install()
    {
        return $this->app['twig']->render('install.html.twig');
    }

    public function sandbox()
    {
        return $this->app['twig']->render('sandbox.html.twig');
    }

    public function sandbox2()
    {
        return $this->app['twig']->render('sandbox2.html.twig');
    }
}
