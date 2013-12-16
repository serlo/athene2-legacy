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
namespace Versioning;

use Versioning\Entity\RepositoryInterface;

class RepositoryManager implements RepositoryManagerInterface
{
    use\Common\Traits\InstanceManagerTrait;

    public function getRepository(RepositoryInterface $repository)
    {
        $id = $this->getUniqId($repository);
        if (! $this->hasInstance($id)) {
            $this->createService($repository);
        }
        
        return $this->getInstance($id);
    }

    protected function createService(RepositoryInterface $repository)
    {
        $instance = $this->createInstance('Versioning\Service\RepositoryServiceInterface');
        $name = $this->getUniqId($repository);
        
        $instance->setIdentifier($name);
        $instance->setRepository($repository);
        $this->addInstance($name, $instance);
        
        return $this;
    }

    protected function getUniqId(RepositoryInterface $repository)
    {
        return get_class($repository) . '::' . $repository->getId();
    }
}