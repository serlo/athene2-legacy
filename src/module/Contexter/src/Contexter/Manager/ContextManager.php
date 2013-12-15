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

use Contexter\Entity;
use Contexter\Exception;
use Contexter\Router;
use Doctrine\Common\Collections\ArrayCollection;
use Contexter\Collection\ContextCollection;

class ContextManager implements ContextManagerInterface
{
    use\Common\Traits\ObjectManagerAwareTrait,\Common\Traits\InstanceManagerTrait, Router\RouterAwareTrait;

    public function getContext($id)
    {
        if (! is_numeric($id)) {
            throw new Exception\InvalidArgumentException(sprintf('Expected id to be numeric but got "%s"', gettype($id)));
        }
        
        if (! $this->hasInstance($id)) {
            $className = $this->getClassResolver()->resolveClassName('Contexter\Entity\ContextInterface');
            $context = $this->getObjectManager()->find($className, $id);
            
            if (! is_object($context)) {
                throw new Exception\ContextNotFoundException(sprintf('Could not find a context by the id of %d', $id));
            }
            $this->addInstance($context->getId(), $this->createService($context));
        }
        
        return $this->getInstance($id);
    }

    public function getRoute($id)
    {
        if (! is_numeric($id)) {
            throw new Exception\InvalidArgumentException(sprintf('Expected id to be numeric but got "%s"', gettype($id)));
        }
        
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
        $this->getObjectManager()->remove($context->getEntity());
        $this->removeInstance($id);
        return $this;
    }

    public function add($object, $type, $title)
    {
        $object = $this->getUuidManager()->getUuid($object);
        
        $type = $this->findTypeByName($type);
        
        /* @var $context Entity\ContextInterface */
        $context = $this->getClassResolver()->resolve('Contexter\Entity\ContextInterface');
        $context->setTitle($title);
        $context->setObject($object);
        
        $context->setType($type);
        $type->addContext($context);
        
        $this->getObjectManager()->persist($context);
        return $this->createService($context);
    }

    public function findTypeByName($name, $createOnFallback = false)
    {
        $className = $this->getClassResolver()->resolveClassName('Contexter\Entity\TypeInterface');
        
        /* @var $type Entity\TypeInterface */
        $type = $this->getTypeRepository()->findOneBy(array(
            'name' => $name
        ));
        
        if (! is_object($type) && $createOnFallback) {
            $type = $this->getClassResolver()->resolve('Contexter\Entity\TypeInterface');
            $type->setName($name);
            $this->getObjectManager()->persist($type);
        } elseif (! is_object($type) && ! $createOnFallback) {
            throw new Exception\RuntimeException(sprintf('Type `%s` not found', $name));
        }
        
        return $type;
    }

    public function findAll()
    {
        $className = $this->getClassResolver()->resolveClassName('Contexter\Entity\ContextInterface');
        $results = $this->getObjectManager()
            ->getRepository($className)
            ->findAll();
        $collection = new ArrayCollection($results);
        return new ContextCollection($collection, $this);
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

    protected function getTypeClassName()
    {
        return $this->getClassResolver()->resolveClassName('Contexter\Entity\TypeInterface');
    }

    protected function getTypeRepository()
    {
        $className = $this->getTypeClassName();
        return $this->getObjectManager()->getRepository($className);
    }

    protected function createService(Entity\ContextInterface $context)
    {
        /* @var $instance ContextInterface */
        $instance = $this->createInstance('Contexter\ContextInterface');
        $instance->setEntity($context);
        return $instance;
    }

    protected function findAllTypes()
    {
        $className = $this->getClassResolver()->resolveClassName('Contexter\Entity\TypeInterface');
        return new ArrayCollection($this->getObjectManager()
            ->getRepository($className)
            ->findAll());
    }
}