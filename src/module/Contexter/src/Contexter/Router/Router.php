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

use Zend\Mvc\Router\RouteMatch;
use Contexter\Entity;
use Contexter\Adapter\AdapterInterface;
use Contexter\Exception;
use Zend\Stdlib\ArrayUtils;

class Router implements RouterInterface
{
    
    use\Zend\ServiceManager\ServiceLocatorAwareTrait,\Common\Traits\ConfigAwareTrait,\Contexter\ContexterAwareTrait,\Common\Traits\ObjectManagerAwareTrait,\ClassResolver\ClassResolverAwareTrait;

    /**
     *
     * @var RouteMatch
     */
    protected $routeMatch;
    
    /**
     * 
     * @var array
     */
    protected $parameters;
    
    /**
     * 
     * @var AdapterInterface[]
     */
    protected $adapters;
    
    /*
     * (non-PHPdoc) @see \Contexter\Router\RouterInterface::match()
     */
    public function match($type = NULL)
    {
        $className = $this->getClassResolver()->resolveClassName('Contexter\Entity\RouteInterface');
        
        $criteria = array(
            'name' => $this->getRouteMatch()->getMatchedRouteName()
        );
        
        if ($type) {
            $type = $this->getContexter()->findTypeByName($type);
            $criteria['type'] = $type->getId();
        }
        
        $matches = $this->getObjectManager()->getRepository($className)->findBy($criteria);
        $matches = $this->matchesParameters($matches);
        return $matches;
    }

    /**
     *
     * @return RouteMatch $routeMatch
     */
    public function getRouteMatch()
    {
        return $this->routeMatch;
    }

    /**
     *
     * @param RouteMatch $routeMatch            
     * @return $this
     */
    public function setRouteMatch(RouteMatch $routeMatch)
    {
        $this->routeMatch = $routeMatch;
        return $this;
    }
    
    /**
     * 
     * @return AdapterInterface[]
     */
    protected function getAdapters(){
        $result = array();
        $adapters = $this->getOption('adapters');
        foreach($adapters as $adapter){
            foreach($adapter['routes'] as $route){
                if($route == $this->getRouteMatch()->getMatchedRouteName()){
                    $result[] = $this->getServiceLocator()->get($adapter['adapter']);
                }
            }
        }
        return $result;
    }
    
    protected function matchesParameters(array $matches){
        /* @var $route Entity\RouteInterface */
        foreach($matches as $route){
            /* @var $parameter Entity\RouteParameterInterface */
            foreach($route->getParameters() as $parameter){
                $this->matchesParameter($parameter);
            }
        }
    }
    
    protected function matchesParameter(Entity\RouteParameterInterface $parameter){
        $adapters = $this->getAdapters();
        $routeMatchParameters = $this->getRouteMatch()->getParams();
        $adapterParameters = array();
        foreach($adapters as $adapter){
            $adapterParameters = ArrayUtils::merge($adapterParameters, $adapter->getParams());
        }
        
        return;
    }
}