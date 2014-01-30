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
namespace Taxonomy\Provider;

use Taxonomy\Entity\TaxonomyTermInterface;
use Zend\Stdlib\ArrayUtils;

class NavigationProvider implements \Ui\Navigation\ProviderInterface
{
    use\Taxonomy\Manager\TaxonomyManagerAwareTrait,\Common\Traits\ConfigAwareTrait,\Zend\ServiceManager\ServiceLocatorAwareTrait,\Common\Traits\ObjectManagerAwareTrait,\Instance\Manager\InstanceManagerAwareTrait;

    /**
     *
     * @var array
     */
    protected function getDefaultConfig()
    {
        return array(
            'name' => 'default',
            'route' => 'default',
            'parent' => array(
                'type' => '',
                'slug' => ''
            ),
            'instance' => 'de',
            'max_depth' => 1,
            'types' => array(),
            'params' => array()
        );
    }

    /**
     *
     * @var TaxonomyTermInterface
     */
    protected $term;

    public function getTerm()
    {
        if (! is_object($this->term)) {
            $taxonomy = $this->getTaxonomyManager()->findTaxonomyByName($this->getOption('parent')['type'], $this->getInstanceManager()
                ->findInstanceByName($this->getOption('instance')));
            
            $this->term = $this->getTaxonomyManager()->findTerm($taxonomy, (array) $this->getOption('parent')['slug']);
        }
        return $this->term;
    }

    public function providePagesConfig()
    {
        if ($this->getObjectManager()->isOpen()){
            $this->getObjectManager()->refresh($this->getTerm());
        }
        
        $terms = $this->getTerm()->findChildrenByTaxonomyNames($this->getOption('types'));
        $return = $this->iterTerms($terms, $this->getOption('max_depth'));
        $this->term = NULL;
        return $return;
    }

    protected function iterTerms($terms, $depth)
    {
        if ($depth < 1){
            return [];
        }
        
        $return = [];
        foreach ($terms as $term) {
            if (! $term->isTrashed()) {
                $current = array();
                $current['route'] = $this->getOption('route');
                
                $current['params'] = ArrayUtils::merge($this->getOption('params'), array(
                    'path' => $term->slugify($this->getOption('parent')['type'])
                ));
                
                $current['label'] = $term->getName();
                $children = $term->findChildrenByTaxonomyNames($this->getOption('types'));
                if (count($children)) {
                    $current['pages'] = $this->iterTerms($children, $depth - 1);
                }
                $return[] = $current;
            }
        }
        return $return;
    }
}