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
namespace Navigation\Controller;

use Zend\View\Model\ViewModel;

class SideNavigationComponent
{
    private $items = array();

    private function _inject (ViewModel $content)
    {
        $view = new ViewModel(array(
            'items' => $this->items
        ));
        $view->setTemplate('navigation/sidenav/sidenav');
        $view->addChild($content, 'content');
        return $view;
    }

    public function addItem ($active, $href, $content, array $children = array())
    {
        $item = array();
        $item['active'] = $active;
        $item['href'] = $href;
        $item['content'] = $content;
        $item['children'] = $children;
        $this->items[] = $item;
        return $this;
    }

    public function inject ($view, \Zend\Mvc\MvcEvent $e)
    {
        $view = $this->_inject($view);
        $e->setResult($view);
        return $view;
    }
}