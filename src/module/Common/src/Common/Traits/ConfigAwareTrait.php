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

trait ConfigAwareTrait
{
    protected $config = array();
    
	/**
     * @return field_type $config
     */
    public function getConfig ()
    {
        return $this->config;
    }

	/**
     * @param field_type $config
     * @return $this
     */
    public function setConfig ($config)
    {
        $this->config = array_replace_recursive($this->config, $config);
        return $this;
    }

    public function getOption($key){
        if(array_key_exists($key, $this->getConfig())){
            return $this->getConfig()[$key];
        } else {
            return NULL;
        }
    }
}