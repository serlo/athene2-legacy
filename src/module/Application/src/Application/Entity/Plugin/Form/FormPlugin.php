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
namespace Application\Entity\Plugin\Form;

use Entity\Plugin\AbstractPlugin;

class FormPlugin extends AbstractPlugin
{
    protected $forms;
    
    public function __invoke()
    {
	    $form = $this->getOption('class');
	    
	    if(array_key_exists($form, $this->forms)){
	        return $this->forms[$form];
	    }
	    
	    $instance = new $form();
	    $this->forms[$form] = $instance;
	    
		return $instance;
    }
}