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

class RepositoryService implements RepositoryServiceInterface, EventManagerAwareInterface
{

    private $entityManager;

    private $identifier;

    private $revisions = array();

    private $repository;

    private $trashedRevisions = array();

    private $currentRevision;

    private $authService;

    protected $events;

    public function setEventManager (EventManagerInterface $events)
    {
        $events->setIdentifiers(array(
            __CLASS__,
            get_called_class()
        ));
        $this->events = $events;
        return $this;
    }

    public function getEventManager ()
    {
        return $this->events;
    }

    public function getEntity ()
    {
        return $this->repository->getEntity();
    }

    /**
     *
     * @param AbstractEntity $repository            
     */
    public function setRepository (AbstractEntity $repository)
    {
        $this->repository = $repository;
    }

    /**
     *
     * @return AuthServiceInterface
     */
    public function getAuthService ()
    {
        return $this->authService;
    }

    /**
     *
     * @param AuthServiceInterface $authService            
     */
    public function setAuthService (AuthServiceInterface $authService)
    {
        $this->authService = $authService;
    }

    /**
     *
     * @return the $entityManager
     */
    public function getEntityManager ()
    {
        return $this->entityManager;
    }

    /**
     *
     * @param EntityManager $entityManager            
     */
    public function setEntityManager (EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function setup ($identifier, EntityInterface $repository)
    {
        $this->identifier = $identifier;
        $this->repository = $repository;
        $this->_load();
    }

    private function _load ()
    {
        $this->currentRevision = $this->repository->get('currentRevision');
        $this->trashedRevisions = $this->_getRevisions();
        $this->revisions = $this->_getRevisions();
    }

    private function _adaptRevision ($adaptee)
    {
        if ($adaptee == NULL)
            return NULL;
        return new $this->revisionClass($adaptee);
    }

    private function _getRevisions ($trashed = false)
    {
        $return = array();
        foreach ($this->repository->get('revisions')->toArray() as $revision) {
            $return[$revision->getId()] = $revision;
        }
        return $return;
    }

    public function setIdentifier ($identifier)
    {
        $this->identifier = $identifier;
        return $this;
    }

    public function getIdentifier ()
    {
        return $this->identifier;
    }

    public function setPrototype (RevisionInterface $prototype)
    {
        $this->prototype = $prototype;
        return $this;
    }

    private function _persistEntity ($entity)
    {
        $this->getEntityManager()->persist($entity);
        $this->getEntityManager()->flush();
        return $this;
    }

    public function addRevision (RevisionInterface $revision)
    {
        if ($this->hasRevision($revision))
            throw new \Exception("A revision with the ID `$revision->getId()` already exists in this repository.");
        
        $revisions = $this->getRevisions();
        
        $revision->setRepository($this->repository);
        $this->repository->getRevisions()->add($revision);
        
        $this->persistRevision($revision);
        $this->revisions[$revision->getId()] = $revision;
        
        $this->getEventManager()->trigger(__CLASS__ . '::' . __FUNCTION__, $this, array(
            'action' => 'create',
            'ref' => get_class($revision),
            'refId' => $revision->getId(),
            'user' => $this->getAuthService()
                ->getUser()
        ));
        
        return $this;
    }

    public function deleteRevision (RevisionInterface $revision)
    {
        if (! $this->hasRevision($revision))
            throw new \Exception("A revision with the ID `$revision->getId()` does not exist in this repository.");
        
        unset($revisions[$revision->getId()]);
        $this->_deleteRevision($revision);
        $revisions = $this->getRevisions();
        
        $this->getEventManager()->trigger(__CLASS__ . '::' . __FUNCTION__, $this, array(
            'action' => 'delete',
            'ref' => get_class($revision->getEntity()),
            'refId' => $revision->getId(),
            'user' => $this->getAuthService()
                ->getUser()
        ));
        
        return $this;
    }

    private function _deleteRevision (RevisionInterface $revision)
    {
        $em = $this->getEntityManager();
        $em->remove($revision->getEntity());
        $em->flush();
    }

    public function trashRevision (RevisionInterface $revision)
    {
        if (! $this->hasRevision($revision))
            throw new \Exception("A revision with the ID `$revision->getId()` does not exist in this repository.");
        
        if (in_array($revision->getId(), $this->getTrashedRevisions()))
            throw new \Exception("The revision with the ID `$revision->getId()` is already trashed.");
        
        $this->_trashRevision($revision);
        $revisions = $this->getRevisions();
        unset($revisions[$revision->getId()]);
        
        $revisions = $this->getTrashedRevisions();
        $revisions[$revision->getId()] = $revision;
        
        $this->getEventManager()->trigger(__CLASS__ . '::' . __FUNCTION__, $this, array(
            'action' => 'trash',
            'ref' => get_class($revision->getEntity()),
            'refId' => $revision->getId(),
            'user' => $this->getAuthService()
                ->getUser()
        ));
        
        return $this;
    }

    private function _trashRevision (RevisionInterface $revision)
    {
        $revision->trash();
        $this->persistRevision($revision);
    }

    public function hasRevision ($revision)
    {
        if ($revision instanceof RevisionInterface) {
            return array_key_exists($revision->getId(), $this->getRevisions()) || array_key_exists($revision->getId(), $this->getTrashedRevisions());
        } else {
            return array_key_exists($revision, $this->getRevisions()) || array_key_exists($revision, $this->getTrashedRevisions());
        }
    }

    public function getRevision ($revisionId)
    {
        if (! $this->hasRevision($revisionId))
            throw new \Exception("A revision with the ID `$revisionId` does not exist in the repository `$this->identifier`.");
        
        if ($revisionId instanceof RevisionInterface)
            $revisionId = $revisionId->getId();
        
        return (array_key_exists($revisionId, $this->getRevisions())) ? $this->revisions[$revisionId] : $this->trashedRevisions[$revisionId];
    }

    public function getTrashedRevisions ()
    {
        return $this->trashedRevisions;
    }

    public function getRevisions ()
    {
        return $this->revisions;
    }

    public function getHead ()
    {
        return current($this->getRevisions());
    }

    public function checkoutRevision (RevisionInterface $revision)
    {
        if (! $this->hasRevision($revision))
            throw new \Exception('Revision ' . $revision->getId() . ' not existent in this repository');
        
        $this->repository->set('currentRevision', $revision);
        $this->currentRevision = $revision;
        $this->persist();
        
        $this->getEventManager()->trigger(__CLASS__ . '::' . __FUNCTION__, $this, array(
            'action' => 'checkout',
            'ref' => get_class($revision->getEntity()),
            'refId' => $revision->getId(),
            'user' => $this->getAuthService()
                ->getUser()
        ));
        
        return $this;
    }

    public function getCurrentRevision ()
    {
        if ($this->currentRevision == NULL)
            throw new \Exception('No Revision set!');
        
        return $this->currentRevision;
    }

    public function mergeRevisions (RevisionInterface $revision, RevisionInterface $base)
    {
        throw new \Exception("Not implemented yet");
    }

    public function persistRevision (RevisionInterface $revision)
    {
        $em = $this->getEntityManager();
        $em->persist($revision);
        $em->flush();
        return $this;
    }

    public function persist ()
    {
        return $this->persistRepository($this->repository);
    }

    public function persistRepository (RepositoryInterface $repository)
    {
        $em = $this->getEntityManager();
        $em->persist($repository);
        $em->flush();
        return $this;
    }
}