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

use Entity\Exception;
use Language\Model\LanguageModelInterface;
use Entity\Entity\EntityInterface;
use Entity\Options\EntityOptions;

class EntityManager implements EntityManagerInterface
{
    use\Type\TypeManagerAwareTrait,\Common\Traits\ConfigAwareTrait,\Common\Traits\InstanceManagerTrait,\Common\Traits\ObjectManagerAwareTrait,\Uuid\Manager\UuidManagerAwareTrait,\Entity\Plugin\PluginManagerAwareTrait,\Zend\EventManager\EventManagerAwareTrait;

    protected function getDefaultConfig()
    {
        return [
            'types' => []
        ];
    }

    public function getEntity($id)
    {
        $entity = $this->getObjectManager()->find($this->getClassResolver()
            ->resolveClassName('Entity\Entity\EntityInterface'), $id);
        
        if (! is_object($entity)) {
            throw new Exception\EntityNotFoundException(sprintf('Entity "%d" not found.', $id));
        }
        
        return $this->buildEntity($entity);
    }

    public function createEntity($typeName, array $data = array(), LanguageModelInterface $languageService)
    {
        $type = $this->getTypeManager()->findTypeByName($typeName);
        
        if (! is_object($type)) {
            throw new Exception\RuntimeException(sprintf('Type "%s" not found', $typeName));
        }
        
        $entity = $this->getClassResolver()->resolve('Entity\Entity\EntityInterface');
        
        $this->getUuidManager()->injectUuid($entity);
        
        $entity->setLanguage($languageService->getEntity());
        $entity->setType($type);
        
        $this->getObjectManager()->persist($entity);
        
        return $this->buildEntity($entity);
    }

    public function getOptions(EntityInterface $entity)
    {
        $typeName = $entity->getType()->getName();
        if (! array_key_exists($typeName, $this->getOption('types'))) {
            throw new Exception\RuntimeException(sprintf('Type "%s" not found in configuration.', $entity->getType()->getName()));
        }
        
        return new EntityOptions($this->getOption('types')[$typeName]);
    }

    public function flush()
    {
        $this->getObjectManager()->flush();
        return $this;
    }

    protected function buildEntity(EntityInterface $entity)
    {
        $config = $this->getOptions($entity);
        $entity->setOptions($config);
        return $entity;
    }
}