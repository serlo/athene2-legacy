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
namespace Type;

use ClassResolver\ClassResolverAwareTrait;
use ClassResolver\ClassResolverInterface;
use Common\Traits\ObjectManagerAwareTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Persistence\ObjectManager;

class TypeManager implements TypeManagerInterface
{
    use ObjectManagerAwareTrait, ClassResolverAwareTrait;

    public function __construct(ClassResolverInterface $classResolver, ObjectManager $objectManager)
    {
        $this->classResolver = $classResolver;
        $this->objectManager = $objectManager;
    }

    public function getType($id)
    {
        $type = $this->getObjectManager()->find($this->getEntityClassName(), $id);
        if (!is_object($type)) {
            throw new Exception\TypeNotFoundException(sprintf('Type "%d" not found.', $id));
        }

        return $type;
    }

    public function findAllTypes()
    {
        $repository = $this->getObjectManager()->getRepository($this->getEntityClassName());

        return new ArrayCollection($repository->findAll());
    }

    public function findTypeByName($name)
    {
        $repository = $this->getObjectManager()->getRepository($this->getEntityClassName());
        $type       = $repository->findOneBy(
            array(
                'name' => $name
            )
        );

        if (!is_object($type)) {
            throw new Exception\TypeNotFoundException(sprintf('Type "%d" not found.', $name));
        }

        return $type;
    }

    protected function getEntityClassName()
    {
        return $this->getClassResolver()->resolveClassName('Type\Entity\TypeInterface');
    }
}