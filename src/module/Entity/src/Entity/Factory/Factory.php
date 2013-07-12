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

use Zend\ServiceManager\ServiceLocatorInterface;
use Entity\Service\EntityServiceInterface;
use Entity\EntityManagerInterface;
use Entity\Entity\EntityInterface;

interface Factory
{
    /**
     * Builds an EntityService
     * 
     * @param EntityServiceInterface $entityService
     * @param EntityInterface $entity
     * @param ServiceLocatorInterface $serviceLocator
     * @param EntityManagerInterface $entityManager
     */
    public function build(EntityServiceInterface $entityService, EntityInterface $entity, ServiceLocatorInterface $serviceLocator, EntityManagerInterface $entityManager);
}