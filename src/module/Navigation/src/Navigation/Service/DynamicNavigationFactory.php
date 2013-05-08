<?php
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

    protected $_serviceLocator;

    protected $_provider;

    protected function getPages(ServiceLocatorInterface $serviceLocator)
    {
        $this->_serviceLocator = $serviceLocator;
        return parent::getPages($serviceLocator);
    }

    /**
     * Injects components
     *
     * @see \Zend\Navigation\Service\AbstractNavigationFactory::injectComponents()
     */
    protected function injectComponents(array $pages, RouteMatch $routeMatch = null, Router $router = null)
    {
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
            
            if (isset($page['pages'])) {
                $page['pages'] = $this->injectComponents($page['pages'], $routeMatch, $router);
            }

            if (isset($page['provider'])) {
                $options = array();
                if (isset($page['options'])) {
                    $options = $page['options'];
                }
                
                $className = $page['provider'];
                $provider = new $className($options, $this->_serviceLocator);
                
                if(isset($page['pages'])){
                    $page['pages'] = array_merge($page['pages'], $this->injectComponentsFromProvider($provider, $routeMatch, $router));
                } else {
                    $page['pages'] = $this->injectComponentsFromProvider($provider, $routeMatch, $router);
                }
                
                unset($page['options']);
                unset($page['provider']);
            }
        }
        return $pages;
    }
    
    protected function injectComponentsFromProvider(ProviderInterface $provider, $routeMatch, $router){
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
