<?php
/**
 * Athene2 - Advanced Learning Resources Manager
 *
 * @author    Aeneas Rekkas (aeneas.rekkas@serlo.org)
 * @license   LGPL-3.0
 * @license   http://opensource.org/licenses/LGPL-3.0 The GNU Lesser General Public License, version 3.0
 * @link      https://github.com/serlo-org/athene2 for the canonical source repository
 * @copyright Copyright (c) 2013-2014 Gesellschaft für freie Bildung e.V. (http://www.open-education.eu/)
 */
namespace Authentication\Controller;

use Authentication\Form\ActivateForm;
use Authorization\Service\RoleServiceAwareTrait;
use Authorization\Service\RoleServiceInterface;
use Common\Traits\AuthenticationServiceAwareTrait;
use User\Exception\UserNotFoundException;
use User\Form\ChangePasswordForm;
use User\Form\Login;
use User\Form\LostPassword;
use User\Form\SelectUserForm;
use User\Manager\UserManagerAwareTrait;
use User\Manager\UserManagerInterface;
use Zend\Authentication\AuthenticationService;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class AuthenticationController extends AbstractActionController
{
    use AuthenticationServiceAwareTrait, UserManagerAwareTrait;
    use RoleServiceAwareTrait;

    public function __construct(
        AuthenticationService $authenticationService,
        RoleServiceInterface $roleService,
        UserManagerInterface $userManager
    ) {
        $this->authenticationService = $authenticationService;
        $this->userManager           = $userManager;
        $this->roleService           = $roleService;
    }

    public function loginAction()
    {
        $form     = new Login();
        $messages = array();

        $this->layout('layout/1-col');

        if ($this->getRequest()->isPost()) {

            $post = $this->params()->fromPost();
            $form->setData($post);

            if ($form->isValid()) {
                $data    = $form->getData();
                $adapter = $this->getAuthenticationService()->getAdapter();

                $adapter->setIdentity($data['email']);
                $adapter->setCredential($data['password']);

                $result = $this->getAuthenticationService()->authenticate();

                if ($result->isValid()) {
                    $user = $this->getUserManager()->getUser(
                        $result->getIdentity()->getId()
                    );
                    $user->updateLoginData();

                    $this->getUserManager()->persist($user);
                    $this->getUserManager()->flush();

                    $this->redirect()->toUrl($this->params('ref', '/'));
                }
                $messages = $result->getMessages();
            }
        }

        $view = new ViewModel(array(
            'form'          => $form,
            'errorMessages' => $messages
        ));

        $view->setTemplate('authentication/login');

        return $view;
    }

    public function restorePasswordAction()
    {
        $messages = array();
        $view     = new ViewModel();

        $this->layout('layout/1-col');

        if (!$this->params('token', false)) {
            $form = new SelectUserForm();

            $view->setTemplate('authentication/reset-password/select');

            if ($this->getRequest()->isPost()) {
                $data = $this->params()->fromPost();
                $form->setData($data);
                if ($form->isValid()) {
                    try {
                        $user = $this->getUserManager()->findUserByEmail($data['email']);

                        $user->generateToken();

                        $this->getEventManager()->trigger(
                            'restore-password',
                            $this,
                            array(
                                'user' => $user
                            )
                        );

                        $this->getUserManager()->persist($user);
                        $this->getUserManager()->flush();

                        $this->flashmessenger()->addSuccessMessage(
                            'You have been sent an email with instructions on how to restore your password!'
                        );
                        $this->redirect()->toRoute('home');
                    } catch (UserNotFoundException $e) {
                        $messages[] = 'Sorry, this email adress does not seem to be registered yet.';
                    }
                }
            }
        } else {
            $form = new LostPassword();
            $url  = $this->url()->fromRoute(
                'authentication/password/restore',
                array(
                    'token' => $this->params('token')
                )
            );
            $form->setAttribute('action', $url);

            $user = $this->getUserManager()->findUserByToken($this->params('token'));

            $view->setTemplate('authentication/reset-password/restore');

            if ($this->getRequest()->isPost()) {
                $data = $this->params()->fromPost();
                $form->setData($data);
                if ($form->isValid()) {
                    $data = $form->getData();
                    $user->setPassword($data['password']);
                    $user->generateToken();

                    $this->getUserManager()->persist($user);
                    $this->getUserManager()->flush();

                    $this->redirect()->toRoute('authentication/login');
                }
            }
        }

        $view->setVariable('form', $form);
        $view->setVariable('messages', $messages);

        return $view;
    }


    public function logoutAction()
    {
        $this->getAuthenticationService()->clearIdentity();
        $this->redirect()->toReferer();

        return false;
    }


    public function activateAction()
    {
        if ($this->params('token', false)) {
            try {
                $user = $this->getUserManager()->findUserByToken($this->params('token'));
                $role = $this->getRoleService()->findRoleByName('login');
                $user->addRole($role);
                $user->generateToken();

                $this->getUserManager()->persist($user);
                $this->getUserManager()->flush();
                $this->flashMessenger()->addSuccessMessage('Your account has been activated, you may now log in.');

                $this->redirect()->toRoute('authentication/login');

                return false;
            } catch (UserNotFoundException $e) {
                $this->flashMessenger()
                    ->addErrorMessage('I couldn\'t find an account by that token. You can now try to re-activate your account.');
                $this->redirect()->toRoute('authentication/activate');

                return false;
            }
        } else {
            $form     = new ActivateForm();
            $messages = [];

            if ($this->getRequest()->isPost()) {
                $post = $this->params()->fromPost();
                $form->setData($post);
                if ($form->isValid()) {
                    $data = $form->getData();
                    try {
                        $user = $this->getUserManager()->findUserByEmail($data['email']);
                        $this->getEventManager()->trigger('activate', $this, ['user' => $user]);
                        $this->flashMessenger()->addSuccessMessage('Your have been sent an activation email.');
                        $this->redirect()->toRoute('authentication/login');

                        return false;
                    } catch (UserNotFoundException $e) {
                        $messages[] = 'No such user could be found.';
                    }
                }
            }

            $view = new ViewModel([
                'form'     => $form,
                'messages' => $messages
            ]);
            $view->setTemplate('authentication/activate');

            return $view;
        }
    }

    public function changePasswordAction()
    {
        $form     = new ChangePasswordForm();
        $user     = $this->getUserManager()->getUserFromAuthenticator();
        $messages = array();

        if ($this->getRequest()->isPost()) {

            $form->setData(
                $this->params()->fromPost()
            );

            if ($form->isValid()) {
                $data = $form->getData();

                $adapter = $this->getAuthenticationService()->getAdapter();
                $adapter->setIdentity($user->getEmail());
                $adapter->setCredential($data['currentPassword']);

                $result = $adapter->authenticate();

                if ($result->isValid()) {
                    $user->setPassword($data['password']);

                    $this->getUserManager()->persist($user);
                    $this->getUserManager()->flush();
                    $this->flashmessenger()->addSuccessMessage('Your password has successfully been changed.');
                    $this->redirect()->toRoute('user/me');

                    return false;
                }

                $messages = $result->getMessages();
            }
        }

        $view = new ViewModel(array(
            'user'     => $user,
            'form'     => $form,
            'messages' => $messages
        ));

        $this->layout('layout/1-col');
        $view->setTemplate('authentication/change-password');

        return $view;
    }
}
