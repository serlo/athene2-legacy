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
use Doctrine\Common\Collections\ArrayCollection;
use Entity\Exception\BadMethodCallException;
use Entity\Collection\EntityCollection;
use Entity\Service\EntityServiceInterface;

class EntityPlugin extends AbstractPlugin
{
    use \Entity\Manager\EntityManagerAwareTrait,\Common\Traits\ObjectManagerAwareTrait;

    protected function getDefaultConfig()
    {
        return array();
    }

    private function getEntities()
    {
        return $this->getSubjectService()
            ->getTermService()
            ->getAssociated('entities', true, array(
            'topic',
            'topic-folder',
            'subject',
            'curriculum',
            'curriculum-folder',
            'school-type'
        ));
    }
    
    public function getTrashedEntities()
    {
        $entities = $this->getEntities();
        $collection = new ArrayCollection();
        $this->iterEntities($entities, $collection, 'isTrashed');
        return $collection;        
    }

    public function getUnrevisedEntities()
    {
        $entities = $this->getEntities();
        $collection = new ArrayCollection();
        $this->iterEntities($entities, $collection, 'isRevised');
        return $collection;
    }

    private function iterEntities(\Doctrine\Common\Collections\Collection $entities,\Doctrine\Common\Collections\Collection $collection, $callback){
        foreach ($entities as $entity) {
            $this->$callback($entity, $collection);
            $this->iterLinks($entity, $collection, $callback);
        }
    }
    
    private function iterLinks($entity, $collection, $callback)
    {
        foreach ($entity->getScopesForPlugin('link') as $scope) {
            if ($entity->$scope()->hasChildren()) {
                $this->iterEntities($entity->$scope()
                    ->findChildren(), $collection, $callback);
            }
        }
    }
    
    private function isRevised(EntityServiceInterface $entity,\Doctrine\Common\Collections\Collection $collection)
    {
        foreach ($entity->getScopesForPlugin('repository') as $scope) {
            if ($entity->$scope()->isUnrevised() && ! $collection->contains($entity)) {
                $collection->add($entity);
            }
        }
    }
    
    private function isTrashed(EntityServiceInterface $entity,\Doctrine\Common\Collections\Collection $collection)
    {
        if ($entity->getTrashed() === TRUE) {
            $collection->add($entity);
        }
    }
}