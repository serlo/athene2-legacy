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
use Versioning\Entity\RepositoryInterface;
use Versioning\Exception\OutOfSynchException;
use Versioning\Exception\RevisionNotFoundException;
use Doctrine\Common\Collections\Criteria;

class RepositoryService implements RepositoryServiceInterface
{
    use \Zend\EventManager\EventManagerAwareTrait,\Common\Traits\EntityDelegatorTrait;

    private $identifier;

    public function getRepository()
    {
        return $this->getEntity();
    }

    public function countRevisions()
    {
        return $this->getRevisions()->count();
    }

    public function setRepository(RepositoryInterface $repository)
    {
        $this->setEntity($repository);
        return $this;
    }

    public function setIdentifier($identifier)
    {
        $this->identifier = $identifier;
        return $this;
    }

    public function getIdentifier()
    {
        return $this->identifier;
    }

    public function addRevision(RevisionInterface $revision)
    {
        //if ($this->hasRevision($revision->getId()))
        //    throw new OutOfSynchException("A revision with the ID `$revision->getId()` already exists in this repository.");
        
        $revisions = $this->getRevisions();
        
        $revision->setRepository($this->getEntity());
        $this->getEntity()->addRevision($revision);
        
        return $this;
    }

    public function removeRevision($id)
    {
        $revision = $this->getRevision($id);
        
        $id = $revision->getId();
        $this->getRevisions()->removeElement($revision);
        //$revision->setRepository(NULL);
        
        return $this;
    }

    public function hasRevision($id)
    {
        if (! is_numeric($id))
            throw new \Versioning\Exception\InvalidArgumentException(sprintf('Expected int but got %s', gettype($id)));
        
        return $this->getRevisions()->matching(Criteria::create()->where(Criteria::expr()->eq('id', $id)))->count();
    }

    public function getRevision($id)
    {
        if (! is_numeric($id))
            throw new \Versioning\Exception\InvalidArgumentException(sprintf('Expected int but got %s', gettype($id)));
        
        if (! $this->hasRevision($id))
            throw new RevisionNotFoundException("A revision with the ID `$id` does not exist in the repository `$this->identifier`.");
        
        return $this->getRevisions()->matching(Criteria::create()->where(Criteria::expr()->eq('id', $id)))->current();
    }

    public function getRevisions()
    {
        return $this->getEntity()->getRevisions();
    }

    public function getHead()
    {
        return $this->getRevisions()->last();
    }
    
    public function hasHead(){
        return $this->getRevisions()->count();
    }

    public function checkoutRevision($id)
    {
        $revision = $this->getRevision($id);
        $this->getEntity()->setCurrentRevision($revision);
        return $this;
    }

    public function getCurrentRevision()
    {
        if (! $this->hasCurrentRevision())
            throw new RevisionNotFoundException();
        
        return $this->getEntity()->getCurrentRevision();
    }

    public function hasCurrentRevision()
    {
        return $this->getEntity()->hasCurrentRevision();
    }

    public function isUnrevised()
    {
        return ($this->hasCurrentRevision() && $this->getCurrentRevision() !== $this->getHead()) || (! $this->hasCurrentRevision() && $this->getRevisions()->count() > 0);
    }

    public function mergeRevisions(RevisionInterface $revision, RevisionInterface $base)
    {
        throw new \Exception("Not implemented yet");
    }
}