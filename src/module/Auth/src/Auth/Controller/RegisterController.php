<?php
namespace Auth\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\EventManager\EventManagerInterface;
use Auth\Form\SignUp as SignUpForm;

class RegisterController extends AbstractActionController
{
    
    public function indexAction ()
    {
        $form = new SignUpForm();
        
        if ($this->getRequest()->isPost()) {
            $post = $this->getRequest()->getPost();
            $data = $post->getArrayCopy();
            $form->setData($post);
            
            if ($form->isValid()) {
                $params = compact(array('data','form'));
                $this->getEventManager()->trigger('signUpComplete', $this, $params);
                
                return new ViewModel(array(
                		'form' => $form
                ));
            } else {
                return new ViewModel(array(
                    'form' => $form
                ));
            }
        } else {
            return new ViewModel(array(
                'form' => $form
            ));
        }
    }
}