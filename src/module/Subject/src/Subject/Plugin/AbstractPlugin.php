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
namespace Subject\Plugin;

use Subject\Exception\RuntimeException;

abstract class AbstractPlugin implements PluginInterface
{
    use \Subject\Service\SubjectServiceAwareTrait, \Common\Traits\ConfigAwareTrait;
    
    protected $name, $scope;

    /**
     * @var string
     */
    protected $identity;
    
    /**
     * @var array
     */
    protected $options;
    
    /**
     * @return field_type $name
     */
    public function getName ()
    {
        return $this->name;
    }

	/**
     * @return field_type $scope
     */
    public function getScope ()
    {
        return $this->scope;
    }

	/**
     * @param field_type $name
     * @return $this
     */
    public function setName ($name)
    {
        $this->name = $name;
        return $this;
    }

	/**
     * @param field_type $scope
     * @return $this
     */
    public function setScope ($scope)
    {
        $this->scope = $scope;
        return $this;
    }

	/**
     * @return string $identity
     */
    public function getIdentity()
    {
        return $this->identity;
    }

	/**
     * @param string $identity
     * @return $this
     */
    public function setIdentity($identity)
    {
        $this->identity = $identity;
        return $this;
    }
    
    public function getTemplate($template){
        if(!array_key_exists($template, $this->getOption('templates')))
            throw new RuntimeException(sprintf('Template `%s` not found.', $template));
        
        return $this->getOption('templates')[$template];
    }

    public function getRoute(){
        if(!$this->getOption('route'))
            throw new RuntimeException(sprintf('Route to plugin not found.'));
    
        return $this->getOption('route');
    }
}