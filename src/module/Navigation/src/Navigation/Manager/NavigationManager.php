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
namespace Navigation\Manager;

use ClassResolver\ClassResolverInterface;
use Doctrine\ORM\EntityManager;
use Instance\Manager\InstanceManagerInterface;
use Type\TypeManagerInterface;

class NavigationManager implements NavigationManagerInterface
{
    /**
     * @var EntityManager
     */
    protected $entityManager;

    /**
     * @var TypeManagerInterface
     */
    protected $typeManager;

    /**
     * @var InstanceManagerInterface
     */
    protected $instanceManager;

    /**
     * @var ClassResolverInterface
     */
    protected $classResolver;

    public function __construct(
        ClassResolverInterface $classResolver,
        EntityManager $entityManager,
        TypeManagerInterface $typeManager,
        InstanceManagerInterface $instanceManager
    ) {
        $this->entityManager   = $entityManager;
        $this->typeManager     = $typeManager;
        $this->instanceManager = $instanceManager;
        $this->classResolver   = $classResolver;
    }
}
 