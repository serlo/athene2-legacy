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
namespace Subject\Service;

use Subject\Exception\PluginNotFoundException;
use Subject\Entity\SubjectInterface;
use Zend\Stdlib\ArrayUtils;

class SubjectService implements SubjectServiceInterface, SubjectInterface
{
    use\Taxonomy\Service\TermServiceAwareTrait,\Common\Traits\ConfigAwareTrait,\Zend\ServiceManager\ServiceLocatorAwareTrait,\Entity\Manager\EntityManagerAwareTrait,\Subject\Manager\SubjectManagerAwareTrait,\Common\Traits\EntityDelegatorTrait,\Subject\Entity\SubjectDelegatorTrait,\Subject\Plugin\PluginManagerAwareTrait,\Taxonomy\Manager\SharedTaxonomyManagerAwareTrait;

    protected function getDefaultConfig()
    {
        return array(
            'plugins' => array()
        );
    }

    public function getLanguageService()
    {
        return $this->getTermService()->getLanguageService();
    }

    public function getId()
    {
        return $this->getEntity()->getId();
    }

    public function getSubjectEntity()
    {
        return $this->getEntity();
    }

    public function getSlug()
    {
        return $this->getEntity()->getSlug();
    }

    public function getName()
    {
        return $this->getEntity()->getName();
    }

    public function getTermService()
    {
        return $this->getSharedTaxonomyManager()->getTerm($this->getEntity()
            ->getId());
    }

    public function setConfig(array $config)
    {
        $this->config = ArrayUtils::merge($this->getDefaultConfig(), $config);
        $this->whitelistPlugins($config['plugins']);
        return $this;
    }

    protected $pluginWhitelist = array();

    protected $pluginOptions = array();

    public function isPluginWhitelisted($name)
    {
        return array_key_exists($name, $this->pluginWhitelist) && $this->pluginWhitelist[$name] !== FALSE;
    }

    public function whitelistPlugins(array $config)
    {
        foreach ($config as $plugin) {
            $this->whitelistPlugin($plugin['name'], $plugin['plugin']);
            if (isset($plugin['options'])) {
                $this->setPluginOptions($plugin['name'], $plugin['options']);
            }
        }
        return $this;
    }

    public function setPluginOptions($scope, array $options)
    {
        if (isset($this->pluginOptions[$scope])) {
            $options = ArrayUtils::merge($this->pluginOptions[$scope], $options);
        }
        
        $this->pluginOptions[$scope] = $options;
        return $this;
    }

    public function getPluginOptions($scope)
    {
        return (isset($this->pluginOptions[$scope])) ? $this->pluginOptions[$scope] : array();
    }

    public function whitelistPlugin($scope, $plugin)
    {
        $this->pluginWhitelist[$scope] = $plugin;
        return $this;
    }

    public function getPluginByScope($scope)
    {
        return $this->pluginWhitelist[$scope];
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
            throw new PluginNotFoundException(sprintf('Plugin %s is not whitelisted for this entity.', $scope));
        }
        
        $pluginManager = $this->getPluginManager();
        
        $pluginManager->setSubjectService($this);
        $pluginManager->setPluginOptions($this->getPluginOptions($scope));
        $pluginManager->setPluginIdentification($scope, $this->getPluginByScope($scope));
        
        return $this->getPluginManager()->get($this->getPluginByScope($scope));
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
        $plugin = $this->plugin($method);
        if (is_callable($plugin)) {
            return call_user_func_array($plugin, $params);
        }
        
        return $plugin;
    }
}