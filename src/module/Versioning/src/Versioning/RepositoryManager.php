<?php
/**
 * Athene2 - Advanced Learning Resources Manager
 *
 * @author      Aeneas Rekkas (aeneas.rekkas@serlo.org)
 * @license     LGPL-3.0
 * @license     http://opensource.org/licenses/LGPL-3.0 The GNU Lesser General Public License, version 3.0
 * @link        https://github.com/serlo-org/athene2 for the canonical source repository
 * @copyright   Copyright (c) 2013 Gesellschaft für freie Bildung e.V. (http://www.open-education.eu/)
 */
namespace Versioning;

use ClassResolver\ClassResolverInterface;
use Common\Traits\InstanceManagerTrait;
use Versioning\Entity\RepositoryInterface;
use Zend\EventManager\EventManagerAwareTrait;
use Zend\ServiceManager\ServiceLocatorInterface;

class RepositoryManager implements RepositoryManagerInterface
{
    use InstanceManagerTrait, EventManagerAwareTrait;

    public function __construct(ClassResolverInterface $classResolver, ServiceLocatorInterface $serviceLocator)
    {
        $this->serviceLocator = $serviceLocator;
        $this->classResolver  = $classResolver;
    }

    public function getRepository(RepositoryInterface $repository)
    {
        $id = $this->getUniqId($repository);

        if (!$this->hasInstance($id)) {
            $this->createService($repository);
        }

        return $this->getInstance($id);
    }

    protected function getUniqId(RepositoryInterface $repository)
    {
        return get_class($repository) . '::' . $repository->getId();
    }

    protected function createService(RepositoryInterface $repository)
    {
        $instance = $this->createInstance('Versioning\Service\RepositoryServiceInterface');
        $name     = $this->getUniqId($repository);

        $instance->setRepository($repository);
        $instance->setRepositoryManager($this);
        $this->addInstance($name, $instance);

        return $this;
    }
}
