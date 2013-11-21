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

use Zend\Stdlib\ArrayUtils;
use Taxonomy\Service\TermServiceInterface;

class NavigationProvider implements \Ui\Navigation\ProviderInterface
{
    use\Taxonomy\Manager\SharedTaxonomyManagerAwareTrait,\Common\Traits\ConfigAwareTrait,\Zend\ServiceManager\ServiceLocatorAwareTrait,\Common\Traits\ObjectManagerAwareTrait,\Language\Manager\LanguageManagerAwareTrait; // , \Common\Traits\ConfigAwareTrait;
    
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
            'language' => 'de',
            'max_depth' => 1,
            'types' => array(),
            'params' => array()
        );
    }

    /**
     *
     * @var TermServiceInterface
     */
    protected $termService;

    public function getTermService()
    {
        if (! is_object($this->termService)) {
            $this->termService = $this->getSharedTaxonomyManager()
                ->findTaxonomyByName($this->getOption('parent')['type'], $this->getLanguageManager()
                ->findLanguageByCode($this->getOption('language')))
                ->findTermByAncestors((array) $this->getOption('parent')['slug']);
        }
        return $this->termService;
    }

    public function providePagesConfig()
    {
        if ($this->getObjectManager()->isOpen())
            $this->getObjectManager()->refresh($this->getTermService()
                ->getEntity());
        
        $terms = $this->getTermService()->filterChildren($this->getOption('types'));
        $return = $this->iterTerms($terms, $this->getOption('max_depth'));
        $this->termService = NULL;
        return $return;
    }

    protected function iterTerms($terms, $depth)
    {
        if ($depth == 0)
            return array();
        
        $return = array();
        foreach ($terms as $term) {
            $current = array();
            $current['route'] = $this->getOption('route');
            
            $current['params'] = ArrayUtils::merge($this->getOption('params'), array(
                'path' => $this->getPathToTermAsUri($term)
            ));
            
            // getPathToTermAsUri
            
            $current['label'] = $term->getName();
            $children = $term->filterChildren($this->getOption('types'));
            if (count($children)) {
                $current['pages'] = $this->iterTerms($children, $depth - 1);
            }
            $return[] = $current;
        }
        return $return;
    }

    private function getPathToTermAsUri(TermServiceInterface $term)
    {
        return substr($this->_getPathToTermAsUri($term), 0, - 1);
    }

    private function _getPathToTermAsUri(TermServiceInterface $term)
    {
        return (! in_array($term->getTaxonomy()->getName(), (array) $this->getOption('parent')['type'])) ? $this->_getPathToTermAsUri($term->getParent()) . $term->getSlug() . '/' : '';
    }
}