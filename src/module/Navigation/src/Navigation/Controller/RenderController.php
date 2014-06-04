<?php
/**
 * Athene2 - Advanced Learning Resources Manager
 *
 * @author    Aeneas Rekkas (aeneas.rekkas@serlo.org)
 * @license   LGPL-3.0
 * @license   http://opensource.org/licenses/LGPL-3.0 The GNU Lesser General Public License, version 3.0
 * @link      https://github.com/serlo-org/athene2 for the canonical source repository
 * @copyright Copyright (c) 2013 Gesellschaft für freie Bildung e.V. (http://www.open-education.eu/)
 */

namespace Navigation\Controller;


use Alias\AliasManagerInterface;
use Alias\Exception\AliasNotFoundException;
use Taxonomy\Controller\AbstractController;
use Zend\Http\Request;
use Zend\View\Model\ViewModel;
use Zend\Mvc\Router\RouteMatch;

class RenderController extends AbstractController
{
    /**
     * @var array
     */
    protected $config;

    /**
     * @var AliasManagerInterface
     */
    protected $aliasManager;

    /**
     * @param array $config
     */
    public function __construct(array $config, AliasManagerInterface $aliasManager)
    {
        $this->config = $config;
        $this->aliasManager = $aliasManager;
    }

    public function listAction()
    {
        $navigation = $this->params('navigation');
        $current    = $this->params('current');
        $depth      = $this->params('depth');
        $branch     = $this->params('branch');
        $terminate  = $this->getRequest()->isXmlHttpRequest();
        $routeMatch = $this->getRouteMatch($branch);

        // TODO: Wow, again, that's so haxxy..
        if($routeMatch && $routeMatch->getMatchedRouteName() == 'alias'){
            try {
                $branch = $routeMatch->getParam('alias');
                $branch = $this->aliasManager->findSourceByAlias($branch);
                $routeMatch = $this->getRouteMatch($branch);
            } catch (AliasNotFoundException $e){
                $this->getResponse()->setStatusCode(404);
                return false;
            }
        }

        if (!$this->isValidNavigationKey($navigation) || !$routeMatch) {
            $this->getResponse()->setStatusCode(404);
            return false;
        }

        $this->getEvent()->setRouteMatch($routeMatch);

        $view = new ViewModel([
            'navigation' => $navigation,
            'current'    => $current,
            'depth'      => $depth,
            'branch'     => $branch,
        ]);

        $view->setTemplate('navigation/render/list');
        $view->setTerminal(true);

        return $view;
    }

    /**
     * @param $uri
     * @return RouteMatch
     */
    protected function getRouteMatch($uri)
    {
        $router  = $this->getServiceLocator()->get('Router');
        $request = new Request();
        $request->setMethod(Request::METHOD_GET);
        $request->setUri($uri);
        $routeMatch = $router->match($request);
        return $routeMatch;

    }

    protected function isValidNavigationKey($key)
    {
        // TODO: Wow, that's haxxyy - this is due to the fucked up name conventions in the navigations.
        $key = str_replace('_navigation', '', $key);
        $key = str_replace('-', '_', $key);
        return array_key_exists($key, $this->config);
    }
}
 