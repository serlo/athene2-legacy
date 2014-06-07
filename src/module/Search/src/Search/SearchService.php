<?php
/**
 * Athene2 - Advanced Learning Resources Manager
 *
 * @author      Aeneas Rekkas (aeneas.rekkas@serlo.org)
 * @license     LGPL-3.0
 * @license     http://opensource.org/licenses/LGPL-3.0 The GNU Lesser General Public License, version 3.0
 * @link        https://github.com/serlo-org/athene2 for the canonical source repository
 */
namespace Search;

use Zend\I18n\Translator\Translator;
use Zend\Mvc\Router\RouteInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class SearchService implements SearchServiceInterface
{
    use \Common\Traits\ConfigAwareTrait;

    /**
     * @var ServiceLocatorInterface
     */
    protected $serviceLocator;

    /**
     * @var RouteInterface
     */
    protected $router;

    /**
     * @var Translator
     */
    protected $translator;

    public function __construct(Translator $translator, RouteInterface $router, ServiceLocatorInterface $serviceLocator)
    {
        $this->router         = $router;
        $this->serviceLocator = $serviceLocator;
        $this->translator     = $translator;
    }

    public function ajaxify(Result\ContainerInterface $container)
    {
        $return = [];
        $this->iterContainer($container, $return);

        return $return;
    }

    public function search($query, array $adapters)
    {
        $container      = new Result\Container();
        $configAdapters = $this->getOption('adapters');

        foreach ($adapters as $adapterName) {
            if (!array_key_exists($adapterName, $configAdapters)) {
                throw new Exception\RuntimeException(sprintf('Unkown adapter: %s', $adapterName));
            }

            /* @var $adapterName Adapter\AdapterInterface */
            $adapterName = $this->serviceLocator->get($configAdapters[$adapterName]);

            $adapterName->search($query, $container);
        }

        return $container;
    }

    protected function getDefaultConfig()
    {
        return [
            'adapters' => [
                'entity'       => __NAMESPACE__ . '\Adapter\SphinxQL\EntityAdapter',
                'taxonomyTerm' => __NAMESPACE__ . '\Adapter\SphinxQL\TaxonomyTermAdapter'
            ]
        ];
    }

    protected function iterContainer(Result\ContainerInterface $container, array & $return, $limit = 10)
    {
        $items = [];

        foreach ($container->getResults() as $result) {
            $url     = $this->router->assemble(
                $result->getRouteParams(),
                [
                    'name' => $result->getRouteName()
                ]
            );
            $item    = [
                'title' => $result->getName(),
                'url'   => $url
            ];
            $items[] = $item;
        }

        if (!empty($items)) {
            $return[] = [
                'title' => $this->translator->translate($container->getName()),
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
}
