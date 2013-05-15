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
namespace Core;

use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\Validator\IsInstanceOf;

abstract class AbstractManager implements ServiceLocatorAwareInterface
{

    /**
     * Array of all registered instances
     * 
     * @var array
     */
    protected $instances = array();

    /**
     * 
     * @var array
     */
    protected $options = array();


    /**
     *
     * @var array
     */
    protected abstract $defaultOptions;

    /**
     *
     * @var \Zend\ServiceManager\ServiceLocatorInterface
     */
    protected $serviceLocator;
    
    /*
     * (non-PHPdoc) @see \Zend\ServiceManager\ServiceLocatorAwareInterface::getServiceLocator()
     */
    public function getServiceLocator()
    {
        return $this->serviceLocator;
    }
    
    /*
     * (non-PHPdoc) @see \Zend\ServiceManager\ServiceLocatorAwareInterface::setServiceLocator()
     */
    public function setServiceLocator(\Zend\ServiceManager\ServiceLocatorInterface $serviceLocator)
    {
        $this->serviceLocator = $serviceLocator;
        return $this;
    }

    /**
     * Constructor
     *
     * @param string $options            
     */
    public function __construct($options = NULL)
    {
        if (is_array($options)) {
            $this->options = array_merge($defaultOptions, $options);
        } else {
            $this->options = $this->defaultOptions;
        }
    }

    /**
     * Adds an instance.
     *
     * @param string $name            
     * @param object $instance            
     * @throws \Exception
     * @return $this
     */
    protected function addInstance($name, $instance)
    {
        if (! is_object($instance))
            throw new \Exception('Please pass only instances.');
        
        if ($this->hasInstance($name)) {
            $instance = $this->instances[$name];
            unset($instance);
            unset($this->instances[$name]);
        }
        
        $this->instances[$name] = $instance;
        return $this;
    }

    /**
     * Checks if an instance is already registered.
     *
     * @param string $name            
     * @return boolean
     */
    protected function hasInstance($name)
    {
        return array_key_exists($name, $this->instances);
    }

    /**
     * Returns an instance.
     *
     * @param string $name            
     * @throws \Exception
     * @return multitype:
     */
    protected function getInstance($name)
    {
        if (! $this->hasInstance($name))
            throw new \Exception('Instance `' . $name . '` not set.');
        
        return $this->instances[$name];
    }

    /**
     * Resolves an Interface to a Class and returns the Class name.
     *
     * @param string $interface            
     * @throws \Exception
     * @return string
     */
    protected function resolve($interface)
    {
        if (! isset($this->options['instance'][$interface]))
            throw new \Exception("Class for interface `{$interface}` not set.");
        
        return $this->options['instance'][$interface];
    }

    /**
     * Creates an instance
     * 
     * @param string $instanceClassName
     * @throws \InvalidArgumentException
     * @return $instanceClassName
     */
    protected function createInstance($instanceClassName = 'managing')
    {
        $instanceClassName = $this->resolve($managing);
        $this->getServiceLocator()->setShared($instanceClassName, false);
        $instance = $this->getServiceLocator()->get($instanceClassName);
        if (! $instance instanceof $instanceClassName)
            throw new \InvalidArgumentException('Expeted ' . $instanceClassName . ' but got ' . get_class($instance));
        return $instance;
    }
}