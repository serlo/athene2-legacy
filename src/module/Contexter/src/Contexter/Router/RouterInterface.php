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
namespace Contexter\Router;

use Contexter\Adapter\AdapterInterface;
use Zend\Mvc\Router\RouteMatch;

interface RouterInterface
{

    /**
     *
     * @param string $url            
     * @param string $type            
     * @return RouteMatchInterface[]
     */
    public function match($url, $type);

    /**
     *
     * @return AdapterInterface
     */
    public function getAdapter();

    /**
     *
     * @param string $uri            
     * @return RouteMatch
     */
    public function matchUri($uri);

    /**
     *
     * @param RouteMatch $routeMatch            
     * @return self
     */
    public function setRouteMatch(RouteMatch $routeMatch);

    /**
     *
     * @return bool
     */
    public function hasAdapter();
}
