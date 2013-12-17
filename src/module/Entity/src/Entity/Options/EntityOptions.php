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
namespace Entity\Options;

use Zend\Stdlib\AbstractOptions;
use Entity\Exception;

class EntityOptions extends AbstractOptions
{

    /**
     *
     * @var array
     */
    protected $availableComponents = [
        'Entity\Options\RepositoryOptions',
        'Entity\Options\LinkOptions'
    ];

    /**
     *
     * @var array
     */
    protected $components = [];

    /**
     *
     * @param string $component            
     * @return array $components
     */
    public function getComponent($component)
    {
        if (! $this->hasComponent($component)) {
            throw new Exception\RuntimeException(sprintf('Component "%s" not enabled.', $component));
        }

        $options = $this->components[$component];
        
        if(!$options instanceof AbstractOptions){
            $instance = $this->findComponent($component);
            $instance->setFromArray($options);
            $this->components[$component] = $options = $instance;
        }
        
        return $options;
    }

    /**
     *
     * @param string $component            
     * @return bool
     */
    public function hasComponent($component)
    {
        return array_key_exists($component, $this->components);
    }

    /**
     *
     * @param array $components            
     */
    public function setComponents($components)
    {
        $this->components = $components;
        return $this;
    }

    /**
     * 
     * @param string $key
     * @throws Exception\RuntimeException
     * @return AbstractOptions
     */
    protected function findComponent($key)
    {
        foreach ($this->availableComponents as & $availableComponent) {
            if (! is_object($availableComponent)) {
                $availableComponent = new $availableComponent();
            }
            if ($availableComponent->isValid($key)) {
                return $availableComponent;
            }
        }
        
        throw new Exception\RuntimeException(sprintf('Could not find a suitable component for "%s"', $key));
    }
}