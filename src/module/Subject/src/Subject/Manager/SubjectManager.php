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
namespace Subject\Manager;

use Subject\Service\SubjectServiceInterface;
use Taxonomy\Entity\TermTaxonomyEntityInterface;
use Subject\Exception\InvalidArgumentException;
use Core\Service\LanguageService;
use Doctrine\Common\Collections\ArrayCollection;
use Language\Service\LanguageServiceInterface;

class SubjectManager extends AbstractManager implements SubjectManagerInterface
{
    use\Common\Traits\ObjectManagerAwareTrait,\Taxonomy\Manager\SharedTaxonomyManagerAwareTrait,\Subject\Plugin\PluginManagerAwareTrait,\Language\Manager\LanguageManagerAwareTrait;
    
    protected $taxonomyType = 'subject';

    public function add(SubjectServiceInterface $service)
    {
        //$this->names[$service->getName()] = $service->getId();
        $this->addInstance($service->getId(), $service);
        return $this;
    }

    public function get($subject, $language = NULL)
    {
        //$this->injectInstances();
        if (is_numeric($subject)) {
            $subject = $this->getSharedTaxonomyManager()->getTerm((int) $subject);
        } elseif (is_string($subject)) {
            if(!$language)
                $language = $this->getLanguageManager()->getRequestLanguage();
            
            //return $this->getInstance($this->names[$subject]);
            //$subject = $this->getObjectManager()->getRepository($this->resolveClassName('Taxonomy\Entity\TermT'));
            $taxonomy = $this->getSharedTaxonomyManager()->get($this->taxonomyType, $language);
            $subject = $taxonomy->get((string) $subject);
        } else {
            throw new InvalidArgumentException();
        }

        if(!is_object($subject))
            throw new InvalidArgumentException(sprintf('Not Found'));
        
        if(!$this->hasInstance($subject->getId())){
            $this->add($this->createInstanceFromEntity($subject));
        }
        return $this->getInstance($subject->getId());
    }
    
    /*
     * public function getAllSubjects () { $this->injectInstances(); return $this->getInstances(); }
     */
    public function getSubjectsWithLanguage($language)
    {
        if(is_numeric($language)){
            $language = $this->getLanguageManager()->get($language);
        } elseif ($language instanceof LanguageServiceInterface) {
        } else {
            throw new InvalidArgumentException();
        }
        $taxonomy = $this->getSharedTaxonomyManager()->get($this->taxonomyType, $language);
        $collection = new ArrayCollection();
        foreach($taxonomy->getRootTerms() as $subject){
            if(!$this->hasInstance($subject->getId())){
                $this->add($this->createInstanceFromEntity($subject));
            }
            $collection->add($this->getInstance($subject->getId()));
        }
        return $collection;
    }

    public function has($subject)
    {
        if ($subject instanceof SubjectServiceInterface) {
            $subject = $subject->getId();
        }
        return $this->hasInstance($subject);
    }

    /*private function injectInstances()
    {
        if (count($this->getInstances())) {
            return $this;
        }
        
        $em = $this->getObjectManager();
        $entities = $em->getRepository($this->resolveClassName('Subject\Entity\SubjectEntityInterface'))
            ->findAll();
        foreach ($entities as $entity) {
            $this->add($this->createInstanceFromEntity($entity));
        }
        return $this;
    }*/
    
    /*
     * public function getSubjectFromRequest () { return $this->get(1); }
     */
    protected function createInstanceFromEntity(TermTaxonomyEntityInterface $entity)
    {
        if (! isset($this->config[$entity->getName()]))
            throw new \Exception(sprintf('Could not find a configuration for `%s`', $entity->getType()->getName()));
        $options = $this->config[$entity->getName()];
        
        $instance = $this->createInstance('Subject\Service\SubjectServiceInterface');
        $instance->setEntity($entity);
        $instance->setOptions($options);
        return $instance;
    }
}