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
namespace Term\Service;

class TermService implements TermServiceInterface
{
    use \Common\Traits\EntityDelegatorTrait, \Common\Traits\ObjectManagerAwareTrait;
    
    /**
     *
     * @return field_type $language
     */
    public function getLanguage()
    {
        return $this->getEntity()->getLanguage();
    }
    
    /**
     *
     * @return field_type $name
     */
    public function getName()
    {
        return $this->getEntity()->getName();
    }
    
    /**
     *
     * @return field_type $slug
     */
    public function getId()
    {
        return $this->getEntity()->getId();
    }
    
    /**
     *
     * @return field_type $slug
     */
    public function getSlug()
    {
        return $this->getEntity()->getSlug();
    }
    
    /**
     *
     * @param field_type $language
     * @return $this
     */
    public function setLanguage($language)
    {
        $this->getEntity()->setLanguage($language);
        return $this;
    }
    
    /**
     *
     * @param field_type $name
     * @return $this
     */
    public function setName($name)
    {
        $this->getEntity()->setName($name);
        return $this;
    }
    
    /**
     *
     * @param field_type $slug
     * @return $this
     */
    public function setSlug($slug)
    {
        $this->getEntity()->setSlug($slug);
        return $this;
    }
}