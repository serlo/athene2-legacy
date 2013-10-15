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

use Zend\ServiceManager\AbstractPluginManager;
use Subject\Exception\InvalidArgumentException;

class PluginManager extends AbstractPluginManager implements PluginManagerInterface
{
    use \Entity\Service\EntityServiceAwareTrait;

    protected $pluginOptions;

    public function validatePlugin($plugin)
    {
        if ($plugin instanceof PluginInterface) {
            return true;
        }
        
        throw new InvalidArgumentException(sprintf('%s does not implement %s.', get_class($plugin), __NAMESPACE__ . '\PluginInterface'));
    }

    protected function getPluginOptions()
    {
        if (! $this->pluginOptions === NULL)
            throw new \Exception('Setup plugin data first!');
        
        return $this->pluginOptions;
    }

    public function setPluginOptions($options)
    {
        $this->pluginOptions = $options;
        return $this;
    }

    public function clear()
    {
        $this->entityService = NULL;
        $this->pluginOptions = NULL;
    }

    public function get($name, array $options = array(), $usePeeringServiceManagers = true)
    {
        $plugin = parent::get($name);
        $this->inject($plugin);
        return $plugin;
    }

    protected function inject(PluginInterface $plugin)
    {
        if (! $this->hasEntityService() || ! $this->pluginOptions === NULL)
            throw new \Exception('Setup plugin data first!');
        
        $plugin->setEntityService($this->getEntityService());
        $plugin->setConfig($this->getPluginOptions());
        $this->clear();
        
        return $plugin;
    }
}