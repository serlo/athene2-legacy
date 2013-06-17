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
namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class SideNavigationController extends AbstractActionController
{
    protected $items = array();
    
    protected function inject($content){
        $view = new ViewModel(
            array('items' => $this->items)
        );
        $view->addChild('content', $content);
        return $view;
    }
    
    protected function addItem($item){
        $this->items[] = $item;
        return $this;
    }
    
    public function dispatch(\Zend\Stdlib\RequestInterface $request, \Zend\Stdlib\ResponseInterface $response){

        return parent::dispatch($request, $response);
    }
}