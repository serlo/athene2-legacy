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
namespace Entity;

use Doctrine\ORM\EntityManager as OrmManager;
use Core\AbstractManager;
use Entity\Entity\EntityInterface;
use Entity\Exception\InvalidArgumentException;
use Entity\Service\EntityServiceInterface;
use Uuid\Manager\UuidManagerAware;

class EntityManager extends AbstractManager implements EntityManagerInterface, UuidManagerAware
{
    
    protected $uuidManager;
    
	/* (non-PHPdoc)
     * @see \Uuid\Manager\UuidManagerAware::getUuidManager()
     */
    public function getUuidManager ()
    {
        return $this->uuidManager;
    }

	/* (non-PHPdoc)
     * @see \Uuid\Manager\UuidManagerAware::setUuidManager()
     */
    public function setUuidManager (\Uuid\Manager\UuidManagerInterface $manager)
    {
        $this->uuidManager = $manager;
        return $this;
    }
    
    /**
     * @var OrmManager
     */
    protected $_entityManager;

	/**
	 * @return OrmManager
	 */
	public function getEntityManager() {
		return $this->_entityManager;
	}
    
    protected $options = array(
        'instances' => array(
	       'manages' => 'Entity\Service\EntityService',
	       'EntityInterface' => 'Entity\Entity\Entity',
	       'EntityFactoryInterface' => 'Entity\Entity\Factory',
        ),
    );
    
    public function __construct(){
        parent::__construct($this->options);
    }

	/**
	 * @param OrmManager $_entityManager
	 */
	public function setEntityManager(OrmManager $_entityManager) {
		$this->_entityManager = $_entityManager;
		return $this;
	}

	private function _getById($id){
	    $entity = $this->getEntityManager()->find($this->resolve('EntityInterface'), $id);
        $entityService = $this->createInstanceFromEntity($entity);
        $this->add($entityService);
        return $this;
    }
    
    private function _getByEntity(EntityInterface $entity){
        $entityService = $this->createInstanceFromEntity($entity);
        $this->add($entityService);
        return $this;
    }
    
    public function get($id){
    	if(is_numeric($id)){
    	} else if ($id instanceof EntityInterface) {
    	    $id = $id->getId();
    	} else {
    	    throw new InvalidArgumentException();
    	}
    	if(!$this->hasInstance($id)){
    	    $this->_getById($id);
    	}
    	return $this->getInstance($id);
    }
    
    public function create($factoryClass){
        
        $em = $this->getEntityManager();
        $factory = $em->getRepository($this->resolve('EntityFactoryInterface'))->findOneByClassName($factoryClass);

        if(!is_object($factory))
            throw new \Exception("Factory `{$factoryClass}` not found."); 
               
        $class = $this->resolve('EntityInterface');
        
        $entity = $this->getUuidManager()->factory($class);  
              
        $entity->populate(array(
            'language' => $this->getServiceManager()->get('Core\Service\LanguageManager')->getRequestLanguage()->getEntity(),
            'factory' => $factory
        ));
        $entity->setFactory($factory);
        echo $entity->getId();
        $em->persist($entity);
        $em->flush();
        
        return $this->get($entity->getId());
    }
    
    public function add(EntityServiceInterface $entityService){
        return $this->addInstance($entityService->getId(), $entityService);
    }
    
    public function createInstanceFromEntity($entity){
        $instance = parent::createInstance();
        $instance->setManager($this);
        $instance->setEntity($entity);
        return $instance->build();
    }
    
    public function delete(EntityServiceInterface $entityService){
        $entityService->trash();
        $entityService->persistAndFlush();
        return $this;
    }
}