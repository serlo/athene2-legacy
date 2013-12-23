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
use User\Form\ChangePasswordForm;
use User\Exception\UserNotFoundException;
use Zend\Form\Form;

class UserController extends AbstractUserController
{
    use \Common\Traits\ConfigAwareTrait,\Common\Traits\AuthenticationServiceAwareTrait,\Common\Traits\ObjectManagerAwareTrait,\Language\Manager\LanguageManagerAwareTrait;

    public function getObjectManager()
    {
        return $this->getUserManager()->getObjectManager();
    }

    protected function getDefaultConfig()
    {
        return array(
            'forms' => array(
                'register' => 'User\Form\Register',
                'login' => 'User\Form\Login',
                'user_select' => 'User\Form\SelectUserForm',
                'restore_password' => 'User\Form\LostPassword',
                'settings' => 'User\Form\SettingsForm'
            )
        );
    }

    /**
     *
     * @var Form[]
     */
    protected $forms = array();

    /**
     *
     * @param Form $form            
     */
    public function getForm($name)
    {
        if (! array_key_exists($name, $this->forms)) {
            $form = $this->getOption('forms')[$name];
            if ($name == 'register' || $name = 'settings') {
                $this->forms[$name] = new $form($this->getObjectManager());
            } else {
                $this->forms[$name] = new $form();
            }
        }
        return $this->forms[$name];
    }

    /**
     *
     * @param string $name            
     * @param Form $form            
     * @return self
     */
    public function setForm($name, Form $form)
    {
        $this->forms[$name] = $form;
        return $this;
    }

    public function loginAction()
    {
        $form = $this->getForm('login');
        $errorMessages = false;
        $messages = array();
        
        $this->layout('layout/1-col');
        
        if ($this->getRequest()->isPost()) {
            
            $form->setData($this->params()
                ->fromPost());
            
            if ($form->isValid()) {
                $data = $form->getData();
                
                $adapter = $this->getAuthenticationService()->getAdapter();
                $adapter->setIdentity($data['email']);
                $adapter->setCredential($data['password']);
                
                $result = $this->getAuthenticationService()->authenticate();
                
                if ($result->isValid()) {
                    $user = $this->getUserManager()->getUser($result->getIdentity()
                        ->getId());
                    $user->updateLoginData();
                    
                    $this->getEventManager()->trigger('login', $this, array(
                        'user' => $user,
                        'email' => $data['email']
                    ));
                    
                    $user->persist();
                    $user->flush();
                    
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
        
        $form = $this->getForm('register');
        
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
                
                $user->persist();
                $user->flush();
                
                $this->redirect()->toUrl($this->params('ref', '/'));
                return '';
            }
        }
        
        $view = new ViewModel(array(
            'form' => $form
        ));
        return $view;
    }

    public function restorePasswordAction()
    {
        $messages = array();
        $view = new ViewModel();
        
        $this->layout('layout/1-col');
        
        if ($this->params('token', NULL) === NULL) {
            $form = $this->getForm('user_select');
            $form->setAttribute('action', $this->url()
                ->fromRoute('user/password/restore'));
            $view->setTemplate('user/user/reset-password/select');
            
            if ($this->getRequest()->isPost()) {
                $data = $this->params()->fromPost();
                $form->setData($data);
                if ($form->isValid()) {
                    try {
                        $user = $this->getUserManager()->findUserByEmail($data['email']);
                        $user->generateToken();
                        
                        $user->persist();
                        
                        $this->getEventManager()->trigger('restore-password', $this, array(
                            'user' => $user
                        ));
                        
                        $user->flush();
                        
                        $this->flashmessenger()->addSuccessMessage('You have been sent an email with instructions on how to restore your password!');
                        $this->redirect()->toRoute('home');
                    } catch (UserNotFoundException $e) {
                        $messages[] = 'Sorry, this email adress does not seem to be registered yet.';
                    }
                }
            }
        } else {
            $form = $this->getForm('restore_password');
            $form->setAttribute('action', $this->url()
                ->fromRoute('user/password/restore', array(
                'token' => $this->params('token')
            )));
            
            $user = $this->getUserManager()->findUserByToken($this->params('token'));
            
            $view->setTemplate('user/user/reset-password/restore');
            
            if ($this->getRequest()->isPost()) {
                $data = $this->params()->fromPost();
                $form->setData($data);
                if ($form->isValid()) {
                    $data = $form->getData();
                    $user->setPassword($data['password']);
                    $user->generateToken();
                    
                    $user->persist();
                    $user->flush();
                    
                    $this->redirect()->toRoute('user/login');
                }
            }
        }
        
        $view->setVariable('form', $form);
        $view->setVariable('messages', $messages);
        return $view;
    }

    public function activateAction()
    {
        try {
            $user = $this->getUserManager()->findUserByToken($this->params('token'));
            $role = $this->getUserManager()->findRoleByName('login');
            $user->addRole($role);
            $user->generateToken();
            $user->persist();
            $user->flush();
            $this->flashMessenger()->addSuccessMessage('Your account has been activated, you may now log in.');
        } catch (UserNotFoundException $e) {
            $this->flashMessenger()->addErrorMessage('I couldn\'t find an account by that token.');
        }
        
        $this->redirect()->toRoute('user/login');
        return false;
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
        $form = $this->getForm('settings');
        $form->setAttribute('action', $this->url()
            ->fromRoute('user/settings'));
        $user = $this->getUserManager()->getUserFromAuthenticator();
        
        if ($this->getRequest()->isPost()) {
            $data = $this->params()->fromPost();
            $form->setData($data);
            if ($form->isValid()) {
                $data = $form->getData();
                $user->setEmail($data['email']);
                $user->persist();
                $user->flush();
            }
        } else {
            $data = array(
                'email' => $user->getEmail()
            );
            $form->setData($data);
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
                
                $adapter = $this->getAuthenticationService()->getAdapter();
                $adapter->setIdentity($user->getEmail());
                $adapter->setCredential($data['currentPassword']);
                
                $result = $adapter->authenticate();
                
                if ($result->isValid()) {
                    $user->setPassword($data['password']);
                    $user->persist();
                    $user->flush();
                    $this->flashmessenger()->addSuccessMessage('Your password has successfully been changed.');
                    $this->redirect()->toRoute('user/me');
                    return '';
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
        $view = new ViewModel([
            'user' => $this->getUserManager()->getUser($this->params('id')),
            'unassociatedRoles' => $this->getUserManager()->getUnassociatedRoles($this->params('id'))
        ]);
        $this->layout('layout/1-col');
        $view->setTemplate('user/user/profile');
        return $view;
    }

    public function removeAction()
    {
        $user = $this->getUserManager()->getUser($this->params('id', null));
        $user->setTrashed(true);
        $user->persist();
        $user->flush();
        $this->redirect()->toReferer();
        return false;
    }

    public function purgeAction()
    {
        $this->getUserManager()->purgeUser($this->params('id', null));
        $this->getUserManager()
            ->getObjectManager()
            ->flush();
        $this->redirect()->toReferer();
        return false;
    }

    public function removeRoleAction()
    {
        $user = $this->getUserManager()->getUser($this->params('user'));
        $role = $this->getUserManager()->findRole($this->params('role'));
        $user->removeRole($role);
        
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
        return false;
    }

    public function addRoleAction()
    {
        $user = $this->getUserManager()->getUser($this->params('user'));
        $role = $this->getUserManager()->findRole($this->params('role'));
        $user->addRole($role);
        
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
        return false;
    }
}
