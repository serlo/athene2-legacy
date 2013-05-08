<?php
namespace Navigation\Provider;

use Zend\ServiceManager\ServiceLocatorInterface;
use Taxonomy\TaxonomyManagerInterface;
use Doctrine\ORM\EntityManager;
use Taxonomy\Service\TermServiceInterface;
use Doctrine\Common\Collections\Criteria;


class TaxonomyProvider implements ProviderInterface
{
    /**
     * 
     * @var ServiceLocatorInterface
     */
    protected $_serviceLocator;
    
    /**
     * 
     * @var EntityManager
     */
    protected $_entityManager;
    
    /**
     * 
     * @var TaxonomyManagerInterface
     */
    protected $_taxonomyManager;
    
    /**
     * 
     * @var array
     */
    protected $_defaultOptions = array(
        'name' => 'math:topic',
        'route' => 'math/topic'
    );
    
    protected $_options;
    
    public function __construct(array $options, ServiceLocatorInterface $serviceLocator){
        $this->_options = array_merge($this->_defaultOptions, $options);
        $this->_serviceLocator = $serviceLocator;
        $this->_entityManager = $serviceLocator->get('EntityManager');
        $this->_taxonomyManager = $serviceLocator->get('Taxonomy\SharedTaxonomyManager')->get($this->_options['name']);
    }
    
    public function provideArray($maxDepth = 3){
        $criteria = Criteria::create()
            ->where(Criteria::expr()->eq("parent", NULL))
            ->setFirstResult(0)
            ->setMaxResults(20);
        $terms = $this->_taxonomyManager->getTerms();
        $return = $this->_iterTerms($terms, $maxDepth);
        return $return;
    }
    
    protected function _iterTerms($terms, $depth){
        if($depth == 0)
            return array();
        
        $return = array();
        foreach($terms as $term){   
            $current = array();
            $current['route'] = $this->_options['route'];
            $current['slug'] = $term->getSlug();
            $current['label'] = $term->getName();
            $children = $term->getChildren();
            if(count($children)){
                //$current['pages'] = $this->_iterTerms($children, $depth - 1);
            }
            $return[] = $current;
        }
        return $return;
    }
}