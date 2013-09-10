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
namespace Versioning\Service;

use Versioning\Entity\RevisionInterface;
use Core\Entity\AbstractEntity;
use Versioning\Entity\RepositoryInterface;
use Versioning\Exception\OutOfSynchException;
use Versioning\Exception\RevisionNotFoundException;

class RepositoryService implements RepositoryServiceInterface
{
    use \Auth\Service\AuthServiceAwareTrait, \Zend\EventManager\EventManagerAwareTrait, \Common\Traits\ObjectManagerAwareTrait, \Common\Traits\EntityDelegatorTrait;

    private $identifier;    

    public function getRepository()
    {
        return $this->getEntity();
    }
    
    public function countRevisions(){
        return $this->getRevisions()->count();
    }

    /**
     *
     * @param AbstractEntity $repository            
     */
    public function setRepository(RepositoryInterface $repository)
    {
        $this->setEntity($repository);
        return $this;
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
     * (non-PHPdoc)
     * @see \Versioning\Service\RepositoryServiceInterface::addRevision()
     */
    public function addRevision(RevisionInterface $revision)
    {
        if ($this->hasRevision($revision))
            throw new OutOfSynchException("A revision with the ID `$revision->getId()` already exists in this repository.");
        
        $revisions = $this->getRevisions();
        
        $revision->setRepository($this->getEntity());
        $this->getEntity()->addRevision($revision);
        
        //$this->persistRevision($revision);
        
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
        //$this->deleteRevision($revision);
        $revisions = $this->getRevisions()->remove($revision->getId());
        
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
    /*private function deleteRevision(RevisionInterface $revision)
    {
        $this->getObjectManager()->remove($revision);
        return $this;
    }*/

    /**
     * (non-PHPdoc)
     * @see \Versioning\Service\RepositoryServiceInterface::hasRevision()
     */
    public function hasRevision($revision)
    {
        if ($revision instanceof RevisionInterface) {
            $id = $revision->getId();
        } elseif(is_numeric($revision)) {
            $id = $revision;
        } else {
            throw new \Versioning\Exception\InvalidArgumentException();
        }
        
        return $this->getRevisions()->containsKey($id);
    }

    /**
     * (non-PHPdoc)
     * @see \Versioning\Service\RepositoryServiceInterface::getRevision()
     */
    public function getRevision($id)
    {
        if (! $this->hasRevision($id))
            throw new RevisionNotFoundException("A revision with the ID `$id` does not exist in the repository `$this->identifier`.");
        
        if ($id instanceof RevisionInterface)
            $id = $id->getId();
        
        return $revision = $this->getRevisions()->get($id);
    }

    /**
     * (non-PHPdoc)
     * @see \Versioning\Service\RepositoryServiceInterface::getRevisions()
     */
    public function getRevisions()
    {
        return $this->getEntity()->getRevisions();
    }

    /**
     * (non-PHPdoc)
     * @see \Versioning\Service\RepositoryServiceInterface::getHead()
     */
    public function getHead()
    {
        return $this->getRevisions()->last();
    }

    /**
     * (non-PHPdoc)
     * @see \Versioning\Service\RepositoryServiceInterface::checkoutRevision()
     */
    public function checkoutRevision(RevisionInterface $revision)
    {
        if (! $this->hasRevision($revision))
            throw new RevisionNotFoundException('Revision ' . $revision->getId() . ' not existent in this repository');
        
        $this->getEntity()->setCurrentRevision($revision);
        //$this->persist();
        
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
        if (! $this->hasCurrentRevision() )
            throw new RevisionNotFoundException();
        
        return $this->getEntity()->getCurrentRevision();
    }
    
    public function hasCurrentRevision(){
        return $this->getEntity()->hasCurrentRevision();
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
        $em = $this->getObjectManager();
        $em->persist($revision);
        return $this;
    }

    /**
     * 
     * @return \Versioning\Service\RepositoryService
     */
    public function persist()
    {
        return $this->persistRepository($this->getEntity());
    }

    /**
     * 
     * @param RepositoryInterface $repository
     * @return \Versioning\Service\RepositoryService
     */
    public function persistRepository(RepositoryInterface $repository)
    {
        $em = $this->getObjectManager();
        $em->persist($repository);
        return $this;
    }
}