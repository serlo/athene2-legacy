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
namespace Taxonomy\View\Helper;

use Taxonomy\Entity\TaxonomyInterface;
use Taxonomy\Entity\TaxonomyTermInterface;
use Taxonomy\Exception;
use Taxonomy\Manager\TaxonomyManagerInterface;
use Taxonomy\Options\ModuleOptions;
use Zend\View\Helper\AbstractHelper;

class TaxonomyHelper extends AbstractHelper
{
    /**
     * @var \Taxonomy\Manager\TaxonomyManagerInterface
     */
    protected $taxonomyManager;
    /**
     * @var ModuleOptions
     */
    protected $moduleOptions;

    public function __construct(ModuleOptions $moduleOptions, TaxonomyManagerInterface $taxonomyManager)
    {
        $this->moduleOptions   = $moduleOptions;
        $this->taxonomyManager = $taxonomyManager;
    }

    public function getAllowedChildren($object)
    {
        if ($object instanceof TaxonomyInterface) {
            $name = $object->getName();
        } elseif ($object instanceof TaxonomyTermInterface) {
            $name = $object->getTaxonomy()->getName();
        } else {
            throw new Exception\InvalidArgumentException(sprintf(
                'Expected $nameOrObject to be TaxonomyInterface, TaxonomyTermInterface or string but got "%s"',
                is_object($nameOrObject) ? get_class($nameOrObject) : gettype($nameOrObject)
            ));
        }

        $taxonomies = [];
        $children   = $this->getOptions($name)->getAllowedChildren();
        $instance   = $object->getInstance();
        foreach ($children as $child) {
            $taxonomies[] = $this->taxonomyManager->findTaxonomyByName($child, $instance);
        }

        return $taxonomies;
    }

    /**
     * @param TaxonomyInterface|TaxonomyTermInterface|string $nameOrObject
     * @throws Exception\InvalidArgumentException
     * @return Ambigous \Taxonomy\Options\TaxonomyOptions
     */
    public function getOptions($nameOrObject)
    {
        if ($nameOrObject instanceof TaxonomyInterface) {
            $name = $nameOrObject->getName();
        } elseif ($nameOrObject instanceof TaxonomyTermInterface) {
            $name = $nameOrObject->getTaxonomy()->getName();
        } elseif (is_string($nameOrObject)) {
            $name = $nameOrObject;
        } else {
            throw new Exception\InvalidArgumentException(sprintf(
                'Expected $nameOrObject to be TaxonomyInterface, TaxonomyTermInterface or string but got "%s"',
                is_object($nameOrObject) ? get_class($nameOrObject) : gettype($nameOrObject)
            ));
        }

        return $this->moduleOptions->getType($name);
    }
}