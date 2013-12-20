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
namespace Taxonomy\Options;

use Zend\Stdlib\AbstractOptions;

class TaxonomyOptions extends AbstractOptions
{

    protected $allowedChildren = [];

    protected $rootable;

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
     * @param string $child
     * @return boolean
     */
    public function isChildAllowed($child){
        return in_array($child, $this->allowedChildren);
    }

    /**
     *
     * @return bool $rootable
     */
    public function isRootable()
    {
        return $this->rootable;
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
     * @param bool $rootable            
     * @return self
     */
    public function setRootable($rootable)
    {
        $this->rootable = $rootable;
        return $this;
    }
}