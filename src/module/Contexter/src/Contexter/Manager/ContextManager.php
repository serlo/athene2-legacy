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
namespace Contexter\Manager;

use Authorization\Service\AuthorizationAssertionTrait;
use Common\Traits\FlushableTrait;
use Common\Traits\ObjectManagerAwareTrait;
use Contexter\Entity\ContextInterface;
use Contexter\Exception;
use Contexter\Router;
use Doctrine\Common\Collections\ArrayCollection;
use Instance\Manager\InstanceManagerAwareTrait;
use Type\TypeManagerAwareTrait;
use Uuid\Manager\UuidManagerAwareTrait;

class ContextManager implements ContextManagerInterface
{
    use ObjectManagerAwareTrait, InstanceManagerAwareTrait;
    use Router\RouterAwareTrait, UuidManagerAwareTrait;
    use TypeManagerAwareTrait, AuthorizationAssertionTrait;
    use FlushableTrait;

    public function addRoute(ContextInterface $context, $routeName, array $params = [])
    {
        $this->assertGranted('contexter.route.add', $context);

        /* @var $route Entity\RouteInterface */
        $route = $this->getClassResolver()->resolve('Contexter\Entity\RouteInterface');
        $route->setName($routeName);
        $route->addParameters($params);
        $route->setContext($context);
        $context->addRoute($route);
        $this->getObjectManager()->persist($route);

        return $route;
    }

    public function removeRoute($id)
    {
        $route = $this->getRoute($id);
        $this->assertGranted('contexter.route.remove', $route->getContext());

        $this->getObjectManager()->remove($route);
    }

    public function getRoute($id)
    {
        $className = $this->getClassResolver()->resolveClassName('Contexter\Entity\RouteInterface');
        $object    = $this->getObjectManager()->find($className, $id);
        if (!is_object($object)) {
            throw new Exception\RuntimeException(sprintf('Could not find a route by the id of %d', $id));
        }

        return $object;
    }

    public function removeContext($id)
    {
        $context = $this->getContext($id);
        $this->assertGranted('contexter.context.remove', $context);

        $this->getObjectManager()->remove($context);
        $this->removeInstance($id);
    }

    public function getContext($id)
    {
        $className = $this->getClassResolver()->resolveClassName('Contexter\Entity\ContextInterface');
        $context   = $this->getObjectManager()->find($className, $id);

        if (!is_object($context)) {
            throw new Exception\ContextNotFoundException(sprintf('Could not find a context by the id of %d', $id));
        }

        return $context;
    }

    public function add($objectId, $type, $title)
    {
        $instance = $this->getInstanceManager()->getInstanceFromRequest();
        $this->assertGranted('contexter.context.add', $instance);

        $object = $this->getUuidManager()->getUuid($objectId);

        $type = $this->findTypeByName($type);

        /* @var $context ContextInterface */
        $context = $this->getClassResolver()->resolve('Contexter\Entity\ContextInterface');
        $context->setTitle($title);
        $context->setObject($object);
        $context->setInstance($instance);

        $context->setType($type);
        $type->addContext($context);

        $this->getObjectManager()->persist($context);

        return $context;
    }

    public function findTypeByName($name)
    {
        return $this->getTypeManager()->findTypeByName($name);
    }

    public function findAll()
    {
        $className = $this->getClassResolver()->resolveClassName('Contexter\Entity\ContextInterface');
        $results   = $this->getObjectManager()->getRepository($className)->findAll();

        return new ArrayCollection($results);
    }

    public function findAllTypeNames()
    {
        return $this->findAllTypes()->map(
            function (\Type\Entity\TypeInterface $e) {
                return $e->getName();
            }
        );
    }

    protected function findAllTypes()
    {
        return $this->getTypeManager()->findAllTypes();
    }

    protected function getTypeRepository()
    {
        $className = $this->getClassResolver()->resolveClassName('Contexter\Entity\TypeInterface');

        return $this->getObjectManager()->getRepository($className);
    }
}