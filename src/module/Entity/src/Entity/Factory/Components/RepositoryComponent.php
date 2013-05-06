<?php
namespace Entity\Factory\Components;

use Entity\Factory\Components\ComponentInterface;
use Entity\Factory\EntityServiceProxy;
use Versioning\Service\RepositoryServiceInterface;

class RepositoryComponent extends EntityServiceProxy implements ComponentInterface
{

    public function build ()
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
    public function getRepository ()
    {
        return $this->getComponent('repository');
    }

    public function getCurrentRevision ()
    {
        return $this->getRepository()->getCurrentRevision();
    }

    public function getAllRevisions ()
    {
        return $this->getRepository()->getRevisions();
    }

    public function commitRevision (array $data)
    {
        $repository = $this->getRepository();
        $revision = $this->getEntity()->addRevision();
        $repository->addRevision($revision);
        $this->getEntityManager()->persist($revision);
        
        foreach ($data as $key => $value) {
            $this->getEntityManager()->persist($revision->addValue($key, $value));
        }
        $this->getEntityManager()->flush();
        return $this;
    }
}