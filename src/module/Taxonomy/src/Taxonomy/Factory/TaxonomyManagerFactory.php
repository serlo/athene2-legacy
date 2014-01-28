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
namespace Taxonomy\Factory;

use ClassResolver\ClassResolverFactoryTrait;
use Common\Factory\EntityManagerFactoryTrait;
use Taxonomy\Hydrator\TaxonomyTermHydrator;
use Taxonomy\Manager\TaxonomyManager;
use Taxonomy\Options\ModuleOptions;
use Type\Factory\TypeManagerFactoryTrait;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;


class TaxonomyManagerFactory implements FactoryInterface
{
    use ClassResolverFactoryTrait, EntityManagerFactoryTrait;
    use TypeManagerFactoryTrait;

    /**
     * Create service
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @return mixed
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        /* @var $moduleOptions ModuleOptions */
        /* @var $hydrator TaxonomyTermHydrator */
        $classResolver = $this->getClassResolver($serviceLocator);
        $objectManager = $this->getEntityManager($serviceLocator);
        $typeManager   = $this->getTypeManager($serviceLocator);
        $moduleOptions = $serviceLocator->get('Taxonomy\Options\ModuleOptions');
        $hydrator      = $serviceLocator->get('Taxonomy\Hydrator\TaxonomyTermHydrator');
        $service       = new TaxonomyManager($classResolver, $moduleOptions, $objectManager, $hydrator, $typeManager);

        return $service;
    }
}
