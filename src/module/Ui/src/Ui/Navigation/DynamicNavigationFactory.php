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

use Zend\Navigation\Service\AbstractNavigationFactory;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Mvc\Router\RouteMatch;
use Zend\Mvc\Router\RouteStackInterface as Router;
use Zend\Navigation\Exception;

class DynamicNavigationFactory extends AbstractNavigationFactory
{
    use \Common\Traits\ConfigAwareTrait,\Zend\ServiceManager\ServiceLocatorAwareTrait;

    protected function getDefaultConfig()
    {
        return array(
            'navigation' => array(
                'default' => array()
            ),
            'default_navigation' => array(
                'hydrators' => array()
            )
        );
    }

    protected function getName()
    {
        return 'default';
    }

    protected function getPages(ServiceLocatorInterface $serviceLocator)
    {
        $this->setServiceLocator($serviceLocator);
        $this->setConfig($serviceLocator->get('config'));
        
        if (null === $this->pages) {
            
            if (! $this->getOption('navigation')) {
                throw new Exception\InvalidArgumentException('Could not find navigation configuration key');
            }
            if (! array_key_exists($this->getName(), $this->getOption('navigation'))) {
                throw new Exception\InvalidArgumentException(sprintf('Failed to find a navigation container by the name "%s"', $this->getName()));
            }
            
            $pages = $this->getPagesFromConfig($this->getOption('navigation')[$this->getName()]);
            
            foreach ($this->getOption('default_navigation')['hydrators'] as $hydrator) {
                $hydrator = $this->getServiceLocator()->get($hydrator); // get('Subject\Hydrator\Navigation');
                $hydrator->hydrateConfig($pages);
            }
            
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
                $provider = $this->getServiceLocator()->get($className);
                
                $provider->setConfig($options);
                
                if (isset($page['pages'])) {
                    $page['pages'] = array_merge($page['pages'], $this->injectComponentsFromProvider($provider, $routeMatch, $router));
                } else {
                    $page['pages'] = $this->injectComponentsFromProvider($provider, $routeMatch, $router);
                }
                
                if (isset($page['options']))
                    unset($page['options']);
                if (isset($page['provider']))
                    unset($page['provider']);
            }
        }
        return $pages;
    }

    protected function injectComponentsFromProvider(ProviderInterface $provider, RouteMatch $routeMatch = null, Router $router = null)
    {
        $pages = $provider->providePagesConfig();
        $return = $this->injectComponents($pages, $routeMatch, $router);
        return $return;
    }
}
