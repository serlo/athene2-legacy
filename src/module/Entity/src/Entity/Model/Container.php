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
namespace Entity\Model;

class Container implements EntityModel {	
	
	/**
	 * @var Form
	 */
	protected $form;
	
	public function getForm ()
	{
		if (! $this->hasForm()) {
			$form = $this->getFormObject();
			$form->setData($this->getFormData());
			$this->setForm($form);
		}
	
		return $this->form;
	}
	
	public function hasForm ()
	{
		return $this->form !== NULL;
	}
	
    /**
     * @var array
     */
    protected $fields = array();
    
    /**
     * 
     * @var int
     */
    protected $index;
    
    public function addFields(array $fields){
    	foreach($fields as $name => $values){
    		$options = isset($values['options']) ? $values['options'] : NULL;
    		$this->addField($name, $values['provider'], $options);
    	}
    	return $this;
    }
    
    public function addField($name, $provider, array $options = array()){
    	$provider = new $provider();    	
    	$field = array();
    	$field['name'] = $name;
    	
    	
    	$this->fields[$this->index++] = $field;
    	return $this;
    }
}