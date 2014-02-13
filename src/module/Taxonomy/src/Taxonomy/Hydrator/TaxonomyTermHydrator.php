<?php
/**
 * Athene2 - Advanced Learning Resources Manager
 *
 * @author      Aeneas Rekkas (aeneas.rekkas@serlo.org)
 * @license     LGPL-3.0
 * @license     http://opensource.org/licenses/LGPL-3.0 The GNU Lesser General Public License, version 3.0
 * @link        https://github.com/serlo-org/athene2 for the canonical source repository
 * @copyright   Copyright (c) 2013 Gesellschaft für freie Bildung e.V. (http://www.open-education.eu/)
 */
namespace Taxonomy\Hydrator;

use Taxonomy\Entity\TaxonomyTermInterface;
use Taxonomy\Exception\RuntimeException;
use Taxonomy\Manager\TaxonomyManagerInterface;
use Taxonomy\Options\ModuleOptions;
use Term\Exception\TermNotFoundException;
use Term\Manager\TermManagerAwareTrait;
use Term\Manager\TermManagerInterface;
use Uuid\Manager\UuidManagerAwareTrait;
use Uuid\Manager\UuidManagerInterface;
use Zend\Stdlib\Hydrator\HydratorInterface;

class TaxonomyTermHydrator implements HydratorInterface
{

    use TermManagerAwareTrait, UuidManagerAwareTrait;

    /**
     * @var ModuleOptions
     */
    protected $moduleOptions;

    /**
     * @var \Taxonomy\Manager\TaxonomyManagerInterface
     */
    protected $taxonomyManager;

    public function __construct(
        ModuleOptions $moduleOptions,
        TermManagerInterface $termManager,
        UuidManagerInterface $uuidManager,
        TaxonomyManagerInterface $taxonomyManager
    ) {
        $this->termManager     = $termManager;
        $this->moduleOptions   = $moduleOptions;
        $this->uuidManager     = $uuidManager;
        $this->taxonomyManager = $taxonomyManager;
    }

    /**
     * @see \Zend\Stdlib\Extractor\ExtractionInterface::extract()
     * @param TaxonomyTermInterface $object
     * @return array
     */
    public function extract($object)
    {
        $term = $object->getTerm();

        return [
            'id'          => is_object($object->getUuidEntity()) ? $object->getId() : null,
            'term'        => [
                'id'   => is_object($term) ? $term->getId() : null,
                'name' => is_object($term) ? $term->getName() : null,
                'slug' => is_object($term) ? $term->getSlug() : null
            ],
            'taxonomy'    => is_object($object->getTaxonomy()) ? $object->getTaxonomy()->getId() : null,
            'parent'      => is_object($object->getParent()) ? $object->getParent()->getId() : null,
            'description' => $object->getDescription(),
            'position'    => $object->getPosition()
        ];
    }

    public function hydrate(array $data, $object)
    {
        $data = $this->validate($data, $object);

        $this->getUuidManager()->injectUuid($object, $object->getUuidEntity());
        $object->setTaxonomy($data['taxonomy']);
        $object->setTerm($data['term']);
        $object->setDescription($data['description']);
        $object->setParent($data['parent']);
        $object->setPosition($data['position']);

        return $object;
    }

    /**
     * @param array                 $data
     * @param TaxonomyTermInterface $object
     * @return array
     * @throws \Taxonomy\Exception\RuntimeException
     */
    protected function validate(array $data, TaxonomyTermInterface $object)
    {
        $taxonomy = $data['taxonomy'];
        $parent   = isset($data['parent']) ? $data['parent'] : null;
        if (!is_object($taxonomy)) {
            $taxonomy = $data['taxonomy'] = $this->taxonomyManager->getTaxonomy($taxonomy);
        }
        if ($parent && !is_object($parent)) {
            $parent = $data['parent'] = $this->taxonomyManager->getTerm($parent);
        }
        $options = $this->getModuleOptions()->getType($taxonomy->getName());

        if ($parent === null && !$options->isRootable()) {
            throw new RuntimeException(sprintf(
                'Taxonomy "%s" is not rootable.',
                $taxonomy->getName()
            ));
        } elseif ($parent instanceof TaxonomyTermInterface) {
            $parentType    = $parent->getTaxonomy()->getName();
            $objectType    = $taxonomy->getName();
            $objectOptions = $this->getModuleOptions()->getType($objectType);

            if (!$objectOptions->isParentAllowed($parentType)) {
                throw new RuntimeException(sprintf(
                    'Parent "%s" does not allow child "%s"',
                    $parentType,
                    $objectType
                ));
            }
        }

        try {
            $data['term'] = $this->getTermManager()->findTermByName(
                $data['term']['name'],
                $taxonomy->getInstance()
            );
        } catch (TermNotFoundException $e) {
            $data['term'] = $this->getTermManager()->createTerm(
                $data['term']['name'],
                null,
                $taxonomy->getInstance()
            );
        }

        return $data;
    }

    /**
     * @return ModuleOptions $moduleOptions
     */
    public function getModuleOptions()
    {
        return $this->moduleOptions;
    }
}