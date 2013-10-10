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

use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Stdlib\ArrayUtils;

class NavigationProvider implements \Ui\Navigation\ProviderInterface
{
    use \Zend\ServiceManager\ServiceLocatorAwareTrait,\Common\Traits\ObjectManagerAwareTrait,\Taxonomy\Service\TermServiceAwareTrait,\Language\Service\LanguageServiceAwareTrait; // , \Common\Traits\ConfigAwareTrait;
    
    /**
     *
     * @var array
     */
    protected $defaultOptions = array(
        'name' => 'default',
        'route' => 'default',
        'params' => array()
    );

    protected $options;

    public function __construct(array $options, ServiceLocatorInterface $serviceLocator)
    {
        $this->options = ArrayUtils::merge($this->defaultOptions, $options);
        $this->serviceLocator = $serviceLocator;
        $this->objectManager = $serviceLocator->get('EntityManager');
        $this->languageService = $serviceLocator->get('Language\Manager\LanguageManager')->findLanguageByCode($this->options['language']);
        $this->termService = $serviceLocator->get('Taxonomy\Manager\SharedTaxonomyManager')
            ->findTaxonomyByName($this->options['type'], $this->languageService)
            ->findTermByAncestors((array) $this->options['parent']);
    }

    public function provideArray($maxDepth = 1)
    {
        if ($this->objectManager->isOpen())
            $this->objectManager->refresh($this->termService->getEntity());
        
        $terms = $this->termService->getChildren();
        $return = $this->iterTerms($terms, $maxDepth);
        return $return;
    }

    protected function iterTerms($terms, $depth)
    {
        if ($depth == 0)
            return array();
        
        $return = array();
        foreach ($terms as $term) {
            $current = array();
            $current['route'] = $this->options['route'];
            
            $current['params'] = ArrayUtils::merge($this->options['params'], array(
                'path' => $term->getSlug()
            ));
            $current['label'] = $term->getName();
            $children = $term->getChildren();
            if (count($children)) {
                $current['pages'] = $this->iterTerms($children, $depth - 1);
            }
            $return[] = $current;
        }
        return $return;
    }
}