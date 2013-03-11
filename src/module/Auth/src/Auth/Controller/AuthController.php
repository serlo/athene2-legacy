<?php
namespace Auth\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class AuthController extends AbstractActionController
{

    private $loginForm;

    public function loginAction ()
    {
        $this->title()->set('Anmelden');
        
        if (! $this->loginForm)
            throw new \BadMethodCallException('Login Form not yet set!');
        
        if ($this->auth()->loggedIn())
            $this->redirect()->toRoute('home');
        
        if ($this->getRequest()->isPost()) {
            $this->loginForm->setData($this->getRequest()
                ->getPost());
            
            if ($this->loginForm->isValid()) {
                $data = $this->loginForm->getData();
                
                $authResult = $this->auth()->login($data['email'], $data['password']);
                
                if (! $authResult->isValid()) {
                    return new ViewModel(array(
                        'form' => $this->loginForm,
                        'loginError' => true
                    ));
                } else {
                    $this->flashMessenger()->addSuccessMessage("Login erfolgreich!");
                    $this->redirect()->toRoute('home');
                    return new ViewModel(array(
                        'form' => $this->loginForm,));
                }
            } else {
                return new ViewModel(array(
                    'form' => $this->loginForm
                ));
            }
        } else {
            
            return new ViewModel(array(
                'form' => $this->loginForm
            ));
        }
    }

    public function logoutAction ()
    {
        $this->auth()->logout();
        if(!$this->auth()->loggedIn())
            $this->flashMessenger()->addMessage("Logout erfolgreich!");
        else 
            $this->flashMessenger()->addErrorMessage("Logout fehlgeschlagen. Bitte probiere es nochmal!");
            
        $this->redirect()->toRoute('home');
    }

    public function setLoginForm ($loginForm)
    {
        $this->loginForm = $loginForm;
    }

    public function getLoginForm ()
    {
        return $this->loginForm;
    }
}
