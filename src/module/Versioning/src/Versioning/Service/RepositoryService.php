<?php
/**
 * Athene2 - Advanced Learning Resources Manager
 *
 * @author      Aeneas Rekkas (aeneas.rekkas@serlo.org)
 * @license     LGPL-3.0
 * @license     http://opensource.org/licenses/LGPL-3.0 The GNU Lesser General Public License, version 3.0
 * @link        https://github.com/serlo-org/athene2 for the canonical source repository
 */
namespace Versioning\Service;

use Authorization\Service\AuthorizationAssertionTrait;
use Common\Traits\ObjectManagerAwareTrait;
use User\Manager\UserManagerAwareTrait;
use Uuid\Manager\UuidManagerAwareTrait;
use Versioning\Entity\RepositoryInterface;
use Versioning\Entity\RevisionInterface;
use Versioning\Exception;
use Versioning\Options\ModuleOptions;
use Versioning\RepositoryManagerAwareTrait;

class RepositoryService implements RepositoryServiceInterface
{
    use ObjectManagerAwareTrait;
    use AuthorizationAssertionTrait, RepositoryManagerAwareTrait;
    use UserManagerAwareTrait;

    /**
     * @var ModuleOptions
     */
    protected $moduleOptions;

    /**
     * @var RepositoryInterface
     */
    protected $repository;

    /**
     * @param ModuleOptions $moduleOptions
     * @return void
     */
    public function setModuleOptions(ModuleOptions $moduleOptions)
    {
        $this->moduleOptions = $moduleOptions;
    }

    /**
     * @return ModuleOptions
     */
    public function getModuleOptions()
    {
        return $this->moduleOptions;
    }

    /**
     * @param RepositoryInterface $repository
     * @return void
     */
    public function setRepository(RepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @return RepositoryInterface
     */
    public function getRepository()
    {
        return $this->repository;
    }

    /**
     * {@inheritDoc}
     */
    public function findRevision($id)
    {
        foreach ($this->getRepository()->getRevisions() as $revision) {
            if ($revision->getId() == $id) {
                return $revision;
            }
        }

        throw new Exception\RevisionNotFoundException(sprintf('Revision "%d" not found', $id));
    }

    /**
     * {@inheritDoc}
     */
    public function commitRevision(array $data)
    {
        $user       = $this->getAuthorizationService()->getIdentity();
        $repository = $this->getRepository();
        $permission = $this->getModuleOptions()->getPermission($repository, 'commit');
        $revision   = $repository->createRevision();

        $this->assertGranted($permission, $repository);
        $revision->setAuthor($user);
        $repository->addRevision($revision);
        $revision->setRepository($repository);

        foreach ($data as $key => $value) {
            if (is_string($key) && is_string($value)) {
                $revision->set($key, $value);
            }
        }

        $this->getRepositoryManager()->getEventManager()->trigger(
            'commit',
            $this,
            [
                'repository' => $this->getRepository(),
                'revision'   => $revision,
                'data'       => $data,
                'author'     => $user
            ]
        );

        $this->getObjectManager()->persist($revision);

        return $revision;
    }

    /**
     * {@inheritDoc}
     */
    public function checkoutRevision($revision, $reason = '')
    {
        if (!$revision instanceof RevisionInterface) {
            $revision = $this->findRevision($revision);
        }

        $user       = $this->getAuthorizationService()->getIdentity();
        $repository = $this->getRepository();
        $permission = $this->getModuleOptions()->getPermission($repository, 'checkout');
        $this->assertGranted($permission, $repository);
        $this->getRepository()->setCurrentRevision($revision);

        $this->getRepositoryManager()->getEventManager()->trigger(
            'checkout',
            $this,
            [
                'repository' => $this->getRepository(),
                'revision'   => $revision,
                'actor'      => $user,
                'reason'     => $reason
            ]
        );

        $this->getObjectManager()->persist($this->getRepository());
    }

    public function rejectRevision($revision, $reason = '')
    {
        if (!$revision instanceof RevisionInterface) {
            $revision = $this->findRevision($revision);
        }

        $user       = $this->getAuthorizationService()->getIdentity();
        $repository = $this->getRepository();
        $permission = $this->getModuleOptions()->getPermission($repository, 'reject');

        $this->assertGranted($permission, $repository);
        $revision->setTrashed(true);
        $this->objectManager->persist($revision);
        $this->getRepositoryManager()->getEventManager()->trigger(
            'reject',
            $this,
            [
                'repository' => $this->getRepository(),
                'revision'   => $revision,
                'actor'      => $user,
                'reason'     => $reason
            ]
        );
    }
}
