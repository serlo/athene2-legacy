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
namespace Entity\Options;

use Zend\Stdlib\AbstractOptions;
use Entity\Exception;

class ModuleOptions extends AbstractOptions
{
    /**
     * 
     * @var array
     */
    protected $types = [];
    
    /**
     * 
     * @param unknown $types
     * @return \Entity\Options\ModuleOptions
     */
	public function setTypes($types)
    {
        $this->types = $types;
        return $this;
    }

    public function getType($type)
    {
    	if(array_key_exists($type, $this->types)){
    	    throw new Exception\RuntimeException(sprintf('Type "%s" not found.', $type));
    	}
    	
    	$options = $this->types[$type];
    	
    	if(!$options instanceof EntityOptions){
    	    $options = new EntityOptions($options);
    	    $this->types[$type] = $options;
    	}
    	
    	return $options;
    }
}