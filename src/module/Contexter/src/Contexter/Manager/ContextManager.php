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
namespace Contexter\Manager;

use Contexter\Exception;
use Contexter\Router;
use Doctrine\Common\Collections\ArrayCollection;
use Contexter\Entity\ContextInterface;

class ContextManager implements ContextManagerInterface
{
    use \Common\Traits\ObjectManagerAwareTrait,\Common\Traits\InstanceManagerTrait, Router\RouterAwareTrait,\Uuid\Manager\UuidManagerAwareTrait,\Type\TypeManagerAwareTrait;

    public function addRoute(ContextInterface $context, $routeName, array $params = array())
    {
        /* @var $route Entity\RouteInterface */
        $route = $this->getClassResolver()->resolve('Contexter\Entity\RouteInterface');
        $route->setName($routeName);
        $route->addParameters($params);
        $route->setContext($context);
        $context->addRoute($route);
        $this->getObjectManager()->persist($route);
        return $route;
    }

    public function getContext($id)
    {
        $className = $this->getClassResolver()->resolveClassName('Contexter\Entity\ContextInterface');
        $context = $this->getObjectManager()->find($className, $id);
        
        if (! is_object($context)) {
            throw new Exception\ContextNotFoundException(sprintf('Could not find a context by the id of %d', $id));
        }
        
        return $context;
    }

    public function getRoute($id)
    {
        $className = $this->getClassResolver()->resolveClassName('Contexter\Entity\RouteInterface');
        $object = $this->getObjectManager()->find($className, $id);
        if (! is_object($object)) {
            throw new Exception\RuntimeException(sprintf('Could not find a route by the id of %d', $id));
        }
        
        return $object;
    }

    public function removeRoute($id)
    {
        $route = $this->getRoute($id);
        $this->getObjectManager()->remove($route);
        return $this;
    }

    public function removeContext($id)
    {
        $context = $this->getContext($id);
        $this->getObjectManager()->remove($context);
        $this->removeInstance($id);
        return $this;
    }

    public function add($objectId, $type, $title)
    {
        $object = $this->getUuidManager()->getUuid($objectId);
        
        $type = $this->findTypeByName($type);
        
        /* @var $context Entity\ContextInterface */
        $context = $this->getClassResolver()->resolve('Contexter\Entity\ContextInterface');
        $context->setTitle($title);
        $context->setObject($object);
        
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
        $results = $this->getObjectManager()
            ->getRepository($className)
            ->findAll();
        return new ArrayCollection($results);
    }

    public function findAllTypeNames()
    {
        return $this->findAllTypes()->map(function (\Contexter\Entity\TypeInterface $e)
        {
            return $e->getName();
        });
    }

    public function flush()
    {
        $this->getObjectManager()->flush();
        return $this;
    }

    protected function getTypeRepository()
    {
        $className = $this->getClassResolver()->resolveClassName('Contexter\Entity\TypeInterface');
        return $this->getObjectManager()->getRepository($className);
    }

    protected function findAllTypes()
    {
        return $this->getTypeManager()->findAllTypes();
    }
}