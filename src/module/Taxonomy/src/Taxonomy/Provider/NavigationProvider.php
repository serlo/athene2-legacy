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
use Instance\Manager\InstanceManagerAwareTrait;
use Navigation\Provider\PageProviderInterface;
use Taxonomy\Entity\TaxonomyTermInterface;
use Taxonomy\Manager\TaxonomyManagerAwareTrait;
use Zend\ServiceManager\ServiceLocatorAwareTrait;
use Zend\Stdlib\ArrayUtils;

class NavigationProvider implements PageProviderInterface
{
    use TaxonomyManagerAwareTrait, ServiceLocatorAwareTrait;
    use ObjectManagerAwareTrait, InstanceManagerAwareTrait;

    /**
     * @var array
     */
    protected $defaultOptions = [
        'name'      => 'default',
        'route'     => 'default',
        'parent'    => array(
            'type' => '',
            'slug' => ''
        ),
        'instance'  => 'de',
        'max_depth' => 1,
        'types'     => array(),
        'params'    => array()
    ];

    /**
     * @var array
     */
    protected $options;

    /**
     * @var TaxonomyTermInterface
     */
    protected $term;

    public function provide(array $options)
    {
        $this->options = ArrayUtils::merge($this->defaultOptions, $options);

        if ($this->getObjectManager()->isOpen()) {
            $this->getObjectManager()->refresh($this->getTerm());
        }

        $terms      = $this->getTerm()->findChildrenByTaxonomyNames($this->options['types']);
        $return     = $this->iterTerms($terms, $this->options['max_depth']);
        $this->term = null;

        return $return;
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
                $current          = array();
                $current['route'] = $this->options['route'];

                $current['params'] = ArrayUtils::merge(
                    $this->options['params'],
                    array(
                        'path' => $term->slugify($this->options['parent']['type'])
                    )
                );

                $current['label'] = $term->getName();
                $children         = $term->findChildrenByTaxonomyNames($this->options['types']);
                if (count($children)) {
                    $current['pages'] = $this->iterTerms($children, $depth - 1);
                }
                $return[] = $current;
            }
        }

        return $return;
    }
}