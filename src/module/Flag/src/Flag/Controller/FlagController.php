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
namespace Flag\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Flag\Form\FlagForm;
use Zend\Session\Container;

class FlagController extends AbstractActionController
{
    use\Flag\Manager\FlagManagerAwareTrait, \User\Manager\UserManagerAwareTrait;

    public function manageAction()
    {
        $flags = $this->getFlagManager()->findAllFlags();
        $view = new ViewModel(array(
            'flags' => $flags
        ));
        $view->setTemplate('flag/manage');
        return $view;
    }

    public function addAction()
    {
        $this->layout('layout/1-col');
        $types = $this->getFlagManager()->findAllTypes();
        $form = new FlagForm($types);
        if($this->getRequest()->isPost()){
            $form->setData($this->getRequest()
                ->getPost());
            if($form->isValid()){
                $data = $form->getData();
                $uuid = $this->params('id');
                $reporter = $this->getUserManager()->getUserFromAuthenticator();
                $this->getFlagManager()->addFlag((int) $data['type'], $data['content'], (int) $uuid, $reporter);
                $this->getFlagManager()->getObjectManager()->flush();
                
                $this->flashMessenger()->addSuccessMessage('The content has been flagged.');

                $this->redirect()->toUrl($this->referer()->fromStorage());
                return false;
            }
        }
        
        $this->referer()->store();
        
        $view = new ViewModel(array(
            'form' => $form
        ));
        $view->setTemplate('flag/add');
        return $view;
    }
    
    public function detailAction(){
        $id = (int) $this->params('id');
        $flag = $this->getFlagManager()->getFlag($id);
        $view = new ViewModel(array('flag' => $flag));
        $view->setTemplate('flag/detail');
        return $view;
    }

    public function removeAction()
    {
        
        $id = $this->params('id');
        $this->getFlagManager()->removeFlag((int) $id);   
        $this->getFlagManager()->getObjectManager()->flush();  
                
        $this->flashMessenger()->addSuccessMessage('Your action was successfull.');
           
        $this->redirect()->toReferer();
        return false;
    }
}