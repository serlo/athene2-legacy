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
    use \Common\Traits\ObjectManagerAwareTrait,\Uuid\Manager\UuidManagerAwareTrait,\Entity\Plugin\PluginManagerAwareTrait;

    private function getById($id)
    {
        $entity = $this->getObjectManager()->find($this->resolveClassName('Entity\Entity\EntityInterface'), $id);

        if(!is_object($entity))
            throw new InvalidArgumentException(sprintf('Entity with ID %s not found.', $id));
        
        $entityService = $this->createInstanceFromEntity($entity);
        $this->add($entityService);
        return $entityService;
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
            return $this->getById($id);
        }
        return $this->getInstance($id);
    }

    public function create($factoryClass)
    {
        $em = $this->getObjectManager();
        $factory = $em->getRepository($this->resolveClassName('Entity\Entity\TypeInterface'))
            ->findOneByClassName($factoryClass);
        
        if (! is_object($factory))
            throw new \Exception("Factory `{$factoryClass}` not found.");
        
        $class = $this->resolveClassName('Entity\Entity\EntityInterface');
        
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
        $instance = parent::createInstance('Entity\Service\EntityServiceInterface');
        $this->inject($instance, $entity);       
        return $instance;
    }

    protected function inject(EntityServiceInterface $entityService, EntityInterface $entity)
    {
        $entityService->setPluginManager($this->getPluginManager());
        $entityService->setEntityManager($this);
        $entityService->setEntity($entity);
        
        if (! array_key_exists($entity->getType()->getName(), $this->config['types'])) {
            throw new InvalidArgumentException(sprintf('Type %s not found in configuration.', $entity->getType()->getName()));
        }
        
        $config = $this->config['types'][$entity->getType()->getName()];


        $entityService->setOptions($config);
        return $this;
    }
    
    /*
     * public function delete(EntityServiceInterface $entityService) { $entityService->trash(); $entityService->persistAndFlush(); return $this; }
     */
}