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
namespace Taxonomy\Hydrator;

use Zend\Stdlib\Hydrator\HydratorInterface;
use Taxonomy\Entity\TaxonomyTermInterface;
use Taxonomy\Exception;
use Zend\Stdlib\ArrayUtils;
use Taxonomy\Options\ModuleOptions;

class TaxonomyTermHydrator implements HydratorInterface
{
    
    use \Term\Manager\TermManagerAwareTrait,\Uuid\Manager\UuidManagerAwareTrait;

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
     * @param ModuleOptions $moduleOptions            
     * @return $this
     */
    public function setModuleOptions(ModuleOptions $moduleOptions)
    {
        $this->moduleOptions = $moduleOptions;
        return $this;
    }

    /**
     *
     * @see \Zend\Stdlib\Extractor\ExtractionInterface::extract()
     *
     * @param TaxonomyTermInterface $object            
     * @return array
     */
    public function extract($object)
    {
        $term = $object->getTerm();
        return [
            'id' => $object->getId(),
            'term' => [
                'id' => $term !== NULL ? $term->getId() : NULL,
                'name' => $term !== NULL ? $term->getName() : NULL,
                'slug' => $term !== NULL ? $term->getSlug() : NULL,
            ],
            'taxonomy' => $object->getTaxonomy() !== NULL ? $object->getTaxonomy()->getId() : NULL,
            'parent' => $object->getParent(),
            'description' => $object->getDescription()
        ];
    }

    /**
     *
     * @see \Zend\Stdlib\Extractor\ExtractionInterface::hydrate()
     *
     * @param TaxonomyTermInterface $object            
     * @return TaxonomyTermInterface
     */
    public function hydrate(array $data, $object)
    {
        $data = ArrayUtils::merge($this->extract($object), $data);
        
        $data = $this->validate($data, $object);
        
        $this->getUuidManager()->injectUuid($object, $object->getUuidEntity());
        $object->setTaxonomy($data['taxonomy']);
        $object->setTerm($data['term']);
        $object->setDescription($data['description']);
        $object->setParent($data['parent']);
        
        return $object;
    }

    /**
     *
     * @param TaxonomyTermInterface $object            
     * @return boolean
     */
    protected function isValid(TaxonomyTermInterface $object)
    {
        return true;
    }

    /**
     *
     * @param array $data            
     * @param TaxonomyTermInterface $object            
     * @throws Exception\RuntimeException
     * @return array
     */
    protected function validate(array $data, TaxonomyTermInterface $object)
    {
        if (isset($data['taxonomy'])) {
            $options = $this->getModuleOptions()->getType($data['taxonomy']->getName());
        }
        
        $parent = $data['parent'];
        
        if ($data['parent'] === NULL && ! $options->isRootable()) {
            throw new Exception\RuntimeException(sprintf('Taxonomy "%s" is not rootable.', $data['taxonomy']->getName()));
        } elseif ( $data['parent'] instanceof TaxonomyTermInterface) {
            $parentType = $data['parent']->getTaxonomy()->getName();
            $objectType = $data['taxonomy']->getName();
            $parentOptions = $this->getModuleOptions()->getType($objectType);
            
            if (! $parentOptions->isChildAllowed($objectType)) {
                throw new Exception\RuntimeException(sprintf('Parent "%s" does not allow child "%s"', $parentType, $objectType));
            }
        } else {
            throw new Exception\RuntimeException('Parent must be TaxonomyTermInterface, got "%s"', is_object($data['parent']) ? get_class($data['parent']) : gettype($data['parent']));
        }
        
        try {
            $data['term'] = $this->getTermManager()
                ->findTermByName($data['term']['name'], $data['taxonomy']->getLanguage())
                ->getEntity();
        } catch (\Term\Exception\TermNotFoundException $e) {
            $data['term'] = $this->getTermManager()->createTerm($data['term']['name'], NULL, $data['taxonomy']->getLanguage());
        }
        
        return $data;
    }
}