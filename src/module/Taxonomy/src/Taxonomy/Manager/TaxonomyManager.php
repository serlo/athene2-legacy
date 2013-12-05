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

use Taxonomy\Model\TaxonomyTermModelInterface;
use Taxonomy\Collection\TermCollection;
use Taxonomy\Exception\RuntimeException;
use Taxonomy\Exception\TermNotFoundException;
use Taxonomy\Exception\InvalidArgumentException;
use Taxonomy\Entity\TaxonomyTypeInterface;
use Doctrine\Common\Collections\ArrayCollection;

class TaxonomyManager extends AbstractManager implements TaxonomyManagerInterface
{
    use \Common\Traits\ObjectManagerAwareTrait,\Language\Service\LanguageServiceAwareTrait,\Common\Traits\EntityDelegatorTrait,\Taxonomy\Manager\SharedTaxonomyManagerAwareTrait,\Common\Traits\ConfigAwareTrait;

    public function getTerm($id)
    {
        if (! is_numeric($id))
            throw new InvalidArgumentException(sprintf('Expected int but got %s', gettype($id)));
        
        if (! $this->hasInstance($id)) {
            $entity = $this->getObjectManager()->find($this->getClassResolver()
                ->resolveClassName('Taxonomy\Model\TaxonomyTermModelInterface'), (int) $id);
            
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
        $found = false;
        foreach ($ancestors as &$element) {
            if (is_string($element) && strlen($element) > 0) {
                $element = strtolower($element);
                foreach ($terms as $term) {
                    $found = false;
                    if (strtolower($term->getSlug()) == strtolower($element)) {
                        $terms = $term->getChildren();
                        $found = $term;
                        $ancestorsFound ++;
                        break;
                    }
                }
                if (! is_object($found))
                    break;
            }
        }
        
        if (! is_object($found))
            throw new TermNotFoundException(sprintf('Could not find term with acestors: %s', implode(',', $ancestors)));
        
        if ($ancestorsFound != count($ancestors))
            throw new TermNotFoundException(sprintf('Could not find term with acestors: %s. Ancestor ratio %s:%s does not equal 1:1', implode(',', $ancestors), $ancestorsFound, count($ancestors)));
        
        if (! $this->hasInstance($found->getId())) {
            $this->addInstance($found->getId(), $this->createService($found));
        }
        
        return $this->getInstance($found->getId());
    }

    public function getSaplings()
    {
        $collection = $this->getEntity()->getSaplings();
        return new TermCollection($collection, $this->getSharedTaxonomyManager());
    }

    public function getAllowedChildrenTypes()
    {
        return $this->getSharedTaxonomyManager()->getAllowedChildrenTypes($this->getEntity()
            ->getName(), $this->getLanguageService());
    }

    public function getAllowedParentTypes()
    {
        $collection = new ArrayCollection();
        foreach ($this->getOption('allowed_parents') as $taxonomy) {
            $collection->add($this->getSharedTaxonomyManager()
                ->findTaxonomyByName($taxonomy, $this->getLanguageService()));
        }
        return $collection;
    }

    public function allowsParentType($type)
    {
        return in_array($type, $this->getOption('allowed_parents'));
    }

    public function getId()
    {
        return $this->getEntity()->getId();
    }

    public function getType()
    {
        return $this->getEntity()->getType();
    }

    public function getName()
    {
        return $this->getEntity()->getName();
    }

    public function setType(TaxonomyTypeInterface $type)
    {
        $this->getEntity()->setType($type);
        return $this;
    }

    public function findTerms(array $types)
    {
        $collection = $this->getEntity()
            ->getTerms()
            ->filter(function (TaxonomyTermModelInterface $term) use($types)
        {
            return in_array($term->getTaxonomy()
                ->getName(), $types);
        });
        return new TermCollection($collection, $this->getSharedTaxonomyManager());
    }

    public function getTerms()
    {
        return new TermCollection($this->getEntity()->getTerms(), $this->getSharedTaxonomyManager());
    }

    public function setTerms($terms)
    {
        $this->getEntity()->setTerms($terms);
        return $this;
    }

    public function addTerm(TaxonomyTermModelInterface $term)
    {
        $this->getEntity()
            ->getTerms()
            ->add($term);
        return $this;
    }

    public function getRadixEnabled()
    {
        return $this->getOption('radix_enabled');
    }

    protected function createService(TaxonomyTermModelInterface $entity)
    {
        /* @var $instance \Taxonomy\Service\TermServiceInterface */
        $instance = $this->createInstance('Taxonomy\Service\TermServiceInterface');
        $instance->setTaxonomyTerm($entity);
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
            'templates' => array(
                'update' => 'taxonomy/taxonomy/update'
            ),
            'allowed_parents' => array(),
            'allowed_associations' => array(),
            'radix_enabled' => true
        );
    }
}