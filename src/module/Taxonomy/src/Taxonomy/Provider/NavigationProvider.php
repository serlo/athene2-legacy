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
namespace Taxonomy\Provider;

use Common\Traits\ObjectManagerAwareTrait;
use Doctrine\Common\Persistence\ObjectManager;
use Instance\Manager\InstanceManagerAwareTrait;
use Instance\Manager\InstanceManagerInterface;
use Navigation\Provider\PageProviderInterface;
use Taxonomy\Entity\TaxonomyTermInterface;
use Taxonomy\Manager\TaxonomyManagerAwareTrait;
use Taxonomy\Manager\TaxonomyManagerInterface;
use Zend\Cache\Storage\StorageInterface;
use Zend\ServiceManager\ServiceLocatorAwareTrait;
use Zend\Stdlib\ArrayUtils;

class NavigationProvider implements PageProviderInterface
{
    use TaxonomyManagerAwareTrait;
    use ObjectManagerAwareTrait, InstanceManagerAwareTrait;

    /**
     * @var array
     */
    protected $defaultOptions = [
        'name'      => 'default',
        'route'     => 'default',
        'parent'    => [
            'type' => '',
            'slug' => ''
        ],
        'instance'  => 'de',
        'max_depth' => 1,
        'types'     => [],
        'params'    => []
    ];
    /**
     * @var array
     */
    protected $options;
    /**
     * @var StorageInterface
     */
    protected $storage;
    /**
     * @var TaxonomyTermInterface
     */
    protected $term;

    public function __construct(
        InstanceManagerInterface $instanceManager,
        TaxonomyManagerInterface $taxonomyManager,
        ObjectManager $objectManager,
        StorageInterface $storage
    ) {
        $this->instanceManager = $instanceManager;
        $this->taxonomyManager = $taxonomyManager;
        $this->objectManager   = $objectManager;
        $this->storage         = $storage;

    }

    public function provide(array $options)
    {
        $this->options = ArrayUtils::merge($this->defaultOptions, $options);

        $term = $this->getTerm();
        $key  = 'provider.' . serialize($term);

        if ($this->storage->hasItem($key)) {
            $pages = $this->storage->getItem($key);
            return $pages;
        }

        if ($this->getObjectManager()->isOpen()) {
            $this->getObjectManager()->refresh($this->getTerm());
        }

        $terms      = $term->findChildrenByTaxonomyNames($this->options['types']);
        $pages      = $this->iterTerms($terms, $this->options['max_depth']);
        $this->term = null;
        $this->storage->setItem($key, $pages);

        return $pages;
    }

    public function getTerm()
    {
        if (!is_object($this->term)) {
            $taxonomy = $this->getTaxonomyManager()->findTaxonomyByName(
                $this->options['parent']['type'],
                $this->getInstanceManager()->findInstanceByName($this->options['instance'])
            );

            $this->term = $this->getTaxonomyManager()->findTerm($taxonomy, (array)$this->options['parent']['slug']);
        }

        return $this->term;
    }

    protected function iterTerms($terms, $depth)
    {
        if ($depth < 1) {
            return [];
        }

        $return = [];
        foreach ($terms as $term) {
            if (!$term->isTrashed()) {
                $current          = [];
                $current['route'] = $this->options['route'];

                $current['params'] = ArrayUtils::merge(
                    $this->options['params'],
                    [
                        'path' => $term->slugify($this->options['parent']['type'])
                    ]
                );

                $current['label']    = $term->getName();
                $current['elements'] = $term->countElements();
                $children            = $term->findChildrenByTaxonomyNames($this->options['types']);
                if (count($children)) {
                    $current['pages'] = $this->iterTerms($children, $depth - 1);
                }
                $return[] = $current;
            }
        }

        return $return;
    }
}