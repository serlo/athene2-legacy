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

use Subject\Exception\InvalidArgumentException;
use Doctrine\Common\Collections\ArrayCollection;
use Language\Entity\LanguageInterface;
use Taxonomy\Entity\TaxonomyTermInterface;
use Subject\Exception\RuntimeException;

class SubjectManager extends AbstractManager implements SubjectManagerInterface
{
    use \Common\Traits\ConfigAwareTrait,\Common\Traits\ObjectManagerAwareTrait,\Taxonomy\Manager\TaxonomyManagerAwareTrait,\Subject\Plugin\PluginManagerAwareTrait,\Language\Manager\LanguageManagerAwareTrait;

    public function __construct(array $config)
    {
        $this->setConfig($config);
    }

    protected function getDefaultConfig()
    {
        return array(
            'taxonomy' => 'subject',
            'instances' => array(),
            'plugins' => array()
        );
    }

    public function getSubject($id)
    {
        if (! is_numeric($id))
            throw new InvalidArgumentException();
        
        if (! $this->hasInstance($id)) {
            $term = $this->getTaxonomyManager()->getTerm((int) $id);
            $this->addInstance($term->getId(), $this->createInstanceFromEntity($term));
        }
        
        return $this->getInstance($id);
    }

    public function findSubjectByString($name, LanguageInterface $language)
    {
        if (! is_string($name))
            throw new InvalidArgumentException();
        
        $taxonomy = $this->getTaxonomyManager()
            ->findTaxonomyByName($this->getOption('taxonomy'), $language);
        $term = $this->getTaxonomyManager()->findTerm($taxonomy, (array) $name);
        
        if (! $this->hasInstance($term->getId())) {
            $this->addInstance($term->getId(), $this->createInstanceFromEntity($term));
        }
        
        return $this->getInstance($term->getId());
    }

    public function findSubjectsByLanguage(LanguageInterface $language)
    {
        $taxonomy = $this->getTaxonomyManager()->findTaxonomyByName($this->getOption('taxonomy'), $language);
        $collection = new ArrayCollection();
        foreach ($taxonomy->getChildren() as $subject) {
            $collection->add($this->getSubject($subject->getId()));
        }
        return $collection;
    }

    private function createInstanceFromEntity(TaxonomyTermInterface $entity)
    {
        $name = strtolower($entity->getName());
        $languageService = $this->getLanguageManager()->getLanguage($entity->getLanguage()->getId());
        
        $config = $this->findInstanceConfig($name, $languageService->getCode());
        
        $instance = $this->createInstance('Subject\Service\SubjectServiceInterface');
        $instance->setEntity($entity);
        $instance->setConfig($config);
        return $instance;
    }

    private function findInstanceConfig($name, $language)
    {
        foreach ($this->getOption('instances') as $instance) {
            if ($instance['name'] == $name && $instance['language'] == $language)
                return $instance;
        }
        throw new RuntimeException(sprintf('Could not find a configuration for `%s - %s`', $name, $language));
    }
}