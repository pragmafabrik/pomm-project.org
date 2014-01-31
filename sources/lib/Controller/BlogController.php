<?php
namespace Controller;

use Silex\Application;
use Silex\ControllerProviderInterface;

use \Symfony\Component\HttpFoundation\Request;
use \Symfony\Component\HttpFoundation\Response;

class BlogController implements ControllerProviderInterface
{
    protected $app;

    public function connect(Application $app)
    {
        $this->app = $app;

        $controller_collection = $app['controllers_factory'];
        $controller_collection->get('/{page}', array($this, 'index'))->bind('blog_index')->value('page', 1);
        $controller_collection->get('/{slug}.html', array($this, 'show'))->bind('blog_show');

        return $controller_collection;
    }

    public function index($page)
    {
        $news_pager = $this->app['pomm.connection']
            ->getMapFor('Model\PommProject\Pomm\News')
            ->paginateFindAllShorten(15, $page);

        return $this->app['twig']->render('blog_list.html.twig', array('news_pager' => $news_pager));
    }
}
