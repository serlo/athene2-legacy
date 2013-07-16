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
namespace Entity\Plugin;

trait PluginManagerAware
{

    /**
     *
     * @var PluginManagerInterface
     */
    protected $pluginManager;

    /**
     *
     * @return PluginManagerInterface $pluginManager
     */
    public function getPluginManager()
    {
        return $this->pluginManager;
    }

    /**
     *
     * @param PluginManagerInterface $pluginManager            
     * @return $this
     */
    public function setPluginManager(PluginManagerInterface $pluginManager)
    {
        $this->pluginManager = $pluginManager;
        return $this;
    }
}