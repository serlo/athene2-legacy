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

class EntityService implements EntityServiceInterface
{
    use\Zend\ServiceManager\ServiceLocatorAwareTrait,\Common\Traits\ObjectManagerAwareTrait,\Entity\Plugin\PluginManagerAwareTrait,\Entity\Manager\EntityManagerAwareTrait,\Common\Traits\EntityDelegatorTrait, \Taxonomy\Manager\SharedTaxonomyManagerTrait;
    
    public function getTerms()
    {
        return new TermCollection($this->getEntity()->get('terms'), $this->getSharedTaxonomyManager());
    }
    
    public function persist(){
        
        $this->getObjectManager()->persist($this->getEntity());
    }
    
    public function flush(){
        $this->getObjectManager()->flush($this->getEntity());
        return $this;
    }
    
    public function persistAndFlush(){
        $this->persist();
        $this->flush();
        return $this;
    }

    public function getId()
    {
        return $this->getEntity()->getId();
    }

    public function refresh()
    {
        if ($this->getObjectManager()->isOpen()) {
            $this->getObjectManager()->refresh($this->getEntity());
        }
        return $this;
    }
    
    public function setOptions($options){
        $this->whitelistPlugins($options['plugins']);
    }

    protected $pluginWhitelist = array();

    protected $pluginOptions = array();

    public function isPluginWhitelisted($name)
    {
        return array_key_exists($name, $this->pluginWhitelist) && $this->pluginWhitelist[$name] === TRUE;
    }

    public function whitelistPlugins($config)
    {
        foreach ($config as $plugin) {
            $this->whitelistPlugin($plugin['name']);
            if (isset($plugin['options'])) {
                $this->setPluginOptions($plugin['name'], $plugin['options']);
            }
        }
    }

    public function setPluginOptions($name, $options)
    {
        if (isset($this->pluginOptions[$name])) {
            $options = array_merge_recursive($this->pluginOptions[$name], $options);
        }
        
        $this->pluginOptions[$name] = $options;
        return $this;
    }

    public function getPluginOptions($name)
    {
        return (array_key_exists($name, $this->pluginOptions)) ? $this->pluginOptions[$name] : array();
    }

    public function whitelistPlugin($name)
    {
        $this->pluginWhitelist[$name] = true;
        return $this;
    }

    /**
     * Get plugin instance
     *
     * @param string $name
     *            Name of plugin to return
     * @return mixed
     */
    public function plugin($name)
    {
        if (! $this->isPluginWhitelisted($name)) {
            throw new InvalidArgumentException(sprintf('Plugin %s is not whitelisted for this entity.', $name));
        }
        
        $pluginManager = $this->getPluginManager();
        
        $pluginManager->setEntityService($this);
        $pluginManager->setPluginOptions($this->getPluginOptions($name));
        
        return $this->getPluginManager()->get($name);
    }

    /**
     * Method overloading: return/call plugins
     *
     * If the plugin is a functor, call it, passing the parameters provided.
     * Otherwise, return the plugin instance.
     *
     * @param string $method            
     * @param array $params            
     * @return mixed
     */
    public function __call($method, $params)
    {
        if($this->isPluginWhitelisted($method)){
            $plugin = $this->plugin($method);
            if (is_callable($plugin)) {
                return call_user_func_array($plugin, $params);
            }
            return $plugin;
        } else {
            if(method_exists($this->getEntity(), $method)){
                return call_user_func_array(array(
                    $this->getEntity(),
                    $method
                ), $params);
            } else {
                throw new \Exception(sprintf('Method %s not defined.', $method));
            }
        }
    }
}