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
namespace Navigation\Service;

use Zend\Navigation\Service\AbstractNavigationFactory;
use Zend\ServiceManager\ServiceLocatorInterface;
use Navigation\Provider\DefaultProvider;
use Navigation\Provider\ProviderInterface;

use Zend\Mvc\Router\RouteMatch;
use Zend\Mvc\Router\RouteStackInterface as Router;
use Zend\Navigation\Exception; 

class DynamicNavigationFactory extends AbstractNavigationFactory
{

    protected $serviceLocator;

    protected $provider;

    protected function getPages(ServiceLocatorInterface $serviceLocator)
    {
        $this->serviceLocator = $serviceLocator;
        return parent::getPages($serviceLocator);
    }

    /**
     * Injects components
     *
     * @see \Zend\Navigation\Service\AbstractNavigationFactory::injectComponents()
     */
    protected function injectComponents(array $pages, RouteMatch $routeMatch = null, Router $router = null)
    {
        $merge = array();
        foreach ($pages as &$page) {
            $hasMvc = isset($page['action']) || isset($page['controller']) || isset($page['route']);
            if ($hasMvc) {
                if (! isset($page['routeMatch']) && $routeMatch) {
                   $page['routeMatch'] = $routeMatch;
                }
                if (! isset($page['router'])) {
                    $page['router'] = $router;
                }
            }
            
            if (isset($page['pages']) && is_array($page['pages'])) {                
                $page['pages'] = $this->injectComponents($page['pages'], $routeMatch, $router);
            }

            if (isset($page['provider'])) {
                $options = array();
                if (isset($page['options'])) {
                    $options = $page['options'];
                }
                
                $className = $page['provider'];
                $provider = new $className($options, $this->serviceLocator);
                
                if(isset($page['pages'])){
                    $page['pages'] = array_merge($page['pages'], $this->injectComponentsFromProvider($provider, $routeMatch, $router));
                } else {
                    $page['pages'] = $this->injectComponentsFromProvider($provider, $routeMatch, $router);
                }
                
                if(isset($page['options']))
                    unset($page['options']);
                if(isset($page['provider']))
                    unset($page['provider']);
            }
        }
        return $pages;
    }
    
    protected function injectComponentsFromProvider(ProviderInterface $provider,RouteMatch $routeMatch = null, Router $router = null){
        $array = $provider->provideArray();
        $return = $this->injectComponents($array, $routeMatch, $router);
        return $return;
    }

    /**
     *
     * @return string
     */
    protected function getName()
    {
        return 'default';
    }
}
