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
namespace Core\Service;

use Core\Structure\AbstractDecorator;
use Core\Entity\EntityInterface;
use DoctrineModule\Persistence\ObjectManagerAwareInterface;

class AbstractEntityDecorator extends AbstractDecorator implements ObjectManagerAwareInterface
{
    /**
     * 
     * @var \Doctrine\Common\Persistence\ObjectManager
     */
    protected $objectManager;

    /* (non-PHPdoc)
     * @see \DoctrineModule\Persistence\ObjectManagerAwareInterface::getObjectManager()
     */
    public function getObjectManager ()
    {
        return $this->objectManager;
    }

	/* (non-PHPdoc)
     * @see \DoctrineModule\Persistence\ObjectManagerAwareInterface::setObjectManager()
     */
    public function setObjectManager (\Doctrine\Common\Persistence\ObjectManager $objectManager)
    {
        $this->objectManager = $objectManager;
        return $this;
    }

	/**
     *
     * @return \Core\Entity\EntityInterface $entity
     */
    public function getEntity()
    {
        return $this->getConcreteComponent();
    }

    /**
     *
     * @param \Core\Entity\EntityInterface $entity            
     * @return $this
     */
    public function setEntity(EntityInterface $entity)
    {
        if(!is_object($entity))
            throw new \Exception('Not an object.');
        
        $this->setConcreteComponent($entity);
        return $this;
    }

    /**
     * Persist the entity
     * 
     * @return $this
     */
    public function persist ()
    {
        $om = $this->getObjectManager();
        $om->persist($this->getEntity());
        return $this;
    }
    
    /**
     * Persists the entity and flushes the ObjectManager
     * 
     * @return $this
     */
    public function persistAndFlush(){
        $om = $this->getObjectManager();
        $this->persist();
        $om->flush();
        return $this;
    }
}