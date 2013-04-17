<?php
namespace Entity;

use Entity\Service\EntityServiceInterface;
use Doctrine\Common\Collections\Criteria;

class EntityManager
{
    public function updateEntity($id, array $data);
    public function createEntity(array $data);
    public function removeEntity($id);
    public function getEntity($id);
    public function addEntity(EntityServiceInterface $entity);
    public function getEntities(Criteria $criteria);
}