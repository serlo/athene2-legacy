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
namespace Uuid\Manager;

use Core\AbstractManager;
use DoctrineModule\Persistence\ObjectManagerAwareInterface;
use Uuid\Entity\UuidHolder;

class UuidManager extends AbstractManager implements ObjectManagerAwareInterface, UuidManagerInterface
{
    protected $options = array(
        'instances' => array(
            'manages' => 'Uuid\Entity\Uuid',
        )
    );

    protected $objectManager;
    
    /*
     * (non-PHPdoc) @see \DoctrineModule\Persistence\ObjectManagerAwareInterface::setObjectManager()
     */
    public function setObjectManager (\Doctrine\Common\Persistence\ObjectManager $objectManager)
    {
        $this->objectManager = $objectManager;
        return $this;
    }
    
    /*
     * (non-PHPdoc) @see \DoctrineModule\Persistence\ObjectManagerAwareInterface::getObjectManager()
     */
    public function getObjectManager ()
    {
        return $this->objectManager;
    }
    
    public function __construct ()
    {
        parent::__construct($this->options);
    }
    
    public function inject(UuidHolder $entity, $uuid = NULL){
        $name = $this->resolve('manages');
        if(!$uuid instanceof $name){
            $uuid = $this->create();
        }
        return $entity->setUuid($uuid);
    }
    
    public function get($key){
        $className = $this->resolve('manages');
        if(is_numeric($key)){
            $entity = $this->getObjectManager()->find($this->resolve('manages'), (int) $key);
        } elseif (is_string($key)) {
            $entity = $this->getObjectManager()->getRepository($this->resolve('manages'))->findOneByUuid((string) $key);
        } elseif ($key instanceof $className){
            $entity = $key;
        } else
            throw new \InvalidArgumentException(); 
        
        if(!is_object($entity))
            throw new \Exception('not found');

        $this->addInstance($entity->getId(), $entity);
        return $entity;
    }
    
    public function factory($class){
        return new $class($this->create());
    }
    
    public function create(){
        $entity = $this->createInstance();
        $em = $this->getObjectManager();
        $em->persist($entity);
        $em->flush($entity);
        $this->addInstance($entity->getId(), $entity);
        return $entity;
    }
}