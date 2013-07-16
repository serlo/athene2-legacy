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
        $this->registry = $config;
    }

    protected function checkClass($class)
    {
        if (! is_string($class))
            throw new \InvalidArgumentException(sprintf('Argument is not a string.'));
        
        if (! array_key_exists($class, $this->registry))
            throw new \Exception(sprintf("Can't resolve %s.", $class));
        
        if(!class_exists($this->registry[$class]))
            throw new \Exception(sprintf("Class %s not found.", $this->registry[$class]));
    }
    
    public function resolveClassName($class){
        $this->checkClass($class);
        
    }
    
    /*
     * (non-PHPdoc) @see \ClassResolver\ClassResolverInterface::resolve()
     */
    public function resolve($class)
    {
        $this->checkClass($class);
        
        $instance = $this->getServiceLocator()->get($this->registry[$class]);
        
        if($instance instanceof $class)
            throw new \Exception(sprintf('Class %s does not implement %s', get_class($instance), $class));
        
        return $instance;
    }
}