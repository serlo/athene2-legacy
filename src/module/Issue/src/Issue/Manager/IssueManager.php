<?php
/**
 *
 * @author Aeneas Rekkas (aeneas.rekkas@serlo.org)
 * @copyright 2013 by www.serlo.org
 * @license LGPL
 * @license http://www.gnu.org/copyleft/lesser.html GNU Lesser General Public License (LGPL)
 */
namespace Issue\Manager;

use Core\AbstractManager;
use DoctrineModule\Persistence\ObjectManagerAwareInterface;
use Issue\Exception\NotFoundException;
use Core\Collection\DecoratorCollection;
use Issue\Entity\IssueInterface;
use Issue\Exception\InvalidArgumentException;
use Doctrine\Common\Collections\ArrayCollection;
use Uuid\Manager\UuidManagerAware;
use Auth\Service\AuthServiceAware;

class IssueManager extends AbstractManager implements ObjectManagerAwareInterface, IssueManagerInterface, UuidManagerAware, AuthServiceAware
{
    protected $authService;
    
    protected $objectManager;
    
    protected $uuidManager;
    
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

    protected $options = array(
        'instances' => array(
            'manages' => 'Issue\Service\IssueService',
            'EntityInterface' => 'Issue\Entity\IssueEntity'
        )
    );

    public function __construct ()
    {
        parent::__construct($this->options);
    }

    public function get ($key)
    {
        if (! $this->hasInstance($key)) {
            if(is_numeric($key)){
                $key = (int) $key;
                $entity = $this->getObjectManager()->find($this->resolve('EntityInterface'), $key);
                $instance = $this->createInstanceFromEntity($entity);
                $this->addInstance($key, $instance);
            } elseif (is_object($key) && $key instanceof IssueInterface){
                $instance = $this->createInstanceFromEntity($key);
                $this->addInstance($key->getId(), $instance);
            } else {
                throw new InvalidArgumentException();
            }
        }
        return $this->getInstance($key);
    }
    
    public function getAllOpenIssues(){
        return new DecoratorCollection(
            new ArrayCollection( $this->getObjectManager()
                ->getRepository($this->resolve('EntityInterface'))
                ->findByClosed(false)), $this);
    }
    
    public function getAllClosedIssues(){
        return new DecoratorCollection(
            new ArrayCollection( $this->getObjectManager()
                ->getRepository($this->resolve('EntityInterface'))
                ->findByClosed(true)), $this);
    }

    public function getAllIssues ()
    {
        return new DecoratorCollection(
            new ArrayCollection( $this->getObjectManager()
            ->getRepository($this->resolve('EntityInterface'))
            ->findAll()), $this);
    }

    protected function createInstanceFromEntity ($entity)
    {
        if (! is_object($entity))
            throw new NotFoundException();
        
        $instance = parent::createInstance();
        $instance->setEntity($entity);
        return $instance;
    }
    
    public function create($on){
        $uuid = $this->getUuidManager()->create();
        $className = $this->resolve('EntityInterface');
        $entity = new $className($uuid);
        $entity->setAuthor($this->getAuthService()->getUser());
        $entity->setOn($this->getUuidManager()->get($on));
        $this->getObjectManager()->persist($entity);
        $this->getObjectManager()->flush();
        $instance = $this->createInstanceFromEntity($entity);
        $this->addInstance($instance->getId(), $instance);
        return $instance;
    }
    
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
    
	/* (non-PHPdoc)
     * @see \Auth\Service\AuthServiceAware::setAuthService()
     */
    public function setAuthService (\Auth\Service\AuthServiceInterface $service)
    {
        $this->authService = $service;
        return $this;
    }

	/* (non-PHPdoc)
     * @see \Auth\Service\AuthServiceAware::getAuthService()
     */
    public function getAuthService ()
    {
        return $this->authService;
    }

}