<?php
/**
 * Athene2 - Advanced Learning Resources Manager
 *
 * @author    Aeneas Rekkas (aeneas.rekkas@serlo.org)
 * @license   LGPL-3.0
 * @license   http://opensource.org/licenses/LGPL-3.0 The GNU Lesser General Public License, version 3.0
 * @link      https://github.com/serlo-org/athene2 for the canonical source repository
 * @copyright Copyright (c) 2013-2014 Gesellschaft für freie Bildung e.V. (http://www.open-education.eu/)
 */
namespace Alias\Factory;

use Alias\AliasManager;
use Zend\Mvc\Service\RouterFactory;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class AliasManagerFactory implements FactoryInterface
{
    /**
     * Create service
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @return mixed
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $aliasManager  = new AliasManager();
        $options       = $serviceLocator->get('Alias\Options\ManagerOptions');
        $objectManager = $serviceLocator->get('Doctrine\ORM\EntityManager');
        $uuidManager   = $serviceLocator->get('Uuid\Manager\UuidManager');
        $tokenizer     = $serviceLocator->get('Token\Tokenizer');
        $classResolver = $serviceLocator->get('ClassResolver\ClassResolver');
        $router        = (new RouterFactory())->createService($serviceLocator);

        $aliasManager->setOptions($options);
        $aliasManager->setObjectManager($objectManager);
        $aliasManager->setUuidManager($uuidManager);
        $aliasManager->setTokenizer($tokenizer);
        $aliasManager->setClassResolver($classResolver);
        $aliasManager->setRouter($router);

        return $aliasManager;
    }
}
 