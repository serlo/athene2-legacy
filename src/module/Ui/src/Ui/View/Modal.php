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
namespace Ui\View;

use Zend\View\Helper\AbstractHelper;

class Modal extends AbstractHelper {
    protected $modals = array();
    
    protected $current;
    
    public function __invoke($key){
        if(!array_key_exists($key, $this->modals)){
            $this->modals[$key] = array();
        }
        $this->current = $key;
    }
    
    public function render(){
        return $this->getView()->render($nameOrModel);
    }
}