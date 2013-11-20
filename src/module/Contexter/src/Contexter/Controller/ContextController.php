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
    use\Contexter\ContexterAwareTrait,\Contexter\Router\RouterAwareTrait,\Uuid\Manager\UuidManagerAwareTrait;

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
        $uri = $this->params()->fromQuery('uri');
        if ($uri === NULL) {
            $this->redirect()->toRoute('contexter/select-uri');
            return false;
        } else {
            $routeMatch = $this->getRouter()->matchUri($uri);
            $this->getRouter()->setRouteMatch($routeMatch);
            $types = $this->getContexter()->findAllTypeNames();
            $parameters = $this->getRouter()
                ->getAdapter()
                ->getParameters();
            $form = new ContextForm($parameters, $types->toArray());
            $form->setData(array(
                'route' => $routeMatch->getMatchedRouteName()
            ));
            if ($this->getRequest()->isPost()) {
                $form->setData($this->getRequest()
                    ->getPost());
                if ($form->isValid()) {
                    $data = $form->getData();
                    
                    $useParameters = array();
                    foreach ($data['parameters'] as $key => $value) {
                        if ($value === '1' && array_key_exists($key, $parameters)) {
                            $useParameters[$key] = $parameters[$key];
                        }
                    }
                    $object = $this->getUuidManager()->getUuid($data['object']);
                    $context = $this->getContexter()->add($object, $data['type'], $data['title']);
                    $context->addRoute($data['route'], $useParameters);
                    $this->getContexter()
                        ->getObjectManager()
                        ->flush();
                    $this->redirect()->toUrl($uri);
                    return FALSE;
                }
            }
        }
        $view = new ViewModel(array(
            'form' => $form
        ));
        $view->setTemplate('contexter/add/form');
        return $view;
    }

    public function selectUriAction()
    {
        $form = new UrlForm();
        $view = new ViewModel(array(
            'form' => $form
        ));
        if ($this->getRequest()->isPost()) {
            $form->setData($this->getRequest()
                ->getPost());
            if ($form->isValid()) {
                $data = $form->getData();
                $url = rawurldecode($this->url()->fromRoute('contexter/add', array())) . '?uri=' . $data['uri'];
                $this->redirect()->toUrl($url);
                return false;
            }
        }
        $view->setTemplate('contexter/add/url-form');
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

    public function removeAction()
    {
        $id = $this->params('id');
        $this->getContexter()->removeContext((int) $id);
        $this->getContexter()
            ->getObjectManager()
            ->flush();
        $this->redirect()->toReferer();
        return false;
    }

    public function removeRouteAction()
    {
        $id = $this->params('id');
        $this->getContexter()->removeRoute((int) $id);
        $this->getContexter()
            ->getObjectManager()
            ->flush();
        $this->redirect()->toReferer();
        return false;
    }
}