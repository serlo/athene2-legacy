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
namespace Contexter\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Contexter\Form\ContextForm;
use Contexter\Form\UrlForm;

class ContextController extends AbstractActionController
{
    use \Contexter\ContexterAwareTrait,\Contexter\Router\RouterAwareTrait;

    public function manageAction()
    {
        $elements = $this->getContexter()->findAll();
        $view = new ViewModel(array(
            'elements' => $elements
        ));
        $view->setTemplate('contexter/manage');
        return $view;
    }

    public function addAction()
    {
        $url = $this->params('url', NULL);
        $parameters = $this->getRouter()->getRouteParams();
        if ($url === NULL) {
            $form = new UrlForm();
            $template = 'contexter/add/url-form';
        } else {
            $form = new ContextForm($parameters);
            $template = 'contexter/add/form';
            if ($this->getRequest()->isPost()) {
                $form->setData($this->getRequest()
                    ->getPost());
                if ($form->isValid()) {
                	$data = $form->getData();
                    $type = $this->params('type', NULL);
                	$this->getContexter()->add($data['object'], $type, $data['title']);
                }
            }
        }
        $view = new ViewModel(array(
            'form' => $form
        ));
        $view->setTemplate($template);
        return $view;
    }

    public function updateAction()
    {
        $id = $this->params('id');
        $context = $this->getContexter()->getContext($id);
        $view = new ViewModel(array(
            'context' => $context
        ));
        $view->setTemplate('contexter/update');
        return $view;
    }
}