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
namespace PageTest\Entity;

use Page\Entity\PageRepositoryInterface;
use User\Entity\User;
use Doctrine\Common\Collections\ArrayCollection;
use Page\Entity\PageRevisionInterface;

/**
 * @codeCoverageIgnore
 */
class RepositoryFake implements PageRepositoryInterface
{

    protected $id, $revisions, $currentRevision;

    public function __construct()
    {
        $this->revisions = new ArrayCollection();
    }
    
    /*
     * (non-PHPdoc) @see \Versioning\Entity\RepositoryInterface::getRevisions()
     */
    public function getRevisions()
    {
        return $this->revisions;
    }
    
    /*
     * (non-PHPdoc) @see \Versioning\Entity\RepositoryInterface::newRevision()
     */
    public function newRevision()
    {
        $revision = new RevisionFake();
        $revision->setRepository($this);
        $this->revisions->add($revision);
        return $revision;
    }
    
    /*
     * (non-PHPdoc) @see \Core\Entity\ModelInterface::getId()
     */
    public function getId()
    {
        return $this->id;
    }
    
    /*
     * (non-PHPdoc) @see \Versioning\Entity\RepositoryInterface::setId()
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     *
     * @return field_type $currentRevision
     */
    public function getCurrentRevision()
    {
        return $this->currentRevision;
    }

    /**
     *
     * @param multitype: $revisions            
     * @return self
     */
    public function setRevisions($revisions)
    {
        $this->revisions = $revisions;
        return $this;
    }

    /**
     *
     * @param field_type $currentRevision            
     * @return self
     */
    public function setCurrentRevision(PageRevisionInterface $currentRevision)
    {
        $this->currentRevision = $currentRevision;
        return $this;
    }
    
    /*
     * (non-PHPdoc) @see \Versioning\Entity\RepositoryInterface::hasCurrentRevision()
     */
    public function hasCurrentRevision()
    {
        return $this->getCurrentRevision() !== NULL;
    }

    public function addRevision(PageRevisionInterface $revision)
    {
        $this->revisions->set($revision->getId(), $revision);
        return $this;
    }
	/* (non-PHPdoc)
     * @see \Versioning\Entity\RepositoryInterface::removeRevision()
     */
    public function removeRevision (\Page\Entity\PageRevisionInterface $revision)
    {
        $this->revisions->removeElement($revision);
    }

}