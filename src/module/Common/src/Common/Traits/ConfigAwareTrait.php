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
namespace Common\Traits;

use Zend\Stdlib\ArrayUtils;

trait ConfigAwareTrait
{

    abstract protected function getDefaultConfig();

    protected $config = array();

    /**
     *
     * @return field_type $config
     */
    public function getConfig()
    {
        return $this->config;
    }

    /**
     *
     * @param field_type $config            
     * @return $this
     */
    public function setConfig(array $config)
    {
        $this->config = ArrayUtils::merge($this->getDefaultConfig(), $config);
        
        $array = array(
            $this->getDefaultConfig(),
            $config,
            $this->config
        );
        
        return $this;
    }

    public function appendConfig(array $config)
    {
        $this->config = ArrayUtils::merge($this->config, $config);
        return $this;
    }

    public function getOption($key)
    {
        if (array_key_exists($key, $this->getConfig())) {
            return $this->getConfig()[$key];
        } else {
            return NULL;
        }
    }
}