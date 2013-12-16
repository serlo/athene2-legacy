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

class RepositoryService implements RepositoryServiceInterface
{
    use \Common\Traits\ObjectManagerAwareTrait;

    /**
     *
     * @var RepositoryInterface
     */
    protected $repository;

    public function getRepository()
    {
        return $this->getEntity();
    }

    public function setRepository(RepositoryInterface $repository)
    {
        $this->setEntity($repository);
        return $this;
    }

    public function commitRevision(array $data, UserInterface $user)
    {
        $repository = $this->getRepository();
        $revision = $repository->newRevision();
        
        $this->getUuidManager()->injectUuid($revision);
        $revision->setAuthor($user);
        
        $repository->addRevision($revision);
        
        foreach ($data as $key => $value) {
            if (is_string($key) && is_string($value)) {
                $revision->set($key, $value);
            }
        }
        
        $this->getObjectManager()->persist($revision);
        
        return $revision;
    }

    public function checkoutRevision($id)
    {
        $revision = $this->getRepository()->getRevision($id);
        $this->getRepository()->setCurrentRevision($revision);
        
        $this->getObjectManager()->persist($this->getRepository());
        return $this;
    }
}