<?php
namespace Versioning\Service;

use Versioning\Entity\RevisionInterface;
use Zend\EventManager\EventManagerInterface;
use Zend\EventManager\EventManagerAwareInterface;
use Doctrine\ORM\EntityManager;
use Auth\Service\AuthServiceInterface;
use Core\Entity\AbstractEntityAdapter;
use Core\Entity\AbstractEntity;
use Versioning\Entity\RepositoryInterface;
use Core\Entity\EntityInterface;
use Versioning\Exception\OutOfSynchException;
use Versioning\Exception\RevisionNotFoundException;
use Doctrine\Common\Collections\Criteria;

class RepositoryService implements RepositoryServiceInterface, EventManagerAwareInterface
{

    private $entityManager;

    private $identifier;

    private $revisions = array();

    private $repository;

    private $currentRevision;

    private $authService;

    protected $events;

    /**
     * (non-PHPdoc)
     * @see \Zend\EventManager\EventManagerAwareInterface::setEventManager()
     */
    public function setEventManager(EventManagerInterface $events)
    {
        $events->setIdentifiers(array(
            __CLASS__,
            get_called_class()
        ));
        $this->events = $events;
        return $this;
    }

    /**
     * (non-PHPdoc)
     * @see \Zend\EventManager\EventsCapableInterface::getEventManager()
     */
    public function getEventManager()
    {
        return $this->events;
    }

    public function getEntity()
    {
        return $this->repository->getEntity();
    }
    
    public function countRevisions(){
        return $this->getRevisions()->count();
    }

    /**
     *
     * @param AbstractEntity $repository            
     */
    public function setRepository(AbstractEntity $repository)
    {
        $this->repository = $repository;
    }

    /**
     *
     * @return AuthServiceInterface
     */
    public function getAuthService()
    {
        return $this->authService;
    }

    /**
     *
     * @param AuthServiceInterface $authService            
     */
    public function setAuthService(AuthServiceInterface $authService)
    {
        $this->authService = $authService;
    }

    /**
     *
     * @return the $entityManager
     */
    public function getEntityManager()
    {
        return $this->entityManager;
    }

