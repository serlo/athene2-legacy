<?php
/**
 *
 *
 * Athene2 - Advanced Learning Resources Manager
 * http://dev/
 * 
 * @author Aeneas Rekkas (aeneas.rekkas@serlo.org)
 * @license LGPL-3.0
 * @license http://opensource.org/licenses/LGPL-3.0 The GNU Lesser General Public License, version 3.0
 * @link https://github.com/serlo-org/athene2 for the canonical source repository
 * @copyright Copyright (c) 2013 Gesellschaft für freie Bildung e.V. (http://www.open-education.eu/)
 */
namespace Term\Manager;

use Term\Service\TermServiceInterface;
use Term\Exception\TermNotFoundException;
use Language\Service\LanguageServiceInterface;

class TermManager implements TermManagerInterface
{
    use\Common\Traits\ObjectManagerAwareTrait,\Common\Traits\EntityDelegatorTrait,\Common\Traits\InstanceManagerTrait;

    /**
     *
     * @param TermServiceInterface $termService            
     */
    public function addTerm(TermServiceInterface $termService)
    {
        $this->addInstance($termService->getId(), $termService);
        return $this;
    }

    public function getTerm($id)
    {
        if (! is_numeric($id)) {
            throw new \InvalidArgumentException();
        }
        
        if (! $this->hasInstance($id)) {
            $instance = $this->getObjectManager()->find($this->getClassResolver()
                ->resolveClassName('Term\Entity\TermInterface'), $id);
            
            if (! is_object($instance))
                throw new TermNotFoundException($id);
            
            $this->addTerm($this->createInstanceFromEntity($instance));
        }
        
        return $this->getInstance($id);
    }

    public function findTermBySlug($slug, LanguageServiceInterface $language)
    {
        $entity = $this->getObjectManager()
            ->getRepository($this->getClassResolver()
            ->resolveClassName('Term\Entity\TermInterface'))
            ->findOneBy(array(
            'slug' => $slug,
            'language' => $language->getId()
        ));
        
        if (! is_object($entity))
            throw new TermNotFoundException(sprintf('Term %s with Language %s not found', $slug, $language->getId()));
        
        $this->addTerm($this->createInstanceFromEntity($entity));
        return $this->getInstance($entity->getId());
    }

    public function findTermByName($name, LanguageServiceInterface $language)
    {
        $entity = $this->getObjectManager()
            ->getRepository($this->getClassResolver()
            ->resolveClassName('Term\Entity\TermInterface'))
            ->findOneBy(array(
            'name' => $name,
            'language' => $language->getId()
        ));
        
        if (! is_object($entity))
            throw new TermNotFoundException(sprintf('Term %s with Language %s not found', $name, $language->getId()));
        
        $this->addTerm($this->createInstanceFromEntity($entity));
        return $this->getInstance($entity->getId());
    }

    public function createTerm($name, $slug, LanguageServiceInterface $language)
    {
        $entity = $this->getClassResolver()->resolve('Term\Entity\TermInterface');
        $entity->setName($name);
        $entity->setLanguage($language->getEntity());
        $entity->setSlug(($slug ? $slug : strtolower(trim(preg_replace('/[^a-zA-Z0-9]+/', '-', $name), '-'))));
        $this->getObjectManager()->persist($entity);
        return $this;
    }

    protected function createInstanceFromEntity($entity)
    {
        /* @var $instance TermServiceInterface */
        $instance = $this->createInstance('Term\Service\TermServiceInterface');
        $instance->setEntity($entity);
        $instance->setManager($this);
        return $instance;
    }
}