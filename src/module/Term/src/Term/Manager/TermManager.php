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
use Common\Filter\Slugify;
use Term\Exception;
use Term\Entity\TermEntityInterface;
use Language\Model\LanguageModelInterface;

class TermManager implements TermManagerInterface
{
    use\Common\Traits\ObjectManagerAwareTrait,\Common\Traits\EntityDelegatorTrait,\Common\Traits\InstanceManagerTrait;

    /**
     *
     * @param TermServiceInterface $termService            
     */
    private function addTerm(TermServiceInterface $termService)
    {
        $this->addInstance($termService->getId(), $termService);
        return $this;
    }

    public function getTerm($idOrObject)
    {
        if (is_numeric($idOrObject)) {
        } elseif ($idOrObject instanceof TermEntityInterface){
            $idOrObject = $idOrObject->getId();
        } else {
            throw new Exception\InvalidArgumentException();
        }
        
        if (! $this->hasInstance($idOrObject)) {
            $instance = $this->getObjectManager()->find($this->getClassResolver()
                ->resolveClassName('Term\Entity\TermEntityInterface'), $idOrObject);
            
            if (! is_object($instance))
                throw new TermNotFoundException($idOrObject);
            
            $this->addTerm($this->createInstanceFromEntity($instance));
        }
        
        return $this->getInstance($idOrObject);
    }

    public function findTermBySlug($slug, LanguageModelInterface $language)
    {
        $entity = $this->getObjectManager()
            ->getRepository($this->getClassResolver()
            ->resolveClassName('Term\Entity\TermEntityInterface'))
            ->findOneBy(array(
            'slug' => $slug,
            'language' => $language->getId()
        ));
        
        if (! is_object($entity))
            throw new TermNotFoundException(sprintf('Term %s with Language %s not found', $slug, $language->getId()));
        
        $this->addTerm($this->createInstanceFromEntity($entity));
        return $this->getInstance($entity->getId());
    }

    public function findTermByName($name, LanguageModelInterface $language)
    {
        $entity = $this->getObjectManager()
            ->getRepository($this->getClassResolver()
            ->resolveClassName('Term\Entity\TermEntityInterface'))
            ->findOneBy(array(
            'name' => $name,
            'language' => $language->getId()
        ));
        
        if (! is_object($entity))
            throw new TermNotFoundException(sprintf('Term %s with Language %s not found', $name, $language->getId()));
        
        $this->addTerm($this->createInstanceFromEntity($entity));
        return $this->getInstance($entity->getId());
    }

    public function createTerm($name, $slug = NULL, LanguageModelInterface $language)
    {
        $filter = new Slugify();
        $entity = $this->getClassResolver()->resolve('Term\Entity\TermEntityInterface');
        $entity->setName($name);
        $entity->setLanguage($language->getEntity());
        $entity->setSlug(($slug ? $slug : $filter->filter($name)));
        $this->getObjectManager()->persist($entity);
        return $entity;
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