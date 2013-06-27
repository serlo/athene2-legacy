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

use Subject\Controller\AbstractController as AC;
use Zend\View\Model\ViewModel;
use Navigation\Controller\SideNavigationComponent;

abstract class AbstractController extends AC
{
    protected $viewPath = 'subject/math/';

    protected $subNavigationComponent;
    
    protected function getSubNavigationComponent(){
        if(!$this->subNavigationComponent){
            $this->subNavigationComponent = new SideNavigationComponent();
        }
        return $this->subNavigationComponent;
    }
    
    private $items = array();
    
    private function inject (ViewModel $content)
    {
        return $this->getSubNavigationComponent()->inject($content);
    }
    
    protected function addItem ($active, $href, $content, array $children = array())
    {
        return $this->getSubNavigationComponent()->addItem($active, $href, $content, $children);
    }
    
    public function onDispatch (\Zend\Mvc\MvcEvent $e)
    {
        $view = parent::onDispatch($e);
        return $this->getSubNavigationComponent()->inject($view, $e);
    }
}