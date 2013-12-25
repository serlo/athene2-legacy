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
namespace Entity\Controller;

use Entity\Form\MoveForm;
use Zend\View\Model\ViewModel;

class LinkController extends AbstractController
{
    use \Link\Service\LinkServiceAwareTrait;

    public function orderChildrenAction()
    {
        $entity = $this->getEntity();
        
        if ($this->getRequest()->isPost()) {
            $scope = $this->params('type');
            $data = $this->params()->fromPost()['sortable'];
            
            $data = $this->prepareDataForOrdering($data);
            
            $this->getLinkService()->sortChildren($entity, $scope, $data);
            
            $this->getEntityManager()->flush();
        }
        
        return false;
    }

    public function moveAction()
    {
        $entity = $this->getEntityService();
        $scope = $this->params('scope');
        $form = new MoveForm();
        
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
                
                $this->redirect()->toUrl($this->referer()
                    ->fromStorage());
            }
        }
        $this->referer()->store();
        
        $view = new ViewModel(array(
            'form' => $form
        ));
        $view->setTemplate('entity/plugin/link/move');
        $this->layout('layout/1-col');
        return $view;
    }

    protected function prepareDataForOrdering($data)
    {
        $return = [];
        foreach ($data as $child) {
            $return[] = $child['id'];
        }
        return $return;
    }
}