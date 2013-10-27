<?php
/**
 *
 *
 * Athene2 - Advanced Learning Resources Manager
 *
 * @author Aeneas Rekkas (aeneas.rekkas@serlo.org)
 * @license LGPL-3.0
 * @license http://opensource.org/licenses/LGPL-3.0 The GNU Lesser General Public License, version 3.0
 * @link https://github.com/serlo-org/athene2 for the canonical source repository
 * @copyright Copyright (c) 2013 Gesellschaft für freie Bildung e.V. (http://www.open-education.eu/)
 */
namespace User\Controller;

use Zend\View\Model\ViewModel;
use User\Authentication\Adapter\AdapterInterface;
use User\Form\Login as LoginForm;
use User\Form\Register;
use User\Form\SettingsForm;
use User\Form\ChangePasswordForm;

class UserController extends AbstractUserController
{
    use \Common\Traits\AuthenticationServiceAwareTrait,\Common\Traits\ObjectManagerAwareTrait,\Language\Manager\LanguageManagerAwareTrait;

    protected function getObjectManager()
    {
        return $this->getUserManager()->getObjectManager();
    }

    /**
     *
     * @var AdapterInterface
     */
    private $authAdapter;

    /**
     *
     * @var Register
     */
    private $registerForm;

    /**
     *
     * @return \User\Form\Register $registerForm
     */
    public function getRegisterForm()
    {
        return $this->registerForm;
    }

    /**
     *
     * @param \User\Form\Register $registerForm            
     * @return $this
     */
    public function setRegisterForm(Register $registerForm)
    {
        $this->registerForm = $registerForm;
        return $this;
    }

    /**
     *
     * @return AdapterInterface $authAdapter
     */
    public function getAuthAdapter()
    {
        return $this->authAdapter;
    }

    /**
     *
     * @param AdapterInterface $authAdapter            
     * @return $this
     */
    public function setAuthAdapter(AdapterInterface $authAdapter)
    {
        $this->authAdapter = $authAdapter;
        return $this;
    }

    public function loginAction()
    {
        $form = new LoginForm();
        $errorMessages = false;
        $messages = array();
        
        $this->layout('layout/1-col');
        
        if ($this->getRequest()->isPost()) {
            
            $form->setData($this->params()
                ->fromPost());
            
            if ($form->isValid()) {
                $data = $form->getData();
                
                $this->getAuthAdapter()->setIdentity($data['email']);
                $this->getAuthAdapter()->setPassword($data['password']);
                
                $result = $this->getAuthenticationService()->authenticate($this->getAuthAdapter());
                if ($result->isValid()) {
                    $user = $this->getUserManager()->findUserByEmail($result->getIdentity());
                    $user->updateLoginData();
                    
                    $this->getEventManager()->trigger('login', $this, array(
                        'user' => $user,
                        'email' => $data['email']
                    ));
                    
                    $this->getUserManager()
                        ->getObjectManager()
                        ->flush();
                    
                    $this->redirect()->toUrl($this->params('ref', '/'));
                }
                $messages = $result->getMessages();
            }
        }
        $view = new ViewModel(array(
            'form' => $form,
            'errorMessages' => $messages
        ));
        return $view;
    }

    public function logoutAction()
    {
        $this->getAuthenticationService()->clearIdentity();
        $this->redirect()->toReferer();
        
        $this->getEventManager()->trigger('logout', $this, array());
        return '';
    }

    public function registerAction()
    {
        if ($this->getAuthenticationService()->hasIdentity())
            $this->redirect()->toReferer();
        
        $this->layout('layout/1-col');
        
        $form = $this->getRegisterForm();
        
        if ($this->getRequest()->isPost()) {
            $data = $this->params()->fromPost();
            $form->setData($data);
            if ($form->isValid()) {
                $user = $this->getUserManager()->createUser($form->getData());
                
                $this->getEventManager()->trigger('register', $this, array(
                    'user' => $user,
                    'language' => $this->getLanguageManager()
                        ->getLanguageFromRequest()
                        ->getEntity(),
                    'data' => $data
                ));
                
                $this->getUserManager()
                    ->getObjectManager()
                    ->flush();
                $this->redirect()->toUrl($this->params('ref', '/'));
                return '';
            }
        }
        
        $view = new ViewModel(array(
            'form' => $form
        ));
        return $view;
    }

