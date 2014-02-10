<?php
/**
 * Created by PhpStorm.
 * User: mrnice
 * Date: 15.01.14
 * Time: 01:37
 */
namespace Instance\Factory;

use ClassResolver\ClassResolverFactoryTrait;
use Common\Factory\EntityManagerFactoryTrait;
use Instance\Manager\InstanceManager;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class InstanceManagerFactory implements FactoryInterface
{
    use EntityManagerFactoryTrait;
    use ClassResolverFactoryTrait;

    /**
     * Create service
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @return mixed
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $instance      = new InstanceManager();
        $objectManager = $this->getEntityManager($serviceLocator);
        $classResolver = $this->getClassResolver($serviceLocator);

        $instance->setObjectManager($objectManager);
        $instance->setClassResolver($classResolver);

        return $instance;
    }

} 