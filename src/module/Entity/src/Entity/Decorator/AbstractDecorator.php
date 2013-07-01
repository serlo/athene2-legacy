<?php
/**
 * 
 * @author Aeneas Rekkas (aeneas.rekkas@serlo.org)
 * @copyright 2013 by www.serlo.org
 * @license LGPL
 * @license http://www.gnu.org/copyleft/lesser.html GNU Lesser General Public License (LGPL)
 */
namespace Entity\Decorator;

use Zend\Form\Form;
use Core\Decorator\GraphDecorator;

abstract class AbstractDecorator extends GraphDecorator
{	
	/**
	 * @var Form
	 */
    protected $form;

    public function setForm (Form $form = NULL)
    {
    	if(!$form) $form = new Form();
        $this->form = $form;
        return $this;
    }
    
    public function getForm(){
    	return $this->form;
    } 
}