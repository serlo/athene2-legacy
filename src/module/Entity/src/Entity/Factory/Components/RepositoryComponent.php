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
        $this->getRepository()->getCurrentRevision();
    }

    public function createRevision (array $data)
    {
        $repository = $this->getRepository();
        $revision = $this->getEntity()->newRevision();
        
        foreach ($data as $key => $value) {
            $valueEntity = $revision->newValue($key);
            $valueEntity->set('value', $value);
            $this->persist($valueEntity);
        }

        $this->flush();
        $repository->addRevision($revision);
    }
}