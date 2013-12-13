<?php
/**
 * 
 * Athene2 - Advanced Learning Resources Manager
 *
 * @author	Aeneas Rekkas (aeneas.rekkas@serlo.org)
 * @license	LGPL-3.0
 * @license	http://opensource.org/licenses/LGPL-3.0 The GNU Lesser General Public License, version 3.0
 * @link		https://github.com/serlo-org/athene2 for the canonical source repository
 * @copyright Copyright (c) 2013 Gesellschaft für freie Bildung e.V. (http://www.open-education.eu/)
 */
namespace Contexter\Adapter;

use Zend\Mvc\Router\RouteMatch;
use Zend\Mvc\Controller\AbstractActionController;

abstract class AbstractAdapter implements AdapterInterface
{
    use \Contexter\Router\RouterAwareTrait;

    /**
     *
     * @var RouteMatch
     */
    protected $routeMatch;

    /**
     *
     * @var AnbstractActionController
     */
    protected $controller;

    public function getRouteMatch()
    {
        return $this->routeMatch;
    }

    public function getController()
    {
        return $this->controller;
    }

    public function setController(AbstractActionController $controller)
    {
        $this->controller = $controller;
        return $this;
    }

    public function getKeys()
    {
        return array_keys($this->getParameters());
    }

    public function setRouteMatch(RouteMatch $routeMatch)
    {
        $this->routeMatch = $routeMatch;
        return $this;
    }

    /**
     *
     * @return array
     */
    protected function getParametersFromRouteMatch()
    {
        return $this->getRouteMatch()->getParams();
    }
}