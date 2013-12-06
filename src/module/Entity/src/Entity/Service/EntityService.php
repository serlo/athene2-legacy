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
namespace Entity\Service;

use Entity\Exception\InvalidArgumentException;
use Taxonomy\Collection\TermCollection;
use Zend\Stdlib\ArrayUtils;
use Common\Normalize\Normalizable;
use Common\Normalize\Normalized;

class EntityService implements EntityServiceInterface
{
    use\Zend\ServiceManager\ServiceLocatorAwareTrait,\Entity\Plugin\PluginManagerAwareTrait,\Entity\Manager\EntityManagerAwareTrait,\Common\Traits\EntityDelegatorTrait,\Zend\EventManager\EventManagerAwareTrait,\Taxonomy\Manager\SharedTaxonomyManagerAwareTrait;

    protected $whitelistedPlugins = array();

    protected $pluginOptions = array();

    public function normalize()
    {
        $normalized = new Normalized();
        $normalized->setRouteName('entity/plugin/page');
        $normalized->setRouteParams(array(
            'entity' => $this->getId()
        ));
        $normalized->setTimestamp($this->getTimestamp());
        
        $normalized->setTitle($this->getUuid());
        
        // repository
        if ($this->hasPlugin('repository') && ($this->repository()->hasCurrentRevision() || $this->repository()->hasHead())) {
            if ($this->repository()->hasCurrentRevision()) {
                $revision = $this->repository()->getCurrentRevision();
            } elseif ($this->repository()->hasHead()) {
                $revision = $this->repository()->getHead();
            }
            if ($revision->get('title')) {
                $normalized->setTitle($revision->get('title'));
            }
            if ($revision->get('content')) {
                $normalized->setPreview(substr($revision->get('content'), 0, 200) . '...');
            }
            if ($revision->get('content')) {
                $normalized->setContent($revision->get('content'));
            }
        }
        
        $normalized->setType($this->getEntity()
            ->getType()
            ->getName());
        
        return $normalized;
    }

    public function getTerms()
    {
        return new TermCollection($this->getEntity()->get('terms'), $this->getSharedTaxonomyManager());
    }

    public function getTimestamp()
    {
        return $this->getEntity()->getTimestamp();
    }

    public function getUuid()
    {
        return $this->getEntity()->getUuid();
    }

    public function getType()
    {
        return $this->getEntity()->getType();
    }

    public function setTrashed($voided)
    {
        $this->getEntity()->setTrashed($voided);
        $this->getEntityManager()
            ->getObjectManager()
            ->persist($this->getEntity());
        return $this;
    }

    public function getId()
    {
        return $this->getEntity()->getId();
    }

    public function setConfig(array $config)
    {
        $this->whitelistPlugins($config['plugins']);
        return $this;
    }

    public function hasPlugin($name)
    {
        return $this->isPluginWhitelisted($name);
    }

    public function isPluginWhitelisted($name)
    {
        return array_key_exists($name, $this->whitelistedPlugins) && $this->whitelistedPlugins[$name] !== FALSE;
    }

    public function getScopesForPlugin($plugin)
    {
        $return = array();
        foreach ($this->pluginOptions as $scope => $options) {
            if ($options['plugin'] == $plugin) {
                $return[] = $scope;
            }
        }
        return $return;
    }

    public function whitelistPlugins(array $config)
    {
        foreach ($config as $name => $data) {
            $this->whitelistPlugin($name, $data['plugin']);
            $this->setPluginOptions($name, $data);
        }
    }

    public function setPluginOptions($name, array $options)
    {
        if (isset($this->pluginOptions[$name])) {
            $options = ArrayUtils::merge($this->pluginOptions[$name], $options);
        }
        
        $this->pluginOptions[$name] = $options;
        return $this;
    }

    public function getPluginOptions($name)
    {
        return (array_key_exists($name, $this->pluginOptions) && array_key_exists('options', $this->pluginOptions[$name])) ? $this->pluginOptions[$name]['options'] : array();
    }

    public function whitelistPlugin($name, $plugin)
    {
        $this->whitelistedPlugins[$name] = $plugin;
        return $this;
    }

    public function getPlugin($name)
    {
        return $this->whitelistedPlugins[$name];
    }

    /**
     * Get plugin instance
     *
     * @param string $scope
     *            Name of plugin to return
     * @return mixed
     */
    public function plugin($scope)
    {
        if (! $this->isPluginWhitelisted($scope)) {
            throw new InvalidArgumentException(sprintf('Plugin %s is not whitelisted for this entity.', $scope));
        }
        
        $pluginManager = $this->getPluginManager();
        
        $pluginManager->setEntityService($this);
        $pluginManager->setPluginOptions($this->getPluginOptions($scope));
        
        $plugin = $this->getPluginManager()->get($this->getPlugin($scope));
        $plugin->setScope($scope);
        return $plugin;
    }

    /**
     * Method overloading: return/call plugins
     * If the plugin is a functor, call it, passing the parameters provided.
     * Otherwise, return the plugin instance.
     *
     * @param string $method            
     * @param array $params            
     * @return mixed
     */
    public function __call($method, $params)
    {
        if ($this->isPluginWhitelisted($method)) {
            $plugin = $this->plugin($method);
            if (is_callable($plugin)) {
                return call_user_func_array($plugin, $params);
            }
            return $plugin;
        } else {
            if (method_exists($this->getEntity(), $method)) {
                return call_user_func_array(array(
                    $this->getEntity(),
                    $method
                ), $params);
            } else {
                throw new \Exception(sprintf('Method %s not defined.', $method));
            }
        }
    }

    public function getTrashed()
    {
        return $this->getEntity()->getTrashed();
    }
}