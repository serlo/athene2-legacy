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

use Taxonomy\Exception;
use Language\Entity\LanguageInterface;
use Taxonomy\Entity\TaxonomyInterface;
use Taxonomy\Hydrator\TaxonomyTermHydrator;
use Taxonomy\Entity\TaxonomyTermAwareInterface;
use Taxonomy\Options\ModuleOptions;
use Taxonomy\Entity\TaxonomyTermInterface;

class TaxonomyManager implements TaxonomyManagerInterface
{
    use\ClassResolver\ClassResolverAwareTrait,\Common\Traits\ObjectManagerAwareTrait,\Type\TypeManagerAwareTrait;

    /**
     *
     * @var TaxonomyTermHydrator
     */
    protected $hydrator;

    /**
     *
     * @var ModuleOptions
     */
    protected $moduleOptions;

    /**
     *
     * @return ModuleOptions $moduleOptions
     */
    public function getModuleOptions()
    {
        return $this->moduleOptions;
    }

    /**
     *
     * @return TaxonomyTermHydrator $hydrator
     */
    public function getHydrator()
    {
        return $this->hydrator;
    }

    /**
     *
     * @param TaxonomyTermHydrator $hydrator            
     * @return self
     */
    public function setHydrator(TaxonomyTermHydrator $hydrator)
    {
        $this->hydrator = $hydrator;
        return $this;
    }

    /**
     *
     * @param ModuleOptions $moduleOptions            
     * @return self
     */
    public function setModuleOptions(ModuleOptions $moduleOptions)
    {
        $this->moduleOptions = $moduleOptions;
        return $this;
    }

    public function getTerm($id)
    {
        $className = $this->getClassResolver()->resolveClassName('Taxonomy\Entity\TaxonomyTermInterface');
        $entity = $this->getObjectManager()->find($className, $id);
        
        if (! is_object($entity)) {
            throw new Exception\TermNotFoundException(sprintf('Term with id %s not found', $id));
        }
        
        return $entity;
    }

    public function getTaxonomy($id)
    {
        $className = $this->getClassResolver()->resolveClassName('Taxonomy\Entity\TaxonomyInterface');
        $entity = $this->getObjectManager()->find($className, (int) $id);
        
        if (! is_object($entity)) {
            throw new Exception\RuntimeException(sprintf('Term with id %s not found', $id));
        }
        
        return $entity;
    }

    public function findTaxonomyByName($name, LanguageInterface $language)
    {
        $className = $this->getClassResolver()->resolveClassName('Taxonomy\Entity\TaxonomyInterface');
        
        $type = $this->getTypeManager()->findTypeByName($name);
        
        $entity = $this->getObjectManager()
            ->getRepository($className)
            ->findOneBy([
            'type' => $type->getId(),
            'language' => $language->getId()
        ]);
        
        if (! is_object($entity)) {
            throw new Exception\RuntimeException(sprintf('Taxonomy "%s" (language: "$s") not found', $name, $language->getCode()));
        }
        
        return $entity;
    }

    public function findTerm(TaxonomyInterface $taxonomy, array $ancestors)
    {
        if (! count($ancestors)) {
            throw new Exception\RuntimeException('Ancestors are empty');
        }
        
        $terms = $taxonomy->getChildren();
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
        
        if (! is_object($found)) {
            throw new Exception\TermNotFoundException(sprintf('Could not find term with acestors: %s', implode(',', $ancestors)));
        }
        
        if ($ancestorsFound != count($ancestors)) {
            throw new Exception\TermNotFoundException(sprintf('Could not find term with acestors: %s. Ancestor ratio %s:%s does not equal 1:1', implode(',', $ancestors), $ancestorsFound, count($ancestors)));
        }
        
        return $found;
    }

    public function createTerm(array $data, LanguageInterface $language)
    {
        $term = $this->getClassResolver()->resolve('Taxonomy\Entity\TaxonomyTermInterface');
        
        if (isset($data['taxonomy']) && !$data['taxonomy'] instanceof TaxonomyInterface) {
            $data['taxonomy'] = $this->findTaxonomyByName($data['taxonomy'], $language);
        }
        if (isset($data['parent']) && !$data['parent'] instanceof TaxonomyTermInterface) {
            $data['parent'] = $this->getTerm($data['parent']);
        }
        
        $this->getHydrator()->hydrate($data, $term);
        $this->getObjectManager()->persist($term);
        
        return $term;
    }

    public function updateTerm($id, array $data)
    {
        $term = $this->getTerm($id);
        
        if (isset($data['taxonomy'])) {
            $data['taxonomy'] = $this->getTaxonomy($data['taxonomy']);
        }
        if (isset($data['parent'])) {
            $data['parent'] = $this->getTerm($data['parent']);
        }
        
        $this->getHydrator()->hydrate($data, $term);
        $this->getObjectManager()->persist($term);
        
        return $term;
    }

    public function associateWith($id, $association, TaxonomyTermAwareInterface $object)
    {
        $term = $this->getTerm($id);
        
        $taxonomy = $term->getTaxonomy();
        
        if (! $this->getModuleOptions()
            ->getType($taxonomy->getName())
            ->isAssociationAllowed($association)) {
            throw new Exception\RuntimeException(sprintf('Taxonomy "%s" does not allow associations "%s"', $taxonomy->getName(), $association));
        }
        
        $term->associateObject($association, $object);
        $this->getObjectManager()->persist($term);
        
        return $this;
    }
}