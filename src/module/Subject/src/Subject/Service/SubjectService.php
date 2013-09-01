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

use Doctrine\Common\Collections\Criteria;
use Subject\Exception\PluginNotFoundException;

class SubjectService implements SubjectServiceInterface
{
    use \Zend\ServiceManager\ServiceLocatorAwareTrait,\Entity\Manager\EntityManagerAwareTrait,\Subject\Manager\SubjectManagerAwareTrait,\Common\Traits\EntityDelegatorTrait,\Subject\Entity\SubjectDelegatorTrait,\Subject\Plugin\PluginManagerAwareTrait,\Taxonomy\Manager\SharedTaxonomyManagerAwareTrait;

    public function getId ()
    {
        return $this->getEntity()->getId();
    }

    public function getSubjectEntity ()
    {
        return $this->getEntity();
    }

    public function getSlug ()
    {
        return $this->getEntity()->getSlug();
    }

    public function getName ()
    {
        return $this->getEntity()->getName();
    }
    
    public function getTermService(){
        return $this->getSharedTaxonomyManager()->getTerm($this->getEntity()->getId());
    }

    public function setOptions ($options)
    {
        $this->whitelistPlugins($options['plugins']);
    }

    protected $pluginWhitelist = array();

    protected $pluginOptions = array();

    public function isPluginWhitelisted ($name)
    {
        return isset($this->pluginWhitelist[$name]) && $this->pluginWhitelist[$name] === TRUE;
    }

    public function whitelistPlugins ($config)
    {
        foreach ($config as $plugin) {
            $this->whitelistPlugin($plugin['name']);
            if (isset($plugin['options'])) {
                $this->setPluginOptions($plugin['name'], $plugin['options']);
            }
        }
    }

    public function setPluginOptions ($name, $options)
    {
        if (isset($this->pluginOptions[$name])) {
            $options = array_merge_recursive($this->pluginOptions[$name], $options);
        }
        
        $this->pluginOptions[$name] = $options;
        return $this;
    }

    public function getPluginOptions ($name)
    {
        return (isset($this->pluginOptions[$name])) ? $this->pluginOptions[$name] : array();
    }

    public function whitelistPlugin ($name)
    {
        $this->pluginWhitelist[$name] = true;
        return $this;
    }

    /**
     * Get
     * plugin
     * instance
     *
     * @param string $name
     *            Name
     *            of
     *            plugin
     *            to
     *            return
     * @return mixed
     */
    public function plugin ($name)
    {
        if (! $this->isPluginWhitelisted($name)) {
            throw new PluginNotFoundException(sprintf('Plugin %s is not whitelisted for this entity.', $name));
        }
        
        $pluginManager = $this->getPluginManager();
        
        $pluginManager->setSubjectService($this);
        $pluginManager->setPluginOptions($this->getPluginOptions($name));
        
        return $this->getPluginManager()->get($name);
    }

    /**
     * Method
     * overloading:
     * return/call
     * plugins
     *
     * If
     * the
     * plugin
     * is
     * a
     * functor,
     * call
     * it,
     * passing
     * the
     * parameters
     * provided.
     * Otherwise,
     * return
     * the
     * plugin
     * instance.
     *
     * @param string $method            
     * @param array $params            
     * @return mixed
     */
    public function __call ($method, $params)
    {
        $plugin = $this->plugin($method);
        if (is_callable($plugin)) {
            return call_user_func_array($plugin, $params);
        }
        
        return $plugin;
    }
}