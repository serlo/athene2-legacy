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
use Subject\Exception\InvalidArgumentException;
use Doctrine\Common\Collections\ArrayCollection;
use Language\Service\LanguageServiceInterface;
use Taxonomy\Service\TermServiceInterface;

class SubjectManager extends AbstractManager implements SubjectManagerInterface
{
    use\Common\Traits\ConfigAwareTrait,\Common\Traits\ObjectManagerAwareTrait,\Taxonomy\Manager\SharedTaxonomyManagerAwareTrait,\Subject\Plugin\PluginManagerAwareTrait,\Language\Manager\LanguageManagerAwareTrait,\Taxonomy\Service\TermServiceAwareTrait;

    public function __construct(array $config){
        $this->setConfig($config);
    }
    
    protected function getDefaultConfig()
    {
        return array(
            'taxonomy' => 'subject',
            'instances' => array(),
            'plugins' => array(),
        );
    }

    public function addSubject(SubjectServiceInterface $service)
    {
        $this->addInstance($service->getId(), $service);
        return $this;
    }

    public function getSubject($id)
    {
        if (! is_numeric($id))
            throw new InvalidArgumentException();
        
        if (! $this->hasInstance($id)) {
            $term = $this->getSharedTaxonomyManager()->getTerm((int) $id);
            $this->addSubject($this->createInstanceFromEntity($term));
        }
        
        return $this->getInstance($id);
    }

    public function findSubjectByString($name, LanguageServiceInterface $language)
    {
        if (! is_string($name))
            throw new InvalidArgumentException();
        
        $term = $this->getSharedTaxonomyManager()
            ->get($this->getOption('taxonomy'), $language)
            ->findTermByAncestors((array) $name);
        
        if(!$this->hasInstance($term->getId())){
            $this->addSubject($this->createInstanceFromEntity($term));
        }
        
        return $this->getInstance($term->getId());
    }

    public function findSubjectsByLanguage(LanguageServiceInterface $language)
    {
        $taxonomy = $this->getSharedTaxonomyManager()->get($this->getOption('taxonomy'), $language);
        $collection = new ArrayCollection();
        foreach ($taxonomy->getRootTerms() as $subject) {
            $collection->add($this->getSubject($subject->getId()));
        }
        return $collection;
    }

    public function hasSubject($subject)
    {
        return $this->hasInstance($subject);
    }

    private function createInstanceFromEntity(TermServiceInterface $entity)
    {
        $entity = $entity->getEntity();
        $name = strtolower($entity->getName());
        
        if (! array_key_exists($name, $this->getOption('instances')))
            throw new \Exception(sprintf('Could not find a configuration for `%s`', $name));
        
        $instance = $this->createInstance('Subject\Service\SubjectServiceInterface');
        $instance->setEntity($entity);
        $instance->setLanguageService($this->getLanguageManager()->get($entity->getLanguage()));
        $instance->setConfig($this->getOption('instances')[$name]);
        return $instance;
    }
}