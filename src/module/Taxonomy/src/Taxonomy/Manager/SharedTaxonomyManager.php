<?php
/**
 *
 * @author Aeneas Rekkas (aeneas.rekkas@serlo.org)
 * @copyright 2013 by www.serlo.org
 * @license LGPL
 * @license http://www.gnu.org/copyleft/lesser.html GNU Lesser General Public License (LGPL)
 */
namespace Taxonomy\Manager;

use Taxonomy\Exception\NotFoundException;
use Taxonomy\Exception\InvalidArgumentException;
use Language\Service\LanguageServiceInterface;
use Taxonomy\Exception\ConfigNotFoundException;
use Taxonomy\Exception\TermNotFoundException;
use Taxonomy\Entity\TaxonomyTermInterface;
use Taxonomy\Entity\TaxonomyInterface;
use Doctrine\Common\Collections\Criteria;
use Taxonomy\Exception\RuntimeException;
use Doctrine\Common\Collections\ArrayCollection;
use Taxonomy\Entity\TaxonomyTypeInterface;
use Language\Model\LanguageModelInterface;
use Taxonomy\Entity\TaxonomyType;
use Taxonomy\Model\TaxonomyTermModelInterface;

class SharedTaxonomyManager extends AbstractManager implements SharedTaxonomyManagerInterface
{
    use \Common\Traits\ObjectManagerAwareTrait,\Language\Manager\LanguageManagerAwareTrait,\Common\Traits\ConfigAwareTrait,\Term\Manager\TermManagerAwareTrait,\Uuid\Manager\UuidManagerAwareTrait;

    public function getDefaultConfig()
    {
        return array();
    }

    public function __construct(array $config = NULL)
    {
        if ($config)
            $this->setConfig($config);
    }

    public function getTaxonomy($id)
    {
        if (! is_numeric($id))
            throw new InvalidArgumentException(sprintf('Expected int but got %s', gettype($id)));
        
        if (! $this->hasInstance($id)) {
            $entity = $this->getObjectManager()->find($this->getClassResolver()
                ->resolveClassName('Taxonomy\Entity\TaxonomyInterface'), $id);
            
            if (! is_object($entity))
                throw new NotFoundException(sprintf('A taxonomy by the id of %s could not be found.', $id));
            
            $this->addInstance($entity->getId(), $this->createService($entity));
        }
        
        return $this->getInstance($id);
    }

    public function findTaxonomyByName($name, LanguageModelInterface $language)
    {
        if (! is_string($name))
            throw new InvalidArgumentException(sprintf('Expected string but got %s', gettype($name)));
        
        $type = $this->getObjectManager()
            ->getRepository($this->getClassResolver()
            ->resolveClassName('Taxonomy\Entity\TaxonomyTypeInterface'))
            ->findOneBy(array(
            'name' => $name
        ));
        
        if (! is_object($type))
            throw new InvalidArgumentException(sprintf('Taxonomy type %s not found', $name));
        
        $entity = $type->getTaxonomies()
            ->matching(Criteria::create()->where(Criteria::expr()->eq('language', $language->getEntity()))
            ->setMaxResults(1))
            ->first();
        
        if (! is_object($entity))
            throw new NotFoundException(sprintf('Could not find Taxonomy %s by language %s.', $name, $language->getId()));
        
        if (! $this->hasInstance($entity->getId())) {
            $this->addInstance($entity->getId(), $this->createService($entity));
        }
        
        return $this->getInstance($entity->getId());
    }

    public function getTerm($term)
    {
        if (is_numeric($term)) {
            $entity = $this->getObjectManager()->find($this->getClassResolver()
                ->resolveClassName('Taxonomy\Entity\TaxonomyTermInterface'), (int) $term);
        } elseif ($term instanceof TaxonomyTermModelInterface) {
            $entity = $term;
        } else {
            if (! is_object($term)) {
                throw new InvalidArgumentException(sprintf('Expected numeric but got %s', gettype($term)));
            } else {
                throw new InvalidArgumentException(sprintf('Expected `Taxonomy\Entity\TaxonomyTermInterface` but got %s', get_class($term)));
            }
        }
        
        if (! is_object($entity))
            throw new TermNotFoundException(sprintf('Term with id %s could not be found', $term));
        
        $return = $this->getTaxonomy($entity->getTaxonomy()
            ->getId())
            ->getTerm($entity->getId());
        
        return $return;
    }

