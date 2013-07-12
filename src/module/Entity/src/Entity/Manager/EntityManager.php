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
namespace Entity\Manager;

use Entity\Entity\EntityInterface;
use Entity\Exception\InvalidArgumentException;
use Entity\Service\EntityServiceInterface;
use Uuid\Manager\UuidManagerAwareInterface;
use DoctrineModule\Persistence\ObjectManagerAwareInterface;

class EntityManager extends AbstractManager implements EntityManagerInterface, UuidManagerAwareInterface, ObjectManagerAwareInterface
{
    use \Common\Traits\ObjectManagerAware, \Uuid\Manager\UuidManagerAware;

    protected $options = array(
        'instances' => array(
            'manages' => 'Entity\Service\EntityService',
            'EntityInterface' => 'Entity\Entity\Entity',
            'EntityFactoryInterface' => 'Entity\Entity\Factory'
        )
    );

    public function __construct()
    {
        parent::__construct($this->options);
    }

    private function getById($id)
    {
        $entity = $this->getObjectManager()->find($this->resolve('EntityInterface'), $id);
        $entityService = $this->createInstanceFromEntity($entity);
        $this->add($entityService);
        return $this;
    }

    private function getByEntity(EntityInterface $entity)
    {
        $entityService = $this->createInstanceFromEntity($entity);
        $this->add($entityService);
        return $this;
    }

    public function get($id)
    {
        if (is_numeric($id)) {} elseif ($id instanceof EntityInterface) {
            $id = $id->getId();
        } else {
            throw new InvalidArgumentException();
        }
        if (! $this->hasInstance($id)) {
            $this->getById($id);
        }
        return $this->getInstance($id);
    }

    public function create($factoryClass)
    {
        $em = $this->getObjectManager();
        $factory = $em->getRepository($this->resolve('EntityFactoryInterface'))
            ->findOneByClassName($factoryClass);
        
        if (! is_object($factory))
            throw new \Exception("Factory `{$factoryClass}` not found.");
        
        $class = $this->resolve('EntityInterface');
        
        $entity = $this->getUuidManager()->factory($class);
        
        $entity->populate(array(
            'language' => $this->getServiceManager()
                ->get('Core\Service\LanguageManager')
                ->getRequestLanguage()
                ->getEntity(),
            'factory' => $factory
        ));
        $entity->setFactory($factory);
        echo $entity->getId();
        $em->persist($entity);
        $em->flush();
        
        return $this->get($entity->getId());
    }

    public function add(EntityServiceInterface $entityService)
    {
        return $this->addInstance($entityService->getId(), $entityService);
    }

    public function createInstanceFromEntity($entity)
    {
        $instance = parent::createInstance();
        
        $factory = $entity->getFactory()->getName();
        if(class_exists($factory))
            throw new InvalidArgumentException('Class `'.$factory.'` not found.');
            
        $factory = new $factory();
        return $factory->createService($instance, $entity, $this->getServiceManager(), $this);
    }

    public function delete(EntityServiceInterface $entityService)
    {
        $entityService->trash();
        $entityService->persistAndFlush();
        return $this;
    }
}