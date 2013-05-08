<?php
namespace Entity\Factory\Components;

use Entity\Factory\Components\ComponentInterface;
use Entity\Factory\EntityServiceProxy;
use Versioning\Service\RepositoryServiceInterface;
use Versioning\Exception\RevisionNotFoundException;
use Doctrine\Common\Collections\Criteria;

class RepositoryComponent extends EntityServiceProxy implements ComponentInterface
{

    public function build()
    {
        $entityService = $this->getSource();
        $repository = $entityService->getEntity();
        $repository = $this->getRepositoryManager()->addRepository('Entity(' . $entityService->getId() . ')', $repository);
        $entityService->addComponent('repository', $repository);
        return $this;
    }

    /**
     *
     * @return RepositoryServiceInterface
     */
    public function getRepository()
    {
        return $this->getComponent('repository');
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
        return $this;
    }

    public function commitRevision(array $data)
    {
        $repository = $this->getRepository();
        $revision = $this->getEntity()->addNewRevision();
        $revision->setAuthor($this->getAuthService()
            ->getUser());
        $repository->addRevision($revision);
        $this->getEntityManager()->persist($revision);
        foreach ($data as $key => $value) {
            $this->getEntityManager()->persist($revision->addValue($key, $value));
        }
        $this->getEntityManager()->flush();
        return $this;
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
        return $this;
    }

    public function trashRevision($revisionId)
    {
        $revision = $this->getRepository()->getRevision($revisionId);
        $revision->toggleTrashed();
        $this->getEntityManager()->persist($revision);
        $this->getEntityManager()->flush($revision);
        return $this;
    }
}