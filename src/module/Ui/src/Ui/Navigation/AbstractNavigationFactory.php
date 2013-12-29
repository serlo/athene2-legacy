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
namespace Ui\Navigation;

use Zend\Navigation\Service\AbstractNavigationFactory as ZendAbstractNavigationFactory;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Mvc\Router\RouteMatch;
use Zend\Mvc\ApplicationInterface;

abstract class AbstractNavigationFactory extends ZendAbstractNavigationFactory
{    
    /**
     * 
     * @var RouteMatch
     */
    protected $routeMatch;
    
    /**
     * @return RouteMatch $routeMatch
     */
    public function getRouteMatch (ApplicationInterface $application = NULL)
    {
        if(!is_object($this->routeMatch)){
            $this->setRouteMatch($application->getMvcEvent()->getRouteMatch());
        }
        return $this->routeMatch;
    }

	/**
     * @param RouteMatch $routeMatch
     * @return self
     */
    public function setRouteMatch (RouteMatch $routeMatch)
    {
        $this->routeMatch = $routeMatch;
        return $this;
    }
    /**
     * @param ServiceLocatorInterface $serviceLocator
     * @param array|\Zend\Config\Config $pages
     * @throws \Zend\Navigation\Exception\InvalidArgumentException
     */
    protected function preparePages(ServiceLocatorInterface $serviceLocator, $pages)
    {
        $application = $serviceLocator->get('Application');
        $routeMatch  = $this->getRouteMatch($application);
        $router      = $application->getMvcEvent()->getRouter();
        
        return $this->injectComponents($pages, $routeMatch, $router);
    }
}