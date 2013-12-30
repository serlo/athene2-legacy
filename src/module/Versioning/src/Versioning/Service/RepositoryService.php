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

use Versioning\Entity\RepositoryInterface;
use User\Entity\UserInterface;
use Versioning\Exception;
use Uuid\Entity\UuidHolder;

class RepositoryService implements RepositoryServiceInterface
{
    use \Common\Traits\ObjectManagerAwareTrait,\Uuid\Manager\UuidManagerAwareTrait,\Versioning\RepositoryManagerAwareTrait;

    /**
     *
     * @var RepositoryInterface
     */
    protected $repository;

    public function getRepository()
    {
        return $this->repository;
    }

    public function setRepository(RepositoryInterface $repository)
    {
        $this->repository = $repository;
        return $this;
    }

    public function findRevision($id)
    {
        foreach ($this->getRepository()->getRevisions() as $revision) {
            if ($revision->getId() == $id) {
                return $revision;
            }
        }
        
        throw new Exception\RevisionNotFoundException(sprintf('Revision "%d" not found', $id));
    }

    public function commitRevision(array $data, UserInterface $user)
    {
        $repository = $this->getRepository();
        $revision = $repository->createRevision();
        
        $this->getUuidManager()->injectUuid($revision);
        
        $revision->setAuthor($user);
        
        $repository->addRevision($revision);
        
        foreach ($data as $key => $value) {
            if (is_string($key) && is_string($value)) {
                $revision->set($key, $value);
            }
        }
        
        $this->getRepositoryManager()->getEventManager()->trigger('commit', $this, [
            'repository' => $this->getRepository(),
            'revision' => $revision,
            'data' => $data
        ]);
        
        $this->getObjectManager()->persist($revision);
        
        return $revision;
    }

    public function checkoutRevision($id)
    {
        $revision = $this->findRevision($id);
        $this->getRepository()->setCurrentRevision($revision);
        
        $this->getRepositoryManager()->getEventManager()->trigger('checkout', $this, [
            'repository' => $this->getRepository(),
            'revision' => $revision
        ]);
        
        $this->getObjectManager()->persist($this->getRepository());
        return $this;
    }
}