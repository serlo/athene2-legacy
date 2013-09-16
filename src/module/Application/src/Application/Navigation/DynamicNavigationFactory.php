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
namespace Application\Navigation;

use Zend\Navigation\Service\AbstractNavigationFactory;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Mvc\Router\RouteMatch;
use Zend\Mvc\Router\RouteStackInterface as Router;
use Zend\Navigation\Exception; 
use Subject\Hydrator\Navigation;

class DynamicNavigationFactory extends AbstractNavigationFactory
{

    protected $serviceLocator;

    protected $provider;
    
    protected function getName()
    {
        return 'default';
    }
    
    protected function getPages(ServiceLocatorInterface $serviceLocator)
    {
        $this->serviceLocator = $serviceLocator;
        
        if (null === $this->pages) {
            $configuration = $serviceLocator->get('Config');
        
            if (!isset($configuration['navigation'])) {
                throw new Exception\InvalidArgumentException('Could not find navigation configuration key');
            }
            if (!isset($configuration['navigation'][$this->getName()])) {
                throw new Exception\InvalidArgumentException(sprintf(
                    'Failed to find a navigation container by the name "%s"',
                    $this->getName()
                ));
            }
        
            $pages       = $this->getPagesFromConfig($configuration['navigation'][$this->getName()]);

            $hydrator = $this->serviceLocator->get('Subject\Hydrator\Navigation');
            $pages = $hydrator->inject($pages);
            
            $this->pages = $this->preparePages($serviceLocator, $pages);
        }
        return $this->pages;
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
}
