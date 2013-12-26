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

abstract class AbstractLinkOptions extends AbstractOptions implements ComponentOptionsInterface
{

    protected $type;

    protected $children = [];

    protected $parents = [];

    abstract public function getLinkType();

    public function getAllowedChildren()
    {
        return array_keys($this->children);
    }

    public function getAllowedParents()
    {
        return array_keys($this->parents);
    }

    public function isParentAllowed($type)
    {
        return in_array($type, $this->getAllowedParents());
    }

    public function isChildAllowed($type)
    {
        return in_array($type, $this->getAllowedChildren());
    }

    public function allowsManyParents($type)
    {
        if (! $this->isParentAllowed($type)) {
            throw new Exception\RuntimeException(sprintf('Link type "%s" does not allow parent "%s".', $this->getLinkType(), $type));
        }
        
        return array_key_exists('multiple', $this->parents[$type]) ? $this->parents[$type]['multiple'] : false;
    }
    
    public function allowsManyChildren($type)
    {
        if (! $this->isChildAllowed($type)) {
            throw new Exception\RuntimeException(sprintf('Link "%s" does not allow child "%s".', $this->getLinkType(), $type));
        }
        
        return array_key_exists('multiple', $this->children[$type]) ? $this->children[$type]['multiple'] : false;
    }

    public function isValid($key)
    {
        return $key == $this->getLinkType();
    }

    public function setType($type)
    {
        $this->type = $type;
        return $this;
    }

    public function setChildren($children)
    {
        $this->children = $children;
        return $this;
    }

    public function setParents($parents)
    {
        $this->parents = $parents;
        return $this;
    }
}