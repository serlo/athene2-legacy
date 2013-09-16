<?php
/**
 * 
 * Athene2 - Advanced Learning Resources Manager
 *http://dev/
 * @author	Aeneas Rekkas (aeneas.rekkas@serlo.org)
 * @license	LGPL-3.0
 * @license	http://opensource.org/licenses/LGPL-3.0 The GNU Lesser General Public License, version 3.0
 * @link		https://github.com/serlo-org/athene2 for the canonical source repository
 * @copyright Copyright (c) 2013 Gesellschaft für freie Bildung e.V. (http://www.open-education.eu/)
 */
namespace Term\Manager;

use Term\Service\TermServiceInterface;
use Term\Exception\TermNotFoundException;
use Language\Service\LanguageServiceInterface;

class TermManager implements TermManagerInterface
{
    
    use \Language\Manager\LanguageManagerAwareTrait,\Common\Traits\ObjectManagerAwareTrait,\Common\Traits\EntityDelegatorTrait,\Common\Traits\InstanceManagerTrait;

    /**
     *
     * @param TermServiceInterface $termService            
     */
    public function add (TermServiceInterface $termService)
    {
        $this->addInstance($termService->getId(), $termService);
        return $this;
    }

    public function getTerm ($id)
    {
        if (is_numeric($id)) {} else {
            throw new \InvalidArgumentException();
        }
        
        if (! $this->hasInstance($id)) {
            $instance = $this->getObjectManager()->find($this->getClassResolver()
            ->resolveClassName('Term\Entity\TermEntityInterface'), $id);
            
            if (! is_object($instance))
                throw new TermNotFoundException($id);
            
            $this->add($this->createInstanceFromEntity($instance));
        }
        
        return $this->getInstance($id);
    }

    protected function getById ($id)
    {
        $term = $this->getObjectManager()->find($this->getClassResolver()
            ->resolveClassName('Term\Entity\TermEntityInterface'), $id);
        
        if (! is_object($term))
            throw new TermNotFoundException($id);
        
        if (! $this->hasInstance($term->getId())) {
            $this->add($this->createInstanceFromEntity($term));
        }
        
        return $this->getInstance($term->getId());
    }

    protected function getByService (TermServiceInterface $term)
    {
        if (! $this->hasInstance($term->getId())) {
            $this->add($term);
        }
        return $this->getInstance($term->getId());
    }

    public function findTermByString ($string, LanguageServiceInterface $language)
    {
        $entity = $this->getObjectManager()
            ->getRepository($this->getClassResolver()
            ->resolveClassName('Term\Entity\TermEntityInterface'))
            ->findOneBy(array(
            'name' => $string,
            'language' => $language->getId()
        ));
        if (! is_object($entity)) {
            $entity = $this->getObjectManager()
                ->getRepository($this->getClassResolver()
                ->resolveClassName('Term\Entity\TermEntityInterface'))
                ->findOneBy(array(
                'slug' => $string,
                'language' => $language->getId()
            ));
        }
        
        if (!is_object($entity))
            throw new TermNotFoundException(sprintf('Term %s with Language %s not found', $string, $language->getId()));
            
        $this->add($this->createInstanceFromEntity($entity));
        return $this->getInstance($entity->getId());
    }

    public function createTerm ($name, $slug, LanguageServiceInterface $language)
    {
        $entity = $this->getClassResolver()->resolve('Term\Entity\TermEntityInterface');
        $entity->setName($name);
        $entity->setLanguage($language->getEntity());
        $entity->setSlug(($slug ? $slug : strtolower(trim(preg_replace('/[^a-zA-Z0-9]+/', '-', $name), '-'))));
        
        $this->getObjectManager()->persist($entity);
    }
    
    public function flush(\Term\Entity\TermEntityInterface $entity = NULL){
        $this->getObjectManager()->flush($entity);
    }
    
    public function persist(\Term\Entity\TermEntityInterface $entity){
        $this->getObjectManager()->persist($entity);
    }

    protected function createInstanceFromEntity ($entity)
    {
        $instance = $this->createInstance('Term\Service\TermServiceInterface');
        $instance->setEntity($entity);
        return $instance;
    }
}