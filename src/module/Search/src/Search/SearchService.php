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
namespace Search;

use Doctrine\Common\Collections\ArrayCollection;

class SearchService implements SearchServiceInterface
{
    
    use \Common\Traits\ConfigAwareTrait, \Zend\ServiceManager\ServiceLocatorAwareTrait;
    
    protected function getDefaultConfig(){
        return array(
            'adapters' => array(
                'entity' => __NAMESPACE__ . '\Adapter\SphinxQL\EntityAdapter',
                'taxonomyTerm' => __NAMESPACE__ . '\Adapter\SphinxQL\TaxonomyTermAdapter'
            )
        );
    }
    
    public function search ($query, array $adapters)
    {
        $container = new ArrayCollection();
        $configAdapters =  $this->getOption('adapters');
        foreach($adapters as $adapter){
            if(!array_key_exists($adapter, $configAdapters))
                throw new Exception\RuntimeException(sprintf('Unkown adapter: %s', $adapter));
            
            $adapter = $configAdapters[$adapter];
            /* @var $adapter Adapter\AdapterInterface */
            $adapter = $this->getServiceLocator()->get($adapter);
            $container->add($adapter->search($query));
        }
        return $container;
    }    
}