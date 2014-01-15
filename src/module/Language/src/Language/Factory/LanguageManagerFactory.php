<?php
/**
 * Created by PhpStorm.
 * User: mrnice
 * Date: 15.01.14
 * Time: 01:37
 */
namespace Language\Factory;

use Language\Manager\LanguageManager;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class LanguageManagerFactory implements FactoryInterface
{
    /**
     * Create service
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @return mixed
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $instance = new LanguageManager();
        $objectManager = $serviceLocator->get('EntityManager');
        $classResolver = $serviceLocator->get('ClassResolver\ClassResolver');

        $instance->setObjectManager($objectManager);
        $instance->setClassResolver($classResolver);
        return $instance;
    }

} 