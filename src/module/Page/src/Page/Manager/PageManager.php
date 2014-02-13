<?php

/**
 * Athene2 - Advanced Learning Resources Manager
 *
 * @author      Jakob Pfab (jakob.pfab@serlo.org)
 * @author      Aeneas Rekkas (aeneas.rekkas@serlo.org)
 * @license     LGPL-3.0
 * @license     http://opensource.org/licenses/LGPL-3.0 The GNU Lesser General Public License, version 3.0
 * @link        https://github.com/serlo-org/athene2 for the canonical source repository
 * @copyright   Copyright (c) 2013 Gesellschaft für freie Bildung e.V. (http://www.open-education.eu/)
 */
namespace Page\Manager;

use Authorization\Service\RoleServiceAwareTrait;
use ClassResolver\ClassResolverAwareTrait;
use Common\Traits\ObjectManagerAwareTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Instance\Entity\InstanceInterface;
use Instance\Manager\InstanceManagerAwareTrait;
use License\Manager\LicenseManagerAwareTrait;
use Page\Entity\Page;
use Page\Entity\PageRepositoryInterface;
use Page\Exception\InvalidArgumentException;
use Page\Exception\PageNotFoundException;
use User\Entity\UserInterface;
use User\Manager\UserManagerAwareTrait;
use Uuid\Manager\UuidManagerAwareTrait;
use Versioning\RepositoryManagerAwareTrait;

class PageManager implements PageManagerInterface
{
    use ObjectManagerAwareTrait, ClassResolverAwareTrait;
    use UuidManagerAwareTrait, InstanceManagerAwareTrait;
    use LicenseManagerAwareTrait, RepositoryManagerAwareTrait;
    use RoleServiceAwareTrait, UserManagerAwareTrait;

    public function editPageRepository(array $data, PageRepositoryInterface $pageRepository)
    {
        $pageRepository->setRoles(new ArrayCollection());
        $this->setRoles($data, $pageRepository);
        $this->getObjectManager()->persist($pageRepository);

        return $pageRepository;
    }

    protected function setRoles(array $data, PageRepositoryInterface $pageRepository)
    {
        for ($i = 0; $i <= $this->countRoles(); $i++) {
            if (array_key_exists($i, $data['roles'])) {
                $role = $this->getRoleService()->getRole($data['roles'][$i]);
                if ($role != null) {
                    $pageRepository->setRole($role);
                }
            }
        }
    }

    protected function countRoles()
    {
        $roles = $this->findAllRoles();

        return count($roles);
    }

    public function findAllRoles()
    {
        return $this->getRoleService()->findAllRoles();
    }

    public function findAllRepositories(InstanceInterface $instance)
    {
        $className    = $this->getClassResolver()->resolveClassName('Page\Entity\PageRepositoryInterface');
        $params       = array('instance' => $instance->getId());
        $repositories = $this->getObjectManager()->getRepository($className)->findBy($params);
        $return       = array();
        foreach ($repositories as $repository) {
            if (!$repository->isTrashed()) {
                $return[] = $repository;
            }
        }

        return $return;
    }

    public function getRevision($id)
    {
        if (!is_numeric($id)) {
            throw new InvalidArgumentException(sprintf('Expected numeric but got %s', gettype($id)));
        }
        $revision = $this->getObjectManager()->find(
            $this->getClassResolver()->resolveClassName('Page\Entity\PageRevisionInterface'),
            $id
        );
        if (!$revision) {
            throw new PageNotFoundException(sprintf('Page Revision %s not found', $id));
        }

        if (!$revision->isTrashed()) {
            return $revision;
        } else {
            throw new PageNotFoundException(sprintf('Page Revision %s is trashed', $id));
        }
    }

    public function getPageRepository($id)
    {

        if (!is_numeric($id)) {
            throw new InvalidArgumentException(sprintf('Expected numeric but got %s', gettype($id)));
        }

        $pageRepository = $this->getObjectManager()->find(
            $this->getClassResolver()->resolveClassName('Page\Entity\PageRepositoryInterface'),
            $id
        );

        if (!is_object($pageRepository)) {
            throw new PageNotFoundException(sprintf('Page Repository "%d" not found.', $id));
        }

        if (!$pageRepository->isTrashed()) {
            return $pageRepository;
        } else {
            throw new PageNotFoundException(sprintf('Page Repository "%d" is trashed.', $id));
        }
    }

    public function createPageRepository(array $data, $instance)
    {
        $pageRepository = $this->createPageRepositoryEntity();
        $pageRepository->populate($data);
        $pageRepository->setInstance($instance);
        $this->setRoles($data, $pageRepository);
        $this->getObjectManager()->persist($pageRepository);

        return $pageRepository;
    }

    public function createRevision(PageRepositoryInterface $repository, array $data, UserInterface $user)
    {
        $repository = $this->getRepositoryManager()->getRepository($repository);
        $revision   = $repository->commitRevision($data);
        $this->getObjectManager()->persist($revision);
        $this->getObjectManager()->persist($revision);
        $this->getObjectManager()->persist($repository->getRepository());
        $repository->checkOutRevision($revision->getId());

        return $repository;
    }

    protected function createPageRepositoryEntity()
    {
        $repository = $this->getClassResolver()->resolve('Page\Entity\PageRepositoryInterface');
        $this->getUuidManager()->injectUuid($repository);
        $license = $this->getLicenseManager()->getLicense(1); // Finds a license with the id 3
        $this->getLicenseManager()->injectLicense($repository, $license);
        $repository->setTrashed(false);

        return $repository;
    }
}