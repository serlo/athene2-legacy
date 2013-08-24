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
namespace Taxonomy\Manager;

abstract class AbstractManager
{
    use\Zend\ServiceManager\ServiceLocatorAwareTrait,\ClassResolver\ClassResolverAwareTrait;

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
    protected $config;

    public function get($name)
    {
        return $this->getInstance($name);
    }

    /**
     * Constructor
     *
     * @param array $options            
     */
    public function __construct(array $options)
    {
        if (! is_array($this->config))
            $this->config = array();
        
        $this->config = array_merge($this->config, $options);
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
            if ($this->instances[$name] !== $instance) {
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
     * @param string $class            
     * @return string
     */
    protected function resolveClassName($class)
    {
        return $this->getClassResolver()->resolveClassName($class);
    }

    /**
     * Creates an instance
     *
     * @param string $instanceClassName            
     * @throws \InvalidArgumentException
     * @return $instanceClassName
     */
    protected function createInstance($instanceClassName)
    {
        $instanceClassName = $this->resolveClassName($instanceClassName);
        $this->getServiceLocator()->setShared($instanceClassName, false);
        $instance = $this->getServiceLocator()->get($instanceClassName);
        if (! $instance instanceof $instanceClassName)
            throw new \InvalidArgumentException('Expeted ' . $instanceClassName . ' but got ' . get_class($instance));
        return $instance;
    }

    protected function getInstances()
    {
        return $this->instances;
    }
}