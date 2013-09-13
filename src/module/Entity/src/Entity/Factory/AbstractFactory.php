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
namespace Entity\Factory;

use Entity\EntityManagerInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Entity\Entity\EntityInterface;
use Entity\Service\EntityServiceInterface;

abstract class AbstractFactory implements Factory
{

    /**
     *
     * @var ServiceLocatorInterface
     */
    final private $serviceLocator;

    /**
     *
     * @var ServiceLocatorInterface
     */
    final private $entityService;

    /**
     *
     * @return \Zend\ServiceManager\ServiceLocatorInterface $entityService
     */
    final protected function getEntityService()
    {
        return $this->entityService;
    }

    /**
     *
     * @param \Zend\ServiceManager\ServiceLocatorInterface $entityService            
     * @return $this
     */
    final protected function setEntityService($entityService)
    {
        $this->entityService = $entityService;
        return $this;
    }

    /**
     *
     * @return \Zend\ServiceManager\ServiceLocatorInterface $serviceLocator
     */
    final protected function getServiceLocator()
    {
        return $this->serviceLocator;
    }

    /**
     *
     * @param \Zend\ServiceManager\ServiceLocatorInterface $serviceLocator            
     * @return $this
     */
    final protected function setServiceLocator($serviceLocator)
    {
        $this->serviceLocator = $serviceLocator;
        return $this;
    }

    public function build(EntityServiceInterface $entityService, EntityInterface $entity, ServiceLocatorInterface $serviceLocator, EntityManagerInterface $entityManager)
    {
        $this->setServiceLocator($serviceLocator);
        $this->setEntityService($entityService);
        $entityService->setEntity($entity);
        $entityService->setManager($entityManager);
        return $entityService;
    }
}