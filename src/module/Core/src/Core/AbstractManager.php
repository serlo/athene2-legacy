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
    private $options = array();

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
     * @param string $options            
     */
    public function __construct($options = NULL)
    {
        if (is_array($options)) {
            $this->options = array_merge($this->options, $options);
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
            throw new \Exception('Please pass only objects.');
        
        if ($this->hasInstance($name)) {
            if($this->instances[$name] !== $instance){
                $instance = $this->instances[$name];
                unset($instance);
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
     * @return string
     */
    protected function resolve($interface)
    {
        if (! isset($this->options['instances'][$interface]))
            throw new \Exception("Class for interface `{$interface}` not set.");
        
        return $this->options['instances'][$interface];
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