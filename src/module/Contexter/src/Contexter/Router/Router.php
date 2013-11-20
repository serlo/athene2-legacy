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
use Contexter\Collection\ContextCollection;
use Doctrine\Common\Collections\ArrayCollection;
use Zend\Http\Request;
use Contexter\Entity\TypeInterface;

class Router implements RouterInterface
{
    use \Common\Traits\RouterAwareTrait,\Zend\ServiceManager\ServiceLocatorAwareTrait,\Common\Traits\ConfigAwareTrait,\Contexter\ContexterAwareTrait,\Common\Traits\ObjectManagerAwareTrait,\ClassResolver\ClassResolverAwareTrait;

    /**
     *
     * @var RouteMatch
     */
    protected $routeMatch;
    
    /**
     * 
     * @var RouteMatch
     */
    protected $factoryRouteMatch;

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
    public function match($uri = NULL, $type = NULL)
    {
        if ($uri !== NULL) {
            $routeMatch = $this->matchUri($uri);
            $this->setRouteMatch($routeMatch);
        } else {
            if (! is_object($this->getRouteMatch()))
                throw new \Contexter\Exception\RuntimeException(sprintf('No RouteMatch set!'));
        }
        
        $className = $this->getClassResolver()->resolveClassName('Contexter\Entity\RouteInterface');
        
        $criteria = array(
            'name' => $this->getRouteMatch()->getMatchedRouteName()
        );
        
        if ($type) {
            $type = $this->getContexter()->findTypeByName($type);
        }
        
        $routes = $this->getObjectManager()
            ->getRepository($className)
            ->findBy($criteria);
        
        $result = $this->matchRoutes($routes, $type);
        $this->clear();
        return $result;
    }

    public function matchUri($uri)
    {
        $request = new Request();
        $request->setUri($uri);
        $request->setMethod('post');
        return $this->getRouter()->match($request);
    }

    /**
     *
     * @return AdapterInterface
     */
    public function getAdapter()
    {
        $requestedController = $this->getRouteMatch()->getParam('controller');
        $adapters = $this->getOption('adapters');
        foreach ($adapters as $adapter) {
            if (in_array($requestedController, $adapter['controllers'])) {
                $controller = $requestedController;
                /* @var $adapter AdapterInterface */
                $adapter = $this->getServiceLocator()->get($adapter['adapter']);
                $adapter->setRouteMatch($this->getRouteMatch());
                $adapter->setController($this->getServiceLocator()
                    ->get($controller));
                return $adapter;
            }
        }
        throw new Exception\RuntimeException(sprintf('No suitable adapter found for controller `%s`', $requestedController));
    }
    
    public function hasAdapter(){
        try{
            $this->getAdapter();
            return true;
        } catch (Exception\RuntimeException $e){
            return false;
        }
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
        if($this->factoryRouteMatch === NULL){
            $this->factoryRouteMatch = $this->routeMatch;
        }
        $this->routeMatch = $routeMatch;
        return $this;
    }

    protected function getDefaultConfig()
    {
        return array(
            'adapters' => array()
        );
    }
    
    protected function clear(){
        $this->setRouteMatch($this->factoryRouteMatch);
        return $this;
    }

    /**
     *
     * @param array $routes            
     * @return \Doctrine\Common\Collections\ArrayCollection
     */
    protected function matchRoutes(array $routes, TypeInterface $type)
    {
        $result = new ArrayCollection();
        /* @var $route Entity\RouteInterface */
        foreach ($routes as $route) {
            if ($route->getContext()->getType() === $type && $this->matchesParameters($route)) {
                $context = $this->getContexter()->getContext($route->getContext()
                    ->getId());
                $result->add($context);
            }
        }
        return $result;
    }

    protected function matchesParameters(Entity\RouteInterface $route)
    {
        $passed = false;
        /* @var $parameter Entity\RouteParameterInterface */
        foreach ($route->getParameters() as $parameter) {
            $matching = $this->matchesParameter($parameter);
            if (! $matching) {
                $passed = false;
                break;
            } else {
                $passed = true;
            }
        }
        if ($passed === true) {
            return true;
        }
        return $passed;
    }

    protected function matchesParameter(Entity\RouteParameterInterface $parameter)
    {
        $parameters = $this->getAdapter()->getParameters();
        if (array_key_exists($parameter->getKey(), $parameters) && $parameters[$parameter->getKey()] === $parameter->getValue()) {
            return true;
        }
        return false;
    }
}