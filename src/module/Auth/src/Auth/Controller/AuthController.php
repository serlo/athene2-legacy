<?php
namespace Auth\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class AuthController extends AbstractActionController
{
    use\Common\Traits\ObjectManagerAwareTrait;

    protected $userService;

    protected $hashService;
    
    private $registerForm;

    private $loginForm;

    /**
     * @return field_type $registerForm
     */
    public function getRegisterForm ()
    {
        return $this->registerForm;
    }

	/**
     * @param field_type $registerForm
     * @return $this
     */
    public function setRegisterForm ($registerForm)
    {
        $this->registerForm = $registerForm;
        return $this;
    }

	/**
     *
     * @return field_type
     *         $hashService
     */
    public function getHashService ()
    {
        return $this->hashService;
    }

    /**
     *
     * @param field_type $hashService            
     * @return $this
     */
    public function setHashService ($hashService)
    {
        $this->hashService = $hashService;
        return $this;
    }

    /**
     *
     * @return field_type
     *         $userService
     */
    public function getUserService ()
    {
        return $this->userService;
    }

    /**
     *
     * @param field_type $userService            
     * @return $this
     */
    public function setUserService ($userService)
    {
        $this->userService = $userService;
        return $this;
    }

    public function loginAction ()
    {
        $this->title()->set('Anmelden');
        
        if (! $this->loginForm)
            throw new \BadMethodCallException('Login Form not yet set!');
        
        $from = $this->params()->fromQuery('ref', false);
        
        if ($from) {
            $this->loginForm->setAttribute('action', '/login?ref=' . $from);
        } else {
            $this->loginForm->setAttribute('action', '/login');
        }
        
        if ($this->auth()->loggedIn()) {
            if ($from) {
                $this->redirect()->toUrl($from);
            } else {
                $this->redirect()->toRoute('home');
            }
        }
        
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
                    
                    if ($from) {
                        $this->redirect()->toUrl($from);
                    } else {
                        $this->redirect()->toRoute('home');
                    }
                    
                    return new ViewModel(array(
                        'form' => $this->loginForm
                    ));
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
        if (! $this->auth()->loggedIn())
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

    public function registerAction ()
    {
        $form = new \Auth\Form\SignUp($this->getObjectManager());
        
        if ($this->getRequest()->isPost()) {
            $post = $this->getRequest()->getPost();
            $data = $post->getArrayCopy();
            $post->set('id',0);
            
            if (isset($data['password']))
                $data['password'] = $this->getHashService()->hash_password($data['password']);
            if (isset($data['passwordConfirm']))
                $data['passwordConfirm'] = $this->getHashService()->hash_password($data['passwordConfirm']);
            
            $form->setData($post);
            if ($form->isValid()) {
                $data = $form->getData();
                $this->getUserService()->create($form->getData());
                
                $params = compact(array(
                    'data',
                ));
                
                //$this->getEventManager()->trigger('signUpComplete', $this, $params);
                $this->flashmessenger()->addMessage('Du hast dich erfolgreich registriert und kannst dich nun einloggen.');
                
                $this->redirect()->toRoute('home');
                return false;
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
