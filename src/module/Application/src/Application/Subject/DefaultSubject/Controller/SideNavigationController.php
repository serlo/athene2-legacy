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
namespace Application\Subject\DefaultSubject\Controller;

use Zend\View\Model\ViewModel;

class SideNavigationController extends AbstractController
{
    protected $items = array();
    
    protected function inject($template){
        $view = new ViewModel(
            array('items' => $this->items)
        );
        $view->setTemplate('layout/sidenav/sidenav');
        $view->addChild($template, 'content');
        return $view;
    }
    
    protected function addItem($active, $href, $content, array $children = array()){
        $item = array();
        $item['active'] = $active;
        $item['href'] = $href;
        $item['content'] = $content;
        $item['children'] = $children;        
        $this->items[] = $item;
        return $this;
    }
    
    public function onDispatch(\Zend\Mvc\MvcEvent $e){
        //$e = $e->setViewModel($this->inject($e->getViewModel()));
        //$e->setResult('');
        return parent::onDispatch($e);
    }
}