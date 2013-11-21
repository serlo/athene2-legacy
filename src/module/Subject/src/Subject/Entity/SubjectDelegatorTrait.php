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
namespace Subject\Entity;

trait SubjectDelegatorTrait
{
    abstract public function getSubjectEntity();
    
    /**
     *
     * @return field_type $type
     */
    public function getType()
    {
        return $this->getSubjectEntity()->getType();
    }
    
    /**
     *
     * @return field_type $taxonomies
     */
    public function getTaxonomies()
    {
        return $this->getSubjectEntity()->getTaxonomies();
    }
    
    /**
     *
     * @return field_type $name
     */
    public function getName()
    {
        return $this->getSubjectEntity()->getName();
    }
    
    public function getTypeName(){
        return $this->getSubjectEntity()->getTypeName();
    }
}