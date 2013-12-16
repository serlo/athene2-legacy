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
    protected $enabledComponents = [];

    /**
     *
     * @var array
     */
    protected $allowedChildren = [];

    /**
     *
     * @var array
     */
    protected $allowedParents = [];

    /**
     *
     * @var array
     */
    protected $repository = [];

    /**
     *
     * @return array $enabledComponents
     */
    public function getEnabledComponents()
    {
        return $this->enabledComponents;
    }

    /**
     *
     * @return array $allowedChildren
     */
    public function getAllowedChildren()
    {
        return $this->allowedChildren;
    }

    /**
     *
     * @return array $allowedParents
     */
    public function getAllowedParents()
    {
        return $this->allowedParents;
    }

    public function getRepositoryForm()
    {
        if (! array_key_exists('form', $this->repository)) {
            throw new Exception\RuntimeException('No form has been set');
        }
        return $this->repository['form'];
    }

    public function getRepositoryFields()
    {
        if (! array_key_exists('fields', $this->repository)) {
            throw new Exception\RuntimeException('No fields have been set');
        }
        return $this->repository['fields'];
    }

    /**
     *
     * @param array $component            
     * @return boolean
     */
    public function isComponentEnabled(array $component)
    {
        return in_array($component, $this->getEnabledComponents());
    }

    /**
     *
     * @param unknown $parent            
     * @return boolean
     */
    public function isParentAllowed($parent)
    {
        return $this->getParent($parent) !== NULL;
    }

    /**
     *
     * @param string $child            
     * @return boolean
     */
    public function isChildAllowed($child)
    {
        return $this->getChild($child) !== NULL;
    }

    public function areMultipleChildrenAllowed($child)
    {
        $child = $this->getChild($child);
        if ($child === NULL) {
            return false;
        }
        
        return array_key_exists('many', $child) ? $child['many'] : false;
    }

    /**
     *
     * @param string $parent            
     * @return boolean
     */
    public function areMultipleParentsAllowed($parent)
    {
        $parent = $this->getParent($parent);
        if ($parent === NULL) {
            return false;
        }
        
        return array_key_exists('many', $parent) ? $parent['many'] : false;
    }

    /**
     *
     * @param array $enabledComponents            
     * @return $this
     */
    public function setEnabledComponents(array $enabledComponents)
    {
        $this->enabledComponents = $enabledComponents;
        return $this;
    }

    /**
     *
     * @param array $allowedChildren            
     * @return $this
     */
    public function setAllowedChildren(array $allowedChildren)
    {
        $this->allowedChildren = $allowedChildren;
        return $this;
    }

    /**
     *
     * @param array $allowedParents            
     * @return $this
     */
    public function setAllowedParents(array $allowedParents)
    {
        $this->allowedParents = $allowedParents;
        return $this;
    }

    /**
     *
     * @param array $repository            
     * @return $this
     */
    public function setRepository(array $repository)
    {
        $this->repository = $repository;
        return $this;
    }

    /**
     *
     * @param string $type            
     * @return array NULL
     */
    protected function getChild($type)
    {
        foreach ($this->getAllowedChildren() as $child) {
            if ($child['type'] == $type) {
                return $child;
            }
        }
        return NULL;
    }

    /**
     *
     * @param string $type            
     * @return array NULL
     */
    protected function getParent($type)
    {
        foreach ($this->getAllowedParents() as $parent) {
            if ($parent['type'] == $type) {
                return $parent;
            }
        }
        return NULL;
    }
}