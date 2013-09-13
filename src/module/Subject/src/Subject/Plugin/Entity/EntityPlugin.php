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
namespace Subject\Plugin\Entity;

use Subject\Plugin\AbstractPlugin;
use Entity\Entity\Entity;
use Entity\Collection\EntityCollection;
use Doctrine\Common\Collections\ArrayCollection;
use Entity\Exception\BadMethodCallException;

class EntityPlugin extends AbstractPlugin 
{
    use \Entity\Manager\EntityManagerAwareTrait, \Common\Traits\ObjectManagerAwareTrait;
    
    protected function getDefaultConfig ()
    {
        return array();
    }
    
    public function getEntities(){
        return $this->getSubjectService()->getTermService()->getLinks('entities', true, array('topic', 'entity-folder', 'subject'));//$this->getObjectManager()->createQuery(sprintf('SELECT e FROM Entity\Entity\Entity e JOIN e.terms te JOIN te.taxonomy ta WHERE ta.id = %d', $this->getSubjectService()->getId()));
    }
    
    public function getUnrevisedEntities(){
        $entities = $this->getEntities();
        $collection = new ArrayCollection();
        foreach($entities->asService() as $entity){
            try{
                if($entity->repository()->isUnrevised()){
                    $collection->add($entity);
                }
            } catch (BadMethodCallException $e){}
        }
        return $collection;
    }
}