    public function activateAction()
    {
        $user = $this->getUserManager()->findUserByToken($this->params('token'));
        $user->addRole('login');
        $user->generateToken();
        $this->getUserManager()
            ->getObjectManager()
            ->flush();
        $this->flashMessenger()->addSuccessMessage('Dein Konto wurde erfolgreich aktiviert. Du kannst dich nun einloggen.');
        $this->redirect()->toRoute('user/login');
        return '';
    }

    public function meAction()
    {
        $view = new ViewModel(array(
            'user' => $this->getUserManager()->getUserFromAuthenticator()
        ));
        $this->layout('layout/1-col');
        $view->setTemplate('user/user/profile');
        return $view;
    }

    public function settingsAction()
    {
        $form = new SettingsForm();
        $form->setAttribute('action', $this->url()
            ->fromRoute('user/settings'));
        $user = $this->getUserManager()->getUserFromAuthenticator();
        $data = array(
            'email' => $user->getEmail(),
            'givenname' => $user->getGivenname(),
            'lastname' => $user->getLastname(),
            'gender' => $user->getGender()
        );
        $form->setData($data);
        
        if ($this->getRequest()->isPost()) {
            $data = $this->params()->fromPost();
            $form->setData($data);
            if ($form->isValid()) {
                $data = $form->getData();
                $user->setGivenname($data['givenname']);
                $user->setLastname($data['lastname']);
                $user->setEmail($data['email']);
                $user->setGender($data['gender']);
                $this->getUserManager()
                    ->getObjectManager()
                    ->persist($user->getEntity());
                $this->getUserManager()
                    ->getObjectManager()
                    ->flush();
            }
        }
        
        $view = new ViewModel(array(
            'user' => $user,
            'form' => $form
        ));
        $view->setTemplate('user/user/settings');
        $this->layout('layout/1-col');
        return $view;
    }

    public function changePasswordAction()
    {
        $form = new ChangePasswordForm();
        $user = $this->getUserManager()->getUserFromAuthenticator();
        $messages = array();
        
        if ($this->getRequest()->isPost()) {
            
            $form->setData($this->params()
                ->fromPost());
            
            if ($form->isValid()) {
                $data = $form->getData();
                
                $this->getAuthAdapter()->setIdentity($user->getEmail());
                $this->getAuthAdapter()->setPassword($data['currentPassword']);
                
                $result = $this->getAuthAdapter()->authenticate();
                
                if ($result->isValid()) {
                    $user->setPassword($data['password']);
                    $user->getObjectManager()->flush();
                    $this->flashmessenger()->addSuccessMessage('Your password has successfully been changed.');
                    $this->redirect()->toRoute('user/me');
                }
                
                $messages = $result->getMessages();
            }
        }
        
        $view = new ViewModel(array(
            'user' => $user,
            'form' => $form,
            'messages' => $messages
        ));
        
        $this->layout('layout/1-col');
        $view->setTemplate('user/user/change-password');
        return $view;
    }

    public function profileAction()
    {
        $view = new ViewModel(array(
            'user' => $this->getUserManager()->getUser($this->params('id'))
        ));
        $this->layout('layout/1-col');
        $view->setTemplate('user/user/profile');
        return $view;
    }

    public function removeAction()
    {
        $this->getUserManager()->trashUser($this->params('id', null));
        $this->getUserManager()
            ->getObjectManager()
            ->flush();
    }

    public function purgeAction()
    {
        $this->getUserManager()->purgeUser($this->params('id', null));
        $this->getUserManager()
            ->getObjectManager()
            ->flush();
    }

    public function removeRoleAction()
    {
        $user = $this->getUserManager()->getUser($this->params('user'));
        $role = $this->getUserManager()->findRole($this->params('role'));
        $user->removeRole($this->params('role'));
        
        $this->getEventManager()->trigger('removeRole', $this, array(
            'actor' => $this->getUserManager()
                ->getUserFromAuthenticator(),
            'user' => $user,
            'role' => $role
        ));
        
        $this->getUserManager()
            ->getObjectManager()
            ->flush();
        $this->redirect()->toReferer();
        return '';
    }

    public function addRoleAction()
    {
        $user = $this->getUserManager()->getUser($this->params('user'));
        $role = $this->getUserManager()->findRole($this->params('role'));
        $user->addRole($this->params('role'));
        
        $this->getEventManager()->trigger('addRole', $this, array(
            'actor' => $this->getUserManager()
                ->getUserFromAuthenticator(),
            'user' => $user,
            'role' => $role
        ));
        
        $this->getUserManager()
            ->getObjectManager()
            ->flush();
        
        $this->redirect()->toReferer();
        return '';
    }
}