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

use Search\Adapter\AdapterInterface;
use Search\Options\ModuleOptions;
use Search\Provider\ProviderPluginManager;

class SearchService implements SearchServiceInterface
{
    /**
     * @var Adapter\AdapterInterface
     */
    protected $adapter;

    /**
     * @var Provider\ProviderPluginManager
     */
    protected $providerPluginManager;

    /**
     * @var Options\ModuleOptions
     */
    protected $moduleOptions;

    /**
     * @param AdapterInterface      $adapter
     * @param ModuleOptions         $moduleOptions
     * @param ProviderPluginManager $providerPluginManager
     */
    public function __construct(
        AdapterInterface $adapter,
        ModuleOptions $moduleOptions,
        ProviderPluginManager $providerPluginManager
    ) {
        $this->adapter               = $adapter;
        $this->moduleOptions         = $moduleOptions;
        $this->providerPluginManager = $providerPluginManager;
    }

    /**
     * {@inheritDoc}
     */
    public function add($id, $title, $content, $type, $link, array $keywords, $instance = null)
    {
        //var_dump([$id, $title, $content, $type, $link, $keywords, $instance]);
        $this->adapter->add($id, $title, $content, $type, $link, $keywords, $instance);
    }

    /**
     * {@inheritDoc}
     */
    public function delete($id)
    {
        $this->adapter->delete($id);
    }

    /**
     * {@inheritDoc}
     */
    public function erase()
    {
        $this->adapter->erase();
    }

    /**
     * {@inheritDoc}
     */
    public function flush()
    {
        $this->adapter->flush();
    }

    /**
     * {@inheritDoc}
     */
    public function rebuild()
    {
        $this->erase();
        $providers = $this->moduleOptions->getProviders();
        foreach ($providers as $provider) {
            /* @var $provider Provider\ProviderInterface */
            $provider = $this->providerPluginManager->get($provider);
            $results  = $provider->provide()->getResults();
            foreach ($results as $result) {
                $this->add(
                    $result->getId(),
                    $result->getTitle(),
                    $result->getContent(),
                    $result->getType(),
                    $result->getLink(),
                    $result->getKeywords()
                );
            }
        }
    }

    /**
     * {@inheritDoc}
     */
    public function search($query, $limit = 20)
    {
        return $this->adapter->search($query, $limit);
    }
}
