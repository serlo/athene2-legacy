<?php
/**
 * Athene2 - Advanced Learning Resources Manager
 *
 * @author    Aeneas Rekkas (aeneas.rekkas@serlo.org)
 * @license   LGPL-3.0
 * @license   http://opensource.org/licenses/LGPL-3.0 The GNU Lesser General Public License, version 3.0
 * @link      https://github.com/serlo-org/athene2 for the canonical source repository
 * @copyright Copyright (c) 2013 Gesellschaft für freie Bildung e.V. (http://www.open-education.eu/)
 */
namespace RelatedContent\Factory;

use RelatedContent\Manager\RelatedContentManager;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class RelatedContentManagerFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $instance             = new RelatedContentManager();
        $classResolver        = $serviceLocator->get('ClassResolver\ClassResolver');
        $authorizationService = $serviceLocator->get('ZfcRbac\Service\AuthorizationService');
        $uuidManager          = $serviceLocator->get('Uuid\Manager\UuidManager');
        $objectManager        = $serviceLocator->get('Doctrine\ORM\EntityManager');
        $router               = $serviceLocator->get('router');

        $instance->setClassResolver($classResolver);
        $instance->setUuidManager($uuidManager);
        $instance->setObjectManager($objectManager);
        $instance->setRouter($router);
        $instance->setAuthorizationService($authorizationService);

        return $instance;
    }
}
