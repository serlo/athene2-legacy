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

class SearchService implements SearchServiceInterface
{
    use \Common\Traits\ConfigAwareTrait,\Zend\ServiceManager\ServiceLocatorAwareTrait,\Common\Traits\RouterAwareTrait;

    protected function getDefaultConfig()
    {
        return array(
            'adapters' => array(
                'entity' => __NAMESPACE__ . '\Adapter\SphinxQL\EntityAdapter',
                'taxonomyTerm' => __NAMESPACE__ . '\Adapter\SphinxQL\TaxonomyTermAdapter'
            )
        );
    }

    public function search($query, array $adapters)
    {
        $container = new Result\Results();
        $configAdapters = $this->getOption('adapters');
        foreach ($adapters as $adapter) {
            if (! array_key_exists($adapter, $configAdapters))
                throw new Exception\RuntimeException(sprintf('Unkown adapter: %s', $adapter));
            
            $adapter = $configAdapters[$adapter];
            /* @var $adapter Adapter\AdapterInterface */
            $adapter = $this->getServiceLocator()->get($adapter);
            $container->add($adapter->search($query));
        }
        return $container;
    }

    public function simplifyResults(Result\Results $results)
    {
        $return = array();
        
        /* @var $result Result\ContainerInterface */
        foreach ($results as $container) {
            $this->iterContainer($container, $return);
        }
        
        return $return;
    }

    protected function iterContainer(Result\ContainerInterface $container, array & $return)
    {
        $items = array();
        
        foreach ($container->getResults() as $result) {
            $url = $this->getRouter()->assemble($result->getRouteParams(), array(
                'name' => $result->getRouteName()
            ));
            $item = array(
                'title' => $result->getName(),
                'url' => $url
            );
            $items[] = $item;
        }
        
        $return[] = array(
            'title' => $container->getName(),
            'items' => $items
        );
        
        foreach ($container->getContainers() as $subContainer) {
            $this->iterContainer($subContainer, $return);
        }
        
        return $this;
    }
}