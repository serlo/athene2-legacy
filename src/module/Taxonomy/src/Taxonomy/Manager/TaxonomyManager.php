<?php
/**
 *
 *
 * Athene2 - Advanced Learning Resources Manager
 *
 * @author Aeneas Rekkas (aeneas.rekkas@serlo.org)
 * @license LGPL-3.0
 * @license http://opensource.org/licenses/LGPL-3.0 The GNU Lesser General Public License, version 3.0
 * @link https://github.com/serlo-org/athene2 for the canonical source repository
 * @copyright Copyright (c) 2013 Gesellschaft für freie Bildung e.V. (http://www.open-education.eu/)
 */
namespace Taxonomy\Manager;

use Taxonomy\Entity\TermTaxonomyInterface;
use Taxonomy\Collection\TermCollection;
use Language\Service\LanguageServiceInterface;
use Taxonomy\Exception\RuntimeException;
use Taxonomy\Exception\TermNotFoundException;
use Taxonomy\Exception\InvalidArgumentException;
use Taxonomy\Entity\TaxonomyTypeInterface;

class TaxonomyManager extends AbstractManager implements TaxonomyManagerInterface
{
    use\Common\Traits\ObjectManagerAwareTrait,\Language\Service\LanguageServiceAwareTrait,\Common\Traits\EntityDelegatorTrait,\Uuid\Manager\UuidManagerAwareTrait,\Taxonomy\Manager\SharedTaxonomyManagerAwareTrait,\Term\Manager\TermManagerAwareTrait,\Common\Traits\ConfigAwareTrait;

    public function getTerm($id)
    {
        if (! is_numeric($id))
            throw new InvalidArgumentException(sprintf('Expected int but got %s', gettype($id)));
        
        if (! $this->hasInstance($id)) {
            $entity = $this->getObjectManager()->find($this->getClassResolver()
                ->resolveClassName('Taxonomy\Entity\TermTaxonomyInterface'), (int) $id);
            
            if (! is_object($entity))
                throw new TermNotFoundException(sprintf('Term with id %s not found', $id));
            
            $this->addInstance($entity->getId(), $this->createService($entity));
        }
        
        return $this->getInstance($id);
    }

    public function findTermByAncestors(array $ancestors)
    {
        if (! count($ancestors))
            throw new RuntimeException('Ancestors are empty');
        
        $terms = $this->getEntity()->getSaplings();
        $ancestorsFound = 0;
        foreach ($ancestors as $element) {
            if (is_string($element) && strlen($element) > 0) {
                foreach ($terms as $term) {
                    $found = false;
                    if (strtolower($term->getSlug()) == strtolower($element)) {
                        $terms = $term->getChildren();
                        $found = $term;
                        $ancestorsFound++;
                        break;
                    }
                }
            }
        }
        
        if (! is_object($found) || $ancestorsFound != count($ancestors))
            throw new TermNotFoundException(sprintf('Could not find term with acestors: %s', implode(',', $ancestors)));
        
        if (! $this->hasInstance($found->getId())) {
            $this->addInstance($found->getId(), $this->createService($found));
        }
        
        return $this->getInstance($found->getId());
    }

    public function deleteTerm($id)
    {
        $term = $this->getTerm($id);
        $this->getObjectManager()->remove($term->getEntity());
        $this->removeInstance($id);
        unset($term);
        return $this;
    }

    public function getSaplings()
    {
        $collection = $this->getEntity()->getSaplings();
        return new TermCollection($collection, $this->getSharedTaxonomyManager());
    }

    public function createTerm(array $data, TaxonomyManagerInterface $taxonomy, LanguageServiceInterface $language)
    {
        $entity = $this->getClassResolver()->resolve('Taxonomy\Entity\TermTaxonomyInterface');
        
        try {
            $term = $this->getTermManager()->findTermByName($data['term']['name'], $language);
        } catch (TermNotFoundException $e) {
            $term = $this->getTermManager()->createTerm($data['term']['name'], $language);
        }
        
        $this->getUuidManager()->injectUuid($entity);
        $entity->setTerm($term->getEntity());
        $this->getEntity()->addTerm($term);
        $this->hydrateTerm($data, $entity);
        
        $this->getObjectManager()->persist($entity);
        $instance = $this->createService($entity);
        return $instance;
    }

    public function updateTerm($id, array $data)
    {
        $term = $this->getTerm($id);
        $this->getObjectManager()->persist($term);
        return $this;
    }

    public function getAllowedChildrenTypes()
    {
        return $this->getSharedTaxonomyManager()->getAllowedChildrenTypes($this->getEntity()
            ->getName());
    }

    public function allowsParentType($type)
    {
        return in_array($type, $this->getOption('allowed_parents'));
    }

    public function getAllowedParentTypes()
    {
        return $this->getOption('allowed_parents');
    }

    public function getId()
    {
        return $this->getEntity()->getId();
    }

    public function getType()
    {
        return $this->getEntity()->getType();
    }

    public function setType(TaxonomyTypeInterface $type)
    {
        return $this->getEntity()->setType($type);
    }

    public function getTerms()
    {
        return new TermCollection($this->getEntity()->getTerms(), $this->getSharedTaxonomyManager());
    }

    public function setTerms($terms)
    {
        return $this->getEntity()->setTerms($terms);
    }

    protected function createService(TermTaxonomyInterface $entity)
    {
        $instance = $this->createInstance('Taxonomy\Service\TermServiceInterface');
        $instance->setTermTaxonomy($entity);
        if ($entity->getTaxonomy() !== $this->getEntity()) {
            $instance->setManager($this->getSharedTaxonomyManager()
                ->getTaxonomy($entity->getTaxonomy()
                ->getId()));
        } else {
            $instance->setManager($this);
        }
        return $instance;
    }

    protected function getDefaultConfig()
    {
        return array(
            'options' => array(
                'templates' => array(
                    'update' => 'taxonomy/taxonomy/update'
                ),
                'allowed_parents' => array(),
                'allowed_associations' => array(),
                'radix_enabled' => true
            )
        );
    }

    private function hydrateTerm(array $data, TermTaxonomyInterface $term)
    {
        $columns = array(
            'parent' => 'setParent',
            'description' => 'setDescription',
            'weight' => 'setWeight'
        );
        
        foreach ($columns as $key => $method) {
            if (array_key_exists($key, $data)) {
                $term->$method($data[$key]);
            }
        }
        
        return $this;
    }
}