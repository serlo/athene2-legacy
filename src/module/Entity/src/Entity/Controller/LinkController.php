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
    use \Link\Service\LinkServiceAwareTrait,\Entity\Options\ModuleOptionsAwareTrait;

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
        $entity = $this->getEntity();
        $type = $this->params('type');
        
        $form = new MoveForm();
        
        if ($this->getRequest()->isPost()) {
            
            $form->setData($this->getRequest()
                ->getPost());
            
            if ($form->isValid()) {
                $data = $form->getData();
                
                $from = $this->getEntityManager()->getEntity($this->params('from'));
                $to = $this->getEntityManager()->getEntity($data['to']);
                
                $options = $this->getModuleOptions()
                    ->getType($from->getType()
                    ->getName())
                    ->getComponent($type);
                
                $this->getLinkService()->dissociate($from, $entity, $options);
                
                $options = $this->getModuleOptions()
                    ->getType($to->getType()
                    ->getName())
                    ->getComponent($type);
                
                $this->getLinkService()->associate($to, $entity, $options);
                
                $this->getEntityManager()
                    ->getObjectManager()
                    ->flush();
                
                $this->redirect()->toUrl($this->referer()
                    ->fromStorage());
            }
        } else {
            $this->referer()->store();
        }
        
        $view = new ViewModel(array(
            'form' => $form
        ));
        $view->setTemplate('entity/link/move');
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