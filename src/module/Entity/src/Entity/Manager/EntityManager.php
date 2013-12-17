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
    use\Type\TypeManagerAwareTrait,\Common\Traits\ObjectManagerAwareTrait,\Uuid\Manager\UuidManagerAwareTrait;

    public function getEntity($id)
    {
        $entity = $this->getObjectManager()->find($this->getClassResolver()
            ->resolveClassName('Entity\Entity\EntityInterface'), $id);
        
        if (! is_object($entity)) {
            throw new Exception\EntityNotFoundException(sprintf('Entity "%d" not found.', $id));
        }
        
        return $entity;
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
        
        return $entity;
    }

    public function flush()
    {
        $this->getObjectManager()->flush();
        return $this;
    }
}