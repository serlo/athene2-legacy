<?php
namespace Versioning\Service;

use Versioning\Entity\RevisionInterface;
use Versioning\Entity\RepositoryInterface;
use Doctrine\ORM\EntityManager;
use Auth\Service\AuthServiceInterface;
use Core\Entity\AbstractEntityAdapter;

class RepositoryService implements RepositoryServiceInterface
{

    private $entityManager;

    private $identifier;

    private $prototype;

    private $revisions;

    private $repository;
    
    private $trashedRevisions;

    private $currentRevision;
    
    private $authService;

    /**
	 * @return AuthServiceInterface
	 */
	public function getAuthService() {
		return $this->authService;
	}

	/**
	 * @param AuthServiceInterface $authService
	 */
	public function setAuthService(AuthServiceInterface $authService) {
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

    public function __construct ($identifier, RepositoryInterface $repository)
    {
        $this->identifier = $identifier;
        $this->repository = $repository;
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
    
    private function _persistEntity($entity){
        $this->getEntityManager()->persist($entity);
        $this->getEntityManager()->flush();
        return $this;
    }

    public function addRevision (RevisionInterface $revision)
    {
        if ($this->hasRevision($revision))
            throw new \Exception("A revision with the ID `$revision->getId()` already exists in this repository.");
        
        $this->getRevisions()[$revision->getId()] = $revision;
        $revision->setFieldValue('repository', $this->repository);
        $this->persistRevision($revision);
        return $this;
    }

    public function deleteRevision (RevisionInterface $revision)
    {
        if (! $this->hasRevision($revision))
            throw new \Exception("A revision with the ID `$revision->getId()` does not exist in this repository.");

        unset($revisions[$revision->getId()]);
        $this->_deleteRevision($revision);
        $revisions = $this->getRevisions();
        return $this;
    }
    
    private function _deleteRevision(RevisionInterface $revision){
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
        
        return $this;
    }

    private function _trashRevision (RevisionInterface $revision)
    {
        $revision->trash();
        $this->persistRevision($revision);
    }

    public function hasRevision (RevisionInterface $revision)
    {
        return in_array($revision->getId(), $this->getRevisions()) || in_array($revision->getId(), $this->getTrashedRevisions());
    }

    public function getRevision ($revisionId)
    {
        // TODO Auto-generated method stub
        if (! $this->hasRevision($revisionId))
            throw new \Exception("A revision with the ID `$revisionId` does not exist in this repository.");
        
        return (in_array($revisionId, $this->getRevisions())) ? $this->revisions[$revisionId] : $this->trashedRevisions[$revisionId];
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
        
        $this->repository->setFieldValue('currentRevision', $revision);
        $revision->setConfirmer($this->getAuthService()->getUser());
        $revision->setConfirmDate(date('Y-m-d H:i'));
        return $this->persist();
    }

    public function getCurrentRevision ()
    {
        return $this->currentRevision;
    }

    public function mergeRevisions (RevisionInterface $revision, RevisionInterface $base)
    {
        throw new \Exception("Not implemented yet");
    }
    
    public function persistRevision(AbstractEntityAdapter $revision){
        $em = $this->getEntityManager();
        $em->persist($revision->getAdaptee());
        $em->flush();
        return $this;
    }
    
    public function persist(){
        return $this->persist($this->getAdaptee());
    }
    
    public function persistRepository(AbstractEntityAdapter $repository){
        $em = $this->getEntityManager();
        $em->persist($repository->getAdaptee());
        $em->flush();
        return $this;
    }
}