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
use Entity\Exception;

class EntityManager implements EntityManagerInterface
{
    use\Common\Traits\ConfigAwareTrait,\Common\Traits\InstanceManagerTrait,\Common\Traits\ObjectManagerAwareTrait,\Uuid\Manager\UuidManagerAwareTrait,\Entity\Plugin\PluginManagerAwareTrait,\Zend\EventManager\EventManagerAwareTrait,\Language\Manager\LanguageManagerAwareTrait;

    protected function getDefaultConfig()
    {
        return array(
            'types' => array(),
            'plugins' => array()
        );
    }

    public function getEntity($id)
    {
        if (! is_numeric($id))
            throw new Exception\InvalidArgumentException(sprintf('Expected numeric but got %s', gettype($id)));
        
        if (! $this->hasInstance($id)) {
            $entity = $this->getObjectManager()->find($this->getClassResolver()
                ->resolveClassName('Entity\Entity\EntityInterface'), $id);
            
            if (! is_object($entity))
                throw new Exception\EntityNotFoundException(sprintf('Entity with ID %s not found.', $id));
            
            $this->addInstance($entity->getId(), $this->createService($entity));
        }
        
        return $this->getInstance($id);
    }

    public function createEntity($typeName, array $data = array())
    {
        $type = $this->getObjectManager()
            ->getRepository($this->getClassResolver()
            ->resolveClassName('Entity\Entity\TypeInterface'))
            ->findOneByName($typeName);
        
        if (! is_object($type))
            throw new Exception\RuntimeException(sprintf('Type %s not found', $typeName));
        
        $entity = $this->getClassResolver()->resolve('Entity\Entity\EntityInterface');
        
        $this->getUuidManager()->injectUuid($entity);
        
        $entity->setLanguage($this->getLanguageManager()
            ->getLanguageFromRequest()
            ->getEntity());
        $entity->setType($type);
        
        $this->getObjectManager()->persist($entity);
        
        $instance = $this->createService($entity);
        
        return $instance;
    }

    protected function createService(EntityInterface $entity)
    {
        $instance = $this->createInstance('Entity\Service\EntityServiceInterface');
        
        if (! array_key_exists($entity->getType()->getName(), $this->getOption('types')))
            throw new Exception\RuntimeException(sprintf('Type %s not found in configuration.', $entity->getType()->getName()));
        
        if (! array_key_exists('plugins', $this->getOption('types')[$entity->getType()
            ->getName()]))
            throw new Exception\RuntimeException('Must define plugins');
        
        $config = $this->getOption('types')[$entity->getType()
            ->getName()];
        
        $instance->setEventManager($this->getEventManager());
        $instance->setPluginManager($this->getPluginManager());
        $instance->setEntityManager($this);
        $instance->setEntity($entity);
        $instance->setConfig($config);
        
        return $instance;
    }
}