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
namespace LearningResource\Plugin\Link\Controller;

use Entity\Plugin\Controller\AbstractController;
use LearningResource\Plugin\Link\Form\MoveForm;
use Zend\View\Model\ViewModel;

class LinkController extends AbstractController
{

    public function orderChildrenAction()
    {
        $entity = $this->getEntityService();
        foreach ($entity->getScopesForPlugin('link') as $scope) {
            if ($scope == $this->params('scope')) {
                $data = $this->params()->fromPost()['sortable'];
                $entity->plugin($scope)->orderChildren($data);
            }
        }
        $this->getEntityManager()
            ->getObjectManager()
            ->flush();
        return '';
    }

    public function moveAction()
    {
        $entity = $this->getEntityService();
        $scope = $this->params('scope');
        $form = new MoveForm();
        $form->setAttribute('action', $this->url()
            ->fromRoute('entity/plugin/link/move', array(
            'scope' => $this->params('scope'),
            'entity' => $this->params('entity')
        )) . '?ref=' . $this->referer()
            ->toUrl());
        
        $form->setData(array(
            'from' => $this->params()
                ->fromPost('from', NULL) !== NULL ? $this->params()
                ->fromPost('from') : $this->params()
                ->fromRoute('from', NULL)
        ));
        
        if ($this->getRequest()->isPost()) {
            
            $form->setData($this->getRequest()
                ->getPost());
            
            if ($form->isValid()) {
                $data = $form->getData();
                $from = $this->getEntityManager()->getEntity($data['from']);
                $to = $this->getEntityManager()->getEntity($data['to']);
                
                $entity->plugin($scope)->remove($from);
                $entity->plugin($scope)->add($to);
                
                $this->getEntityManager()
                    ->getObjectManager()
                    ->flush();
                
                $this->redirect()->toUrl($this->params()->fromQuery('ref', '/'));
            }
        }
        
        $view = new ViewModel(array(
            'form' => $form
        ));
        $view->setTemplate('learning-resource/plugin/link/move');
        $this->layout('layout/1-col');
        return $view;
    }
}