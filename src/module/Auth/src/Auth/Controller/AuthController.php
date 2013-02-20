<?php
namespace Auth\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Auth\Service\AuthServiceInterface;

class AuthController extends AbstractActionController
{

    private $loginForm;

    public function loginAction ()
    {
        if (! $this->loginForm)
            throw new \BadMethodCallException('Login Form not yet set!');
        
        
        if ($this->getRequest()->isPost()) {
            $this->loginForm->setData($this->getRequest()
                ->getPost());
            
            if ($this->loginForm->isValid()) {
                $data = $this->loginForm->getData();
                
                $authResult = $this->auth()->login($data['username'], $data['password']);
                
                if (! $authResult->isValid()) {
                    return new ViewModel(array(
                        'form' => $this->loginForm,
                        'loginError' => true
                    ));
                } else
                    return new ViewModel(array(
                        'loginSuccess' => true,
                        'userLoggedIn' => $authResult->getIdentity()
                    ));
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

	public function setLoginForm ($loginForm)
    {
        $this->loginForm = $loginForm;
    }

    public function getLoginForm ()
    {
        return $this->loginForm;
    }
}
