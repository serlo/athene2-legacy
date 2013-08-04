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
namespace LearningResource\Plugin\Repository;

use Doctrine\Common\Collections\Criteria;
use Entity\Plugin\AbstractPlugin;

class RepositoryPlugin extends AbstractPlugin
{
    use \Common\Traits\ObjectManagerAwareTrait, \Versioning\RepositoryManagerAwareTrait;

    /**
     *
     * @return RepositoryServiceInterface
     */
    public function getRepository()
    {
        $repository = $this->getEntityService()->getEntity();
        return $this->getRepositoryManager()->addRepository($repository)->getRepository($repository);
    }

    /**
     * (non-PHPdoc)
     * 
     * @see \Versioning\Service\RepositoryServiceInterface::getCurrentRevision()
     */
    public function getCurrentRevision()
    {
        return $this->getRepository()->getCurrentRevision();
    }

    /**
     * (non-PHPdoc)
     * 
     * @see \Versioning\Service\RepositoryServiceInterface::getRevision()
     */
    public function getRevision($id)
    {
        return $this->getRepository()->getRevision($id);
    }

    public function getAllRevisions()
    {
        $criteria = Criteria::create()->where(Criteria::expr()->eq("trashed", false))
            ->orderBy(array(
            "id" => "desc"
        ));
        return $this->getRepository()
            ->getRevisions()
            ->matching($criteria);
    }

    public function getTrashedRevisions()
    {
        $criteria = Criteria::create()->where(Criteria::expr()->eq("trashed", true))
            ->orderBy(array(
            "id" => "desc"
        ));
        return $this->getRepository()
            ->getRevisions()
            ->matching($criteria);
    }

    /**
     * (non-PHPdoc)
     * 
     * @see \Versioning\Service\RepositoryServiceInterface::checkoutRevision()
     */
    public function checkout($revisionId)
    {
        $revision = $this->getRepository()->getRevision($revisionId);
        $this->getRepository()->checkoutRevision($revision);
        return $this->entityService;
    }

    public function commitRevision(array $data)
    {
        $repository = $this->getRepository();
        $revision = $this->entityService->getEntity()->addNewRevision();
        $revision->setAuthor($this->entityService->getAuthService()
            ->getUser());
        $repository->addRevision($revision);
        $this->entityService->getObjectManager()->persist($revision);
        foreach ($data as $key => $value) {
            $this->entityService->getObjectManager()->persist($revision->addValue($key, $value));
        }
        $this->entityService->getObjectManager()->flush();
        return $this->entityService;
    }

    /**
     * (non-PHPdoc)
     * 
     * @see \Versioning\Service\RepositoryServiceInterface::removeRevision()
     */
    public function removeRevision($revisionId)
    {
        $revision = $this->getRepository()->getRevision($revisionId);
        $this->getRepository()->removeRevision($revision);
        return $this->entityService;
    }

    public function trashRevision($revisionId)
    {
        $revision = $this->getRepository()->getRevision($revisionId);
        $revision->toggleTrashed();
        $this->getObjectManager()->persist($revision);
        $this->getObjectManager()->flush($revision);
        return $this->entityService;
    }
    
    public function isCheckedOut(){
        try{
            $this->getCurrentRevision();
            return true;
        } catch (\Versioning\Exception\RevisionNotFoundException $e) {
            return null;
        }
    }
}