    public function getCallback($link)
    {
        if (! array_key_exists($link, $this->getOption('associations')))
            throw new RuntimeException(sprintf('Callback for type %s not found', $link));
        
        return $this->getOption('associations')[$link]['callback'];
    }

    public function getAllowedChildrenTypes($type, LanguageModelInterface $language)
    {
        $collection = new ArrayCollection();
        foreach ($this->getAllowedChildrenTypeNames($type) as $child) {
            $collection->add($this->findTaxonomyByName($child, $language));
        }
        return $collection;
    }

    public function updateTerm($id, array $data)
    {
        $term = $this->getTerm($id);
        $this->hydrateTerm($data, $term->getEntity());
        $this->getObjectManager()->persist($term->getEntity());
        return $this;
    }

    public function deleteTerm($id)
    {
        $term = $this->getTerm($id);
        $this->getObjectManager()->remove($term->getEntity());
        $this->removeInstance($id);
        unset($term);
        return $this;
    }

    public function createTerm(array $data)
    {
        $entity = $this->getClassResolver()->resolve('Taxonomy\Entity\TaxonomyTermInterface');
        
        $this->hydrateTerm($data, $entity);
        $this->getUuidManager()->injectUuid($entity);
        
        $this->getObjectManager()->persist($entity);
        
        return $entity;
    }

    private function hydrateTerm(array $data, TaxonomyTermInterface $taxonomyTerm)
    {
        $columns = array(
            'description' => 'setDescription'
        );
        
        $taxonomyManager = array_key_exists('taxonomy', $data) ? $this->getTaxonomy($data['taxonomy']) : $this->getTaxonomy($taxonomyTerm->getTaxonomy()
            ->getId());
        $parent = array_key_exists('parent', $data) ? $data['parent'] : null;
        
        $taxonomyManager->addTerm($taxonomyTerm);
        
        if ($parent) {
            $parent = $this->getTerm($parent);
            if (! $taxonomyManager->allowsParentType($parent->getTaxonomy()
                ->getName())) {
                throw new RuntimeException(sprintf('Taxonomy `%s` does not allow parent of type `%s`', $taxonomyManager->getName(), $parent->getTaxonomy()->getName()));
            }
            $taxonomyTerm->setParent($parent->getEntity());
        } else {
            if (! $taxonomyManager->getRadixEnabled())
                throw new RuntimeException(sprintf('Taxonomy `%s` does allow `parent` to be NULL', $taxonomyManager->getName()));
        }
        
        try {
            $term = $this->getTermManager()
                ->findTermByName($data['term']['name'], $taxonomyManager->getLanguageService())
                ->getEntity();
        } catch (\Term\Exception\TermNotFoundException $e) {
            $term = $this->getTermManager()->createTerm($data['term']['name'], NULL, $taxonomyManager->getLanguageService());
        }
        $taxonomyTerm->setTerm($term);
        
        $taxonomyManager->addTerm($taxonomyTerm);
        $taxonomyTerm->setTaxonomy($taxonomyManager->getEntity());
        
        foreach ($columns as $key => $method) {
            if (array_key_exists($key, $data)) {
                $taxonomyTerm->$method($data[$key]);
            }
        }
        
        return $this;
    }

    protected function getAllowedChildrenTypeNames($type)
    {
        $return = array();
        foreach ($this->getOption('types') as $name => $config) {
            if (array_key_exists('allowed_parents', (array) $config['options']) && in_array($type, $config['options']['allowed_parents'])) {
                $return[] = $name;
            }
        }
        return $return;
    }

    protected function createService(TaxonomyInterface $entity)
    {
        if (! array_key_exists($entity->getName(), $this->getOption('types'))) {
            throw new ConfigNotFoundException(sprintf('Could not find a configuration for `%s` (ID %s).', $entity->getName(), $entity->getId()));
        }
        
        $instance = parent::createInstance('Taxonomy\Manager\TaxonomyManagerInterface');
        $instance->setEntity($entity);
        $instance->setSharedTaxonomyManager($this);
        $instance->setLanguageService($this->getLanguageManager()
            ->getLanguage($entity->getLanguage()
            ->getId()));
        $instance->setConfig($this->getOption('types')[$entity->getName()]['options']);
        return $instance;
    }
}
