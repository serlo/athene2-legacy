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
namespace ClassResolver;

class ClassResolver implements ClassResolverInterface
{
    use \Zend\ServiceManager\ServiceLocatorAwareTrait;

    /**
     *
     * @var array
     */
    protected $registry;

    public function __construct($config = array())
    {
        foreach($config as $from => $to){
            $this->addClass($from, $to);
        }
    }
    
    protected function addClass($from, $to){
        $this->registry[$this->getIndex($from)] = $to;
        return $this;
    }
    
    protected function getIndex($key){
        return preg_replace('/[^a-z0-9]/i', '_', $key);
    }

    protected function getClass($class)
    {
        $index = $this->getIndex($class);
        
        if (! is_string($class))
            throw new \InvalidArgumentException(sprintf('Argument is not a string.'));
        
        if (! array_key_exists($index, $this->registry))
            throw new \Exception(sprintf("Can't resolve %s (%s).", $class, $index));
        
        if (! class_exists($this->registry[$index]))
            throw new \Exception(sprintf("Class %s not found.", $this->registry[$class]));
        
        return $this->registry[$index];
    }

    public function resolveClassName($class)
    {
        return $this->getClass($class);
    }
    
    /*
     * (non-PHPdoc) @see \ClassResolver\ClassResolverInterface::resolve()
     */
    public function resolve($class)
    {
        $instance = $this->getServiceLocator()->get($this->getClass($class));
        
        if ($instance instanceof $class)
            throw new \Exception(sprintf('Class %s does not implement %s', get_class($instance), $class));
        
        return $instance;
    }
}