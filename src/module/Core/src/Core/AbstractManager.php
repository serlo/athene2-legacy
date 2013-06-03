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

use Zend\ServiceManager\ServiceManagerAwareInterface;
use Zend\ServiceManager\ServiceManager;

abstract class AbstractManager implements ServiceManagerAwareInterface
{

    /**
     * Array of all registered instances
     * 
     * @var array
     */
    private $instances = array();

    /**
     * 
     * @var array
     */
    private $options;

    /**
     *
     * @var \Zend\ServiceManager\ServiceLocatorInterface
     */
    private $serviceLocator;
    
    /**
     * (non-PHPdoc)
     * @see \Zend\ServiceManager\ServiceManagerAwareInterface::getServiceManager()
     */
    public function getServiceManager()
    {
        return $this->serviceLocator;
    }
    
    /**
     * (non-PHPdoc)
     * @see \Zend\ServiceManager\ServiceManagerAwareInterface::setServiceManager()
     */
    public function setServiceManager(ServiceManager $serviceLocator)
    {
        $this->serviceLocator = $serviceLocator;
        return $this;
    }

    /**
     * Constructor
     *
     * @param array $options            
     */
    public function __construct(array $options)
    {
        if(!is_array($this->options))
            $this->options = array();
        
        $this->options = array_merge($this->options, $options);
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
            throw new \Exception('Please pass only objects.');
        
        if ($this->hasInstance($name)) {
            if($this->instances[$name] !== $instance){
                $unsetInstance = $this->instances[$name];
                unset($unsetInstance);
                unset($this->instances[$name]);
            } else {
                return $this;
            }
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
     * @return string|Object
     */
    protected function resolve($interface, $createInstance = false)
    {
        if(!is_array($this->options))
            throw new \Exception('Please provide a configuration via `__construct($options)`!');
        
        if (! isset($this->options['instances'][$interface]))
            throw new \Exception("Class for interface `{$interface}` not set.");
        
        if($createInstance){
            $className = $this->options['instances'][$interface];
            return new $className();
        } else {
            return $this->options['instances'][$interface];
        }
    }

    /**
     * Creates an instance
     * 
     * @param string $instanceClassName
     * @throws \InvalidArgumentException
     * @return $instanceClassName
     */
    protected function createInstance($instanceClassName = 'manages')
    {
        $instanceClassName = $this->resolve($instanceClassName);
        $this->getServiceManager()->setShared($instanceClassName, false);
        $instance = $this->getServiceManager()->get($instanceClassName);
        if (! $instance instanceof $instanceClassName)
            throw new \InvalidArgumentException('Expeted ' . $instanceClassName . ' but got ' . get_class($instance));
        return $instance;
    }
    
    public function getInstances(){
        return $this->instances;
    }
}