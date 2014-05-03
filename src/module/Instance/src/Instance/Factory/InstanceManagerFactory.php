<?php
/**
 * Created by PhpStorm.
 * User: mrnice
 * Date: 15.01.14
 * Time: 01:37
 */
namespace Instance\Factory;

use ClassResolver\ClassResolverFactoryTrait;
use Common\Factory\AuthorizationServiceFactoryTrait;
use Common\Factory\EntityManagerFactoryTrait;
use Instance\Manager\InstanceManager;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class InstanceManagerFactory implements FactoryInterface
{
    use EntityManagerFactoryTrait;
    use ClassResolverFactoryTrait;
    use AuthorizationServiceFactoryTrait;

    /**
     * Create service
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @return mixed
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $objectManager        = $this->getEntityManager($serviceLocator);
        $classResolver        = $this->getClassResolver($serviceLocator);
        $authorizationService = $this->getAuthorizationService($serviceLocator);
        $instance             = new InstanceManager($authorizationService, $classResolver, $objectManager);

        return $instance;
    }

}
