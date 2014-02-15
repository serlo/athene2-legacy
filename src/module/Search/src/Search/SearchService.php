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
namespace Search;

class SearchService implements SearchServiceInterface
{
    use \Common\Traits\ConfigAwareTrait, \Zend\ServiceManager\ServiceLocatorAwareTrait, \Common\Traits\RouterAwareTrait;

    public function search($query, array $adapters)
    {
        $container      = new Result\Container();
        $configAdapters = $this->getOption('adapters');

        foreach ($adapters as $adapter) {
            if (!array_key_exists($adapter, $configAdapters)) {
                throw new Exception\RuntimeException(sprintf('Unkown adapter: %s', $adapter));
            }

            /* @var $adapter Adapter\AdapterInterface */
            $adapter = $this->getServiceLocator()->get($configAdapters[$adapter]);

            $adapter->search($query, $container);
        }

        return $container;
    }

    public function ajaxify(Result\ContainerInterface $container)
    {
        $return = [];
        $this->iterContainer($container, $return);

        return $return;
    }

    protected function iterContainer(Result\ContainerInterface $container, array & $return, $limit = 10)
    {
        $items = array();

        foreach ($container->getResults() as $result) {
            $url     = $this->getRouter()->assemble(
                $result->getRouteParams(),
                array(
                    'name' => $result->getRouteName()
                )
            );
            $item    = array(
                'title' => $result->getName(),
                'url'   => rawurldecode($url)
            );
            $items[] = $item;
        }

        if (!empty($items)) {
            $return[] = [
                'title' => $container->getName(),
                'items' => $items
            ];
        }

        if (count($return) > $limit) {
            return;
        }

        foreach ($container->getContainers() as $subContainer) {
            $this->iterContainer($subContainer, $return);
        }
    }

    protected function getDefaultConfig()
    {
        return array(
            'adapters' => array(
                'entity'       => __NAMESPACE__ . '\Adapter\SphinxQL\EntityAdapter',
                'taxonomyTerm' => __NAMESPACE__ . '\Adapter\SphinxQL\TaxonomyTermAdapter'
            )
        );
    }
}
