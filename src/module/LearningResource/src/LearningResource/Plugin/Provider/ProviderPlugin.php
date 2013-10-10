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
namespace LearningResource\Plugin\Provider;

use Entity\Plugin\AbstractPlugin;

class ProviderPlugin extends AbstractPlugin
{
    protected function getDefaultConfig(){
        return array(
            'fields' => array(
            ),
        );
    }
    
    public function get($field){
        if(!array_key_exists($field, $this->getOption('fields')))
            throw new \Entity\Exception\RuntimeException(sprintf('No configuration found for field %s', $field));

        $callback = $this->getOption('fields')[$field];        
        return $callback($this->getEntityService());
    }
}