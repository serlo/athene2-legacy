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
namespace Contexter;

use DoctrineModule\Persistence\ObjectManagerAwareInterface;
use ClassResolver\ClassResolverAwareInterface;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Contexter\Entity;
use Contexter\Exception;
use Contexter\Router;
use Contexter\Entity\ContextInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Contexter\Collection\ContextCollection;

class Contexter implements ContexterInterface, ObjectManagerAwareInterface, ClassResolverAwareInterface, ServiceLocatorAwareInterface, Router\RouterAwareInterface
{
    use \Common\Traits\ObjectManagerAwareTrait,\Common\Traits\InstanceManagerTrait, Router\RouterAwareTrait;

    public function getContext($id)
    {
        if (! is_int($id))
            throw new Exception\InvalidArgumentException(sprintf('Expected int but got `%s`', gettype($id)));
        
        if (! $this->hasInstance($id)) {
            $className = $this->getClassResolver()->resolveClassName('Contexter\Entity\ContextInterface');
            $context = $this->getObjectManager()->find($className, $id);
            
            if (! is_object($context))
                throw new Exception\ContextNotFoundException(sprintf('Could not find a context by the id of %d', $id));
            
            $this->addInstance($context->getId(), $this->createService(context));
        }
        
        return $this->getInstance($id);
    }

    public function add(\Uuid\Entity\UuidHolder $object, $type, $title)
    {
        $type = $this->findTypeByName($type);
        
        /* @var $context Entity\ContextInterface */
        $context = $this->getClassResolver()->resolve('Contexter\Entity\ContextInterface');
        $context->setTitle($title);
        $context->setObject($object->getUuidEntity());
        
        $context->setType($type);
        $type->addContext($context);
        
        $this->getObjectManager()->persist($context);
        return $context;
    }

    public function findTypeByName($name)
    {
        $className = $this->getClassResolver()->resolveClassName('Contexter\Entity\TypeInterface');
        
        /* @var $type Entity\TypeInterface */
        $type = $this->getObjectManager()
            ->getRepository($className)
            ->findOneBy(array(
            'name' => $name
        ));
        
        if (! is_object($type)) {
            $type = $this->getClassResolver()->resolve('Contexter\Entity\TypeInterface');
            $type->setName($name);
            $this->getObjectManager()->persist($type);
        }
        
        return $type;
    }

    /*
    public function match($string, $type = NULL)
    {
        $matches = $this->getRouter()->match($string);
        
        $collection = new ArrayCollection();
        
        foreach ($matches as $match) {
            // @var $match Entity\ContextInterface
            if ($type !== NULL) {
                $type = $this->findTypeByName($type);
                if ($match->getType() === $type) {
                    $collection->add($match);
                }
            } else {
                $collection->add($match);
            }
        }
        
        return new ContextCollection($collection, $this);
    }
    */
    
    /*
     * (non-PHPdoc) @see \Contexter\ContexterInterface::findAllByType()
     */
    public function findAllByType($name)
    {
        $type = $this->findTypeByName($name);
        return new ContextCollection($type->getContext(), $this);
    }
    
    /*
     * (non-PHPdoc) @see \Contexter\ContexterInterface::findAll()
     */
    public function findAll()
    {
        $className = $this->getClassResolver()->resolveClassName('Contexter\Entity\ContextInterface');
        $results = $this->getObjectManager()->getRepository($className)->findAll();
        $collection = new ArrayCollection($results);
        return new ContextCollection($collection, $this);
    }

    public function createService(Entity\ContextInterface $context)
    {
        /* @var $instance ContextInterface */
        $instance = $this->createInstance('Contexter\ContextInterface');
        return $instance;
    }
}