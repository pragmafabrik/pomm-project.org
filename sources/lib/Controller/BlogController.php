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
        $controller_collection->get('/{page}', array($this, 'index'))->bind('blog_index')->value('page', 1)->assert('page', '\d+');
        $controller_collection->get('/{slug}.html', array($this, 'show'))->bind('blog_show');
        $controller_collection->get('/search', array($this, 'search'))->bind('blog_search');

        return $controller_collection;
    }

    /**
     * index
     *
     * Shows a pager of news.
     *
     * @access public
     * @param  int $page
     * @return string
     */
    public function index($page)
    {
        $news_pager = $this->app['pomm']
            ->getDefaultSession()
            ->getModel('Model\PommProject\PommSchema\NewsModel')
            ->paginateFindAllShorten(5, $page);

        return $this
            ->app['twig']
            ->render('blog_list.html.twig', array('news_pager' => $news_pager))
            ;
    }

    /**
     * show
     *
     * Show a single news with full informations.
     *
     * @access public
     * @param  string $slug
     * @return string
     */
    public function show($slug)
    {
        if (!$news = ($this
            ->app['pomm']
            ->getDefaultSession()
            ->getModel('Model\PommProject\PommSchema\NewsModel')
            ->findBySlugWithNeighbours($slug)
        )) {
            $this->app->abort(404, "News not found");
        }

        return $this
            ->app['twig']
            ->render('blog_show.html.twig', array('news' => $news))
            ;
    }

    /**
     * search
     *
     * Search for words in news.
     *
     * @access public
     * @return string
     */
    public function search()
    {
        $search_string = $this->app['request']->query->get('search_string');
        $results = $this->app['pomm']
            ->getDefaultSession()
            ->getModel('Model\PommProject\PommSchema\NewsModel')
            ->search($search_string, 15)
            ;

        return $this
            ->app['twig']
            ->render('blog_search_results.html.twig', ['news' => $results, 'search_string' => $search_string])
            ;
    }
}