    /**
     *
     * @param EntityManager $entityManager            
     */
    public function setEntityManager(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * (non-PHPdoc)
     * @see \Versioning\Service\RepositoryServiceInterface::setup()
     */
    public function setup($identifier, RepositoryInterface $repository)
    {
        $this->identifier = $identifier;
        $this->repository = $repository;
        $this->_load();
    }

    /**
     * 
     */
    private function _load()
    {
        $this->currentRevision = $this->repository->getCurrentRevision();
        $this->revisions = $this->_getRevisions();
    }

    /**
     * 
     * @param unknown $adaptee
     * @return NULL|unknown
     */
    private function _adaptRevision($adaptee)
    {
        if ($adaptee == NULL)
            return NULL;
        return new $this->revisionClass($adaptee);
    }

    /**
     * 
     * @param string $trashed
     * @return unknown
     */
    private function _getRevisions($trashed = false)
    {
        $return = $this->repository->getRevisions();
        return $return;
    }

    /**
     * (non-PHPdoc)
     * @see \Versioning\Service\RepositoryServiceInterface::setIdentifier()
     */
    public function setIdentifier($identifier)
    {
        $this->identifier = $identifier;
        return $this;
    }

    /**
     * (non-PHPdoc)
     * @see \Versioning\Service\RepositoryServiceInterface::getIdentifier()
     */
    public function getIdentifier()
    {
        return $this->identifier;
    }

    /**
     * 
     * @param RevisionInterface $prototype
     * @return \Versioning\Service\RepositoryService
     */
    public function setPrototype(RevisionInterface $prototype)
    {
        $this->prototype = $prototype;
        return $this;
    }

    /**
     * 
     * @param unknown $entity
     * @return \Versioning\Service\RepositoryService
     */
    private function _persistEntity($entity)
    {
        $this->getEntityManager()->persist($entity);
        $this->getEntityManager()->flush();
        return $this;
    }

    /**
     * (non-PHPdoc)
     * @see \Versioning\Service\RepositoryServiceInterface::addRevision()
     */
    public function addRevision(RevisionInterface $revision)
    {
        if ($this->hasRevision($revision))
            throw new OutOfSynchException("A revision with the ID `$revision->getId()` already exists in this repository.");
        
        $revisions = $this->getRevisions();
        
        $revision->setRepository($this->repository);
        $this->repository->getRevisions()->add($revision);
        
        $this->revisions->add($revision);
        $this->persistRevision($revision);
        
        $this->getEventManager()->trigger(__CLASS__ . '::' . __FUNCTION__, $this, array(
            'action' => 'create',
            'ref' => get_class($revision),
            'refId' => $revision->getId(),
            'user' => $this->getAuthService()
                ->getUser()
        ));
        
        return $this;
    }

    /**
     * (non-PHPdoc)
     * @see \Versioning\Service\RepositoryServiceInterface::removeRevision()
     */
    public function removeRevision(RevisionInterface $revision)
    {
        if (! $this->hasRevision($revision))
            throw new RevisionNotFoundException("A revision with the ID `$revision->getId()` does not exist in this repository.");
        
        $id = $revision->getId();
        $this->_deleteRevision($revision);
        $revisions = $this->getRevisions()->removeElement($revision);
        
        $this->getEventManager()->trigger(__CLASS__ . '::' . __FUNCTION__, $this, array(
            'action' => 'delete',
            'ref' => get_class($revision),
            'refId' => $id,
            'user' => $this->getAuthService()
                ->getUser()
        ));
        
        return $this;
    }

    /**
     * 
     * @param RevisionInterface $revision
     */
    private function _deleteRevision(RevisionInterface $revision)
    {
        $em = $this->getEntityManager();
        $em->remove($revision);
        $em->flush();
    }

    /**
     * (non-PHPdoc)
     * @see \Versioning\Service\RepositoryServiceInterface::hasRevision()
     */
    public function hasRevision($revision)
    {
        if ($revision instanceof RevisionInterface) {
            $id = $revision->getId();
        } else {
            $id = $revision;
        }
        
        $criteria = Criteria::create()->where(Criteria::expr()->eq("id", $id))
            ->setMaxResults(1);
        return $revision = $this->getRevisions()
            ->matching($criteria)
            ->count() === 1;
    }

    /**
     * (non-PHPdoc)
     * @see \Versioning\Service\RepositoryServiceInterface::getRevision()
     */
    public function getRevision($revisionId)
    {
        if (! $this->hasRevision($revisionId))
            throw new RevisionNotFoundException("A revision with the ID `$revisionId` does not exist in the repository `$this->identifier`.");
        
        if ($revisionId instanceof RevisionInterface)
            $revisionId = $revisionId->getId();
        
        $criteria = Criteria::create()->where(Criteria::expr()->eq("id", $revisionId))
            ->setMaxResults(1);
        $revision = $this->getRevisions()
            ->matching($criteria)
            ->current();
        return $revision;
    }

    /**
     * (non-PHPdoc)
     * @see \Versioning\Service\RepositoryServiceInterface::getRevisions()
     */
    public function getRevisions()
    {
        return $this->repository->getRevisions();
    }

    /**
     * (non-PHPdoc)
     * @see \Versioning\Service\RepositoryServiceInterface::getHead()
     */
    public function getHead()
    {
        return $this->getRevisions()->current();
    }

    /**
     * (non-PHPdoc)
     * @see \Versioning\Service\RepositoryServiceInterface::checkoutRevision()
     */
    public function checkoutRevision(RevisionInterface $revision)
    {
        if (! $this->hasRevision($revision))
            throw new RevisionNotFoundException('Revision ' . $revision->getId() . ' not existent in this repository');
        
        $this->repository->setCurrentRevision($revision);
        $this->currentRevision = $revision;
        $this->persist();
        
        $this->getEventManager()->trigger(__CLASS__ . '::' . __FUNCTION__, $this, array(
            'action' => 'checkout',
            'ref' => get_class($revision),
            'refId' => $revision->getId(),
            'user' => $this->getAuthService()
                ->getUser()
        ));
        
        return $this;
    }

    /**
     * (non-PHPdoc)
     * @see \Versioning\Service\RepositoryServiceInterface::getCurrentRevision()
     */
    public function getCurrentRevision()
    {
        if ($this->currentRevision == NULL)
            throw new RevisionNotFoundException();
        
        return $this->currentRevision;
    }
    
    public function hasHead(){
        return $this->hasCurrentRevision();
    }
    
    public function hasCurrentRevision(){
        return $this->currentRevision !== NULL;
    }

    public function isUnrevised(){
        return ($this->hasCurrentRevision() && $this->getCurrentRevision() !== $this->getHead()) || (!$this->hasCurrentRevision() && $this->getRevisions()->count() > 0);
    }
    
    /**
     * (non-PHPdoc)
     * @see \Versioning\Service\RepositoryServiceInterface::mergeRevisions()
     */
    public function mergeRevisions(RevisionInterface $revision, RevisionInterface $base)
    {
        throw new \Exception("Not implemented yet");
    }

    /**
     * (non-PHPdoc)
     * @see \Versioning\Service\RepositoryServiceInterface::persistRevision()
     */
    public function persistRevision(RevisionInterface $revision)
    {
        $em = $this->getEntityManager();
        $em->persist($revision);
        $em->flush();
        return $this;
    }

    /**
     * 
     * @return \Versioning\Service\RepositoryService
     */
    public function persist()
    {
        return $this->persistRepository($this->repository);
    }

    /**
     * 
     * @param RepositoryInterface $repository
     * @return \Versioning\Service\RepositoryService
     */
    public function persistRepository(RepositoryInterface $repository)
    {
        $em = $this->getEntityManager();
        $em->persist($repository);
        $em->flush();
        return $this;
    }
}